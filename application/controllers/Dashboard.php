<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
    function __construct(){
        parent::__construct();
		date_default_timezone_set('Asia/Jakarta'); 
		$this->load->model('Project','project');
    }
	
	public function index()
	{
		$id_operator = $this->session->userdata('idOperator');
		$project_list = $this->project->getList(array('where'=>array('idOperator'=>$id_operator)));

		$data = array(
			'content'	=>	'dashboard/main'
			,'tpl'		=>	'templates/main_template'
			,'project_list' => $project_list
		);
		
		$this->load->view($data['tpl'],$data);
	}

	public function setActiveProjectTo($id_project,$show_msg=true)
	{
		setActiveProject($id_project,$show_msg);
	}

	public function project()
	{
		$data = array(
			'title'	=>	'List Project'
			,'content'	=>	'dashboard/project'
		);
		
		$this->load->view($data['content'],$data);
	}

	public function projectList()
	{
		$id_operator = $this->session->userdata('idOperator');
		$draw = $this->input->post('draw')?:0;
		$start = $this->input->post('start');
		$rowperpage  = $this->input->post('length')?:0;
		$columnIndex   = $_POST['order'][0]['column'];
		$columnName    = $_POST['columns'][$columnIndex]['data'];;
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value
		
		$where = array();
		
		$where["(tbl.\"Deskripsi\" ~* '$searchValue'
			)"] = NULL;
		$where['idOperator']	=	$id_operator;
		$params = array(
         'select' => array('tbl.*'),
         'auto_quotes' => false,
         'from' => 'Project tbl',
		 'count' => true,
         'where' => $where,
         'or_where' => array(),
         'order_by' => array($columnName=>$columnSortOrder),
		 'join' => array(),
         'offset' => $start,
         'items_per_page' => $rowperpage);
		$getList = $this->project->getList($params,1);
		// echo $this->db->last_query();
		$response = array(
		  "post" => $this->input->post(),
		  "draw" => intval($draw),
		  "iTotalRecords" => $getList['count_all'],
		  "iTotalDisplayRecords" => $getList['count_all'],
		  "aaData" => $getList['result_array']
		);

		header('Content-type: application/json');
		echo json_encode($response);
	}

	public function saveProject()
	{
		$data['idOperator']  = $this->session->userdata('idOperator');
		$data['ProjectID'] 	= $this->input->post('t_project_id')?:0;
		$data['Deskripsi'] = validate_input($this->input->post('t_description'),array('name'=>'deskripsi','required'=>true));
		
		$save = $this->project->upsert($data);
		$new_proj_id = $save['data']['ProjectID'];
		if($save['action_code'] == 1)
		$this->setActiveProjectTo($new_proj_id,0);
		$this->result['action_code'] = $save['action_code'];
		$this->success('data berhasil disimpan');
	}

	public function deleteProject($id)
	{
		$id_operator = $this->session->userdata('idOperator');
		$this->db->trans_begin();

		$this->db->delete('Alternatif', array('ProjectID'=>$id));
		$this->db->delete('BobotAlternatif', array('ProjectID'=>$id));
		$this->db->delete('BobotKriteria', array('ProjectID'=>$id));
		$this->db->delete('BobotSubKriteria', array('ProjectID'=>$id));
		$this->db->delete('FuzzyAlternatif', array('ProjectID'=>$id));
		$this->db->delete('FuzzyKriteria', array('ProjectID'=>$id));
		$this->db->delete('Kriteria', array('ProjectID'=>$id));
		$this->db->delete('PairWiseAlternatif', array('ProjectID'=>$id));
		$this->db->delete('PairWiseAlternatifSummary', array('ProjectID'=>$id));
		$this->db->delete('PairWiseKriteria', array('ProjectID'=>$id));
		$this->db->delete('PairWiseKriteriaSummary', array('ProjectID'=>$id));
		$this->project->delete(array('ProjectID'=>$id));
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$error = $this->db->error();
			$this->error($error);
		}
		else
		{
			$this->db->trans_commit();
			$getList = $this->project->getList(array('where'=>array('idOperator'=>$id_operator)))['row_array'];
			if($getList['ProjectID'])
			{
				$this->setActiveProjectTo($getList['ProjectID'],0);
			}else
			{
				$this->setActiveProjectTo(0,0);
			}
			$this->success('data dihapus');
		}
	}
}

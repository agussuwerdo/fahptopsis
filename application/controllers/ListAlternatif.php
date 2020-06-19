<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ListAlternatif extends MY_Controller {
	
    function __construct(){
        parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Project','project');
		$this->load->model('Alternatif','alternatif');
    }
	
	public function index()
	{
		$id_operator = $this->session->userdata('idOperator');
		$project_list = $this->project->getList(array('where'=>array('idOperator'=>$id_operator)));

		$data = array(
			'content'	=>	'alternatif/main'
			,'title'		=>	'List Alternatif'
			,'tpl'		=>	'templates/main_template'
			,'project_list' => $project_list
		);
		
		$this->load->view($data['tpl'],$data);
	}
	
	public function alternatifPage()
	{
		$draw = $this->input->post('draw')?:0;
		$start = $this->input->post('start');
		$rowperpage  = $this->input->post('length')?:0;
		$columnIndex   = $_POST['order'][0]['column'];
		$columnName    = $_POST['columns'][$columnIndex]['data'];;
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value
		
		$where = array();
		
		$where["(tbl.\"KodeAlternatif\" ~* '$searchValue'
		OR tbl.\"NamaAlternatif\" ~* '$searchValue'
			)"] = NULL;
		$where['tbl.ProjectID'] = $this->session->userdata('ProjectID');

		$params = array(
         'select' => array('tbl.*'),
         'auto_quotes' => false,
         'from' => 'Alternatif tbl',
		 'count' => true,
         'where' => $where,
         'or_where' => array(),
         'order_by' => array($columnName=>$columnSortOrder),
		 'join' => array(),
         'offset' => $start,
         'items_per_page' => $rowperpage);
		$getList = $this->alternatif->getList($params,1);
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

	public function addAlternatif($id='')
	{
		$head = array();
		$where = array();
		$where['ProjectID'] = $this->session->userdata('ProjectID');
		$where['KodeAlternatif']	= $id;

		$getList = $this->alternatif->getList(array('where'=>$where),1);
		$head = $getList['row_array'];
		$data = array(
			'head'	=>	$head
			,'content'	=>	'alternatif/input'
		);
		
		$this->load->view($data['content'],$data);
	}

	public function saveAlternatif()
	{
		$prefix 				= 'A';
		$data['ProjectID'] 		= $this->session->userdata('ProjectID');
		$data['KodeAlternatif'] = $this->input->post('t_kode_alternatif')?:$this->alternatif->get_last_auto_number($prefix);
		$data['NamaAlternatif'] = validate_input($this->input->post('t_nama_alternatif'),array('name'=>'Nama Alternatif','required'=>true));
		
		$this->alternatif->upsert($data);
		$this->project->update_count_project($data['ProjectID']);
		
		$this->success('data berhasil disimpan');
	}
	
	public function removeAlternatif($id)
	{
		$this->db->trans_begin();

		$this->db->delete('BobotKriteria', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('BobotSubKriteria', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('FuzzyKriteria', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('PairWiseKriteria', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('PairWiseKriteriaSummary', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('BobotAlternatif', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('FuzzyAlternatif', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('PairWiseAlternatif', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('PairWiseAlternatifSummary', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->alternatif->delete(array('ProjectID'=>$this->session->userdata('ProjectID'),'KodeAlternatif'=>$id));

		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$error = $this->db->error();
			$this->error($error);
		}
		else
		{
			$this->db->trans_commit();
			$this->project->update_count_project($this->session->userdata('ProjectID'));
			$this->success('data dihapus');
		}
	}
}

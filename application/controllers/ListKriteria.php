<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ListKriteria extends MY_Controller {
	
    function __construct(){
        parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Project','project');
		$this->load->model('Kriteria','kriteria');
    }
	
	public function index()
	{
		$id_operator = $this->session->userdata('idOperator');
		$project_list = $this->project->getList(array('where'=>array('idOperator'=>$id_operator)));
		$data = array(
			'content'	=>	'kriteria/main'
			,'tpl'		=>	'templates/main_template'
			,'project_list' => $project_list
		);
		
		$this->load->view($data['tpl'],$data);
	}
	
	public function getMainKriteria()
	{
		$search_text = strtoupper($this->input->post('t_search_text'));
		$where = array();
		$where['
		(upper("KodeKriteria") like '.quotedStr('%'.$search_text.'%').'
		or upper("NamaKriteria") like '.quotedStr('%'.$search_text.'%').')
		'] = null;
		$where['ProjectID'] = $this->session->userdata('ProjectID');
		$list_data = $this->kriteria->getList(array('where'=>$where,'order_by'=>array('KodeKriteria'=>'ASC')));
		$data = array(
			'content'	=>	'kriteria/list'
			,'list_data' => $list_data
		);
		
		$this->load->view($data['content'],$data);

	}

	public function addKriteria($id='')
	{
		$head = array();
		$where = array();
		$where['ProjectID'] = $this->session->userdata('ProjectID');
		$where['KodeKriteria']	= $id;

		$getList = $this->kriteria->getList(array('where'=>$where),1);
		$head = $getList['row_array'];
		$data = array(
			'head'	=>	$head
			,'content'	=>	'kriteria/input'
		);
		
		$this->load->view($data['content'],$data);
	}
	
	public function addSubKriteria($id_parent='',$id='')
	{
		$head = array();
		$where = array();
		$where['ProjectID'] = $this->session->userdata('ProjectID');
		$where['KodeKriteria']	= $id;

		$getList = $this->kriteria->getList(array('where'=>$where),1);
		$getParent = $this->kriteria->getList(array('where'=>array('KodeKriteria'=>$id_parent)));
		$head = $getList['row_array'];
		$data = array(
			'head'	=>	$head
			,'id_parent'	=>	$id_parent
			,'id_parent_name'	=>	$getParent['row_array']['NamaKriteria']
			,'content'	=>	'kriteria/input_sub'
		);
		
		$this->load->view($data['content'],$data);
	}

	public function saveKriteria()
	{
		$level 					= 0;
		$prefix 				= 'C'.$level;
		$data['ProjectID'] 		= $this->session->userdata('ProjectID');
		$data['KodeKriteria'] 	= $this->input->post('t_kode_kriteria')?:$this->kriteria->get_last_auto_number($prefix);
		$data['NamaKriteria'] 	= validate_input($this->input->post('t_nama_kriteria'),array('name'=>'Nama Kriteria','required'=>true));
		$data['Atribut'] 		= $this->input->post('t_atribut');
		$data['KriteriaLevel'] 	= $level;
		
		$this->kriteria->upsert($data);
		$this->project->update_count_project($data['ProjectID']);
		$this->success('data berhasil disimpan');
	}

	public function saveSubKriteria()
	{
		$level 						= 1;
		$prefix 					= 'C'.$level;
		$data['ProjectID'] 			= $this->session->userdata('ProjectID');
		$data['KodeKriteria'] 		= $this->input->post('t_kode_kriteria')?:$this->kriteria->get_last_auto_number($prefix);
		$data['NamaKriteria'] 		= validate_input($this->input->post('t_nama_kriteria'),array('name'=>'Nama Kriteria','required'=>true));
		$data['KodeKriteriaParent'] = validate_input($this->input->post('t_kode_kriteria_parent'),array('name'=>'Kode Kriteria Parent','required'=>true));
		$data['KriteriaLevel'] 	= $level;
		
		$this->kriteria->upsert($data);
		$this->project->update_count_project($data['ProjectID']);
		
		$this->success('data berhasil disimpan');
	}

	public function removeKriteria($id)
	{
		$this->db->trans_begin();

		$this->kriteria->delete(array('ProjectID'=>$this->session->userdata('ProjectID'),'KodeKriteria'=>$id));
		$this->kriteria->delete(array('ProjectID'=>$this->session->userdata('ProjectID'),'KodeKriteriaParent'=>$id));

		$this->db->delete('BobotKriteria', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('BobotSubKriteria', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('FuzzyKriteria', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('PairWiseKriteria', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('PairWiseKriteriaSummary', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('BobotAlternatif', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('FuzzyAlternatif', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('PairWiseAlternatif', array('ProjectID'=>$this->session->userdata('ProjectID')));
		$this->db->delete('PairWiseAlternatifSummary', array('ProjectID'=>$this->session->userdata('ProjectID')));
		
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

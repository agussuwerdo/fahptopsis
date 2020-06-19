<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class nilaiKriteria extends MY_Controller {
		
    function __construct(){
        parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Project','project');
		$this->load->model('Kriteria','kriteria');
		$this->load->model('FuzzyMaster','fuzzymaster');
		$this->load->model('PairWiseKriteria','pairwisekriteria');
		$this->load->model('PairWiseKriteriaSummary','pairwisekriteriasummary');
		$this->load->model('FuzzyKriteria','fuzzykriteria');
    }
	
	public function index()
	{
		$id_operator = $this->session->userdata('idOperator');
		$project_list = $this->project->getList(array('where'=>array('idOperator'=>$id_operator)));
		$data = array(
			'content'	=>	'nilaiKriteria/main'
			,'tpl'		=>	'templates/main_template'
			,'project_list' => $project_list
		);
		
		$this->load->view($data['tpl'],$data);
	}
	
	public function getPairWiseData()
	{
		$id_project = $this->session->userdata('ProjectID');
		if(!$id_project)
		{
			$this->error('silahkan pilih project');
		}
		$pair_wise = $this->kriteria->getPairWise();
		$pairwise_list = $this->fuzzymaster->getList(array('order_by'=>array('FuzzyID'=>'ASC')))['result_array'];
		
		$where = array();
		$where['ProjectID']	= $id_project;	
		$existing_pairwise_data = $this->pairwisekriteria->getList(array('where'=>$where))['result_array'];
		$tuned_pairwise_data = array();
		foreach($existing_pairwise_data as $key=>$row)
		{
			$tuned_pairwise_data[$row['Left']][$row['Right']][$row['FuzzyID']] = $row;
		}
		

		$pairwise_data = array();
		foreach($pair_wise as $key_pair=>$row_pair){
            $left   = $row_pair['Left'];
            $right  = $row_pair['Right'];
            foreach($pairwise_list as $key_pairwise=>$row_pairwise){
				$pairwise_id = $row_pairwise['FuzzyID'];
				$pairwise_data[$left][$right][$pairwise_id] = isset($tuned_pairwise_data[$left][$right][$pairwise_id])?$tuned_pairwise_data[$left][$right][$pairwise_id]['Value']:0;
			}
		}
		
		// print_r($pairwise_data);
		$data = array(
			'content'	=>	'nilaiKriteria/list'
			,'pair_wise' => $pair_wise
			,'pairwise_list' => $pairwise_list
			,'pairwise_data' => $pairwise_data
		);
		
		$this->load->view($data['content'],$data);
	}

	function submitData()
	{
		$pairwise = $this->input->post('pairwise');
		$data_save = array();
		$id_project = $this->session->userdata('ProjectID');
		$data_save_formatted = array();
		$data_save_fuzzy_formatted = array();
		$data_save_summary = array();
		$summary_recnum =0;
		$count = 0;
		$integrasi =0;
		$pair_left_right = '';
		foreach($pairwise as $keyrowleft=>$rowleft)
		{
			foreach($rowleft as $keyrowright=>$rowright)
			{
				foreach($rowright as $keyrowpairwise=>$rowpairwise)
				{
					if(($keyrowleft.$keyrowright) != $pair_left_right)
					{
						$pair_left_right = $keyrowleft.$keyrowright;
						$summary_recnum++;
						$count = 0;
						$integrasi = 0;
					}
					$count = $count+($rowpairwise?:0);
					if($count>0)
					{
						$integrasi = 1/$count;
					}
					$data_save['ProjectID']	= $id_project;
					$data_save['FuzzyID']	= $keyrowpairwise;
					$data_save['Left']		= $keyrowleft;
					$data_save['Right']		= $keyrowright;
					$data_save['Value']		= $rowpairwise?:0;
					$data_save_formatted[]	= $data_save;

					$data_save_fuzzy['ProjectID']	= $id_project;
					$data_save_fuzzy['FuzzyID']		= $keyrowpairwise;
					$data_save_fuzzy['Left']		= $keyrowleft;
					$data_save_fuzzy['Right']		= $keyrowright;
					$data_save_fuzzy['ValueL']		= $rowpairwise?:0;
					$data_save_fuzzy['ValueM']		= $rowpairwise?:0;
					$data_save_fuzzy['ValueH']		= $rowpairwise?:0;
					$data_save_fuzzy_formatted[]	= $data_save_fuzzy;

					$data_save_summary[$summary_recnum]['ProjectID']	= $id_project;
					$data_save_summary[$summary_recnum]['Left']			= $keyrowleft;
					$data_save_summary[$summary_recnum]['Right']		= $keyrowright;
					$data_save_summary[$summary_recnum]['Count']		= $count;
					$data_save_summary[$summary_recnum]['Integrasi']	= $integrasi;
				}
			}
		}
		// print_r($data_save_summary);
		$this->pairwisekriteria->delete(array('ProjectID'=>$id_project));
		$this->fuzzykriteria->delete(array('ProjectID'=>$id_project));
		$this->pairwisekriteriasummary->delete(array('ProjectID'=>$id_project));

		$this->db->insert_batch('PairWiseKriteria', $data_save_formatted);
		$this->db->insert_batch('FuzzyKriteria', $data_save_fuzzy_formatted);
		$this->db->insert_batch('PairWiseKriteriaSummary', $data_save_summary);

		$this->success('data disimpan');
	}
	
}

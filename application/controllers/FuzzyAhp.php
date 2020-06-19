<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FuzzyAhp extends MY_Controller {
	
    function __construct(){
        parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Project','project');
		$this->load->model('BobotKriteria','bobotkriteria');
		$this->load->model('BobotSubKriteria','bobotsubkriteria');
		$this->load->model('BobotAlternatif','bobotalternatif');
		$this->load->model('Kriteria','kriteria');
		$this->load->model('Alternatif','alternatif');
		$this->load->model('FuzzyKriteria','fuzzykriteria');
		$this->load->model('FuzzyMaster','fuzzymaster');
		$this->load->model('FuzzyAlternatif','fuzzyalternatif');
		$this->load->model('PairWiseKriteriaSummary','pairwisekriteriasummary');
		$this->load->model('PairWiseAlternatifSummary','pairwisealternatifsummary');
		$this->load->model('RandomIndeks','randomindeks');
    }
	
	public function index()
	{
		$id_operator = $this->session->userdata('idOperator');
		$project_list = $this->project->getList(array('where'=>array('idOperator'=>$id_operator)));

		$data = array(
			'content'	=>	'fuzzyAhp/main'
			,'tpl'		=>	'templates/main_template'
			,'project_list' => $project_list
		);
		
		$this->load->view($data['tpl'],$data);
	}

	public function getDataKriteria()
	{
		$id_project 	= $this->session->userdata('ProjectID');
		$kriteria_list 	= $this->kriteria->getList(array('where'=>array('ProjectID'=>$id_project,'KriteriaLevel'=>1),'order_by'=>array('KodeKriteria'=>'ASC')));
		$alternatif_list = $this->alternatif->getList(array('where'=>array('ProjectID'=>$id_project),'order_by'=>array('KodeAlternatif'=>'ASC')));
		$fuzzy_master_list= $this->fuzzymaster->getList(array('order_by'=>array('FuzzyID'=>'ASC')));
		$fuzzy_list 	= $this->fuzzykriteria->getList(array('where'=>array('ProjectID'=>$id_project)));
		$kriteria_sum_list 	= $this->pairwisekriteriasummary->getList(array('where'=>array('ProjectID'=>$id_project)));
		$get_random_indeks	= $this->randomindeks->getList(array('where'=>array('CountKriteria'=>count($kriteria_list['result_array']))))['row_array'];
		$fuzzy_master = array();
		foreach($fuzzy_master_list['result_array'] as $key_fuzzy=>$row_fuzzy){
            $fuzzy_id   = $row_fuzzy['FuzzyID'];
			$data['FuzzyID']	= $row_fuzzy['FuzzyID'];
			$data['value']		= $row_fuzzy['value'];
			$data['FuzzyType']	= $row_fuzzy['FuzzyType'];
			$data['Description']= $row_fuzzy['Description'];
			$data['U']			= $row_fuzzy['up'];
			$data['M']			= $row_fuzzy['middle'];
			$data['L']			= $row_fuzzy['low'];
			$fuzzy_master[$fuzzy_id] = $data;
		}
		$kriteria_sum = array();
		foreach($kriteria_sum_list['result_array'] as $key_sum=>$row_sum){
            $left   = $row_sum['Left'];
			$right  = $row_sum['Right'];
			$data['Integrasi'] = $row_sum['Integrasi'];
			$data['Count'] = $row_sum['Count'];
			
			$kriteria_sum[$left][$right] = $data;
		}
		$fuzzy_data = array();
		foreach($fuzzy_list['result_array'] as $key_fuzzy=>$row_fuzzy){
            $left   = $row_fuzzy['Left'];
			$right  = $row_fuzzy['Right'];
			$data['FuzzyID']	= $row_fuzzy['FuzzyID'];
			$data['L']		= $row_fuzzy['ValueL'];
			$data['M']		= $row_fuzzy['ValueM'];
			$data['U']		= $row_fuzzy['ValueH'];
			foreach($fuzzy_master as $key=>$row)
			{
				$data[$row['FuzzyID']] = $row;
			}
			$fuzzy_data[$left][$right][$row_fuzzy['FuzzyID']] = $data;
		}
		$kriteria_data = array();
		$kriteria_code = array();
		$cr_table = array();
		foreach($kriteria_list['result_array'] as $key_kriteria=>$row_kriteria)
		{
			$kriteria_code[$row_kriteria['KodeKriteria']] = array('Kode'=>$row_kriteria['KodeKriteria'],'NamaKriteria'=>$row_kriteria['NamaKriteria'],'L'=>0,'M'=>0,'U'=>0,'Min'=>0);
			$left   = $row_kriteria['KodeKriteria'];
			$data_cr['Kode'] = $left;
			$data_cr['SumM'] = 0;
			$data_cr['SumNormalize'] = 0;
			$data_cr['VektorPrioritas'] = 0;
			$data_cr['BobotKriteria'] = 0;
			$data_cr['BobotKriteriaNormalisasi'] = 0;
			$cr_table[$left] = $data_cr;
			foreach($kriteria_list['result_array'] as $key_kriteria_up=>$row_kriteria_up){
				$up = $row_kriteria_up['KodeKriteria'];
				$kriteria_data[$left][$up]['CrNormalize'] = 0;
				$integrasi = isset($kriteria_sum[$left][$up]['Integrasi'])?$kriteria_sum[$left][$up]['Integrasi']:0;
				$integrasi_inverse = isset($kriteria_sum[$up][$left]['Integrasi'])?$kriteria_sum[$up][$left]['Integrasi']:0;
				if($left==$up)
				{
					$kriteria_data[$left][$up]['L'] = 1;
					$kriteria_data[$left][$up]['M'] = 1;
					$kriteria_data[$left][$up]['U'] = 1;
				}else{
					if(isset($fuzzy_data[$left][$up][1]['L']))
					{
						$fuzzy_value = array();
						$fuzzy_low = 1;
						$fuzzy_mid = 1;
						$fuzzy_up = 1;
						foreach($fuzzy_master_list['result_array'] as $kx=>$rx)
						{
							$fuzzy_value[$rx['FuzzyID']]['L'] = $fuzzy_master[$rx['FuzzyID']]['L']**$fuzzy_data[$left][$up][$rx['FuzzyID']]['L'];
							$fuzzy_value[$rx['FuzzyID']]['M'] = $fuzzy_master[$rx['FuzzyID']]['M']**$fuzzy_data[$left][$up][$rx['FuzzyID']]['M'];
							$fuzzy_value[$rx['FuzzyID']]['U'] = $fuzzy_master[$rx['FuzzyID']]['U']**$fuzzy_data[$left][$up][$rx['FuzzyID']]['U'];
							$fuzzy_low = $fuzzy_low * $fuzzy_value[$rx['FuzzyID']]['L'];
							$fuzzy_mid = $fuzzy_mid * $fuzzy_value[$rx['FuzzyID']]['M'];
							$fuzzy_up = $fuzzy_up * $fuzzy_value[$rx['FuzzyID']]['U'];
						}
						$kriteria_data[$left][$up]['L'] = $fuzzy_low**$integrasi;
						$kriteria_data[$left][$up]['M'] = $fuzzy_mid**$integrasi;
						$kriteria_data[$left][$up]['U'] = $fuzzy_up**$integrasi;
					}else{
						$kriteria_data[$left][$up]['L'] = -1;
						$kriteria_data[$left][$up]['M'] = -1;
						$kriteria_data[$left][$up]['U'] = -1;
						
						if(!isset($fuzzy_data[$up][$left]))
						{
							echo 'Mohon maaf, nilai kriteria / alternatif belum lengkap, silahkan lengkapi nilai terlebih dahulu';
							die;
						}
						
						$fuzzy_value = array();
						$fuzzy_low = 1;
						$fuzzy_mid = 1;
						$fuzzy_up = 1;
						$fuzzy_value[1]['L']	= $fuzzy_master[1]['L']**$fuzzy_data[$up][$left][17]['L'];
						$fuzzy_value[2]['L']	= $fuzzy_master[17]['L']**$fuzzy_data[$up][$left][1]['L'];
						$fuzzy_value[3]['L']	= $fuzzy_master[16]['L']**$fuzzy_data[$up][$left][2]['L'];
						$fuzzy_value[4]['L']	= $fuzzy_master[15]['L']**$fuzzy_data[$up][$left][3]['L'];
						$fuzzy_value[5]['L']	= $fuzzy_master[14]['L']**$fuzzy_data[$up][$left][4]['L'];
						$fuzzy_value[6]['L']	= $fuzzy_master[13]['L']**$fuzzy_data[$up][$left][5]['L'];
						$fuzzy_value[7]['L']	= $fuzzy_master[12]['L']**$fuzzy_data[$up][$left][6]['L'];
						$fuzzy_value[8]['L']	= $fuzzy_master[11]['L']**$fuzzy_data[$up][$left][7]['L'];
						$fuzzy_value[9]['L']	= $fuzzy_master[10]['L']**$fuzzy_data[$up][$left][8]['L'];
						$fuzzy_value[10]['L']	= $fuzzy_master[9]['L']**$fuzzy_data[$up][$left][9]['L'];
						$fuzzy_value[11]['L']	= $fuzzy_master[8]['L']**$fuzzy_data[$up][$left][10]['L'];
						$fuzzy_value[12]['L']	= $fuzzy_master[7]['L']**$fuzzy_data[$up][$left][11]['L'];
						$fuzzy_value[13]['L']	= $fuzzy_master[6]['L']**$fuzzy_data[$up][$left][12]['L'];
						$fuzzy_value[14]['L']	= $fuzzy_master[5]['L']**$fuzzy_data[$up][$left][13]['L'];
						$fuzzy_value[15]['L']	= $fuzzy_master[4]['L']**$fuzzy_data[$up][$left][14]['L'];
						$fuzzy_value[16]['L']	= $fuzzy_master[3]['L']**$fuzzy_data[$up][$left][15]['L'];
						$fuzzy_value[17]['L']	= $fuzzy_master[2]['L']**$fuzzy_data[$up][$left][16]['L'];
						
						$fuzzy_value[1]['M']	= $fuzzy_master[1]['M']**$fuzzy_data[$up][$left][17]['M'];
						$fuzzy_value[2]['M']	= $fuzzy_master[17]['M']**$fuzzy_data[$up][$left][1]['M'];
						$fuzzy_value[3]['M']	= $fuzzy_master[16]['M']**$fuzzy_data[$up][$left][2]['M'];
						$fuzzy_value[4]['M']	= $fuzzy_master[15]['M']**$fuzzy_data[$up][$left][3]['M'];
						$fuzzy_value[5]['M']	= $fuzzy_master[14]['M']**$fuzzy_data[$up][$left][4]['M'];
						$fuzzy_value[6]['M']	= $fuzzy_master[13]['M']**$fuzzy_data[$up][$left][5]['M'];
						$fuzzy_value[7]['M']	= $fuzzy_master[12]['M']**$fuzzy_data[$up][$left][6]['M'];
						$fuzzy_value[8]['M']	= $fuzzy_master[11]['M']**$fuzzy_data[$up][$left][7]['M'];
						$fuzzy_value[9]['M']	= $fuzzy_master[10]['M']**$fuzzy_data[$up][$left][8]['M'];
						$fuzzy_value[10]['M']	= $fuzzy_master[9]['M']**$fuzzy_data[$up][$left][9]['M'];
						$fuzzy_value[11]['M']	= $fuzzy_master[8]['M']**$fuzzy_data[$up][$left][10]['M'];
						$fuzzy_value[12]['M']	= $fuzzy_master[7]['M']**$fuzzy_data[$up][$left][11]['M'];
						$fuzzy_value[13]['M']	= $fuzzy_master[6]['M']**$fuzzy_data[$up][$left][12]['M'];
						$fuzzy_value[14]['M']	= $fuzzy_master[5]['M']**$fuzzy_data[$up][$left][13]['M'];
						$fuzzy_value[15]['M']	= $fuzzy_master[4]['M']**$fuzzy_data[$up][$left][14]['M'];
						$fuzzy_value[16]['M']	= $fuzzy_master[3]['M']**$fuzzy_data[$up][$left][15]['M'];
						$fuzzy_value[17]['M']	= $fuzzy_master[2]['M']**$fuzzy_data[$up][$left][16]['M'];

						$fuzzy_value[1]['U']	= $fuzzy_master[1]['U']**$fuzzy_data[$up][$left][17]['U'];
						$fuzzy_value[2]['U']	= $fuzzy_master[17]['U']**$fuzzy_data[$up][$left][1]['U'];
						$fuzzy_value[3]['U']	= $fuzzy_master[16]['U']**$fuzzy_data[$up][$left][2]['U'];
						$fuzzy_value[4]['U']	= $fuzzy_master[15]['U']**$fuzzy_data[$up][$left][3]['U'];
						$fuzzy_value[5]['U']	= $fuzzy_master[14]['U']**$fuzzy_data[$up][$left][4]['U'];
						$fuzzy_value[6]['U']	= $fuzzy_master[13]['U']**$fuzzy_data[$up][$left][5]['U'];
						$fuzzy_value[7]['U']	= $fuzzy_master[12]['U']**$fuzzy_data[$up][$left][6]['U'];
						$fuzzy_value[8]['U']	= $fuzzy_master[11]['U']**$fuzzy_data[$up][$left][7]['U'];
						$fuzzy_value[9]['U']	= $fuzzy_master[10]['U']**$fuzzy_data[$up][$left][8]['U'];
						$fuzzy_value[10]['U']	= $fuzzy_master[9]['U']**$fuzzy_data[$up][$left][9]['U'];
						$fuzzy_value[11]['U']	= $fuzzy_master[8]['U']**$fuzzy_data[$up][$left][10]['U'];
						$fuzzy_value[12]['U']	= $fuzzy_master[7]['U']**$fuzzy_data[$up][$left][11]['U'];
						$fuzzy_value[13]['U']	= $fuzzy_master[6]['U']**$fuzzy_data[$up][$left][12]['U'];
						$fuzzy_value[14]['U']	= $fuzzy_master[5]['U']**$fuzzy_data[$up][$left][13]['U'];
						$fuzzy_value[15]['U']	= $fuzzy_master[4]['U']**$fuzzy_data[$up][$left][14]['U'];
						$fuzzy_value[16]['U']	= $fuzzy_master[3]['U']**$fuzzy_data[$up][$left][15]['U'];
						$fuzzy_value[17]['U']	= $fuzzy_master[2]['U']**$fuzzy_data[$up][$left][16]['U'];
						
						$fuzzy_low = $fuzzy_value[1]['L']*$fuzzy_value[2]['L']*$fuzzy_value[3]['L']*$fuzzy_value[4]['L']*$fuzzy_value[5]['L']*$fuzzy_value[6]['L']*$fuzzy_value[7]['L']*$fuzzy_value[8]['L']*$fuzzy_value[9]['L']*$fuzzy_value[10]['L']*$fuzzy_value[11]['L']*$fuzzy_value[12]['L']*$fuzzy_value[13]['L']*$fuzzy_value[14]['L']*$fuzzy_value[15]['L']*$fuzzy_value[16]['L']*$fuzzy_value[17]['L'];
						$kriteria_data[$left][$up]['L'] = $fuzzy_low**$integrasi_inverse;

						$fuzzy_mid = $fuzzy_value[1]['M']*$fuzzy_value[2]['M']*$fuzzy_value[3]['M']*$fuzzy_value[4]['M']*$fuzzy_value[5]['M']*$fuzzy_value[6]['M']*$fuzzy_value[7]['M']*$fuzzy_value[8]['M']*$fuzzy_value[9]['M']*$fuzzy_value[10]['M']*$fuzzy_value[11]['M']*$fuzzy_value[12]['M']*$fuzzy_value[13]['M']*$fuzzy_value[14]['M']*$fuzzy_value[15]['M']*$fuzzy_value[16]['M']*$fuzzy_value[17]['M'];
						$kriteria_data[$left][$up]['M'] = $fuzzy_mid**$integrasi_inverse;

						$fuzzy_up = $fuzzy_value[1]['U']*$fuzzy_value[2]['U']*$fuzzy_value[3]['U']*$fuzzy_value[4]['U']*$fuzzy_value[5]['U']*$fuzzy_value[6]['U']*$fuzzy_value[7]['U']*$fuzzy_value[8]['U']*$fuzzy_value[9]['U']*$fuzzy_value[10]['U']*$fuzzy_value[11]['U']*$fuzzy_value[12]['U']*$fuzzy_value[13]['U']*$fuzzy_value[14]['U']*$fuzzy_value[15]['U']*$fuzzy_value[16]['U']*$fuzzy_value[17]['U'];
						$kriteria_data[$left][$up]['U'] = $fuzzy_up**$integrasi_inverse;
					}
				}
			}
		}
		// print_r($cr_table);
		$data = array(
			'content'	=>	'fuzzyAhp/list_kriteria'
			,'kriteria_list' => $kriteria_list['result_array']
			,'alternatif_list'	=>	$alternatif_list['result_array']
			,'fuzzy_data' => $fuzzy_data
			,'fuzzy_list' => $fuzzy_list
			,'kriteria_data' => $kriteria_data
			,'kriteria_code' => $kriteria_code
			,'cr_table' => $cr_table
			,'get_random_indeks' => $get_random_indeks
		);
		
		$this->load->view($data['content'],$data);
	}

	public function getAlternatif($id_kriteria)
	{
		$id_project 	= $this->session->userdata('ProjectID');
		$get_kriteria   = $this->kriteria->getList(array('where'=>array('ProjectID'=>$id_project,'KodeKriteria'=>$id_kriteria)))['row_array'];
		
		$alternatif_list 	= $this->alternatif->getList(array('where'=>array('ProjectID'=>$id_project),'order_by'=>array('KodeAlternatif'=>'ASC')));
		$fuzzy_master_list= $this->fuzzymaster->getList(array('order_by'=>array('FuzzyID'=>'ASC')));
		$fuzzy_list 	= $this->fuzzyalternatif->getList(array('where'=>array('ProjectID'=>$id_project,'KodeKriteria'=>$id_kriteria),'count'=>true));
		$alternatif_sum_list 	= $this->pairwisealternatifsummary->getList(array('where'=>array('ProjectID'=>$id_project,'KodeKriteria'=>$id_kriteria)));
		$get_random_indeks	= $this->randomindeks->getList(array('where'=>array('CountKriteria'=>count($alternatif_list['result_array']))))['row_array'];
		if($fuzzy_list['count_all']==0)
		{
			return '';
		}
		$fuzzy_master = array();
		foreach($fuzzy_master_list['result_array'] as $key_fuzzy=>$row_fuzzy){
            $fuzzy_id   = $row_fuzzy['FuzzyID'];
			$data['FuzzyID']	= $row_fuzzy['FuzzyID'];
			$data['value']		= $row_fuzzy['value'];
			$data['FuzzyType']	= $row_fuzzy['FuzzyType'];
			$data['Description']= $row_fuzzy['Description'];
			$data['U']			= $row_fuzzy['up'];
			$data['M']			= $row_fuzzy['middle'];
			$data['L']			= $row_fuzzy['low'];
			$fuzzy_master[$fuzzy_id] = $data;
		}
		$alternatif_sum = array();
		foreach($alternatif_sum_list['result_array'] as $key_sum=>$row_sum){
            $left   = $row_sum['Left'];
			$right  = $row_sum['Right'];
			$data['Integrasi'] = $row_sum['Integrasi'];
			$data['Count'] = $row_sum['Count'];
			$alternatif_sum[$left][$right] = $data;
		}
		$fuzzy_data = array();
		foreach($fuzzy_list['result_array'] as $key_fuzzy=>$row_fuzzy){
            $left   = $row_fuzzy['Left'];
			$right  = $row_fuzzy['Right'];
			
			$data['FuzzyID']	= $row_fuzzy['FuzzyID'];
			$data['L']		= $row_fuzzy['ValueL'];
			$data['M']		= $row_fuzzy['ValueM'];
			$data['U']		= $row_fuzzy['ValueH'];
			foreach($fuzzy_master as $key=>$row)
			{
				$data[$row['FuzzyID']] = $row;
			}
			$fuzzy_data[$left][$right][$row_fuzzy['FuzzyID']] = $data;
		}
		$alternatif_data = array();
		$alternatif_code = array();
		$cr_table = array();
		foreach($alternatif_list['result_array'] as $key_alternatif=>$row_alternatif)
		{
			$alternatif_code[$row_alternatif['KodeAlternatif']] = array('Kode'=>$row_alternatif['KodeAlternatif'],'NamaAlternatif'=>$row_alternatif['NamaAlternatif'],'L'=>0,'M'=>0,'U'=>0,'Min'=>0);
			$left   = $row_alternatif['KodeAlternatif'];
			$data_cr['Kode'] = $left;
			$data_cr['SumM'] = 0;
			$data_cr['SumNormalize'] = 0;
			$data_cr['VektorPrioritas'] = 0;
			$data_cr['BobotKriteria'] = 0;
			$data_cr['BobotKriteriaNormalisasi'] = 0;
			$cr_table[$left] = $data_cr;
			foreach($alternatif_list['result_array'] as $key_alternatif_up=>$row_alternatif_up){
				$up = $row_alternatif_up['KodeAlternatif'];
				$kriteria_data[$left][$up]['CrNormalize'] = 0;
				$integrasi = isset($alternatif_sum[$left][$up]['Integrasi'])?$alternatif_sum[$left][$up]['Integrasi']:0;
				$integrasi_inverse = isset($alternatif_sum[$up][$left]['Integrasi'])?$alternatif_sum[$up][$left]['Integrasi']:0;
				if($left==$up)
				{
					$alternatif_data[$left][$up]['L'] = 1;
					$alternatif_data[$left][$up]['M'] = 1;
					$alternatif_data[$left][$up]['U'] = 1;
				}else{
					if(isset($fuzzy_data[$left][$up][1]['L']))
					{
						$fuzzy_value = array();
						$fuzzy_low = 1;
						$fuzzy_mid = 1;
						$fuzzy_up = 1;
						foreach($fuzzy_master_list['result_array'] as $kx=>$rx)
						{
							$fuzzy_value[$rx['FuzzyID']]['L'] = $fuzzy_master[$rx['FuzzyID']]['L']**$fuzzy_data[$left][$up][$rx['FuzzyID']]['L'];
							$fuzzy_value[$rx['FuzzyID']]['M'] = $fuzzy_master[$rx['FuzzyID']]['M']**$fuzzy_data[$left][$up][$rx['FuzzyID']]['M'];
							$fuzzy_value[$rx['FuzzyID']]['U'] = $fuzzy_master[$rx['FuzzyID']]['U']**$fuzzy_data[$left][$up][$rx['FuzzyID']]['U'];
							$fuzzy_low = $fuzzy_low * $fuzzy_value[$rx['FuzzyID']]['L'];
							$fuzzy_mid = $fuzzy_mid * $fuzzy_value[$rx['FuzzyID']]['M'];
							$fuzzy_up = $fuzzy_up * $fuzzy_value[$rx['FuzzyID']]['U'];
						}
						$alternatif_data[$left][$up]['L'] = $fuzzy_low**$integrasi;
						$alternatif_data[$left][$up]['M'] = $fuzzy_mid**$integrasi;
						$alternatif_data[$left][$up]['U'] = $fuzzy_up**$integrasi;
					}else{
						$alternatif_data[$left][$up]['L'] = -1;
						$alternatif_data[$left][$up]['M'] = -1;
						$alternatif_data[$left][$up]['U'] = -1;

						$fuzzy_value = array();
						$fuzzy_low = 1;
						$fuzzy_mid = 1;
						$fuzzy_up = 1;
						$fuzzy_value[1]['L']	= $fuzzy_master[1]['L']**$fuzzy_data[$up][$left][17]['L'];
						$fuzzy_value[2]['L']	= $fuzzy_master[17]['L']**$fuzzy_data[$up][$left][1]['L'];
						$fuzzy_value[3]['L']	= $fuzzy_master[16]['L']**$fuzzy_data[$up][$left][2]['L'];
						$fuzzy_value[4]['L']	= $fuzzy_master[15]['L']**$fuzzy_data[$up][$left][3]['L'];
						$fuzzy_value[5]['L']	= $fuzzy_master[14]['L']**$fuzzy_data[$up][$left][4]['L'];
						$fuzzy_value[6]['L']	= $fuzzy_master[13]['L']**$fuzzy_data[$up][$left][5]['L'];
						$fuzzy_value[7]['L']	= $fuzzy_master[12]['L']**$fuzzy_data[$up][$left][6]['L'];
						$fuzzy_value[8]['L']	= $fuzzy_master[11]['L']**$fuzzy_data[$up][$left][7]['L'];
						$fuzzy_value[9]['L']	= $fuzzy_master[10]['L']**$fuzzy_data[$up][$left][8]['L'];
						$fuzzy_value[10]['L']	= $fuzzy_master[9]['L']**$fuzzy_data[$up][$left][9]['L'];
						$fuzzy_value[11]['L']	= $fuzzy_master[8]['L']**$fuzzy_data[$up][$left][10]['L'];
						$fuzzy_value[12]['L']	= $fuzzy_master[7]['L']**$fuzzy_data[$up][$left][11]['L'];
						$fuzzy_value[13]['L']	= $fuzzy_master[6]['L']**$fuzzy_data[$up][$left][12]['L'];
						$fuzzy_value[14]['L']	= $fuzzy_master[5]['L']**$fuzzy_data[$up][$left][13]['L'];
						$fuzzy_value[15]['L']	= $fuzzy_master[4]['L']**$fuzzy_data[$up][$left][14]['L'];
						$fuzzy_value[16]['L']	= $fuzzy_master[3]['L']**$fuzzy_data[$up][$left][15]['L'];
						$fuzzy_value[17]['L']	= $fuzzy_master[2]['L']**$fuzzy_data[$up][$left][16]['L'];
						
						$fuzzy_value[1]['M']	= $fuzzy_master[1]['M']**$fuzzy_data[$up][$left][17]['M'];
						$fuzzy_value[2]['M']	= $fuzzy_master[17]['M']**$fuzzy_data[$up][$left][1]['M'];
						$fuzzy_value[3]['M']	= $fuzzy_master[16]['M']**$fuzzy_data[$up][$left][2]['M'];
						$fuzzy_value[4]['M']	= $fuzzy_master[15]['M']**$fuzzy_data[$up][$left][3]['M'];
						$fuzzy_value[5]['M']	= $fuzzy_master[14]['M']**$fuzzy_data[$up][$left][4]['M'];
						$fuzzy_value[6]['M']	= $fuzzy_master[13]['M']**$fuzzy_data[$up][$left][5]['M'];
						$fuzzy_value[7]['M']	= $fuzzy_master[12]['M']**$fuzzy_data[$up][$left][6]['M'];
						$fuzzy_value[8]['M']	= $fuzzy_master[11]['M']**$fuzzy_data[$up][$left][7]['M'];
						$fuzzy_value[9]['M']	= $fuzzy_master[10]['M']**$fuzzy_data[$up][$left][8]['M'];
						$fuzzy_value[10]['M']	= $fuzzy_master[9]['M']**$fuzzy_data[$up][$left][9]['M'];
						$fuzzy_value[11]['M']	= $fuzzy_master[8]['M']**$fuzzy_data[$up][$left][10]['M'];
						$fuzzy_value[12]['M']	= $fuzzy_master[7]['M']**$fuzzy_data[$up][$left][11]['M'];
						$fuzzy_value[13]['M']	= $fuzzy_master[6]['M']**$fuzzy_data[$up][$left][12]['M'];
						$fuzzy_value[14]['M']	= $fuzzy_master[5]['M']**$fuzzy_data[$up][$left][13]['M'];
						$fuzzy_value[15]['M']	= $fuzzy_master[4]['M']**$fuzzy_data[$up][$left][14]['M'];
						$fuzzy_value[16]['M']	= $fuzzy_master[3]['M']**$fuzzy_data[$up][$left][15]['M'];
						$fuzzy_value[17]['M']	= $fuzzy_master[2]['M']**$fuzzy_data[$up][$left][16]['M'];

						$fuzzy_value[1]['U']	= $fuzzy_master[1]['U']**$fuzzy_data[$up][$left][17]['U'];
						$fuzzy_value[2]['U']	= $fuzzy_master[17]['U']**$fuzzy_data[$up][$left][1]['U'];
						$fuzzy_value[3]['U']	= $fuzzy_master[16]['U']**$fuzzy_data[$up][$left][2]['U'];
						$fuzzy_value[4]['U']	= $fuzzy_master[15]['U']**$fuzzy_data[$up][$left][3]['U'];
						$fuzzy_value[5]['U']	= $fuzzy_master[14]['U']**$fuzzy_data[$up][$left][4]['U'];
						$fuzzy_value[6]['U']	= $fuzzy_master[13]['U']**$fuzzy_data[$up][$left][5]['U'];
						$fuzzy_value[7]['U']	= $fuzzy_master[12]['U']**$fuzzy_data[$up][$left][6]['U'];
						$fuzzy_value[8]['U']	= $fuzzy_master[11]['U']**$fuzzy_data[$up][$left][7]['U'];
						$fuzzy_value[9]['U']	= $fuzzy_master[10]['U']**$fuzzy_data[$up][$left][8]['U'];
						$fuzzy_value[10]['U']	= $fuzzy_master[9]['U']**$fuzzy_data[$up][$left][9]['U'];
						$fuzzy_value[11]['U']	= $fuzzy_master[8]['U']**$fuzzy_data[$up][$left][10]['U'];
						$fuzzy_value[12]['U']	= $fuzzy_master[7]['U']**$fuzzy_data[$up][$left][11]['U'];
						$fuzzy_value[13]['U']	= $fuzzy_master[6]['U']**$fuzzy_data[$up][$left][12]['U'];
						$fuzzy_value[14]['U']	= $fuzzy_master[5]['U']**$fuzzy_data[$up][$left][13]['U'];
						$fuzzy_value[15]['U']	= $fuzzy_master[4]['U']**$fuzzy_data[$up][$left][14]['U'];
						$fuzzy_value[16]['U']	= $fuzzy_master[3]['U']**$fuzzy_data[$up][$left][15]['U'];
						$fuzzy_value[17]['U']	= $fuzzy_master[2]['U']**$fuzzy_data[$up][$left][16]['U'];
						
						$fuzzy_low = $fuzzy_value[1]['L']*$fuzzy_value[2]['L']*$fuzzy_value[3]['L']*$fuzzy_value[4]['L']*$fuzzy_value[5]['L']*$fuzzy_value[6]['L']*$fuzzy_value[7]['L']*$fuzzy_value[8]['L']*$fuzzy_value[9]['L']*$fuzzy_value[10]['L']*$fuzzy_value[11]['L']*$fuzzy_value[12]['L']*$fuzzy_value[13]['L']*$fuzzy_value[14]['L']*$fuzzy_value[15]['L']*$fuzzy_value[16]['L']*$fuzzy_value[17]['L'];
						$alternatif_data[$left][$up]['L'] = $fuzzy_low**$integrasi_inverse;

						$fuzzy_mid = $fuzzy_value[1]['M']*$fuzzy_value[2]['M']*$fuzzy_value[3]['M']*$fuzzy_value[4]['M']*$fuzzy_value[5]['M']*$fuzzy_value[6]['M']*$fuzzy_value[7]['M']*$fuzzy_value[8]['M']*$fuzzy_value[9]['M']*$fuzzy_value[10]['M']*$fuzzy_value[11]['M']*$fuzzy_value[12]['M']*$fuzzy_value[13]['M']*$fuzzy_value[14]['M']*$fuzzy_value[15]['M']*$fuzzy_value[16]['M']*$fuzzy_value[17]['M'];
						$alternatif_data[$left][$up]['M'] = $fuzzy_mid**$integrasi_inverse;

						$fuzzy_up = $fuzzy_value[1]['U']*$fuzzy_value[2]['U']*$fuzzy_value[3]['U']*$fuzzy_value[4]['U']*$fuzzy_value[5]['U']*$fuzzy_value[6]['U']*$fuzzy_value[7]['U']*$fuzzy_value[8]['U']*$fuzzy_value[9]['U']*$fuzzy_value[10]['U']*$fuzzy_value[11]['U']*$fuzzy_value[12]['U']*$fuzzy_value[13]['U']*$fuzzy_value[14]['U']*$fuzzy_value[15]['U']*$fuzzy_value[16]['U']*$fuzzy_value[17]['U'];
						$alternatif_data[$left][$up]['U'] = $fuzzy_up**$integrasi_inverse;
					}
				}
			}
		}
		// print_r($fuzzy_data);
		$data = array(
			'content'	=>	'fuzzyAhp/list_alternatif'
			,'title' => $get_kriteria['KodeKriteria'].'-'.$get_kriteria['NamaKriteria']
			,'kode_kriteria' => $id_kriteria
			,'alternatif_list' => $alternatif_list['result_array']
			,'fuzzy_data' => $fuzzy_data
			,'fuzzy_list' => $fuzzy_list
			,'alternatif_data' => $alternatif_data
			,'alternatif_code' => $alternatif_code
			,'cr_table' => $cr_table
			,'get_random_indeks' => $get_random_indeks
		);
		
		$this->load->view($data['content'],$data);
	}
	
	public function getDataAlternatif()
	{
		$id_project = $this->session->userdata('ProjectID');
		$kriteria_list = $this->kriteria->getList(array('where'=>array('KriteriaLevel'=>0,'ProjectID'=>$id_project)));
		
		foreach($kriteria_list['result_array'] as $key=>$row)
		{
			$this->getAlternatif($row['KodeKriteria']);
		}
	}

	public function saveBobotKriteria()
	{
		$bobot_kriteria = $this->input->post('bobot_kriteria');
		$id_project = $this->session->userdata('ProjectID');
		$parent_kriteria = $this->kriteria->getList(array('where'=>array('KriteriaLevel'=>0,'ProjectID'=>$id_project)));
		
		$list_parent_kriteria = array();
		foreach($parent_kriteria['result_array'] as $row)
		{
			$list_parent_kriteria[$row['KodeKriteria']]['KodeKriteria'] = $row['KodeKriteria'];
			$list_parent_kriteria[$row['KodeKriteria']]['SumValue'] = 0;
		}
		$data_save_formatted = array();
		$data_save_kriteria_sum = array();
		$id_kriteria_parent = '';
		foreach($bobot_kriteria as $key=>$row)
		{
			$bobot = isset($row)?$row:0;
			$get_parent_kriteria = $this->kriteria->getList(array('where'=>array('KodeKriteria'=>$key,'ProjectID'=>$id_project)))['row_array'];
			if($list_parent_kriteria[$get_parent_kriteria['KodeKriteriaParent']]['KodeKriteria'] == $get_parent_kriteria['KodeKriteriaParent'])
			{
				$id_kriteria_parent = $get_parent_kriteria['KodeKriteriaParent'];
				$list_parent_kriteria[$id_kriteria_parent]['SumValue'] = $list_parent_kriteria[$id_kriteria_parent]['SumValue']+$bobot;
			}
			$data_save['ProjectID']	= $id_project;
			$data_save['KodeKriteria']	= $key;
			$data_save['Value']		= $bobot;
			$data_save_formatted[]	= $data_save;
		}
		
		foreach($list_parent_kriteria as $rowsumkriteria)
		{
			$data_save['ProjectID']		= $id_project;
			$data_save['KodeKriteria']	= $rowsumkriteria['KodeKriteria'];
			$data_save['Value']			= $rowsumkriteria['SumValue'];
			$data_save_kriteria_sum[]	= $data_save;
		}
		
		$this->bobotsubkriteria->delete(array('ProjectID'=>$id_project));
		$this->bobotkriteria->delete(array('ProjectID'=>$id_project));

		$this->db->insert_batch('BobotSubKriteria', $data_save_formatted);
		$this->db->insert_batch('BobotKriteria', $data_save_kriteria_sum);

		$this->success('data disimpan');
	}

	public function saveBobotAlternatif()
	{
		$bobot_alternatif = $this->input->post('bobot_alternatif');
		$id_project = $this->session->userdata('ProjectID');

		$data_save_formatted = array();

		foreach($bobot_alternatif as $key=>$row)
		{
			foreach($row as $key_level=>$row_level)
			{
				$data_save['ProjectID']	= $id_project;
				$data_save['KodeAlternatif']	= $key_level;
				$data_save['KodeKriteria']	= $key;
				$data_save['Value']		= $row_level;
				$data_save_formatted[]	= $data_save;
			}
		}
		
		$this->bobotalternatif->delete(array('ProjectID'=>$id_project));

		$this->db->insert_batch('BobotAlternatif', $data_save_formatted);

		$this->success('data disimpan');
	}
}

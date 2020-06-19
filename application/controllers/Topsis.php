<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Topsis extends MY_Controller {
	
    function __construct(){
        parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Project','project');
		$this->load->model('Kriteria','kriteria');
		$this->load->model('Alternatif','alternatif');
		$this->load->model('BobotKriteria','bobotkriteria');
		$this->load->model('BobotAlternatif','bobotalternatif');
    }
	
	public function index()
	{
		$id_operator = $this->session->userdata('idOperator');
		$project_list = $this->project->getList(array('where'=>array('idOperator'=>$id_operator)));

		$data = array(
			'content'	=>	'topsis/main'
			,'tpl'		=>	'templates/main_template'
			,'project_list' => $project_list
		);
		
		$this->load->view($data['tpl'],$data);
	}

	public function getDataTopsis()
	{
		$id_project 	= $this->session->userdata('ProjectID');
		$kriteria_list 	= $this->kriteria->getList(array('where'=>array('ProjectID'=>$id_project,'KriteriaLevel'=>0)));
		$alternatif_list = $this->alternatif->getList(array('where'=>array('ProjectID'=>$id_project),'order_by'=>array('KodeAlternatif'=>'ASC')));
		$bobot_kriteria = $this->bobotkriteria->getList(array('where'=>array('ProjectID'=>$id_project),'order_by'=>array('KodeKriteria'=>'ASC')));
		// echo $this->db->last_query();
		$bobot_alternatif = $this->bobotalternatif->getList(array('where'=>array('ProjectID'=>$id_project),'order_by'=>array('KodeAlternatif'=>'ASC','KodeKriteria'=>'ASC')));
		
		
		$data_alternatif = array();
		$sum_alternatif = array();
		foreach($bobot_alternatif['result_array'] as $key_alternatif=>$row_alternatif){
            $left   = $row_alternatif['KodeAlternatif'];
			$right  = $row_alternatif['KodeKriteria'];
			$data['Value'] = $row_alternatif['Value'];
			$data['Normalisasi'] = 0;
			$data['NormalisasiTerbobot'] = 0;
			$data['NormalisasiTerbobotSolusiIdealPlus'] = 0;
			$data['NormalisasiTerbobotSolusiIdealMinus'] = 0;
			$data_alternatif[$left][$right] = $data;
			
			$datas['KodeAlternatif'] = $left;
			$datas['SumJarakSolusiIdealPositif'] = 0;
			$datas['SumJarakSolusiIdealNegatif'] = 0;
			$datas['JarakAntarNilaiPositif'] = 0;
			$datas['JarakAntarNilaiNegatif'] = 0;
			$datas['NilaiPreferensi'] = 0;
			$sum_alternatif[$left] = $datas;
		}
		
		foreach($alternatif_list['result_array'] as $a=>$b)
		{
			$sum_alternatif[$b['KodeAlternatif']]['NamaAlternatif'] = $b['NamaAlternatif'];
		}
		
		$data_kriteria = array();
		$kriteria_code = array();
		foreach($bobot_kriteria['result_array'] as $key_kriteria=>$row_kriteria){
			$right  = $row_kriteria['KodeKriteria'];
			$get_kriteria = $this->kriteria->getList(array('where'=>array('ProjectID'=>$id_project,'KodeKriteria'=>$right)))['row_array'];
			$kriteria_code[$row_kriteria['KodeKriteria']] = array('Kode'=>$row_kriteria['KodeKriteria'],'SumBobot'=>0,'BobotPembagi'=>0);
			$data['KodeKriteria'] = $right;
			$data['NamaKriteria'] = $get_kriteria['NamaKriteria'];
			$data['Atribut'] = $get_kriteria['Atribut'];
			$data['Value'] = $row_kriteria['Value'];
			$data['Aplus'] 	= 0;
			$data['Aminus'] = 0;
			$data['ListNormalisasiTerbobot'] = array();
			$data['ListJarakPlus'] = array();
			$data['ListJarakMinus'] = array();
			$data['ListNormalisasiTerbobot'] = array();
			$data['SumNormalisasiTerbobotSolusiIdealPlus'] = 0;
			$data['SumNormalisasiTerbobotSolusiIdealMinus'] = 0;
			$data_kriteria[$right] = $data;
		}
		// print_r($bobot_kriteria);
		$data = array(
			'content'	=>	'topsis/list'
			,'kriteria_list'	=>	$kriteria_list['result_array']
			,'alternatif_list'	=>	$alternatif_list['result_array']
			,'data_kriteria'	=>	$data_kriteria
			,'data_alternatif'	=>	$data_alternatif
			,'kriteria_code'	=>	$kriteria_code
			,'sum_alternatif'	=>	$sum_alternatif
			,'bobot_kriteria'	=>	$bobot_kriteria
		);
		
		$this->load->view($data['content'],$data);
	}
	
}

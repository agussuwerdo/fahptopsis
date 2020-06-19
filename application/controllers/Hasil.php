<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hasil extends MY_Controller {
	
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
			'content'	=>	'hasil/main'
			,'tpl'		=>	'templates/main_template'
			,'project_list' => $project_list
		);
		
		$this->load->view($data['tpl'],$data);
	}
	
}

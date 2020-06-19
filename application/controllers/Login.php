<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	
    function __construct(){
        parent::__construct();
		date_default_timezone_set('Asia/Jakarta'); 
		$this->load->library('authorization');
    }
	
	public function index()
	{
		$data = array(
			'content'	=>	'login/main_login'
		);
		
		$this->load->view($data['content'],$data);
	}
	
    function signin()
    {
		$result['url'] = '';
		$result['error_msg'] = '';
		$error = '';
		
		$data['username'] = $this->input->post('t_username');
		$data['password'] = $this->input->post('t_password');
		
		if (!$data['username'])
			$error .= 'Username tidak boleh kosong, ';
		if (!$data['password'])
			$error .= 'Password belum diisi, ';
		if (!$error)
		{
			$return = $this->authorization->do_login($data);
			// echo $this->db->last_query();
			if($return['status'])
			{
				$error = '';
			}else
			{
				$error = $return['error'];
			}
		}
		$result['url'] = '';
		$result['error_msg'] = $error;
		echo json_encode($result);
    }
    
    function signout()
    {
        $this->authorization->logout();
        redirect(site_url());
    }

}

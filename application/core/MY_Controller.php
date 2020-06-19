<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	var $secure = TRUE;
	var $api 	= FALSE;
	
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		if($this->api == true){
			$auth = $this->get_auth();
		}else{
			if($this->secure == true){
				if (!is_login())
				{
					redirect('/login');
					die;
				}
			}
		}
	}
	
	function get_auth()
	{
		$result = array();
		
		$result['code'] 	= 0;
		$result['status'] 	= 'FAILED';
		$result['message'] 	= 'NOT AUTHORIZED';
		$result['msgid'] 	= '';
		
		$res = array();
		$res['ok'] = false;
		$res['result'] = $result;
		$user = '';
		$pass = '';
		$enable_auth_log = $this->secure;
		if(isset($_SERVER['PHP_AUTH_USER']))
		{
			$user = $_SERVER['PHP_AUTH_USER'];
		}
		if(isset($_SERVER['PHP_AUTH_PW']))
		{
			$pass = $_SERVER['PHP_AUTH_PW'];
		}
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->db->from('ws.auth');
		$this->CI->db->where('login', $user);		
		$this->CI->db->where('pass',$pass);
		$this->CI->db->where('enable',1);
		$user_auth = $this->CI->db->get();
		if($user_auth->num_rows()==0)
		{
			header('WWW-Authenticate: Basic realm="WS"');
			header('Content-type: application/json');
			header('HTTP/1.0 401 Unauthorized');
			echo json_encode($result);
			exit;
		}
		if($enable_auth_log)
		{
			$user_auth= $user_auth->row_array();
			
			$date_expiry = new DateTime($user_auth['validUntil']);
			$date_now = new DateTime(date('Y-m-d H:i:s'));
			
			if($date_expiry < $date_now)
			{
				$result['message'] 	= 'Expired Authorization';
				$this->error($result['message']);
			}
			$uri_segments = $this->uri->uri_string();			
			$class 	= $this->router->class;
			$method = $this->router->method;
			$data_log['value_before'] ='';
			$data_log['log_type'] = 'ws '.$uri_segments;
			$data_log['ip_comp'] = $this->CI->input->ip_address();
			$data_log['user_id'] = $user;
			$data_log['table_name'] = '';
			$data_log['fid_data'] = 0;
			$log = $this->CI->db->insert('ws.authLog', $data_log);
		}

		return $user;
	}
	
	function success($msg='',$success_code='200')
	{
		$request = $_REQUEST;
		// $this->result['request']['method'] 	= $this->input->method();
		// $this->result['request']['data'] 	= $request;
		$this->result['statusCode']			= 1;
		$this->result['message']			= $msg;
		header('Content-Type: application/json');
		header("HTTP/1.1 ".$success_code." OK");
		echo json_encode($this->result);
	}

	function error($err,$error_code='400')
	{
		$request = $_REQUEST;
		// $this->result['request']['method'] 	= $this->input->method();
		// $this->result['request']['data'] 	= $request;
		$this->result['statusCode']			= 0;
		$this->result['message']			= $err;
		header('Content-Type: application/json');
		header("HTTP/1.1 ".$error_code." error");
		echo json_encode($this->result);
		exit;
	}

	function remove_empty_array($array)
	{
		
		foreach ($array as $key =>$value)
		{
			if($value=='')
			{
				unset($array[$key]);
			}
		}
		
		return $array;
	}
}

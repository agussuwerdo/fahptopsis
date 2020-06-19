<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Authorization {

	var $CI = null;

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
	}
	
	function do_login($login = NULL)
	{
		$result = array();

		// A few safety checks
		// Our array has to be set
		if(!isset($login))
		$result['status'] 	= 0;
		$result['error'] 	= '';
	
		//Our array has to have 2 values
		//No more, no less!
		if(count($login) != 2)
		{
			$result['status'] = 0;
			$result['error'] = 'Incorrect parameter';
		}
		
		$username = $login['username'];
		$password = $login['password'];
		$enc_pass = md5($password);
		$this->CI->db->from('msOperator');
		$this->CI->db->where('userName', $username);		
		$this->CI->db->where('passWord',$enc_pass);
		$date = date("Y").date("m").date("d");
		// $this->CI->db->where('ExpiryDate >=', $date);
		$query = $this->CI->db->get();
		// echo $this->CI->db->last_query();
		// print_r($query->result());
		if ($query->num_rows() == 1)
		{
			$newdata = $query->row_array();
			$this->CI->db->from('msOperatorType');
			$this->CI->db->where('idOperatorType', $newdata['fidOperatorType']);
			$getType = $this->CI->db->get();
			$getType = $getType->row_array();
			$newdata['isLogin'] = true;
			$newdata['oprDescription'] = $getType['description'];
			$this->CI->session->set_userdata($newdata);	  
			
			//print_r($this->CI->session->all_userdata());
			//exit;
			
			$result['status'] = 1;
		}
		else 
		{
			// No existing user.
			$result['status'] = 0;
			$result['error'] = 'User tidak ditemukan';
		}
		return $result;
	}
 
	 /**
         *
         * This function restricts users from certain pages.
         * use restrict(TRUE) if a user can't access a page when logged in
         *
         * @access	public
         * @param	boolean	wether the page is viewable when logged in
         * @return	void
         */	
    	function restrict($logged_out = FALSE)
    	{
		// If the user is logged in and he's trying to access a page
		// he's not allowed to see when logged in,
		// redirect him to the index!
		if ($logged_out && is_logged_in())
		{
		      redirect('');
			  exit;
			  //echo $this->CI->fungsi->warning('Maaf, sepertinya Anda sudah login...',site_url());
		      //die();
		}
		
		// If the user isn' logged in and he's trying to access a page
		// he's not allowed to see when logged out,
		// redirect him to the login page!
		if ( ! $logged_out && !is_logged_in()) 
		{
		      echo $this->CI->fungsi->warning('Anda diharuskan untuk Login bila ingin mengakses halaman ini.',site_url());
		      die();
		}
	}
	
	function logout() 
	{
		$this->CI->session->sess_destroy();	
		return TRUE;
	}
}
// End of library class
// Location: system/application/libraries/Auth.php

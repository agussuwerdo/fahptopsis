<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**/
function is_login()
{
	$this_ =& get_instance();
	$session = $this_->session->userdata('isLogin');
	if($session != '')
	{
		return TRUE;
	}else{
		return FALSE;
	}
}

function get_myconf($var){
    $_this = & get_Instance();
	$conf = $_this->config->item($var);
	if($conf <> ''){
		return $conf;
	}else{
		return false;
	}
}

function quotedStr($str)
{
	return '\''.$str.'\'';
}

function delete_directory($dirname) {
		 if (is_dir($dirname))
		   $dir_handle = opendir($dirname);
	 if (!$dir_handle)
		  return false;
	 while($file = readdir($dir_handle)) {
		   if ($file != "." && $file != "..") {
				if (!is_dir($dirname."/".$file))
					 unlink($dirname."/".$file);
				else
					 delete_directory($dirname.'/'.$file);
		   }
	 }
	 closedir($dir_handle);
	 rmdir($dirname);
	 return true;
}

function success($msg='',$success_code='200')
{
	$this_ =& get_instance();
	$request = $_REQUEST;
	// $this_->result['request']['method'] 	= $this_->input->method();
	// $this_->result['request']['data'] 	= $request;
	$this_->result['statusCode']			= 1;
	$this_->result['message']			= $msg;
	header('Content-Type: application/json');
	header("HTTP/1.1 ".$success_code." OK");
	echo json_encode($this_->result);
}

function error($err,$error_code='400')
{
	$this_ =& get_instance();
	$request = $_REQUEST;
	// $this_->result['request']['method'] 	= $this_->input->method();
	// $this_->result['request']['data'] 	= $request;
	$this_->result['statusCode']			= 0;
	$this_->result['message']			= $err;
	header('Content-Type: application/json');
	header("HTTP/1.1 ".$error_code." error");
	echo json_encode($this_->result);
	exit;
}

function remove_empty_array($array)
{
	
	foreach ($array as $key =>$value)
	{
		if(($value=='') || ($value=='NULL'))
		{
			unset($array[$key]);
		}
	}
	
	return $array;
}

function flutterizeData($array)
{
	
	foreach ($array as $key =>$value)
	{
		if(($value==null) || ($value==NULL))
		{
			echo "foundnull";
			$array[$key] = quotedStr('abc');
		}
	}
	
	return $array;
}

function validate_empty($str='',$fielname = 'data')
{
	if(($str==''))
	{
		error($fielname.' tidak boleh kosong '.$str);
	}
	
	return $str;		
}

function generateRandomString($length = 10) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateRandomNumber($length = 10) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if ( ! function_exists('getSQLDate'))
{
	function getSQLDate($inputDate) 
	{
		$return_value = 'NULL';
		if ($inputDate !='')
		{
			$date = null;
			if (strlen($inputDate) > 10)
			{
				$date_eploded = explode(" ", $inputDate);
				if(count($date_eploded) == 2)
				{
					list($date, $time) = explode(' ', $inputDate);
				}else{
					$return_value = $inputDate;
				}
			}
			else
			{
				$date = $inputDate;
				$time = '00:00:00';
			}
			
			$date_eploded = explode("-", $date);
			if(count($date_eploded) == 3)
			{
				list($day, $month, $year) = preg_split("/[\/.-]/", $date);
				if (strlen($year)==2)
					list($year, $month, $day) = preg_split("/[\/.-]/", $date);
				
				$day = strlen($day)==1?'0'.$day:$day;
				$month = strlen($month)==1?'0'.$month:$month;
				$return_value = "$year$month$day";
			}else{
				$return_value = $inputDate;
			}
		}
		return $return_value;
	}
}


function validate_input($str='',$params=array())
{
	$_this = & get_Instance();
	
	$name = isset($params['name'])?$params['name']:'undefined';
	
	if(isset($params['required']))
	{
		if($params['required'])
		{
			if($str=='')
			{
				$_this->error('kolom '.$name.' belum di isi'.$str);
			}
		}
	}
	
	if($str)
	{
		if(isset($params['type']))
		{
			if($params['type'])
			{
				if(($params['type']=='string') || ($params['type']=='varchar') || ($params['type']=='text'))
				{
					if(!is_string($str))
					{
						$_this->error('kolom '.$name.' harus berisi tipe '.$params['type']);
					}
				}
				
				if(($params['type']=='number') || ($params['type']=='float') || ($params['type']=='int') || ($params['type']=='integer'))
				{
					if(!is_numeric($str))
					{
						$_this->error('kolom '.$name.' harus berisi tipe '.$params['type']);
					}
				}
				
				if(($params['type']=='date'))
				{
					if (!(preg_match("/^[0-9]{4}(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])$/",getSQLDate($str)))) {
						$_this->error('kolom '.$name.' invalid '.$params['type'].' format');
					}
					
					$year = substr(getSQLDate($str), 0, 4); // returns "2019"
					$month = substr(getSQLDate($str), 4, 2); // returns "12"
					$day = substr(getSQLDate($str), 6, 2); // returns "31"
					if(!checkdate ( $month, $day, $year ))
					{
						$_this->error('params '.$name.' invalid '.$params['type'].' format'.' y:'.$year.' m:'.$month.' d:'.$day);
					}
				}
			}
		}
	}
	
	if(isset($params['maxlength']))
	{
		if($params['maxlength'])
		{
			if(strlen($str)>$params['maxlength'])
			{
				$_this->error('kolom '.$name.' melebihi panjang maximum '.$params['maxlength']);
			}
		}
	}
	
	if(isset($params['default']))
	{
		if($str=='')
		{
			$str = $params['default'];
		}
	}
	
	return $str;
}

function get_params_from_array($params)
{
	$url_string = '';
	$n = 0;
	foreach($params as $key=>$row)
	{
		$n++;
		if($n==1)
		{
			$url_string .= '?';
		}else{
			$url_string .= '&';
		}
		$url_string .= $key.'='.$row;
	}

	return $url_string;
}

function get_image($img_url)
{
	$return_img = base_url().'images/no-image.png';
	if(file_exists($img_url))
	{
		$return_img = $img_url;
	}
	return $return_img;
}

function getImageList($id,$type)
{
    $_this = & get_Instance();
	$_this->load->model('imageUpload');
	$url = base_url();
	$list_files = array();
	$getFU		= $_this->imageUpload->getList(array('where'=>array('foreignId'=>$id,'imageType'=>$type),'order_by' => array('orderBy'=>'ASC'),'count'=>true));
	if($getFU['count_all'] > 0)
	{
		foreach($getFU['result_array'] as $key=>$row )
		{
			$list_files[$key]['idImages'] = $row['idImages'];
			$list_files[$key]['source'] = $url.''.$row['path'].'/'.$row['serverName'];
			$list_files[$key]['options']['type'] ='local';
			$list_files[$key]['options']['file']['name'] =$row['serverNameWithoutExt'];
			$list_files[$key]['options']['file']['size'] =$row['size'];
			$list_files[$key]['options']['file']['type'] =$row['type'];
			$list_files[$key]['options']['metadata']['poster'] = $list_files[$key]['source'];
		}
	}
	
	return $list_files;
}

function getImagePath($img_type)
{
	$produk_folder = 'images/'.$img_type;
	if (!file_exists($produk_folder)) {
		mkdir($produk_folder);
		// mkdir($produk_folder, 0777, true);
		// chmod($produk_folder, $permit);
	}
	return $produk_folder;
}

function globalRemoveImages($foreign_id,$img_type)
{
    $_this = & get_Instance();
	$_this->load->model('imageUpload');
	$get_image	= $_this->imageUpload->getList(
					array(
						'where'=>array(
							'foreignId'=>$foreign_id
							,'imageType'=>$img_type
						)
					)
				);
	$get_image_row = $get_image['row_array'];
	// echo $_this->db->last_query();
	$delete = $_this->imageUpload->delete(array('idImages'=>$get_image_row['idImages']));
	
	$full_image_path = getImagePath($img_type).'/'.$get_image_row['serverName'];
	// echo $full_image_path;
	if (file_exists($full_image_path)) unlink($full_image_path);
}

function globalOneRemoveImages($id_images)
{
    $_this = & get_Instance();
	$_this->load->model('imageUpload');
	$get_image	= $_this->imageUpload->getList(
					array(
						'where'=>array(
							'idImages'=>$id_images
						)
					)
				);
	$get_image_row = $get_image['row_array'];
	if(!$get_image_row['idImages'])
	{
		$_this->error('file tidak ada');
	}
	// echo $_this->db->last_query();
	$delete = $_this->imageUpload->delete(array('idImages'=>$id_images));
	
	$full_image_path = getImagePath($get_image_row['imageType']).'/'.$get_image_row['serverName'];
	// echo $full_image_path;
	if (file_exists($full_image_path)) unlink($full_image_path);
}

function getInputs()
{
    $_this = & get_Instance();
	$input_method = $_this->input->method();
	$request = $_REQUEST;
	$inputs = array();
	if($input_method == 'get')
	{
		$inputs = $_this->input->get();
	}else if ($input_method == 'post')
	{
		$inputs = $_this->input->post();
	}else{
		$inputs = $request;
	}
	
	return $inputs;
}

function setActiveProject($idProject,$show_message=true)
{
	$_this = & get_Instance();

	$_this->load->model('MsOperator','operator');

	$id_operator = $_this->session->userdata('idOperator');

	$opr = array();
	$opr['idOperator'] 	= $id_operator;
	$opr['ProjectID'] 	= $idProject;

	$save_opr = $_this->operator->upsert($opr);

	$_this->session->set_userdata(array('ProjectID'=>$idProject));
	if($show_message)
	$_this->success('Project Dipilih');
}
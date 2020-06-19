<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MY_Controller {
	
    function __construct(){
        parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('MsOperator','msOperator');
		$this->load->model('MsOperatorType','msOperatorType');
		// $this->load->model('MsProvider','msProvider');
		// $this->load->model('MsSellerPayment','msSellerPayment');
		// $this->load->model('MsPayment','msPayment');
		// $this->load->model('MsTransStatus','msTransStatus');
		// $this->load->model('MsTarget','msTarget');
		// $this->load->model('MsRule','msRule');
		$this->load->model('ImageUpload','imageUpload');
    }
	
	public function index()
	{
		//** Nothing here//
	}
	
	function provider()
	{
		$list = $this->msProvider->getList();
		$data = array(
			'content'	=>	'setting/provider/main'
			,'title'	=>	'Daftar No Target'
			,'list'	=>	$list['result_array']
			,'tpl'		=>	'templates/main_template'
		);
		
		$this->load->view($data['tpl'],$data);
	}
	
	function providerPage()
	{
		$draw = $this->input->post('draw')?:0;
		$start = $this->input->post('start');
		$rowperpage  = $this->input->post('length')?:0;
		$columnIndex   = $_POST['order'][0]['column'];
		$columnName    = $_POST['columns'][$columnIndex]['data'];;
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value
		$isActive = $this->input->post('is_active');
		
		$where = array();
		if($isActive)
		{
			$where['isActive'] = 1;
		}
		
		$where["(tbl.\"name\" ~* '$searchValue'
			)"] = NULL;
		
		$params = array(
         'select' => array('tbl.*,img.path,img."serverName",count_provider."count" "count_target"'),
         'auto_quotes' => false,
         'from' => 'msTarget tbl',
		 'count' => true,
         'where' => $where,
         'or_where' => array(),
         'order_by' => array($columnName=>$columnSortOrder),
		 'join' => array(),
         'offset' => $start,
         'items_per_page' => $rowperpage);
		$getList = $this->msTarget->getList($params,1);
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
	
	function providerInput($id='')
	{
		$head = array();
		
		$getList = $this->msTarget->getList(array('where'=>array('idProvider'=>$id)),1);
		$images_list = getImageList($id,2);
	
		$head = $getList['row_array'];
		$data = array(
			'head'	=>	$head
			,'images_list'	=>	$images_list
			,'content'	=>	'setting/provider/input'
		);
		
		$this->load->view($data['content'],$data);
	}
	
	function providerSave()
	{
		$data['idProvider'] 	= $this->input->post('t_id')?:0;
		$data['name'] 			= validate_empty($this->input->post('t_provider_name'),'nama provider');
		$data['dialUpNumber'] 		 	= $this->input->post('t_dial_up');
		$data['modifyTime'] 	= date('Y-m-d H:i:s');
		
		if($data['idProvider'] == 0)
		{
			$data['createTime']			= date('Y-m-d H:i:s');
		}
		
		$this->msTarget->upsert($data);
		
		$this->success('data berhasil disimpan');
	}
	
	function rule()
	{
		$data = array(
			'content'	=>	'setting/rule/main'
			,'title'	=>	'Daftar Rule'
			,'tpl'		=>	'templates/main_template'
		);
		
		$this->load->view($data['tpl'],$data);
	}
	
	function rulePage()
	{
		$draw = $this->input->post('draw')?:0;
		$start = $this->input->post('start');
		$rowperpage  = $this->input->post('length')?:0;
		$columnIndex   = $_POST['order'][0]['column'];
		$columnName    = $_POST['columns'][$columnIndex]['data'];;
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value
		$isActive = $this->input->post('is_active');
		
		$where = array();
		if($isActive)
		{
			$where['isActive'] = 1;
		}
		
		$where["(tbl.\"name\" ~* '$searchValue'
			)"] = NULL;
		
		$params = array(
         'select' => array('tbl.*,img.path,img."serverName",provider.name "NamaProvider"'),
         'auto_quotes' => false,
         'from' => 'msRule tbl',
		 'count' => true,
         'where' => $where,
         'or_where' => array(),
         'order_by' => array($columnName=>$columnSortOrder),
		 'join' => array(
           'msProvider provider' => array(
             'on' => 'tbl.fidProvider = provider.idProvider',
             'type' => 'left'
           ),
           'imageUpload img' => array(
             'on' => '(provider."idProvider"::varchar) = img."foreignId" and img."imageType" = 2',
             'type' => 'left'
           )
         ),
         'offset' => $start,
         'items_per_page' => $rowperpage);
		$getList = $this->msProvider->getList($params,1);
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
	
	function ruleInput($id='')
	{
		$head = array();
		
		$list_provider = $this->msProvider->getList(array('order_by'=>array('name'=>'asc')));
		$getList = $this->msRule->getList(array('where'=>array('idRule'=>$id)),1);
	// echo $this->db->last_query();
		$head = $getList['row_array'];
		$data = array(
			'head'	=>	$head
			,'list_provider'	=>	$list_provider['result_array']
			,'content'	=>	'setting/rule/input'
		);
		
		$this->load->view($data['content'],$data);
	}
	
	function ruleSave()
	{
		$data['idRule'] 		= $this->input->post('t_id')?:0;
		$data['fidProvider'] 	= $this->input->post('t_id_provider');
		$data['name'] 			= validate_input($this->input->post('t_rule_name'),array('name'=>'Deskripsi','required'=>true));;
		$data['rangeMin'] 		= validate_input(floatval(preg_replace('/[^\d.]/', '', $this->input->post('t_range_min'))),array('name'=>'Range Max','required'=>true,'type'=>'int'));;
		$data['rangeMax'] 		= validate_input(floatval(preg_replace('/[^\d.]/', '', $this->input->post('t_range_max'))),array('name'=>'Range Min','required'=>true,'type'=>'int'));;
		$data['rate'] 			= validate_input($this->input->post('t_rate'),array('name'=>'rate','required'=>true,'type'=>'number'));;
		$data['pointDivider'] 	= validate_input($this->input->post('t_point'),array('name'=>'point','required'=>true,'type'=>'number'));;
		$data['dialUpNumber'] 	= $this->input->post('t_dial_up');
		$data['modifyTime'] 	= date('Y-m-d H:i:s');
		
		$where = array();
		// print_r($data);die;
		if($data['rangeMin'] > $data['rangeMax'])
		{
			$this->error('range yang anda masukan tidak sesuai');
		}
		
		if($data['idRule'] == 0)
		{
			$data['createTime']			= date('Y-m-d H:i:s');
		}else{
			$where['idRule != '.$data['idRule'].'']	= null;
		}
		$where['fidProvider']	= $data['fidProvider'];
		$where['("rangeMin" <= '.$data['rangeMax'].' AND "rangeMax" >= '.$data['rangeMin'].')']	= null;
		
		$get_range_rule	= $this->msRule->getList(array('where' => $where,'count' => true));
		
		if($get_range_rule['count_all'] >= 1)
		{
			$provider = $this->msProvider->getList(array('where' => array('idProvider'=>$get_range_rule['row_array']['fidProvider'])));
			$this->error('range '.$get_range_rule['row_array']['rangeMin'].' sd '.$get_range_rule['row_array']['rangeMax'].' untuk provider '.$provider['row_array']['name'].' sudah ada ['.$get_range_rule['row_array']['name'].']');
		}
		
		$this->db->trans_begin();

		$this->msRule->upsert($data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$error = $this->db->error();
			$this->error($error);
		}
		else
		{
			$this->db->trans_commit();
			$this->success('data berhasil disimpan');
		}
		
	}
	
	function deleteRule($id)
	{
		$this->db->trans_begin();

		$this->msRule->delete(array('idRule'=>$id));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$error = $this->db->error();
			$this->error($error);
		}
		else
		{
			$this->db->trans_commit();
			$this->success('data dihapus');
		}
	}
	
	function target()
	{
		$list = $this->msTarget->getList();
		$data = array(
			'content'	=>	'setting/target/main'
			,'title'	=>	'Daftar No Target'
			,'list'	=>	$list['result_array']
			,'tpl'		=>	'templates/main_template'
		);
		
		$this->load->view($data['tpl'],$data);
	}
	
	function targetPage()
	{
		$draw = $this->input->post('draw')?:0;
		$start = $this->input->post('start');
		$rowperpage  = $this->input->post('length')?:0;
		$columnIndex   = $_POST['order'][0]['column'];
		$columnName    = $_POST['columns'][$columnIndex]['data'];;
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value
		$isActive = $this->input->post('is_active');
		
		$where = array();
		if($isActive)
		{
			$where['isActive'] = 1;
		}
		
		$where["(tbl.\"name\" ~* '$searchValue'
			)"] = NULL;
		
		$params = array(
         'select' => array('tbl.*,provider.name "NamaProvider",img.path,img."serverName"'),
         'auto_quotes' => false,
         'from' => 'msTarget tbl',
		 'count' => true,
         'where' => $where,
         'or_where' => array(),
         'order_by' => array($columnName=>$columnSortOrder),
		 'join' => array(
		   'msProvider provider' => array(
			 'on' => 'tbl.fidProvider = provider.idProvider',
			 'type' => 'left'
		   ),
           'imageUpload img' => array(
             'on' => '(provider."idProvider"::varchar) = img."foreignId" and img."imageType" = 2',
             'type' => 'left'
           )
         
         ),
         'offset' => $start,
         'items_per_page' => $rowperpage);
		$getList = $this->msProvider->getList($params,1);
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
	
	function targetSave()
	{
		$data['idTarget'] 		= $this->input->post('t_id')?:0;
		$data['fidProvider'] 	= $this->input->post('t_id_provider');
		$data['name'] 			= validate_input($this->input->post('t_name'),array('name'=>'Deskripsi','required'=>true));
		$data['phoneNumber'] 	= validate_input($this->input->post('t_phone_number'),array('name'=>'Phone Number','required'=>true));
		$data['minTransfer'] 		= validate_input(floatval(preg_replace('/[^\d.]/', '', $this->input->post('t_amount_min'))),array('name'=>'Minimum Transaksi','required'=>true,'type'=>'int'));
		$data['maxTransfer'] 		= validate_input(floatval(preg_replace('/[^\d.]/', '', $this->input->post('t_amount_max'))),array('name'=>'Maximum Transaksi','required'=>true,'type'=>'int'));
		$data['maxSaldo'] 		= validate_input(floatval(preg_replace('/[^\d.]/', '', $this->input->post('t_max_saldo'))),array('name'=>'Saldo Maksimum','required'=>true,'type'=>'int'));
		$data['dialUpNumber'] 	= $this->input->post('t_dial_up');
		$data['expiryInterval'] = validate_input($this->input->post('t_expiry_interval'),array('name'=>'Interval Pembayaran','required'=>true));
		$data['expiryInvervalType'] = validate_input($this->input->post('t_expiry_interval_type'),array('name'=>'Tipe Interval Pembayaran','required'=>true));
		$data['modifyTime'] 	= date('Y-m-d H:i:s');
		
		$where = array();
		
		if($data['idTarget'] == 0)
		{
			$data['createTime']			= date('Y-m-d H:i:s');
		}
		
		$this->db->trans_begin();
		$this->msTarget->upsert($data);
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$error = $this->db->error();
			$this->error($error);
		}
		else
		{
			$this->db->trans_commit();
			$this->success('data berhasil disimpan');
		}
	}
	
	function targetInput($id='')
	{
		$head = array();
		
		$list_provider = $this->msProvider->getList(array('order_by'=>array('name'=>'asc')));
		$getList = $this->msTarget->getList(array('where'=>array('idTarget'=>$id)),1);
	// echo $this->db->last_query();
		$head = $getList['row_array'];
		$data = array(
			'head'	=>	$head
			,'list_provider'	=>	$list_provider['result_array']
			,'content'	=>	'setting/target/input'
		);
		
		$this->load->view($data['content'],$data);
	}
	
	function deleteTarget($id)
	{
		$this->db->trans_begin();

		$this->msTarget->delete(array('idTarget'=>$id));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$error = $this->db->error();
			$this->error($error);
		}
		else
		{
			$this->db->trans_commit();
			$this->success('data dihapus');
		}
	}
	
	function bank()
	{
		$data = array(
			'content'	=>	'setting/bank/main'
			,'title'	=>	'Daftar Metode Pembayaran'
			,'tpl'		=>	'templates/main_template'
		);
		
		$this->load->view($data['tpl'],$data);
	}
	
	function bankPage()
	{
		$draw = $this->input->post('draw')?:0;
		$start = $this->input->post('start');
		$rowperpage  = $this->input->post('length')?:0;
		$columnIndex   = $_POST['order'][0]['column'];
		$columnName    = $_POST['columns'][$columnIndex]['data'];;
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value
		$isActive = $this->input->post('is_active');
		
		$where = array();
		if($isActive)
		{
			$where['isActive'] = 1;
		}
		
		$where["(tbl.\"codeSellerPayment\" ~* '$searchValue'
			OR tbl.\"description\" ~* '$searchValue')"] = NULL;
		
		$params = array(
         'select' => array('tbl.*,payment.description "paymentDescription",img.path,img."serverName"'),
         'auto_quotes' => false,
         'from' => 'msSellerPayment tbl',
		 'count' => true,
         'where' => $where,
         'or_where' => array(),
         'order_by' => array($columnName=>$columnSortOrder),
		 'join' => array(
           'msPayment payment' => array(
             'on' => 'tbl.fidPayment = payment.idPayment',
             'type' => 'left'
           ),
           'imageUpload img' => array(
             'on' => '(payment."idPayment"::varchar) = img."foreignId" and img."imageType" = 3',
             'type' => 'left'
           )
         ),
         'offset' => $start,
         'items_per_page' => $rowperpage);
		$getList = $this->msSellerPayment->getList($params,1);
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
	
	function bankInput($id='')
	{
		$head = array();
		
		$list_payment = $this->msPayment->getList(array('order_by'=>array('description'=>'asc')));
		$getList = $this->msSellerPayment->getList(array('where'=>array('idSellerPayment'=>$id)),1);
	// echo $this->db->last_query();
		$head = $getList['row_array'];
		$data = array(
			'head'	=>	$head
			,'list_payment'	=>	$list_payment['result_array']
			,'content'	=>	'setting/bank/input'
		);
		
		$this->load->view($data['content'],$data);
	}
	
	function bankSave()
	{
		$data['idSellerPayment'] 	= $this->input->post('t_id')?:0;
		$data['fidPayment'] = validate_input($this->input->post('t_id_payment'),array('name'=>'id'));
		$data['codeSellerPayment'] = validate_input($this->input->post('t_code_payment'),array('name'=>'No Rekening','required'=>true));
		$data['description'] = validate_input($this->input->post('t_description'),array('name'=>'Deskripsi'));
		$data['modifyTime'] 	= date('Y-m-d H:i:s');
		
		if($data['idSellerPayment'] == 0)
		{
			$data['createTime']			= date('Y-m-d H:i:s');
		}
		
		$this->msSellerPayment->upsert($data);
		
		$this->success('data berhasil disimpan');
	}
	
	function deleteBank($id)
	{
		$this->db->trans_begin();

		$this->msSellerPayment->delete(array('idSellerPayment'=>$id));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$error = $this->db->error();
			$this->error($error);
		}
		else
		{
			$this->db->trans_commit();
			$this->success('data dihapus');
		}
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
	
	function uploadImages($id,$img_type)
	{
		$files			= $_FILES; 
		if(!$files)
		{
			$result['message']	= 'Failed, File Upload is empty`..!';
			header('Content-type: application/json');
			echo die(json_encode($result));
		}
		
		$files_upload	= $_FILES;
		$config['upload_path']          = $this->getImagePath($img_type);
		$config['allowed_types']        = 'gif|jpg|png|jpeg';
		$config['file_name']            = $files_upload['t_filepond']['name'];
		$config['overwrite']			= true;
		$config['max_size']             = 3072; // 3MB
		$exploded						= explode(".", $config['file_name']);
		$extension 						= end($exploded);
		$config['fileType']             = $extension; // 3MB
		$file 							= $files_upload['t_filepond']['tmp_name'];
		list($width, $height) 			= getimagesize($file);
		
		$getFU		= $this->imageUpload->getList(array('where'=>array('foreignId'=>$id,'imageType'=>$img_type),'order_by' => array('orderBy'=>'ASC'),'count' => true));
		$countFU	= $getFU['count_all'];
		
		$orderBy	= $countFU+1;
		foreach($getFU['result_array'] as $key=>$row )
		{
			if(($key+1)!=($row['orderBy']))
			{
				$orderBy = $key+1;
				break;
			}
		}
		
		
		$data_file_upload	= array();
		$data_file_upload['idImages']			=	0;
		$data_file_upload['orderBy']			=	$orderBy;
		$data_file_upload['foreignId']			=	$id;
		$data_file_upload['serverName']			=	$files_upload['t_filepond']['name'];
		$data_file_upload['serverNameWithoutExt']=	pathinfo($files_upload['t_filepond']['name'], PATHINFO_FILENAME);
		$data_file_upload['type']				=	$files_upload['t_filepond']['type'];
		$data_file_upload['path']				=	$config['upload_path'];
		$data_file_upload['ext']				=	$extension;
		$data_file_upload['size']				=	$files_upload['t_filepond']['size'];
		$data_file_upload['width']				=	$width;
		$data_file_upload['height']				=	$height;
		$data_file_upload['imageType']			=	$img_type; // provider = 2
		$data_file_upload['uploadBy']			=	$this->session->userdata('idOperator');
		$data_file_upload['usedFor']			=	3;
		$data_file_upload['createTime']			=	date('Y-m-d H:i:s');
		$data_file_upload['lastUpdate']			=	date('Y-m-d H:i:s');
		
		$id_file_upload = $this->imageUpload->upsert($data_file_upload);
		// start resize
		// $this->load->library('image_lib');
		// $resize_height = 200;
		
		// $resize = array(
			// "height"       => $resize_height,
			// "quality"      => '100%',
			// "source_image" => $data_file_upload['serverName'],
			// "new_image"    => $data_file_upload['path'].'resized_'.$data_file_upload['serverName']
		// );
		
		// $resize['width'] = $width * ($resize_height / $height);
		
		// $this->image_lib->initialize($resize);
		// if (!$this->image_lib->resize())
		// echo($this->image_lib->display_errors());
	 
		// end resize
		if (!file_exists($config['upload_path'])) {
			// $old_umask = umask(0);
			mkdir($config['upload_path']);
			// umask($old_umask);
		}
		$this->load->library('upload', $config);
		// $old_umask = umask(0);
		$upload_image = $this->upload->initialize($config);
		if(!$this->upload->do_upload('t_filepond'))
		{
		header("HTTP/1.1 500 Internal server error");
			$result['message']	= $this->upload->display_errors();
			header('Content-type: application/json');
			echo die(json_encode($result));
		}
		// umask($old_umask);
		chmod($config['upload_path'], 0755);
	}
	
	function removeImages()
	{
		$data['foreignId']		= $this->input->post('id');
		$server_name_wo_ext	= $this->input->post('file_name');
		$data['imageType']		= $this->input->post('img_type');
		// $file_id 				= $this->input->post('file_id');
		// print_r($_POST);
		
		$get_image	= $this->imageUpload->getList(
						array(
							'where'=>array(
								'serverNameWithoutExt'=>$server_name_wo_ext
							)
						)
					);
		$get_image_row = $get_image['row_array'];
		globalRemoveImages($get_image_row['foreignId'],$get_image_row['imageType']);
		
	}
	
	function nomorTarget()
	{
		$target = $this->msTarget->getList();
		$data = array(
			'content'	=>	'setting/nomorTarget/main'
			,'title'	=>	'Daftar No Target'
			,'list'	=>	$target['result_array']
			,'tpl'		=>	'templates/main_template'
		);
		
		$this->load->view($data['tpl'],$data);
		
	}
	
	function nomorTargetPage()
	{
		$draw = $this->input->post('draw')?:0;
		$start = $this->input->post('start');
		$rowperpage  = $this->input->post('length')?:0;
		$columnIndex   = $_POST['order'][0]['column'];
		$columnName    = $_POST['columns'][$columnIndex]['data'];;
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value
		$transStatus = $this->input->post('trans_status');
		$start_periode	= $this->input->post('start_periode');
		$end_periode	= $this->input->post('end_periode');
		
		$where = array();
		if($transStatus)
		{
			$where['transStatus'] = $transStatus;
		}
		if($start_periode)
		{
			$where['transDate >= '.quotedStr(getSQLDate($start_periode)).''] = null;
		}
		if($end_periode)
		{
			$where['transDate <= '.quotedStr(getSQLDate($end_periode)).''] = null;
		}
		
		$where["(tbl.\"name\" ~* '$searchValue'
				OR tbl.\"phoneNumber\" ~* '$searchValue'
				OR cust.\"name\" ~* '$searchValue'
				OR cust.\"address\" ~* '$searchValue'
			)"] = NULL;
		
		$params = array(
         'select' => array('tbl.*,
		 provider.name "ProviderName"'),
         'auto_quotes' => false,
         'from' => 'msTarget tbl',
		 'count' => true,
         'where' => $where,
         'or_where' => array(),
         'order_by' => array($columnName=>$columnSortOrder),
         'join' => array(
           'msProvider provider' => array(
             'on' => 'tbl.fidProvider = provider.idProvider',
             'type' => 'left'
           )
         ),
         'offset' => $start,
         'items_per_page' => $rowperpage);
		$getList = $this->msTarget->getList($params,1);
		$qry = $this->db->last_query();
		
		$response = array(
		  "qry" => $qry,
		  "post" => $this->input->post(),
		  "draw" => intval($draw),
		  "iTotalRecords" => $getList['count_all'],
		  "iTotalDisplayRecords" => $getList['count_all'],
		  "aaData" => $getList['result_array']
		);

		header('Content-type: application/json');
		echo json_encode($response);

	}
	
	function profile($is_profile=0,$id_operator=0)
	{
		
		$head = array();
		
		$getList = $this->msOperator->getList(array('where'=>array('idOperator'=>$id_operator)),1);
		$oprType = $this->msOperatorType->getList(array('order_by'=>array('idOperatorType'=>'ASC')),1);
		
		$head = $getList['row_array'];
		$images_list = getImageList($id_operator,1);
		$data = array(
			'head'	=>	$head
			,'oprType'	=>	$oprType
			,'images_list'	=>	$images_list
			,'is_profile'	=>	$is_profile
			,'title'	=>	'Edit profile'
			,'content'	=>	'setting/akun/profile'
		);
		
		$this->load->view($data['content'],$data);
	}
	
	function profileSave()
	{
		$data['idOperator'] 	= $this->input->post('t_id_operator')?:0;
		$data['email'] 			= validate_empty($this->input->post('t_email'),'email');
		$data['phone'] 			= validate_empty($this->input->post('t_phone'),'phone');
		$pass1				 	= $this->input->post('t_password_1');
		$pass2				 	= $this->input->post('t_password_2');
		$oprType				 = $this->input->post('t_opr_type');
		$data['userName'] 		 = validate_empty($this->input->post('userName'),'user name');
		$data['lastUpdate'] 	= date('Y-m-d H:i:s');
		
		if($data['idOperator'] == 0)
		{
			$check_username = $this->msOperator->getList(array('where'=>array('userName'=>$data['userName']),'count' => true))['count_all'];
			
			if($check_username>0)
			{
				$this->error('user name sudah dipakai');
			}
			
			$check_email = $this->msOperator->getList(array('where'=>array('email'=>$data['email']),'count' => true))['count_all'];
			
			if($check_email>0)
			{
				$this->error('email sudah dipakai');
			}
			
			$check_phone = $this->msOperator->getList(array('where'=>array('phone'=>$data['phone']),'count' => true))['count_all'];
			
			if($check_phone>0)
			{
				$this->error('no handphone sudah dipakai');
			}
			if(!$pass1)
			{
				$this->error('password belum di isi');
			}
			$datenow = date('Y-m-d H:i:s');
			$data['expiryDate']			= date('Y-m-d',strtotime(date("Y-m-d", strtotime($datenow)) . " +20 year"));
		}
		
		if($pass1)
		{
			if($pass1!=$pass2)
			{
				$this->error('password yang dimasukan tidak sama');
			}
			$data['passWord'] 	= md5($pass1);
		}
		
		if($oprType)
		{
			$data['fidOperatorType']	= $oprType;
			if($data['idOperator'] == $this->session->userdata('idOperator'))
			{
				$newdata['fidOperatorType'] = $oprType;
				$this->session->set_userdata($newdata);	 
			}
		}
		
		$this->msOperator->upsert($data);
		
		$this->success('data berhasil disimpan');
	}
	
	function akun()
	{
		$data = array(
			'title'	=>	'List Akun'
			,'content'	=>	'setting/akun/main'
			,'tpl'		=>	'templates/main_template'
		);
		
		$this->load->view($data['tpl'],$data);
	}
	
	function akunPage()
	{
		$draw = $this->input->post('draw')?:0;
		$start = $this->input->post('start');
		$rowperpage  = $this->input->post('length')?:0;
		$columnIndex   = $_POST['order'][0]['column'];
		$columnName    = $_POST['columns'][$columnIndex]['data'];;
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value
		
		$where = array();
		
		$where["(tbl.\"userName\" ~* '$searchValue'
				OR tbl.\"email\" ~* '$searchValue'
				OR tbl.\"phone\" ~* '$searchValue'
			)"] = NULL;
			
		$where['fidOperatorType != 3']	= null;
		
		$params = array(
         'select' => array('tbl.*,type.description "oprDescription"'),
         'auto_quotes' => false,
		 'count' => true,
         'from' => 'msOperator tbl',
         'where' => $where,
         'or_where' => array(),
         'order_by' => array($columnName=>$columnSortOrder),
         'join' => array('msOperatorType type' => array(
             'on' => 'tbl.fidOperatorType = type.idOperatorType',
             'type' => 'left'
           )
		 ),
         'offset' => $start,
         'items_per_page' => $rowperpage);
		$getList = $this->msOperator->getList($params,1);
		
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
	
	function akunInput()
	{
		
	}
	
	function akunSave()
	{
		
	}
	
	function privilege()
	{
		$data = array(
			'title'	=>	'List privilege'
			,'content'	=>	'setting/privilege/main'
			,'tpl'		=>	'templates/main_template'
		);
		
		$this->load->view($data['tpl'],$data);
	}
	
	function privilegePage()
	{
		$draw = $this->input->post('draw')?:0;
		$start = $this->input->post('start');
		$rowperpage  = $this->input->post('length')?:0;
		$columnIndex   = $_POST['order'][0]['column'];
		$columnName    = $_POST['columns'][$columnIndex]['data'];;
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value
		
		$where = array();
		
		$where["(tbl.\"description\" ~* '$searchValue')"] = NULL;
		
		$where['idOperatorType != 3']	= null;
		
		$params = array(
         'select' => array('tbl.*'),
         'auto_quotes' => false,
		 'count' => true,
         'from' => 'msOperatorType tbl',
         'where' => $where,
         'or_where' => array(),
         'order_by' => array($columnName=>$columnSortOrder),
         'join' => array(),
         'offset' => $start,
         'items_per_page' => $rowperpage);
		$getList = $this->msOperatorType->getList($params,1);
		
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
	
	function privilegeInput($idOperatorType)
	{
		$data = array(
			'title'	=>	'List privilege'
			,'idOperatorType'	=>	$idOperatorType
			,'content'	=>	'setting/privilege/input'
		);
		
		$this->load->view($data['content'],$data);
		
	}
	
	function privilegeSave()
	{
		$oprType = $this->input->post('oprType');
		$selectedMenu = $this->input->post('selectedMenu');
		
		$operator_access = array();
		
		foreach($selectedMenu as $row)
		{
			$idMenu = substr($row, strpos($row, "_") + 1);
			$operator_access[] = array('fidOperatorType'=>$oprType,'fidMenu'=>$idMenu);
		}
		
		$this->db->delete('msOperatorAccess', array('fidOperatorType' => $oprType));
		// echo $this->db->last_query();
        $this->db->insert_batch('msOperatorAccess', $operator_access);
		
		
		$this->success('Save Success');
	}
}

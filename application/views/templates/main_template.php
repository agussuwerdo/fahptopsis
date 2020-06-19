<!--
*
*  INSPINIA - Responsive Admin Theme
*  version 2.9.2
*
-->

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?=get_myconf('app_name')?></title>
	
	<!-- CSS -->

    <link href="<?=base_url()?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Toastr style -->
    <link href="<?=base_url()?>assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Gritter -->
    <link href="<?=base_url()?>assets/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
	
	<!-- DATATABLES -->
	
    <link href="<?=base_url()?>assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
	
	<!-- SELECT2 -->
    <link href="<?=base_url()?>assets/css/plugins/select2/select2.min.css" rel="stylesheet">

    <link href="<?=base_url()?>assets/css/animate.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/style.css" rel="stylesheet">
	
    <!-- Sweet Alert -->
    <!--<link href="<?//=base_url()?>assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">-->
    <!-- Sweet Alert -->
    <link href="<?=base_url()?>assets/css/plugins/iCheck/custom.css" rel="stylesheet">
	
    <!-- filepond -->
	<link rel="stylesheet" href="<?=base_url()?>assets/css/plugins/filepond/filepond.css">
	<link rel="stylesheet" href="<?=base_url()?>assets/css/plugins/filepond/filepond-plugin-image-preview.min.css">
	<link rel="stylesheet" href="<?=base_url()?>assets/css/plugins/filepond/filepond-plugin-file-poster.css">
	<link rel="stylesheet" href="<?=base_url()?>assets/css/plugins/filepond/filepond-plugin-image-edit.css">
	
	<!-- jsTree -->
	
    <link href="<?=base_url()?>assets/css/plugins/jsTree/style.min.css" rel="stylesheet">	
	
    <link href="<?=base_url()?>assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <!-- Date picker -->
    <link href="<?=base_url()?>assets/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
	<!-- Chosen -->
    <link href="<?=base_url()?>assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
	
    <!-- Ladda style -->
    <link href="<?=base_url()?>assets/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
	
	<?php $this->load->view('templates/my_style')?>

	<!-- JAVASCRIPTS -->
	

    <!-- Mainly scripts -->
    <script src="<?=base_url()?>assets/js/jquery-3.1.1.min.js"></script>
    <script src="<?=base_url()?>assets/js/popper.min.js"></script>
    <script src="<?=base_url()?>assets/js/bootstrap.js"></script>
    <script src="<?=base_url()?>assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?=base_url()?>assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Flot -->
    <script src="<?=base_url()?>assets/js/plugins/flot/jquery.flot.js"></script>
    <script src="<?=base_url()?>assets/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="<?=base_url()?>assets/js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="<?=base_url()?>assets/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="<?=base_url()?>assets/js/plugins/flot/jquery.flot.pie.js"></script>

    <!-- Peity -->
    <script src="<?=base_url()?>assets/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="<?=base_url()?>assets/js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="<?=base_url()?>assets/js/inspinia.js"></script>
    <script src="<?=base_url()?>assets/js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="<?=base_url()?>assets/js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- GITTER -->
    <script src="<?=base_url()?>assets/js/plugins/gritter/jquery.gritter.min.js"></script>

    <!-- Sparkline -->
    <script src="<?=base_url()?>assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="<?=base_url()?>assets/js/demo/sparkline-demo.js"></script>

    <!-- ChartJS-->
    <script src="<?=base_url()?>assets/js/plugins/chartJs/Chart.min.js"></script>
	
	<!-- DATATABLES -->
	
    <script src="<?=base_url()?>assets/js/plugins/dataTables/datatables.min.js"></script>
	
    <!-- Chosen -->
    <script src="<?=base_url()?>assets/js/plugins/chosen/chosen.jquery.js"></script>
	
	<!-- SELECT2 -->
	
    <script src="<?=base_url()?>assets/js/plugins/select2/select2.full.min.js"></script>

    <!-- Toastr -->
    <script src="<?=base_url()?>assets/js/plugins/toastr/toastr.min.js"></script>
	
    <script src="<?=base_url()?>assets/js/eModal.js"></script>
    <!-- Sweet alert -->
    <script src="<?=base_url()?>assets/js/sweetalert.min.js"></script>
    <!-- iCheck -->
    <script src="<?=base_url()?>assets/js/plugins/iCheck/icheck.min.js"></script>
	
    <!-- filepond -->
	<script src="<?=base_url()?>assets/js/plugins/filepond/filepond-plugin-file-encode.min.js"></script>
	<script src="<?=base_url()?>assets/js/plugins/filepond/filepond-plugin-file-validate-type.min.js"></script>
	<script src="<?=base_url()?>assets/js/plugins/filepond/filepond-plugin-file-validate-size.min.js"></script>
	<script src="<?=base_url()?>assets/js/plugins/filepond/filepond-plugin-image-exif-orientation.min.js"></script>
	<script src="<?=base_url()?>assets/js/plugins/filepond/filepond-plugin-image-preview.min.js"></script>
	<script src="<?=base_url()?>assets/js/plugins/filepond/filepond-plugin-file-poster.js"></script>
	<script src="<?=base_url()?>assets/js/plugins/filepond/filepond-plugin-image-resize.js"></script>
	<script src="<?=base_url()?>assets/js/plugins/filepond/filepond-plugin-image-crop.js"></script>
	<script src="<?=base_url()?>assets/js/plugins/filepond/filepond-plugin-image-transform.js"></script>
	<script src="<?=base_url()?>assets/js/plugins/filepond/filepond-plugin-image-edit.js"></script>
	<script src="<?=base_url()?>assets/js/plugins/filepond/filepond-plugin-file-rename.js"></script>
	<script src="<?=base_url()?>assets/js/plugins/filepond/filepond.js"></script>
	
	<!-- jstree -->
	<script src="<?=base_url()?>assets/js/plugins/jsTree/jstree.min.js"></script>
	
   <!-- Input Mask-->
    <script src="<?=base_url()?>assets/js/plugins/jasny/jasny-bootstrap.min.js"></script>
	
   <!-- Date picker -->
   <script src="<?=base_url()?>assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
   
    <!-- Date range picker -->
    <script src="<?=base_url()?>assets/js/plugins/daterangepicker/daterangepicker.js"></script>
	
    <!-- Ladda -->
    <script src="<?=base_url()?>assets/js/plugins/ladda/spin.min.js"></script>
    <script src="<?=base_url()?>assets/js/plugins/ladda/ladda.min.js"></script>
    <script src="<?=base_url()?>assets/js/plugins/ladda/ladda.jquery.min.js"></script>
    <!-- FLOAT THREAD -->
    <script src="<?=base_url()?>assets/js/plugins/floatThread/jquery.floatThead.js"></script>
	<?php $this->load->view('templates/my_js_header')?>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> 
						<?php 
							$profile_pic_img = base_url().'images/no-image.png';
							$images_list = getImageList($this->session->userdata("idOperator"),1);
							if(isset($images_list[0]['options']['metadata']['poster']))
							{
								$profile_pic_img = $images_list[0]['options']['metadata']['poster'];
							}
						?>
						<span>
						<img alt="image" class="img-thumbnail img-md" src="<?=$profile_pic_img?>">
                             </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?=$this->session->userdata("userName")?></strong>
                             </span> <span class="text-muted text-xs block"><?=$this->session->userdata('oprDescription')?><b class="caret"></b></span> </span> </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a onclick="modalView('setting/profile/1/<?=$this->session->userdata('idOperator');?>','Edit Profile ','','lg')">Profile</a></li>
                                <li class="divider"></li>
                                <li><a href="<?=base_url()?>login/signout">Log Out</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            <?=substr($this->session->userdata("userName"), 0, 3)?>
                        </div>
                    </li>
                    <?=$this->menu->generate()?>
                </ul>

            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            
        </div>
            <ul class="nav navbar-top-links navbar-right">
            
                <li style="padding: 20px">
                    <span class="m-r-sm text-muted welcome-message">Welcome <?=$this->session->userdata('userName')?></span>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope"></i>  <span class="label label-warning" id="notify_counter">0</span>
                    </a>
					<ul class="dropdown-menu dropdown-messages">
						<div id="notify_content">
						</div>
                    </ul>
                </li>
                <li>
                    <a href="<?=base_url()?>login/signout">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
            </ul>

        </nav>
        </div>
		<!-- BREADCRUMB-->
		<div class="row wrapper border-bottom white-bg page-heading"><div class="hr-line-dashed"></div>
			<div class="col-lg-12">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Project</label>
                    <div class="col-sm-4">
                    
                    <select class="select2_project form-control" id="project_selector">
                        <?php 
                        $selected_project = $this->session->userdata('ProjectID');
                        foreach($project_list['result_array'] as $row){?>
                            <option <?=($row['ProjectID']==$selected_project)?'selected':''?> value="<?=$row['ProjectID']?>"><?=$row['Deskripsi']?></option>
                        <?php }?>
                    </select>
                </div>
                    <label class="col-sm-2 control-label">
                        <a class="control-label btn btn-primary " onclick="modalView('dashboard/project','List Project','','lg')" href="#"><i class="fa fa-search"></i></a>
                    </label>
                  
                    
                </div>
			</div>
			<div class="col-lg-12"><div class="hr-line-dashed"></div>
				<h2 class="" id="menu_title"></h2>
				<ol class="breadcrumb" id="menu_breadcrumb">
				</ol>
			</div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
			<?php if(!$this->session->userdata('ProjectID')){?>
			<div class="alert alert-danger">
				Silahkan pilih <a class="alert-link" >Project</a> terlebih dahulu
			</div>
			<?php die; }?>
            <?php $this->load->view($content); ?>
        </div>
			<div class="footer">
                    <div class="pull-right">
                    <?=get_myconf('dev_team')?>.
                    </div>
                    <div class="">
                    <strong>Copyright</strong>&copy;<?=date('Y')?> agussuwerdo@gmail.com 
                    </div>
                </div>
        </div>
    </div>
	<?php $this->load->view('templates/my_js')?>
	
	
</body>
</html>

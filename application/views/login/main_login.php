<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?=base_url()?> | Login</title>

    <link href="<?=base_url()?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="<?=base_url()?>assets/css/animate.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/style.css" rel="stylesheet">
    <!-- Ladda style -->
    <link href="<?=base_url()?>assets/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="<?=base_url()?>assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <h3>LOGIN</h3>
            <p>silahkan login menggunakan akun anda</p>
            <form class="m-t" role="form" id="form_login">
                <div class="form-group">
                    <input id="username" name="t_username" type="text" class="form-control" placeholder="Username" required="">
                </div>
                <div class="form-group">
                    <input id="password" name="t_password" type="password" class="form-control" placeholder="Password" required="">
                </div>
                <button id="btn_login" type="submit" class="btn btn-primary block full-width m-b">Login</button>
            </form>
            <p class="m-t"> <small>Copyright&copy; <?=date('Y')?></small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="<?=base_url()?>assets/js/jquery-3.1.1.min.js"></script>
    <script src="<?=base_url()?>assets/js/popper.min.js"></script>
    <script src="<?=base_url()?>assets/js/bootstrap.js"></script>

    <!-- Ladda -->
    <script src="<?=base_url()?>assets/js/plugins/ladda/spin.min.js"></script>
    <script src="<?=base_url()?>assets/js/plugins/ladda/ladda.min.js"></script>
    <script src="<?=base_url()?>assets/js/plugins/ladda/ladda.jquery.min.js"></script>
	
    <!-- Sweet alert -->
    <script src="<?=base_url()?>assets/js/plugins/sweetalert/sweetalert.min.js"></script>
</body>

</html>
<script type="text/javascript">
	var site_url = "<?= site_url()?>";
    $(document).ready(function () {
		  $('#form_login').submit(function (e) {
		    e.preventDefault();
			login(e);
		  });
		  $('#t_username').focus();
    });
    
    function login(e)
    {
        var l = $( '#btn_login' ).ladda();
        l.ladda( 'start' );
            $.ajax({
                type: "POST",
                url: site_url+"login/signin/",
                data: $("#form_login").serialize(),
                dataType: 'json',
                success: function(response)
                {
                    if(response['error_msg'])
                    {   
                        swal(response['header']||'error', response['error_msg']||'', "error");
                    }else
                    {
						window.location = site_url+response['url'];
                    }
                    l.ladda( 'stop' );
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    console.log( (textStatus) );
                    l.ladda( 'stop' );
                    swal('error', 'please reload the page', "error");
                }
           });
    }
</script>
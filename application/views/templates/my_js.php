<html><body>
<script>
var site_url_breadcrumb 	= "<?= site_url() ?>";
var n 						= uri_string.lastIndexOf('_');
var title 					= uri_string.substring(n + 1);
var a 						= uri_string.split("_"),i;
var title_list 				= '';
var breadcrumb 				= '';
var menu_title = $('#title_'+uri_string).val();
var menu_description = $('#description_'+uri_string).val();
var submenu 				= '';
	for (i = 0; i < a.length; i++) {
		submenu = a[i]+' ';
		if(i>0)
		{
			title_list += '| ';
		}
		if(a.length-1 == i)
		{
			site_url_breadcrumb = window.location.href ;
			submenu				= menu_title;
		}
		breadcrumb += '<li class="breadcrumb-item"><a href="'+site_url_breadcrumb+'">'+submenu+'</a></li>';
		title_list += submenu;
	}
	$('#menu_breadcrumb').html(breadcrumb);
	$('#menu_title').text(menu_description);
	document.title = "<?=get_myconf('app_name')?>"+' | '+title_list;
	
	// Minimalize menu
	$('.navbar-minimalize').click(function () {
	// save state in local storage 
		localStorage.setItem('mini-navbar', $('.mini-navbar').is(":visible"));
		SmoothlyMenu();
	});
	// get visibility state from local storage 
	var miniNavbar = localStorage.getItem('mini-navbar');
	if(miniNavbar)
	{
		if (miniNavbar == 'true') {
		// console.log('miniNavbar: ' + miniNavbar);
			$("body").removeClass('mini-navbar');
			SmoothlyMenu();
		}else{
			$("body").addClass('mini-navbar');
			SmoothlyMenu();
		}
	}
	
	
	function quotedStr(str)
	{
		return '\''+str+'\'';
	}
	
	function isset(variable)
	{
		return typeof(variable) != "undefined" && variable !== null;
	}
	
	function UrlExists(url)
	{
		var http = new XMLHttpRequest();
		http.open('HEAD', url, false);
		http.send();
		return http.status!=404;
	}
	
	function get_image(img_url)
	{
		var return_img = "<?=base_url()?>images/no-image.png";
		if(UrlExists(img_url))
		{
			return_img = img_url;
		}
		return return_img;
	}
	
	function number_format(nStr)
	{
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}

	function setActiveProjectTo(id_project)
	{
		$.ajax({
            type: "POST",
            url: site_url+"dashboard/setActiveProjectTo/"+id_project,
            data: $('#form-search').serialize(),
            dataType: 'json',
            success: function(response)
            {
				location.reload();
				// swal(response['header']||'success', response['message']||'', "success").then(() => {
				// });
            },
			error: function( jqXhr, textStatus, errorThrown ){
				var error_message = 'internal error';
				if(jqXhr.responseJSON !== undefined)
				{
					error_message = jqXhr['responseJSON']['message'];
				}
				swal('error', error_message, "error");
				btn_save.ladda('stop');
			}
    });
	}
</script>
</body></html>

<html><body>
<script>
var site_url 				= "<?= site_url() ?>";
var uri_string 				= "<?= preg_replace('/[^a-z0-9]/i', '_', (uri_string())) ?>";

	$(document).ready(function() {
		$(document).ready(function() {
		if(uri_string!='')
			{
				$('#'+uri_string).addClass('active');
				// $('#'+uri_string).addClass('open');
				$('#'+uri_string).parents('a').attr("aria-expanded","true");
				$('#'+uri_string).parents('ul').attr("aria-expanded","true");
				if(uri_string!='main')
				{
					$('#'+uri_string).closest('ul').addClass('collapse in');
				}
				$('#'+uri_string).parents('li').addClass('active');
				// $('#'+uri_string).parents('li').addClass('open');
			}
		// setTimeout(function() {
			// toastr.options = {
				// closeButton: true,
				// progressBar: true,
				// showMethod: 'slideDown',
				// timeOut: 4000
			// };
			// toastr.success('<?=get_myconf('app_name')?>', 'Welcome <?=$this->session->userdata('loginName')?>');
		// }, 1300);
		
	});
		$('.select2_project').select2().on("change", function(e) { 
    		var selected_value =$(this).val();
			var selected_text = $(".select2_project option:selected").text();
			setActiveProjectTo(selected_value);
		});
	});

	function modalView(url='',title='',subtitle='',modalsize='lg')
	{
		// size : ['sm' | 'lg' | 'xl']
		var options = {
			url: site_url+url,
			title: title,
			size: modalsize,
			subtitle: subtitle
		};

		eModal.ajax(options);
	}
	
</script>
</body></html>

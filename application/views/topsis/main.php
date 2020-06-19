<div class="row"> 
	<div class="ibox-content">
		<div class="">
			<div id="list-data-topsis">
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
        loadDataTopsis();
	});
    
	function loadDataTopsis()
	{
        $.ajax({
            type: "POST",
            url: site_url+"topsis/getDataTopsis/",
            data: $('#form-search').serialize(),
            dataType: 'html',
            success: function(response)
            {
                $('#list-data-topsis').html(response);
            },
			error: function( jqXhr, textStatus, errorThrown ){
				var error_message = 'internal error';
				if(jqXhr.responseJSON !== undefined)
				{
					error_message = jqXhr['responseJSON']['message'];
				}
				swal('error', error_message, "error");
			}
        });
	}
</script>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div id="list-data">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	$(document).ready(function(){
        loadData();
	});
	
	function loadData()
	{
        $.ajax({
            type: "POST",
            url: site_url+"nilaiKriteria/getPairWiseData/",
            data: $('#form-search').serialize(),
            dataType: 'html',
            success: function(response)
            {
                $('#list-data').html(response);
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
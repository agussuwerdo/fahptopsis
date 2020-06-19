<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
                <label class="col-sm-2 control-label">Kriteria</label>
                <div class="col-sm-4">
					<select class="select2 form-control" id="kriteria_selector">
						<?php 
						foreach($kriteria_list['result_array'] as $row){?>
							<option value="<?=$row['KodeKriteria']?>"><?=$row['KodeKriteria']?>-<?=$row['NamaKriteria']?></option>
						<?php }?>
					</select>
				</div>
        </div>
		<div class="ibox-content">
			<div id="list-data">
			</div>
		</div>
    </div>
</div>
<script>
	$(document).ready(function(){
        loadData();
		$('#kriteria_selector').select2().on("change", function(e) { 
            loadData();
		});
	});
	
	function loadData()
	{
        var kriteria_selected = $("#kriteria_selector").val();
        $.ajax({
            type: "POST",
            url: site_url+"nilaiAlternatif/getPairWiseData/"+kriteria_selected,
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
				btn_save.ladda('stop');
			}
        });
	}
</script>
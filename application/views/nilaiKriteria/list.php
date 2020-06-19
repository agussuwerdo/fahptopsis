
<form id="form-pairwise-kriteria">
	<div style="">
		<div class="tableFixHead">
			<table class="table table-bordered ">
				<thead class="">
				<tr>
					<th>Kriteria</th>
					<?php foreach($pairwise_list as $key_pairwise=>$row_pairwise){?>
						<th style="text-align: center;"><?=$row_pairwise['value']?></th>
					<?php }?>
					<th>Kriteria</th>
				</tr>
				</thead>
				<tbody>
					<?php foreach($pair_wise as $key_pair=>$row_pair){
						$left   = $row_pair['Left'];
						$left_name   = $row_pair['LeftName'];
						$right  = $row_pair['Right'];
						$right_name   = $row_pair['RightName'];
						?>
						<tr>
							<td  style="padding: 1px;"><strong title="<?=$left_name?>"><span style="font-size:10px;"><?=$left?></span><br><span style="font-size:7px;"><?=$left_name?></span></strong></td>
							<?php foreach($pairwise_list as $key_pairwise=>$row_pairwise){
								$pairwise_id = $row_pairwise['FuzzyID'];
								$value = $pairwise_data[$left][$right][$pairwise_id];
								?>
								<td style="min-width:50px;font-weight:bold"><input onClick="this.select();" style="text-align:center;width: 80%;border-radius: 10px;" type="number" min="0" max="99" name="pairwise[<?=$left?>][<?=$right?>][<?=$pairwise_id?>]" value="<?=($value != '0' ? $value : '')?>"></td>
							<?php }?>
							<td style="padding: 1px;"><strong title="<?=$right_name?>"><span style="font-size:10px;"><?=$right?></span><br><span style="font-size:7px;"><?=$right_name?></span></strong></td>
						</tr>
					<?php }?>
				</tbody>
			</table>
		</div>
	</div>
    <div class="ibox-footer">
        <div class="form-group">
			<div class="text-center">
                <button class="btn btn-primary save-button" type="submit">Simpan Nilai</button>
			</div>
        </div>
    </div>
</form>

<script>
var btn_save = $( '.save-button' ).ladda();
$(document).ready(function(){
	$('form').on('focus', 'input[type=number]', function (e) {
	  $(this).on('wheel.disableScroll', function (e) {
		e.preventDefault()
	  })
	})
	$('form').on('blur', 'input[type=number]', function (e) {
	  $(this).off('wheel.disableScroll')
	})
	$('#form-pairwise-kriteria').submit(function(e) {
		e.preventDefault();
		submit_data();
	});
});
	
function submit_data()
{
	swal({
			title: "Apakah data sudah benar?",
			text: "Perubahan akan disimpan",
			type: "warning",
			buttons: {
			cancel: {
				text: "Cancel",
				value: null,
				visible: true,
				className: "btn-warning",
				closeModal: true,
			},
			confirm: {
				text: "Simpan",
				value: true,
				visible: true,
				className: "btn-primary",
				closeModal: false
			}
		},
	}).then(isConfirm => {
		if (isConfirm) {
			btn_save.ladda('start');
			$.ajax({
				type: "POST",
				url: site_url+"nilaiKriteria/submitData/",
				data: $('#form-pairwise-kriteria').serialize(),
				dataType: 'json',
				success: function(response)
				{
					swal(response['header']||'success', response['message']||'', "success").then(() => {
						loadData();
						btn_save.ladda('stop');
					});
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
			
		}else{
			return false;
		}
	});
}
</script>
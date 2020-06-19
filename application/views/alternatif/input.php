<script>
var btn_save = $( '.save-button' ).ladda();
	$(document).ready(function(){
		$('.chosen-select').chosen({width: "100%"});
		$('#formInput').submit(function(e) {
		e.preventDefault();
		saveInput(e);
		});
	});
	

function saveInput(e)
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
				url: site_url+"listAlternatif/saveAlternatif/",
				data: $('#'+e.target.id+'').serialize(),
				dataType: 'json',
				success: function(response)
				{
					swal(response['header']||'success', response['message']||'', "success").then(() => {
						eModal.close();
                        refreshDataTable();
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
<div class="form-horizontal">
	<form method="post" class="" id="formInput">
		<div class="form-group">
			<label class="col-sm-2 control-label">Kode Alternatif</label>
			<div  class="col-sm-8">
				<input  placeholder="Auto" readonly name="t_kode_alternatif"  type="text" class="form-control" value="<?=$head['KodeAlternatif']?>">
				<span class="help-block m-b-none">Kode dibuat otomatis oleh sistem</span>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Nama Alternatif</label>
			<div class="col-sm-8">
				<input name="t_nama_alternatif" maxlength="100" type="text" class="form-control" value="<?=$head['NamaAlternatif']?>">
			</div>
		</div>
		<div class="hr-line-dashed"></div>
		<div class="form-group">
			<div class="col-sm-4 col-sm-offset-2">
				<button class="btn btn-primary save-button" data-style="zoom-out" type="submit">Save </button>
			</div>
		</div>
	</form>
</div>
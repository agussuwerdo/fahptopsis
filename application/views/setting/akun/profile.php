<script>
$(document).ready(function(){
	$("#type").select2();
	$('#formProfile').submit(function(e) {
    e.preventDefault();
    saveInput(e);
	});
});

function saveInput(e)
{
	$.ajax({
		type: "POST",
		url: site_url+"setting/profileSave/",
		data: $('#'+e.target.id+'').serialize(),
		dataType: 'json',
		success: function(response)
		{
			console.table(response);
			swal(response['header']||'success', response['message']||'', "success").then(() => {
				eModal.close();
				refreshDataTable();
			});
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
<div class="form-horizontal">
	<form method="post" class="" id="formProfile">
		<div class="form-group"><label class="col-lg-2 control-label">Profile Pic<br/><small class="text-navy">Max 1 File (1MB)</small></label>

			<div class="col-lg-3">
				<?php if($head['idOperator']==''){?>
				<p class="form-control-static">Silahkan simpan profile sebelum upload gambar</p>
				<?php }else{?>
					<input id="attach-file" type="file" class="filepond" name="t_filepond" multiple data-max-file-size="1MB" data-max-files="1">
				<?php }?>
			</div>
		</div>
		<div class="form-group">
			<input name="t_id_operator" style="display:none" type="text" value="<?=$head['idOperator']?>">
			<label class="col-sm-2 control-label">username</label>
			<div class="col-sm-8">
				<input name="userName" placeholder="username untuk login" <?=$head['idOperator']?'readonly=""':''?>  type="text" class="form-control" value="<?=$head['userName']?>">
				<span class="help-block m-b-none"></span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">email</label>
			<div class="col-sm-8">
				<input rows="2" type="text" placeholder="email" class="form-control"  name="t_email" value="<?=$head['email']?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Phone</label>
			<div class="col-sm-6">
				<input type="number" class="form-control" placeholder="handphone" name="t_phone" value="<?=$head['phone']?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Hak akses</label>
			<div class="col-sm-4"><select class="form-control m-b" id="type" name="t_opr_type" <?=$is_profile?'disabled=""':''?> >
			<?php foreach($oprType['result_array'] as $key=>$row){?>
				<option <?=$row['idOperatorType']==$head['fidOperatorType']?'selected':'';?> value="<?=$row['idOperatorType']?>"><?=$row['description']?></option>
			<?php }?>
			</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Password</label>
			<div class="col-sm-6">
				<input type="password" class="form-control" placeholder="isi untuk mengganti password" name="t_password_1">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Ulangi password</label>
			<div class="col-sm-6">
				<input type="password" class="form-control" placeholder="samakan dengan password diatas" name="t_password_2">
			</div>
		</div>
		<div class="hr-line-dashed"></div>
		<div class="form-group">
			<div class="col-sm-4 col-sm-offset-2">
				<button class="btn btn-primary" type="submit">Save </button>
			</div>
		</div>
	</form>
</div>

	<script>
	
	var data_files = <?php echo json_encode($images_list)?>;
	FilePond.registerPlugin(

		FilePondPluginFileEncode,

		FilePondPluginFileValidateSize,

		FilePondPluginImageExifOrientation,
		
		FilePondPluginFileValidateType,
		
		FilePondPluginImagePreview,
				
		FilePondPluginFilePoster,
		
		FilePondPluginImageResize,
		
		FilePondPluginImageCrop,
		
		FilePondPluginImageTransform,
		
		FilePondPluginImageEdit,
		
		FilePondPluginFileRename,

	);
	pond = FilePond.create(
		document.querySelector('#attach-file'),
		{
		acceptedFileTypes: ['image/*'],
		files: data_files
			,server: {
				url: site_url,
			  process: {
				url: "./setting/uploadImages/"+'<?=$head['idOperator']?>'+'/'+'1',
				method: 'post',
				dataType: 'JSON',
				withCredentials: false,
				onload: function (res) {
					console.table(res.key);
				},
				onerror: function ( res,file, status) {
					console.log(res);
				  return 'onerror';
				},
				ondata: function (formData) {
					console.log(formData);
					formData.append('Hello', 'World');
					return formData;
				  return 'ondata';
				},
			  },
			},

		  }

	);
	
	function cleanWhiteSpace(crapURI)
	{
		return crapURI.replace(/[^a-zA-Z0-9-_]/g, '');
		
	}
	
	pond.setOptions({
		fileRenameFunction: (file) =>  new Promise(resolve => {
			var unique_time = new Date().valueOf();
			console.log(file);
			resolve(cleanWhiteSpace(file.basename+unique_time))
		}),
		maxFiles: 1,
		maxFileSize: "1MB",
		allowDrop: true,
		instantUpload: true,
		required: false,
		allowReplace: false,
		allowImageResize: true,
		allowImageCrop: true,
		allowImageEdit: true,
		imageResizeTargetWidth: 500,
		imageResizeTargetHeight: 500,
	});
	pond.on('addfile', (error, file) => {
		if (error) {
			console.log('Oh ',error);
			return;
		}
		console.log('File added', file.id);
	});
	// pond.on('addfileprogress', (file, progress) => {
		
		// console.log('File progres', file.id);
		// console.log('File progres bar', progres);
	// });
	pond.on('removefile', (error, file) => {
		if (error) {
			console.log('Oh no',error);
			return;
		}
		$.ajax({
			type: 'POST',
			url: site_url+"setting/removeImages/",
			data: {
				id : '<?=$head['idOperator']?>'
				,file_id : file.id
				,file_name : file.filename
				,img_type : 1
			},
			success: function(result) {
				console.log(result);
			}
		});
		// console.log('File basename  :', file.basename);
		console.log('File Removed id :', file.id);
		console.log('File Removed filename :', file.filename);
	});
	pond.on('beforeRemoveFile', (error, file) => {
		if (error) {
			console.log('Oh no',error);
			return;
		}
		console.log('before remove File ', file);
		console.log('before remove File ', file.name);
	});
		
	</script>
 <div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?=$title?></h5>
					<div class="ibox-tools">
						<button type="button" class="btn btn-xs btn-outline btn-primary" onclick="update_project(0,'')">Tambah</button> 
					</div>
				</div>
				<div class="ibox-content"> 
					<div class="table-responsive ">
						<table id="dataTableProject" class="table table-striped table-bordered table-hover" style="width:100%;" >
							<thead>
								<tr>
									<th>Deskripsi</th>
									<th>Jumlah Kriteria</th>
									<th>Jumlah Alternatif</th>
									<th>Aksi</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		   $('#dataTableProject').DataTable({
			  'processing': true,
			  'serverSide': true,
			  'serverMethod': 'post',
			  'ajax': {
				  'url': site_url+"dashboard/projectList/",
					'data': function ( d ) {
					}
			  },
			"columnDefs": [
				{
				"render": function (data, type, row) {
					return '<button type="button" class="btn btn-xs btn-outline btn-primary" onclick="update_project('+row['ProjectID']+','+quotedStr(row['Deskripsi'])+')" ><i class="fa fa-pencil"></i></button>'+
					'&nbsp;<button type="button" class="btn btn-xs btn-outline btn-danger" onclick="removeData('+row['ProjectID']+')" ><i class="fa fa-trash"></i></button>';
					
				},
				"targets": 3,
				"orderable": false
				},
			],
			'columns': [
			 { data: 'Deskripsi' },
			 { data: 'CountKriteria' },
			 { data: 'CountAlternatif' },
			 { defaultContent: '' },
			],
			initComplete: function () {
			},
		   });
	});
	
	function refreshDataTable()
	{
		$('#dataTableProject').DataTable().ajax.reload( null, false );
	}
	
	function removeData(id_data)
	{
		swal({
				title: "Hapus data?",
				text: "Aksi ini akan menghapus project dan seluruh data project",
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
					text: "Hapus",
					value: true,
					visible: true,
					className: "btn-danger",
					closeModal: false
				}
			},
		}).then(isConfirm => {
			if (isConfirm) {
				$.ajax({
					type: "POST",
					url: site_url+"dashboard/deleteProject/"+id_data,
					dataType: 'json',
					success: function(response)
					{
						swal(response['header']||'success', response['message']||'', "success").then(() => {
							refreshDataTable();
							location.reload();
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
			}else{
				return false;
			}
		});
	}

	function update_project(id_project,text)
	{
        var text_modify = '';
        if(id_project)
        {
            text_modify = 'update project';
        }else{
            text_modify = 'buat project';
        }
		var default_value	= text;
        $(document).off('focusin.modal');
        swal(text_modify, {
                content: {element: 'input',
				attributes: {
				  defaultValue: default_value,
				}},
            })
            .then((value) => {
            	if (value === false) return false;
				if(value)
				{
                    $.ajax({
                        type: "POST",
                        url: site_url+"dashboard/saveProject/",
						data: {
							t_project_id:id_project
							,t_description:value
						},
                        dataType: 'json',
                        success: function(response)
                        {
                            swal(response['header']||'success', response['message']||'', "success").then(() => {
                                refreshDataTable();
								if(response['action_code'] == 1)
								{
									location.reload();
								}
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
				}
            })
	}
</script>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?=$title?></h5>
					<div class="ibox-tools">
						<button type="button" class="btn btn-xs btn-outline btn-primary" onclick="modalView('listAlternatif/addAlternatif/','Tambah Data','','lg')">Tambah</button> 
					</div>
				</div>
				<div class="ibox-content"> 
					<div class="table-responsive ">
						<table id="dataTable" class="table table-striped table-bordered table-hover" style="width:100%;" >
							<thead>
								<tr>
									<th>Kode Alternatif</th>
									<th>Nama Alternatif</th>
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
		   $('#dataTable').DataTable({
			  'processing': true,
			  'serverSide': true,
			  'serverMethod': 'post',
			  'ajax': {
				  'url': site_url+"listAlternatif/alternatifPage/",
					'data': function ( d ) {
					}
			  },
			"columnDefs": [
				{
				"render": function (data, type, row) {
					return '<button type="button" class="btn btn-xs btn-outline btn-primary" onclick="modalView(\'listAlternatif/addAlternatif/'+row['KodeAlternatif']+'/\',\'Edit\',\''+row['NamaAlternatif']+'\',\'lg\')" ><i class="fa fa-pencil"></i></button>'+
					'&nbsp;<button type="button" class="btn btn-xs btn-outline btn-danger" onclick="removeData('+quotedStr(row['KodeAlternatif'])+')" ><i class="fa fa-trash"></i></button>';
				},
				"targets": 2,
				"orderable": false
				},
			],
			'columns': [
			 { data: 'KodeAlternatif' },
			 { data: 'NamaAlternatif' },
			 { defaultContent: '' },
			],
			initComplete: function () {
			},
		   });
	});
	
	function refreshDataTable()
	{
		$('#dataTable').DataTable().ajax.reload( null, false );
	}
	
	function removeData(id_data)
	{
		swal({
				title: "Hapus data?",
				text: "Nilai alternatif yang telah di input akan ikut terhapus",
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
					url: site_url+"listAlternatif/removeAlternatif/"+id_data,
					dataType: 'json',
					success: function(response)
					{
						swal(response['header']||'success', response['message']||'', "success").then(() => {
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
			}else{
				return false;
			}
		});
	}
</script>
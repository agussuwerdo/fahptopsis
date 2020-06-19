 <div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?=$title?></h5>
					<div class="ibox-tools">
						<button type="button" class="btn btn-xs btn-outline btn-primary" onclick="modalView('setting/profile/0/0','Tambah user admin panel','','lg')">Tambah</button> 
					</div>
				</div>
				<div class="ibox-content"> 
					<div class="table-responsive ">
						<table id="dataTable" class="table table-striped table-bordered table-hover" style="width:100%;" >
							<thead>
								<tr>
									<th>Username</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Expiry date</th>
									<th>Hak Akses</th>
									<th>Edit</th>
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
				  'url': site_url+"setting/akunPage/",
					'data': function ( d ) {
					}
			  },
			"columnDefs": [
				{
				"render": function (data, type, row) {
					if(row['idOperator']!=1)
					{
						return '<button type="button" class="btn btn-xs btn-outline btn-primary" onclick="modalView(\'setting/profile/0/'+row['idOperator']+'\',\'Edit Profile \',\'\',\'lg\')" ><i class="fa fa-pencil"></i></button>';
					}
				},
				"targets": 5,
				"orderable": false
				},
			],
			'columns': [
			 { data: 'userName' },
			 { data: 'email' },
			 { data: 'phone' },
			 { data: 'expiryDate' },
			 { data: 'oprDescription' },
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
</script>
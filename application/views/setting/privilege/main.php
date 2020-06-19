 <div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?=$title?></h5>
					<div class="ibox-tools">
					</div>
				</div>
				<div class="ibox-content">
					<div class="table-responsive ">
						<table id="privilegeTable" class="table table-striped table-bordered table-hover" style="width:100%;" >
							<thead>
								<tr>
									<th>Description</th>
									<th>Akses</th>
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
		   $('#privilegeTable').DataTable({
			  'processing': true,
			  'serverSide': true,
			  'serverMethod': 'post',
			  'ajax': {
				  'url': site_url+"setting/privilegePage/",
					'data': function ( d ) {
					}
			  },
			"columnDefs": [
				{
				"render": function (data, type, row) {
					if(row['idOperatorType']!=1)
					{
						return '<button type="button" class="btn btn-xs btn-outline btn-primary"  onclick="modalView(\'setting/privilegeInput/'+row['idOperatorType']+'/\',\'Edit Privilege \',\''+row['description']+'\',\'lg\')"><i class="fa fa-pencil"></i></button>';
					}else{
						return 'Full Akses';
					}
				},
				"targets": 1,
				"orderable": false
				},
			],
			'columns': [
			 { data: 'description' },
			 { defaultContent: '' },
			],
			initComplete: function () {
			},
		   });
	});
	
	function refreshDataTable()
	{
		$('#privilegeTable').DataTable().ajax.reload( null, false );
	}
</script>
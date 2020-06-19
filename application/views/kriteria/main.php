<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <form role="form" class="form-inline" id="form-search">
                    <div class="form-group">
                        <label for="search_text">Cari</label>
                        <input type="text" placeholder="Masukan Kata Kunci" id="search_text" name="t_search_text" class="form-control">
                        <button class="btn btn-white" type="submit" style="margin-bottom: 0px;"><i class="fa fa-search"></i></button>
                    </div>
                    <button type="button" onclick="modalView('listKriteria/addKriteria/0','Tambah Data','','lg')" class="pull-right btn btn-outline btn-primary">Tambah Kriteria</button>
                </form>
            </div>
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
        $('#form-search').submit(function(e) {
            e.preventDefault();
            loadData();
        });
	});
	
	function loadData()
	{
        $.ajax({
            type: "POST",
            url: site_url+"ListKriteria/getMainKriteria/",
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

    
	function removeKriteria(id_data)
	{
		swal({
				title: "Hapus data?",
				text: "Nilai kriteria yang telah di input akan ikut terhapus",
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
					url: site_url+"ListKriteria/removeKriteria/"+id_data,
					dataType: 'json',
					success: function(response)
					{
						swal(response['header']||'success', response['message']||'', "success").then(() => {
							loadData();
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
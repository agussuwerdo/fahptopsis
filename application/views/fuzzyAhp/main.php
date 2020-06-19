<div class="row"> 
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-3"> <i class="fa fa-laptop"></i>Kriteria</a> </li>
            <li class=""><a data-toggle="tab" href="#tab-4"><i class="fa fa-desktop"></i>Alternatif</a></li>
        </ul>
        <div class="tab-content">
            <div id="tab-3" class="tab-pane active">
                <div class="panel-body">
                    <div id="list-data-kriteria">
                    </div>
                </div>
            </div>
            <div id="tab-4" class="tab-pane">
                <div class="panel-body">
                    <form id="form-bobot-alternatif">
                        <div id="list-data-alternatif">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="ibox-content">

            <div class="">
                <div class="feed-activity-list">
                </div>

                <div class="text-center">
                    <button onclick="simpan_bobot()" class="btn btn-primary save-button" type="submit"> Simpan Bobot Fuzzy</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var btn_save = $( '.save-button' ).ladda();
	$(document).ready(function(){
        loadDataKriteria();
        loadDataAlternatif();
	});
    
    function simpan_bobot()
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
				save_bobot_kriteria();
				save_bobot_alternatif();
			}else{
				return false;
			}
		});
    }

	function loadDataKriteria()
	{
        $.ajax({
            type: "POST",
            url: site_url+"fuzzyAhp/getDataKriteria/",
            data: $('#form-search').serialize(),
            dataType: 'html',
            success: function(response)
            {
                $('#list-data-kriteria').html(response);
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
	
	function loadDataAlternatif()
	{
        $.ajax({
            type: "POST",
            url: site_url+"fuzzyAhp/getDataAlternatif/",
            data: $('#form-search').serialize(),
            dataType: 'html',
            success: function(response)
            {
                $('#list-data-alternatif').html(response);
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

	function save_bobot_kriteria()
	{
		btn_save.ladda('start');
        $.ajax({
            type: "POST",
            url: site_url+"FuzzyAhp/saveBobotKriteria/",
            data: $('#form-bobot-kriteria').serialize(),
            dataType: 'json',
            success: function(response)
            {
                swal(response['header']||'success', response['message']||'', "success").then(() => {
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
    }
    
	function save_bobot_alternatif()
	{
		btn_save.ladda('start');
        $.ajax({
            type: "POST",
            url: site_url+"FuzzyAhp/saveBobotAlternatif/",
            data: $('#form-bobot-alternatif').serialize(),
            dataType: 'json',
            success: function(response)
            {
                swal(response['header']||'success', response['message']||'', "success").then(() => {
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
	}
</script>
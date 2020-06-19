<div class="row" id="menu_privilege">
	<?= $this->menu->get_menu_privilege(0,0,$idOperatorType)?>
</div>
<hr>
<button class="btn btn-primary" type="button" onClick="privilegeSave()"><i class="fa fa-check"></i>&nbsp;Save</button>
<script>
	$(document).ready(function(){
        $('#menu_privilege').jstree({
			"checkbox" : {
			  "keep_selected_style" : false
			},
            'core' : {
                'check_callback' : true
            },
            'plugins' : [ 'types', 'dnd','checkbox' ],
            'types' : {
                'default' : {
                    'icon' : 'fa fa-folder'
                },
                'html' : {
                    'icon' : 'fa fa-file-code-o'
                },
                'svg' : {
                    'icon' : 'fa fa-file-picture-o'
                },
                'css' : {
                    'icon' : 'fa fa-file-code-o'
                },
                'img' : {
                    'icon' : 'fa fa-file-image-o'
                },
                'js' : {
                    'icon' : 'fa fa-file-text-o'
                }

            }
        });

	});
	function privilegeSave()
	{
		var selectedMenu = [];
	var selectedElms = $('#menu_privilege').jstree('get_selected', true);
	$.each(selectedElms, function() {
		selectedMenu.push(this.id);
	});
		$.ajax({
			type: "POST",
			url: site_url+"setting/privilegeSave/",
			data:  { 
			oprType : '<?=$idOperatorType?>'
			,selectedMenu:selectedMenu},
			dataType: 'json',
			success: function(response)
			{
				swal(response['header']||'success', response['message']||'', "success").then(() => {
					eModal.close();
					refreshDataTable();
				});
			},
			error: function( jqXhr, textStatus, errorThrown ){
			swal('error', jqXhr['responseJSON']['message']||'', "error");
			}
	   });
	}
</script>
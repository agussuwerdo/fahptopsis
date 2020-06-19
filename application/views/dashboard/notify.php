<script>
$(document).ready(function(){
   $('#notify_counter').text('<?=$notif_count;?>');
});
</script>
<?php foreach($notif_list as $row){?>
	 <li>
		<div class="dropdown-messages-box">
			<div class="media-body">
				<small class="pull-right"></small>
				<strong><?=$row['notifTitle']?></strong><br>
				<small class="text-muted"><?=$row['notifContent']?></small>
			</div>
		</div>
	</li>
	<li class="divider"></li>
<?php }?>
	<li>
		<div class="text-center link-block">
			<a href="<?=base_url()?>transaksi/pesanan">
				<i class="fa fa-envelope"></i> <strong>Lihat semua pesanan masuk</strong>
			</a>
		</div>
	</li>
<div class="wrap">

	<h1 class="wp-heading-inline" ><?php _e('Address Book','wdac');?></h1>

	<a class="page-title-action" href="<?php echo admin_url('admin.php?page=wd-ac&action=new');?>">New</a>

	<form id="posts-filter" method="get">
		<?php
			 $table = new Wd\Ac\Admin\Address_List();
			 $table->prepare_items();
			 $table->display();
		?>	
	</form>

</div>
<div class="wrap">

	<h1 class="wp-heading-inline" ><?php _e('Address Book','wdac');?></h1>
   <?php if ( isset( $_GET['inserted'] ) ) { ?>
        <div class="notice notice-success">
            <p><?php _e( 'Address has been added successfully!', 'wdac' ); ?></p>
        </div>
    <?php } ?>
     <?php if ( isset( $_GET['address-deleted'] ) && $_GET['address-deleted'] == 'true' ) { ?>
        <div class="notice notice-success">
            <p><?php _e( 'Address has been deleted successfully!', 'wdac' ); ?></p>
        </div>
    <?php } ?>
     <?php if ( isset( $_GET['address-deleted'] ) && $_GET['address-deleted'] == 'false' ) { ?>
        <div class="notice notice-success">
            <p><?php _e( 'Address has not been deleted!', 'wdac' ); ?></p>
        </div>
    <?php } ?>
	<a class="page-title-action" href="<?php echo admin_url('admin.php?page=wd-ac&action=new');?>">New</a>

	<form id="posts-filter" method="get">
		<?php
			 $table = new Wd\Ac\Admin\Address_List();
			 $table->prepare_items();
			 $table->display();
		?>	
	</form>

</div>
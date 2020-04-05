<div class="wrap">
	<h1 class="wp-heading-inline" ><?php _e('New Address Book','wdac');?></h1>
	<?php // var_dump($this->errors);?>
	<form method="post" action="" novalidate="novalidate">
	<table class="form-table" role="presentation">
		<tr class="row <?php echo ($this->has_error('name')) ? 'form-invalid':'' ?>">
			<th scope="row"><label for="name"><?php _e('Name','wdac');?></label></th>
			<td>
				<input name="name" type="text" id="blogname" value="" class="regular-text">
				<?php if ($this->has_error('name')) { ?>
					<p><?php echo $this->get_error('name'); ?></p>
				<?php } ?>
				
			</td>
		</tr>
		<tr>
            <th scope="row">
                <label for="address"><?php _e( 'Address', 'wdac' ); ?></label>
            </th>
            <td>
                <textarea class="regular-text" name="address" id="address"></textarea>
            </td>
        </tr>
        <tr class="row <?php echo ($this->has_error('name')) ? 'form-invalid':'' ?>">
            <th scope="row">
                <label for="phone"><?php _e( 'Phone', 'wdac' ); ?></label>
            </th>
            <td>
                <input type="text" name="phone" id="phone" class="regular-text" value="">
                <?php if ($this->has_error('phone')) { ?>
					<p><?php echo $this->get_error('phone'); ?></p>
				<?php } ?>
            </td>
        </tr>

	</table>
	<?php 
		wp_nonce_field( 'new-address');
		// wp_nonce_field( 'new_address_nonce', 'address_nonce' );
		submit_button( __( 'Add address', 'wdac' ), 'button button-primary','submit_address' );
	?>
	</form>

</div>
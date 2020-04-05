<?php

namespace Wd\Ac\Admin;

use  Wd\Ac\Traits\Form_Error;
/**
 * 
 */
class Addressbook
{

	use Form_Error;
	/**
	* Plugin page 
	*/
	function plugin_page(){
		$action = isset($_GET['action']) ? $_GET['action'] : 'list' ;
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0 ;
		switch ($action) {
			case 'new':
				$template = __DIR__.'/view/address-new.php';
				break;
			case 'edit':
				$address = wd_ac_get_address( $id );
				$template = __DIR__.'/view/address-edit.php';
				break;
			case 'view':
				$template = __DIR__.'/view/address-view.php';
				break;
			
			default:
				$template = __DIR__.'/view/address-list.php';
				break;
		}
		if (file_exists($template)) {
			include $template;
		}
	}

	/**
	* Data insert
	*
	* @return redirect 
	*/

	function form_handaler(){
		if (!isset($_POST['submit_address'])) { 
			return ;
		}
		if ( ! wp_verify_nonce($_POST['_wpnonce'], 'new-address') ) {
		    die( __( 'Security nonce faild', 'textdomain' ) ); 
		} 

		if (!current_user_can('manage_options')) {
			die( __( 'Security faild', 'textdomain' ) ); 
		}
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) :'';
		$address = isset($_POST['address']) ? sanitize_text_field($_POST['address']) :'';
		$phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) :'';
		if (empty($name)) {
	 		 $this->errors['name'] = __('Please provide a name','wdac');
	 	}
	 	if (empty($phone)) {
	 		 $this->errors['phone'] = __('Please provide a phone number','wdac');
	 	}
	 	if (!empty($this->errors)) {
	 		return ;
	 	}

	 	$args = [
            'name'    => $name,
            'address' => $address,
            'phone'   => $phone
        ];

        if ( $id ) {
            $args['id'] = $id;
        }

		$insert_id = wd_ac_insert($args);

		if (is_wp_error( $insert_id )) {
			wp_die( $insert_id->get_error_message() );
		}

		 if ( $id ) {
            $redirect_to = admin_url( 'admin.php?page=wd-ac&action=edit&address-updated=true&id='.$id );
        } else {
            $redirect_to = admin_url( 'admin.php?page=wd-ac&inserted=true' );
        }
		
		wp_redirect( $redirect_to);

		exit;

	}

 	public function delete_address() {
        if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'wd-ac-delete-address' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;

        if ( wd_ac_delete_address( $id ) ) {
            $redirected_to = admin_url( 'admin.php?page=wd-ac&address-deleted=true' );
        } else {
            $redirected_to = admin_url( 'admin.php?page=wd-ac&address-deleted=false' );
        }

        wp_redirect( $redirected_to );
        exit;
    }
}



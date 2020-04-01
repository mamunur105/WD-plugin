<?php

namespace Wd\Ac\Admin;

/**
 * 
 */
class Addressbook
{
	public $errors = [];
	/**
	* Plugin page 
	*/
	function plugin_page(){
		$action = isset($_GET['action']) ? $_GET['action'] : 'list' ;
		switch ($action) {
			case 'new':
				$template = __DIR__.'/view/address-new.php';
				break;
			case 'edit':
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
		$insert_id = wd_ac_insert([
			'name'		=> $name,
			'address'	=> $address,
			'phone'		=> $phone
		]);
		
		if (is_wp_error( $insert_id )) {
			wp_die( $insert_id->get_error_message() );
		}

		$redirect_to = admin_url( 'admin.php?page=wd-ac&inserted=true', 'admin' );
		wp_redirect( $redirect_to);
		exit;

	}


}



// CREATE TABLE `wd_ac_addreses` (
//  `id` int(11) NOT NULL,
//  `name` varchar(100) NOT NULL,
//  `address` varchar(255) NOT NULL,
//  `phone` varchar(30) NOT NULL,
//  `created_by` int(20) NOT NULL,
//  `created_at` datetime NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=latin1
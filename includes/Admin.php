<?php
namespace Wd\Ac;

/**
 * The admin class
 */
class Admin {
	
	function __construct(){
		$addressbook = new Admin\Addressbook();
		$this->dispatch_action($addressbook);
		new Admin\Menus($addressbook);
	}

	function dispatch_action($addressbook){
		add_action('admin_init',[$addressbook,'form_handaler']);
		add_action( 'admin_post_wd-ac-delete-address', [ $addressbook, 'delete_address' ] );
	}
}
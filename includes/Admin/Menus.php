<?php

namespace Wd\Ac\Admin;

/**
 * 
 */
class Menus {
	
	function __construct(){
		add_action('admin_menu',[$this,'admin_menu']);
	}

	function admin_menu(){
		$parent_slug = 'wd-ac';
		$capability = 'manage_options';
		add_menu_page(__( 'Wd Ac', 'wdac' ),  __( 'Ac', 'wdac' ),$capability,$parent_slug, [$this,'address_book'], 'dashicons-buddicons-topics' );
		add_submenu_page( $parent_slug,  __( 'Address book', 'wdac' ),__( 'Address book', 'wdac' ) , $capability,$parent_slug,[$this,'address_book'] );
		add_submenu_page( $parent_slug,  __( 'Settings', 'wdac' ),__( 'Settings', 'wdac' ) , $capability,'wd-settings',[$this,'address_book_settings'] );
	}

	function address_book(){
		$Addressbook = new Addressbook();
		$Addressbook->plugin_page();
	}
	
	function address_book_settings(){
		echo "Hello settings";
	}

}
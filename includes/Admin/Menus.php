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
		add_menu_page(__( 'Wd Ac', 'wdac' ),  __( 'Ac', 'wdac' ),'manage_options','wd-ac', [$this,'plugin_page'], 'dashicons-buddicons-topics' );
	}

	function plugin_page(){
		echo "Hello world ";
	}

}
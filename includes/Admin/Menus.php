<?php

namespace Wd\Ac\Admin;

/**
 * 
 */
class Menus {
	
	public $addressbook;

	function __construct($addressbook ){
		$this->addressbook = $addressbook;
		add_action('admin_menu',[$this,'admin_menu']);
	}

	function admin_menu(){
		$parent_slug = 'wd-ac';
		$capability = 'manage_options';
		$hook = add_menu_page(__( 'Wd Ac', 'wdac' ),  __( 'Ac', 'wdac' ),$capability,$parent_slug, [$this->addressbook,'plugin_page'], 'dashicons-buddicons-topics' );
		add_submenu_page( $parent_slug,  __( 'Address book', 'wdac' ),__( 'Address book', 'wdac' ) , $capability,$parent_slug,[$this->addressbook,'plugin_page'] );
		add_submenu_page( $parent_slug,  __( 'Settings', 'wdac' ),__( 'Settings', 'wdac' ) , $capability,'wd-settings',[$this,'address_book_settings'] );

		add_action( 'admin_head-' . $hook, [ $this, 'enqueue_assets' ] );
	}

	function address_book_settings(){
		echo "Hello settings";
	}

	/**
     * Enqueue scripts and styles
     *
     * @return void
     */
    public function enqueue_assets() {
        wp_enqueue_style( 'academy-admin-style' );
    }
}
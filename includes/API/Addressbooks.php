<?php

namespace Wd\Ac\API;

use WP_REST_Controller;
use WP_REST_Server;
/**
 * Undocumented class
 */
class Addressbooks extends  WP_REST_Controller {

	/**
	 * Undocumented function
	 */
	public function __construct() {
		$this->namespace = 'ac/v1';
		$this->rest_base = 'contacts';
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				'methods'             => WP_REST_Server::READABLE, // 'GET',
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'arg'                 => $this->get_collection_params(),
			)
		);
	}

	/**
	 * Checks if a given request has access to get items.
	 *
	 * @since 4.7.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
	 */
	public function get_items_permissions_check( $request ) {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}
		return false;
	}
	/**
	 * Retrieves a collection of items.
	 *
	 * @since 4.7.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_items( $request ) {

	}

	/**
	 * Retrieves the item's schema, conforming to JSON Schema.
	 *
	 * @since 4.7.0
	 *
	 * @return array Item schema data.
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->add_additional_fields_schema( array() );
		}
	}

}

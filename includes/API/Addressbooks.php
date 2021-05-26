<?php

namespace Wd\Ac\API;

use WP_REST_Controller;
use WP_REST_Server;
use WP_Error;
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
				array(
					'methods'             => WP_REST_Server::READABLE, // 'GET',
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_item_schema' ),
			)
		);
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the object.' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param( array( 'default' => 'view' ) ),
					),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' ),
				),
				'schema' => array( $this, 'get_item_schema' ),
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
		// return $request;
		$args   = array();
		$params = $this->get_collection_params();
		foreach ( $params as $key => $value ) {
			if ( isset( $request[ $key ] ) ) {
				$args[ $key ] = $request[ $key ];
			}
		}
		$args['number'] = $args['per_page'];
		$args['offset'] = $args['number'] * ( $args['page'] - 1 );
		unset( $args['per_page'] );
		unset( $args['page'] );
		$data     = array();
		$contacts = wd_ac_get_addresses( $args );
		foreach ( $contacts as $cont ) {
			$res = $this->prepare_item_for_response( $cont, $request );
			// return $res;
			$data[] = $this->prepare_response_for_collection( $res );
		}

		$total     = wd_ac_address_count();
		$max_pages = ceil( $total / (int) $args['number'] );

		$response = rest_ensure_response( $data );

		$response->header( 'X-WP-Total', (int) $total );
		$response->header( 'X-WP-TotalPages', (int) $max_pages );

		return $response;

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
			return $this->add_additional_fields_schema( $this->schema );
		}
		$schema       = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'contact',
			'type'       => 'object',
			'properties' => array(
				'id'      => array(
					'description' => __( 'Unique identifier for the object.' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'    => array(
					'description' => __( 'Name of the contact.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'address' => array(
					'description' => __( 'Address of the contact.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_textarea_field',
					),
				),
				'phone'   => array(
					'description' => __( 'Phone number of the contact.' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'date'    => array(
					'description' => __( "The date the object was published, in the site's timezone." ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
			),
		);
		$this->schema = $schema;
		return $this->add_additional_fields_schema( $this->schema );
	}

	/**
	 * Retrieves the query params for the collections.
	 *
	 * @since 4.7.0
	 *
	 * @return array Query parameters for the collection.
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();
		unset( $params['search'] );
		return $params;
	}

	/**
	 * Prepares the item for the REST response.
	 *
	 * @since 4.7.0
	 *
	 * @param mixed           $item    WordPress representation of the item.
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function prepare_item_for_response( $item, $request ) {
		$data   = array();
		$fields = $this->get_fields_for_response( $request );
		// return $fields;
		if ( in_array( 'id', $fields, true ) ) {
			$data['id'] = (int) $item->id;
		}

		if ( in_array( 'name', $fields, true ) ) {
			$data['name'] = $item->name;
		}

		if ( in_array( 'address', $fields, true ) ) {
			$data['address'] = $item->address;
		}

		if ( in_array( 'phone', $fields, true ) ) {
			$data['phone'] = $item->phone;
		}

		if ( in_array( 'date', $fields, true ) ) {
			$data['date'] = mysql_to_rfc3339( $item->created_at );
		}

		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->filter_response_by_context( $data, $context );

		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $item ) );

		return $response;
	}
	// public function get_fields_for_response( $request ) {
	// return parent::get_fields_for_response( $request );
	// }

	/**
	 * Prepares links for the request.
	 *
	 * @param \WP_Post $post Post object.
	 *
	 * @return array Links for the given post.
	 */
	protected function prepare_links( $item ) {
		$base = sprintf( '%s/%s', $this->namespace, $this->rest_base );

		$links = array(
			'self'       => array(
				'href' => rest_url( trailingslashit( $base ) . $item->id ),
			),
			'collection' => array(
				'href' => rest_url( $base ),
			),
		);

		return $links;
	}
	/**
	 * Checks if a given request has access to get a specific item.
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_Error|bool
	 */
	public function get_item_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$contact = $this->get_contact( $request['id'] );

		if ( is_wp_error( $contact ) ) {
			return $contact;
		}

		return true;
	}

	/**
	 * Retrieves one item from the collection.
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function get_item( $request ) {
		$contact = $this->get_contact( $request['id'] );

		$response = $this->prepare_item_for_response( $contact, $request );
		$response = rest_ensure_response( $response );

		return $response;
	}
	/**
	 * Checks if a given request has access to delete a specific item.
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_Error|bool
	 */
	public function delete_item_permissions_check( $request ) {
		return $this->get_item_permissions_check( $request );
	}

	/**
	 * Deletes one item from the collection.
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_Error|WP_REST_Response
	 */
	public function delete_item( $request ) {
		$contact  = $this->get_contact( $request['id'] );
		$previous = $this->prepare_item_for_response( $contact, $request );

		$deleted = wd_ac_delete_address( $request['id'] );

		if ( ! $deleted ) {
			return new WP_Error(
				'rest_not_deleted',
				__( 'Sorry, the address could not be deleted.' ),
				array( 'status' => 400 )
			);
		}

		$data = array(
			'deleted'  => true,
			'previous' => $previous->get_data(),
		);

		$response = rest_ensure_response( $data );

		return $response;
	}
	/**
	 * Get the address, if the ID is valid.
	 *
	 * @param int $id Supplied ID.
	 *
	 * @return Object|\WP_Error
	 */
	protected function get_contact( $id ) {
		$contact = wd_ac_get_address( $id );

		if ( ! $contact ) {
			return new WP_Error(
				'rest_contact_invalid_id',
				__( 'Invalid contact ID.' ),
				array( 'status' => 404 )
			);
		}

		return $contact;
	}


}

<?php
/*
* Insert a new error
*
* Param Array $args
*
* Return int/WP_Error
*/
function wd_ac_insert( $args = array() ) {

	if ( empty( $args['name'] ) ) {
		return new \WP_Error( 'no-name', __( 'You must Provide a name', 'wdac' ) );
	}
	global $wpdb;
	$default = array(
		'name'       => '',
		'address'    => '',
		'phone'      => '',
		'created_by' => get_current_user_id(),
		'created_at' => current_time( 'mysql' ),
	);
	$data    = wp_parse_args( $args, $default );

	if ( isset( $data['id'] ) ) {

		$id = $data['id'];
		unset( $data['id'] );
		$updated = $wpdb->update(
			$wpdb->prefix . 'wd_ac_addreses',
			$data,
			array( 'id' => $id ),
			array(
				'%s',
				'%s',
				'%s',
				'%d',
				'%s',
			),
			array( '%d' )
		);

		return $updated;
	} else {
		$inserted = $wpdb->insert(
			"{$wpdb->prefix}wd_ac_addreses",
			$data,
			array( '%s', '%s', '%s', '%d', '%s' )
		);
		if ( ! $inserted ) {
			return new \WP_Error( 'failed-to-insert', __( 'Failed to insert data', 'wdac' ) );
		}
		return $wpdb->insert_id;
	}

}
/**
 * Fetch Address
 *
 * @param array $args
 *
 * @return array
 */
function wd_ac_get_addresses( $args = array() ) {
	global $wpdb;
	$defaults = array(
		'number'  => 20,
		'offset'  => 0,
		'orderby' => 'id',
		'order'   => 'DESC',
	);

	$args  = wp_parse_args( $args, $defaults );
	$items = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}wd_ac_addreses
				ORDER BY {$args['orderby']} {$args['order']}
				LIMIT %d, %d",
			$args['offset'],
			$args['number']
		)
	);
	return $items;
}

/*
* Get the count of total address
*
* @return init
*
*/

function wd_ac_address_count() {

	global $wpdb;

	$count = wp_cache_get( 'count', 'address' );

	if ( false === $count ) {
		$count = (int) $wpdb->get_var( "SELECT count(id) FROM {$wpdb->prefix}wd_ac_addreses" );

		wp_cache_set( 'count', $count, 'address' );

	}

	return $count;

}


/**
 * Fetch a single contact from the DB
 *
 * @param  int $id
 *
 * @return object
 */
function wd_ac_get_address( $id ) {
	global $wpdb;

	// return $wpdb->get_row(
	// $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wd_ac_addreses WHERE id = %d", $id )
	// );

	$address = wp_cache_get( 'book-' . $id, 'address' );

	if ( false === $address ) {
		$address = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wd_ac_addreses WHERE id = %d", $id )
		);

		wp_cache_set( 'book-' . $id, $address, 'address' );
	}

	return $address;

}



/**
 * Delete an address
 *
 * @param  int $id
 *
 * @return int|boolean
 */
function wd_ac_delete_address( $id ) {
	global $wpdb;
	return $wpdb->delete(
		$wpdb->prefix . 'wd_ac_addreses',
		array( 'id' => $id ),
		array( '%d' )
	);
}

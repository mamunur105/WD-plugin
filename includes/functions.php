<?php
/*
* Insert a new error
* 
* Param Array $args
*
* Return int/WP_Error
*/
function wd_ac_insert($args=[]){

 	if (empty($args['name'])) {
 		return new \WP_Error('no-name',__('You must Provide a name','wdac'));
 	}
 	global $wpdb;
 	$default = [
 		'name'=>'',
 		'address'=>'',
 		'phone'=>'',
 		'created_by'=> get_current_user_id(),
 		'created_at'=> current_time('mysql')
 	];
 	$data = wp_parse_args($args,$default);
 	$inserted = $wpdb->insert(
 		"{$wpdb->prefix}wd_ac_addreses",
 		$data,
 		array('%s','%s','%s','%d','%s')
 	);
 	if (!$inserted) {
 		return new \WP_Error('failed-to-insert',__('Failed to insert data','wdac'));
 	}
 	return $wpdb->insert_id;
 }
/**
* Fetch Address
*
* @param array $args
*
* @return array
*
*/
function wd_ac_get_address($args=[]){
	global $wpdb ;
	$defaults = array(
		'number' 	=> 20,
		'offset' 	=> 0,
		'orderby'	=> 'id',
		'order' 	=> 'DESC'
	);

	$args = wp_parse_args( $args, $defaults );
	$items = $wpdb->get_results(
		$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}wd_ac_addreses
				ORDER BY {$args['orderby']} {$args['order']}
				LIMIT %d, %d",
				$args['offset'],$args['number']
	));
	return $items;
}

/*
* Get the count of total address
*
* @return init
*
*/

function wd_ac_address_count(){
	global $wpdb ;
	return (int) $wpdb->get_var("SELECT count(id) FROM {$wpdb->prefix}wd_ac_addreses");
}
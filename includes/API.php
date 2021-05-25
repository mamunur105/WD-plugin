<?php

namespace Wd\Ac;

class API {
	function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_api' ) );
	}

	function register_api() {
		$Addressbooks = new API\Addressbooks();
		$Addressbooks->register_routes();
	}
}

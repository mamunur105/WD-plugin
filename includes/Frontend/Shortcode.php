<?php

namespace Wd\Ac\Frontend;

/**
 * Shortcode handaler class 
 */
class Shortcode 
{
	/**
	 * Initilize the class 
	 */
	function __construct(){
		add_shortcode('wd-ac',[$this,'random_shortcode']);
	}
	/**
	 * Shortcode handles class
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string 
	 */
	function random_shortcode($attr,$contant){
		wp_enqueue_script( 'academy-script' );
        wp_enqueue_style( 'academy-style' );

        return '<div class="academy-shortcode">Hello from Shortcode</div>';
	}
}
<?php
/**
 * Plugin Name:       WD-plugin
 * Plugin URI:        https://profiles.wordpress.org/mamunur105/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mamun
 * Author URI:        https://profiles.wordpress.org/mamunur105/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wdac
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

final class WD_ac{
   /**
	* Plugin version 
	*/
	const version = 1.0;

	/**
	 * class constructr 
	 *
	 */
	private function __construct(){
		$this->define_constant();
		// echo WD_AC_URL;

		register_activation_hook(__FILE__,[ $this,'activate' ]);
		add_action('plugin_loaded',[ $this,'init_plugin' ]);
	}
	/**
	 * initilize a singleton instance
	 * @return \WD_ac
	 */
	public static function init(){
		static $instance = false;
		if (!$instance) {
			$instance = new self();
		}
		return $instance;
	}
	/**
	 *  Requred constant
	 * @return \WD_ac
	 */

	public function define_constant(){
		define('WD_AC_VERSION', self::version);
		define('WD_AC_FILE', __FILE__);
		define('WD_AC_PATH', __DIR__);
		define('WD_AC_URL', plugins_url('',WD_AC_FILE));
		define('WD_AC_ASSETS', WD_AC_URL.'/assets');
	}

	/**
	 *  Do stuff upon activation
	 * @return 
	 */
	public function activate(){
		$installed = get_option('wd_ac_installed_time');
		if (!$installed) {
			update_option('wd_ac_installed_time',time());
		}
		update_option('wd_ac_version',WD_AC_VERSION);
	}

	public function init_plugin(){
		if (is_admin()) {
			new Wd\Ac\Admin();	
		}else{
			
			new Wd\Ac\Frontend();	
			
		}
		
	}


}

function wd_ac(){
	return WD_ac::init();
}

wd_ac();
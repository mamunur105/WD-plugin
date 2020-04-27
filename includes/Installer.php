<?php
namespace Wd\Ac;

/**
 * 
 */
class Installer
{
	
	function run(){
		$this->add_version();
		$this->create_tables();
	}
	public function add_version(){
		// echo "string";
		$installed = get_option('wd_ac_installed_time');
		if (!$installed) {
			update_option('wd_ac_installed_time',time());
		}
		update_option('wd_ac_version',WD_AC_VERSION);
	}

	public function create_tables(){
		global $wpdb;
		if (!function_exists('dbDelta')) {
			require_once ABSPATH.'wp-admin/includes/upgrade.php';
		}
		$charset_collate = $wpdb->get_charset_collate();
		$sql_query = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wd_ac_addreses` (	
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				 `name` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
				 `address` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
				 `phone` varchar(30) COLLATE utf8mb4_unicode_520_ci NOT NULL,
				 `created_by` int(20) NOT NULL,
				 `created_at` datetime NOT NULL,
				 PRIMARY KEY (`id`)
				) $charset_collate";

		dbDelta($sql_query);
	}
	
}

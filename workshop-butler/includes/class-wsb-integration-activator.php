<?php
/**
 * Fired during plugin activation
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( __FILE__ ) . 'class-wsb-integration-upgrade.php';

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Integration_Activator {

	/**
	 * Adds required pages on the plugin activation if they are not added before
	 *
	 * @since    2.0.0
	 */
	public static function activate() {
		$upgrader = new WSB_Integration_Upgrade();
		$upgrader->upgrade();
	}
}

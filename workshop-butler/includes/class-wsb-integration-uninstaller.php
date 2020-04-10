<?php
/**
 * Fired during plugin uninstall
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( __FILE__ ) . 'class-wsb-options.php';

/**
 * Fired during plugin uninstall.
 *
 * This class defines all code necessary to run during the plugin's uninstall.
 *
 * @since      2.9.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Integration_Uninstaller {

	/**
	 * Removes the plugin settings on uninstall process.
	 *
	 * Cleans all plugins settings
	 *
	 * @since 2.9.0
	 */
	public static function uninstall() {
		// if uninstall.php is not called by WordPress, die.
		if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
			die;
		}

		delete_option( WSB_Options::PLUGIN_SETTINGS );
		delete_option( WSB_Options::INTERNAL_SETTINGS );
	}
}

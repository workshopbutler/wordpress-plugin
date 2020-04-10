<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( __FILE__ ) . 'class-wsb-options.php';

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Integration_Deactivator {

	/**
	 * Removes the plugin settings on deactivation.
	 *
	 * Cleans all public plugins settings and stores only the internal ones to support a correct version migration
	 *
	 * @since    2.0.0
	 */
	public static function deactivate() {
		WSB_Options::set_internal_option( WSB_Options::INT_STATE, false );
	}

}

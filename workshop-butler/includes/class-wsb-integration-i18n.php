<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 * @subpackage WSB_Integration/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @subpackage WSB_Integration/includes
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Integration_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.2.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wsbintegration',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

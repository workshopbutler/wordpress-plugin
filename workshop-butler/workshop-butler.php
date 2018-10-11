<?php

/**
 * @link              https://workshopbutler.com
 * @since             2.0.0
 * @package           WSB_Integration
 *
 * @wordpress-plugin
 * Plugin Name:       Workshop Butler
 * Plugin URI:        https://workshopbutler.com/plugin-name-uri/
 * Description:       This plugin integrates Workshop Butler Events and Trainers to your WordPress website.
 * Version:           2.0.0
 * Author:            Workshop Butler
 * Author URI:        https://workshopbutler.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wsbintegration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WSB_INTEGRATION_VERSION', '2.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wsb-integration-activator.php
 */
function activate_WSB_Integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsb-integration-activator.php';
	WSB_Integration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wsb-integration-deactivator.php
 */
function deactivate_WSB_Integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsb-integration-deactivator.php';
	WSB_Integration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_WSB_Integration' );
register_deactivation_hook( __FILE__, 'deactivate_WSB_Integration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wsb-integration.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_WSB_Integration() {

	$plugin = new WSB_Integration();
	$plugin->run();

}

run_WSB_Integration();

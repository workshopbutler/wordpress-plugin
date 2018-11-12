<?php
/**
 * This plugin integrates Workshop Butler Events, Trainers and Testimonials to your WordPress website.
 *
 * @link              https://workshopbutler.com
 * @since             2.0.0
 * @package           WSB_Integration
 *
 * @wordpress-plugin
 * Plugin Name:       Workshop Butler
 * Plugin URI:        https://github.com/workshopbutler/wordpress-plugin
 * Description:       This plugin integrates Workshop Butler Events, Trainers and Testimonials to your WordPress website.
 * Version:           2.1.0
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
define( 'WSB_INTEGRATION_VERSION', '2.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wsb-integration-activator.php
 */
function activate_wsb_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsb-integration-activator.php';
	WorkshopButler\WSB_Integration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wsb-integration-deactivator.php
 */
function deactivate_wsb_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsb-integration-deactivator.php';
	WorkshopButler\WSB_Integration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wsb_integration' );
register_deactivation_hook( __FILE__, 'deactivate_wsb_integration' );

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
function run_wsb_integration() {

	$plugin = new WorkshopButler\WSB_Integration();
	$plugin->run();

}

run_wsb_integration();

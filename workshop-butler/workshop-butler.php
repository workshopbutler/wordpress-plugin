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
 * Description:       This plugin integrates Workshop Butler Events, Trainers and Testimonials to your WordPress
 *     website.
 * Version:           3.1.4
 * Author:            Workshop Butler
 * Author URI:        https://workshopbutler.com/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 * Text Domain:       wsbintegration
 * Domain Path:       /languages
 * Requires PHP:      7.2.5
 */

// Load composer modules
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

use WorkshopButler\WSB_Integration;

defined( 'ABSPATH' ) || exit;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WSB_INTEGRATION_VERSION', '3.1.4' );

/**
 * Version of Workshop Butler API, used by this plugin
 */
define( 'WSB_API_VERSION', '2021-09-26' );

if ( ! defined( 'WSB_PLUGIN_FILE' ) ) {
	define( 'WSB_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'WSB_ABSPATH' ) ) {
	define( 'WSB_ABSPATH', dirname( WSB_PLUGIN_FILE ) . '/' );
}


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

/**
 * The code that runs during plugin removal.
 * This action is documented in includes/class-wsb-integration-uninstaller.php
 */
function remove_wsb_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsb-integration-uninstaller.php';
	WorkshopButler\WSB_Integration_Uninstaller::uninstall();
}

register_activation_hook( __FILE__, 'activate_wsb_integration' );
register_deactivation_hook( __FILE__, 'deactivate_wsb_integration' );
register_uninstall_hook( __FILE__, 'remove_wsb_integration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wsb-integration.php';

/**
 * Returns the main instance of WSB.
 *
 * @return WSB_Integration
 * @since    3.0.0
 */
function WSB() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return WSB_Integration::instance();
}

// Global for backwards compatibility.
$GLOBALS['workshopbutler'] = WSB();

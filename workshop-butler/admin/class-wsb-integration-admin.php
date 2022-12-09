<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Integration_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string $integration The ID of this plugin.
	 */
	private $integration;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 *
	 * @param      string $integration The name of this plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $integration, $version ) {
		$this->integration = $integration;
		$this->version     = $version;

		$this->load_dependencies();
		$this->init();
	}

	/**
	 * Initializes plugin options
	 *
	 * @since 2.0.0
	 */
	public function init() {
		$settings = new WSB_Settings( WSB_Options::PLUGIN_SETTINGS );
		$settings->init();
	}

	/**
	 * Load the required dependencies for this class.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		if ( ! class_exists( 'WorkshopButler\ReduxFramework' )
			&& file_exists( dirname( __FILE__ ) . '/../lib/ReduxFramework/ReduxCore/framework.php' ) ) {
			require_once dirname( __FILE__ ) . '/../lib/ReduxFramework/ReduxCore/framework.php';
		}
		require_once plugin_dir_path( __FILE__ ) . '/../includes/class-wsb-options.php';
		require_once plugin_dir_path( __FILE__ ) . '/includes/class-wsb-settings.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {
		// empty.
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 2.0.0
	 */
	public function enqueue_scripts() {
		// empty.
	}

}

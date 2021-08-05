<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

use WorkshopButler\Hooks\All_Hooks;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @subpackage WSB_Integration/includes
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Integration {

	/**
	 * The single instance of the class.
	 *
	 * @var WSB_Integration
	 * @since 3.0.0
	 */
	protected static $instance = null;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      WSB_Integration_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Plugin dictionary
	 *
	 * @var WSB_Dictionary $dict
	 * @since 3.0.0
	 */
	public $dict;

	/**
	 * Plugin settings
	 *
	 * @since   2.0.0
	 * @var     WSB_Options $settings Plugin settings
	 */
	public $settings;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function __construct() {
		if ( defined( 'WSB_INTEGRATION_VERSION' ) ) {
			$this->version = WSB_INTEGRATION_VERSION;
		} else {
			$this->version = '2.0.0';
		}
		$this->plugin_name = 'workshop-butler-plugin';

		$this->define_constants();
		$this->load_dependencies();
		$this->set_locale();
		$this->define_common_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->loader->run();
	}


	/**
	 * Main WorkshopButler Instance.
	 *
	 * Ensures only one instance of WorkshopButler is loaded or can be loaded.
	 *
	 * @return WSB_Integration - Main instance.
	 * @see WSB()
	 * @since 2.1
	 * @static
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( WSB_PLUGIN_FILE ) );
	}

	/**
	 * Get the template path.
	 *
	 * @return string
	 */
	public function template_path() {
		return apply_filters( 'wsb_template_path', 'workshop-butler/' );
	}

	/**
	 * Define WSB Constants.
	 */
	private function define_constants() {
		$this->define( 'WSB_TEMPLATE_DEBUG_MODE', false );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name Constant name.
	 * @param string|bool $value Constant value.
	 *
	 * @since 3.0.0
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WSB_Integration_Loader. Orchestrates the hooks of the plugin.
	 * - WSB_Integration_i18n. Defines internationalization functionality.
	 * - WSB_Integration_Admin. Defines all hooks for the admin area.
	 * - WSB_Integration_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once WSB_ABSPATH . 'includes/class-wsb-integration-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once WSB_ABSPATH . 'includes/class-wsb-integration-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once WSB_ABSPATH . 'admin/class-wsb-integration-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once WSB_ABSPATH . 'public/class-wsb-integration-public.php';

		$this->loader = new WSB_Integration_Loader();

		/**
		 * The class responsible rendering and configuring a sidebar
		 */
		require_once WSB_ABSPATH . 'public/includes/class-sidebar-widget.php';

		/**
		 * The class responsible for orchestrating the upgrade plugin process
		 */
		require_once WSB_ABSPATH . 'includes/class-wsb-integration-upgrade.php';

		/**
		 * The class responsible for defining all template system hooks
		 */
		require_once WSB_ABSPATH . 'public/includes/hooks/class-all-hooks.php';

		All_Hooks::init();

		/**
		 * The class responsible for providing an access to entities, loaded from API
		 */
		require_once WSB_ABSPATH . 'public/includes/class-wsb-dictionary.php';

		$this->dict = new WSB_Dictionary();

		/**
		 * The class responsible for all plugin-related options
		 * core plugin.
		 */
		require_once WSB_ABSPATH . 'includes/class-wsb-options.php';

		$this->settings = new WSB_Options();
	}

	/**
	 * Returns true if the request is a non-legacy REST API request.
	 *
	 * @since    3.0.0
	 * @return bool
	 */
	public function is_rest_api_request() {
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		$rest_prefix         = trailingslashit( rest_get_url_prefix() );
		$is_rest_api_request = ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) ); // phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		return apply_filters( 'wsb_is_rest_api_request', $is_rest_api_request );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WSB_Integration_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WSB_Integration_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new WSB_Integration_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'init' );

		$upgrader = new WSB_Integration_Upgrade();
		$this->loader->add_action( 'admin_init', $upgrader, 'upgrade' );
		$this->loader->add_action( 'init', $upgrader, 'upgrade', 15 );
	}

	/**
	 * Register the hooks related to both the admin and public-facing area functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_common_hooks() {
		add_action( 'widgets_init', array( 'WorkshopButler\Sidebar_Widget', 'init' ) );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new WSB_Integration_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		if ( ! is_admin() ) {
			$this->loader->add_action( 'init', $plugin_public, 'init' );
			$this->loader->add_filter( 'pre_get_document_title', $plugin_public, 'set_document_title', 99 );
			$this->loader->add_filter( 'the_title', $plugin_public, 'set_title', 10, 2 );
			// Yoast SEO hooks
			$this->loader->add_filter( 'wpseo_frontend_presenter_classes', $plugin_public, 'wpseo_frontend_presenters', 10, 1 );
			$this->loader->add_filter( 'wpseo_opengraph_title', $plugin_public, 'set_document_title' );
			$this->loader->add_filter( 'wpseo_add_opengraph_additional_images', $plugin_public, 'wpseo_add_opengraph_additional_images' );
			// Also useful hooks wpseo_metadesc, wpseo_opengraph_desc
		}
		$this->loader->add_action( 'init', $plugin_public, 'add_ajax_handlers' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    2.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     2.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    WSB_Integration_Loader    Orchestrates the hooks of the plugin.
	 * @since     2.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     2.0.0
	 */
	public function get_version() {
		return $this->version;
	}

}

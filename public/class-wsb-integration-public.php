<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 * @subpackage WSB_Integration/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    WSB_Integration
 * @subpackage WSB_Integration/public
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Integration_Public {
    
    /**
     * The ID of this plugin.
     *
     * @since    0.2.0
     * @access   private
     * @var      string $WSB_Integration The ID of this plugin.
     */
    private $WSB_Integration;
    
    /**
     * The version of this plugin.
     *
     * @since    0.2.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    0.2.0
     *
     * @param      string $WSB_Integration The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct( $WSB_Integration, $version ) {
        
        $this->WSB_Integration = $WSB_Integration;
        $this->version         = $version;
        $this->load_dependencies();
    }
    
    /**
     * Load the required dependencies for this class.
     *
     * @since    0.2.0
     * @access   private
     */
    private function load_dependencies() {
        
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsb-trainer-list.php';
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsb-trainer-page.php';
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsb-event-list.php';
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsb-event-page.php';
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsb-ajax.php';
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    0.2.0
     */
    public function enqueue_styles() {
        wp_register_style("wsb-fontawesome-styles", plugin_dir_url( __FILE__ ) . 'css/fontawesome-all.min.css', array(), $this->version);
        wp_register_style("wsb-themes", plugin_dir_url( __FILE__ ) . 'css/themes.0.3.1.min.css', array(), $this->version);
        wp_enqueue_style( 'wsb-themes');
        wp_enqueue_style( 'wsb-fontawesome-styles');
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    0.2.0
     */
    public function enqueue_scripts() {
        wp_register_script("wsb-helper-scripts", plugin_dir_url( __FILE__ ) .  'js/helper-scripts.js', array( "jquery" ), $this->version, true);
        wp_register_script("wsb-all-trainers-scripts", plugin_dir_url( __FILE__ ) .  'js/all-trainers-scripts.js', array( "jquery" ), $this->version, true);
        wp_register_script("wsb-single-event-scripts", plugin_dir_url( __FILE__ ) . 'js/single-event-scripts.js', array( "jquery", "wsb-helper-scripts", "wsb-dateformat" ), $this->version, true);
    
        wp_register_script("wsb-single-trainer-scripts", plugin_dir_url( __FILE__ ) . 'js/single-trainer-scripts.js', array( "jquery", "wsb-helper-scripts", "wsb-dateformat" ), $this->version, true);
    
        wp_register_script("wsb-dateformat", plugin_dir_url( __FILE__ ) . 'js/jquery-dateFormat.min.js', array( "jquery" ), $this->version, true);
        wp_register_script("wsb-all-events-scripts", plugin_dir_url( __FILE__ ) . 'js/all-events-scripts.js', array( "jquery" ), $this->version, true);
    
        wp_enqueue_script( $this->WSB_Integration, plugin_dir_url( __FILE__ ) . 'js/plugin-name-public.js', array( 'jquery' ), $this->version, false );
    }
    
    /**
     * Adds Workshop Butler shortcodes
     */
    public function add_shortcodes() {
        add_shortcode('wsb_events', array('WSB_Event_List', 'shortcode'));
        add_shortcode('wsb_event_details', array('WSB_Event_Page', 'shortcode'));
    
        add_shortcode('wsb_trainers', array('WSB_Trainer_List', 'shortcode'));
        add_shortcode('wsb_trainer_details', array('WSB_Trainer_Page', 'shortcode'));
    }
    
    /**
     * Registers AJAX handlers
     */
    public function add_ajax_handlers() {
        add_action( 'wp_ajax_nopriv_wsb_get_values', array('WSB_Ajax', 'get_values'));
        add_action( 'wp_ajax_wsb_get_values', array('WSB_Ajax', 'get_values') );
    
        add_action( 'wp_ajax_nopriv_wsb_register_to_event', array('WSB_Ajax', 'register_to_event'));
        add_action( 'wp_ajax_wsb_register_to_event', array('WSB_Ajax', 'register_to_event'));
    }
}

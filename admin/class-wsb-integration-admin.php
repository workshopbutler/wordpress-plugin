<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WSB_Integration
 * @subpackage WSB_Integration/admin
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Integration_Admin {
    
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
     * @param      string $WSB_Integration The name of this plugin.
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
     * @since    0.3.0
     * @access   private
     */
    private function load_dependencies() {
        if ( !class_exists( 'ReduxFramework' )
             && file_exists( dirname( __FILE__ ) . '/../lib/ReduxFramework/ReduxCore/framework.php' ) ) {
            require_once( dirname( __FILE__ ) . '/../lib/ReduxFramework/ReduxCore/framework.php' );
        }
        if ( !isset( $redux_demo ) && file_exists( dirname( __FILE__ ) . '/config.php' ) ) {
            require_once( dirname( __FILE__ ) . '/config.php' );
        }
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    0.2.0
     */
    public function enqueue_styles() {
        //empty
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @since 0.2.0
     */
    public function enqueue_scripts() {
        //empty
    }
    
}

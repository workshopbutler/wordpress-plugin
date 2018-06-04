<?php
/**
 * The file that defines WSB_Page class
 *
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 * @subpackage WSB_Integration/includes
 */

/**
 * Represents an integrated page
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @subpackage WSB_Integration/includes
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
abstract class WSB_Page {
    /**
     * Template engine
     *
     * @since    0.2.0
     * @access   protected
     * @var      \Timber\Timber $engine Template engine.
     */
    protected $engine;
    
    /**
     * Plugin settings
     *
     * @access  protected
     * @since   0.3.0
     * @var     WSB_Options $settings Plugin settings
     */
    protected $settings;
    
    /**
     * Creates a template engine entity
     *
     * @since 0.2.0
     */
    public function __construct() {
        require_once plugin_dir_path(__FILE__) . '../../vendor/autoload.php';
        \Timber\Timber::$locations = plugin_dir_path(__FILE__) . '../../views';
        $this->engine = new Timber\Timber();
        
        $this->load_dependencies();
    }
    
    /**
     * Load the required dependencies
     *
     * @since    0.3.0
     * @access   private
     */
    private function load_dependencies() {
        
        /**
         * The class responsible for all plugin-related options
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . '../includes/class-wsb-options.php';
        
        $this->settings = new WSB_Options();
    }
    
    
    /**
     * Returns an active theme for the integration
     *
     * @since  0.2.0
     * @return mixed
     */
    protected function get_theme() {
        return $this->settings->get( WSB_Options::THEME, 'alfred' );
    }
    
}

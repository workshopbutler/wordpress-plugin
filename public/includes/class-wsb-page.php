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
     * @param $name
     * @param $content
     * @param $handler Closure
     *
     * @return string
     */
    protected function handle_trainer_shortcode( $name, $content, $handler ) {
        $trainer = $this->get_trainer();
        if (is_null($trainer)) {
            return '';
        }
        $template = $this->get_template($name, $content);
        if (is_null($template)) {
            return '';
        }
        return $handler->call($this, $trainer, $template);
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
    
    /**
     * Returns a currently-processed trainer
     *
     * @since  0.3.0
     * @return Trainer|null
     */
    protected function get_trainer() {
        if (!isset($GLOBALS['wsb_trainer']) || !is_a($GLOBALS['wsb_trainer'], 'Trainer')) {
            return null;
        }
        return $GLOBALS['wsb_trainer'];
    }
    
    /**
     * Returns a currently-processed endorsement
     *
     * @since  0.3.0
     * @return object|null
     */
    protected function get_endorsement() {
        if (!isset($GLOBALS['wsb_endorsement']) || !is_object($GLOBALS['wsb_endorsement'])) {
            return null;
        }
        return $GLOBALS['wsb_endorsement'];
    }
    
    /**
     * Returns the named template or 'null' if it doesn't exist
     *
     * @param $name    string       Name of the template
     * @param $content null|string  Template content
     *
     * @since  0.3.0
     * @return null|string
     */
    protected function get_template($name, $content) {
        if (empty($content)) {
            $filename = plugin_dir_path( dirname(__FILE__) ) . '../views/' . $name . '.twig';
            $content = file_get_contents($filename);
            if (!$content) {
                return null;
            }
        }
        return $content;
    }
}

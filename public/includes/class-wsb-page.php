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
     * Creates a template engine entity
     *
     * @since 0.2.0
     */
    public function __construct() {
        require_once plugin_dir_path(__FILE__) . '../../vendor/autoload.php';
        \Timber\Timber::$locations = plugin_dir_path(__FILE__) . '../../views';
        $this->engine = new Timber\Timber();
    }
    
    /**
     * Returns an active theme for the integration
     *
     * @since  0.2.0
     * @return mixed
     */
    protected function get_theme() {
        return $this->get_option_value('wsb_field_theme', 'alfred');
    }
    
    /**
     * Returns the value of the option
     *
     * @since 0.2.0
     * @param $name           string Option name
     * @param $default_value  string Default value if the option is not set
     *
     * @return mixed
     */
    protected function get_option_value($name, $default_value) {
        $options = get_option( 'wsb_options' );
        $is_option_set = $options[$name] !== "" && $options[$name] != null;
    
        return $is_option_set ? $options[$name] : $default_value;
    }
}

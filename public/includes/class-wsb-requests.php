<?php
/**
 * The file that defines the request wrapper class
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
define( 'WSB_API_END_POINT', 'https://api.workshopbutler.com/' );
require_once plugin_dir_path( __FILE__ ) . 'class-wsb-response.php';

/**
 * The request wrapper class
 *
 * It's used to make requests to Workshop Butler API in a correct way
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Requests {
    
    /**
     * Plugin settings
     *
     * @access  protected
     * @since   0.3.0
     * @var     WSB_Options $settings Plugin settings
     */
    protected $settings;
    
    /**
     * Initialises a new object
     *
     * @since 0.3.0
     */
    public function __construct() {
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
     * Makes GET request
     *
     * @param $method string API method
     * @param $query array API query parameters
     *
     * @return WSB_Response
     */
    public function get( $method, $query ) {
        $query["api_key"] = $this->settings->get ( WSB_Options::API_KEY );
        $url = WSB_API_END_POINT . $method . '?' . http_build_query($query);
        return new WSB_Response(wp_remote_get($url));
    }
    
    /**
     * Makes POST request
     *
     * @param $method string API method
     * @param $data array Post data
     *
     * @return WSB_Response
     */
    public function post( $method, $data ) {
        $api_key = $this->settings->get ( WSB_Options::API_KEY );
    
        $data_string = json_encode($data);
    
        $url = WSB_API_END_POINT . $method . '?api_key=' . $api_key;
    
        $headers = array(
            'content-type' => 'application/json',
            'content-length' => strlen($data_string));
    
        $resp = wp_remote_post($url, array(
                'method' => 'POST',
                'headers' => $headers,
                'body' => $data_string)
        );
    
        return new WSB_Response($resp);
    }
    
}

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
     * Makes GET request
     *
     * @param $method string API method
     * @param $query array API query parameters
     *
     * @return WSB_Response
     */
    public function get( $method, $query ) {
        $options = get_option( 'wsb_options' );
    
        $query["api_key"] = $options["wsb_field_api_key"];
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
        $options = get_option( 'wsb_options' );
    
        $api_key = $options["wsb_field_api_key"];
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

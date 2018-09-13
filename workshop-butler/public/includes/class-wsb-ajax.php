<?php

require_once plugin_dir_path( __FILE__ ) . 'ui/class-embed-event-list.php';
require_once plugin_dir_path( __FILE__ ) . 'class-wsb-requests.php';

/**
 * The file that defines the Ajax-related logic
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */

class WSB_Ajax {
    
    static public function get_values() {
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            // Nonce check
            check_ajax_referer( 'wsb-nonce' );
            
            $type = $_GET['type'];
            
            switch ( $type ) {
                case "future-events-country":
                    $method = 'events';
                    $query  = array( 'future' => 'true', 'countryCode' => rawurlencode( $_GET['country_code'] ) );
                    break;
                case "future-trainer-events":
                    $method = 'facilitators/' . rawurlencode( $_GET['id'] ) . '/events';
                    $query  = array( 'future' => 'true' );
                    break;
                case "past-trainer-events":
                    $method = 'facilitators/' . rawurlencode( $_GET['id'] ) . '/events';
                    $query  = array( 'future' => 'false' );
                    break;
                default:
                    die();
                    break;
            }
            $requests = new Embed_Event_List();
            echo $requests->render( $method, $query );
            wp_die();
        } else {
            exit();
        }
    }
    
    static public function register_to_event() {
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            check_ajax_referer( 'wsb-nonce' );

            $form_data = $_POST;
            unset($form_data['action']);
            unset($form_data['_ajax_nonce']);
    
            $requests = new WSB_Requests();
            $response = $requests->post( 'attendees/register', $form_data );
            wp_send_json($response->body, $response->http_code);
        } else {
            exit();
        }
    }
}

<?php
/**
 * The file that defines the event page class
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path(__FILE__) . 'class-wsb-page.php';

/**
 * Event Page class which handles the rendering and logic for the event page
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Event_Page extends WSB_Page {
    
    private $requests;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    0.2.0
     */
    public function __construct() {
        parent::__construct();
        $this->load_dependencies();
        $this->requests = new WSB_Requests();
    }
    
    /**
     * Load the required dependencies for this class.
     *
     * @since    0.2.0
     * @access   private
     */
    private function load_dependencies() {
        require_once plugin_dir_path( __FILE__ ) . 'helper-functions.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-wsb-requests.php';
        require_once plugin_dir_path(__FILE__) . 'models/class-event.php';
    }
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    0.2.0
     */
    public function render( $attrs = [], $content = null ) {
        // normalize attribute keys, lowercase
        $atts = array_change_key_case((array)$attrs, CASE_LOWER);
    
        // override default attributes with user attributes
        $wsb_atts = shortcode_atts([
            'id' => -1,
        ], $atts);
    
        $data = null;
        $id = ( ! empty( $_GET['id'] ) ) ? $_GET['id'] : '';
        if($id !== "") {
            wp_enqueue_script("wsb-helper-scripts");
            wp_enqueue_script("wsb-dateformat");
            wp_enqueue_script("wsb-single-event-scripts");
        
            $wsb_nonce = wp_create_nonce( 'wsb-nonce' );
        
            $internal_options = get_option("wsb_internal_options");
    
            $method = 'events/';
            $method .= rawurlencode($id);
            $query = array();
            $data = json_decode($this->requests->get($method, $query));
            if( $data == null || $data->code == 404) {
                $html = "<h2>" . __('Error 404 - Not Found', 'wsbintegration')  . "</h2>";
                $html .= "<p>" . __('Sorry, but the page you were looking for could not be found.', 'wsbintegration')  . "</p>";
                return $html;
            }
    
            $event = $this->get_event( $data );
    
            wp_localize_script( 'wsb-single-event-scripts', 'wsb_single_event', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce' => $wsb_nonce,
                'country' => $data->country,
                'is_registration_closed' => $event->is_registration_closed(),
                'registration_url' => $event->get_registration_url(),
                'id' => $data->id,
                'error_required' => __("This field is required.", 'wsbintegration'),
                'error_email' => __("Please enter a valid email address.", 'wsbintegration'),
                'error_url' => __("Please enter a valid URL.", 'wsbintegration'),
                'error_date' => __("Please enter a valid date.", 'wsbintegration'),
                'error_dateiso' => __("Please enter a valid date (ISO).", 'wsbintegration'),
                'error_nospace' => __("Please enter a valid number.", 'wsbintegration'),
                'error_digits' => __("Please enter only digits.", 'wsbintegration'),
                'error_upper' => "",
                'error_floats' => __("Please enter only digits.", 'wsbintegration'),
                'string_validation_errors' => __('Validation errors occurred. Please confirm the fields and try again.', 'wsbintegration'),
                'string_error_try_again' => __('The server doesn\'t response. Please try again. If the error persists please contact your trainer.', 'wsbintegration'),
                'string_try_again' => __('Please try again. If the error persists please contact your trainer.', 'wsbintegration'),
                'single_event_url' => get_permalink($internal_options['event_detail_page_id']),
            ));
            
        }
        $html = $this->render_page( $event );
    
        return $html;
    }
    
    /**
     * Returns a new event from the given data
     * @param $data object JSON event data
     *
     * @return Event
     */
    private function get_event( $data ) {
        $internal_options = get_option( "wsb_internal_options" );
        $event_page_url = get_permalink( $internal_options['event_detail_page_id'] );
        $trainer_page_url = get_permalink( $internal_options['trainer_detail_page_id'] );
    
        return new Event($data, $event_page_url, $trainer_page_url);
    }
    
    /**
     * Renders the event page
     *
     * @param $event Event
     *
     * @return string
     */
    private function render_page( $event ) {
        $filename = 'event-page.twig';
        $countries = wsb_get_countries();
        sort($countries);
        $template_data = array('event' => $event,
                               'theme' => $this->get_theme(),
                               'countries' => $countries);
    
        return $this->engine->fetch($filename, $template_data);
    }
    
    static public function shortcode( $attrs = [], $content = null ) {
        $page = new WSB_Event_Page();
        
        return $page->render( $attrs, $content );
    }
}

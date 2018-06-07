<?php
/**
 * The file that defines the event page class
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( dirname(__FILE__) ) . 'class-wsb-page.php';

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
        require_once plugin_dir_path(  dirname(__FILE__) ) . '/../../includes/class-wsb-options.php';
        require_once plugin_dir_path(  dirname(__FILE__) ) . 'helper-functions.php';
        require_once plugin_dir_path(  dirname(__FILE__) ) . 'class-wsb-requests.php';
        require_once plugin_dir_path( dirname(__FILE__) ) . 'models/class-event.php';
    }
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    0.2.0
     */
    public function render( $attrs = [], $content = null ) {
        $id = ( ! empty( $_GET['id'] ) ) ? $_GET['id'] : '';
        if($id !== "") {
            wp_enqueue_script("wsb-helper-scripts");
            wp_enqueue_script("wsb-dateformat");
            wp_enqueue_script("wsb-single-event-scripts");
        
        
            $method = 'events/';
            $method .= rawurlencode($id);
            $query = array();
            $response = $this->requests->get($method, $query);
            if ( $response->is_error()) {
                $html = "<h2>" . __('Workshop Butler API: Request failed', 'wsbintegration')  . "</h2>";
                $html .= "<p>" . __('Reason : ', 'wsbintegration') . $response->error . "</p>";
                return $html;
            }
    
            $event = new Event($response->body,
                $this->settings->get_event_page_url(),
                $this->settings->get_trainer_page_url());
            
            $this->add_localized_script($response->body,  $event);
            
            $html = $this->render_page( $event );
            
        } else {
            $html = "<h2>" . __('Workshop Butler API: Request failed', 'wsbintegration')  . "</h2>";
            $html .= "<p>" . __('Reason : empty event ID', 'wsbintegration') . "</p>";
        }
    
        return $html;
    }
    
    /**
     * Adds a localized version of JS script on the page
     * @param $event_data object Event object from API
     * @param $event      Event  Event
     */
    protected function add_localized_script($event_data, $event) {
        $wsb_nonce = wp_create_nonce( 'wsb-nonce' );
        wp_localize_script( 'wsb-single-event-scripts', 'wsb_single_event', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => $wsb_nonce,
            'country' => $event_data->country,
            'is_registration_closed' => $event->is_registration_closed(),
            'registration_url' => $event->get_registration_url(),
            'id' => $event->id,
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
            'single_event_url' => $this->settings->get_event_page_url(),
        ));
    }
    
    /**
     * Renders the event page
     *
     * @param $event Event
     *
     * @return string
     */
    private function render_page( $event ) {
        $countries = wsb_get_countries();
        sort($countries);
    
        $custom_template = $this->settings->get(WSB_Options::EVENT_TEMPLATE);
        $template = $this->get_template('event-page', $custom_template);
    
        $GLOBALS['wsb_event'] = $event;
        $template_data = array('event' => $event,
                               'theme' => $this->get_theme(),
                               'countries' => $countries);
    
        $processed_template = do_shortcode($template);
        $content = $this->engine->compile_string($processed_template, $template_data);
        unset($GLOBALS['wsb_event']);
        
        return $this->add_custom_styles($content);
    }
    
    static public function page( $attrs = [], $content = null ) {
        $page = new WSB_Event_Page();
        
        return $page->render( $attrs, $content );
    }
}

<?php
/**
 * The file that defines the event list class
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path(__FILE__) . 'class-wsb-page.php';

/**
 * Event List page class which handles the rendering and logic for the list of events
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Event_List extends WSB_Page {
    
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
        require_once plugin_dir_path( __FILE__ ) . '/../../includes/class-wsb-options.php';
        require_once plugin_dir_path( __FILE__ ) . 'helper-functions.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-wsb-requests.php';
        require_once plugin_dir_path(__FILE__) . 'ui/class-event-filters.php';
        require_once plugin_dir_path(__FILE__) . 'models/class-event.php';
    }
    
    /**
     * Retrieves the page data and renders it
     *
     * @param array $attrs
     * @param null $content
     *
     * @since  0.2.0
     * @return string
     */
    public function render( $attrs = [], $content = null ) {
        // Load styles and scripts only on demand.
        wp_enqueue_script("wsb-helper-scripts");
        wp_enqueue_script("wsb-dateformat");
        wp_enqueue_script("wsb-all-events-scripts");
    
        
        $method = 'events';
        $fields = 'title,city,country,hashed_id,end,start,free,type,registration_page,spoken_languages,sold_out,facilitators,free_ticket_type,paid_ticket_types';
        $query  = array('future' => true, 'public' => true, 'fields' => $fields);
    
        $response = $this->requests->get( $method, $query );
        return $this->render_list($response);
    }
    
    /**
     * Renders the list of trainers
     *
     * @param $response WSB_Response
     *
     * @since  0.2.0
     * @return string
     */
    private function render_list( $response ) {
        if ( $response->is_error()) {
            $html = "<h2>" . __('Workshop Butler API: Request failed', 'wsbintegration')  . "</h2>";
            $html .= "<p>" . __('Reason : ', 'wsbintegration') . $response->error . "</p>";
            return $html;
        }
        
        $events = [];
        foreach ( $response->body as $json_event) {
            $event = new Event( $json_event, WSB_Options::get_event_page_url(), WSB_Options::get_trainer_page_url());
            array_push($events, $event );
        }
        $event_filters = new Event_Filters($events, ['location', 'type', 'language', 'trainer']);
        $template_data = array('events' => $events,
                               'filters' => $event_filters->get_filters(),
                               'theme' => $this->get_theme());
        switch($this->get_list_type()) {
            case 'tile':
                $filename = 'event-list.twig';
                break;
            default:
                $filename = 'event-table.twig';
        }
        return $this->engine->fetch($filename, $template_data);
    }
    
    /**
     * Returns the type of event list
     *
     * @since  0.2.0
     * @return string
     */
    private function get_list_type() {
        return $this->get_option_value('wsb_field_event_list_type', 'table');
    }
    
    
    static public function shortcode( $attrs = [], $content = null ) {
        $page = new WSB_Event_List();
        return $page->render($attrs, $content);
    }
}

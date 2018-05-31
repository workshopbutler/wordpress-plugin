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
    
        $data = json_decode( $this->requests->get( $method, $query ) );
        return $this->render_list($data);
    }
    
    /**
     * Renders the list of trainers
     *
     * @param $json_events object JSON trainers data
     *
     * @since  0.2.0
     * @return string
     */
    private function render_list( $json_events ) {
        if ( $json_events == null || $json_events->code == 404) {
            $html = "<h2>" . __('Error 404 - Not Found', 'wsbintegration')  . "</h2>";
            $html .= "<p>" . __('Sorry, but the page you were looking for could not be found.', 'wsbintegration')  . "</p>";
            return $html;
        }
    
        $internal_options = get_option("wsb_internal_options");
        $trainer_page_url = get_permalink( $internal_options['trainer_detail_page_id'] );
        $event_page_url = get_permalink( $internal_options['event_detail_page_id']);

        $events = [];
        foreach ( $json_events as $json_event) {
            $event = new Event( $json_event, $event_page_url, $trainer_page_url);
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

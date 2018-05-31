<?php
/**
 * The file that defines WSB_Sidebar class
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path(__FILE__) . 'class-wsb-page.php';

/**
 * Represents a list of events in a sidebar, either on an event page or a trainer profile
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Sidebar extends WSB_Page {
    
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
        require_once plugin_dir_path( __FILE__ ) . 'class-wsb-requests.php';
        require_once plugin_dir_path(__FILE__) . 'models/class-event.php';
    }
    
    /**
     * Retrieves the page data and renders it
     *
     * @param $method string Workshop Butler API method
     * @param $query  array  API parameters
     *
     * @since  0.2.0
     * @return string
     */
    public function render( $method, $query ) {
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
    private function render_list( $json_events) {
        if ( $json_events == null || $json_events->code == 404) {
            $html = __('No events were found', 'wsbintegration');
            return $html;
        }
    
        $events = [];
        foreach ( $json_events as $json_event) {
            $event = new Event( $json_event, WSB_Options::get_event_page_url(), WSB_Options::get_trainer_page_url());
            array_push($events, $event );
        }
        $sliced = array_slice($events, 0, 5);
        $template_data = array('events' => $sliced);
        $filename = 'sidebar.twig';
        return $this->engine->fetch($filename, $template_data);
    }
}

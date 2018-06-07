<?php
/**
 * The file that defines the Schedule class
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( dirname(__FILE__) ) . 'class-wsb-page.php';

/**
 * Schedule page class which handles the rendering and logic for the list of events
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Schedule_Page extends WSB_Page {
    
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
        require_once plugin_dir_path( dirname(__FILE__)  ) . '/../../includes/class-wsb-options.php';
        require_once plugin_dir_path( dirname(__FILE__)  ) . 'helper-functions.php';
        require_once plugin_dir_path( dirname(__FILE__)  ) . 'class-wsb-requests.php';
        require_once plugin_dir_path( dirname(__FILE__) ) . 'ui/class-event-filters.php';
        require_once plugin_dir_path( dirname(__FILE__) ) . 'models/class-event.php';
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
    public function render_page( $attrs = [], $content = null ) {
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
     * Renders the list of events
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
            $event = new Event( $json_event,
                $this->settings->get_event_page_url(),
                $this->settings->get_trainer_page_url());
            array_push($events, $event );
        }
    
        $template_data = array('events' => $events, 'theme' => $this->get_theme());
        $template = $this->get_template('schedule-page', null);
    
        $GLOBALS['wsb_events'] = $events;
        $processed_template = do_shortcode($template);
        $content = $this->engine->compile_string($processed_template, $template_data);
        unset($GLOBALS['wsb_events']);
    
        return $content;
    }
    
    /**
     * Renders filters on the page
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    protected function render_list_filters( $attrs = []) {
        $default_attrs = array('filters' => 'location,trainer,language,type');
        $attrs = shortcode_atts($default_attrs, $attrs);
        
        if (!isset($GLOBALS['wsb_events']) || !is_array($GLOBALS['wsb_events'])) {
            return '';
        }
        $template = $this->get_template('filters', null);
        if (is_null($template)) {
            return '';
        }
        $available_filters = array_map(function($name) {
            return trim($name);
        }, explode(',', $attrs['filters']));
        
        $event_filters = new Event_Filters($GLOBALS['wsb_events'], $available_filters);
        return $this->engine->compile_string($template, array('filters' => $event_filters->get_filters()));
    }
    
    /**
     * Renders the list of events
     *
     * @param array       $attrs   Short code attributes
     * @param null|string $content Short code content
     *
     * @since  0.3.0
     * @return string
     */
    protected function render_event( $attrs = [], $content = null ) {
        if (!isset($GLOBALS['wsb_events']) || !is_array($GLOBALS['wsb_events'])) {
            return '';
        }
        $events = $GLOBALS['wsb_events'];
        switch($this->get_list_type()) {
            case 'tile':
                $template_name = 'event-list-item';
                break;
            default:
                $template_name = 'event-table-row';
        }
    
        $item_template = $this->get_template($template_name, null);
        if (!$item_template) {
            return '';
        }
        
        $html = '';
        foreach ($events as $event) {
            $GLOBALS['wsb_event'] = $event;
            $item_content = $this->engine->compile_string($content, array('event' => $event));
            $processed_item_content = do_shortcode($item_content);
            $html .= $this->engine->compile_string($item_template,
                array('event' => $event, 'content' => $processed_item_content));
            unset($GLOBALS['wsb_event']);
        }
    
        switch($this->get_list_type()) {
            case 'tile':
                $template_name = 'event-list';
                break;
            default:
                $template_name = 'event-table';
        }
        
        $list_template = $this->get_template($template_name, null);
        if (!$list_template) {
            return '';
        }
        
        return $this->engine->compile_string($list_template, array('content' => $html));
    }
    
    /**
     * Renders a event's title
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    protected function render_title( $attrs = []) {
        $content = '<a data-widget-go href="{{ url }}" class="wb-tile-title">{{ title }}</a>';
        
        $event = $this->get_event();
        if (!$event) {
            return '';
        }
        $data = array('url' => $event->url, 'title' => $event->title);
        return $this->engine->compile_string($content, $data);
    }
    
    /**
     * Renders an event's info block
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    protected function render_info( $attrs = []) {
        $content = <<<EOD
    <div class="wb-tile-info">
        {{ schedule }}
        {% if free %}
            <div class="wb-tile-free">free</div>
        {% endif %}
        <br/>
        {{ location }}
    </div>
EOD;
        $event = $this->get_event();
        if (!$event) {
            return '';
        }
        $data = array('location' => $event->location, 'schedule' => $event->schedule, 'free' => $event->free);
        return $this->engine->compile_string($content, $data);
    }
    
    /**
     * Renders an event's registration button
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    protected function render_register( $attrs = []) {
        $content = <<<EOD
    <div class="wb-tile-button">
        <a data-widget-go href="{{ url }}" class="wb-btn wb-btn-primary wb-tile-btn">
            {% if event.sold_out %}
                {{ __('Sold Out', 'wsbintegration') }}
            {% else %}
                {{ __('Register', 'wsbintegration') }}
            {% endif %}
        </a>
    </div>
EOD;
        $event = $this->get_event();
        if (!$event) {
            return '';
        }
        return $this->engine->compile_string($content, array('url' => $event->url));
    }
    
    /**
     * Returns the type of event list
     *
     * @since  0.2.0
     * @return string
     */
    private function get_list_type() {
        return $this->settings->get( WSB_Options::SCHEDULE_LAYOUT, 'table' );
    }
    
    /**
     * Handles 'wsb_schedule_item' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.2.0
     * @return string
     */
    static public function event( $attrs = [], $content = null ) {
        $page = new WSB_Schedule_Page();
        return $page->render_event($attrs, $content);
    }
    
    /**
     * Handles 'wsb_schedule_register' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.2.0
     * @return string
     */
    static public function register( $attrs = [], $content = null ) {
        $page = new WSB_Schedule_Page();
        return $page->render_register($attrs);
    }
    
    /**
     * Handles 'wsb_schedule_title' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.2.0
     * @return string
     */
    static public function title( $attrs = [], $content = null ) {
        $page = new WSB_Schedule_Page();
        return $page->render_title($attrs);
    }
    
    /**
     * Handles 'wsb_schedule_info' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.2.0
     * @return string
     */
    static public function info( $attrs = [], $content = null ) {
        $page = new WSB_Schedule_Page();
        return $page->render_info($attrs, $content);
    }
    
    
    /**
     * Handles 'wsb_schedule_filters' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.3.0
     * @return string
     */
    static public function list_filters( $attrs = [], $content = null ) {
        $page = new WSB_Schedule_Page();
        return $page->render_list_filters($attrs);
    }
    
    /**
     * Handles 'wsb_events' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.2.0
     * @return string
     */
    static public function page( $attrs = [], $content = null ) {
        $page = new WSB_Schedule_Page();
        return $page->render_page($attrs, $content);
    }
}

<?php
/**
 * The file that defines the trainer list class
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( dirname(__FILE__) ) . 'class-wsb-page.php';

/**
 * Trainer List page class which handles the rendering and logic for the list of trainers
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Trainer_List_Page extends WSB_Page {
    
    private $requests;
    
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
        require_once plugin_dir_path( dirname(__FILE__) ) . 'ui/class-trainer-filters.php';
        require_once plugin_dir_path( dirname(__FILE__) ) . 'models/class-trainer.php';
    }
    
    public function render_page( $attrs = [], $content = null ) {
        // Load styles and scripts only on demand.
        wp_enqueue_script( "wsb-helper-scripts" );
        wp_enqueue_script( "wsb-all-trainers-scripts" );
        
        $method = 'facilitators';
        $query  = array();
    
        $response = $this->requests->get( $method, $query );
        return $this->render_list($response, $this->settings->get_trainer_page_url());
    }
    
    /**
     * Renders the list of trainers
     *
     * @param $response WSB_Response
     * @param $trainerUrl string Trainer profile page URL
     *
     * @return string
     */
    protected function render_list( $response, $trainerUrl ) {
        if ( $response->is_error()) {
            $html = "<h2>" . __('Workshop Butler API: Request failed', 'wsbintegration')  . "</h2>";
            $html .= "<p>" . __('Reason : ', 'wsbintegration') . $response->error . "</p>";
            return $html;
        }
    
        $trainers = [];
        foreach ( $response->body as $json_trainer_data) {
            $trainer = new Trainer( $json_trainer_data, $trainerUrl);
            array_push($trainers, $trainer );
        }
        $template_data = array('trainers' => $trainers, 'theme' => $this->get_theme());
    
        $template = $this->get_template('trainer-list-page', null);
    
        $GLOBALS['wsb_trainers'] = $trainers;
        $processed_template = do_shortcode($template);
        $content = $this->engine->compile_string($processed_template, $template_data);
        unset($GLOBALS['wsb_trainers']);
        
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
        $default_attrs = array('filters' => 'location,trainer,language');
        $attrs = shortcode_atts($default_attrs, $attrs);
        
        if (!isset($GLOBALS['wsb_trainers']) || !is_array($GLOBALS['wsb_trainers'])) {
            return '';
        }
        $template = $this->get_template('filters', null);
        if (is_null($template)) {
            return '';
        }
        $available_filters = array_map(function($name) {
            return trim($name);
        }, explode(',', $attrs['filters']));
        
        $trainer_filters = new Trainer_Filters($GLOBALS['wsb_trainers'], $available_filters);
        return $this->engine->compile_string($template, array('filters' => $trainer_filters->get_filters()));
    }
    
    /**
     * Renders the list of trainers
     *
     * @param array       $attrs   Short code attributes
     * @param null|string $content Short code content
     *
     * @since  0.3.0
     * @return string
     */
    protected function render_trainer( $attrs = [], $content = null ) {
        if (!isset($GLOBALS['wsb_trainers']) || !is_array($GLOBALS['wsb_trainers'])) {
            return '';
        }
        $trainers = $GLOBALS['wsb_trainers'];
        $item_template = $this->get_template('trainer-list-item', null);
        if (!$item_template) {
            return '';
        }
        
        $html = '';
        foreach ($trainers as $trainer) {
            $GLOBALS['wsb_trainer'] = $trainer;
            $item_content = $this->engine->compile_string($content, array('trainer' => $trainer));
            $processed_item_content = do_shortcode($item_content);
            $html .= $this->engine->compile_string($item_template,
                array('trainer' => $trainer, 'content' => $processed_item_content));
            unset($GLOBALS['wsb_trainer']);
        }
        
        $list_template = $this->get_template('trainer-list', null);
        if (!$list_template) {
            return '';
        }
        
        return $this->engine->compile_string($list_template, array('content' => $html));
    }
    
    /**
     * Renders a trainer's photo
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    protected function render_photo( $attrs = []) {
        $content = <<<EOD
    {% if url %}
        <a href="{{ url }}">
            <div class="wb-trainer-tile_img" style="background-image: url({{ photo }});"></div>
        </a>
    {% else %}
        <div class="wb-trainer-tile_img" style="background-image: url({{ photo }});"></div>
    {% endif %}
EOD;

        $handler = function($trainer, $template) {
            $data = array('url' => $trainer->url, 'photo' => $trainer->photo);
            return $this->engine->compile_string($template, $data);
        };
        return $this->handle_trainer_shortcode('photo', $content, $handler);
    }
    
    /**
     * Renders a trainer's name
     *
     * @param array  $attrs Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_name( $attrs = []) {
        $default_attrs = array( 'with_country' => true );
        $attrs = shortcode_atts($default_attrs, $attrs);
        
        $content = <<<EOD
    {% if url %}
        <a href="{{ url }}">
            <div>{{ name }}</div>
        </a>
    {% else %}
        <div>{{ name }}</div>
    {% endif %}
    {% if with_country == 'true' %}
        <div class="wb-trainer-tile__country">
            {{ __(country, 'wsbintegration') }}
        </div>
    {% endif %}
EOD;
        
        $handler = function($trainer, $template) use($attrs) {
            $data = array('name' => $trainer->full_name(), 'country' => $trainer->country,
                          'with_country' => $attrs['with_country'], 'url' => $trainer->url);
            $html = do_shortcode($template);
            return $this->engine->compile_string($html, $data);
        };
        return $this->handle_trainer_shortcode('name', $content, $handler);
    }
    
    
    
    /**
     * Handles 'wsb_trainer_list' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.2.0
     * @return string
     */
    static public function page( $attrs = [], $content = null ) {
        $page = new WSB_Trainer_List_Page();
        return $page->render_page($attrs, $content);
    }
    
    /**
     * Handles 'wsb_trainer_list_filters' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.2.0
     * @return string
     */
    static public function list_filters( $attrs = [], $content = null ) {
        $page = new WSB_Trainer_List_Page();
        return $page->render_list_filters($attrs);
    }
    
    /**
     * Handles 'wsb_trainer_list_name' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.3.0
     * @return string
     */
    static public function name( $attrs = [], $content = null) {
        $page = new WSB_Trainer_List_Page();
        return $page->render_name($attrs);
    }
    
    /**
     * Handles 'wsb_trainer_list_item' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.2.0
     * @return string
     */
    static public function trainer( $attrs = [], $content = null ) {
        $page = new WSB_Trainer_List_Page();
        return $page->render_trainer($attrs, $content);
    }
    
    /**
     * Handles 'wsb_trainer_list_photo' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.3.0
     * @return string
     */
    static public function photo( $attrs = [], $content = null) {
        $page = new WSB_Trainer_List_Page();
        return $page->render_photo($attrs);
    }
    
}

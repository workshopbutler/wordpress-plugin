<?php
/**
 * The file that defines the trainer list class
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path(__FILE__) . 'class-wsb-page.php';

/**
 * Trainer List page class which handles the rendering and logic for the list of trainers
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Trainer_List extends WSB_Page {
    
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
        require_once plugin_dir_path( __FILE__ ) . '/../../includes/class-wsb-options.php';
        require_once plugin_dir_path( __FILE__ ) . 'helper-functions.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-wsb-requests.php';
        require_once plugin_dir_path(__FILE__) . 'ui/class-trainer-filters.php';
        require_once plugin_dir_path(__FILE__) . 'models/class-trainer.php';
    }
    
    public function render( $attrs = [], $content = null ) {
        // Load styles and scripts only on demand.
        wp_enqueue_script( "wsb-helper-scripts" );
        wp_enqueue_script( "wsb-all-trainers-scripts" );
        
        $method = 'facilitators';
        $query  = array();
    
        $data = json_decode( $this->requests->get( $method, $query ) );
        return $this->renderList($data, WSB_Options::get_trainer_page_url());
    }
    
    /**
     * Renders the list of trainers
     *
     * @param $jsonTrainers object JSON trainers data
     * @param $trainerUrl string Trainer profile page URL
     *
     * @return string
     */
    private function renderList( $jsonTrainers, $trainerUrl ) {
        if ( $jsonTrainers == null || $jsonTrainers->code == 404) {
            $html = "<h2>" . __('Error 404 - Not Found', 'wsbintegration')  . "</h2>";
            $html .= "<p>" . __('Sorry, but the page you were looking for could not be found.', 'wsbintegration')  . "</p>";
            return $html;
        }
        $trainers = [];
        foreach ( $jsonTrainers as $jsonTrainer) {
            $trainer = new Trainer( $jsonTrainer, $trainerUrl);
            array_push($trainers, $trainer );
        }
        $trainerFilters = new Trainer_Filters($trainers, ['location', 'trainer', 'language']);
        $templateData = array('trainers' => $trainers,
                              'filters' => $trainerFilters->get_filters(),
                              'theme' => $this->get_theme());
        
        $filename = 'trainer-list.twig';
        return $this->engine->fetch($filename, $templateData);
    }
    
    
    static public function shortcode( $attrs = [], $content = null ) {
        $page = new WSB_Trainer_List();
        return $page->render($attrs, $content);
    }
}

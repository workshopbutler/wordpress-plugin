<?php
/**
 * The file that defines the trainer page class
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path(__FILE__) . 'class-wsb-page.php';

/**
 * Trainer Page class which handles the rendering and logic for the profile of trainer
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Trainer_Page extends WSB_Page {
    
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
        require_once plugin_dir_path( __FILE__ ) . 'class-wsb-requests.php';
        require_once plugin_dir_path(__FILE__) . 'models/class-trainer.php';
    }
    
    
    public function render( $attrs = [], $content = null ) {
        $data = null;
        $id   = ( ! empty( $_GET['id'] ) ) ? $_GET['id'] : '';
        
        if ( $id !== "" ) {
            wp_enqueue_script( "wsb-single-trainer-scripts" );
            $wsb_nonce = wp_create_nonce( 'wsb-nonce' );
    
            wp_localize_script( 'wsb-single-trainer-scripts', 'wsb_single_trainer', array(
                'ajax_url'   => admin_url( 'admin-ajax.php' ),
                'nonce'      => $wsb_nonce,
                'trainer_id' => $id,
            ) );
            
            $method = 'facilitators/';
            $method .= rawurlencode( $id );
            $query  = array();
            
            $data = json_decode( $this->requests->get( $method, $query ) );
        }
        $html = $this->renderProfile( $data );
        
        return $html;
    }
    
    /**
     * Renders the profile of trainer
     * @param $data object JSON trainer data
     *
     * @return string
     */
    private function renderProfile( $data ) {
        if($data == null || $data->code == 404) {
            $html = "<h2>" . __('Error 404 - Not Found', 'wsbintegration')  . "</h2>";
            $html .= "<p>" . __('Sorry, but the page you were looking for could not be found.', 'wsbintegration')  . "</p>";
            return $html;
        }
        $filename = 'trainer-page.twig';
        $template_data = array('trainer' => new Trainer($data),
                               'theme' => $this->get_theme());
    
        return $this->engine->fetch($filename, $template_data);
    }
    
    static public function shortcode( $attrs = [], $content = null ) {
        $page = new WSB_Trainer_Page();
        
        return $page->render( $attrs, $content );
    }
}
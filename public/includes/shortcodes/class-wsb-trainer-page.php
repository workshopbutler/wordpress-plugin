<?php
/**
 * The file that defines the trainer page class
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path(dirname(__FILE__) ) . 'class-wsb-page.php';

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
        require_once plugin_dir_path( dirname(__FILE__ ) ) . 'class-wsb-requests.php';
        require_once plugin_dir_path(dirname(__FILE__ ) ) . 'models/class-trainer.php';
    }
    
    
    public function render( $attrs = [], $content = null ) {
        $response = null;
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
            
            $response = $this->requests->get( $method, $query );
            $html = $this->renderProfile( $response );
        } else {
            $html = "<h2>" . __('Workshop Butler API: Request failed', 'wsbintegration')  . "</h2>";
            $html .= "<p>" . __('Reason : empty trainer ID', 'wsbintegration') . "</p>";
        }
        
        return $html;
    }
    
    /**
     * Renders the profile of trainer
     *
     * @param $response WSB_Response
     *
     * @return string
     */
    private function renderProfile( $response ) {
        if ( $response->is_error()) {
            $html = "<h2>" . __('Workshop Butler API: Request failed', 'wsbintegration')  . "</h2>";
            $html .= "<p>" . __('Reason : ', 'wsbintegration') . $response->error . "</p>";
            return $html;
        }
    
        $trainer = new Trainer( $response->body );
        $template_data = array('trainer' => $trainer,
                               'theme' => $this->get_theme());
    
        $template = $this->get_template('trainer-page', null);
        
        $GLOBALS['wsb_trainer'] = $trainer;
        
        $processed_template = do_shortcode($template);
        $content = $this->engine->compile_string($processed_template, $template_data);
        
        unset($GLOBALS['wsb_trainer']);
        return $content;
    }
    
    /**
     * Handles 'wsb_trainer' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.2.0
     * @return string
     */
    static public function page( $attrs = [], $content = null ) {
        $page = new WSB_Trainer_Page();
        
        return $page->render( $attrs, $content );
    }
}

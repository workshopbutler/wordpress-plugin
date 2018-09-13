<?php
/**
 * The file that defines the class with endorsement-related shortcodes
 * @link       https://workshopbutler.com
 * @since      0.3.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path(dirname(__FILE__) ) . 'class-wsb-page.php';

/**
 * Handles the execution of the shortcodes related endorsements
 * @since      0.3.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Endorsement extends WSB_Page {
    
    /**
     * Renders the list of endorsements
     *
     * @param array       $attrs   Short code attributes
     * @param null|string $content Short code content
     *
     * @since  0.3.0
     * @return string
     */
    public function render_endorsements( $attrs = [], $content = null ) {
        $handler_endorsements = function($trainer, $template) {
            if (count($trainer->endorsements) == 0) {
                return '';
            }
            $with_data = $this->compile_string($template);
            $html = do_shortcode($with_data);
    
            return $html;
        };
        
        return $this->handle_trainer_shortcode('endorsements', $content, $handler_endorsements);
    }
    
    /**
     * @param $name
     * @param $content
     * @param $handler Closure
     *
     * @return string
     */
    protected function handle_trainer_shortcode( $name, $content, $handler ) {
        $trainer = $this->dict->get_trainer();
        if (!is_a($trainer, 'Trainer')) {
            return '';
        }
        $template = $this->get_template($name, $content);
        if (is_null($template)) {
            return '';
        }
        return $handler->call($this, $trainer, $template);
    }
    
    /**
     * Retrieves a currently-processed endorsement, related template and compiles an html
     * @param $name    string       Name of the template
     * @param $content string|null  Template content
     * @param $handler Closure      Callback
     *
     * @return string
     */
    protected function handle_endorsement_shortcode( $name, $content, $handler ) {
        $endorsement = $this->dict->get_endorsement();
        if (is_null($endorsement)) {
            return '';
        }
        $template = $this->get_template($name, $content);
        if (is_null($template)) {
            return '';
        }
        return $handler->call($this, $endorsement, $template);
    }
    
    /**
     * Renders an endorsement
     *
     * @param array       $attrs   Short code attributes
     * @param null|string $content Short code content
     *
     * @since  0.3.0
     * @return string
     */
    public function render_endorsement( $attrs = [], $content = null ) {
        $handler = function($trainer, $template) {
            $html = '';
    
            foreach ($trainer->endorsements as $endorsement) {
                $GLOBALS['wsb_endorsement'] = $endorsement;
                $with_data = $this->compile_string($template, array('endorsement' => $endorsement));
                $html .= do_shortcode($with_data);
                unset($GLOBALS['wsb_endorsement']);
            }
    
            return $html;
        };
        
        return $this->handle_trainer_shortcode('endorsement', $content, $handler);
    }
    
    /**
     * Renders a rating of an endorsement
     *
     * @param array       $attrs   Short code attributes
     * @param null|string $content Short code content
     *
     * @since  0.3.0
     * @return string
     */
    public function render_rating( $attrs = [], $content = null ) {
        $handler = function($endorsement, $template) {
            if (empty($endorsement->rating)) {
                return '';
            }
            $endorsement_html = do_shortcode($template);
            return $this->compile_string($endorsement_html, array('rating' => $endorsement->rating));
        };
        
        return $this->handle_endorsement_shortcode('rating', $content, $handler);
    }
    
    /**
     * Renders a content of an endorsement
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_content( $attrs = []) {
        $handler = function($endorsement, $template) {
            if (empty($endorsement->content)) {
                return '';
            }
            $html = do_shortcode($template);
            return $this->compile_string($html, array('content' => $endorsement->content));
        };
    
        $content = '<p class="wsb-endorsement-text">{{ content }}</p>';
        return $this->handle_endorsement_shortcode('content', $content, $handler);
    }
    
    /**
     * Renders an author of an endorsement
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_author( $attrs = []) {
        $handler = function($endorsement, $template) {
            if (empty($endorsement->content)) {
                return '';
            }
            $html = do_shortcode($template);
            return $this->compile_string($html, array('endorsement' => $endorsement));
        };
        
        return $this->handle_endorsement_shortcode('author', null, $handler);
    }
    
    
    static public function endorsements( $attrs = [], $content = null ) {
        $page = new WSB_Endorsement();
        return $page->render_endorsements($attrs, $content);
    }
    
    static public function endorsement( $attrs = [], $content = null ) {
        $page = new WSB_Endorsement();
        return $page->render_endorsement($attrs, $content);
    }
    
    static public function author( $attrs = [], $content = null) {
        $page = new WSB_Endorsement();
        return $page->render_author($attrs);
    }
    
    static public function content( $attrs = [], $content = null) {
        $page = new WSB_Endorsement();
        return $page->render_content($attrs);
    }
    
    static public function rating( $attrs = [], $content = null) {
        $page = new WSB_Endorsement();
        return $page->render_rating($attrs, $content);
    }
}

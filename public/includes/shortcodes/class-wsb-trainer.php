<?php
/**
 * The file that defines the class with trainer-related shortcodes
 * @link       https://workshopbutler.com
 * @since      0.3.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path(dirname(__FILE__) ) . 'class-wsb-page.php';

/**
 * Handles the execution of the shortcodes related to trainers
 * @since      0.3.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Trainer extends WSB_Page {
    
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
            $with_data = $this->engine->compile_string($template);
            $html = do_shortcode($with_data);
    
            return $html;
        };
        
        return $this->handle_trainer_shortcode('endorsements', $content, $handler_endorsements);
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
        $endorsement = $this->get_endorsement();
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
                $with_data = $this->engine->compile_string($template, array('endorsement' => $endorsement));
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
            return $this->engine->compile_string($endorsement_html, array('rating' => $endorsement->rating));
        };
        
        return $this->handle_endorsement_shortcode('rating', $content, $handler);
    }
    
    protected function trainer_shortcode( $name, $content ) {
        $handler = function($trainer, $template) use ($name) {
            if (empty($trainer->$name)) {
                return '';
            }
            $html = do_shortcode($template);
            return $this->engine->compile_string($html, array($name => $trainer->$name));
        };
    
        return parent::handle_trainer_shortcode( $name, $content, $handler );
    }
    
    /**
     * Renders the trainer's badges
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_badges( $attrs = []) {
        return $this->trainer_shortcode('badges', null);
    }
    
    /**
     * Renders a trainer's bio
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_bio( $attrs = []) {
        $content = <<<EOD
                    <h3>{{ __('Bio', 'wsbintegration') }} </h3>
                    <div class="wb-desc">
                        {{ bio }}
                    </div>
EOD;
        return $this->trainer_shortcode('bio', $content);
    }
    
    /**
     * Renders a list of upcoming events for a trainer
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_upcoming_events( $attrs = []) {
        $html = <<<EOD
<div class="wb-workshops" id="upcoming-events">
            <div class="wb-workshops__title">
                {{ __('Upcoming events', 'wsbintegration') }}
            </div>
            <div data-events-list>
            </div>
        </div>
EOD;
        return $html;
    }
    
    /**
     * Renders a list of past events for a trainer
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_past_events( $attrs = []) {
        $html = <<<EOD
<div class="wb-workshops" id="past-events">
            <div class="wb-workshops__title">
                {{ __('Past events', 'wsbintegration') }}
            </div>
            <div data-events-list>
            </div>
        </div>
EOD;
        return $html;
    }
    
    
    static public function endorsements( $attrs = [], $content = null ) {
        $page = new WSB_Trainer();
        return $page->render_endorsements($attrs, $content);
    }
    
    static public function endorsement( $attrs = [], $content = null ) {
        $page = new WSB_Trainer();
        return $page->render_endorsement($attrs, $content);
    }
    
    static public function bio( $attrs = [], $content = null) {
        $page = new WSB_Trainer();
        return $page->render_bio($attrs);
    }
    
    static public function badges( $attrs = [], $content = null) {
        $page = new WSB_Trainer();
        return $page->render_badges($attrs);
    }
    
    static public function upcoming_events( $attrs = [], $content = null) {
        $page = new WSB_Trainer();
        return $page->render_upcoming_events($attrs);
    }
    
    static public function past_events( $attrs = [], $content = null) {
        $page = new WSB_Trainer();
        return $page->render_past_events($attrs);
    }
}

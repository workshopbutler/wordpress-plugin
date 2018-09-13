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
        $trainer = $this->dict->get_trainer();
        if (!is_a($trainer, 'Trainer')) {
            return '';
        }
        if (count($trainer->endorsements) == 0) {
            return '';
        }
        $template = $this->get_template('endorsements', $content);
        if (is_null($template)) {
            return '';
        }
        $with_data = $this->compile_string($template);
        $html = do_shortcode($with_data);
    
        return $html;
    }
    
    /**
     * Renders a simple shortcode with no additional logic
     * @param string       $name Name of the shortcode (like 'title', 'register')
     * @param array        $attrs  Attributes
     * @param null|string  $content Replaceable content
     *
     * @return bool|string
     */
    protected function render_simple_shortcode($name, $attrs = [], $content = null) {
        $endorsement = $this->dict->get_endorsement();
        if (is_null($endorsement)) {
            return '';
        }
        $template = $this->get_template('endorsement/' . $name, null);
        if (!$template) {
            return '[wsb_endorsement_' . $name . ']';
        }
        $attrs['endorsement'] = $endorsement;
        return $this->compile_string($template, $attrs);
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
        $trainer = $this->dict->get_trainer();
        if (!is_a($trainer, 'Trainer')) {
            return '';
        }
        $template = $this->get_template('endorsement', $content);
        if (is_null($template)) {
            return '';
        }
        $html = '';
        
        foreach ($trainer->endorsements as $endorsement) {
            $GLOBALS['wsb_endorsement'] = $endorsement;
            $with_data = $this->compile_string($template, array('endorsement' => $endorsement));
            $html .= do_shortcode($with_data);
            unset($GLOBALS['wsb_endorsement']);
        }
    
        return $html;
    }
    
    
    static public function endorsements( $attrs = [], $content = null ) {
        $page = new WSB_Endorsement();
        return $page->render_endorsements($attrs, $content);
    }
    
    static public function endorsement( $attrs = [], $content = null ) {
        $page = new WSB_Endorsement();
        return $page->render_endorsement($attrs, $content);
    }
}

<?php
/**
 * The file that defines the Twig initialisation class
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */

/**
 * Initialises Twig environment
 *
 * @since 2.0.0
 */
class WSB_Twig {
    /**
     * @var Twig_Loader_Array $loader Template loader
     */
    public $loader;
    
    /**
     * @var Twig_Environment $twig Template environemnt
     */
    public $twig;
    
    public function __construct() {
        $this->loader = new Twig_Loader_Array();
        $this->twig = new Twig_Environment($this->loader);
        $this->initialise_functions();
        $this->initialise_filters();
    }
    
    protected function initialise_functions() {
        $this->twig->addFunction(new Twig_Function('__', function( $text, $domain = 'default' ) {
            return __($text, $domain);
        } ));
        $this->twig->addFunction(new Twig_Function('_n', function( $single, $plural, $number, $domain = 'default' ) {
            return _n($single, $plural, $number, $domain);
        } ));
    
        require_once dirname( __FILE__ ) . '/view/class-formatter.php';
        $this->twig->addFunction(new Twig_Function('wsb_f', function($object, $type = null) {
            return Formatter::format($object, $type);
        }));
        $this->twig->addFunction(new Twig_Function('wsb_t', function($key, $type = null) {
            return __($key, 'wsbintegration');
        }));
        $this->twig->addFunction(new Twig_Function('wsb_pt', function($key, $number) {
            $token = _n($key, $key, $number, 'wsbintegration');
            return sprintf($token, $number);
        }));
    }
    
    protected function initialise_filters() {
        $this->twig->addFilter(new Twig_Filter('truncate', function( $text, $len ) {
            $text = wp_strip_all_tags($text);
            $text = mb_strimwidth($text, 0, $len, null);
            return $text;
        } ));
    }
}

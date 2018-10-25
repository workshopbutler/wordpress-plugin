<?php
/**
 * The file that defines the Twig initialisation class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Initialises Twig environment
 *
 * @since 2.0.0
 */
class WSB_Twig {
	/**
	 * Template loader
	 *
	 * @var \Twig_Loader_Array $loader
	 */
	public $loader;

	/**
	 * Template environment
	 *
	 * @var \Twig_Environment $twig
	 */
	public $twig;

	/**
	 * WSB_Twig constructor
	 */
	public function __construct() {
		$this->loader = new \Twig_Loader_Array();
		$this->twig   = new \Twig_Environment( $this->loader );
		$this->initialise_functions();
		$this->initialise_filters();
	}

	/**
	 * Initialises different functions
	 */
	protected function initialise_functions() {
		$this->twig->addFunction(
			new Twig_Function(
				'__',
				function ( $text, $domain = 'default' ) {
					return __( $text, $domain );
				}
			)
		);
		$this->twig->addFunction(
			new Twig_Function(
				'_n',
				function ( $single, $plural, $number, $domain = 'default' ) {
					return _n( $single, $plural, $number, $domain );
				}
			)
		);

		require_once dirname( __FILE__ ) . '/view/class-formatter.php';
		$this->twig->addFunction(
			new Twig_Function(
				'wsb_f',
				function ( $object, $type = null ) {
					return Formatter::format( $object, $type );
				}
			)
		);
		$this->twig->addFunction(
			new Twig_Function(
				'wsb_t',
				function ( $key, $type = null ) {
					return __( $key, 'wsbintegration' );
				}
			)
		);
		$this->twig->addFunction(
			new Twig_Function(
				'wsb_pt',
				function ( $key, $number ) {
					$token = _n( $key, $key, $number, 'wsbintegration' );
					return sprintf( $token, $number );
				}
			)
		);
	}

	/**
	 * Adds additional filters, available in Twig templates
	 */
	protected function initialise_filters() {
		$this->twig->addFilter(
			new \Twig_SimpleFilter(
				'truncate',
				function ( $text, $len ) {
					$text = wp_strip_all_tags( $text );
					$text = mb_strimwidth( $text, 0, $len, null );
					return $text;
				}
			)
		);
	}
}

/**
 * Handle TwigFunction among Twig versions
 *
 * From Twig 2.4.0, extending Twig_Function is deprecated, will be final in 3.0
 *
 * @ticket #1641
 * Temporary fix for conflicts between Twig_Function and Twig_SimpleFunction
 * in different versions of Twig (1.* and 2.*)
 */

if ( version_compare( \Twig_Environment::VERSION, '2.4.0', '>=' ) ) {

	class_alias( '\Twig\TwigFunction', '\Twig_Function' );

} elseif ( version_compare( \Twig_Environment::VERSION, '2.0.0', '>=' ) ) {

	/**
	 * Defines Twig_Function class
	 *
	 * @package WorkshopButler
	 */
	class Twig_Function extends \Twig_Function {
	}

} else {

	/**
	 * Defines Twig_Function class
	 *
	 * @package WorkshopButler
	 */
	class Twig_Function extends \Twig_SimpleFunction {
	}

}

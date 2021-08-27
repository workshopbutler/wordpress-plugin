<?php
/**
 * The file that defines WSB_Page class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Represents an integrated page
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
abstract class WSB_Page {
	/**
	 * Plugin settings
	 *
	 * @since   2.0.0
	 * @var     WSB_Options $settings Plugin settings
	 */
	protected $settings;

	/**
	 * Template engine
	 *
	 * @var WSB_Twig $twig
	 * @since 2.0.0
	 */
	protected $twig;

	/**
	 * Plugin dictionary
	 *
	 * @var WSB_Dictionary $dict
	 * @since 2.0.0
	 */
	protected $dict;

	/**
	 * Number of words between '_' in prefix (usually 2)
	 *
	 * @var int
	 * @since 2.12.0
	 */
	static protected $prefix_size = 2;

	/**
	 * Creates a template engine entity
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		$this->load_dependencies();
	}

	/**
	 * Retrieves the name of Workshop Butler shortcode
	 *
	 * @param string $tag Full shortcode tag.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	protected static function get_shortcode_name( $tag ) {
		$parts     = explode( '_', $tag );
		$empty_tag = '[' . $tag . ']';
		if ( count( $parts ) <= static::$prefix_size ) {
			return $empty_tag;
		}

		return implode( '_', array_slice( $parts, static::$prefix_size ) );
	}

	/**
	 * Compiles the template
	 *
	 * @param string $template Template.
	 * @param array  $data Template paratemeters.
	 *
	 * @return string
	 */
	public function compile_string( $template, $data = array() ) {
		$key = base64_encode( $template );
		if ( ! $this->twig->loader->exists( $key ) ) {
			$this->twig->loader->setTemplate( $key, $template );
		}

		return $this->twig->twig->render( $key, $data );
	}

	/**
	 * Handles 'wsb_x_*' shortcodes
	 *
	 * @param array  $attrs Shortcode attributes.
	 * @param string $content Shortcode content.
	 * @param string $tag Shortcode's tag.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	public static function tag( $attrs, $content, $tag ) {
		$shortcode_name      = static::get_shortcode_name( $tag );
		$method              = 'render_' . $shortcode_name;
		$page                = new static();
		$processed_attrs     = is_array( $attrs ) ? self::convert_booleans( $attrs ) : array();
		$with_default_values = shortcode_atts( $page->get_default_attrs( $shortcode_name ), $processed_attrs );
		if ( method_exists( $page, $method ) ) {
			return $page->$method( $with_default_values, $content );
		} else {
			return $page->render_simple_shortcode( $shortcode_name, $with_default_values, $content );
		}
	}

	/**
	 * Converts boolean values in string format to real boolean values
	 *
	 * @param array $attrs Shortcode attributes.
	 *
	 * @return array
	 */
	protected static function convert_booleans( $attrs ) {
		foreach ( $attrs as $key => $value ) {
			switch ( $value ) {
				case 'true':
					$attrs[ $key ] = true;
					break;
				case 'false':
					$attrs[ $key ] = false;
					break;
				default:
					break;
			}
		}

		return $attrs;
	}

	/**
	 * Renders a simple shortcode with no additional logic
	 *
	 * @param string      $name Name of the shortcode (like 'title', 'register').
	 * @param array       $attrs Attributes.
	 * @param null|string $content Replaceable content.
	 *
	 * @return string
	 */
	protected function render_simple_shortcode( $name, $attrs = array(), $content = null ) {
		return '';
	}


	/**
	 * Returns default attributes for the shortcodes. Must be redefined in subclasses
	 *
	 * @param string $shortcode_name Name of the shortcode (only the meaningful part).
	 *
	 * @return array
	 */
	protected function get_default_attrs( $shortcode_name ) {
		return array();
	}

	/**
	 * Load the required dependencies
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for all plugin-related options
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '../includes/class-wsb-options.php';

		$this->settings = new WSB_Options();

		/**
		 * The class responsible for template rendering
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsb-twig.php';

		$this->twig = new WSB_Twig();

		/**
		 * The class responsible for providing an access to entities, loaded from API
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsb-dictionary.php';

		$this->dict = new WSB_Dictionary();
	}

	/**
	 * Returns an active theme for the integration
	 *
	 * @return string
	 * @since  2.0.0
	 */
	protected function get_theme() {
		return $this->settings->get_theme();
	}

	/**
	 * Loads additional Google fonts for default themes
	 *
	 * @since 2.0.0
	 */
	protected function add_theme_fonts() {
		$theme = $this->get_theme();
		switch ( $theme ) {
			case 'britton':
				wp_enqueue_style( 'wsb-font-droid-sans' );
				break;
			case 'dacota':
				wp_enqueue_style( 'wsb-font-arapey' );
				wp_enqueue_style( 'wsb-font-montserrat' );
				break;
			case 'gatsby':
				wp_enqueue_style( 'wsb-font-montserrat' );
				wp_enqueue_style( 'wsb-font-raleway' );
				break;
			case 'hayes':
				wp_enqueue_style( 'wsb-font-montserrat' );
				wp_enqueue_style( 'wsb-font-open-sans' );
		}
	}

	/**
	 * Returns the named template or 'null' if it doesn't exist
	 *
	 * @param string      $name Name of the template.
	 * @param null|string $content Template content.
	 *
	 * @return null|string
	 * @since  2.0.0
	 */
	protected function get_template( $name, $content ) {
		if ( empty( $content ) ) {
			$filename = plugin_dir_path( dirname( __FILE__ ) ) . '../views/' . $name . '.twig';
			$content  = file_get_contents( $filename );
			if ( ! $content ) {
				return null;
			}
		}

		return $content;
	}

	/**
	 * Returns the error message in an appropriate format
	 *
	 * @param string $error Error message.
	 *
	 * @return string
	 */
	protected function format_error( $error ) {
		$message = '<h2> Workshop Butler API: Request failed</h2>';
		$message .= '<p>Reason : ' . $error . '</p>';

		return $message;
	}

	/**
	 * Adds custom CSS if it exists
	 *
	 * @param string $content Page content.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	protected function add_custom_styles( $content ) {
		$custom_styles = $this->settings->get( WSB_Options::CUSTOM_CSS );
		if ( ! $custom_styles ) {
			return $content;
		}
		$styles = '<style>' . $custom_styles . '</style>';

		return $styles . $content;
	}
}

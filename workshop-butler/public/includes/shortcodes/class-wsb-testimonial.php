<?php
/**
 * The file that defines the class with endorsement-related shortcodes
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-page.php';

/**
 * Handles the execution of the shortcodes related testimonials
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Testimonial extends WSB_Page {

	/**
	 * Renders the list of testimonials
	 *
	 * @param array       $attrs   Short code attributes.
	 * @param null|string $content Short code content.
	 *
	 * @since  2.0.0
	 * @return string
	 */
	public function render_testimonials( $attrs = array(), $content = null ) {
		if ( empty( $attrs['trainer_id'] ) ) {
			return $this->render_builtin_testimonials( $content );
		} else {
			return $this->render_standalone_testimonials( $attrs['trainer_id'], $content );
		}
	}

	/**
	 * Renders the list of testimonials on a trainer's page
	 *
	 * @param int         $trainer_id Trainer ID who owns the testimonials.
	 * @param null|string $content    Content of the shortcode.
	 *
	 * @return string
	 */
	protected function render_standalone_testimonials( $trainer_id, $content = null ) {
		$may_be_trainer = $this->dict->get_trainer();
		if ( is_null( $may_be_trainer ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-requests.php';
			$requests       = new WSB_Requests();
			$may_be_trainer = $requests->retrieve_trainer( $trainer_id );
		}
		if ( is_wp_error( $may_be_trainer ) ) {
			return $this->format_error( $may_be_trainer->get_error_message() );
		}

		if ( 0 === count( $may_be_trainer->testimonials ) ) {
			return '';
		}
		$template = $this->get_template( 'testimonial/standalone-list', $content );
		if ( is_null( $template ) ) {
			return '';
		}
		$with_data = $this->compile_string( $template, array( 'theme' => $this->get_theme() ) );
		$html      = do_shortcode( $with_data );

		return $html;
	}

	/**
	 * Renders the list of testimonials on a trainer's page
	 *
	 * @param null|string $content Content of the shortcode.
	 *
	 * @return string
	 */
	protected function render_builtin_testimonials( $content = null ) {
		$trainer = $this->dict->get_trainer();
		if ( ! is_a( $trainer, 'WorkshopButler\Trainer' ) ) {
			return '';
		}
		if ( 0 === count( $trainer->testimonials ) ) {
			return '';
		}
		$template = $this->get_template( 'testimonial/builtin-list', $content );
		if ( is_null( $template ) ) {
			return '';
		}
		$with_data = $this->compile_string( $template );
		$html      = do_shortcode( $with_data );

		return $html;
	}

	/**
	 * Renders a simple shortcode with no additional logic
	 *
	 * @param string      $name    Name of the shortcode (like 'title', 'register').
	 * @param array       $attrs   Attributes.
	 * @param null|string $content Replaceable content.
	 *
	 * @return bool|string
	 */
	protected function render_simple_shortcode( $name, $attrs = array(), $content = null ) {
		$testimonial = $this->dict->get_testimonial();
		if ( is_null( $testimonial ) ) {
			return '';
		}
		$template = $this->get_template( 'testimonial/' . $name, null );
		if ( ! $template ) {
			return '[wsb_testimonial_' . $name . ']';
		}
		$attrs['testimonial'] = $testimonial;
		return $this->compile_string( $template, $attrs );
	}

	/**
	 * Renders a testimonial
	 *
	 * @param array       $attrs   Short code attributes.
	 * @param null|string $content Short code content.
	 *
	 * @since  2.0.0
	 * @return string
	 */
	public function render_testimonial( $attrs = array(), $content = null ) {
		$trainer = $this->dict->get_trainer();
		if ( ! is_a( $trainer, 'WorkshopButler\Trainer' ) ) {
			return '';
		}
		$template = $this->get_template( 'testimonial/item', $content );
		if ( is_null( $template ) ) {
			return '';
		}
		$html = '';

		foreach ( $trainer->testimonials as $testimonial ) {
			$GLOBALS['wsb_testimonial'] = $testimonial;
			$with_data                  = $this->compile_string( $template, array( 'testimonial' => $testimonial ) );
			$html                      .= do_shortcode( $with_data );
			unset( $GLOBALS['wsb_testimonial'] );
		}

		return $html;
	}

	/**
	 * Renders the list of testimonials
	 *
	 * @param array       $attrs   Short code attributes.
	 * @param null|string $content Short code content.
	 *
	 * @since  2.0.0
	 * @return string
	 */
	public static function testimonials( $attrs = array(), $content = null ) {
		$page = new WSB_Testimonial();
		return $page->render_testimonials( $attrs, $content );
	}

	/**
	 * Renders a testimonial
	 *
	 * @param array       $attrs   Short code attributes.
	 * @param null|string $content Short code content.
	 *
	 * @since  2.0.0
	 * @return string
	 */
	public static function testimonial( $attrs = array(), $content = null ) {
		$page = new WSB_Testimonial();
		return $page->render_testimonial( $attrs, $content );
	}
}

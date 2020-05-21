<?php
/**
 * The file that defines the class with the shortcode for 'Next event' button
 *
 * @link       https://workshopbutler.com
 * @since      2.12.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-page.php';

/**
 * Handles the execution of the 'Next event' shortcode
 *
 * @since      2.12.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Next_Event extends WSB_Page {

	/**
	 * Renders the button (async)
	 *
	 * @param array       $attrs Short code attributes.
	 * @param null|string $content Short code content.
	 *
	 * @return string
	 * @since  2.12.0
	 */
	public function render( $attrs = array(), $content = null ) {
		wp_enqueue_script( 'wsb-next-event' );

		$template = $this->get_template( 'next-event/button', $content );
		if ( is_null( $template ) ) {
			return '';
		}
		$with_data = $this->compile_string( $template, array( 'theme' => $this->get_theme() ) );

		return do_shortcode( $with_data );
	}


	/**
	 * Renders the button
	 *
	 * @param array       $attrs Short code attributes.
	 * @param null|string $content Short code content.
	 *
	 * @return string
	 * @since  2.12.0
	 */
	public static function next_event( $attrs = array(), $content = null ) {
		var_dump("OK");;
		$element = new WSB_Next_Event();

		return $element->render( $attrs, $content );
	}
}

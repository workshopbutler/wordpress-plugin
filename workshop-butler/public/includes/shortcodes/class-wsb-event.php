<?php
/**
 * The file that defines the class with event-related shortcodes
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-page.php';

/**
 * Handles the execution of the shortcodes related to events
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Event extends WSB_Page {

	/**
	 * Returns default attributes for the shortcodes
	 *
	 * @param string $shortcode_name Name of the shortcode (only the meaningful part).
	 *
	 * @return array
	 */
	protected function get_default_attrs( $shortcode_name ) {
		switch ( $shortcode_name ) {
			case 'image':
				return array(
					'width'  => '300',
					'height' => '200',
					'type'   => 'full',
					'class'  => null,
				);
			case 'registration_button':
				return array(
					'target' => '_self',
				);
			case 'tickets':
				$show_expired_tickets   = $this->settings->get( WSB_Options::SHOW_EXPIRED_TICKETS, true );
				$show_number_of_tickets = $this->settings->get( WSB_Options::SHOW_NUMBER_OF_TICKETS, true );

				return array(
					'show_expired_tickets'   => $show_expired_tickets,
					'show_number_of_tickets' => $show_number_of_tickets,
				);
			default:
				return array();
		}
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
		$event = $this->dict->get_event();
		if ( ! is_a( $event, 'WorkshopButler\Event' ) ) {
			return '';
		}
		$template = $this->get_template( 'event/' . $name, null );
		if ( ! $template ) {
			return '[wsb_event_' . $name . ']';
		}
		$attrs['event'] = $event;
		return $this->compile_string( $template, $attrs );
	}

}

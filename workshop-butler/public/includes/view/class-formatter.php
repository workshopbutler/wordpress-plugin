<?php
/**
 * The file that defines the object formatter class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once dirname( __FILE__ ) . '/class-location-formatter.php';
require_once dirname( __FILE__ ) . '/class-schedule-formatter.php';
require_once dirname( __FILE__ ) . '/class-language-formatter.php';
require_once dirname( __FILE__ ) . '/class-ticket-formatter.php';
require_once dirname( __FILE__ ) . '/class-event-state-formatter.php';

/**
 * Formats objects in a way, suitable for the integration needs
 */
class Formatter {

	/**
	 * Formats the given object
	 *
	 * @param object $object Object to format.
	 * @param string $type Additional format type.
	 *
	 * @return string
	 */
	public static function format( $object, $type = null ) {
		if ( $object instanceof Location ) {
			return Location_Formatter::format( $object );
		}
		if ( $object instanceof Schedule ) {
			return Schedule_Formatter::format( $object, $type );
		}
		if ( $object instanceof Language ) {
			return Language_Formatter::format( $object, $type );
		}
		if ( $object instanceof Ticket_Type ) {
			return esc_html__( Ticket_Formatter::format( $object, $type ) );
		}
		if ( $object instanceof Event_State ) {
			return Event_State_Formatter::format( $object );
		}
		if ( is_numeric( $object ) ) {
			if ( intval( $object ) === $object ) {
				return number_format_i18n( $object, 0 );
			} else {
				$number = number_format_i18n( $object, 2 );

				return trim( $number, '0.,' );
			}
		}

		return '';
	}
}

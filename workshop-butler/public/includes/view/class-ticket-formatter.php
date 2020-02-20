<?php
/**
 * The file that defines the Ticket_Formatter class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'view/class-date-formatter.php';

/**
 * Formats a ticket
 *
 * @since 2.0.0
 */
class Ticket_Formatter {
	/**
	 * Formats different parts of the ticket type
	 *
	 * @param Ticket_Type $ticket_type Type to format.
	 * @param string      $part Name of the part to format.
	 *
	 * @return string
	 */
	public static function format( $ticket_type, $part ) {
		switch ( $part ) {
			case 'desc':
				if ( $ticket_type instanceof Paid_Ticket_Type ) {
					return self::format_description( $ticket_type );
				} else {
					return '';
				}
			case 'price':
				if ( $ticket_type instanceof Paid_Ticket_Type ) {
					return self::format_price( $ticket_type );
				} else {
					return '';
				}
			default:
				return self::format_state( $ticket_type );
		}
	}

	/**
	 * Returns correctly-formatted price
	 *
	 * @param Paid_Ticket_Type $ticket_type Ticket type to format.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	protected static function format_price( $ticket_type ) {
		if ( class_exists( 'NumberFormatter' ) ) {
			$formatter = new \NumberFormatter( get_locale(), \NumberFormatter::CURRENCY );
			return $formatter->formatCurrency( $ticket_type->price->amount, $ticket_type->price->currency );
		} else {
			$without_fraction = ( $ticket_type->price->amount - floor( $ticket_type->price->amount ) ) < 0.001;
			$decimals         = $without_fraction ? 0 : 2;

			$sign = $ticket_type->price->sign ? $ticket_type->price->sign : $ticket_type->price->currency;

			return $sign . number_format_i18n( $ticket_type->price->amount, $decimals );
		}
	}

	/**
	 * Returns correctly-formatted stated of the given ticket type
	 *
	 * @param Ticket_Type $ticket_type Ticket type to format.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	protected static function format_state( $ticket_type ) {
		if ( $ticket_type->sold_out() ) {
			return __( 'event.ticket.soldOut', 'wsbintegration' );
		} elseif ( $ticket_type instanceof Paid_Ticket_Type && $ticket_type->ended() ) {
			return __( 'event.ticket.ended', 'wsbintegration' );
		} else {
			if ( $ticket_type->without_limit() ) {
				return '';
			} else {
				$token = _n( 'event.ticket.left', 'event.ticket.left', $ticket_type->number_of_tickets_left, 'wsbintegration' );

				return sprintf( $token, $ticket_type->number_of_tickets_left );
			}
		}
	}

	/**
	 * Returns correctly-formatted ticket description
	 *
	 * @param Paid_Ticket_Type $ticket_type Ticket type to format.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	protected static function format_description( $ticket_type ) {
		if ( $ticket_type->ended() ) {
			return sprintf( __( 'event.ticket.endedOn', 'wsbintegration' ), Date_Formatter::format( $ticket_type->end ) );
		}
		if ( $ticket_type->active() ) {
			return sprintf( __( 'event.ticket.endsOn', 'wsbintegration' ), Date_Formatter::format( $ticket_type->end ) );
		}

		return sprintf( __( 'event.ticket.onSaleFrom', 'wsbintegration' ), Date_Formatter::format( $ticket_type->start ) );
	}
}

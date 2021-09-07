<?php
/**
 * The file that defines the Date_Formatter class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

use DateTime;

/**
 * Formats a date
 *
 * @since 2.0.0
 */
class Date_Formatter {

	/**
	 * Removes the year from the date if the date is at the current year
	 *
	 * @param DateTime $date Date.
	 * @param bool     $with_time True if the time should be added.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	public static function format( $date, $with_time = false ) {
		$formatted_date = date_i18n( self::get_date_format( $date ), self::get_timestamp_with_offset( $date ) );
		$formatted_time = $with_time ? $date->format( get_option( 'time_format' ) ) : '';

		return trim( $formatted_date . ' ' . $formatted_time, '.,-/ ' );
	}

	/**
	 * Returns time of the date.
	 *
	 * @param DateTime $date Date.
	 *
	 * @return string
	 * @since 2.11.0
	 */
	public static function format_time( $date ) {
		$formatted_time = $date->format( get_option( 'time_format' ) );

		return trim( $formatted_time, '.,-/ ' );
	}

	/**
	 * Shows date + start and end times for the event if it's at one day
	 *
	 * @param DateTime $start_date Date.
	 * @param DateTime $end_date Date.
	 *
	 * @return string
	 * @since 2.1.2
	 */
	public static function format_one_day( $start_date, $end_date ) {
		$formatted_date = date_i18n( self::get_date_format( $start_date ), self::get_timestamp_with_offset( $start_date ) );
		$formatted_time = $start_date->format( get_option( 'time_format' ) ) . ' — ' . $end_date->format( get_option( 'time_format' ) );

		return trim( $formatted_date . ' ' . $formatted_time, '.,-/ ' );
	}


	/**
	 * Shows only start and end times for the event if it's at one day
	 *
	 * @param DateTime $start_date Date.
	 * @param DateTime $end_date Date.
	 *
	 * @return string
	 * @since 2.11.0
	 */
	public static function format_one_day_time( $start_date, $end_date ) {
		$formatted_time = $start_date->format( get_option( 'time_format' ) ) . '—' . $end_date->format( get_option( 'time_format' ) );

		return trim( $formatted_time, '.,-/ ' );
	}

	/**
	 * Returns a date format (with year or without) for the given date
	 *
	 * @param DateTime $date Date.
	 *
	 * @return string
	 */
	public static function get_date_format( $date ) {
		return self::is_this_year( $date ) && self::is_textual_month() ?
			preg_replace( '/[Yy]/', '', get_option( 'date_format' ) ) :
			get_option( 'date_format' );
	}

	/**
	 * Returns true if the current date format has a textual month representation
	 *
	 * @return  boolean
	 */
	public static function is_textual_month() {
		$format = get_option( 'date_format' );

		return strpos( $format, 'F' ) !== false || strpos( $format, 'M' ) !== false;
	}

	/**
	 * Returns true if the date is in the current year
	 *
	 * @param DateTime $date Date of interest.
	 *
	 * @return boolean
	 * @since 2.0.0
	 */
	protected static function is_this_year( $date ) {
		$now = new DateTime( 'now', $date->getTimezone() );

		return $now->format( 'Y' ) === $date->format( 'Y' );
	}

	/**
	 * Returns timestamp with offset for the date.
	 *
	 * @param DateTime $date Date of interest.
	 *
	 * @return int
	 * @since 2.11.0
	 */
	protected static function get_timestamp_with_offset( $date ) {
		return $date->getTimestamp() + $date->getOffset();
	}
}

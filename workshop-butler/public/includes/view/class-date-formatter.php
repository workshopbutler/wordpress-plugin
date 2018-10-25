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

/**
 * Formats a date
 *
 * @since 2.0.0
 */
class Date_Formatter {

	/**
	 * Removes the year from the date if the date is at the current year
	 *
	 * @param \DateTime $date      Date.
	 * @param bool      $with_time True if the time should be added.
	 * @return string
	 * @since 2.0.0
	 */
	public static function format( $date, $with_time = false ) {
		$formatted_date = date_i18n( self::get_date_format( $date ), $date->getTimestamp() );
		$formatted_time = $with_time ? $date->format( get_option( 'time_format' ) ) : '';
		return trim( $formatted_date . ' ' . $formatted_time, '.,-/ ' );
	}

	/**
	 * Returns a date format (with year or without) for the given date
	 *
	 * @param \DateTime $date Date.
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
	 * @param \DateTime $date Date of interest.
	 * @return boolean
	 * @since 2.0.0
	 */
	protected static function is_this_year( $date ) {
		$now = new \DateTime( 'now', $date->getTimezone() );
		return $now->format( 'Y' ) === $date->format( 'Y' );
	}
}

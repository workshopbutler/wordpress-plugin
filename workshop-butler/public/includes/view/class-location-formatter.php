<?php
/**
 * The file that defines the Location formatter class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Formats a location
 */
class Location_Formatter {

	/**
	 * Formats the location
	 *
	 * @param Location $location Workshop location.
	 * @since 2.0.0
	 * @return string
	 */
	public static function format( $location ) {
		if ( $location->online ) {
			return __( 'country.00', 'wsbintegration' );
		} else {
			return $location->city . ', ' . __( 'country.' . $location->country_code, 'wsbintegration' );
		}
	}
}

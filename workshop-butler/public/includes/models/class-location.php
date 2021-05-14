<?php
/**
 * The file that defines the location class, used later in templates
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Represents a location of an event
 *
 * @since   2.0.0
 * @package WSB_Integration
 */
class Location {

	/**
	 * Creates Location object from JSON
	 *
	 * @param object $json JSON to convert.
	 *
	 * @return Location
	 * @since 2.7.0
	 */
	static function from_json( $json ) {
		return new Location( $json->online, $json->country, $json->city );
	}

	/**
	 * True if an event is online
	 *
	 * @var boolean $online
	 * @since 2.0.0
	 */
	public $online;

	/**
	 * 2-letter country code
	 *
	 * @var string $country_code
	 * @since 2.0.0
	 */
	public $country_code;

	/**
	 * City
	 *
	 * @var string|null $city
	 * @since 2.0.0
	 */
	public $city;

	/**
	 * Initialises a new location
	 *
	 * @param boolean     $online True if the event is online.
	 * @param string      $country_code 2-letter country code.
	 * @param string|null $city Name of the city.
	 */
	public function __construct( $online, $country_code, $city ) {
		$this->online       = $online;
		$this->country_code = $this->online ? '00' : $country_code;
		$this->city         = $city;
	}

	/**
	 * Returns true if the location is online
	 *
	 * @return bool
	 */
	public function is_online() {
		return $this->online;
	}
}

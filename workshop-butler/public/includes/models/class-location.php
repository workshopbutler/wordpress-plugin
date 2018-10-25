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
	 * @param object $json_data JSON data from Workshop Butler API.
	 */
	public function __construct( $json_data ) {
		$this->online       = $json_data->online;
		$this->country_code = $this->online ? '00' : $json_data->country_code;
		$this->city         = $json_data->city;
	}
}

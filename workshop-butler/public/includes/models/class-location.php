<?php
/**
 * The file that defines the location class, used later in templates
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */

/**
 * Represents a location of an event
 *
 * @since 2.0.0
 * @package WSB_Integration
 */
class Location {
	/**
	 * @var boolean $online True if an event is online
	 * @since 2.0.0
	 */
	public $online;

	/**
	 * @var string $country_code Country code
	 * @since 2.0.0
	 */
	public $country_code;

	/**
	 * @var string|null $city City
	 * @since 2.0.0
	 */
	public $city;

	/**
	 * Initialises a new location
	 *
	 * @param object $json_data JSON data from Workshop Butler API
	 */
	public function __construct( $json_data ) {
		$this->online       = $json_data->online;
		$this->country_code = $this->online ? '00' : $json_data->country_code;
		$this->city         = $json_data->city;
	}
}

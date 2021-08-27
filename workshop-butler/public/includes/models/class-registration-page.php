<?php
/**
 * The file that defines the Registration_Page class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Contains the logic for the event registration
 */
class Registration_Page {
	/**
	 * True if the registration page is on the third-party website
	 *
	 * @var boolean $external
	 * @since 2.0.0
	 */
	public $external;

	/**
	 * The registration url
	 *
	 * @var string $url
	 * @since 2.0.0
	 */
	public $url;

	/**
	 * Registration_Page constructor
	 *
	 * @param object      $json_data JSON from WorkshopButler API.
	 * @param string|null $registration_url URL to the page with [wsb_registration] shortcode.
	 * @param int         $event_id ID of the event.
	 */
	public function __construct( $json_data, $registration_url, $event_id ) {
		if ( $json_data ) {
			$this->external = $json_data->external;
			$this->url      = $json_data->url;
		}
		if ( ! $this->external && $registration_url ) {
			$this->url = self::get_internal_url( $registration_url, $event_id );
		}
	}

	/**
	 * Returns the registration page URL
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function get_url() {
		return $this->url;
	}

	/**
	 * Returns a correctly formed url for a registration page of the event
	 *
	 * @param string $registration_page_url Url of the page with RegistrationPage widget.
	 * @param string $event_id Hashed event id.
	 *
	 * @return string
	 */
	protected static function get_internal_url( $registration_page_url, $event_id ) {
		return $registration_page_url . '?id=' . $event_id;
	}
}

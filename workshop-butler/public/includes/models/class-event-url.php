<?php
/**
 * The file that defines the event url
 *
 * @link       https://workshopbutler.com
 * @since      2.1.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Contains information about the event page URL relevant for correct rendering
 *
 * @since   2.1.0
 * @package WorkshopButler
 */
class Event_Url {

	/**
	 * The url to the event
	 *
	 * @since   2.1.0
	 * @var     string $url
	 */
	public $url;

	/**
	 * True if the URL leads to a third-party website
	 *
	 * @since   2.1.0
	 * @var     boolean
	 */
	public $on_third_party_website;

	/**
	 * Creates a new Event_Url object
	 *
	 * @param string  $url                    URL to the event page.
	 * @param boolean $on_third_party_website True if the URL leads to a third-party website.
	 */
	private function __construct( $url, $on_third_party_website ) {
		$this->url                    = $url;
		$this->on_third_party_website = $on_third_party_website;
	}

	/**
	 * Returns a new object with internal event page
	 *
	 * @param string $url URL to the event page.
	 * @return Event_Url
	 */
	public static function internal( $url ) {
		return new Event_Url( $url, false );
	}

	/**
	 * Returns a new object with external event page
	 *
	 * @param string $url URL to the event page.
	 * @return Event_Url
	 */
	public static function external( $url ) {
		return new Event_Url( $url, true );
	}
}

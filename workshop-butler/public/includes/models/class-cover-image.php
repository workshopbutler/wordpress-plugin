<?php
/**
 * This file contains Cover_Image class
 *
 * @package WorkshopButler
 * @since 2.6.0
 */

namespace WorkshopButler;

/**
 * Represents a cover image for event
 *
 * @package WorkshopButler
 * @since 2.6.0
 */
class Cover_Image {

	/**
	 * Creates a new cover image
	 *
	 * @param object|null $json JSON value.
	 *
	 * @return Cover_Image
	 */
	static function from_json( $json ) {
		return $json ? new Cover_Image( $json->url, $json->thumbnail_m ) : new Cover_Image( null, null );
	}

	/**
	 * URL to a cover image
	 *
	 * @var string|null $url
	 * @since 2.6.0
	 */
	public $url;

	/**
	 * URL to a cover image's thumbnail
	 *
	 * @var string|null $thumbnail
	 * @since 2.6.0
	 */
	public $thumbnail;

	/**
	 * Cover_Image constructor.
	 *
	 * @param string|null $url URL to a cover image.
	 * @param string|null $thumbnail URL to a cover image's thumbnail.
	 */
	public function __construct( $url, $thumbnail ) {
		$this->url       = $url;
		$this->thumbnail = $thumbnail;
	}

	/**
	 * Returns the url to the full-size cover image
	 *
	 * @since 3.0.0
	 * @return string|null
	 */
	public function get_url() {
		return $this->url;
	}

	/**
	 * Returns the url to the thumbnail of the cover image
	 *
	 * @since 3.0.0
	 * @return string|null
	 */
	public function get_thumbnail_url() {
		return $this->thumbnail;
	}
}

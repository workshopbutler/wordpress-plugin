<?php
/**
 * This file contains Badge class
 *
 * @package WorkshopButler
 * @since 2.6.0
 */

namespace WorkshopButler;

/**
 * Represents a trainer's badge
 *
 * @package WorkshopButler
 * @since 2.13.0
 */
class Badge {

	/**
	 * Creates a new badge
	 *
	 * @param object $json JSON value.
	 *
	 * @return Badge
	 */
	static function from_json( $json ) {
		return new Badge( $json->id, $json->name, $json->url );
	}

	/**
	 * ID of the badge
	 *
	 * @var int $id
	 * @since 2.13.0
	 */
	public $id;

	/**
	 * Badge's name.
	 *
	 * @var string $name
	 * @since 2.13.0
	 */
	public $name;

	/**
	 * URL to the badge
	 *
	 * @var string $url
	 * @since 2.13.0
	 */
	public $url;

	/**
	 * Badge constructor.
	 *
	 * @param int    $id Badge's ID.
	 * @param string $name Name.
	 * @param string $url URL to the image.
	 */
	public function __construct( $id, $name, $url ) {
		$this->id   = $id;
		$this->name = $name;
		$this->url  = $url;
	}
}

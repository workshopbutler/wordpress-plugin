<?php
/**
 * This files contains Testimonial class
 *
 * @package WorkshopButler
 * @since 2.6.0
 */

namespace WorkshopButler;

/**
 * Class Testimonial
 *
 * @package WorkshopButler
 * @since 2.6.0
 */
class Testimonial {

	/**
	 * Creates a testimonial
	 *
	 * @param object $json JSON value.
	 *
	 * @return Testimonial
	 */
	static function from_json( $json ) {
		return new Testimonial( $json->attendee, $json->content, $json->company, $json->rating );
	}

	/**
	 * Name of attendee who gave the testimonial
	 *
	 * @var string $attendee Name of attendee who gave the testimonial
	 * @since 2.6.0
	 */
	public $attendee;

	/**
	 * Content
	 *
	 * @var string $content
	 * @since 2.6.0
	 */
	public $content;

	/**
	 * Name of the company where the attendee works at
	 *
	 * @var string|null $company
	 * @since 2.6.0
	 */
	public $company;

	/**
	 * Verified rating given by attendee
	 *
	 * @var int|null $rating
	 * @since 2.6.0
	 */
	public $rating;

	/**
	 * Testimonial constructor.
	 *
	 * @param string      $attendee Name of attendee.
	 * @param string      $content Content.
	 * @param string|null $company Name of the company the attendee works at.
	 * @param int|null    $rating Verified rating.
	 */
	public function __construct( $attendee, $content, $company, $rating ) {
		$this->attendee = $attendee;
		$this->company  = $company;
		$this->content  = $content;
		$this->rating   = $rating;
	}
}

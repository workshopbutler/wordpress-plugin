<?php
/**
 * This files contains Testimonial class
 *
 * @package WorkshopButler
 * @since 2.7.0
 */

namespace WorkshopButler;

/**
 * Class Testimonial
 *
 * @package WorkshopButler
 * @since 2.7.0
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
		return new Testimonial( $json->attendee, $json->content, $json->company, $json->rating,
			@$json->verified, @$json->avatar, @$json->reason );
	}

	/**
	 * Name of attendee who gave the testimonial
	 *
	 * @var string $attendee Name of attendee who gave the testimonial
	 * @since 2.7.0
	 */
	public $attendee;

	/**
	 * Content
	 *
	 * @var string $content
	 * @since 2.7.0
	 */
	public $content;

	/**
	 * Name of the company where the attendee works at
	 *
	 * @var string|null $company
	 * @since 2.7.0
	 */
	public $company;

	/**
	 * Verified rating given by attendee
	 *
	 * @var int|null $rating
	 * @since 2.7.0
	 */
	public $rating;

	/**
	 * Is rating verified
	 *
	 * @var boolean $is_verified
	 * @since 3.0.0
	 */
	public $is_verified;

	/**
	 * Attendee avatar
	 *
	 * @var string|null $avatar
	 * @since 3.0.0
	 */
	public $avatar;

	/**
	 * Reason
	 *
	 * @var string|null $reason
	 * @since 3.0.0
	 */
	public $reason;

	/**
	 * Testimonial constructor.
	 *
	 * @param string      $attendee Name of attendee.
	 * @param string      $content Content.
	 * @param string|null $company Name of the company the attendee works at.
	 * @param int|null    $rating Verified rating.
	 */
	public function __construct( $attendee, $content, $company, $rating, $is_verified, $avatar, $reason ) {
		$this->attendee = $attendee;
		$this->company  = $company;
		$this->content  = $content;
		$this->rating   = $rating;
		$this->is_verified = $is_verified;
		$this->avatar   = $avatar;
		$this->reason   = $reason;
	}
}

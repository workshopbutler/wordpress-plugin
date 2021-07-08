<?php
/**
 * The file that defines the social links class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Contains different social links a trainer can have
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Social_Links {

	/**
	 * Creates a new Social_Links object
	 *
	 * @param object $json JSON value.
	 *
	 * @return Social_Links
	 * @since 2.7.0
	 */
	static function from_json( $json ) {
		if ( $json ) {
			return new Social_Links( $json->website, $json->blog, $json->twitter, $json->linkedin, $json->facebook );
		} else {
			return new Social_Links( null, null, null, null, null );
		}
	}

	/**
	 * Trainer's website
	 *
	 * @var string|null $website
	 * @since 2.0.0
	 */
	public $website;

	/**
	 * Trainer's blog
	 *
	 * @var string|null $blog
	 * @since 2.0.0
	 */
	public $blog;

	/**
	 * Trainer's twitter account
	 *
	 * @var string|null $twitter
	 * @since 2.0.0
	 */
	public $twitter;

	/**
	 * URL to the trainer's LinkedIn account
	 *
	 * @var string|null $linked_in
	 * @since 2.0.0
	 */
	public $linked_in;

	/**
	 * URL to the trainer's Facebook account
	 *
	 * @var string|null $facebook
	 * @since 2.0.0
	 */
	public $facebook;

	public function is_empty() {
		return !! ( $this->website || $this->blog || $this->facebook || $this->twitter || $this->linked_in );
	}

	/**
	 * Social_Links constructor.
	 *
	 * @param string|null $website Trainer's website.
	 * @param string|null $blog Trainer's blog.
	 * @param string|null $twitter Link to the trainer's twitter.
	 * @param string|null $linked_in Link to the trainer's linkedin account.
	 * @param string|null $facebook Link to the trainer's facebook account.
	 */
	public function __construct( $website, $blog, $twitter, $linked_in, $facebook ) {
		$this->website   = $website;
		$this->blog      = $blog;
		$this->facebook  = $facebook;
		$this->twitter   = $twitter;
		$this->linked_in = $linked_in;
	}
}

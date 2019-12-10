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
	 * Trainer's website
	 *
	 * @var string $website
	 * @since 2.0.0
	 */
	public $website;

	/**
	 * Trainer's blog
	 *
	 * @var string $blog
	 * @since 2.0.0
	 */
	public $blog;

	/**
	 * Trainer's twitter account
	 *
	 * @var string $twitter
	 * @since 2.0.0
	 */
	public $twitter;

	/**
	 * URL to the trainer's LinkedIn account
	 *
	 * @var string $linked_in
	 * @since 2.0.0
	 */
	public $linked_in;

	/**
	 * URL to the trainer's Facebook account
	 *
	 * @var string $facebook
	 * @since 2.0.0
	 */
	public $facebook;

	/**
	 * URL to the trainer's G+ account
	 *
	 * @var string $google_plus
	 * @since 2.0.0
	 */
	public $google_plus;

	/**
	 * Creates a new object
	 *
	 * @param object $json_data JSON data from Workshop Butler API.
	 */
	public function __construct( $json_data ) {
		$this->website  = $json_data->website;
		$this->blog     = $json_data->blog;
		$this->facebook = $json_data->facebook_url;
		if ( $json_data->twitter_handle ) {
			$this->twitter = 'https://twitter.com/' . $json_data->twitter_handle;
		}
		$this->linked_in   = $json_data->linkedin_url;
		$this->google_plus = $json_data->google_plus_url;
	}
}

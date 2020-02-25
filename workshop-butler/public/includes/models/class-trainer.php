<?php
/**
 * The file that defines the trainer class, used later in templates
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-social-links.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-trainer-stats.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-testimonial.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/language.php';


/**
 * Trainer class which represents a trainer profile in Workshop Butler
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Trainer {
	/**
	 * Trainer's ID
	 *
	 * @since 2.0.0
	 * @var   int
	 */
	public $id;

	/**
	 * First name
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $first_name;

	/**
	 * Last name
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $last_name;

	/**
	 * Email address
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $email;

	/**
	 * URL to the photo
	 *
	 * @since 2.0.0
	 * @var string|null
	 */
	public $photo;

	/**
	 * Bio (html)
	 *
	 * @since 2.0.0
	 * @var string|null
	 */
	public $bio;

	/**
	 * URL to the trainer's profile
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $url;

	/**
	 * 2-letter code of country of origin
	 *
	 * @since 2.0.0
	 * @var string|null
	 */
	public $country_code;

	/**
	 * List of code languages the trainer speaks at
	 *
	 * @since   2.0.0
	 * @var     string[] $languages Languages the trainer speaks to
	 */
	public $languages;

	/**
	 * Years of experience for the trainer. It's more than 0 only when you work with the profiles of trainers,
	 * licensed by certification body
	 *
	 * @since 2.0.0
	 * @var int
	 */
	public $years_of_experience;

	/**
	 * Total number of workshops the trainer had
	 *
	 * @since 2.0.0
	 * @var int
	 */
	public $number_of_events;

	/**
	 * List of badges the trainer earned
	 *
	 * @since 2.0.0
	 * @var object[]
	 */
	public $badges;

	/**
	 * Links to Facebook, LinkedIn and other social profiles
	 *
	 * @since 2.0.0
	 * @var Social_Links
	 */
	public $social_links;

	/**
	 * Trainer's statistics
	 *
	 * @since 2.7.0
	 * @var Trainer_Stats
	 */
	public $stats;

	/**
	 * List of testimonials
	 *
	 * @since 2.0.0
	 * @var Testimonial[]
	 */
	public $testimonials;

	/**
	 * List of countries (2-letter codes) where the trainer works in
	 *
	 * @since 2.7.0
	 * @var string[]
	 */
	public $works_in;

	/**
	 * Creates a new object
	 *
	 * @param object      $json_data JSON data from Workshop Butler API.
	 * @param string|null $trainer_url Trainer profile page URL.
	 */
	public function __construct( $json_data, $trainer_url = null ) {
		$this->id                  = $json_data->id;
		$this->first_name          = $json_data->first_name;
		$this->last_name           = $json_data->last_name;
		$this->photo               = $json_data->avatar;
		$this->bio                 = $json_data->bio;
		$this->email               = $json_data->email_address;
		$this->years_of_experience = $json_data->years_of_experience;
		$this->number_of_events    = $json_data->number_of_events;
		$this->badges              = $json_data->badges ? $json_data->badges : array();
		$this->social_links        = Social_Links::from_json( $json_data->social_links );

		$this->stats        = Trainer_Stats::from_json( $json_data->statistics );
		$this->testimonials = $this->create_testimonials( $json_data->testimonials );

		if ( $trainer_url ) {
			$this->url = $this->get_trainer_url( $trainer_url );
		} else {
			$this->url = null;
		}

		$this->country_code = $json_data->address->country;
		$this->languages    = array();
		if ( $json_data->languages && is_array( $json_data->languages ) ) {
			foreach ( $json_data->languages as $lang ) {
				array_push( $this->languages, get_lang_code( $lang ) );
			}
		}

		$this->works_in = $json_data->countries && is_array( $json_data->countries ) ? $json_data->countries : array();
	}

	/**
	 * Returns a list of testimonials from JSON
	 *
	 * @param object[] $json Testimonials in JSON.
	 *
	 * @return array
	 */
	protected function create_testimonials( $json ) {
		$testimonials = array();
		if ( $json && is_array( $json ) ) {
			foreach ( $json as $json_testimonial ) {
				array_push( $testimonials, Testimonial::from_json( $json_testimonial ) );
			}
		}

		return $testimonials;
	}

	/**
	 * Returns the URL to a trainer profile
	 *
	 * @param string $base_url URL of the page containing TrainerProfile widget.
	 *
	 * @return string
	 * @since 2.2.0
	 */
	protected function get_trainer_url( $base_url ) {
		return $base_url . '?id=' . $this->id . '&full_name=' . $this->full_name();
	}

	/**
	 * Returns the full name of the trainer
	 *
	 * @return string
	 * @since  2.0.0
	 */
	public function full_name() {
		return $this->first_name . ' ' . $this->last_name;
	}

	/**
	 * Returns the list of badge's name
	 *
	 * @return string[]
	 * @since 2.0.0
	 */
	public function name_of_badges() {
		$names = array();
		foreach ( $this->badges as $badge ) {
			array_push( $names, $badge->name );
		}

		return $names;
	}

}

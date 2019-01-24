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
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-statistics.php';
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
	 * @var string
	 */
	public $photo;

	/**
	 * Bio (html)
	 *
	 * @since 2.0.0
	 * @var string
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
	 * @var string
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
	 * Total statistics for public workshops
	 *
	 * @since 2.0.0
	 * @var Statistics
	 */
	public $public_stats;

	/**
	 * Total statistics for public workshops
	 *
	 * @since 2.0.0
	 * @var Statistics
	 */
	public $private_stats;

	/**
	 * Statistics for the last 6 months for public workshops
	 *
	 * @since 2.0.0
	 * @var Statistics
	 */
	public $recent_public_stats;

	/**
	 * Statistics for the last 6 months for public workshops
	 *
	 * @since 2.0.0
	 * @var Statistics
	 */
	public $recent_private_stats;

	/**
	 * List of testimonials
	 *
	 * @since 2.0.0
	 * @var object[]
	 */
	public $testimonials;

	/**
	 * Creates a new object
	 *
	 * @param object      $json_data   JSON data from Workshop Butler API.
	 * @param string|null $trainer_url Trainer profile page URL.
	 */
	public function __construct( $json_data, $trainer_url = null ) {
		$this->id                  = $json_data->id;
		$this->first_name          = $json_data->first_name;
		$this->last_name           = $json_data->last_name;
		$this->photo               = $json_data->photo;
		$this->bio                 = $json_data->bio;
		$this->email               = $json_data->email_address;
		$this->years_of_experience = $json_data->years_of_experience;
		$this->number_of_events    = $json_data->number_of_events;
		$this->badges              = $json_data->badges;
		$this->social_links        = new Social_Links( $json_data );

		$this->public_stats         = $this->get_statistics( $json_data, true, false );
		$this->private_stats        = $this->get_statistics( $json_data, false, false );
		$this->recent_public_stats  = $this->get_statistics( $json_data, true, true );
		$this->recent_private_stats = $this->get_statistics( $json_data, false, true );
		$this->testimonials         = $json_data->endorsements;

		if ( $trainer_url ) {
			$this->url = $trainer_url . '?id=' . $this->id;
		} else {
			$this->url = null;
		}

		$this->country_code = $this->get_country_code( $json_data );
		$this->languages    = array();
		if ( $json_data->languages && is_array( $json_data->languages ) ) {
			foreach ( $json_data->languages as $lang ) {
				array_push( $this->languages, get_lang_code( $lang ) );
			}
		}
	}

	/**
	 * Returns the full name of the trainer
	 *
	 * @since  2.0.0
	 * @return string
	 */
	public function full_name() {
		return $this->first_name . ' ' . $this->last_name;
	}

	/**
	 * Returns the list of badge's name
	 *
	 * @since 2.0.0
	 * @return string[]
	 */
	public function name_of_badges() {
		$names = array();
		foreach ( $this->badges as $badge ) {
			array_push( $names, $badge->name );
		}
		return $names;
	}


	/**
	 * Returns a correct trainer country code
	 *
	 * @param object $json_data JSON data from Workshop Butler API.
	 *
	 * @return string
	 */
	private function get_country_code( $json_data ) {
		if ( $json_data->country ) { // from Trainers API.
			return $json_data->country;
		} elseif ( $json_data->address && $json_data->address->country ) { // from Trainer API.
			return $json_data->address->country;
		} else {
			return '';
		}
	}

	/**
	 * Returns a specific type of statistics
	 *
	 * @param object  $json_data        JSON data from Workshop Butler API.
	 * @param boolean $public_workshops True if the statistics is from public workshops.
	 * @param boolean $recent_workshops True if the statistic is from recent workshops.
	 *
	 * @return Statistics
	 */
	private function get_statistics( $json_data, $public_workshops, $recent_workshops ) {
		if ( $json_data->statistics ) {
			$stats = $json_data->statistics;
			if ( $public_workshops ) {
				if ( $recent_workshops ) {
					return new Statistics(
						$stats->recent_number_of_public_evaluations,
						$stats->recent_public_median,
						$stats->recent_public_nps,
						$stats->recent_public_rating
					);
				} else {
					return new Statistics(
						$stats->number_of_public_evaluations,
						$stats->public_median,
						$stats->public_nps,
						$stats->public_rating
					);
				}
			} else {
				if ( $recent_workshops ) {
					return new Statistics(
						$stats->recent_number_of_private_evaluations,
						$stats->recent_private_median,
						$stats->recent_private_nps,
						$stats->recent_private_rating
					);
				} else {
					return new Statistics(
						$stats->number_of_private_evaluations,
						$stats->private_median,
						$stats->private_nps,
						$stats->private_rating
					);
				}
			}
		} else {
			return new Statistics( 0, 0, 0, 0 );
		}
	}
}

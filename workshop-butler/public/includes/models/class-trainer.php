<?php
/**
 * The file that defines the trainer class, used later in templates
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-social-links.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-statistics.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/language.php';

/**
 * Trainer class which represents a trainer profile in Workshop Butler
 *
 * @since      2.0.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Trainer {
	public $id;
	public $first_name;
	public $last_name;
	public $email;
	public $photo;
	public $bio;
	public $url;
	public $country_code;

	/**
	 * @since   2.0.0
	 * @var     string[] $languages Languages the trainer speaks to
	 */
	public $languages;
	public $years_of_experience;
	public $number_of_events;
	public $badges;
	public $social_links;
	public $public_stats;
	public $private_stats;
	public $recent_public_stats;
	public $recent_private_stats;
	public $testimonials;

	/**
	 * Creates a new object
	 *
	 * @param $json_data object          JSON data from Workshop Butler API
	 * @param $trainer_url string|null   Trainer profile page URL
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
		$this->languages    = [];
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
		$names = [];
		foreach ( $this->badges as $badge ) {
			array_push( $names, $badge->name );
		}
		return $names;
	}


	/**
	 * Returns a correct trainer country code
	 *
	 * @param $jsonData object JSON data from Workshop Butler API
	 *
	 * @return string
	 */
	private function get_country_code( $jsonData ) {
		if ( $jsonData->country ) { // from Trainers API
			return $jsonData->country;
		} elseif ( $jsonData->address && $jsonData->address->country ) { // from Trainer API
			return $jsonData->address->country;
		} else {
			return '';
		}
	}

	/**
	 * Returns a specific type of statistics
	 *
	 * @param $jsonData object JSON data from Workshop Butler API
	 * @param $publicWorkshops boolean True if the statistics is from public workshops
	 * @param $recentWorkshops boolean True if the statistic is from recent workshops
	 *
	 * @return Statistics
	 */
	private function get_statistics( $jsonData, $publicWorkshops, $recentWorkshops ) {
		if ( $jsonData->statistics ) {
			$stats = $jsonData->statistics;
			if ( $publicWorkshops ) {
				if ( $recentWorkshops ) {
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
				if ( $recentWorkshops ) {
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

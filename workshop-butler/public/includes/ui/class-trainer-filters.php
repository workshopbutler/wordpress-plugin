<?php
/**
 * The file that defines Trainer_Filters class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( __FILE__ ) . 'class-list-filters.php';
require_once plugin_dir_path( __FILE__ ) . 'class-filter-value.php';

/**
 * This class contains the logic for producing various filters for trainers
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Trainer_Filters extends List_Filters {

	/**
	 * Initialises a new object
	 *
	 * @param Trainer[] $trainers Available trainers which we use to build filters.
	 * @param string[]  $visible_filters List of filters to render on the page.
	 */
	public function __construct( $trainers, $visible_filters ) {
		$this->objects = $trainers;
		$this->filters = $visible_filters;
	}

	/**
	 * Returns the values of the filter based on its name
	 *
	 * @param string $name Name of the filter.
	 *
	 * @return Filter_Value[]
	 */
	protected function get_filter_values( $name ) {
		switch ( $name ) {
			case 'language':
				return $this->get_language_filter_data( __( 'filter.languages', 'wsbintegration' ), $this->objects );
			case 'location':
				return $this->get_location_filter_data( __( 'filter.locations', 'wsbintegration' ), $this->objects );
			case 'trainer':
				return $this->get_trainer_filter_data( __( 'filter.trainers', 'wsbintegration' ), $this->objects );
			case 'rating':
				return $this->get_rating_filter_data( __( 'filter.rating', 'wsbintegration' ), $this->objects );
			case 'badge':
				return $this->get_badge_filter_data( __( 'filter.badge', 'wsbintegration' ), $this->objects );
			default:
				return array();
		}
	}

	/**
	 * Returns values for Badge filter
	 *
	 * @param string    $default_name Name of the default filter value.
	 * @param Trainer[] $trainers Available trainers to filter.
	 *
	 * @return Filter_Value[]
	 */
	private function get_badge_filter_data( $default_name, $trainers ) {
		$badges = array();
		foreach ( $trainers as $trainer ) {
			foreach ( $trainer->badges as $badge ) {
				array_push( $badges, new Filter_Value( $badge->name, $badge->name ) );
			}
		}

		return $this->get_filter_data( $default_name, $badges );
	}

	/**
	 * Returns values for Rating filter
	 *
	 * @param string    $default_name Name of the default filter value.
	 * @param Trainer[] $trainers Available trainers to filter.
	 *
	 * @return Filter_Value[]
	 */
	private function get_rating_filter_data( $default_name, $trainers ) {
		$ratings = array(
			'one'   => 1,
			'two'   => 2,
			'three' => 3,
			'four'  => 4,
			'five'  => 5,
			'six'   => 6,
			'seven' => 7,
			'eight' => 8,
			'nine'  => 9,
		);
		$values  = array();
		foreach ( $ratings as $key => $value ) {
			array_push( $values, new Filter_Value( __( 'rating.' . $key, 'wsbintegration' ), $value ) );
		}

		return $this->get_filter_data( $default_name, $values );
	}

	/**
	 * Returns values for Language filter
	 *
	 * @param string    $default_name Name of the default filter value.
	 * @param Trainer[] $trainers Available trainers to filter.
	 *
	 * @return Filter_Value[]
	 */
	private function get_language_filter_data( $default_name, $trainers ) {
		$languages = array();
		foreach ( $trainers as $trainer ) {
			$trainer_languages = $trainer->languages;
			foreach ( $trainer_languages as $language ) {
				$id    = 'language.' . $language;
				$value = new Filter_Value( __( $id, 'wsbintegration' ), $language );
				array_push( $languages, $value );
			}
		}

		return $this->get_filter_data( $default_name, $languages );
	}

	/**
	 * Returns values for Location filter
	 *
	 * @param string    $default_name Name of the default filter value.
	 * @param Trainer[] $trainers Available trainers to filter.
	 *
	 * @return Filter_Value[]
	 */
	private function get_location_filter_data( $default_name, $trainers ) {
		$values = array();
		foreach ( $trainers as $trainer ) {
			foreach ( $trainer->works_in as $country_code ) {
				$country_name = __( 'country.' . $country_code, 'wsbintegration' );

				$value = new Filter_Value( $country_name, $country_code );
				array_push( $values, $value );
			}
		}

		return $this->get_filter_data( $default_name, $values );
	}

	/**
	 * Returns values for Trainer filter
	 *
	 * @param string    $default_name Name of the default filter value.
	 * @param Trainer[] $trainers Available trainers to filter.
	 *
	 * @return Filter_Value[]
	 */
	private function get_trainer_filter_data( $default_name, $trainers ) {
		$values = array();
		foreach ( $trainers as $trainer ) {
			$full_name = $trainer->first_name . ' ' . $trainer->last_name;
			$value     = new Filter_Value( $full_name, $full_name );
			array_push( $values, $value );
		}

		return $this->get_filter_data( $default_name, $values );
	}
}

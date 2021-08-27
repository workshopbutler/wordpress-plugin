<?php
/**
 * The file that defines Event_Filters class
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
 * This class contains the logic for producing various filters for events
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Event_Filters extends List_Filters {

	/**
	 * Initialises a new object
	 *
	 * @param Event[]  $events Available events which we use to build filters.
	 * @param string[] $visible_filters List of filters to render on the page.
	 */
	public function __construct( $events, $visible_filters ) {
		$this->objects = $events;
		$this->filters = $visible_filters;
	}

	/**
	 * Returns filters which should be added to the page
	 *
	 * @return Filter[]
	 * @since 2.12.0
	 */
	public function get_filters() {
		$filters = array();
		foreach ( $this->filters as $id ) {
			$values = $this->get_filter_values( $id );
			if ( count( $values ) > 0 ) {
				$filter_value = new Filter( $id, $values, true );
				array_push( $filters, $filter_value );
			}
		}

		return $filters;
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
			case WSB_Options::FILTER_LANGUAGE_ID:
				return $this->get_language_filter_data( __( 'filter.languages', 'wsbintegration' ), $this->objects );
			case WSB_Options::FILTER_LOCATION_ID:
				return $this->get_location_filter_data( __( 'filter.locations', 'wsbintegration' ), $this->objects );
			case WSB_Options::FILTER_TRAINER_ID:
				return $this->get_trainer_filter_data( __( 'filter.trainers', 'wsbintegration' ), $this->objects );
			case WSB_Options::FILTER_TYPE_ID:
				return $this->get_type_filter_data( __( 'filter.types', 'wsbintegration' ), $this->objects );
			default:
				return array();
		}
	}

	/**
	 * Returns values for Language filter
	 *
	 * @param string  $default_name Name of the default filter value.
	 * @param Event[] $events Available events to filter.
	 *
	 * @return Filter_Value[]
	 */
	private function get_language_filter_data( $default_name, $events ) {
		$languages = array();
		foreach ( $events as $event ) {
			$event_languages = $event->language->spoken;
			foreach ( $event_languages as $language ) {
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
	 * @param string  $default_name Name of the default filter value.
	 * @param Event[] $events Available events to filter.
	 *
	 * @return Filter_Value[]
	 */
	private function get_location_filter_data( $default_name, $events ) {
		$values = array();
		foreach ( $events as $event ) {
			$country_name = __( 'country.' . $event->location->country_code, 'wsbintegration' );
			$value        = new Filter_Value( $country_name, $event->location->country_code );
			array_push( $values, $value );
		}

		return $this->get_filter_data( $default_name, $values );
	}

	/**
	 * Returns values for Trainer filter
	 *
	 * @param string  $default_name Name of the default filter value.
	 * @param Event[] $events Available events to filter.
	 *
	 * @return Filter_Value[]
	 */
	private function get_trainer_filter_data( $default_name, $events ) {
		$values = array();
		foreach ( $events as $event ) {
			foreach ( $event->trainers as $trainer ) {
				$value = new Filter_Value( $trainer->get_full_name(), $trainer->get_full_name() );
				array_push( $values, $value );
			}
		}

		return $this->get_filter_data( $default_name, $values );
	}

	/**
	 * Returns values for Event Type filter
	 *
	 * @param string  $default_name Name of the default filter value.
	 * @param Event[] $events Available events to filter.
	 *
	 * @return Filter_Value[]
	 */
	private function get_type_filter_data( $default_name, $events ) {
		$values = array();
		foreach ( $events as $event ) {
			$value = new Filter_Value( $event->type->name, $event->type->id );
			array_push( $values, $value );
		}

		return $this->get_filter_data( $default_name, $values );
	}

}

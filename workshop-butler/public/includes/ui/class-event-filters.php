<?php
/**
 * The file that defines Event_Filters class
 *
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( __FILE__ ) . 'class-list-filters.php';
require_once plugin_dir_path( __FILE__ ) . 'class-filter-value.php';

/**
 * This class contains the logic for producing various filters for events
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Event_Filters extends List_Filters {

	/**
	 * Initialises a new object
	 *
	 * @param $events Event[] Available events which we use to build filters
	 * @param $visibleFilters string[] List of filters to render on the page
	 */
	public function __construct( $events, $visibleFilters ) {
		$this->objects = $events;
		$this->filters = $visibleFilters;
	}

	/**
	 * Returns the values of the filter based on its name
	 *
	 * @param $name string Name of the filter
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
			case 'type':
				return $this->get_type_filter_data( __( 'filter.types', 'wsbintegration' ), $this->objects );
			default:
				return [];
		}
	}

	/**
	 * Returns values for Language filter
	 *
	 * @param $defaultName string Name of the default filter value
	 * @param $events Event[] Available events to filter
	 *
	 * @return Filter_Value[]
	 */
	private function get_language_filter_data( $defaultName, $events ) {
		$languages = [];
		foreach ( $events as $event ) {
			$event_languages = $event->language->spoken;
			foreach ( $event_languages as $language ) {
				$id    = 'language.' . $language;
				$value = new Filter_Value( __( $id, 'wsbintegration' ), $language );
				array_push( $languages, $value );
			}
		}
		return $this->get_filter_data( $defaultName, $languages );
	}

	/**
	 * Returns values for Location filter
	 *
	 * @param $defaultName string Name of the default filter value
	 * @param $events Event[] Available events to filter
	 *
	 * @return Filter_Value[]
	 */
	private function get_location_filter_data( $defaultName, $events ) {
		$values = [];
		foreach ( $events as $event ) {
			$country_name = __( 'country.' . $event->location->country_code, 'wsbintegration' );
			$value        = new Filter_Value( $country_name, $event->location->country_code );
			array_push( $values, $value );
		}

		return $this->get_filter_data( $defaultName, $values );
	}

	/**
	 * Returns values for Trainer filter
	 *
	 * @param $defaultName string Name of the default filter value
	 * @param $events Event[] Available events to filter
	 *
	 * @return Filter_Value[]
	 */
	private function get_trainer_filter_data( $defaultName, $events ) {
		$values = [];
		foreach ( $events as $event ) {
			foreach ( $event->trainers as $trainer ) {
				$value = new Filter_Value( $trainer->full_name(), $trainer->full_name() );
				array_push( $values, $value );
			}
		}
		return $this->get_filter_data( $defaultName, $values );
	}

	/**
	 * Returns values for Event Type filter
	 *
	 * @param $defaultName string Name of the default filter value
	 * @param $events Event[] Available events to filter
	 *
	 * @return Filter_Value[]
	 */
	private function get_type_filter_data( $defaultName, $events ) {
		$values = [];
		foreach ( $events as $event ) {
			$value = new Filter_Value( $event->type->name, $event->type->id );
			array_push( $values, $value );
		}

		return $this->get_filter_data( $defaultName, $values );
	}

}

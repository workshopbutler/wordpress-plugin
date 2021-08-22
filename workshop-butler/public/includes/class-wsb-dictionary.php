<?php
/**
 * The file that defines the Dictionary class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

use WorkshopButler\Config\Event_Calendar_Config;
use WorkshopButler\Config\Next_Event_Config;
use WorkshopButler\Config\Single_Event_Config;

/**
 * Dictionary class which provides an access to entities, loaded from API
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Dictionary {

	/**
	 * Removes a current event from the dictionary
	 *
	 * @since 2.0.0
	 */
	public function clear_event() {
		unset( $GLOBALS['wsb_event'] );
	}

	/**
	 * Removes all events from the dictionary
	 *
	 * @since 2.0.0
	 */
	public function clear_events() {
		unset( $GLOBALS['wsb_events'] );
	}

	/**
	 * Removes a current trainer from the dictionary
	 *
	 * @since 2.0.0
	 */
	public function clear_trainer() {
		unset( $GLOBALS['wsb_trainer'] );
	}

	/**
	 * Removes all trainers from the dictionary
	 *
	 * @since 2.0.0
	 */
	public function clear_trainers() {
		unset( $GLOBALS['wsb_trainers'] );
	}

	/**
	 * Returns a currently-processed trainer or WP_Error if an API request failed
	 *
	 * @return Trainer|null|\WP_Error
	 * @since  2.0.0
	 */
	public function get_trainer() {
		if ( ! isset( $GLOBALS['wsb_trainer'] ) ) {
			return null;
		}
		$may_be_trainer = $GLOBALS['wsb_trainer'];
		if ( is_wp_error( $may_be_trainer ) ) {
			return $may_be_trainer;
		}
		if ( ! is_a( $may_be_trainer, 'WorkshopButler\Trainer' ) ) {
			return null;
		}

		return $may_be_trainer;
	}

	/**
	 * Returns a currently-processed event or WP_Error if an API request failed
	 *
	 * @return Event|\WP_Error|null
	 * @since  2.0.0
	 */
	public function get_event() {
		if ( ! isset( $GLOBALS['wsb_event'] ) ) {
			return null;
		}
		$may_be_event = $GLOBALS['wsb_event'];
		if ( is_wp_error( $may_be_event ) ) {
			return $may_be_event;
		}
		if ( ! is_a( $may_be_event, 'WorkshopButler\Event' ) ) {
			return null;
		}

		return $may_be_event;
	}

	/**
	 * Returns the attributes for a currently-processed event or null
	 *
	 * @return Event_Calendar_Config|null
	 * @since 2.0.0
	 */
	public function get_schedule_config() {
		if ( ! isset( $GLOBALS['wsb_schedule_attrs'] ) ) {
			return null;
		}

		return $GLOBALS['wsb_schedule_attrs'];
	}

	/**
	 * Returns the config for the single event page
	 *
	 * @return Single_Event_Config|null
	 * @since 3.0.0
	 */
	public function get_single_event_config() {
		if ( ! isset( $GLOBALS['wsb_single_event_config'] ) ) {
			return null;
		}

		return $GLOBALS['wsb_single_event_config'];
	}

	/**
	 * Sets new single page config
	 *
	 * @param Single_Event_Config $config New event config.
	 *
	 * @since 3.0.0
	 */
	public function set_single_event_config( $config ) {
		$GLOBALS['wsb_single_event_config'] = $config;
	}

	/**
	 * Returns the config for the trainer list page
	 *
	 * @return Trainer_List_Config|null
	 * @since 3.0.0
	 */
	public function get_trainer_list_config() {
		if ( ! isset( $GLOBALS['wsb_trainer_list_config'] ) ) {
			return null;
		}

		return $GLOBALS['wsb_trainer_list_config'];
	}

	/**
	 * Sets new trainer list config
	 *
	 * @param Trainer_List_Config $config New trainer list config.
	 *
	 * @since 3.0.0
	 */
	public function set_trainer_list_config( $config ) {
		$GLOBALS['wsb_trainer_list_config'] = $config;
	}

	/**
	 * Returns the config for the next event widget
	 *
	 * @return Next_Event_Config|null
	 * @since 3.0.0
	 */
	public function get_next_event_config() {
		if ( ! isset( $GLOBALS['wsb_next_event_config'] ) ) {
			return null;
		}

		return $GLOBALS['wsb_next_event_config'];
	}

	/**
	 * Sets new next event config
	 *
	 * @param Next_Event_Config $config Next event config.
	 *
	 * @since 3.0.0
	 */
	public function set_next_event_config( $config ) {
		$GLOBALS['wsb_next_event_config'] = $config;
	}


	/**
	 * Sets new schedule attributes
	 *
	 * @param Event_Calendar_Config $config New schedule config.
	 *
	 * @since 2.0.0
	 */
	public function set_schedule_config( $config ) {
		$GLOBALS['wsb_schedule_attrs'] = $config;
	}


	/**
	 * Returns the attributes for a currently-processed event or null
	 *
	 * @return array|null
	 * @since 2.12.0
	 */
	public function get_item_attrs() {
		if ( ! isset( $GLOBALS['wsb_item_attrs'] ) ) {
			return null;
		}

		return $GLOBALS['wsb_item_attrs'];
	}

	/**
	 * Sets new item attributes
	 *
	 * @param array $attrs New item attrs.
	 *
	 * @since 2.12.0
	 */
	public function set_item_attrs( $attrs ) {
		$GLOBALS['wsb_item_attrs'] = $attrs;
	}

	/**
	 * Returns a list of processed events
	 *
	 * @return Event[]|null
	 * @since  2.0.0
	 */
	public function get_events() {
		if ( ! isset( $GLOBALS['wsb_events'] ) || ! is_array( $GLOBALS['wsb_events'] ) ) {
			return null;
		}

		return $GLOBALS['wsb_events'];
	}

	/**
	 * Returns a list of processed trainers
	 *
	 * @return Trainer[]|null
	 * @since  2.0.0
	 */
	public function get_trainers() {
		if ( ! isset( $GLOBALS['wsb_trainers'] ) || ! is_array( $GLOBALS['wsb_trainers'] ) ) {
			return null;
		}

		return $GLOBALS['wsb_trainers'];
	}

	/**
	 * Returns a currently-processed testimonial
	 *
	 * @return object|null
	 * @since  2.0.0
	 */
	public function get_testimonial() {
		if ( ! isset( $GLOBALS['wsb_testimonial'] ) || ! is_object( $GLOBALS['wsb_testimonial'] ) ) {
			return null;
		}

		return $GLOBALS['wsb_testimonial'];
	}

	/**
	 * Adds a loaded event to the dictionary
	 *
	 * @param Event|\WP_Error $event Retrieved event or an error if an API request failed.
	 *
	 * @since 2.0.0
	 */
	public function set_event( $event ) {
		$GLOBALS['wsb_event'] = $event;
	}

	/**
	 * Adds a loaded trainer to the dictionary
	 *
	 * @param Trainer|\WP_Error $trainer Retrieved trainer or an error if an API request failed.
	 *
	 * @since 2.0.0
	 */
	public function set_trainer( $trainer ) {
		$GLOBALS['wsb_trainer'] = $trainer;
	}

	/**
	 * Adds a section to the dictionary
	 *
	 * @param Section $section Section of the registration form.
	 *
	 * @since 3.0.0
	 */
	public function set_form_section( $section ) {
		$GLOBALS['wsb_form_section'] = $section;
	}

	/**
	 * Cleans a section from the dictionary
	 *
	 * @since 3.0.0
	 */
	public function clear_form_section() {
		unset( $GLOBALS['wsb_form_section'] );
	}

	/**
	 * Returns a currently-processed section
	 *
	 * @return Section|null
	 * @since  3.0.0
	 */
	public function get_form_section() {
		if ( ! isset( $GLOBALS['wsb_form_section'] ) ) {
			return null;
		}
		$may_be_section = $GLOBALS['wsb_form_section'];
		if ( ! is_a( $may_be_section, 'WorkshopButler\Section' ) ) {
			return null;
		}

		return $may_be_section;
	}

	/**
	 * Adds a field to the dictionary
	 *
	 * @param Field $field Field of the registration form.
	 *
	 * @since 3.0.0
	 */
	public function set_form_field( $field ) {
		$GLOBALS['wsb_form_field'] = $field;
	}

	/**
	 * Cleans a field from the dictionary
	 *
	 * @since 3.0.0
	 */
	public function clear_form_field() {
		unset( $GLOBALS['wsb_form_field'] );
	}

	/**
	 * Returns a currently-processed field
	 *
	 * @return Field|Select|null
	 * @since  3.0.0
	 */
	public function get_form_field() {
		if ( ! isset( $GLOBALS['wsb_form_field'] ) ) {
			return null;
		}
		$may_be_field = $GLOBALS['wsb_form_field'];
		if ( ! is_a( $may_be_field, 'WorkshopButler\Field' ) ) {
			return null;
		}

		return $may_be_field;
	}

	/**
	 * Adds loaded events to the dictionary
	 *
	 * @param Event[] $events Retrieved events.
	 *
	 * @since 2.0.0
	 */
	public function set_events( $events ) {
		$GLOBALS['wsb_events'] = $events;
	}

	/**
	 * Adds loaded trainers to the dictionary
	 *
	 * @param Trainer[] $trainers Retrieved trainers.
	 *
	 * @since 2.0.0
	 */
	public function set_trainers( $trainers ) {
		$GLOBALS['wsb_trainers'] = $trainers;
	}

}

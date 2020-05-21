<?php
/**
 * This file contains Event_List class
 *
 * @package WorkshopButler
 * @since 2.12.0
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . '../../includes/class-wsb-options.php';

/**
 * Contains a list of methods to work with the list of events
 *
 * @package WorkshopButler
 * @since 2.12.0
 */
class Event_List {

	/**
	 * Creates a list of events from JSON
	 *
	 * @param object[]    $json Events in JSON.
	 * @param WSB_Options $options Plugin options.
	 * @param bool        $only_featured True if only featured events to show.
	 *
	 * @return array
	 */
	static public function from_json( $json, $options, $only_featured ) {
		$events = array();
		foreach ( $json as $json_event ) {
			try {
				$event = new Event(
					$json_event,
					$options->get_event_page_url(),
					$options->get_trainer_page_url(),
					$options->get_registration_page_url()
				);
				if ( ! $only_featured || $event->featured ) {
					array_push( $events, $event );
				}
			} catch (\Exception $e) {
				error_log($e->getMessage());
			}
		}

		return $events;
	}

	/**
	 * Returns the list of events where featured events are in the beginning, and non-featured - at the end.
	 *
	 * @param Event[] $events List of events.
	 *
	 * @return Event[]
	 */
	static public function put_featured_on_top( $events ) {
		$featured     = array_filter(
			$events,
			function ( $event ) {
				return $event->featured;
			}
		);
		$non_featured = array_filter(
			$events,
			function ( $event ) {
				return ! $event->featured;
			}
		);

		return array_merge( $featured, $non_featured );
	}
}

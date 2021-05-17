<?php
/**
 * Set of hooks to render single page
 *
 * @package WorkshopButler\Hooks
 * @since 3.0.0
 */

namespace WorkshopButler\Hooks;

require_once WSB_ABSPATH . '/includes/wsb-core-functions.php';
require_once WSB_ABSPATH . '/includes/wsb-conditional-functions.php';

/**
 * Class Single_Event_Hooks
 *
 * @since 3.0.0
 * @package WorkshopButler\Hooks
 */
class Single_Event_Hooks {

	/**
	 * Initializes hooks available in this class
	 */
	public static function init() {
		add_action( 'wsb_event_register_button', array( 'WorkshopButler\Hooks\Single_Event_Hooks', 'register_button' ), 10 );
		add_action( 'wsb_event_schedule', array( 'WorkshopButler\Hooks\Single_Event_Hooks', 'schedule' ), 10 );
		add_action( 'wsb_event_location', array( 'WorkshopButler\Hooks\Single_Event_Hooks', 'location' ), 10 );
		add_action( 'wsb_event_trainers', array( 'WorkshopButler\Hooks\Single_Event_Hooks', 'trainers' ), 10 );
		add_action( 'wsb_event_description', array( 'WorkshopButler\Hooks\Single_Event_Hooks', 'description' ), 10 );
		add_action( 'wsb_event_cover_image', array( 'WorkshopButler\Hooks\Single_Event_Hooks', 'cover_image' ), 10 );
		add_action( 'wsb_event_tickets', array( 'WorkshopButler\Hooks\Single_Event_Hooks', 'tickets' ), 10 );
		add_action( 'wsb_event_social_links', array( 'WorkshopButler\Hooks\Single_Event_Hooks', 'social_links' ), 10 );
		add_action( 'wsb_event_events', array( 'WorkshopButler\Hooks\Single_Event_Hooks', 'events' ), 10 );
	}

	/**
	 * Renders registration button for a single event
	 *
	 * @see Single_Event_Hooks::init() for the hook
	 */
	public static function register_button() {
		wsb_get_template( 'event/register-button.php' );
	}

	/**
	 * Renders the cover image for a single event
	 *
	 * @see Single_Event_Hooks::init() for the hook
	 */
	public static function cover_image() {
		wsb_get_template( 'event/cover-image.php' );
	}

	/**
	 * Renders tickets for a single event
	 *
	 * @see Single_Event_Hooks::init() for the hook
	 */
	public static function tickets() {
		wsb_get_template( 'event/tickets.php' );
	}

	/**
	 * Renders the event's trainers
	 */
	public static function trainers() {
		wsb_get_template( 'event/trainers.php' );
	}

	/**
	 * Renders the event's location
	 */
	public static function location() {
		wsb_get_template( 'event/location.php' );
	}

	/**
	 * Renders the event's description
	 */
	public static function description() {
		wsb_get_template( 'event/description.php' );
	}

	/**
	 * Renders the event's schedule
	 */
	public static function schedule() {
		wsb_get_template( 'event/schedule.php' );
	}

	/**
	 * Renders the event's social links
	 */
	public static function social_links() {
		wsb_get_template( 'event/social-links.php' );
	}

	/**
	 * Renders the list of events
	 */
	public static function events() {
		wsb_get_template( 'event/events.php' );
	}
}

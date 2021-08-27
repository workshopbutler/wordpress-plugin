<?php
/**
 * Set of hooks to render single page
 *
 * @package WorkshopButler\Hooks
 * @since 3.0.0
 */

namespace WorkshopButler\Hooks;

require_once WSB_ABSPATH . '/includes/wsb-core-functions.php';

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
		add_action( 'wsb_event_info', array( 'WorkshopButler\Hooks\Single_Event_Hooks', 'info' ), 10 );
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
		Single_Event_Hooks::with_default_context( 'event/register-button.php' );
	}

	/**
	 * Renders the cover image for a single event
	 *
	 * @see Single_Event_Hooks::init() for the hook
	 */
	public static function cover_image() {
		Single_Event_Hooks::with_default_context( 'event/cover-image.php' );
	}

	/**
	 * Renders tickets for a single event
	 *
	 * @see Single_Event_Hooks::init() for the hook
	 */
	public static function tickets() {
		Single_Event_Hooks::with_default_context( 'event/tickets.php' );
	}

	/**
	 * Renders the event's trainers
	 */
	public static function trainers() {
		Single_Event_Hooks::with_default_context( 'event/trainers.php' );
	}

	/**
	 * Renders the event's description
	 */
	public static function description() {
		Single_Event_Hooks::with_default_context( 'event/description.php' );
	}

	/**
	 * Renders the event's info
	 */
	public static function info() {
		Single_Event_Hooks::with_default_context( 'event/info.php' );
	}

	/**
	 * Renders the event's social links
	 */
	public static function social_links() {
		Single_Event_Hooks::with_default_context( 'event/social-links.php' );
	}

	/**
	 * Renders the list of events
	 */
	public static function events() {
		Single_Event_Hooks::with_default_context( 'event/events.php' );
	}

	private static function with_default_context( $template ) {
		$event = WSB()->dict->get_event();
		if( !is_a( $event, 'WorkshopButler\Event' )) {
			return false;
		}
		wsb_get_template( $template, array(
			'event' => $event,
			'config' => WSB()->dict->get_single_event_config(),
		));
	}
}

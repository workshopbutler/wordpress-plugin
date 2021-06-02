<?php
/**
 * Set of hooks to render next event widget
 *
 * @package WorkshopButler\Hooks
 * @since 3.0.0
 */

namespace WorkshopButler\Hooks;

require_once WSB_ABSPATH . '/includes/wsb-core-functions.php';
require_once WSB_ABSPATH . '/includes/wsb-conditional-functions.php';

/**
 * Class Next_Event_Hooks
 *
 * @since 3.0.0
 * @package WorkshopButler\Hooks
 */
class Next_Event_Hooks {

	/**
	 * Initializes hooks available in this class
	 */
	public static function init() {
		add_action( 'workshopbutler_next_event_button', array( 'WorkshopButler\Hooks\Next_Event_Hooks', 'button' ), 10 );
	}

	/**
	 * Renders registration button for a next event widget
	 *
	 * @see Next_Event_Hooks::init() for the hook
	 */
	public static function button() {
		wsb_get_template( 'next-event/button.php' );
	}
}

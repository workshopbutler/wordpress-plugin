<?php
/**
 * Initializes all public hooks for rendering widget's content
 *
 * @package WorkshopButler\Hooks
 * @since 3.0.0
 */

namespace WorkshopButler\Hooks;

require_once WSB_ABSPATH . '/public/includes/hooks/class-event-calendar-hooks.php';

/**
 * Class All_Hooks
 *
 * @package WorkshopButler\Hooks
 * @since 3.0.0
 */
class All_Hooks {

	/**
	 * Initializes all public hooks
	 */
	public static function init() {
		Event_Calendar_Hooks::init();
	}
}

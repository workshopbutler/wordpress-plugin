<?php
/**
 * Initializes all public hooks for rendering widget's content
 *
 * @package WorkshopButler\Hooks
 * @since 3.0.0
 */

namespace WorkshopButler\Hooks;

require_once WSB_ABSPATH . '/public/includes/hooks/class-event-calendar-hooks.php';
require_once WSB_ABSPATH . '/public/includes/hooks/class-single-event-hooks.php';
require_once WSB_ABSPATH . '/public/includes/hooks/class-registration-form-hooks.php';
require_once WSB_ABSPATH . '/public/includes/hooks/class-trainer-list-hooks.php';
require_once WSB_ABSPATH . '/public/includes/hooks/class-single-trainer-hooks.php';

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
		Single_Event_Hooks::init();
		Registration_Form_Hooks::init();
		Single_Trainer_Hooks::init();
		Trainer_List_Hooks::init();
	}
}

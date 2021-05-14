<?php
/**
 * Set of hooks to render event calendar
 *
 * @package WorkshopButler\Hooks
 * @since 3.0.0
 */

namespace WorkshopButler\Hooks;

use WorkshopButler\Event_Filters;

require_once WSB_ABSPATH . '/includes/wsb-core-functions.php';
require_once WSB_ABSPATH . '/includes/wsb-conditional-functions.php';

/**
 * Class Event_Calendar_Hooks
 *
 * @since 3.0.0
 * @package WorkshopButler\Hooks
 */
class Event_Calendar_Hooks {

	/**
	 * Initializes hooks available in this class
	 */
	public static function init() {
		add_action( 'wsb_filters', array( 'WorkshopButler\Hooks\Event_Calendar_Hooks', 'filters' ), 10 );
		add_action( 'wsb_calendar', array( 'WorkshopButler\Hooks\Event_Calendar_Hooks', 'calendar_content' ), 10 );
		add_action( 'wsb_calendar_item', array( 'WorkshopButler\Hooks\Event_Calendar_Hooks', 'item' ), 10 );
		add_action( 'wsb_calendar_item_title', array( 'WorkshopButler\Hooks\Event_Calendar_Hooks', 'title' ), 10 );
		add_action( 'wsb_calendar_item_time', array( 'WorkshopButler\Hooks\Event_Calendar_Hooks', 'time' ), 10 );
		add_action( 'wsb_calendar_item_date', array( 'WorkshopButler\Hooks\Event_Calendar_Hooks', 'date' ), 10 );
		add_action( 'wsb_calendar_item_image', array( 'WorkshopButler\Hooks\Event_Calendar_Hooks', 'image' ), 10 );
		add_action(
			'wsb_calendar_item_location',
			array(
				'WorkshopButler\Hooks\Event_Calendar_Hooks',
				'location',
			),
			10
		);
		add_action(
			'wsb_calendar_item_language',
			array(
				'WorkshopButler\Hooks\Event_Calendar_Hooks',
				'language',
			),
			10
		);
		add_action(
			'wsb_calendar_item_schedule',
			array(
				'WorkshopButler\Hooks\Event_Calendar_Hooks',
				'schedule',
			),
			10
		);
		add_action(
			'wsb_calendar_item_register',
			array(
				'WorkshopButler\Hooks\Event_Calendar_Hooks',
				'register',
			),
			10
		);
		add_action( 'wsb_calendar_item_tag', array( 'WorkshopButler\Hooks\Event_Calendar_Hooks', 'tag' ), 10 );
		add_action(
			'wsb_calendar_item_trainers',
			array(
				'WorkshopButler\Hooks\Event_Calendar_Hooks',
				'trainers',
			),
			10
		);
	}

	/**
	 * Renders filters for event calendar
	 *
	 * @see Event_Calendar_Hooks::init() for the hook
	 */
	public static function filters() {
		$events  = WSB()->dict->get_events();
		$filters = ( new Event_Filters( $events, WSB()->dict->get_schedule_config()->get_filters() ) )->get_filters();

		wsb_get_template( 'filters.php', array( 'filters' => $filters ) );
	}

	/**
	 * Renders the content of the calendar (tiles or rows)
	 *
	 * @see Event_Calendar_Hooks::init() for the hook
	 */
	public static function calendar_content() {
		$config = WSB()->dict->get_schedule_config();
		foreach ( WSB()->dict->get_events() as $event ) {
			WSB()->dict->set_event( $event );
			if ( $config->is_table_layout() ) {
				do_action( 'wsb_calendar_item' );
			} else {
				do_action( 'wsb_calendar_item' );
			}
			WSB()->dict->clear_event();
		}
	}

	/**
	 * Renders a item in the loop of the event calendar
	 */
	public static function item() {
		$config = WSB()->dict->get_schedule_config();
		if ( $config->is_table_layout() ) {
			wsb_get_template( 'schedule/table/row.php' );
		} else {
			wsb_get_template( 'schedule/tiles/tile.php' );
		}
	}

	/**
	 * Renders the event's title in the row
	 */
	public static function title() {
		$config = WSB()->dict->get_schedule_config();
		if ( $config->is_table_layout() ) {
			wsb_get_template( 'schedule/table/title.php' );
		} else {
			wsb_get_template( 'schedule/tiles/title.php' );
		}
	}

	/**
	 * Renders the event's trainers in the item
	 */
	public static function trainers() {
		$config = WSB()->dict->get_schedule_config();
		if ( $config->is_table_layout() ) {
			wsb_get_template( 'schedule/table/trainers.php' );
		} else {
			wsb_get_template( 'schedule/tiles/trainers.php' );
		}
	}

	/**
	 * Renders the event's location in the item
	 */
	public static function location() {
		$config = WSB()->dict->get_schedule_config();
		if ( $config->is_table_layout() ) {
			wsb_get_template( 'schedule/table/location.php' );
		} else {
			wsb_get_template( 'schedule/tiles/location.php' );
		}
	}

	/**
	 * Renders the event's schedule in the item
	 */
	public static function schedule() {
		$config = WSB()->dict->get_schedule_config();
		if ( $config->is_table_layout() ) {
			wsb_get_template( 'schedule/table/schedule.php' );
		} else {
			wsb_get_template( 'schedule/tiles/schedule.php' );
		}
	}

	/**
	 * Renders the event's start time in the item
	 */
	public static function time() {
		$config = WSB()->dict->get_schedule_config();
		if ( $config->is_table_layout() ) {
			wsb_get_template( 'schedule/table/time.php' );
		} else {
			wsb_get_template( 'schedule/tiles/time.php' );
		}
	}

	/**
	 * Renders the event's dates in the item
	 */
	public static function date() {
		$config = WSB()->dict->get_schedule_config();
		if ( $config->is_table_layout() ) {
			wsb_get_template( 'schedule/table/date.php' );
		} else {
			wsb_get_template( 'schedule/tiles/date.php' );
		}
	}

	/**
	 * Renders the event's image in the item
	 */
	public static function image() {
		$config = WSB()->dict->get_schedule_config();
		if ( ! $config->is_table_layout() ) {
			wsb_get_template( 'schedule/tiles/image.php' );
		}
	}

	/**
	 * Renders the event's language in the item
	 */
	public static function language() {
		$config = WSB()->dict->get_schedule_config();
		if ( $config->is_table_layout() ) {
			wsb_get_template( 'schedule/table/language.php' );
		} else {
			wsb_get_template( 'schedule/tiles/language.php' );
		}
	}

	/**
	 * Renders the Register button in the item
	 */
	public static function register() {
		$config = WSB()->dict->get_schedule_config();
		if ( $config->is_table_layout() ) {
			wsb_get_template( 'schedule/table/register.php' );
		} else {
			wsb_get_template( 'schedule/tiles/register.php' );
		}
	}

	/**
	 * Renders Featured/Free tags in the item
	 *
	 * @param bool $row_level_tag True if the tag is on the row level, not the cell level.
	 */
	public static function tag( $row_level_tag = false ) {
		$args   = array( 'mobile' => $row_level_tag );
		$config = WSB()->dict->get_schedule_config();
		if ( $config->is_table_layout() ) {
			wsb_get_template( 'schedule/table/tag.php', $args );
		} else {
			wsb_get_template( 'schedule/tiles/tag.php' );
		}
	}
}

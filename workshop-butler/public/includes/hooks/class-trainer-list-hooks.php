<?php
/**
 * Set of hooks to render single page
 *
 * @package WorkshopButler\Hooks
 * @since 3.0.0
 */

namespace WorkshopButler\Hooks;

use WorkshopButler\Trainer_Filters;

require_once WSB_ABSPATH . '/includes/wsb-core-functions.php';

/**
 * Class Trainer_List_Hooks
 *
 * @since 3.0.0
 * @package WorkshopButler\Hooks
 */
class Trainer_List_Hooks {

	/**
	 * Initializes hooks available in this class
	 */
	public static function init() {
		add_action( 'wsb_trainer_list_filters', array( 'WorkshopButler\Hooks\Trainer_List_Hooks', 'filters' ), 10 );
		add_action( 'wsb_trainer_list_items', array( 'WorkshopButler\Hooks\Trainer_List_Hooks', 'list_items' ), 10 );
		add_action( 'wsb_trainer_list_item', array( 'WorkshopButler\Hooks\Trainer_List_Hooks', 'item' ), 10 );
		add_action( 'wsb_trainer_list_item_photo', array( 'WorkshopButler\Hooks\Trainer_List_Hooks', 'item_photo' ), 10 );
		add_action( 'wsb_trainer_list_item_name', array( 'WorkshopButler\Hooks\Trainer_List_Hooks', 'item_name' ), 10 );
		add_action( 'wsb_trainer_list_item_country', array( 'WorkshopButler\Hooks\Trainer_List_Hooks', 'item_country' ), 10 );
		add_action( 'wsb_trainer_list_item_badges', array( 'WorkshopButler\Hooks\Trainer_List_Hooks', 'item_badges' ), 10 );
		add_action( 'wsb_trainer_list_item_rating', array( 'WorkshopButler\Hooks\Trainer_List_Hooks', 'item_rating' ), 10 );
	}

	/**
	 *
	 *
	 * @see Trainer_List_Hooks::init() for the hook
	 */
	public static function filters() {
		$trainers = WSB()->dict->get_trainers();

		$filters_config = WSB()->dict->get_trainer_list_config()->get_filters();

		$filters = (new Trainer_Filters( $trainers, $filters_config ))->get_filters();
		wsb_get_template( 'filters.php', array( 'filters' => $filters ) );
	}

	/**
	 *
	 *
	 * @see Trainer_List_Hooks::init() for the hook
	 */
	public static function item() {
		Trainer_List_Hooks::with_default_context( 'trainer/list/item.php' );
	}

	public static function item_photo() {
		Trainer_List_Hooks::with_default_context( 'trainer/list/photo.php' );
	}

	public static function item_name() {
		Trainer_List_Hooks::with_default_context( 'trainer/list/name.php' );
	}

	public static function item_country() {
		Trainer_List_Hooks::with_default_context( 'trainer/list/country.php' );
	}

	public static function item_badges() {
		Trainer_List_Hooks::with_default_context( 'trainer/list/badges.php' );
	}

	public static function item_rating() {
		Trainer_List_Hooks::with_default_context( 'trainer/list/rating.php' );
	}

	public static function list_items() {
		foreach ( WSB()->dict->get_trainers() as $trainer ) {
			WSB()->dict->set_trainer( $trainer );
			do_action( 'wsb_trainer_list_item' );
			WSB()->dict->clear_trainer();
		}
	}

	private static function with_default_context( $template ) {
		$trainer = WSB()->dict->get_trainer();
		if( !is_a( $trainer, 'WorkshopButler\Trainer' )) {
			return false;
		}
		wsb_get_template( $template, array(
			'trainer' => $trainer,
		));
	}

}

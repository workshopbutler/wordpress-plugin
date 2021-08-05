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
 * Class Single_Trainer_Hooks
 *
 * @since 3.0.0
 * @package WorkshopButler\Hooks
 */
class Single_Trainer_Hooks {

	/**
	 * Initializes hooks available in this class
	 */
	public static function init() {
		add_action( 'wsb_trainer_photo', array( 'WorkshopButler\Hooks\Single_Trainer_Hooks', 'photo' ), 10 );
		add_action( 'wsb_trainer_email', array( 'WorkshopButler\Hooks\Single_Trainer_Hooks', 'email' ), 10 );
		add_action( 'wsb_trainer_country', array( 'WorkshopButler\Hooks\Single_Trainer_Hooks', 'country' ), 10 );
		add_action( 'wsb_trainer_badges', array( 'WorkshopButler\Hooks\Single_Trainer_Hooks', 'badges' ), 10 );
		add_action( 'wsb_trainer_bio', array( 'WorkshopButler\Hooks\Single_Trainer_Hooks', 'bio' ), 10 );
		add_action( 'wsb_trainer_testimonials', array( 'WorkshopButler\Hooks\Single_Trainer_Hooks', 'testimonials' ), 10 );
		add_action( 'wsb_trainer_future_events', array( 'WorkshopButler\Hooks\Single_Trainer_Hooks', 'future_events' ), 10 );
		add_action( 'wsb_trainer_past_events', array( 'WorkshopButler\Hooks\Single_Trainer_Hooks', 'past_events' ), 10 );
		add_action( 'wsb_trainer_social_links', array( 'WorkshopButler\Hooks\Single_Trainer_Hooks', 'social_links' ), 10 );
		add_action( 'wsb_trainer_stats', array( 'WorkshopButler\Hooks\Single_Trainer_Hooks', 'stats' ), 10 );

	}

	/**
	 *
	 *
	 * @see Single_Trainer_Hooks::init() for the hook
	 */
	public static function photo() {
		Single_Trainer_Hooks::with_default_context( 'trainer/photo.php' );
	}

	/**
	 *
	 *
	 * @see Single_Trainer_Hooks::init() for the hook
	 */
	public static function email() {
		Single_Trainer_Hooks::with_default_context( 'trainer/email.php' );
	}

	/**
	 *
	 *
	 * @see Single_Trainer_Hooks::init() for the hook
	 */
	public static function country() {
		Single_Trainer_Hooks::with_default_context( 'trainer/country.php' );
	}

	/**
	 *
	 *
	 * @see Single_Trainer_Hooks::init() for the hook
	 */
	public static function badges() {
		Single_Trainer_Hooks::with_default_context( 'trainer/badges.php' );
	}

	/**
	 *
	 *
	 * @see Single_Trainer_Hooks::init() for the hook
	 */
	public static function bio() {
		Single_Trainer_Hooks::with_default_context( 'trainer/bio.php' );
	}

	/**
	 *
	 *
	 * @see Single_Trainer_Hooks::init() for the hook
	 */
	public static function testimonials() {
		Single_Trainer_Hooks::with_default_context( 'trainer/testimonials.php' );
	}

	/**
	 *
	 *
	 * @see Single_Trainer_Hooks::init() for the hook
	 */
	public static function future_events() {
		Single_Trainer_Hooks::with_default_context( 'trainer/future-events.php' );
	}

	/**
	 *
	 *
	 * @see Single_Trainer_Hooks::init() for the hook
	 */
	public static function past_events() {
		Single_Trainer_Hooks::with_default_context( 'trainer/past-events.php' );
	}

	/**
	 *
	 *
	 * @see Single_Trainer_Hooks::init() for the hook
	 */
	public static function social_links() {
		Single_Trainer_Hooks::with_default_context( 'trainer/social-links.php' );
	}

	/**
	 *
	 *
	 * @see Single_Trainer_Hooks::init() for the hook
	 */
	public static function stats() {
		Single_Trainer_Hooks::with_default_context( 'trainer/stats.php' );
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

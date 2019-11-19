<?php
/**
 * The file that defines the class for managing plugin options
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

if ( ! class_exists( 'ReduxFramework' )
	&& file_exists( dirname( __FILE__ ) . '/../lib/ReduxFramework/ReduxCore/framework.php' ) ) {
	require_once dirname( __FILE__ ) . '/../lib/ReduxFramework/ReduxCore/framework.php';
}


/**
 * This class helps to manage plugin options
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Options {

	const OLD_API_KEY       = 'wb_token';
	const OLD_SCHEDULE_PAGE = 'wb_url';

	const PLUGIN_SETTINGS   = 'wsb-settings';
	const INTERNAL_SETTINGS = 'wsb-internal-settings';

	const INT_STATE   = '_state';
	const INT_VERSION = '_version';

	const API_KEY = 'api-key';

	const SCHEDULE_TILE_TEMPLATE  = 'schedule-tile-template';
	const SCHEDULE_TABLE_TEMPLATE = 'schedule-table-template';
	const EVENT_TEMPLATE          = 'event-template';
	const REGISTRATION_TEMPLATE   = 'registration-template';
	const TRAINER_LIST_TEMPLATE   = 'trainer-list-template';
	const TRAINER_TEMPLATE        = 'trainer-template';

	const CUSTOM_CSS = 'custom-css';

	const THEME        = 'theme';
	const CUSTOM_THEME = 'custom-theme';
	const GA_API_KEY = 'google-analytics-key';

	const CUSTOM_EVENT_DETAILS   = 'custom-event-page';
	const SHOW_EXPIRED_TICKETS   = 'show-expired-tickets';
	const SHOW_NUMBER_OF_TICKETS = 'show-number-of-tickets';
	const SCHEDULE_NO_EVENTS     = 'no-events-caption';
	const SCHEDULE_LAYOUT        = 'event-list-layout';
	const SCHEDULE_PAGE          = 'event-list-page-id';
	const EVENT_PAGE             = 'event-page-id';
	const REGISTRATION_PAGE      = 'registration-page-id';

	const TRAINER_MODULE       = 'trainer-module';
	const TRAINER_LIST_PAGE    = 'trainer-list-page-id';
	const TRAINER_PROFILE_PAGE = 'trainer-page-id';

	/**
	 * Removes plugin options
	 *
	 * @since  2.0.0
	 * @return void
	 */
	public static function destroy_options() {
		delete_option( self::PLUGIN_SETTINGS );
		delete_option( self::INTERNAL_SETTINGS );
	}

	/**
	 * Returns the value of the option, or false if the option is not set
	 *
	 * @param  string $name Name of the option.
	 * @since  2.0.0
	 * @return bool|mixed
	 */
	public static function get_option( $name ) {
		$option = \Redux::getOption( self::PLUGIN_SETTINGS, $name );
		if ( null === $option ) {
			return false;
		}
		return $option;
	}

	/**
	 * Returns the value of the option, or false if the option is not set
	 *
	 * @param  string $name Name of the option.
	 * @since  2.0.0
	 * @return bool|mixed
	 */
	public static function get_internal_option( $name ) {
		$settings = get_option( self::INTERNAL_SETTINGS, array() );
		if ( array_key_exists( $name, $settings ) ) {
			return $settings[ $name ];
		} else {
			return false;
		}
	}

	/**
	 * Updates the option
	 *
	 * @param string $name  Name of the option.
	 * @param mixed  $value Value of the option.
	 *
	 * @since 2.0.0
	 */
	public static function set_option( $name, $value ) {
		\Redux::setOption( self::PLUGIN_SETTINGS, $name, $value );
	}

	/**
	 * Updates the option
	 *
	 * @param string $name  Name of the option.
	 * @param mixed  $value Value of the option.
	 *
	 * @since 2.0.0
	 */
	public static function set_internal_option( $name, $value ) {
		$settings          = get_option( self::INTERNAL_SETTINGS, array() );
		$settings[ $name ] = $value;
		update_option( self::INTERNAL_SETTINGS, $settings );
	}


	/**
	 * Returns the value of the option, or false if the option is not set
	 *
	 * @param  string $name    Name of the option.
	 * @param  mixed  $default Default value if the option does not exist.
	 * @since  2.0.0
	 * @return bool|mixed
	 */
	public function get( $name, $default = null ) {
		$option = \Redux::getOption( self::PLUGIN_SETTINGS, $name );
		if ( null === $option ) {
			return $default;
		}
		return $option;
	}

	/**
	 * Returns the url to an event page
	 *
	 * @since  2.0.0
	 * @return string|null
	 */
	public function get_event_page_url() {
		$page_id               = $this->get( self::EVENT_PAGE );
		$integrated_event_page = $this->get( self::CUSTOM_EVENT_DETAILS );
		if ( $integrated_event_page && $page_id ) {
			return get_permalink( $page_id );
		} else {
			return null;
		}
	}

	/**
	 * Returns the url to a registration page
	 *
	 * @since  2.0.0
	 * @return string|null
	 */
	public function get_registration_page_url() {
		$page_id = $this->get( self::REGISTRATION_PAGE );
		if ( $page_id ) {
			return get_permalink( $page_id );
		} else {
			return null;
		}
	}


	/**
	 * Returns the url to a trainer profile
	 *
	 * @since  2.0.0
	 * @return string|null
	 */
	public function get_trainer_page_url() {
		$page_id       = $this->get( self::TRAINER_PROFILE_PAGE );
		$active_module = $this->get( self::TRAINER_MODULE );
		if ( $active_module && $page_id ) {
			return get_permalink( $page_id );
		} else {
			return null;
		}
	}
}

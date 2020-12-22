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

if ( ! class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/../lib/ReduxFramework/ReduxCore/framework.php' ) ) {
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

	const INT_STATE            = '_state';
	const INT_VERSION          = '_version';

	const API_KEY = 'api-key';

	const SCHEDULE_TILE_TEMPLATE  = 'schedule-tile-template';
	const SCHEDULE_TABLE_TEMPLATE = 'schedule-table-template';
	const EVENT_TEMPLATE          = 'event-template';
	const REGISTRATION_TEMPLATE   = 'registration-template';
	const TRAINER_LIST_TEMPLATE   = 'trainer-list-template';
	const TRAINER_TEMPLATE        = 'trainer-template';

	const CUSTOM_CSS = 'custom-css';

	const THEME         = 'theme';
	const CUSTOM_THEME  = 'custom-theme';
	const GA_API_KEY    = 'google-analytics-key';
	const REPORT_ERRORS = 'report-errors';

	const CUSTOM_EVENT_DETAILS    = 'custom-event-page';
	const SHOW_EXPIRED_TICKETS    = 'show-expired-tickets';
	const SHOW_NUMBER_OF_TICKETS  = 'show-number-of-tickets';
	const SCHEDULE_NO_EVENTS      = 'no-events-caption';
	const SCHEDULE_LAYOUT         = 'event-list-layout';
	const SCHEDULE_PAGE           = 'event-list-page-id';
	const FEATURED_EVENTS         = 'featured-events';
	const FEATURED_ON_TOP         = 'featured-on-top';
	const SCHEDULE_LANGUAGE       = 'schedule-language';
	const SCHEDULE_LOCATION       = 'schedule-location';
	const SCHEDULE_TRAINER        = 'schedule-trainer';
	const SCHEDULE_TYPE           = 'schedule-type';
	const FILTER_LANGUAGE_ID      = 'language';
	const FILTER_LOCATION_ID      = 'location';
	const FILTER_TRAINER_ID       = 'trainer';
	const FILTER_TYPE_ID          = 'type';
	const EVENT_PAGE              = 'event-page-id';
	const EVENT_PAGE_SIDEBAR_TYPE = 'event-page-sidebar-type';
	const EVENT_PAGE_SIDEBAR_SIZE = 'event-page-sidebar-size';
	const REGISTRATION_PAGE       = 'registration-page-id';

	const TRAINER_MODULE       = 'trainer-module';
	const TRAINER_LIST_PAGE    = 'trainer-list-page-id';
	const TRAINER_PROFILE_PAGE = 'trainer-page-id';

	/**
	 * Removes plugin options
	 *
	 * @return void
	 * @since  2.0.0
	 */
	public static function destroy_options() {
		delete_option( self::PLUGIN_SETTINGS );
		delete_option( self::INTERNAL_SETTINGS );
	}

	/**
	 * Returns the value of the option, or false if the option is not set
	 *
	 * @param string      $name Name of the option.
	 * @param string|bool $default Default value if the option does is empty.
	 *
	 * @return bool|mixed
	 * @since  2.0.0
	 */
	public static function get_option( $name, $default = false ) {
		$option = \Redux::getOption( self::PLUGIN_SETTINGS, $name );
		if ( null === $option ) {
			return $default;
		}

		return $option;
	}

	/**
	 * Returns the value of the option, or false if the option is not set
	 *
	 * @param string $name Name of the option.
	 *
	 * @return bool|mixed
	 * @since  2.0.0
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
	 * @param string $name Name of the option.
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
	 * @param string $name Name of the option.
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
	 * @param string $name Name of the option.
	 * @param mixed  $default Default value if the option does not exist.
	 *
	 * @return bool|mixed
	 * @since  2.0.0
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
	 * @return string|null
	 * @since  2.0.0
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
	 * @return string|null
	 * @since  2.0.0
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
	 * @return string|null
	 * @since  2.0.0
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

	/**
	 * Returns a selected theme
	 *
	 * @return string
	 * @since 2.7.0
	 */
	public function get_theme() {
		return $this->get( self::THEME, 'alfred' );
	}

	/**
	 * Returns true if featured events are active
	 *
	 * @return bool
	 * @since 2.12.0
	 */
	public function is_featured_events_active() {
		return $this->get( self::FEATURED_EVENTS, false );
	}

	/**
	 * Returns fingerprint of the option
	 *
	 * @return string|null
	 * @since 2.14.0
	 */
	public static function get_fingerprint( $name ) {
		$content = self::get_option( $name );

		if( ! $content ) {
			return null;
		}

		// clean content from multiple spaces
		$content = trim(preg_replace('/\s+/', ' ', $content));

		return sha1($content);

	}

	/**
	 * Returns an array with template fingerprints
	 *
	 * @return array
	 * @since 2.14.0
	 */
	public static function get_template_fingerprints() {

		return array(
			self::SCHEDULE_TILE_TEMPLATE => self::get_fingerprint(self::SCHEDULE_TILE_TEMPLATE),
			self::SCHEDULE_TABLE_TEMPLATE => self::get_fingerprint(self::SCHEDULE_TABLE_TEMPLATE),
			self::EVENT_TEMPLATE => self::get_fingerprint(self::EVENT_TEMPLATE),
			self::REGISTRATION_TEMPLATE => self::get_fingerprint(self::REGISTRATION_TEMPLATE),
			self::TRAINER_LIST_TEMPLATE => self::get_fingerprint(self::TRAINER_LIST_TEMPLATE),
			self::TRAINER_TEMPLATE => self::get_fingerprint(self::TRAINER_TEMPLATE),
		);
	}
}

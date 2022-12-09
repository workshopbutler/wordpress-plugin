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

if ( ! class_exists( 'WorkshopButler\ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/../lib/ReduxFramework/ReduxCore/framework.php' ) ) {
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
	const INT_TEMPLATE_VERSION = '_tmpl_version';

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
	const USE_OLD_TEMPLATES = 'use-old-templates';
	const ALLOW_TEMPLATE_SWITCHING = 'allow-template-switching';

	const CUSTOM_EVENT_DETAILS    = 'custom-event-page';
	const SHOW_EXPIRED_TICKETS    = 'show-expired-tickets';
	const SHOW_NUMBER_OF_TICKETS  = 'show-number-of-tickets';
	const REGISTRATION_PAGE_NEW_TAB  = 'registration-page-new-tab';
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

	const TRAINER_DISPLAY_PRIVATE_RATING = 'trainer-display-private-rating';
	const TRAINER_DISPLAY_PUBLIC_RATING  = 'trainer-display-public_rating';
	const TRAINER_DISPLAY_YEARS          = 'trainer-display-years';
	const TRAINER_DISPLAY_EVENTS_HELD    = 'trainer-display-events_held';


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
		$option = Redux::getOption( self::PLUGIN_SETTINGS, $name );
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
		Redux::setOption( self::PLUGIN_SETTINGS, $name, $value );
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
	 * Returns the active template version or false if there is no version yet
	 *
	 * @return bool|string
	 * @since 2.9.0
	 */
	public static function get_template_version() {
		return self::get_internal_option( self::INT_TEMPLATE_VERSION );
	}

	/**
	 * Updates the stored version
	 *
	 * @since 2.9.0
	 */
	public static function set_template_version() {
		self::set_internal_option( self::INT_TEMPLATE_VERSION, WSB_INTEGRATION_VERSION );
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
		$option = Redux::getOption( self::PLUGIN_SETTINGS, $name );
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
		$theme = $this->get( self::THEME, 'alfred' );
		if ( $theme == 'custom' ) {
			$custom_theme = $this->get( self::CUSTOM_THEME );
			// when custom_theme is empty, we expose word 'custom'
			$theme = $custom_theme ? $custom_theme : $theme;
		}
		return $theme;
	}

	/**
	 * Returns true if featured events are active
	 *
	 * @return bool
	 * @since 3.0.0
	 */
	public function is_highlight_featured() {
		return $this->get( self::FEATURED_EVENTS, false );
	}

	/**
	 * Return true in case of old template rendering
	 *
	 * @return bool
	 * @since 3.0.0
	 */
	public function use_old_templates() {
		return $this->get( self::ALLOW_TEMPLATE_SWITCHING, false ) && $this->get( self::USE_OLD_TEMPLATES, false );
	}
}

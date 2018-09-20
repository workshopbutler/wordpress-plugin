<?php
/**
 * The file that defines the class for managing plugin options
 *
 * @link       https://workshopbutler.com
 * @since      0.3.0
 *
 * @package    WSB_Integration
 */
if ( !class_exists( 'ReduxFramework' )
     && file_exists( dirname( __FILE__ ) . '/../lib/ReduxFramework/ReduxCore/framework.php' ) ) {
    require_once( dirname( __FILE__ ) . '/../lib/ReduxFramework/ReduxCore/framework.php' );
}


/**
 * This class helps to manage plugin options
 *
 * @since      0.3.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Options {
    
    const OLD_API_KEY = 'wb_token';
    const OLD_SCHEDULE_PAGE = 'wb_url';
    
    const PLUGIN_SETTINGS = 'wsb-settings';
    const INTERNAL_SETTINGS = 'wsb-internal-settings';

    const INT_STATE = '_state';
    const INT_VERSION = '_version';
    
    const API_KEY = 'api-key';
    
    const SCHEDULE_TEMPLATE = 'schedule-template';
    const EVENT_TEMPLATE    = 'event-template';
    const REGISTRATION_TEMPLATE    = 'registration-template';
    const TRAINER_LIST_TEMPLATE = 'trainer-list-template';
    const TRAINER_TEMPLATE = 'trainer-template';
    
    const CUSTOM_CSS   = 'custom-css';
    
    const THEME        = 'theme';
    const CUSTOM_THEME = 'custom-theme';
    
    const CUSTOM_EVENT_DETAILS = 'custom-event-page';
    const SHOW_EXPIRED_TICKETS = 'show-expired-tickets';
    const SHOW_NUMBER_OF_TICKETS = 'show-number-of-tickets';
    const SCHEDULE_NO_EVENTS = 'no-events-caption';
    const SCHEDULE_LAYOUT = 'event-list-layout';
    const SCHEDULE_PAGE = 'event-list-page-id';
    const EVENT_PAGE = 'event-page-id';
    const REGISTRATION_PAGE = 'registration-page-id';
    
    const TRAINER_MODULE = 'trainer-module';
    const TRAINER_LIST_PAGE = 'trainer-list-page-id';
    const TRAINER_PROFILE_PAGE = 'trainer-page-id';
    
    /**
     * Removes plugin options
     *
     * @since  0.3.0
     * @return void
     */
    static public function destroy_options() {
        delete_option( WSB_Options::PLUGIN_SETTINGS );
        delete_option( WSB_Options::INTERNAL_SETTINGS );
    }
    
    /**
     * Returns the value of the option, or false if the option is not set
     *
     * @param  $name string Name of the option
     * @since  0.3.0
     * @return bool|mixed
     */
    static public function get_option( $name ) {
        $option = Redux::getOption(WSB_Options::PLUGIN_SETTINGS, $name );
        if ($option === null) {
            return false;
        }
        return $option;
    }
    
    /**
     * Returns the value of the option, or false if the option is not set
     *
     * @param  $name string Name of the option
     * @since  0.3.0
     * @return bool|mixed
     */
    static public function get_internal_option( $name ) {
        $settings = get_option( WSB_Options::INTERNAL_SETTINGS , array() );
        if (array_key_exists( $name, $settings)) {
            return $settings[$name];
        } else {
            return false;
        }
    }
    
    /**
     * Updates the option
     *
     * @param $name  string Name of the option
     * @param $value mixed  Value of the option
     *
     * @since 0.3.0
     */
    static public function set_option ( $name, $value ) {
        Redux::setOption(WSB_Options::PLUGIN_SETTINGS, $name, $value);
    }
    
    /**
     * Updates the option
     *
     * @param $name  string Name of the option
     * @param $value mixed  Value of the option
     *
     * @since 0.3.0
     */
    static public function set_internal_option ( $name, $value ) {
        $settings = get_option( WSB_Options::INTERNAL_SETTINGS , array() );
        $settings[$name] = $value;
        update_option( WSB_Options::INTERNAL_SETTINGS, $settings);
    }
    
    
    /**
     * Returns the value of the option, or false if the option is not set
     *
     * @param  $name    string Name of the option
     * @param  $default mixed  Default value if the option does not exist
     * @since  0.3.0
     * @return bool|mixed
     */
    public function get( $name, $default = null ) {
        $option = Redux::getOption(WSB_Options::PLUGIN_SETTINGS, $name );
        if ($option === null) {
            return $default;
        }
        return $option;
    }
    
    /**
     * Returns the url to an event page
     *
     * @since  0.3.0
     * @return string|null
     */
    public function get_event_page_url() {
        $page_id = $this->get( WSB_Options::EVENT_PAGE );
        $integrated_event_page = $this->get ( WSB_Options::CUSTOM_EVENT_DETAILS );
        if ($integrated_event_page && $page_id) {
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
        $page_id = $this->get( WSB_Options::REGISTRATION_PAGE );
        if ($page_id) {
            return get_permalink( $page_id );
        } else {
            return null;
        }
    }

    
    /**
     * Returns the url to a trainer profile
     *
     * @since  0.3.0
     * @return string|null
     */
    public function get_trainer_page_url() {
        $page_id = $this->get( WSB_Options::TRAINER_PROFILE_PAGE );
        $active_module = $this->get ( WSB_Options::TRAINER_MODULE );
        if ($active_module && $page_id) {
            return get_permalink( $page_id );
        } else {
            return null;
        }
    }
}

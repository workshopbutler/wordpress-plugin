<?php
/**
 * The file that defines the Event State formatter class
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */

/**
 * Formats an event state
 * @since 2.0.0
 */
class Event_State_Formatter {
    /**
     * @param Event_State $state Event State to format
     *
     * @since 2.0.0
     * @return string
     */
    static function format($state) {
        if ( $state->reason() ) {
            return __( $state->reason(), 'wsbintegration' );
        } else {
            return __( 'event.register', 'wsbintegration' );
        }
    }
}

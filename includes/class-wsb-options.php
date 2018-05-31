<?php
/**
 * The file that defines the class for managing plugin options
 *
 * @link       https://workshopbutler.com
 * @since      0.3.0
 *
 * @package    WSB_Integration
 */

/**
 * This class helps to manage plugin options
 *
 * @since      0.3.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Options {
    
    /**
     * Removes plugin options
     *
     * @since  0.3.0
     * @return void
     */
    static public function destroy_options() {
        delete_option( 'wsb_options' );
        delete_option( 'wsb_internal_options' );
    }
    
    /**
     * Returns available plugiin options
     *
     * @since  0.3.0
     * @return mixed
     */
    static public function get_options() {
        return get_option('wsb_options');
    }
    
    /**
     * Returns the url to an event page
     *
     * @since  0.3.0
     * @return string
     */
    static public function get_event_page_url() {
        $options = WSB_Options::get_options();
        $internal_options = get_option( "wsb_internal_options" );

        $event_page_url = $options['wsb_field_event_page'] ?
            get_permalink( $options['wsb_field_event_page'] ) :
            get_permalink( $internal_options['event_detail_page_id'] );
        
        return $event_page_url;
    }
    
    /**
     * Returns the url to a trainer profile
     *
     * @since  0.3.0
     * @return string
     */
    static public function get_trainer_page_url() {
        $options = WSB_Options::get_options();
        $internal_options = get_option( "wsb_internal_options" );
        
        $trainer_page_url = $options['wsb_field_trainer_page'] ?
            get_permalink( $options['wsb_field_trainer_page'] ) :
            get_permalink( $internal_options['trainer_detail_page_id'] );
        
        return $trainer_page_url;
    }
}

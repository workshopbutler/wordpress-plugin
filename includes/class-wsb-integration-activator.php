<?php

/**
 * Fired during plugin activation
 *
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 * @subpackage WSB_Integration/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @subpackage WSB_Integration/includes
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Integration_Activator {
    
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    0.2.0
     */
    public static function activate() {
        $options_array = [];
        
        $current_options = get_option( "wsb_internal_options" );
        
        // Check that we don't create again pages if we have created already before
        if ( empty( $current_options['event_page_id'] ) && empty( $current_options['trainer_page_id'] ) ) {
            $options_array['event_page_id'] = wp_insert_post( array(
                'post_title'     => __( 'Event List', 'wsbintegration' ),
                'post_content'   => '[wsb_events]',
                'post_type'      => 'page',
                'post_status'    => 'publish',
                'comment_status' => 'closed'
            ) );
            
            $options_array['event_detail_page_id'] = wp_insert_post( array(
                'post_title'     => __( 'Event Details', 'wsbintegration' ),
                'post_parent'    => $options_array['event_page_id'],
                'post_content'   => '[wsb_event_details]',
                'post_type'      => 'page',
                'post_status'    => 'publish',
                'comment_status' => 'closed'
            ) );
            
            $options_array['trainer_page_id'] = wp_insert_post( array(
                'post_title'     => __( 'Trainer List', 'wsbintegration' ),
                'post_content'   => '[wsb_trainers]',
                'post_type'      => 'page',
                'post_status'    => 'publish',
                'comment_status' => 'closed'
            ) );
            
            $options_array['trainer_detail_page_id'] = wp_insert_post( array(
                'post_title'     => __( 'Trainer Details', 'wsbintegration' ),
                'post_parent'    => $options_array['trainer_page_id'],
                'post_content'   => '[wsb_trainer_details]',
                'post_type'      => 'page',
                'post_status'    => 'publish',
                'comment_status' => 'closed'
            ) );
    
            WSB_Integration_Activator::set_option("wsb_internal_options", $options_array );
        }
    }
    
    protected static function set_option( $name, $value ) {
        if ( ! get_option( $name ) ) {
            add_option( $name, $value );
        } else {
            update_option( $name, $value );
        }
    }
}

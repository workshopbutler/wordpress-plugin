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
require_once plugin_dir_path( __FILE__ ) . 'class-wsb-options.php';

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
     * Adds required pages on the plugin activation if they are not added before
     *
     * @since    0.2.0
     */
    public static function activate() {
        if (empty(WSB_Options::get_option(WSB_Options::EVENT_DETAILS_PAGE))) {
            $self = new self();
            $self->create_page( __( 'Event List', 'wsbintegration' ), WSB_Options::EVENT_LIST_PAGE, '[wsb_events]' );
            $self->create_page( __( 'Event Details', 'wsbintegration' ), WSB_Options::EVENT_DETAILS_PAGE, '[wsb_event_details]' );
            $self->create_page( __( 'Trainer List', 'wsbintegration' ), WSB_Options::TRAINER_LIST_PAGE, '[wsb_trainers]' );
            $self->create_page( __( 'Trainer Profile', 'wsbintegration' ), WSB_Options::TRAINER_PROFILE_PAGE, '[wsb_trainer_details]' );
        }
    }
    
    /**
     * Adds a new post with a content
     * @param $title           string Page title
     * @param $id_opt_name     string Name of the option with a page ID
     * @param $content         string content
     *
     * @since 0.3.0
     */
    private function create_page( $title, $id_opt_name, $content) {
        $page_id = wp_insert_post( array(
            'post_title'     => $title,
            'post_content'   => $content,
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'comment_status' => 'closed'
        ) );
        WSB_Options::set_option( $id_opt_name, $page_id );
    }
    
}

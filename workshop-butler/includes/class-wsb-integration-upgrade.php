<?php
/**
 * Fired during plugin upgrade or activation
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( __FILE__ ) . 'class-wsb-options.php';

/**
 * Fired during plugin upgrade activation.
 *
 * This class defines all code necessary to run during the plugin's upgrade.
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Integration_Upgrade {

	/**
	 * Adds required pages on the plugin upgrade if they are not added before
	 *
	 * @since    2.0.0
	 */
	public function upgrade() {
		$self = new self();

		if ( WSB_INTEGRATION_VERSION === $self->get_version() ) {
			return;
		}

		$self->update_templates();
		$self->update_settings();
		$self->save_internal_settings( $self->get_version() );

		$event_page = WSB_Options::get_option( WSB_Options::EVENT_PAGE );
		if ( empty( $event_page ) ) {
			$self->create_page( __( 'Schedule', 'wsbintegration' ), WSB_Options::SCHEDULE_PAGE, '[wsb_schedule]' );
			$self->create_page(
				__( 'Event', 'wsbintegration' ),
				WSB_Options::EVENT_PAGE,
				'[wsb_event]',
				WSB_Options::get_option( WSB_Options::SCHEDULE_PAGE )
			);
			$self->create_page( __( 'Trainer List', 'wsbintegration' ), WSB_Options::TRAINER_LIST_PAGE, '[wsb_trainer_list]' );
			$self->create_page(
				__( 'Trainer Profile', 'wsbintegration' ),
				WSB_Options::TRAINER_PROFILE_PAGE,
				'[wsb_trainer]',
				WSB_Options::get_option( WSB_Options::TRAINER_LIST_PAGE )
			);
			$self->create_page(
				__( 'Registration Page', 'wsbintegration' ),
				WSB_Options::REGISTRATION_PAGE,
				'[wsb_registration]',
				WSB_Options::get_option( WSB_Options::REGISTRATION_PAGE )
			);
		}
	}

	/**
	 * Updates selected settings
	 */
	protected function update_settings() {
		if ( $this->get_version() && $this->get_version() < '2.11.0' ) {
			WSB_Options::set_option( WSB_Options::REPORT_ERRORS, true );
		}
	}

	/**
	 * Updates the classes that changed in version 2.7.1 for all related pages
	 */
	protected function update_templates() {
		if ( $this->get_version() && $this->get_version() < '2.7.1' ) {
			$this->update_classes_2_7_1( WSB_Options::EVENT_TEMPLATE );
			$this->update_classes_2_7_1( WSB_Options::TRAINER_TEMPLATE );
			$this->update_classes_2_7_1( WSB_Options::REGISTRATION_TEMPLATE );
		}
	}

	/**
	 * Updates the classes that changed in version 2.7.0 for one template
	 *
	 * @param string $option Name of the template.
	 */
	protected function update_classes_2_7_1( $option ) {
		$template = WSB_Options::get_option( $option );
		$template = str_replace( 'wsb-left-col', 'wsb-description', $template );
		$template = preg_replace( '/wsb-right-col/', 'wsb-toolbar wsb-first', $template, 1 );
		$template = preg_replace( '/wsb-right-col/', 'wsb-toolbar wsb-second', $template, 1 );
		WSB_Options::set_option( $option, $template );
	}

	/**
	 * Returns the active version or false if there is no version yet
	 *
	 * @return bool|string
	 */
	protected function get_version() {
		return WSB_Options::get_internal_option( WSB_Options::INT_VERSION );
	}

	/**
	 * Updates the stored version
	 */
	protected function set_version() {
		WSB_Options::set_internal_option( WSB_Options::INT_VERSION, WSB_INTEGRATION_VERSION );
	}

	/**
	 * Saves the internal state settings
	 *
	 * @param string $previous_version Previous version of the plugin.
	 *
	 * @since 2.0.0
	 */
	protected function save_internal_settings( $previous_version ) {
		$configured = WSB_Options::get_internal_option( WSB_Options::INT_STATE );
		if ( ! $configured ) {
			$this->update_state();
		}
		if ( WSB_INTEGRATION_VERSION !== $previous_version ) {
			$this->set_version();
		}
	}

	/**
	 * Updates the state of the plugin
	 *
	 * @since 2.0.0
	 */
	private function update_state() {
		WSB_Options::set_internal_option( WSB_Options::INT_STATE, true );
		$this->set_version();
	}

	/**
	 * Adds a new post with a content
	 *
	 * @param string      $title Page title.
	 * @param string      $id_opt_name Name of the option with a page ID.
	 * @param string      $content Page Content.
	 * @param string|null $parent_id ID of the parent page.
	 *
	 * @since 2.0.0
	 */
	private function create_page( $title, $id_opt_name, $content, $parent_id = null ) {
		$options = array(
			'post_title'     => $title,
			'post_content'   => $content,
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'comment_status' => 'closed',
		);
		if ( $parent_id ) {
			$options['post_parent'] = $parent_id;
		}
		$page_id = wp_insert_post( $options );
		WSB_Options::set_option( $id_opt_name, $page_id );
	}

}

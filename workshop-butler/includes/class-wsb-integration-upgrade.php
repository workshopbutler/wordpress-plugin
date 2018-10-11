<?php
/**
 * Fired during plugin upgrade or activation
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( __FILE__ ) . 'class-wsb-options.php';

/**
 * Fired during plugin upgrade activation.
 *
 * This class defines all code necessary to run during the plugin's upgrade.
 *
 * @since      2.0.0
 * @package    WSB_Integration
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
		if ($self->get_version() == WSB_INTEGRATION_VERSION) {
			return;
		}
		$newKey = WSB_Options::get_option( WSB_Options::API_KEY );
		if ( empty( $newKey ) ) {
			$self->transfer_settings();
		}
		$self->save_internal_settings($self->get_version());

		$eventPage = WSB_Options::get_option( WSB_Options::EVENT_PAGE );
		if ( empty( $eventPage ) ) {
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
	 * Returns the active version or false if there is no version yet
	 *
	 * @return bool|string
	 */
	protected function get_version() {
		return WSB_Options::get_internal_option(WSB_Options::INT_VERSION);
	}

	/**
	 * Updates the stored version
	 */
	protected function set_version() {
		WSB_Options::set_internal_option( WSB_Options::INT_VERSION, WSB_INTEGRATION_VERSION );
	}

	/**
	 * Saves the internal state settings

	 * @param $previous_version string Previous version of the plugin
	 * @since 2.0.0
	 */
	protected function save_internal_settings($previous_version) {
		$configured = WSB_Options::get_internal_option( WSB_Options::INT_STATE );
		if ( ! $configured ) {
			$this->update_state();
		}
		if ($previous_version != WSB_INTEGRATION_VERSION) {
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
	 * Transfers the API key to a new storage place from version 1.2 to version 2.0.0
	 *
	 * @return bool
	 */
	protected function transfer_settings() {
		$old_key = get_option( WSB_Options::OLD_API_KEY );
		if ( empty( $old_key ) ) {
			return false;
		}
		WSB_Options::set_option( WSB_Options::API_KEY, $old_key );
		return true;
	}

	/**
	 * Adds a new post with a content
	 *
	 * @param $title           string      Page title
	 * @param $id_opt_name     string      Name of the option with a page ID
	 * @param $content         string      Page Content
	 * @param $parent_id       string|null ID of the parent page
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

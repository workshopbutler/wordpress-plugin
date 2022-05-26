<?php
/**
 * The file that defines WSB_Ajax class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( __FILE__ ) . 'ui/class-embed-event-list.php';
require_once plugin_dir_path( __FILE__ ) . 'class-wsb-requests.php';

/**
 * The file that defines the Ajax-related logic
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */
class WSB_Ajax {

	/**
	 * Makes a GET request to Workshop Butler API
	 */
	public static function get_values() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			// Nonce check.
			check_ajax_referer( 'wsb-nonce' );

			$type     = sanitize_text_field( $_GET['type'] );
			$event_id = null;

			switch ( $type ) {
				case 'event-page-sidebar':
					$method   = 'events';
					$query    = WSB_Ajax::get_event_page_sidebar_params();
					$event_id = rawurlencode( sanitize_text_field( $_GET['event_id'] ) );
					break;
				case 'future-trainer-events':
					$method = 'facilitators/' . rawurlencode( sanitize_text_field( $_GET['id'] ) ) . '/events';
					$query  = array(
						'dates'  => 'future',
						'public' => true,
					);
					break;
				case 'past-trainer-events':
					$method = 'facilitators/' . rawurlencode( sanitize_text_field( $_GET['id'] ) ) . '/events';
					$query  = array(
						'dates'  => 'past',
						'public' => true,
						'sort'   => '-start_date',
					);
					break;
				default:
					die();
			}
			$requests = new Embed_Event_List( WSB_Ajax::get_sidebar_length( $type ), $event_id );
			echo $requests->render( $method, $query );
			wp_die();
		} else {
			exit();
		}
	}

	/**
	 * Returns the number of events in sidebar
	 *
	 * @param string $type Event type.
	 *
	 * @return int
	 */
	protected static function get_sidebar_length( $type ) {
		$length = 5;
		switch ( $type ) {
			case 'event-page-sidebar':
				$length = intval( WSB_Options::get_option( WSB_Options::EVENT_PAGE_SIDEBAR_SIZE ) );
				break;
			default:
				break;
		}
		$max_length = 10;

		return $length > $max_length ? $max_length : $length;
	}

	/**
	 * Returns the parameters for the list of events on Event page
	 *
	 * @return array
	 */
	protected static function get_event_page_sidebar_params() {
		if ( 'type' === WSB_Options::get_option( WSB_Options::EVENT_PAGE_SIDEBAR_TYPE ) ) {
			return array(
				'dates'     => 'future',
				'eventType' => rawurldecode( sanitize_text_field( $_GET['type_id'] ) ),
			);
		} else {
			return array(
				'dates'       => 'future',
				'countryCode' => rawurlencode( sanitize_text_field( $_GET['country_code'] ) ),
				'trainerId'   => rawurldecode( sanitize_text_field( $_GET['trainer_id'] ) ),
			);
		}
	}

	/**
	 * Makes a POST Register request to Workshop Butler API
	 */
	public static function register() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			check_ajax_referer( 'wsb-nonce' );

			$form_data = self::replace_changed_keys( $_POST );
			unset( $form_data['action'] );
			unset( $form_data['_ajax_nonce'] );

			$requests = new WSB_Requests();
			$response = $requests->post( 'attendees/register', $form_data );
			wp_send_json( $response->body, $response->http_code );
		} else {
			exit();
		}
	}

	/**
	 * Makes a POST Pre-Register request to Workshop Butler API
	 */
	public static function pre_register() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			check_ajax_referer( 'wsb-nonce' );

			$form_data = self::replace_changed_keys( $_POST );
			unset( $form_data['action'] );
			unset( $form_data['_ajax_nonce'] );

			$requests = new WSB_Requests();
			$response = $requests->post( 'attendees/pre-register', $form_data );
			wp_send_json( $response->body, $response->http_code );
		} else {
			exit();
		}
	}

	/**
	 * Makes a GET tax_validation request to Workshop Butler API
	 */
	public static function tax_validation() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			check_ajax_referer( 'wsb-nonce' );

			$requests = new WSB_Requests();
			$response = $requests->get(
				'tax-validation/'.rawurlencode( sanitize_text_field($_GET['number']) ),
				array(
					'lang' => substr( get_locale(), 0, 2 ),
					'eventId' => sanitize_text_field($_GET['eventId'])
				)
			);
			wp_send_json( $response->body, $response->http_code );
		} else {
			exit();
		}
	}

	/**
	 * Corrects form keys
	 *
	 * By some reason, WordPress replaces '.' in keys to '_'. We have to replace it back.
	 *
	 * @param array $raw_data Raw form data.
	 *
	 * @return array
	 */
	protected static function replace_changed_keys( $raw_data ) {
		$form_data = array();
		foreach ( $raw_data as $key => $value ) {
			if ( strpos( $key, 'work_' ) === 0 ) {
				$updated_key               = str_replace( 'work_', 'work.', $key );
				$form_data[ $updated_key ] = $value;
				continue;
			}
			if ( strpos( $key, 'billing_' ) === 0 ) {
				$updated_key               = str_replace( 'billing_', 'billing.', $key );
				$form_data[ $updated_key ] = $value;
				continue;
			}
			$form_data[ $key ] = $value;
		}

		return $form_data;
	}
}

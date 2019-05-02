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

			$type = $_GET['type'];

			switch ( $type ) {
				case 'future-events-country':
					$method = 'events';
					$query  = array(
						'future'      => 'true',
						'countryCode' => rawurlencode( $_GET['country_code'] ),
					);
					break;
				case 'future-trainer-events':
					$method = 'facilitators/' . rawurlencode( $_GET['id'] ) . '/events';
					$query  = array( 'future' => 'true' );
					break;
				case 'past-trainer-events':
					$method = 'facilitators/' . rawurlencode( $_GET['id'] ) . '/events';
					$query  = array( 'future' => 'false' );
					break;
				default:
					die();
					break;
			}
			$requests = new Embed_Event_List();
			echo $requests->render( $method, $query );
			wp_die();
		} else {
			exit();
		}
	}

	/**
	 * Makes a POST Register request to Workshop Butler API
	 */
	public static function register_to_event() {
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
	 * Corrects form keys
	 *
	 * By some reason, WordPress replaces '.' in keys to '_'. We have to replace it back.
	 *
	 * @param array $raw_data Raw form data.
	 * @return array
	 */
	protected static function replace_changed_keys( $raw_data ) {
		$form_data = [];
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

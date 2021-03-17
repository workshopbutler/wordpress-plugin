<?php
/**
 * This file defines the response wrapper class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * The response wrapper class
 *
 * All methods of WSB_Requests MUST return an object of this class
 *
 * It's used to make requests to Workshop Butler API in a correct way.
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Response {

	/**
	 * HTTP code
	 *
	 * @since 2.0.0
	 * @var   $http_code int HTTP code
	 */
	public $http_code;

	/**
	 * Response body
	 *
	 * @since 2.0.0
	 * @var   $body  null|string Response body
	 */
	public $body;

	/**
	 * Error message
	 *
	 * @since 2.0.0
	 * @var   $error null|string  Error message
	 */
	public $error;

	/**
	 * Creates a new object from the response of wp_remote_get/wp_remote_post functions
	 *
	 * @since 2.0.0
	 * @param array|\WP_Error $response Raw response data.
	 */
	public function __construct( $response ) {
		if ( is_wp_error( $response ) ) {
			$this->http_code = $response->get_error_code();
			$this->error     = $response->get_error_message();
		} elseif ( ! $response['body'] ) {
			$this->http_code = 422;
			$this->error     = 'Response does not contain `body` attribute';
		} else {
			$body = json_decode( $response['body'], false );
			if ( null === $body ) {
				$this->http_code = 422;
				$this->error     = 'Unprocessable Entity';
			} else {
				$this->http_code = $response['response']['code'];
				$this->body      = $body;
				if ( $this->http_code < 200 || $this->http_code > 299 ) {
					$this->error = $body->message;
				}
			}
		}
	}

	/**
	 * Returns true if the request resulted in failure
	 *
	 * @since  2.0.0
	 * @return bool
	 */
	public function is_error() {
		return empty( $this->http_code ) || $this->http_code < 200 || $this->http_code > 299;
	}
}

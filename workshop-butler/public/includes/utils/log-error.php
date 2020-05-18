<?php
/**
 * The file contains helper functions for logging error to Workshop Butler logging servers
 *
 * @link       https://workshopbutler.com
 * @since      2.11.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Logs error to Workshop Butler servers.
 *
 * @param string $where Name of class.
 * @param string $what Error description.
 * @param array  $data Error data.
 *
 * @since 2.11.0
 */
function log_error( $where, $what, $data ) {
	$data['where']      = $where;
	$data['what']       = $what;
	$data['page']       = filter_input( INPUT_SERVER, 'HTTP_HOST' ) . filter_input( INPUT_SERVER, 'REQUEST_URI' );
	$data['referer']    = filter_input( INPUT_SERVER, 'HTTP_REFERER' );
	$data['user-agent'] = filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' );

	$params            = array();
	$params['type']    = 'wp';
	$params['version'] = WSB_INTEGRATION_VERSION;
	$params['key']     = WSB_Options::get_option( WSB_Options::API_KEY );
	$params['data']    = $data;
	$url               = 'https://log.workshopbutler.com/integration/ ';
	if ( WSB_Options::get_option( WSB_Options::REPORT_ERRORS ) ) {
		wp_remote_post(
			$url,
			array(
				'method' => 'POST',
				'body'   => json_encode( $params ),
			)
		);
	}
}

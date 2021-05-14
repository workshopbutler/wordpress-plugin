<?php

use Automattic\Jetpack\Constants;

if ( ! function_exists( 'is_ajax' ) ) {

	/**
	 * Is_ajax - Returns true when the page is loaded via ajax.
	 *
	 * @return bool
	 */
	function is_ajax() {
		return function_exists( 'wp_doing_ajax' ) ? wp_doing_ajax() : Constants::is_defined( 'DOING_AJAX' );
	}
}

<?php
/**
 * Configuration for a single event page, taken completely from the UI
 *
 * @package WorkshopButler\Config
 * @since 3.0.0
 */

namespace WorkshopButler\Config;

/**
 * Class Trainer_List_Config
 *
 * @since 3.0.0
 * @package WorkshopButler\Config
 */
class Trainer_List_Config {

	/**
	 * Defines the list of filters
	 *
	 * @var array
	 * @since 3.0.0
	 */
	protected $filters = array('location', 'trainer', 'language', 'rating', 'badge');

	/**
	 * Class constructor
	 */
	public function __construct( $attrs = array() ) {
		if ( isset( $attrs['filters'] ) ) {
			$this->filters = array_map(
				function ( $name ) {
					return trim( $name );
				},
				explode( ',', $attrs['filters'] )
			);
		}
	}

	/**
	 * Returns filters
	 *
	 * @return array
	 * @since 3.0.0
	 */
	public function get_filters() {
		return $this->filters;
	}


}

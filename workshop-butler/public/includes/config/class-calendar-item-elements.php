<?php
/**
 * List of available calendar elements
 *
 * @package WorkshopButler/Config
 * @since 3.0.0
 */

namespace WorkshopButler\Config;

/**
 * Class Calendar_Item_Elements
 *
 * @since 3.0.0
 * @package WorkshopButler\Config
 */
final class Calendar_Item_Elements {

	public static $elements = array(
		'schedule',
		'location',
		'date',
		'time',
		'title',
		'register',
		'language',
		'image',
		'trainers',
	);

	public static $default_elements = array(
		'schedule',
		'location',
		'title',
		'register',
	);

	/**
	 * Returns true if the element is valid
	 *
	 * @param string $element_name Name of the possible element.
	 *
	 * @return bool
	 */
	public static function is_valid( $element_name ) {
		return in_array( $element_name, self::$elements, true );
	}

	public static function get_defaults_as_string() {
		return implode(",", self::$default_elements);
	}
}

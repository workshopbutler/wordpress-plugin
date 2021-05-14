<?php
/**
 * List of available calendar elements
 *
 * @package WorkshopButler/Config
 * @since 3.0.0
 */

namespace WorkshopButler\Config;

require WSB_ABSPATH . 'vendor/autoload.php';

/**
 * Class Calendar_Item_Elements
 *
 * @since 3.0.0
 * @package WorkshopButler\Config
 */
final class Calendar_Item_Elements {
	const SCHEDULE     = 'schedule';
	const LOCATION     = 'location';
	const DATE         = 'date';
	const TIME         = 'time';
	const TITLE        = 'title';
	const REGISTER_BTN = 'register';
	const LANGUAGE     = 'language';
	const IMAGE        = 'image';
	const TRAINERS     = 'trainers';

	/**
	 * Returns true if the element is valid
	 *
	 * @param string $element_name Name of the possible element.
	 *
	 * @return bool
	 */
	public static function is_valid( $element_name ) {
		$elements = array(
			self::SCHEDULE,
			self::LOCATION,
			self::DATE,
			self::TIME,
			self::TITLE,
			self::REGISTER_BTN,
			self::LANGUAGE,
			self::IMAGE,
			self::TRAINERS,
		);

		return in_array( $element_name, $elements, true );
	}
}

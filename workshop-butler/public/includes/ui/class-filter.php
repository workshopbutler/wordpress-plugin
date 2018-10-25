<?php
/**
 * The file that defines Filter class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Represents a list filter
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Filter {

	/**
	 * Name of the filter
	 *
	 * @since   2.0.0
	 * @var     string $name Name of the filter
	 */
	public $name;

	/**
	 * Values of the filter
	 *
	 * @since   2.0.0
	 * @var     Filter_Value[] $values Values of the filter
	 */
	public $values;

	/**
	 * Defines if the filter is visible or not
	 *
	 * @since   2.0.0
	 * @var     boolean $visible When true, the filter is visible on the page
	 */
	public $visible;

	/**
	 * Filter constructor
	 *
	 * @param string         $name    Name of the filter.
	 * @param Filter_Value[] $values  Filter's values.
	 * @param boolean        $visible True if the filter is visible.
	 */
	public function __construct( $name, $values, $visible ) {
		$this->name    = $name;
		$this->values  = $values;
		$this->visible = $visible;
	}
}

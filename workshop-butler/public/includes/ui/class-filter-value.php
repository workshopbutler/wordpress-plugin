<?php
/**
 * The file that defines Filter_Value class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Represents a value in a filter
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Filter_Value {

	/**
	 * Name of the value
	 *
	 * @since   2.0.0
	 * @var     string $name
	 */
	public $name;

	/**
	 * Value
	 *
	 * @since   2.0.0
	 * @var     string $value
	 */
	public $value;

	/**
	 * Constructs a new object
	 *
	 * @param string $name  Name of the value.
	 * @param string $value Value itself.
	 */
	public function __construct( $name, $value ) {
		$this->name  = $name;
		$this->value = $value;
	}

	/**
	 * Returns a string representation of the object
	 *
	 * @return string
	 */
	public function __toString() {
		return '{ name: ' . $this->name . ', value:' . $this->value . '}';
	}
}

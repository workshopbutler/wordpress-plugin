<?php
/**
 * The file that defines Filter_Value class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */

/**
 * Represents a value in a filter
 *
 * @since      2.0.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Filter_Value {

	/**
	 * @since   2.0.0
	 * @var     string $name Name of the value
	 */
	public $name;

	/**
	 * @since   2.0.0
	 * @var     string $value Value
	 */
	public $value;

	/**
	 * Constructs a new object
	 *
	 * @param $name string
	 * @param $value string
	 */
	public function __construct( $name, $value ) {
		$this->name  = $name;
		$this->value = $value;
	}

	public function __toString() {
		return '{ name: ' . $this->name . ', value:' . $this->value . '}';
	}
}

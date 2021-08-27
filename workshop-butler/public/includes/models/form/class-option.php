<?php
/**
 * The file that defines the Option class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Select option
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Option {
	/**
	 * Option's label
	 *
	 * @var string $label
	 * @since 2.0.0
	 */
	public $label;

	/**
	 * Value
	 *
	 * @var string $value
	 * @since 2.0.0
	 */
	public $value;

	/**
	 * Option constructor
	 *
	 * @param string $label Label.
	 * @param string $value Value.
	 *
	 * @since 2.0.0
	 */
	public function __construct( $label, $value ) {
		$this->label = $label;
		$this->value = $value;
	}

	/**
	 * Returns the value
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function get_value() {
		return $this->value;
	}

	/**
	 * Returns the label
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function get_label() {
		return $this->label;
	}
}


<?php
/**
 * The file that defines the Field class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Represents a form field
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Field {
	/**
	 * Type of the field
	 *
	 * @var string $type
	 * @since 2.0.0
	 */
	public $type;

	/**
	 * Name of the field
	 *
	 * @var string $name
	 * @since 2.0.0
	 */
	public $name;

	/**
	 * Label of the field
	 *
	 * @var string $label
	 * @since 2.0.0
	 */
	public $label;

	/**
	 * True if the field is required
	 *
	 * @var boolean $required
	 * @since 2.0.0
	 */
	public $required;

	/**
	 * True if the field is required
	 *
	 * @var boolean $required
	 * @since 2.2.1
	 */
	public $custom;


	/**
	 * Field constructor
	 *
	 * @param object $json_data JSON field data.
	 */
	public function __construct( $json_data ) {
		$this->type     = $json_data->type;
		$this->name     = $json_data->name;
		$this->label    = $json_data->label;
		$this->required = $json_data->required;
		// the only way to determine if the field is a custom one
		// is to check its name which should be only contain some numbers
		// and letters.
		if ( preg_match( '/[0-9a-f]{8}/', $this->name ) ) {
			$this->custom = true;
		} else {
			$this->custom = false;
		}
	}

	/**
	 * Returns the type of the field
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Returns the name of the field
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Returns the label of the field
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * Returns true if the field is required
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function is_required() {
		return $this->required;
	}

	/**
	 * Returns true if the field is custom
	 *
	 * @return bool
	 * @since 3.0.0
	 */
	public function is_custom() {
		return $this->custom;
	}
}

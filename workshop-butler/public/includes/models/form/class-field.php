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
 * Type of the field
 *
 * @package WorkshopButler
 */
abstract class FieldType {
	const CHECKBOX  = 'checkbox';
	const TEXT_AREA = 'textarea';
	const SELECT    = 'select';
	const COUNTRY   = 'country';
	const TICKET    = 'ticket';
	const TEXT      = 'text';
	const EMAIL     = 'email';
	const DATE      = 'date';
}

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
	 * Field constructor
	 *
	 * @param object $json_data JSON field data.
	 */
	public function __construct( $json_data ) {
		$this->type     = $json_data->type;
		$this->name     = $json_data->name;
		$this->label    = $json_data->label;
		$this->required = $json_data->required;
	}
}

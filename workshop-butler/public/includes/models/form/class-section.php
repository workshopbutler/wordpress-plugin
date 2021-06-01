<?php
/**
 * The file that defines the Section class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( __FILE__ ) . 'class-field-type.php';
require_once plugin_dir_path( __FILE__ ) . 'class-field.php';
require_once plugin_dir_path( __FILE__ ) . 'class-select.php';
require_once plugin_dir_path( __FILE__ ) . 'class-country.php';
require_once plugin_dir_path( __FILE__ ) . 'class-ticket.php';

/**
 * Represents a form section, which contains a number of fields
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Section {

	/**
	 * Section unique identifier
	 *
	 * @var string $id
	 * @since 2.7.0
	 */
	public $id;

	/**
	 * Section's fields
	 *
	 * @var Field[] $fields
	 * @since 2.0.0
	 */
	public $fields;

	/**
	 * Section's name
	 *
	 * @var string $name
	 * @since 2.0.0
	 */
	public $name;

	/**
	 * Section constructor.
	 *
	 * @param string      $id Section's unique identifier.
	 * @param string|null $name Name of the section.
	 * @param object[]    $json Section's field in JSON.
	 * @param Event       $event Related event.
	 */
	public function __construct( $id, $name, $json, $event ) {
		$this->id   = $id;
		$this->name = $name;
		$fields     = array();
		if ( is_array( $json ) ) {
			foreach ( $json as $field_data ) {
				array_push( $fields, self::create_any_field( $field_data, $event ) );
			}
		}
		$this->fields = array();
		foreach ( $fields as $field ) {
			if ( is_null( $field ) ) {
				continue;
			}
			array_push( $this->fields, $field );
		}
	}

	/**
	 * Returns the id of the section
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Returns the list of fields
	 *
	 * @since 3.0.0
	 * @return Field[]
	 */
	public function get_fields() {
		return $this->fields;
	}

	/**
	 * Creates any field, including tickets
	 *
	 * @param object $field_data JSON field data.
	 * @param Event  $event Form's event.
	 *
	 * @return Field
	 */
	protected static function create_any_field( $field_data, $event ) {
		switch ( $field_data->type ) {
			case Field_Type::SELECT:
				return new Select( $field_data );
			case Field_Type::COUNTRY:
				return new Country( $field_data );
			case Field_Type::TICKET:
				if ( $event->free || is_null( $event->tickets ) || $event->sold_out ) {
					return null;
				} else {
					return new Ticket( $field_data, $event->tickets );
				}
			default:
				return new Field( $field_data );
		}
	}
}

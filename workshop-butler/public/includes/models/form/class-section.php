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
	 * @param string $name      Name of the section.
	 * @param object $json_data Section's field in JSON.
	 * @param Event  $event     Related event.
	 */
	public function __construct( $name, $json_data, $event ) {
		$this->name = $name;
		$fields     = [];
		foreach ( $json_data as $field_data ) {
			array_push( $fields, self::create_any_field( $field_data, $event ) );
		}
		$this->fields = [];
		foreach ( $fields as $field ) {
			if ( is_null( $field ) ) {
				continue;
			}
			array_push( $this->fields, $field );
		}
	}

	/**
	 * Creates any field, including tickets
	 *
	 * @param object $field_data JSON field data.
	 * @param Event  $event      Form's event.
	 * @return Field
	 */
	protected static function create_any_field( $field_data, $event ) {
		switch ( $field_data->type ) {
			case FieldType::SELECT:
				return new Select( $field_data );
			case FieldType::COUNTRY:
				return new Country( $field_data );
			case FieldType::TICKET:
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

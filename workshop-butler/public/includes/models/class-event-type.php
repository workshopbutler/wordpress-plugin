<?php
/**
 * The file that defines Event_Type class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * This class represents a type of a Workshop Butler event
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Event_Type {
	/**
	 * ID of the type
	 *
	 * @since  2.0.0
	 * @var    int $id ID of the type
	 */
	public $id;

	/**
	 * Name of the type
	 *
	 * @since  2.0.0
	 * @var    string $name Name of the type
	 */
	public $name;

	/**
	 * URL to the badge of the type
	 *
	 * @since  2.0.0
	 * @var    string $badge URL to the badge of the type
	 */
	public $badge;

	/**
	 * Initialises a new type
	 *
	 * @param object $json_data JSON data from Workshop Butler API.
	 */
	public function __construct( $json_data ) {
		$this->name  = $json_data->name;
		$this->badge = isset( $json_data->badge ) ? $json_data->badge : null;
		$this->id    = $json_data->id;
	}

	/**
	 * Creates an empty type
	 *
	 * @return Event_Type
	 */
	public static function create_empty() {
		$empty_type        = new \stdClass();
		$empty_type->name  = '';
		$empty_type->id    = 0;
		$empty_type->badge = '';
		return new Event_Type( $empty_type );
	}
}

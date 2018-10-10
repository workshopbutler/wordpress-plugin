<?php
/**
 * The file that defines Event_Type class
 *
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */

/**
 * This class represents a type of a Workshop Butler event
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Event_Type {
	/**
	 * ID of the type
	 *
	 * @since  0.2.0
	 * @var    int $id ID of the type
	 */
	public $id;

	/**
	 * Name of the type
	 *
	 * @since  0.2.0
	 * @var    string $name Name of the type
	 */
	public $name;

	/**
	 * URL to the badge of the type
	 *
	 * @since  0.2.0
	 * @var    string $badge URL to the badge of the type
	 */
	public $badge;

	/**
	 * Initialises a new type
	 *
	 * @param $jsonData object JSON data from Workshop Butler API
	 */
	public function __construct( $jsonData ) {
		$this->name  = $jsonData->name;
		$this->badge = $jsonData->badge;
		$this->id    = $jsonData->id;
	}

	/**
	 * Creates an empty type
	 *
	 * @return Event_Type
	 */
	static function createEmpty() {
		$emptyType        = new stdClass();
		$emptyType->name  = '';
		$emptyType->id    = 0;
		$emptyType->badge = '';
		return new Event_Type( $emptyType );
	}
}

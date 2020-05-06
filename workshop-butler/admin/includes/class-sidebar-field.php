<?php
/**
 * The file that defines Sidebar_Field class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 * @subpackage WSB_Integration/includes
 */

namespace WorkshopButler;

/**
 * Field on a sidebar settings form
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @subpackage WSB_Integration/includes
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Sidebar_Field {
	/**
	 * Type of the field
	 *
	 * @var string $type
	 * @since 2.0.0
	 */
	public $type;

	/**
	 * Description of the field
	 *
	 * @var string $description
	 * @since 2.0.0
	 */
	public $description;

	/**
	 * Default value of the field
	 *
	 * @var string|boolean|int|null $default_value
	 * @since 2.0.0
	 */
	public $default_value;

	/**
	 * Sidebar_Field constructor
	 *
	 * @param string                  $type Type of the field.
	 * @param string                  $description Description of the field.
	 * @param string|boolean|int|null $default_value Default value of the field.
	 */
	public function __construct( $type, $description, $default_value = null ) {
		$this->type          = $type;
		$this->description   = $description;
		$this->default_value = $default_value;
	}
}

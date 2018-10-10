<?php
/**
 * The file that defines Filter class
 *
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */

/**
 * Represents a list filter
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Filter {

	/**
	 * Name of the filter
	 *
	 * @since   0.2.0
	 * @var     string $name Name of the filter
	 */
	public $name;

	/**
	 * Values of the filter
	 *
	 * @since   0.2.0
	 * @var     Filter_Value[] $values Values of the filter
	 */
	public $values;

	/**
	 * Defines if the filter is visible or not
	 *
	 * @since   0.2.0
	 * @var     boolean $visible When true, the filter is visible on the page
	 */
	public $visible;

	/**
	 * @param $name string
	 * @param $values Filter_Value[]
	 * @param $visible boolean
	 */
	public function __construct( $name, $values, $visible ) {
		$this->name    = $name;
		$this->values  = $values;
		$this->visible = $visible;
	}
}

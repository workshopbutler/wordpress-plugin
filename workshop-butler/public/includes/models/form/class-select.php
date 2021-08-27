<?php
/**
 * The file that defines the Select class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( __FILE__ ) . 'class-field.php';
require_once plugin_dir_path( __FILE__ ) . 'class-option.php';

/**
 * Select field
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Select extends Field {
	/**
	 * Options
	 *
	 * @var Option[] $options
	 * @since 2.0.0
	 */
	public $options;

	/**
	 * Select constructor.
	 *
	 * @param object $json_data JSON from Workshop Butler API.
	 */
	public function __construct( $json_data ) {
		parent::__construct( $json_data );
		$this->options = array();
		foreach ( $json_data->options as $option ) {
			array_push( $this->options, new Option( $option->label, $option->value ) );
		}
	}

	/**
	 * Returns the options of the field
	 *
	 * @return Option[]
	 * @since 3.0.0
	 */
	public function get_options() {
		return $this->options;
	}

}

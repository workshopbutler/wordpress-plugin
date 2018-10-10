<?php
/**
 * The file that defines the Form class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( __FILE__ ) . 'class-section.php';

/**
 * Registration or evaluation form
 *
 * @since      2.0.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Form {
	/**
	 * @var Section[] $sections Sections
	 * @since 2.0.0
	 */
	public $sections;

	/**
	 * @var string|null $instructions Fill-in instructions
	 * @since 2.0.0
	 */
	public $instructions;

	/**
	 * Form constructor.
	 *
	 * @param string|null $instructions Fill-in instructions
	 * @param object[]    $sections_data Sections in JSON
	 * @param Event       $event
	 */
	public function __construct( $instructions, $sections_data, $event ) {
		$this->instructions = $instructions;
		$this->sections     = [];
		foreach ( $sections_data as $section_data ) {
			$section = new Section( $section_data->name, $section_data->fields, $event );
			array_push( $this->sections, $section );
		}
	}

}

<?php
/**
 * The file that defines the Form class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( __FILE__ ) . 'class-section.php';
require_once plugin_dir_path( __FILE__ ) . 'class-ticket-section.php';

/**
 * Registration or evaluation form
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Form {

	/**
	 * Creates a new Form object.
	 *
	 * @param object $json JSON value.
	 * @param Event  $event Source event.
	 *
	 * @return Form|null
	 */
	static function from_json( $json, $event ) {
		return $json ? new Form( $json->instructions, $json->sections, $event ) : null;
	}

	/**
	 * Sections
	 *
	 * @var Section[] $sections
	 * @since 2.0.0
	 */
	public $sections;

	/**
	 * Fill-in instructions
	 *
	 * @var string|null $instructions
	 * @since 2.0.0
	 */
	public $instructions;

	/**
	 * Form constructor.
	 *
	 * @param string|null $instructions Fill-in instructions.
	 * @param object[]    $sections Sections in JSON.
	 * @param Event       $event Related event.
	 */
	public function __construct( $instructions, $sections, $event ) {
		$this->instructions = $instructions;
		$this->sections     = array();
		foreach ( $sections as $json_section ) {
			array_push(
				$this->sections,
				new Section( $json_section->id, $json_section->label, $json_section->fields, $event )
			);
		}
	}

	/**
	 * Returns the fill-in instructions for the form
	 *
	 * @since 3.0.0
	 * @return string|null
	 */
	public function get_instructions() {
		return $this->instructions;
	}

	/**
	 * Returns the sections which the form consists of
	 *
	 * @since 3.0.0
	 * @return Section[]
	 */
	public function get_sections() {
		return $this->sections;
	}
}

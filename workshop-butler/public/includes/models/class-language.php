<?php
/**
 * The file that defines the language class, used later in templates
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/language.php';

/**
 * Language(-s) of the workshop
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Language {

	/**
	 * Creates Language object from JSON
	 *
	 * @param object $json JSON to convert.
	 *
	 * @return Language
	 */
	static function from_json( $json ) {
		return new Language( $json->spoken, $json->materials );
	}

	/**
	 * The array of spoken languages' codes at the workshop
	 *
	 * @since 2.0.0
	 * @var   string[] $spoken
	 */
	public $spoken;

	/**
	 * The language of learning materials at the workshop
	 *
	 * @since 2.0.0
	 * @var string|null $materials
	 */
	public $materials;

	/**
	 * Language constructor
	 *
	 * @param string[]    $spoken Spoken languages at the workshop.
	 * @param string|null $materials Materials' language.
	 */
	public function __construct( $spoken, $materials ) {
		$this->spoken    = $spoken;
		$this->materials = $materials;
	}
}

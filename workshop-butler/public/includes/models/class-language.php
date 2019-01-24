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
	 * @param string[]    $spoken    Spoken languages at the workshop.
	 * @param string|null $materials Materials' language.
	 */
	public function __construct( $spoken, $materials ) {
		$this->spoken = array();
		foreach ( $spoken as $lang ) {
			array_push( $this->spoken, get_lang_code( $lang ) );
		}
		$this->materials = $materials ? get_lang_code( $materials ) : null;
	}
}

<?php
/**
 * The file that defines the language class, used later in templates
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/language.php';

/**
 * Language(-s) of the workshop
 *
 * @since      2.0.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Language {
	/**
	 * @since 2.0.0
	 * @var   string[] $spoken The array of spoken languages at the workshop
	 */
	public $spoken;

	/**
	 * @since 2.0.0
	 * @var string|null $materials The language of learning materials at the workshop
	 */
	public $materials;

	public function __construct( $spoken, $materials ) {
		$this->spoken = [];
		foreach ( $spoken as $lang ) {
			array_push( $this->spoken, get_lang_code( $lang ) );
		}
		$this->materials = $materials ? get_lang_code( $materials ) : null;
	}
}

<?php
/**
 * The file that defines the Country class
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path(  __FILE__  ) . 'class-field.php';

/**
 * Form field with the list of countries
 *
 * @since      2.0.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Country extends Field {
    /**
     * @var string[] $countries List of countries
     * @since 2.0.0
     */
    public $countries;
    
    public function __construct( $json_data ) {
        parent::__construct( $json_data );
    }
}

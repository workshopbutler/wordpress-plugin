<?php
/**
 * The file that defines the Select class
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path(  __FILE__  ) . 'class-field.php';

class Option {
    /**
     * @var string $label Option's label
     * @since 2.0.0
     */
    public $label;
    
    /**
     * @var string $value Value
     * @since 2.0.0
     */
    public $value;
    
    /**
     * Option constructor
     *
     * @param string $label Label
     * @param string $value Value
     * @since 2.0.0
     */
    public function __construct($label, $value) {
        $this->label = $label;
        $this->value = $value;
    }
}

/**
 * Select field
 *
 * @since      2.0.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Select extends Field {
    /**
     * @var Option[] $options Options
     * @since 2.0.0
     */
    public $options;
    
    public function __construct( $json_data ) {
        parent::__construct( $json_data );
        $this->options = $json_data->options;
    }
}

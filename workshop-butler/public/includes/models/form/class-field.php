<?php
/**
 * The file that defines the Field class
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */

abstract class FieldType {
    const CHECKBOX = 'checkbox';
    const TEXT_AREA = 'textarea';
    const SELECT = 'select';
    const COUNTRY = 'country';
    const TICKET = 'ticket';
    const TEXT = 'text';
    const EMAIL = 'email';
    const DATE = 'date';
}

/**
 * Represents a form field
 *
 * @since      2.0.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Field {
    /**
     * @var string $type Type of the field
     * @since 2.0.0
     */
    public $type;
    
    /**
     * @var string $name Name of the field
     * @since 2.0.0
     */
    public $name;
    
    /**
     * @var string $label Label of the field
     * @since 2.0.0
     */
    public $label;
    
    /**
     * @var boolean $required True if the field is required
     * @since 2.0.0
     */
    public $required;
    
    /**
     * Field constructor
     *
     * @param object $json_data JSON field data
     */
    public function __construct($json_data) {
        $this->type = $json_data->type;
        $this->name = $json_data->name;
        $this->label = $json_data->label;
        $this->required = $json_data->required;
    }
}

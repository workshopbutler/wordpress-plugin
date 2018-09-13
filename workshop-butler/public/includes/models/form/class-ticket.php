<?php
/**
 * The file that defines the Ticket class
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path(  __FILE__  ) . 'class-field.php';

/**
 * Form field with tickets' info, where visitors can select a ticket of their choice
 *
 * @since      2.0.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Ticket extends Field {
    /**
     * @var boolean $excluded_tax True if a sales tax is not included in the prices
     * @since 2.0.0
     */
    public $excluded_tax;
    
    /**
     * @var Option[] $options Options
     * @since 2.0.0
     */
    public $options;
    
    /**
     * @var Tickets $tickets Tickets
     * @since 2.0.0
     */
    public $tickets;
    
    /**
     * Ticket constructor
     *
     * @param object $json_data JSON field data
     * @param Tickets $tickets Available event's tickets
     */
    public function __construct( $json_data, $tickets ) {
        parent::__construct( $json_data );
        $this->tickets = $tickets;
        $this->excluded_tax = false;
        foreach ($tickets->paid as $ticket) {
            if ($ticket->excluded_tax) {
                $this->excluded_tax = true;
                break;
            }
        }
    }
}

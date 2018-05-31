<?php
/**
 * The file that defines Paid_Ticket_type class
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-ticket-price.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-ticket-type-state.php';

/**
 * This class represents a paid ticket type in a Workshop Butler event
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Paid_Ticket_Type {
    /**
     * ID of the type
     * @since  0.2.0
     * @var    int $id ID of the type
     */
    public $id;
    
    /**
     * Name of the type
     *
     * @since  0.2.0
     * @var    string $name Name of the type
     */
    public $name;
    
    /**
     * Number of tickets
     *
     * @since  0.2.0
     * @var    int $number_of_tickets Number of tickets
     */
    public $number_of_tickets;
    
    /**
     * Number of tickets on sale
     *
     * @since  0.2.0
     * @var    int $number_of_tickets Number of tickets on sale
     */
    public $number_of_tickets_left;
    
    /**
     * Date when the tickets of this type go on sale
     *
     * @since  0.2.0
     * @var    DateTime $start Date when the tickets of this type go on sale
     */
    public $start;
    
    /**
     * Date when sales of the tickets of this type end
     *
     * @since  0.2.0
     * @var    DateTime $end Date when sales of the tickets of this type end
     */
    public $end;
    
    /**
     * If true, the price of the ticket includes tax
     *
     * @since  0.2.0
     * @var    boolean $with_tax If true, the price of the ticket includes tax
     */
    public $with_tax;
    
    /**
     * @since   0.2.0
     * @var     Ticket_Price $price
     */
    public $price;
    
    /**
     * @since   0.2.0
     * @var     Ticket_Type_State $state
     */
    private $state;
    
    /**
     * Creates a new paid ticket type from JSON
     *
     * @param $jsonData object JSON for a ticket type
     */
    public function __construct($jsonData) {
        $this->id = $jsonData->id;
        $this->name = $jsonData->name;
        $this->number_of_tickets = $jsonData->amount;
        $this->number_of_tickets_left = $jsonData->left;
        $this->start = new DateTime($jsonData->start);
        $this->end = new DateTime($jsonData->end);
        $this->with_tax = $jsonData->with_vat;
        $this->price = new Ticket_Price($jsonData->price);
        $this->state = new Ticket_Type_State($jsonData->state);
    }
    
    /**
     * Returns correctly-formatted ticket price
     *
     * This method is used in templates
     *
     * @since   0.2.0
     * @return  string
     */
    public function formatted_price() {
        $amount = $this->price->sign . $this->price->amount;
        if ($this->with_tax) {
            $amount .= ' + VAT';
        }
        return $amount;
    }
    
    /**
     * Returns correctly-formatted ticket description
     *
     * This method is used in templates
     *
     * @since   0.2.0
     * @return  string
     */
    public function formatted_description() {
        if ($this->is_ended()) {
            return __('Offer ended on ', 'wsbintegration') . $this->end->format('d.m.Y');
        }
        if ($this->is_active()) {
            return __('Offer ends on ', 'wsbintegration') . $this->end->format('d.m.Y');
        }
        return __('On sale from ', 'wsbintegration') . $this->start->format('d.m.Y');
    }
    
    /**
     * Returns the state of the ticket type in a human-readable form
     *
     * This method is used in templates
     *
     * @since   0.2.0
     * @return  string
     */
    public function formatted_state() {
        if ($this->is_sold_out()) {
            return __( 'Sold out', 'wsbintegration' );
        } else if ($this->is_ended()) {
            return __('Ended', 'wsbintegration');
        } else {
            if ($this->number_of_tickets_left == 1) {
                return __('1 ticket left', 'wsbintegration');
            } else if ($this->number_of_tickets_left < 0) {
                return '';
            } else {
                return $this->number_of_tickets_left . __(' tickets left', 'wsbintegration');
            }
        }
    }
    
    /**
     * Returns true if the tickets of this type can be bought
     *
     * @since  0.2.0
     * @return boolean
     */
    public function is_active() {
        return $this->state->valid;
    }
    
    /**
     * Returns true if the tickets of this type can be bought later, in future
     *
     * @since  0.2.0
     * @return boolean
     */
    public function is_in_future() {
        return $this->state->in_future;
    }
    
    /**
     * Returns true if no more seats left
     *
     * @since  0.2.0
     * @return boolean
     */
    public function is_sold_out() {
        return $this->state->sold_out;
    }
    
    /**
     * Returns true if the sales of tickets of this type have ended
     *
     * @since  0.2.0
     * @return boolean
     */
    public function is_ended() {
        return $this->state->ended;
    }
}

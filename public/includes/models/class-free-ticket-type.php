<?php
/**
 * The file that defines Free_Ticket_type class
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */

/**
 * This class represents a free ticket type in a Workshop Butler event
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Free_Ticket_Type {
    
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
     * If true, there is unlimited amount of free tickets for an event
     *
     * @since  0.2.0
     * @var    boolean $unlimited If true, there is unlimited amount of free tickets for an event
     */
    private $unlimited;
    
    /**
     * If true, all free tickets are sold out
     *
     * @since  0.2.0
     * @var    boolean $sold_out If true, all free tickets are sold out
     */
    private $sold_out;
    
    /**
     * Creates a new paid ticket type from JSON
     *
     * @param $jsonData object JSON for a ticket type
     */
    public function __construct($jsonData) {
        $this->number_of_tickets      = $jsonData->amount;
        $this->number_of_tickets_left = $jsonData->left;
        $this->start                  = new DateTime($jsonData->start);
        $this->end                    = new DateTime($jsonData->end);
        $this->unlimited              = $jsonData->unlimited;
        $this->sold_out               = $jsonData->state->sold_out;
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
            return __('Sold out', 'wsbintegration');
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
     * Returns true if no more seats left
     *
     * @since  0.2.0
     * @return boolean
     */
    public function is_sold_out() {
        return $this->sold_out;
    }
    
    /**
     * Returns true if there is unlimited amount of free tickets for an event
     *
     * @since  0.2.0
     * @return boolean
     */
    public function with_unlimited_seats() {
        return $this->unlimited;
    }
}

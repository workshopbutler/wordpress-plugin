<?php
/**
 * The file that defines the event class, used later in templates
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-event-type.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-free-ticket-type.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-paid-ticket-type.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-tickets.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-trainer.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'helper-functions.php';

/**
 * Trainer class which represents an event in Workshop Butler
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Event {
    /**
     * @since   0.2.0
     * @var     int $id ID of the event
     */
    public $id;
    
    /**
     * @since   0.2.0
     * @var     string $hashed_id Hashed ID of the event
     */
    public $hashed_id;
    
    /**
     * @since   0.2.0
     * @var     Event_Type $type Type of the event
     */
    public $type;
    
    /**
     * @since   0.2.0
     * @var     string $title
     */
    public $title;
    
    /**
     * @since   0.2.0
     * @var     string[] $spoken_languages
     */
    public $spoken_languages;
    
    /**
     * @since   0.2.0
     * @var     string $materials_language
     */
    public $materials_language;
    
    /**
     * @since   0.2.0
     * @var     DateTime $start
     */
    public $start;
    
    /**
     * @since   0.2.0
     * @var     DateTime $end
     */
    public $end;
    
    /**
     * @since   0.2.0
     * @var     int $hours_per_day
     */
    public $hours_per_day;
    
    /**
     * @since   0.2.0
     * @var     int $total_hours
     */
    public $total_hours;
    
    /**
     * @since   0.2.0
     * @var     string $city
     */
    public $city;
    
    /**
     * @since   0.2.0
     * @var     string $country
     */
    public $country;
    
    /**
     * @since   0.2.0
     * @var     float $rating
     */
    public $rating;
    
    /**
     * @since   0.2.0
     * @var     boolean $confirmed
     */
    public $confirmed;
    
    /**
     * @since   0.2.0
     * @var     boolean $private
     */
    public $private;
    
    /**
     * @since   0.2.0
     * @var     boolean $free
     */
    public $free;
    
    /**
     * @since   0.2.0
     * @var     boolean $online
     */
    public $online;
    
    /**
     * @since   0.2.0
     * @var     boolean $sold_out
     */
    public $sold_out;
    
    /**
     * @since   0.2.0
     * @var     string $url
     */
    public $url;
    
    /**
     * @since   0.2.0
     * @var     object $registration
     */
    public $registration;
    
    /**
     * @since   0.2.0
     * @var     Tickets|null $tickets
     */
    public $tickets;
    
    /**
     * @since   0.2.0
     * @var     Trainer[] $trainers
     */
    public $trainers;
    
    /**
     * @since   0.2.0
     * @var     string $description
     */
    public $description;
    
    /**
     * @since   0.2.0
     * @var     string $schedule
     */
    public $schedule;
    
    /**
     * @since   0.2.0
     * @var     string $location
     */
    public $location;
    
    /**
     * @since   0.2.0
     * @var     string $instructions
     */
    public $instructions;
    
    /**
     * @since   0.2.0
     * @var     object $registration_form
     */
    public $registration_form;
    
    /**
     * @since   0.2.0
     * @var     object $registration_page
     */
    private $registration_page;
    
    /**
     * Creates a new object
     *
     * @param $json_data object JSON data from Workshop Butler API
     * @param $eventPageUrl string Event page URL on the integrated website
     * @param $trainerPageUrl string Trainer profile page URL on the integrated website
     */
    public function __construct( $json_data, $eventPageUrl, $trainerPageUrl ) {
        $this->id                 = $json_data->id;
        $this->hashed_id          = $json_data->hashed_id;
        $this->title              = $json_data->title;
        $this->type               = $json_data->type ? new Event_Type( $json_data->type ) : Event_Type::createEmpty();
        $this->spoken_languages   = $json_data->spoken_languages;
        $this->materials_language = $json_data->materials_language;
        $this->start              = new DateTime( $json_data->start );
        $this->end                = new DateTime( $json_data->end );
        $this->hours_per_day      = $json_data->hours_per_day;
        $this->total_hours        = $json_data->total_hours;
        $this->city               = $json_data->city;
        $this->country            = wsb_get_country_name($json_data->country);
        $this->rating             = $json_data->rating;
        $this->confirmed          = $json_data->confirmed;
        $this->free               = $json_data->free;
        $this->private            = $json_data->private;
        $this->online             = $json_data->online;
        $this->description        = $json_data->description;
        $this->sold_out           = $json_data->sold_out;
        
        $this->url          = $eventPageUrl . '?id=' . $this->hashed_id;
        $this->tickets      = $this->get_tickets( $this->free, $json_data->free_ticket_type, $json_data->paid_ticket_types );
        
        $this->instructions      = $json_data->instructions;
        $this->registration_form = $this->get_registration_form( $this->tickets, $json_data->registration_form );
        $this->registration_page = $json_data->registration_page;
        
        $this->trainers= $this->get_trainers($json_data, $trainerPageUrl);
        $this->schedule = wsb_get_date_interval($this->start, $this->end, false);
        $this->location = wsb_get_event_location($json_data->country, $json_data->city);
    
    }
    
    /**
     * Returns event languages (spoken and materials), formatted in a correct way
     *
     * This function is used in templates
     *
     * @since  0.2.0
     * @return string
     */
    public function formatted_languages() {
        $prefix = __('The event is in ', 'wsbintegration') . __($this->spoken_languages[0], 'wsbintegration');
        if (count($this->spoken_languages) > 1) {
            $prefix .= __(' and ', 'wsbintegration') . __($this->spoken_languages[1], 'wsbintegration');
        }
        $suffix = '.';
        if ($this->materials_language) {
            $suffix = __(', all materials and handouts are in ', 'wsbintegration') . __($this->materials_language, 'wsbintegration');
        }
        return $prefix . $suffix;
    }
    
    /**
     * Returns the list of trainers' names
     *
     * This method is used in templates
     *
     * @since  0.2.0
     * @return string[]
     */
    public function names_of_trainers() {
        return array_map(function($trainer) {
            return $trainer->full_name();
        }, $this->trainers);
    }
    
    /**
     * Returns true if the registration for the event is closed
     *
     * This method is used in templates
     *
     * @since  0.2.0
     * @return boolean
     */
     public function is_registration_closed() {
        if ($this->is_ended()) {
            return true;
        } else if ($this->private) {
            return true;
        } else if ($this->free && $this->tickets->free->is_sold_out()) {
            return true;
        } else {
            if (!$this->free && $this->tickets && $this->tickets->non_empty()) {
                $closed = true;
                foreach ($this->tickets->paid as $ticket) {
                    if ($ticket->is_active()) {
                        $closed = false;
                    }
                }
                return $closed;
            } else {
                return false;
            }
        }
    }
    
    /**
     * Returns an explanation why the registrations are closed
     *
     * This method is used in templates
     *
     * @since  0.2.0
     * @return string
     */
    public function reason_for_closed_registration() {
        if ($this->is_ended() || !$this->tickets) {
           return __('The event has ended', 'wsbintegration');
        } else if ($this->private) {
            return __('The event is private', 'wsbintegration');
        } else if ($this->free && $this->tickets->free && $this->tickets->free->is_sold_out()) {
            return __('Sold out', 'wsbintegration');
        } else {
            if (!$this->free) {
                $sold_out = true;
                foreach ($this->tickets->paid as $ticket) {
                    if (!$ticket->is_sold_out()) {
                        $sold_out = false;
                    }
                }
                if ($sold_out) {
                    return __('Sold out', 'wsbintegration');
                } else {
                    return __('Registrations are closed', 'wsbintegration');
                }
            } else {
                return '';
            }
        }
    }

    
    /**
     * Returns an URL of a custom registration form or null if a built-in registration is used
     *
     * @since  0.2.0
     * @return string | null
     */
    public function get_registration_url() {
        $external_registration = $this->registration_page && $this->registration_page->custom;
        if ($external_registration) {
            return $this->registration_page->url;
        } else {
            return null;
        }
    }
    
    /**
     * Returns Tickets object
     *
     * @param $free boolean True if the event is free
     * @param $free_ticket_type Free_Ticket_Type
     * @param $paid_ticket_types Paid_Ticket_Type[]
     *
     * @return null|Tickets
     */
    private function get_tickets( $free, $free_ticket_type, $paid_ticket_types ) {
        if ( $free_ticket_type || $paid_ticket_types ) {
            $paid_tickets = array_map( function ( $type ) {
                return new Paid_Ticket_Type( $type );
            }, $paid_ticket_types );
            
            return $free ?
                new Tickets( [], new Free_Ticket_Type( $free_ticket_type ) ) :
                new Tickets( $paid_tickets, null );
        } else {
            return null;
        }
    }
    
    /**
     * Returns a list of event trainers
     *
     * @param $jsonData object  JSON representation of a trainer
     * @param $trainer_page_url string Trainer profile page URL on the integrated website
     * @return Trainer[]
     */
    private function get_trainers($jsonData, $trainer_page_url) {
        if ($jsonData->facilitators) {
            $trainers = [];
            foreach ($jsonData->facilitators as $trainer) {
                array_push($trainers, new Trainer($trainer, $trainer_page_url));
            }
            return $trainers;
        } else {
            return [];
        }
    }
    
    /**
     * Adds ticket field if the paid tickets exist
     *
     * @param Tickets | null tickets
     * @param object form Registration form
     *
     * @return object
     */
    private function get_registration_form( $tickets, $form ) {
        if ( $tickets && count( $tickets->paid ) > 0 && $form ) {
            foreach ( $form as &$section ) {
                $fields = $section->fields;
                foreach ( $fields as &$field ) {
                    if ( $field->type !== 'ticket' ) {
                        continue;
                    }
                    $options = [];
                    foreach ( $tickets->paid as $ticket ) {
                        if ( $ticket->is_active() ) {
                            array_push( $options,
                                array(
                                    'value' => $ticket->id,
                                    'label' => $ticket->formatted_price() . ' – ' . $ticket->name
                                ) );
                        }
                    }
                    if ( count( $options ) > 0 ) {
                        $field->options = $options;
                        $field->type    = 'select';
                        $field->required = true;
                    }
                }
            }
        }
        
        return $form;
    }
    
    /**
     * Returns true if the event ended
     * @return bool
     */
    private function is_ended() {
        return $this->end < new DateTime();
    }
    
}

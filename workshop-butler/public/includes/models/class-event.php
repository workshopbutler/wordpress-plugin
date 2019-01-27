<?php
/**
 * The file that defines the event class, used later in templates
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-event-type.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-free-ticket-type.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-paid-ticket-type.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-tickets.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-trainer.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-language.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-schedule.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-location.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-event-state.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-registration-page.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-event-url.php';
require_once plugin_dir_path( __FILE__ ) . 'form/class-form.php';


/**
 * Trainer class which represents an event in Workshop Butler
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Event {
	/**
	 * ID of the event
	 *
	 * @since   2.0.0
	 * @var     int $id ID of the event
	 */
	public $id;

	/**
	 * Hashed ID of the event
	 *
	 * @since   2.0.0
	 * @var     string $hashed_id Hashed ID of the event
	 */
	public $hashed_id;

	/**
	 * Type of the event
	 *
	 * @since   2.0.0
	 * @var     Event_Type $type Type of the event
	 */
	public $type;

	/**
	 * Title of the event
	 *
	 * @since   2.0.0
	 * @var     string $title
	 */
	public $title;

	/**
	 * Languages of the event
	 *
	 * @since   2.0.0
	 * @var     Language $language
	 */
	public $language;

	/**
	 * Schedule of the event
	 *
	 * @since   2.0.0
	 * @var     Schedule $schedule
	 */
	public $schedule;

	/**
	 * Location of the event
	 *
	 * @since   2.0.0
	 * @var     Location $location Location of the workshop
	 */
	public $location;

	/**
	 * Event's rating
	 *
	 * @since   2.0.0
	 * @var     float $rating
	 */
	public $rating;

	/**
	 * True if the event is confirmed
	 *
	 * @since   2.0.0
	 * @var     boolean $confirmed
	 */
	public $confirmed;

	/**
	 * True if the event is private
	 *
	 * @since   2.0.0
	 * @var     boolean $private
	 */
	public $private;

	/**
	 * True if the event is free
	 *
	 * @since   2.0.0
	 * @var     boolean $free
	 */
	public $free;

	/**
	 * True if there is no tickets left
	 *
	 * @since   2.0.0
	 * @var     boolean $sold_out
	 */
	public $sold_out;

	/**
	 * Tickets to the event
	 *
	 * @since   2.0.0
	 * @var     Tickets|null $tickets
	 */
	public $tickets;

	/**
	 * Trainers who run the workshop
	 *
	 * @since   2.0.0
	 * @var     Trainer[] $trainers
	 */
	public $trainers;

	/**
	 * Description
	 *
	 * @since   2.0.0
	 * @var     string $description
	 */
	public $description;

	/**
	 * Registration form
	 *
	 * @since   2.0.0
	 * @var     Form $registration_form
	 */
	public $registration_form;

	/**
	 * Url to the registration page
	 *
	 * @since   2.0.0
	 * @var     Registration_Page $registration_page
	 */
	public $registration_page;

	/**
	 * State of the workshop
	 *
	 * @var Event_State $state
	 * @since 2.0.0
	 */
	public $state;

	/**
	 * The url to the event
	 *
	 * @since   2.0.0
	 * @var     Event_Url $url
	 */
	private $url;

	/**
	 * Creates a new object
	 *
	 * @param object      $json_data             JSON data from Workshop Butler API.
	 * @param string|null $event_page_url        Event page URL on the integrated website.
	 * @param string|null $trainer_page_url      Trainer profile page URL on the integrated website.
	 * @param string|null $registration_page_url Registration page URL on the integrated website.
	 */
	public function __construct( $json_data, $event_page_url, $trainer_page_url, $registration_page_url ) {
		$this->id          = $json_data->id;
		$this->hashed_id   = $json_data->hashed_id;
		$this->title       = $json_data->title;
		$this->type        = $json_data->type ? new Event_Type( $json_data->type ) : Event_Type::create_empty();
		$this->language    = new Language( $json_data->spoken_languages, $json_data->materials_language );
		$this->rating      = $json_data->rating;
		$this->confirmed   = $json_data->confirmed;
		$this->free        = $json_data->free;
		$this->private     = $json_data->private;
		$this->description = $json_data->description;
		$this->sold_out    = $json_data->sold_out;
		$this->schedule    = new Schedule( $json_data->schedule );
		$this->location    = new Location( $json_data->location );

		if ( $json_data->custom_settings && $json_data->custom_settings->title_url ) {
			$this->url = Event_Url::external( $json_data->custom_settings->title_url );
		} elseif ( $event_page_url ) {
			$this->url = Event_Url::internal( $event_page_url . '?id=' . $this->hashed_id );
		} else {
			$this->url = Event_Url::external( 'https://workshopbutler.com/public/event/' . $this->hashed_id );
		}
		$this->tickets = $this->get_tickets( $this->free, $json_data->free_ticket_type, $json_data->paid_ticket_types );

		$this->registration_form = $json_data->registration_form ?
			new Form( $json_data->instructions, $json_data->registration_form, $this ) :
			null;

		$this->registration_page = new Registration_Page(
			$json_data->registration_page,
			$registration_page_url,
			$this->hashed_id
		);

		$this->trainers = $this->get_trainers( $json_data, $trainer_page_url );
		$this->state    = new Event_State( $this );
	}

	/**
	 * Returns the URL for the event's page
	 *
	 * @since 2.1.0
	 * @return string
	 */
	public function url() {
		return $this->url->url;
	}

	/**
	 * Returns true if the URL leads to a third-party website
	 *
	 * @since  2.1.0
	 * @return boolean
	 */
	public function is_url_external() {
		return $this->url->on_third_party_website;
	}

	/**
	 * Returns the list of trainers' names
	 *
	 * This method is used in templates
	 *
	 * @since  2.0.0
	 * @return string[]
	 */
	public function names_of_trainers() {
		return array_map(
			function ( $trainer ) {
				return $trainer->full_name();
			},
			$this->trainers
		);
	}

	/**
	 * Returns Tickets object
	 *
	 * @param boolean            $free              True if the event is free.
	 * @param Free_Ticket_Type   $free_ticket_type  Free tickets.
	 * @param Paid_Ticket_Type[] $paid_ticket_types Paid tickets.
	 *
	 * @return null|Tickets
	 */
	private function get_tickets( $free, $free_ticket_type, $paid_ticket_types ) {
		if ( $free_ticket_type || $paid_ticket_types ) {
			$paid_tickets = array_map(
				function ( $type ) {
					return new Paid_Ticket_Type( $type );
				},
				$paid_ticket_types
			);
			return $free ?
				new Tickets( array(), new Free_Ticket_Type( $free_ticket_type ) ) :
				new Tickets( $paid_tickets, null );
		} else {
			return null;
		}
	}

	/**
	 * Returns a list of event trainers
	 *
	 * @param object      $json_data        JSON representation of a trainer.
	 * @param string|null $trainer_page_url Trainer profile page URL on the integrated website.
	 * @return Trainer[]
	 */
	private function get_trainers( $json_data, $trainer_page_url ) {
		if ( $json_data->facilitators ) {
			$trainers = array();
			foreach ( $json_data->facilitators as $trainer ) {
				array_push( $trainers, new Trainer( $trainer, $trainer_page_url ) );
			}
			return $trainers;
		} else {
			return array();
		}
	}

}

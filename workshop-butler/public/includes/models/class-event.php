<?php
/**
 * The file that defines the event class, used later in templates
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */
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
require_once plugin_dir_path( __FILE__ ) . 'form/class-form.php';

/**
 * Trainer class which represents an event in Workshop Butler
 *
 * @since      2.0.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Event {
	/**
	 * @since   2.0.0
	 * @var     int $id ID of the event
	 */
	public $id;

	/**
	 * @since   2.0.0
	 * @var     string $hashed_id Hashed ID of the event
	 */
	public $hashed_id;

	/**
	 * @since   2.0.0
	 * @var     Event_Type $type Type of the event
	 */
	public $type;

	/**
	 * @since   2.0.0
	 * @var     string $title
	 */
	public $title;

	/**
	 * @since   2.0.0
	 * @var     Language $language
	 */
	public $language;

	/**
	 * @since   2.0.0
	 * @var     Schedule $schedule
	 */
	public $schedule;

	/**
	 * @since   2.0.0
	 * @var     Location $location Location of the workshop
	 */
	public $location;

	/**
	 * @since   2.0.0
	 * @var     float $rating
	 */
	public $rating;

	/**
	 * @since   2.0.0
	 * @var     boolean $confirmed
	 */
	public $confirmed;

	/**
	 * @since   2.0.0
	 * @var     boolean $private
	 */
	public $private;

	/**
	 * @since   2.0.0
	 * @var     boolean $free
	 */
	public $free;

	/**
	 * @since   2.0.0
	 * @var     boolean $sold_out
	 */
	public $sold_out;

	/**
	 * @since   2.0.0
	 * @var     string $url
	 */
	public $url;

	/**
	 * @since   2.0.0
	 * @var     Tickets|null $tickets
	 */
	public $tickets;

	/**
	 * @since   2.0.0
	 * @var     Trainer[] $trainers
	 */
	public $trainers;

	/**
	 * @since   2.0.0
	 * @var     string $description
	 */
	public $description;

	/**
	 * @since   2.0.0
	 * @var     Form $registration_form
	 */
	public $registration_form;

	/**
	 * @since   2.0.0
	 * @var     Registration_Page $registration_page
	 */
	public $registration_page;

	/**
	 * @var Event_State $state State of the workshop
	 * @since 2.0.0
	 */
	public $state;

	/**
	 * Creates a new object
	 *
	 * @param $json_data object            JSON data from Workshop Butler API
	 * @param $event_page_url string|null  Event page URL on the integrated website
	 * @param $trainer_page_url string|null  Trainer profile page URL on the integrated website
	 * @param $registration_page_url string|null Registration page URL on the integrated website
	 */
	public function __construct( $json_data, $event_page_url, $trainer_page_url, $registration_page_url ) {
		$this->id          = $json_data->id;
		$this->hashed_id   = $json_data->hashed_id;
		$this->title       = $json_data->title;
		$this->type        = $json_data->type ? new Event_Type( $json_data->type ) : Event_Type::createEmpty();
		$this->language    = new Language( $json_data->spoken_languages, $json_data->materials_language );
		$this->rating      = $json_data->rating;
		$this->confirmed   = $json_data->confirmed;
		$this->free        = $json_data->free;
		$this->private     = $json_data->private;
		$this->description = $json_data->description;
		$this->sold_out    = $json_data->sold_out;
		$this->schedule    = new Schedule( $json_data->schedule );
		$this->location    = new Location( $json_data->location );

		if ( $event_page_url ) {
			$this->url = $event_page_url . '?id=' . $this->hashed_id;
		} else {
			$this->url = 'https://workshopbutler.com/public/event/' . $this->hashed_id;
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
	 * @param $free boolean True if the event is free
	 * @param $free_ticket_type Free_Ticket_Type
	 * @param $paid_ticket_types Paid_Ticket_Type[]
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
				new Tickets( [], new Free_Ticket_Type( $free_ticket_type ) ) :
				new Tickets( $paid_tickets, null );
		} else {
			return null;
		}
	}

	/**
	 * Returns a list of event trainers
	 *
	 * @param $jsonData object              JSON representation of a trainer
	 * @param $trainer_page_url string|null Trainer profile page URL on the integrated website
	 * @return Trainer[]
	 */
	private function get_trainers( $jsonData, $trainer_page_url ) {
		if ( $jsonData->facilitators ) {
			$trainers = [];
			foreach ( $jsonData->facilitators as $trainer ) {
				array_push( $trainers, new Trainer( $trainer, $trainer_page_url ) );
			}
			return $trainers;
		} else {
			return [];
		}
	}

}

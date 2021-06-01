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
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-paid-tickets.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-tickets.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-trainer.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-language.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-schedule.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-location.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-event-state.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-registration-page.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-event-url.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-cover-image.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-payment.php';
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
	 * @var     Free_Ticket_Type|Paid_Tickets|null $tickets
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
	 * True if the event is featured.
	 *
	 * @var boolean $featured
	 * @since 2.11.0
	 */
	public $featured;

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
	 * Cover image for the event
	 *
	 * @var Cover_Image $cover_image
	 * @since 2.6.0
	 */
	public $cover_image;

	/**
	 * The url to the event
	 *
	 * @since   2.0.0
	 * @var     Event_Url $url
	 */
	private $url;

	/**
	 * Card payment configuration
	 *
	 * @since 2.14.0
	 * @var CardPayment|null $card_payment
	 */
	public $card_payment;

	/**
	 * PayPal payment configuration
	 *
	 * @since 2.14.0
	 * @var PayPalPayment|null $paypal_payment
	 */
	public $paypal_payment;

	/**
	 * Creates a new object
	 *
	 * @param object      $json_data JSON data from Workshop Butler API.
	 * @param string|null $event_page_url Event page URL on the integrated website.
	 * @param string|null $trainer_page_url Trainer profile page URL on the integrated website.
	 * @param string|null $registration_page_url Registration page URL on the integrated website.
	 */
	public function __construct( $json_data, $event_page_url, $trainer_page_url, $registration_page_url ) {
		$this->id          = $json_data->id;
		$this->hashed_id   = $json_data->hashed_id;
		$this->title       = $json_data->title;
		$this->type        = $json_data->type ? new Event_Type( $json_data->type ) : Event_Type::create_empty();
		$this->language    = Language::from_json( $json_data->language );
		$this->rating      = isset( $json_data->rating ) ? $json_data->rating : null;
		$this->confirmed   = $json_data->confirmed;
		$this->free        = $json_data->free;
		$this->private     = isset( $json_data->private ) ? $json_data->private : null;
		$this->description = $json_data->description;
		$this->sold_out    = $json_data->sold_out;
		$this->schedule    = new Schedule( $json_data->schedule );
		$this->location    = Location::from_json( $json_data->location );

		if ( isset( $json_data->custom_settings ) && $json_data->custom_settings->title_url ) {
			$this->url = Event_Url::external( $json_data->custom_settings->title_url );
		} elseif ( $event_page_url ) {
			$this->url = Event_Url::internal( $event_page_url . '?id=' . $this->hashed_id );
		} else {
			$this->url = Event_Url::external( 'https://workshopbutler.com/public/event/' . $this->hashed_id );
		}
		$this->tickets = $this->get_tickets_from_json( $this->free, $json_data->tickets );

		$this->registration_form = Form::from_json( $json_data->form, $this );

		$this->registration_page = new Registration_Page(
			$json_data->registration_page,
			$registration_page_url,
			$this->hashed_id
		);
		$this->cover_image       = Cover_Image::from_json( $json_data->cover_image );

		$this->trainers       = $this->get_trainers_from_json( $json_data, $trainer_page_url );
		$this->state          = new Event_State( $this, $json_data->state === 'canceled' );
		$this->card_payment   = CardPayment::from_json( $json_data->card_payment );
		$this->paypal_payment = PayPalPayment::from_json( $json_data->paypal_payment );
		$this->featured       = $json_data->featured ? $json_data->featured : false;
	}

	/**
	 * Returns the URL for the event's page
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function get_url() {
		return $this->url->url;
	}

	/**
	 * Returns the list of trainers running the event
	 *
	 * @return mixed|Trainer[]
	 * @since 3.0.0
	 */
	public function get_trainers() {
		return $this->trainers;
	}

	/**
	 * Returns the cover image object for the event. The object always exists but the urls
	 * could be null
	 *
	 * @return Cover_Image
	 * @since 3.0.0
	 */
	public function get_cover_image() {
		return $this->cover_image;
	}

	/**
	 * Returns tickets' object
	 *
	 * @return mixed|Free_Ticket_Type|Paid_Tickets|null
	 * @since 3.0.0
	 */
	public function get_tickets() {
		return $this->tickets;
	}

	/**
	 * Returns the description of the event
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Returns the state object of the event
	 *
	 * @return Event_State
	 * @since 3.0.0
	 */
	public function get_state() {
		return $this->state;
	}

	/**
	 * Returns the url to the registration page of the event
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function get_registration_url() {
		return $this->registration_page->get_url();
	}

	/**
	 * Returns the language object of the event
	 *
	 * @return Language
	 * @since 3.0.0
	 */
	public function get_language() {
		return $this->language;
	}

	/**
	 * Returns title of the event
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * Returns the type of the event
	 *
	 * @return Event_Type
	 * @since 3.0.0
	 */
	public function get_event_type() {
		return $this->type;
	}

	/**
	 * Returns country's code
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function get_country_code() {
		return $this->location->country_code;
	}

	/**
	 * Returns the list of spoken languages
	 *
	 * @return string[]
	 * @since 3.0.0
	 */
	public function get_spoken_languages() {
		return $this->language->spoken;
	}

	/**
	 * Returns the location
	 *
	 * @return Location
	 * @since 3.0.0
	 */
	public function get_location() {
		return $this->location;
	}

	/**
	 * Returns the schedule
	 *
	 * @return Schedule
	 * @since 3.0.0
	 */
	public function get_schedule() {
		return $this->schedule;
	}

	/**
	 * Returns true if the event is free
	 *
	 * @return bool
	 * @since 3.0.0
	 */
	public function is_free() {
		return $this->free;
	}

	/**
	 * Returns true if the event is featured
	 *
	 * @return bool
	 * @since 3.0.0
	 */
	public function is_featured() {
		return $this->featured;
	}

	/**
	 * Returns true if the URL leads to a third-party website
	 *
	 * @return boolean
	 * @since  2.1.0
	 */
	public function is_url_external() {
		return $this->url->on_third_party_website;
	}

	/**
	 * Returns the list of trainers' names
	 *
	 * This method is used in templates
	 *
	 * @return string[]
	 * @since  2.0.0
	 */
	public function get_names_of_trainers() {
		return array_map(
			function ( $trainer ) {
				return $trainer->get_full_name();
			},
			$this->trainers
		);
	}

	/**
	 * Returns the registration form for the event
	 *
	 * @since 3.0.0
	 * @return Form|null
	 */
	public function get_registration_form() {
		return $this->registration_form;
	}

	/**
	 * Returns true if tickets are available
	 *
	 * @return bool
	 * @since 2.7.0
	 */
	public function with_tickets() {
		if ( $this->tickets instanceof Free_Ticket_Type ) {
			return ! $this->tickets->without_limit();
		}
		if ( $this->tickets instanceof Paid_Tickets ) {
			return ! empty( $this->tickets->types );
		}

		return false;
	}

	/**
	 * Returns Tickets object
	 *
	 * @param boolean $free True if the event is free.
	 * @param object  $tickets Tickets in JSON.
	 *
	 * @return null|Paid_Tickets|Free_Ticket_Type
	 */
	private function get_tickets_from_json( $free, $tickets ) {
		if ( $tickets->free || $tickets->paid ) {
			return $free ?
				Free_Ticket_Type::from_json( $tickets->free ) :
				Paid_Tickets::from_json( $tickets->paid );
		} else {
			return null;
		}
	}

	/**
	 * Returns a list of event trainers
	 *
	 * @param object      $json_data JSON representation of a trainer.
	 * @param string|null $trainer_page_url Trainer profile page URL on the integrated website.
	 *
	 * @return Trainer[]
	 */
	private function get_trainers_from_json( $json_data, $trainer_page_url ) {
		if ( $json_data->trainers ) {
			$trainers = array();
			foreach ( $json_data->trainers as $trainer ) {
				array_push( $trainers, new Trainer( $trainer, $trainer_page_url ) );
			}

			return $trainers;
		} else {
			return array();
		}
	}

}

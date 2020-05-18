<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 * @subpackage WSB_Integration/public
 */

namespace WorkshopButler;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    WorkshopButler
 * @subpackage WSB_Integration/public
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Integration_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->load_dependencies();
	}

	/**
	 * Load the required dependencies for this class.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes/class-wsb-trainer-list-page.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes/class-wsb-trainer-page.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes/class-wsb-schedule-page.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes/class-wsb-old-schedule-page.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes/class-wsb-event-page.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes/class-wsb-registration-page.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes/class-wsb-testimonial.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes/class-wsb-trainer.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes/class-wsb-event.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsb-ajax.php';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {
		wp_register_style( 'wsb-fontawesome-styles', plugin_dir_url( __FILE__ ) . 'css/fontawesome-all.min.css', array(), $this->version );
		wp_register_style( 'wsb-themes', plugin_dir_url( __FILE__ ) . 'css/styles.1.9.4.min.css', array(), $this->version );
		wp_register_style( 'wsb-wordpress-themes', plugin_dir_url( __FILE__ ) . 'css/wsb.wordpress.css', array(), $this->version );

		wp_register_style( 'wsb-font-arapey', 'https://fonts.googleapis.com/css?family=Arapey' );
		wp_register_style( 'wsb-font-montserrat', 'https://fonts.googleapis.com/css?family=Montserrat' );
		wp_register_style( 'wsb-font-droid-sans', 'https://fonts.googleapis.com/css?family=Droid+Sans' );
		wp_register_style( 'wsb-font-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans' );
		wp_register_style( 'wsb-font-raleway', 'https://fonts.googleapis.com/css?family=Raleway' );

		wp_enqueue_style( 'wsb-themes' );
		wp_enqueue_style( 'wsb-wordpress-themes' );
		wp_enqueue_style( 'wsb-fontawesome-styles' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script( 'wsb-all-trainers-scripts', plugin_dir_url( __FILE__ ) . 'js/all-trainers-scripts.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wsb-event-page', plugin_dir_url( __FILE__ ) . 'js/event-page.js', array( 'jquery', 'wsb-dateformat' ), $this->version, true );
		wp_register_script( 'wsb-registration-page', plugin_dir_url( __FILE__ ) . 'js/registration-page.js', array( 'jquery', 'wsb-dateformat' ), $this->version, true );

		wp_register_script( 'wsb-single-trainer-scripts', plugin_dir_url( __FILE__ ) . 'js/single-trainer-scripts.js', array( 'jquery', 'wsb-dateformat' ), $this->version, true );

		wp_register_script( 'wsb-dateformat', plugin_dir_url( __FILE__ ) . 'js/jquery-dateFormat.min.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wsb-all-events-scripts', plugin_dir_url( __FILE__ ) . 'js/all-events-scripts.js', array( 'jquery' ), $this->version, true );

		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', false, null );
		wp_enqueue_script( 'jquery' );

		wp_register_script( 'stripe', 'https://js.stripe.com/v3/' );

		$ga_key = WSB_Options::get_option( WSB_Options::GA_API_KEY );
		wp_localize_script(
			'wsb-registration-page',
			'wsb_ga',
			array( 'google_analytics_key' => $ga_key )
		);
	}

	/**
	 * Updates the title of the page
	 *
	 * @param string $title Current page title.
	 * @param int    $id    ID of the page.
	 * @since 2.0.0
	 * @return string
	 */
	public function set_title( $title, $id ) {
		$options      = new WSB_Options();
		$reserved_ids = array(
			intval( $options->get( WSB_Options::EVENT_PAGE ) ),
			intval( $options->get( WSB_Options::REGISTRATION_PAGE ) ),
			intval( $options->get( WSB_Options::TRAINER_PROFILE_PAGE ) ),
		);

		if ( in_array( $id, $reserved_ids, true ) ) {
			return $this->get_title( $title );
		} else {
			return $title;
		}
	}

	/**
	 * Updates the document title
	 *
	 * @param string $title Current title.
	 * @return string
	 */
	public function set_document_title( $title ) {
		return $this->get_title( $title );
	}

	/**
	 * Returns the title of the page based on its url
	 *
	 * @param string $title Current page title.
	 * @since 2.0.0
	 * @return string
	 */
	protected function get_title( $title ) {
		global $post;
		require_once plugin_dir_path( __FILE__ ) . '../includes/class-wsb-options.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsb-dictionary.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsb-requests.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/models/class-event.php';

		$options = new WSB_Options();

		$post_url = get_permalink( $post );
		if ( $post_url === $options->get_event_page_url() || $post_url === $options->get_registration_page_url() ) {
			return $this->get_event_title( $title );
		} elseif ( $post_url === $options->get_trainer_page_url() ) {
			return $this->get_trainer_name( $title );
		} else {
			return $title;
		}
	}

	/**
	 * Retrieves a trainer and returns its full name
	 *
	 * @param string $default_title Default page title.
	 *
	 * @return string
	 */
	protected function get_trainer_name( $default_title ) {
		$dict           = new WSB_Dictionary();
		$may_be_trainer = $dict->get_trainer();
		if ( is_a( $may_be_trainer, 'WorkshopButler\Trainer' ) ) {
			return $may_be_trainer->full_name();
		} elseif ( is_wp_error( $may_be_trainer ) ) {
			return $default_title;
		} elseif ( empty( $_GET['id'] ) ) {
			return $default_title;
		} else {
			$requests = new WSB_Requests();
			$response = $requests->retrieve_trainer( $_GET['id'] );
			if ( is_wp_error( $response ) ) {
				return $default_title;
			} else {
				return $response->full_name();
			}
		}
	}

	/**
	 * Retrieves an event and returns its title
	 *
	 * @param string $default_title Default page title.
	 *
	 * @return string
	 */
	protected function get_event_title( $default_title ) {
		$dict           = new WSB_Dictionary();
		$may_be_trainer = $dict->get_event();
		if ( is_a( $may_be_trainer, 'WorkshopButler\Event' ) ) {
			return $may_be_trainer->title;
		} elseif ( is_wp_error( $may_be_trainer ) ) {
			return $default_title;
		} elseif ( empty( $_GET['id'] ) ) {
			return $default_title;
		} else {
			$requests = new WSB_Requests();
			$response = $requests->retrieve_event( $_GET['id'] );
			if ( is_wp_error( $response ) ) {
				return $default_title;
			} else {
				return $response->title;
			}
		}
	}

	/**
	 * Adds Workshop Butler shortcodes and initialises custom query parameters
	 */
	public function init() {

		// Old version support.
		add_shortcode( 'wb_content', array( 'WorkshopButler\WSB_Old_Schedule_Page', 'page' ) );

		// Pages.
		add_shortcode( 'wsb_schedule', array( 'WorkshopButler\WSB_Schedule_Page', 'page' ) );
		add_shortcode( 'wsb_event', array( 'WorkshopButler\WSB_Event_Page', 'page' ) );
		add_shortcode( 'wsb_registration', array( 'WorkshopButler\WSB_Registration_Page', 'page' ) );

		add_shortcode( 'wsb_trainer_list', array( 'WorkshopButler\WSB_Trainer_List_Page', 'page' ) );
		add_shortcode( 'wsb_trainer', array( 'WorkshopButler\WSB_Trainer_Page', 'page' ) );

		// Elements.
		add_shortcode( 'wsb_schedule_date', array( 'WorkshopButler\WSB_Schedule_Page', 'tag' ) );
		add_shortcode( 'wsb_schedule_time', array( 'WorkshopButler\WSB_Schedule_Page', 'tag' ) );
		add_shortcode( 'wsb_schedule_filters', array( 'WorkshopButler\WSB_Schedule_Page', 'tag' ) );
		add_shortcode( 'wsb_schedule_item', array( 'WorkshopButler\WSB_Schedule_Page', 'tag' ) );
		add_shortcode( 'wsb_schedule_register', array( 'WorkshopButler\WSB_Schedule_Page', 'tag' ) );
		add_shortcode( 'wsb_schedule_title', array( 'WorkshopButler\WSB_Schedule_Page', 'tag' ) );
		add_shortcode( 'wsb_schedule_image', array( 'WorkshopButler\WSB_Schedule_Page', 'tag' ) );
		add_shortcode( 'wsb_schedule_trainers', array( 'WorkshopButler\WSB_Schedule_Page', 'tag' ) );
		add_shortcode( 'wsb_schedule_schedule', array( 'WorkshopButler\WSB_Schedule_Page', 'tag' ) );
		add_shortcode( 'wsb_schedule_location', array( 'WorkshopButler\WSB_Schedule_Page', 'tag' ) );
		add_shortcode( 'wsb_schedule_language', array( 'WorkshopButler\WSB_Schedule_Page', 'tag' ) );

		add_shortcode( 'wsb_trainer_list_filters', array( 'WorkshopButler\WSB_Trainer_List_Page', 'tag' ) );
		add_shortcode( 'wsb_trainer_list_item', array( 'WorkshopButler\WSB_Trainer_List_Page', 'trainer' ) );
		add_shortcode( 'wsb_trainer_list_photo', array( 'WorkshopButler\WSB_Trainer_List_Page', 'tag' ) );
		add_shortcode( 'wsb_trainer_list_name', array( 'WorkshopButler\WSB_Trainer_List_Page', 'tag' ) );
		add_shortcode( 'wsb_trainer_list_country', array( 'WorkshopButler\WSB_Trainer_List_Page', 'tag' ) );
		add_shortcode( 'wsb_trainer_list_badges', array( 'WorkshopButler\WSB_Trainer_List_Page', 'tag' ) );
		add_shortcode( 'wsb_trainer_list_rating', array( 'WorkshopButler\WSB_Trainer_List_Page', 'tag' ) );

		add_shortcode( 'wsb_event_title', array( 'WorkshopButler\WSB_Event', 'tag' ) );
		add_shortcode( 'wsb_event_registration_button', array( 'WorkshopButler\WSB_Event', 'tag' ) );
		add_shortcode( 'wsb_event_schedule', array( 'WorkshopButler\WSB_Event', 'tag' ) );
		add_shortcode( 'wsb_event_image', array( 'WorkshopButler\WSB_Event', 'tag' ) );
		add_shortcode( 'wsb_event_location', array( 'WorkshopButler\WSB_Event', 'tag' ) );
		add_shortcode( 'wsb_event_social_links', array( 'WorkshopButler\WSB_Event', 'tag' ) );
		add_shortcode( 'wsb_event_events', array( 'WorkshopButler\WSB_Event', 'tag' ) );
		add_shortcode( 'wsb_event_description', array( 'WorkshopButler\WSB_Event', 'tag' ) );
		add_shortcode( 'wsb_event_trainers', array( 'WorkshopButler\WSB_Event', 'tag' ) );
		add_shortcode( 'wsb_event_tickets', array( 'WorkshopButler\WSB_Event', 'tag' ) );

		add_shortcode( 'wsb_registration_form', array( 'WorkshopButler\WSB_Registration_Page', 'tag' ) );

		add_shortcode( 'wsb_trainer_name', array( 'WorkshopButler\WSB_Trainer_Page', 'tag' ) );
		add_shortcode( 'wsb_trainer_country', array( 'WorkshopButler\WSB_Trainer_Page', 'tag' ) );
		add_shortcode( 'wsb_trainer_photo', array( 'WorkshopButler\WSB_Trainer_Page', 'tag' ) );

		add_shortcode( 'wsb_trainer_stats', array( 'WorkshopButler\WSB_Trainer', 'statistics' ) );
		add_shortcode( 'wsb_trainer_social_link', array( 'WorkshopButler\WSB_Trainer', 'social_link' ) );
		add_shortcode( 'wsb_trainer_email', array( 'WorkshopButler\WSB_Trainer_Page', 'tag' ) );

		add_shortcode( 'wsb_trainer_events', array( 'WorkshopButler\WSB_Trainer', 'events' ) );
		add_shortcode( 'wsb_trainer_badges', array( 'WorkshopButler\WSB_Trainer', 'tag' ) );
		add_shortcode( 'wsb_trainer_bio', array( 'WorkshopButler\WSB_Trainer_Page', 'tag' ) );

		add_shortcode( 'wsb_trainer_testimonials', array( 'WorkshopButler\WSB_Testimonial', 'testimonials' ) );
		add_shortcode( 'wsb_testimonial', array( 'WorkshopButler\WSB_Testimonial', 'testimonial' ) );
		add_shortcode( 'wsb_testimonial_author', array( 'WorkshopButler\WSB_Testimonial', 'tag' ) );
		add_shortcode( 'wsb_testimonial_rating', array( 'WorkshopButler\WSB_Testimonial', 'tag' ) );
		add_shortcode( 'wsb_testimonial_content', array( 'WorkshopButler\WSB_Testimonial', 'tag' ) );

		// Adds support for custom query parameter.
		global $wp;
		$wp->add_query_var( 'id' );
	}

	/**
	 * Registers AJAX handlers
	 */
	public function add_ajax_handlers() {
		add_action( 'wp_ajax_nopriv_wsb_get_values', array( 'WorkshopButler\WSB_Ajax', 'get_values' ) );
		add_action( 'wp_ajax_wsb_get_values', array( 'WorkshopButler\WSB_Ajax', 'get_values' ) );

		add_action( 'wp_ajax_nopriv_wsb_register', array( 'WorkshopButler\WSB_Ajax', 'register' ) );
		add_action( 'wp_ajax_wsb_register', array( 'WorkshopButler\WSB_Ajax', 'register' ) );

		add_action( 'wp_ajax_nopriv_wsb_pre_register', array( 'WorkshopButler\WSB_Ajax', 'pre_register' ) );
		add_action( 'wp_ajax_wsb_pre_register', array( 'WorkshopButler\WSB_Ajax', 'pre_register' ) );
	}
}

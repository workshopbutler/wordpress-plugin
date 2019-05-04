<?php
/**
 * The file that defines the event registration page class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-page.php';

/**
 * Event Page class which handles the rendering and logic for the event page
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Registration_Page extends WSB_Page {

	/**
	 * Request entity
	 *
	 * @var WSB_Requests
	 */
	private $requests;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 */
	public function __construct() {
		parent::__construct();
		$this->load_dependencies();
		$this->requests = new WSB_Requests();
	}

	/**
	 * Load the required dependencies for this class.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/../../includes/class-wsb-options.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-requests.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-event.php';
	}

	/**
	 * Renders the registration page
	 *
	 * @param array  $attrs   Shortcode attributes.
	 * @param string $content Shortcode content.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	public function render( $attrs = array(), $content = null ) {
		if ( empty( $_GET['id'] ) ) {
			return $this->format_error( 'empty event ID' );
		}
		$may_be_event = $this->dict->get_event();
		if ( is_null( $may_be_event ) ) {
			$may_be_event = $this->requests->retrieve_event( $_GET['id'] );
		}
		if ( is_wp_error( $may_be_event ) ) {
			return $this->format_error( $may_be_event->get_error_message() );
		}
		wp_enqueue_script( 'wsb-registration-page' );
		$this->add_theme_fonts();
		$this->add_localized_script( $may_be_event );

		return $this->render_page( $may_be_event );
	}

	/**
	 * Adds a localized version of JS script on the page
	 *
	 * @param Event $event Event.
	 */
	protected function add_localized_script( $event ) {
		$wsb_nonce = wp_create_nonce( 'wsb-nonce' );
		wp_localize_script(
			'wsb-registration-page',
			'wsb_event',
			array(
				'ajax_url'                 => admin_url( 'admin-ajax.php' ),
				'nonce'                    => $wsb_nonce,
				'is_registration_closed'   => $event->state->closed(),
				'id'                       => $event->id,
				'error_required'           => __( 'form.error.required', 'wsbintegration' ),
				'error_email'              => __( 'form.error.email', 'wsbintegration' ),
				'error_url'                => __( 'form.error.url', 'wsbintegration' ),
				'error_date'               => __( 'form.error.date', 'wsbintegration' ),
				'error_nospace'            => __( 'form.error.number', 'wsbintegration' ),
				'error_digits'             => __( 'form.error.digits', 'wsbintegration' ),
				'string_validation_errors' => __( 'Validation errors occurred. Please confirm the fields and try again.', 'wsbintegration' ),
				'string_error_try_again'   => __( 'The server doesn\'t response. Please try again. If the error persists please contact your trainer.', 'wsbintegration' ),
				'string_try_again'         => __( 'Please try again. If the error persists please contact your trainer.', 'wsbintegration' ),
			)
		);
	}

	/**
	 * Renders the event page
	 *
	 * @param Event $event Event.
	 *
	 * @return string
	 */
	private function render_page( $event ) {
		$custom_template = $this->settings->get( WSB_Options::REGISTRATION_TEMPLATE );
		$template        = $this->get_template( 'registration-page', $custom_template );

		$template_data = array(
			'event' => $event,
			'theme' => $this->get_theme(),
		);

		$processed_template = do_shortcode( $template );
		$content            = $this->compile_string( $processed_template, $template_data );

		return $this->add_custom_styles( $content );
	}

	/**
	 * Returns a sorted list of translated countries
	 *
	 * @return array
	 */
	private function get_countries() {
		$codes     = array(
			'AF',
			'AL',
			'DZ',
			'AS',
			'AD',
			'AO',
			'AI',
			'AQ',
			'AG',
			'AR',
			'AM',
			'AW',
			'AU',
			'AT',
			'AZ',
			'AX',
			'BS',
			'BH',
			'BD',
			'BB',
			'BY',
			'BZ',
			'BE',
			'BJ',
			'BM',
			'BT',
			'BA',
			'BW',
			'BN',
			'BO',
			'BQ',
			'BV',
			'BR',
			'BG',
			'BF',
			'BI',
			'CV',
			'CM',
			'CA',
			'CF',
			'TD',
			'CL',
			'CN',
			'CX',
			'CC',
			'CD',
			'CG',
			'CK',
			'CI',
			'CO',
			'CR',
			'HR',
			'CU',
			'CW',
			'CY',
			'CZ',
			'DK',
			'DJ',
			'DM',
			'DO',
			'EC',
			'EG',
			'SV',
			'ER',
			'GQ',
			'EE',
			'ET',
			'FK',
			'FO',
			'FJ',
			'FI',
			'FR',
			'GF',
			'PF',
			'GA',
			'GM',
			'KH',
			'KY',
			'GE',
			'DE',
			'GH',
			'GI',
			'KM',
			'GR',
			'GL',
			'GD',
			'GP',
			'GG',
			'GN',
			'GU',
			'GT',
			'GW',
			'GY',
			'HT',
			'HK',
			'HN',
			'HU',
			'IS',
			'IN',
			'ID',
			'IR',
			'IQ',
			'IE',
			'IL',
			'IM',
			'IT',
			'JM',
			'JP',
			'JE',
			'JO',
			'KZ',
			'KE',
			'KI',
			'KG',
			'KP',
			'KR',
			'KW',
			'LA',
			'LV',
			'LB',
			'LS',
			'LR',
			'LY',
			'LI',
			'LT',
			'LU',
			'MO',
			'MK',
			'MG',
			'MW',
			'MY',
			'MV',
			'ML',
			'MT',
			'MH',
			'MQ',
			'MR',
			'MU',
			'YT',
			'MX',
			'FM',
			'MD',
			'MC',
			'MN',
			'ME',
			'MS',
			'MA',
			'MZ',
			'MM',
			'NA',
			'NR',
			'NP',
			'NL',
			'NC',
			'NZ',
			'NI',
			'NE',
			'NG',
			'NU',
			'NF',
			'MP',
			'NO',
			'OM',
			'PK',
			'PW',
			'PS',
			'PA',
			'PG',
			'PY',
			'PE',
			'PH',
			'PN',
			'PL',
			'PT',
			'PR',
			'QA',
			'RE',
			'RO',
			'RU',
			'RW',
			'BL',
			'SH',
			'KN',
			'LC',
			'MF',
			'PM',
			'VC',
			'WS',
			'SM',
			'ST',
			'SA',
			'SN',
			'RS',
			'SC',
			'SL',
			'SG',
			'SX',
			'SK',
			'SI',
			'SB',
			'SO',
			'ZA',
			'SS',
			'ES',
			'LK',
			'SD',
			'SR',
			'SJ',
			'SZ',
			'SE',
			'CH',
			'SY',
			'TJ',
			'TW',
			'TZ',
			'TH',
			'TL',
			'TG',
			'TK',
			'TO',
			'TT',
			'TN',
			'TR',
			'TM',
			'TC',
			'TV',
			'UG',
			'UA',
			'AE',
			'GB',
			'US',
			'UY',
			'UZ',
			'VU',
			'VE',
			'VN',
			'VG',
			'VI',
			'WF',
			'EH',
			'YE',
			'ZM',
			'ZW',
		);
		$countries = array();
		foreach ( $codes as $code ) {
			$countries[ $code ] = __( 'country.' . $code, 'wsbintegration' );
		}
		asort( $countries );
		return $countries;
	}


	/**
	 * Renders a simple shortcode with no additional logic
	 *
	 * @param string      $name    Name of the shortcode (like 'title', 'register').
	 * @param array       $attrs   Attributes.
	 * @param null|string $content Replaceable content.
	 *
	 * @return string
	 * @since 2.0.0
	 */
	protected function render_simple_shortcode( $name, $attrs = array(), $content = null ) {
		$event = $this->dict->get_event();
		if ( ! is_a( $event, 'WorkshopButler\Event' ) ) {
			return '';
		}
		$template = $this->get_template( 'registration/' . $name, null );
		if ( ! $template ) {
			return '[wsb_registration_' . $name . ']';
		}
		$attrs['event']     = $event;
		$attrs['countries'] = $this->get_countries();
		return $this->compile_string( $template, $attrs );
	}

	/**
	 * Renders the registration page
	 *
	 * @param array  $attrs   Shortcode attributes.
	 * @param string $content Shortcode content.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	public static function page( $attrs = array(), $content = null ) {
		$page = new WSB_Registration_Page();

		return $page->render( $attrs, $content );
	}
}

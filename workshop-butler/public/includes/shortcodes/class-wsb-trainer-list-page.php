<?php
/**
 * The file that defines the trainer list class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-page.php';

/**
 * Trainer List page class which handles the rendering and logic for the list of trainers
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Trainer_List_Page extends WSB_Page {

	/**
	 * Request entity
	 *
	 * @var WSB_Requests
	 */
	private $requests;

	/**
	 * WSB_Trainer_List_Page constructor
	 *
	 * @since 2.0.0
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'ui/class-trainer-filters.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-trainer.php';
	}

	/**
	 * Renders the list of trainers
	 *
	 * @param array  $attrs   Shortcode attributes.
	 * @param string $content Shortcode content.
	 *
	 * @since  2.0.0
	 *
	 * @return string
	 */
	public function render_page( $attrs = [], $content = null ) {
		// Load styles and scripts only on demand.
		wp_enqueue_script( 'wsb-all-trainers-scripts' );
		$this->add_theme_fonts();

		$method = 'facilitators';
		$query  = array();

		$response = $this->requests->get( $method, $query );
		return $this->render_list( $response, $this->settings->get_trainer_page_url() );
	}

	/**
	 * Renders the list of trainers
	 *
	 * @param WSB_Response $response    Workshop Butler API response.
	 * @param string       $trainer_url Trainer profile page URL.
	 *
	 * @return string
	 */
	protected function render_list( $response, $trainer_url ) {
		if ( $response->is_error() ) {
			return $this->format_error( $response->error );
		}

		$trainers = [];
		foreach ( $response->body as $json_trainer_data ) {
			$trainer = new Trainer( $json_trainer_data, $trainer_url );
			array_push( $trainers, $trainer );
		}
		$template_data = array(
			'trainers' => $trainers,
			'theme'    => $this->get_theme(),
		);

		$custom_template = $this->settings->get( WSB_Options::TRAINER_LIST_TEMPLATE );
		$template        = $this->get_template( 'trainer-list-page', $custom_template );

		$GLOBALS['wsb_trainers'] = $trainers;
		$processed_template      = do_shortcode( $template );
		$content                 = $this->compile_string( $processed_template, $template_data );
		unset( $GLOBALS['wsb_trainers'] );

		return $this->add_custom_styles( $content );
	}

	/**
	 * Renders filters on the page
	 *
	 * @param array $attrs Short code attributes.
	 *
	 * @since  2.0.0
	 * @return string
	 */
	protected function render_filters( $attrs = [] ) {
		$attrs = shortcode_atts( $this->get_default_attrs( 'filter' ), $attrs );

		$trainers = $this->dict->get_trainers();
		if ( is_null( $trainers ) ) {
			return '';
		}
		$template = $this->get_template( 'filters', null );
		if ( is_null( $template ) ) {
			return '';
		}
		$available_filters = array_map(
			function ( $name ) {
				return trim( $name );
			},
			explode( ',', $attrs['filters'] )
		);

		$trainer_filters = new Trainer_Filters( $trainers, $available_filters );
		return $this->compile_string( $template, array( 'filters' => $trainer_filters->get_filters() ) );
	}

	/**
	 * Retrieves the name of Workshop Butler shortcode
	 *
	 * @param string $tag Full shortcode tag.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	protected static function get_shortcode_name( $tag ) {
		$parts     = explode( '_', $tag );
		$empty_tag = '[' . $tag . ']';
		if ( count( $parts ) < 4 ) {
			return $empty_tag;
		}

		return implode( '_', array_slice( $parts, 3 ) );
	}

	/**
	 * Renders the list of trainers
	 *
	 * @param array       $attrs   Short code attributes.
	 * @param null|string $content Short code content.
	 *
	 * @since  2.0.0
	 * @return string
	 */
	protected function render_trainer( $attrs = [], $content = null ) {
		$trainers = $this->dict->get_trainers();
		if ( is_null( $trainers ) ) {
			return '';
		}
		$item_template = $this->get_template( 'trainer-list-item', null );
		if ( ! $item_template ) {
			return '';
		}

		$html = '';
		foreach ( $trainers as $trainer ) {
			$this->dict->set_trainer( $trainer );
			$item_content           = $this->compile_string( $content, array( 'trainer' => $trainer ) );
			$processed_item_content = do_shortcode( $item_content );
			$html                  .= $this->compile_string(
				$item_template,
				array(
					'trainer' => $trainer,
					'content' => $processed_item_content,
				)
			);
			$this->dict->clear_trainer();
		}

		$list_template = $this->get_template( 'trainer-list', null );
		if ( ! $list_template ) {
			return '';
		}

		return $this->compile_string( $list_template, array( 'content' => $html ) );
	}

	/**
	 * Returns default attributes for the shortcodes
	 *
	 * @param string $shortcode_name Name of the shortcode (only the meaningful part).
	 *
	 * @return array
	 */
	protected function get_default_attrs( $shortcode_name ) {
		switch ( $shortcode_name ) {
			case 'name':
				return array( 'with_country' => true );
			case 'filter':
				return array( 'filters' => 'location,trainer,language,rating,badge' );
			default:
				return array();
		}
	}

	/**
	 * Renders a simple shortcode with no additional logic
	 *
	 * @param string      $name    Name of the shortcode (like 'title', 'register').
	 * @param array       $attrs   Attributes.
	 * @param null|string $content Replaceable content.
	 *
	 * @return bool|string
	 */
	protected function render_simple_shortcode( $name, $attrs = [], $content = null ) {
		$trainer = $this->dict->get_trainer();
		if ( ! is_a( $trainer, 'WorkshopButler\Trainer' ) ) {
			return '';
		}
		$template = $this->get_template( 'trainer-list/' . $name, null );
		if ( ! $template ) {
			return '[wsb_trainer_list_' . $name . ']';
		}
		$attrs['trainer'] = $trainer;
		return $this->compile_string( $template, $attrs );
	}

	/**
	 * Handles 'wsb_trainer_list' shortcode
	 *
	 * @param array  $attrs   Shortcode attributes.
	 * @param string $content Shortcode content.
	 * @since  2.0.0
	 * @return string
	 */
	public static function page( $attrs = [], $content = null ) {
		$page = new WSB_Trainer_List_Page();
		return $page->render_page( $attrs, $content );
	}

	/**
	 * Handles 'wsb_trainer_list_item' shortcode
	 *
	 * @param array  $attrs   Shortcode attributes.
	 * @param string $content Shortcode content.
	 * @since  2.0.0
	 * @return string
	 */
	public static function trainer( $attrs = [], $content = null ) {
		$page = new WSB_Trainer_List_Page();
		return $page->render_trainer( $attrs, $content );
	}

}

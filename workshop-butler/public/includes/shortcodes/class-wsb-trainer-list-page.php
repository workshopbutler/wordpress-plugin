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
require_once WSB_ABSPATH . 'public/includes/config/class-trainer-list-config.php';

use WorkshopButler\Config\Trainer_List_Config;

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
	 * Number of words between '_' in prefix (usually 2)
	 *
	 * @var int
	 * @since 2.12.0
	 */
	static protected $prefix_size = 3;

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
	 * @param array  $attrs Shortcode attributes.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	public function render_page( $attrs = array() ) {
		// Load styles and scripts only on demand.
		wp_enqueue_script( 'wsb-all-trainers-scripts' );
		$this->add_theme_fonts();

		$response = $this->requests->get(
			'facilitators',
			array( 'per_page' => '-1' )
		);

		return $this->render_list( $response, $attrs, $this->settings->get_trainer_page_url() );
	}

	/**
	 * Renders the list of trainers
	 *
	 * @param WSB_Response $response Workshop Butler API response.
	 * @param array        $attrs Shortcode attributes.
	 * @param string       $trainer_url Trainer profile page URL.
	 *
	 * @return string
	 */
	protected function render_list( $response, $attrs, $trainer_url ) {
		if ( $response->is_error() ) {
			return $this->format_error( $response->error );
		}

		$this->dict->set_trainer_list_config( new Trainer_List_Config( $attrs ) );

		$trainers = array();
		foreach ( $response->body->data as $json_trainer_data ) {
			$trainer = new Trainer( $json_trainer_data, $trainer_url );
			array_push( $trainers, $trainer );
		}
		$attrs = $this->get_attrs( $attrs );
		if ( ! is_null( $attrs['badges'] ) ) {
			$badge_ids = explode( ',', $attrs['badges'] );
			$trainers  = $this->filter_by_badges( $trainers, $badge_ids );
		}

		$this->dict->set_trainers( $trainers );
		if ( $this->settings->use_old_templates() ) {
			$content = $this->render_old_template( $trainers );
		} else {
			$content = $this->render_new_template();
		}
		$this->dict->clear_trainers();

		return $this->add_custom_styles( $content );
	}

	/**
	 * Render the list of trainers using new templates
	 *
	 * @return false|string
	 * @since 3.0.0
	 */
	protected function render_new_template() {
		ob_start();
		wsb_get_template( 'trainer-list.php', array(
			'theme' => $this->get_theme(),
		));
		return ob_get_clean();
	}

	/**
	 * Renders the old list of trainers
	 *
	 * @param  Trainer[] $trainers List of trainers.
	 *
	 * @return string
	 * @since 3.0.0
	 */
	private function render_old_template( $trainers ): string {
		$template_data = array(
			'trainers' => $trainers,
			'theme'    => $this->get_theme(),
		);

		$custom_template = $this->settings->get( WSB_Options::TRAINER_LIST_TEMPLATE );
		$template        = $this->get_template( 'trainer-list-page', $custom_template );

		$processed_template      = do_shortcode( $template );
		return $this->compile_string( $processed_template, $template_data );
	}


	/**
	 * Returns list of trainers who contains at least one of given badges.
	 *
	 * @param Trainer[] $trainers List of trainers to filter.
	 * @param int[]     $badge_ids List of badges which trainer should posses.
	 *
	 * @return Trainer[]
	 * @since 2.13.0
	 */
	private function filter_by_badges( $trainers, $badge_ids ) {
		$filtered_trainers = array();
		foreach ( $trainers as $trainer ) {
			$ids = array();
			foreach ( $trainer->badges as $badge ) {
				array_push( $ids, $badge->id );
			}
			if ( count( array_intersect( $badge_ids, $ids ) ) > 0 ) {
				array_push( $filtered_trainers, $trainer );
			}
		}

		return $filtered_trainers;
	}

	/**
	 * Returns widget's attributes
	 *
	 * @param array $attrs User attributes.
	 *
	 * @return array
	 * @since 2.13.0
	 */
	private function get_attrs( $attrs ) {

		$defaults = array(
			'badges' => null,
		);

		return shortcode_atts( $defaults, $attrs );
	}

	/**
	 * Renders filters on the page
	 *
	 * @param array $attrs Short code attributes.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	protected function render_filters( $attrs = array() ) {
		$attrs = shortcode_atts( $this->get_default_attrs( 'filters' ), $attrs );

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
	 * Renders the list of trainers
	 *
	 * @param array       $attrs Short code attributes.
	 * @param null|string $content Short code content.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	protected function render_trainer( $attrs = array(), $content = null ) {
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
			$html                   .= $this->compile_string(
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
			case 'filters':
				return array( 'filters' => 'location,trainer,language,rating,badge' );
			default:
				return array();
		}
	}

	/**
	 * Renders a simple shortcode with no additional logic
	 *
	 * @param string      $name Name of the shortcode (like 'title', 'register').
	 * @param array       $attrs Attributes.
	 * @param null|string $content Replaceable content.
	 *
	 * @return bool|string
	 */
	protected function render_simple_shortcode( $name, $attrs = array(), $content = null ) {
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
	 * @param array  $attrs Shortcode attributes.
	 * @param string $content Shortcode content.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	public static function page( $attrs = array(), $content = null ) {
		$page = new WSB_Trainer_List_Page();

		return $page->render_page( $attrs, $content );
	}

	/**
	 * Handles 'wsb_trainer_list_item' shortcode
	 *
	 * @param array  $attrs Shortcode attributes.
	 * @param string $content Shortcode content.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	public static function trainer( $attrs = array(), $content = null ) {
		$page = new WSB_Trainer_List_Page();

		return $page->render_trainer( $attrs, $content );
	}

}

<?php
/**
 * Configuration for Schedule shortcode
 *
 * @since 3.0.0
 * @package WorkshopButler
 */

namespace WorkshopButler\Config;

use WorkshopButler\WSB_Options;

require_once WSB_ABSPATH . '/public/includes/config/class-calendar-item-elements.php';

/**
 * Event Calendar Config class
 */
class Event_Calendar_Config {

	/**
	 * List of categories for events to filter
	 *
	 * @var null|int[]
	 * @since 3.0.0
	 */
	protected $category_ids = null;

	/**
	 * List of event types for events to filter
	 *
	 * @var null|int[]
	 * @since 3.0.0
	 */
	protected $event_type_ids = null;

	/**
	 * Schedule layout ('table' or 'tiles')
	 *
	 * @var string
	 * @since 3.0.0
	 */
	protected $layout = 'table';

	/**
	 * Show only featured events in the schedule
	 *
	 * @var bool
	 * @since 3.0.0
	 */
	protected $only_featured = false;

	/**
	 * Show featured events on top of the schedule
	 *
	 * @var bool
	 * @since 3.0.0
	 */
	protected $featured_on_top = false;

	/**
	 * Highlight featured events
	 *
	 * @var bool
	 * @since 3.0.0
	 */
	protected $highlight_featured = false;

	/**
	 * List of filters for the schedule
	 *
	 * @var array
	 * @since 3.0.0
	 */
	protected $filters = array();

	/**
	 * Type of tags visible on the schedule
	 *
	 * @var string
	 * @since 3.0.0
	 */
	protected $tag_type = 'none';

	/**
	 * True if the user should skip event page and go to the registration page on 'Register' button.
	 *
	 * @since 3.0.0
	 * @var bool
	 */
	protected $skip_event_page = false;

	/**
	 * True if the name of trainer should be shown along with their photo
	 *
	 * @since 3.0.0
	 * @var bool
	 */
	protected $show_trainer_name = true;

	/**
	 * Defines when to show timezone for 'time' block on the item.
	 * Possible values: 'all' - for all events, 'online' - for online events.
	 *
	 * @since 3.0.0
	 * @var string
	 */
	protected $timezone = 'online';

	/**
	 * Format of the timezone for 'time' block on the item.
	 * Possible values: 'long' - Europe/Moscow, 'short' - MSK.
	 *
	 * @since 3.0.0
	 * @var string
	 */
	protected $timezone_format = 'short';

	/**
	 * List of item's elements in the correct order
	 *
	 * @see Calendar_Item_Elements
	 * @since 3.0.0
	 * @var string[]
	 */
	protected $elements = array();

	/**
	 * Constructor.
	 *
	 * @param array $attrs Shortcode's attributes.
	 */
	public function __construct( $attrs = array() ) {
		$defaults = array(
			'category'          => null, // DEPRECATED.
			'categories'        => null,
			'event_type'        => null, // DEPRECATED.
			'event_types'       => null,
			'layout'            => WSB()->settings->get( WSB_Options::SCHEDULE_LAYOUT, 'table' ),
			'wrapper'           => false,
			'only_featured'     => false,
			'featured_on_top'   => WSB()->settings->is_highlight_featured() && WSB()->settings->get( WSB_Options::FEATURED_ON_TOP, false ),
			'highlight_featured'=> WSB()->settings->is_highlight_featured(),
			'filters'           => 'location,language,trainer,type',
			'tag_type'          => WSB()->settings->is_highlight_featured() ? 'all' : 'free',
			'skip_event_page'   => false,
			'show_trainer_name' => true,
			'timezone'          => 'online',
			'timezone_format'   => 'short',
			'elements'          =>	Calendar_Item_Elements::get_defaults_as_string(),
		);
		$this->init( shortcode_atts( $defaults, $attrs ) );
	}

	/**
	 * Returns the type of tag to show on the schedule
	 *
	 * @return string
	 */
	public function get_tag_type() {
		return $this->tag_type;
	}

	/**
	 * Returns the list of categories for events to filter
	 *
	 * @return int[]|null
	 */
	public function get_category_ids() {
		return $this->category_ids;
	}

	/**
	 * Returns the list of event types for events to filter
	 *
	 * @return int[]|null
	 */
	public function get_event_type_ids() {
		return $this->event_type_ids;
	}

	/**
	 * Returns the layout
	 *
	 * @return string
	 */
	public function get_layout() {
		return $this->layout;
	}

	/**
	 * Returns the list of available item's elements.
	 *
	 * @return string[]
	 */
	public function get_elements() {
		return $this->elements;
	}

	/**
	 * Return the timezone for 'time' block of the item
	 *
	 * @return string
	 */
	public function get_timezone() {
		return $this->timezone;
	}

	/**
	 * Return the timezone format for 'time' block of the item
	 *
	 * @return string
	 */
	public function get_timezone_format() {
		return $this->timezone_format;
	}

	/**
	 * True if the user should skip event page and go to the registration page on 'Register' button.
	 *
	 * @return bool
	 * @since 3.0.0
	 */
	public function is_skip_event_page() {
		return $this->skip_event_page;
	}

	/**
	 * Return true if the name of trainers should be shown along side their photo
	 *
	 * @return bool
	 * @since 3.0.0
	 */
	public function is_show_trainer_name() {
		return $this->show_trainer_name;
	}

	/**
	 * Returns true if featured events should be on top
	 *
	 * @return bool
	 */
	public function is_featured_on_top() {
		return $this->featured_on_top;
	}

	/**
	 * Returns true if featured events should be highlighted
	 *
	 * @return bool
	 */
	public function is_highlight_featured() {
		return $this->highlight_featured;
	}

	/**
	 * Returns true if only featured events should be rendered
	 *
	 * @return bool
	 */
	public function is_only_featured() {
		return $this->only_featured;
	}

	/**
	 * Returns true if the schedule's layout is table
	 *
	 * @return bool
	 */
	public function is_table_layout() {
		return 'table' === $this->layout;
	}

	/**
	 * Returns available filters
	 *
	 * @return array
	 */
	public function get_filters() {
		return $this->filters;
	}

	/**
	 * Initialize the config from the attributes
	 *
	 * @param array $attrs Shortcode's attributes.
	 */
	private function init( $attrs ) {
		if ( ! is_null( $attrs['category'] ) ) {
			$this->category_ids = $attrs['category'];
		}
		if ( ! is_null( $attrs['event_type'] ) ) {
			$this->event_type_ids = $attrs['event_type'];
		}
		// newer version of the attribute rewrites the old one.
		if ( ! is_null( $attrs['categories'] ) ) {
			$this->category_ids = preg_replace( '/\s/', '', $attrs['categories'] );
		}
		if ( ! is_null( $attrs['event_types'] ) ) {
			$this->event_type_ids = preg_replace( '/\s/', '', $attrs['event_types'] );
		}
		$this->featured_on_top   = $attrs['featured_on_top'];
		$this->highlight_featured= $attrs['highlight_featured'];
		$this->layout            = $attrs['layout'];
		$this->only_featured     = $attrs['only_featured'];
		$this->filters           = array_map(
										function ( $name ) { return trim( $name ); },
										explode( ',', $attrs['filters'] )
									);
		$this->tag_type          = $attrs['tag_type'];
		$this->skip_event_page   = $attrs['skip_event_page'];
		$this->show_trainer_name = $attrs['show_trainer_name'];
		$this->timezone          = $attrs['timezone'];
		$this->timezone_format   = $attrs['timezone_format'];
		$this->elements          = array_map(
										function ( $name ) { return trim( $name ); },
										explode( ',', $attrs['elements'] )
									);
	}
}

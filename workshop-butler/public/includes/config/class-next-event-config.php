<?php
/**
 * Configuration for a next event element
 *
 * @package WorkshopButler\Config
 * @since 3.0.0
 */

namespace WorkshopButler\Config;

/**
 * Class Next_Event_Config
 *
 * @since 3.0.0
 * @package WorkshopButler\Config
 */
class Next_Event_Config {

	/**
	 * True if the button on the element should lead to the registration page
	 *
	 * @var bool
	 * @since 3.0.0
	 */
	protected $registration = true;

	/**
	 * Defines the behaviour on how the page should open after clicking on the button.
	 *
	 * @var string
	 * @since 3.0.0
	 */
	protected $target = '_self';

	/**
	 * Title on the button.
	 *
	 * @var null|string
	 * @since 3.0.0
	 */
	protected $title = null;

	/**
	 * Message to show if there is no next event.
	 *
	 * @var null|string
	 * @since 3.0.0
	 */
	protected $no_event_title = null;

	/**
	 * List of event type ids to limit the set of events to select the next event from
	 *
	 * @since 3.0.0
	 * @var null|int[]
	 */
	protected $event_type_ids = null;

	/**
	 * List of category ids to limit the set of events to select the next event from
	 *
	 * @since 3.0.0
	 * @var null|int[]
	 */
	protected $category_ids = null;

	/**
	 * Next_Event_Config constructor
	 *
	 * @param array $attrs List of attributes.
	 */
	public function __construct( $attrs ) {
		$this->registration   = $attrs['registration'];
		$this->title          = $attrs['title'];
		$this->target         = $attrs['target'];
		$this->no_event_title = $attrs['no_event_title'];
		$this->category_ids   = $attrs['categories'];
		$this->event_type_ids = $attrs['event_types'];
	}

	/**
	 * Returns the value of 'target' element for the url on the button
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function open_page_in() {
		return $this->target;
	}

	/**
	 * Returns true if the button on the element should lead to the registration page
	 *
	 * @since 3.0.0
	 */
	public function is_registration() {
		return $this->registration;
	}

	/**
	 * Returns title of the button
	 *
	 * @return string|null
	 * @since 3.0.0
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * Returns message in case of no next event found
	 *
	 * @return string|null
	 * @since 3.0.0
	 */
	public function get_no_event_title() {
		return $this->no_event_title;
	}

	/**
	 * Returns the list of event type ids (if exist)
	 *
	 * @since 3.0.0
	 * @return int[]|null
	 */
	public function get_event_type_ids() {
		return $this->event_type_ids;
	}

	/**
	 * Returns the list of category ids (if exist)
	 *
	 * @since 3.0.0
	 * @return int[]|null
	 */
	public function get_category_ids() {
		return $this->category_ids;
	}
}

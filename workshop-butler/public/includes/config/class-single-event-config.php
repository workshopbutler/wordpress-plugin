<?php
/**
 * Configuration for a single event page, taken completely from the UI
 *
 * @package WorkshopButler\Config
 * @since 3.0.0
 */

namespace WorkshopButler\Config;

use WorkshopButler\WSB_Options;

/**
 * Class Single_Event_Config
 *
 * @since 3.0.0
 * @package WorkshopButler\Config
 */
class Single_Event_Config {

	/**
	 * Defines where to open the registration page when a user clicks on 'Register' button
	 *
	 * @var string
	 * @since 3.0.0
	 */
	protected $registration_page_target = '_self';

	/**
	 * Defines if the expired tickets of the event should be shown or not
	 *
	 * @var bool
	 * @since 3.0.0
	 */
	protected $show_expired_tickets = true;

	/**
	 * Defines if the number of available tickets of the event should be shown or not
	 *
	 * @var bool
	 * @since 3.0.0
	 */
	protected $show_number_of_tickets = true;

	/**
	 * Single_Event_Config constructor
	 */
	public function __construct() {
		$this->registration_page_target = WSB()->settings->get(
			WSB_Options::REGISTRATION_PAGE_NEW_TAB, false ) ? '_blank' : '_self';
		$this->show_expired_tickets = WSB()->settings->get( WSB_Options::SHOW_EXPIRED_TICKETS, true );
		$this->show_number_of_tickets = WSB()->settings->get( WSB_Options::SHOW_NUMBER_OF_TICKETS, true );
	}

	/**
	 * Returns the value of 'target' element for the url on the registration button
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function open_registration_page_in() {
		return $this->registration_page_target;
	}

	/**
	 * Returns true if expired tickets should be shown
	 *
	 * @return bool
	 * @since 3.0.0
	 */
	public function is_show_expired_tickets() {
		return $this->show_expired_tickets;
	}

	/**
	 * Returns true if the number of available tickets should be shown
	 *
	 * @return bool
	 * @since 3.0.0
	 */
	public function is_show_number_of_tickets() {
		return $this->show_number_of_tickets;
	}
}

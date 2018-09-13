<?php
/**
 * The file that defines the Event_State class
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */

/**
 * Represents an event's state
 * @since 2.0
 */
class Event_State {
    /**
     * @var Event $event
     * @since 2.0.0
     */
    protected $event;
    
    /**
     * Initialises a new state
     *
     * @param Event $event
     */
    public function __construct($event) {
        $this->event = $event;
    }
    
    /**
     * Returns true if the registrations for this event are open
     * @return boolean
     * @since 2.0.0
     */
    function open() {
        return !$this->closed();
    }
    
    /**
     * Returns true if the registrations for this event are closed
     * @return boolean
     * @since 2.0.0
     */
    function closed() {
        if ($this->event->schedule->ended()) {
            return true;
        } else if ($this->event->private) {
            return true;
        } else if (!$this->event->tickets) {
            return false;
        } else if ($this->event->free && $this->event->tickets->free->sold_out()) {
            return true;
        } else {
            if (!$this->event->tickets) {
                return false;
            }
            if (!$this->event->free && count($this->event->tickets->paid) > 0) {
                $sold_out = true;
                foreach ($this->event->tickets->paid as $ticket_type) {
                    if ($ticket_type->active()) {
                        $sold_out = false;
                    }
                }
                return $sold_out;
            } else {
                return false;
            }
        }
    }
    
    /**
     * Returns the reason why the registrations are closed or 'null' if they are open
     * @return string | null
     * @since 2.0.0
     */
    function reason() {
        if ($this->event->schedule->ended()) {
            return 'event.state.ended';
        } else if ($this->event->private) {
            return 'event.state.private';
        } else if (!$this->event->tickets) {
            return null;
        } else if ($this->event->free && $this->event->tickets->free->sold_out()) {
            return 'event.state.soldOut';
        } else {
            if (!$this->event->free && count($this->event->tickets->paid) > 0) {
                $sold_out = true;
                foreach ($this->event->tickets->paid as $ticket_type) {
                    if ($ticket_type->active()) {
                        $sold_out = false;
                    }
                }
                return $sold_out ? 'event.state.soldOut': null;
            } else {
                return null;
            }
        }
    }
    
}

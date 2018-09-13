<?php
/**
 * The file that defines the Registration_Page class
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WSB_Integration
 */

/**
 * Contains the logic for the event registration
 */
class Registration_Page {
    /**
     * @var boolean $external True if the registration page is on the third-party website
     * @since 2.0.0
     */
    public $external;
    
    /**
     * @var string|null $url The registration url
     * @since 2.0.0
     */
    public $url;
    
    public function __construct($json_data, $registration_url, $event_id) {
        if ($json_data) {
            $this->external = $json_data->custom;
            $this->url = $json_data->url;
        }
        if (!$this->external && $registration_url) {
            $this->url = Registration_Page::get_internal_url($registration_url, $event_id);
        }
    }
    
    /**
     * Returns a correctly formed url for a registration page of the event
     * @param string $registration_page_url Url of the page with RegistrationPage widget
     * @param string $event_id Hashed event id
     * @return string
     */
    static protected function get_internal_url($registration_page_url, $event_id) {
        return $registration_page_url . "?id=" . $event_id;
    }
}

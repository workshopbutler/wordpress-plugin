<?php
/**
 * The file that defines the social links class
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
/**
 * Contains different social links a trainer can have
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Social_Links {
    public $website;
    public $blog;
    public $twitter;
    public $linked_in;
    public $facebook;
    public $google_plus;
    
    /**
     * Creates a new object
     *
     * @param $jsonData object JSON data from Workshop Butler API
     */
    public function __construct($jsonData) {
        $this->website = $jsonData->website;
        $this->blog = $jsonData->blog;
        $this->facebook = $jsonData->facebook_url;
        $this->twitter = 'https://twitter.com/' . $jsonData->twitter_handle;
        $this->linked_in = $jsonData->linkedin_url;
        $this->google_plus = $jsonData->google_plus_url;
    }
}

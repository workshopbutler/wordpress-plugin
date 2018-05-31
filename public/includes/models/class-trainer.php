<?php
/**
 * The file that defines the trainer class, used later in templates
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-social-links.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-statistics.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'helper-functions.php';

/**
 * Trainer class which represents a trainer profile in Workshop Butler
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Trainer {
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $photo;
    public $bio;
    public $url;
    public $country;
    
    /**
     * @since   0.2.0
     * @var     string[] $languages Languages the trainer speaks to
     */
    public $languages;
    public $years_of_experience;
    public $number_of_events;
    public $badges;
    public $social_links;
    public $public_stats;
    public $private_stats;
    public $recent_public_stats;
    public $recent_private_stats;
    public $endorsements;
    
    /**
     * Creates a new object
     *
     * @param $jsonData object JSON data from Workshop Butler API
     * @param $trainerUrl string Trainer profile page URL
     */
    public function __construct( $jsonData, $trainerUrl = '' ) {
        $this->id                  = $jsonData->id;
        $this->first_name          = $jsonData->first_name;
        $this->last_name           = $jsonData->last_name;
        $this->photo               = $jsonData->photo;
        $this->bio                 = $jsonData->bio;
        $this->email               = $jsonData->email_address;
        $this->years_of_experience = $jsonData->years_of_experience;
        $this->number_of_events    = $jsonData->number_of_events;
        $this->badges              = $jsonData->badges;
        $this->social_links        = new Social_Links( $jsonData );
        
        $this->public_stats         = $this->getStatistics( $jsonData, true, false );
        $this->private_stats        = $this->getStatistics( $jsonData, false, false );
        $this->recent_public_stats  = $this->getStatistics( $jsonData, true, true );
        $this->recent_private_stats = $this->getStatistics( $jsonData, false, true );
        $this->endorsements         = $jsonData->endorsements;
        
        $this->url = $trainerUrl . '?id=' . $this->id;
        $this->country   = $this->getCountryName( $jsonData );
        $this->languages = $jsonData->languages;
    }
    
    public function full_name() {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    /**
     * Returns a correct trainer country
     *
     * @param $jsonData object JSON data from Workshop Butler API
     *
     * @return string
     */
    private function getCountryName( $jsonData ) {
        if ( $jsonData->country ) { //from Trainers API
            return wsb_get_country_name( $jsonData->country );
        } else if ( $jsonData->address && $jsonData->address->country ) { //from Trainer API
            return wsb_get_country_name( $jsonData->address->country );
        } else {
            return '';
        }
    }
    
    /**
     * Returns a specific type of statistics
     *
     * @param $jsonData object JSON data from Workshop Butler API
     * @param $publicWorkshops boolean True if the statistics is from public workshops
     * @param $recentWorkshops boolean True if the statistic is from recent workshops
     *
     * @return Statistics
     */
    private function getStatistics( $jsonData, $publicWorkshops, $recentWorkshops ) {
        if ( $jsonData->statistics ) {
            $stats = $jsonData->statistics;
            if ( $publicWorkshops ) {
                if ( $recentWorkshops ) {
                    return new Statistics( $stats->recent_number_of_public_evaluations, $stats->recent_public_median,
                        $stats->recent_public_nps, $stats->recent_public_rating );
                } else {
                    return new Statistics( $stats->number_of_public_evaluations, $stats->public_median,
                        $stats->public_nps, $stats->public_rating );
                }
            } else {
                if ( $recentWorkshops ) {
                    return new Statistics( $stats->recent_number_of_private_evaluations, $stats->recent_private_median,
                        $stats->recent_private_nps, $stats->recent_private_rating );
                } else {
                    return new Statistics( $stats->number_of_private_evaluations, $stats->private_median,
                        $stats->private_nps, $stats->private_rating );
                }
            }
        } else {
            return new Statistics( 0, 0, 0, 0 );
        }
    }
}

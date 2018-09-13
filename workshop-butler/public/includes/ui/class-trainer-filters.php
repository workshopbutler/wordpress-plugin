<?php
/**
 * The file that defines Trainer_Filters class
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path(__FILE__) . 'class-list-filters.php';
require_once plugin_dir_path(__FILE__) . 'class-filter-value.php';

/**
 * This class contains the logic for producing various filters for trainers
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Trainer_Filters extends List_Filters {
    
    /**
     * Initialises a new object
     *
     * @param $trainers Trainer[] Available trainers which we use to build filters
     * @param $visible_filters string[] List of filters to render on the page
     */
    public function __construct($trainers, $visible_filters) {
        $this->objects = $trainers;
        $this->filters = $visible_filters;
    }
    
    /**
     * Returns the values of the filter based on its name
     *
     * @param $name string Name of the filter
     *
     * @return Filter_Value[]
     */
    protected function get_filter_values($name) {
        switch($name) {
            case 'language':
                return $this->get_language_filter_data(__("filter.languages", 'wsbintegration'), $this->objects);
            case 'location':
                return $this->get_location_filter_data(__("filter.locations", 'wsbintegration'), $this->objects);
            case 'trainer':
                return $this->get_trainer_filter_data(__("filter.trainers", 'wsbintegration'), $this->objects);
            default:
                return [];
        }
    }
    
    /**
     * Returns values for Language filter
     *
     * @param $default_name string Name of the default filter value
     * @param $trainers Trainer[] Available trainers to filter
     *
     * @return Filter_Value[]
     */
    private function get_language_filter_data( $default_name, $trainers ) {
        $languages = [];
        foreach ( $trainers as $trainer ) {
            $trainer_languages = $trainer->languages;
            foreach ( $trainer_languages as $language ) {
                $id = 'language.' . $language;
                $value = new Filter_Value( __($id, 'wsbintegration'), $language );
                array_push( $languages, $value );
            }
        }
        return $this->get_filter_data( $default_name, $languages );
    }
    
    /**
     * Returns values for Location filter
     *
     * @param $default_name string Name of the default filter value
     * @param $trainers Trainer[] Available trainers to filter
     *
     * @return Filter_Value[]
     */
    private function get_location_filter_data( $default_name, $trainers ) {
        $values = [];
        foreach ( $trainers as $trainer ) {
            $country_name = __('country.' . $trainer->country_code, 'wsbintegration');
    
            $value = new Filter_Value($country_name, $trainer->country_code);
            array_push($values, $value);
        }
        
        return $this->get_filter_data( $default_name, $values );
    }
    
    /**
     * Returns values for Trainer filter
     *
     * @param $defaultName string Name of the default filter value
     * @param $trainers Trainer[] Available trainers to filter
     *
     * @return Filter_Value[]
     */
    private function get_trainer_filter_data( $defaultName, $trainers ) {
        $values = [];
        foreach ( $trainers as $trainer ) {
            $fullName = $trainer->first_name . ' ' . $trainer->last_name;
            $value = new Filter_Value($fullName, $fullName);
            array_push($values, $value);
        }
        return $this->get_filter_data( $defaultName, $values );
    }
}

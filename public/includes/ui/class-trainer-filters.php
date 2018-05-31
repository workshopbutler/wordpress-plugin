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
     * @param $visibleFilters string[] List of filters to render on the page
     */
    public function __construct($trainers, $visibleFilters) {
        $this->objects = $trainers;
        $this->filters = $visibleFilters;
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
                return $this->getLanguageFilterData(__("All languages", 'wsbintegration'), $this->objects);
            case 'location':
                return $this->getLocationFilterData(__("All locations", 'wsbintegration'), $this->objects);
            case 'trainer':
                return $this->getTrainerFilterData(__("All trainers", 'wsbintegration'), $this->objects);
            default:
                return [];
        }
    }
    
    /**
     * Returns values for Language filter
     *
     * @param $defaultName string Name of the default filter value
     * @param $trainers Trainer[] Available trainers to filter
     *
     * @return Filter_Value[]
     */
    private function getLanguageFilterData( $defaultName, $trainers ) {
        $languages = [];
        foreach ( $trainers as $trainer ) {
            $trainerLanguages = $trainer->languages;
            foreach ( $trainerLanguages as $language ) {
                $value = new Filter_Value( __($language, 'wsbintegration'), $language );
                array_push( $languages, $value );
            }
        }
        return $this->get_filter_data( $defaultName, $languages );
    }
    
    /**
     * Returns values for Location filter
     *
     * @param $defaultName string Name of the default filter value
     * @param $trainers Trainer[] Available trainers to filter
     *
     * @return Filter_Value[]
     */
    private function getLocationFilterData( $defaultName, $trainers ) {
        $values = [];
        foreach ( $trainers as $trainer ) {
            $value = new Filter_Value(__($trainer->country, 'wsbintegration'), $trainer->country);
            array_push($values, $value);
        }
        
        return $this->get_filter_data( $defaultName, $values );
    }
    
    /**
     * Returns values for Trainer filter
     *
     * @param $defaultName string Name of the default filter value
     * @param $trainers Trainer[] Available trainers to filter
     *
     * @return Filter_Value[]
     */
    private function getTrainerFilterData( $defaultName, $trainers ) {
        $values = [];
        foreach ( $trainers as $trainer ) {
            $fullName = $trainer->first_name . ' ' . $trainer->last_name;
            $value = new Filter_Value($fullName, $fullName);
            array_push($values, $value);
        }
        return $this->get_filter_data( $defaultName, $values );
    }
}

<?php
/**
 * The file that defines List_Filters class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( __FILE__ ) . 'class-filter.php';
require_once plugin_dir_path( __FILE__ ) . 'class-filter-value.php';

/**
 * This class contains a common logic for filters on different pages
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
abstract class List_Filters {

	/**
	 * Available objects which we use to build filters
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      object[] $objects Available objects which we use to build filters
	 */
	protected $objects;

	/**
	 * Names of visible filters
	 *
	 * @since  2.0.0
	 * @var    string[] $filters Names of visible filters
	 */
	protected $filters;

	/**
	 * Returns the values of the filter based on its name
	 *
	 * @param string $name Name of the filter.
	 *
	 * @return Filter_Value[]
	 */
	abstract protected function get_filter_values( $name );

	/**
	 * Returns filters which should be added to the page
	 *
	 * @return Filter[]
	 */
	public function get_filters() {
		$filters = array();
		foreach ( $this->filters as $filter_name ) {
			$values = $this->get_filter_values( $filter_name );
			if ( count( $values ) > 0 ) {
				$filter_value = new Filter( $filter_name, $values, true );
				array_push( $filters, $filter_value );
			}
		}
		return $filters;
	}

	/**
	 * Returns values for the filter, which can be added to the page
	 *
	 * @param string         $default_name Caption of the first, default item.
	 * @param Filter_Value[] $values       All available filter values.
	 * @return Filter_Value[]
	 */
	protected function get_filter_data( $default_name, $values ) {
		$filtered = array_filter(
			$values,
			function ( $object ) {
				return null !== $object->value;
			}
		);
		$filtered = array_unique( $filtered );
		if ( 0 === count( $filtered ) ) {
			return array();
		}
		usort(
			$filtered,
			function ( $left, $right ) {
				return $left->name > $right->name;
			}
		);
		$default_value = new Filter_Value( $default_name, 'all' );
		array_unshift( $filtered, $default_value );
		return $filtered;
	}
}

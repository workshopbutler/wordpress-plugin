<?php
/**
 * The file that defines List_Filters class
 *
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( __FILE__ ) . 'class-filter.php';
require_once plugin_dir_path( __FILE__ ) . 'class-filter-value.php';

/**
 * This class contains a common logic for filters on different pages
 *
 * @since      0.2.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
abstract class List_Filters {

	/**
	 * Available objects which we use to build filters
	 *
	 * @since    0.2.0
	 * @access   private
	 * @var      object[] $objects Available objects which we use to build filters
	 */
	protected $objects;

	/**
	 * Names of visible filters
	 *
	 * @since  0.2.0
	 * @var    string[] $filters Names of visible filters
	 */
	protected $filters;

	/**
	 * Returns the values of the filter based on its name
	 *
	 * @param $name string Name of the filter
	 *
	 * @return Filter_Value[]
	 */
	protected abstract function get_filter_values( $name);

	/**
	 * Returns filters which should be added to the page
	 *
	 * @return Filter[]
	 */
	public function get_filters() {
		$filters = [];
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
	 * @param $defaultName string Caption of the first, default item
	 * @param $values Filter_Value[] All available filter values
	 * @return Filter_Value[]
	 */
	protected function get_filter_data( $defaultName, $values ) {
		$filtered = array_filter(
			$values,
			function ( $object ) {
				return $object->value !== null;
			}
		);
		$filtered = array_unique( $filtered );
		if ( count( $filtered ) == 0 ) {
			return [];
		}
		usort(
			$filtered,
			function ( $left, $right ) {
				return $left->name > $right->name;
			}
		);
		$defaultValue = new Filter_Value( $defaultName, 'all' );
		array_unshift( $filtered, $defaultValue );
		return $filtered;
	}
}

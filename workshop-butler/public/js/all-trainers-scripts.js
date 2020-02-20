/**
 * Returns a correct filter function
 *
 * @param name Name of the filter
 * @param value Filter value
 * @return {*}
 */
function wsb_get_filter(name, value) {
	var ratingFunction     = function(index, el) {
		return jQuery( el ).data( 'trainer-rating' ) > value;
	};
	var experienceFunction = function(index, el)  {
		var exp = jQuery( el ).data( 'trainer-exp' );
		switch (value) {
			case 'one': return exp < 1;
			case 'three': return exp <= 3 && exp >= 1;
			case 'five': return exp > 3 && exp <= 5;
			case 'seven': return exp > 5 && exp <= 7;
			case 'more': return exp > 7;
			default: return false;
		}
	};
	switch (name) {
		case 'experience': return experienceFunction;
		case 'rating': return ratingFunction;
		default: return '[data-trainer-' + name + '*="' + value + '"]';
	}
}

jQuery( document ).ready(
	function() {

		var $root = jQuery.find( '.wsb-content' );
		jQuery( $root ).on(
			'change',
			'[data-filter]',
			function(e) {
				var trainers = jQuery( $root ).find( '[data-trainer-obj]' ).hide();
				jQuery( $root ).find( '[data-filter]' ).each(
					function(index, el) {
						var name   = jQuery( el ).data( 'name' );
						var value  = jQuery( el ).val();
						var filter = wsb_get_filter( name, value );
						if (value !== 'all') {
							trainers = trainers.filter( filter );
						}
					}
				);
				if (trainers.length) {
					  jQuery( $root ).find( '.wsb-no-trainers' ).hide();
					  trainers.show();
				} else {
					jQuery( $root ).find( '.wsb-no-trainers' ).show();
				}
			}
		);
	}
);

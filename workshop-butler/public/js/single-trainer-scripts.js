jQuery( document ).ready(
	function() {

		wsb_load_events(
			'future-trainer-events',
			function (result) {
				jQuery( '#upcoming-events' ).find( '[data-events-list]' ).html( result );
			}
		);
		wsb_load_events(
			'past-trainer-events',
			function (result) {
				jQuery( '#past-events' ).find( '[data-events-list]' ).html( result );
			}
		);

        // init owl carousel
        const owl = jQuery('.owl-carousel-testimonial');
        jQuery('.wsb-testimonials-counter').show();
        jQuery('.wsb-testimonials-counter .total').text(owl.find('> div').length);
        owl.owlCarousel({
            items: 1,
            dots: false,
            nav: true,
			loop: true,
        });
        owl.on('changed.owl.carousel', function(e) {
            jQuery('.wsb-testimonials-counter .current').text(
				// workaround for incorrect counter in loop mode
				1 + e.item.index - jQuery(e.target).find('.active:first').prevAll('.cloned').length
			);
        });

		function wsb_load_events(type, callback) {
			var data = {
				action : 'wsb_get_values',
				type: type,
				_ajax_nonce: wsb_single_trainer.nonce,
				id: wsb_single_trainer.trainer_id
			};

			jQuery.ajax(
				{
					type: "GET",
					url: wsb_single_trainer.ajax_url,
					data: data,
					cache: false
				}
			)
			.fail(
				function(result) {
					console.log( "Ajax Failed - wsbLoadData: " + JSON.stringify( result ) );
				}
			)
			.done(
				function(result) {
					callback( result );
				}
			);
		}

	}
);

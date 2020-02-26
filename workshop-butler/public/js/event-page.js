/**
 * Loads events for a sidebar
 *
 * @param type {string} Request type, defining what events will be returned
 * @param callback {function} Function to process the successful request
 */
function wsb_load_events(type, callback) {
    const data = {
        action: 'wsb_get_values',
        type: type,
        _ajax_nonce: wsb_event.nonce,
        country_code: wsb_event.country,
        trainer_id: wsb_event.trainer,
        event_id: wsb_event.id,
		event_type: wsb_event.type_id,
    };

    jQuery.ajax(
        {
            type: "GET",
            url: wsb_event.ajax_url,
            data: data,
            cache: false
        }
    ).fail(
        function (result) {
            console.log("Ajax Failed - wsbLoadData: " + JSON.stringify(result));
        }
    ).done(
        function (result) {
            callback(result);
        }
    );
}

jQuery(document).ready(
    function () {
        wsb_load_events(
            'event-page-sidebar',
            function (result) {
                jQuery('#upcoming-events').find('[data-events-list]').html(result);
            }
        );
    }
);

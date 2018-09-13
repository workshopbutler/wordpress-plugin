jQuery(document).ready(function () {

    var $root = jQuery.find('.wsb-content');
    jQuery($root).on('change', '[data-filter]', function(e) {
        var events = jQuery($root).find('[data-event-obj]').hide();
        jQuery($root).find('[data-filter]').each(function (index, el) {
            var filterName = jQuery(el).data('name');
            var value = jQuery(el).val();
            var filter = (filterName === 'type' || filterName === 'location') ?
                '[data-event-' + filterName + '="' + value + '"]' :
                '[data-event-' + filterName + '*="' + value + '"]';
            if (value !== 'all') {
                events = events.filter(filter);
            }
        });
        if (events.length) {
            jQuery($root).find('.wsb-no-events').hide();
            events.show();
        } else {
            jQuery($root).find('.wsb-no-events').show();
        }
    });
});

jQuery(document).ready(function () {

    var $root = jQuery.find('.wb-content');
    jQuery($root).on('change', '[data-filter]', function(e) {
        var trainers = jQuery($root).find('[data-trainer-obj]').hide();
        jQuery($root).find('[data-filter]').each(function (index, el) {
            var filterName = jQuery(el).data('name');
            var value = jQuery(el).val();
            var filter = (filterName === 'location') ?
                '[data-trainer-' + filterName + '="' + value + '"]' :
                '[data-trainer-' + filterName + '*="' + value + '"]';
            if (value !== 'all') {
                trainers = trainers.filter(filter);
            }
        });
        if (trainers.length) {
            jQuery($root).find('.wb-no-trainers').hide();
            trainers.show();
        } else {
            jQuery($root).find('.wb-no-trainers').show();
        }
    });
});

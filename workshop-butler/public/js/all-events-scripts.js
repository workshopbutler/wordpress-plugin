jQuery(document).ready(function () {
    

    var $root = jQuery.find('.wsb-content');

    jQuery($root).on('change', '[data-name="location"]', function(e) {
        window.location.href = updateQueryStringParameter( window.location.href, "location", jQuery(this).val() );
    });
    jQuery($root).on('change', '[data-name="trainer"]', function(e) {
        window.location.href = updateQueryStringParameter( window.location.href, "trainer", jQuery(this).val() );
    });
    jQuery($root).on('change', '[data-name="language"]', function(e) {
        window.location.href = updateQueryStringParameter( window.location.href, "language", jQuery(this).val() );
    });
    jQuery($root).on('change', '[data-name="type"]', function(e) {
        window.location.href = updateQueryStringParameter( window.location.href, "type", jQuery(this).val() );
    });

    setTimeout(function(){
        var loc = window.location.href;
        var index = loc.indexOf("?");
        var splitted = loc.substr(index+1).split('&');
        var paramObj = [];
        for(var i=0;i<splitted.length;i++){
            var params = splitted[i].split('=');
            var key = params[0];
            if( params[1] != null ){
                var value = decodeURIComponent(params[1].replace(/\+/g, " "));
            }
            jQuery("[data-name='"+key+"'] option[value='" + value + "']")
                .prop("selected",true);

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
        }
    },100);
});

function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    }
    else {
        return uri + separator + key + "=" + value;
    }
}

function getParameterByName( name ){
    var regexS = "[\\?&]"+name+"=([^&#]*)",
        regex = new RegExp( regexS ),
        results = regex.exec( window.location.search );
    if( results == null ){
        return "";
    } else{
        return decodeURIComponent(results[1].replace(/\+/g, " "));
    }
}

function getQueryParameterforWB (name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.search);
    return (results !== null) ? results[1] || 0 : false;
}

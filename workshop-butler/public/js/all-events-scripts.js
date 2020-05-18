jQuery(document).ready(
	function () {
		const $root = jQuery.find('.wsb-content');
		// `wsb` is an object passed from WordPress (see WSB_Schedule_Page)
		jQuery($root).on(
			'change',
			`[data-name="location"]`,
			function () {
				window.location.href = updateQueryStringParameter(window.location.href, wsb.location, jQuery(this).val());
			}
		);
		jQuery($root).on(
			'change',
			'[data-name="trainer"]',
			function (e) {
				window.location.href = updateQueryStringParameter(window.location.href, wsb.trainer, jQuery(this).val());
			}
		);
		jQuery($root).on(
			'change',
			'[data-name="language"]',
			function (e) {
				window.location.href = updateQueryStringParameter(window.location.href, wsb.language, jQuery(this).val());
			}
		);
		jQuery($root).on(
			'change',
			'[data-name="type"]',
			function (e) {
				window.location.href = updateQueryStringParameter(window.location.href, wsb.type, jQuery(this).val());
			}
		);

		setTimeout(
			function () {
				const loc = window.location.href;
				const index = loc.indexOf("?");
				const splitted = loc.substr(index + 1).split('&');
				for (let i = 0; i < splitted.length; i++) {
					const queryParam = splitted[i].split('=');
					const queryName = queryParam[0];
					if (queryParam[1] != null) {
						var value = decodeURIComponent(queryParam[1].replace(/\+/g, " "));
					}
					const filterId = getKey(wsb, queryName);
					if (null === filterId) {
						continue;
					}
					jQuery("[data-name='" + filterId + "'] option[value='" + value + "']").prop("selected", true);

					let events = jQuery($root).find('[data-event-obj]').hide();
					jQuery($root).find('[data-filter]').each(
						function (index, el) {
							const filterName = jQuery(el).data('name');
							const value = jQuery(el).val();
							const filter = (filterName === 'type' || filterName === 'location') ?
								'[data-event-' + filterName + '="' + value + '"]' :
								'[data-event-' + filterName + '*="' + value + '"]';
							if (value !== 'all') {
								events = events.filter(filter);
							}
						}
					);
					if (events.length) {
						jQuery($root).find('.wsb-no-events').hide();
						events.show();
					} else {
						jQuery($root).find('.wsb-no-events').show();
					}
				}
			},
			100
		);
	}

);

/**
 * Returns object key from its value
 * @param object {Object} Object of interest.
 * @param value {string} Value to find
 * @return {string|null}
 */
function getKey(object, value) {
	for (const key in object) {
		if (!object.hasOwnProperty(key)) {
			continue;
		}
		if (object[key] === value) {
			return key;
		}
	}
	return null;
}

function updateQueryStringParameter(uri, key, value) {
	const re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
	const separator = uri.indexOf('?') !== -1 ? "&" : "?";
	if (uri.match(re)) {
		return uri.replace(re, '$1' + key + "=" + value + '$2');
	} else {
		return uri + separator + key + "=" + value;
	}
}

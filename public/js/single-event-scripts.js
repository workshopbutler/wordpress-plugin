/**
 * Loads events for a sidebar
 * @param type {string} Request type, defining what events will be returned
 * @param callback {function} Function to process the successful request
 */
function wsb_load_events(type, callback) {
    var data = {
        action : 'wsb_get_values',
        type: type,
        _ajax_nonce: wsb_single_event.nonce,
        id: wsb_single_event.country
    };

    jQuery.ajax({
        type: "GET",
        url: wsb_single_event.ajax_url,
        data: data,
        cache: false
    })
        .fail(function(result) {
            console.log("Ajax Failed - wsbLoadData: " + JSON.stringify(result));
        })
        .done(function(result) {
            callback(result);
        });
}

function register() {

}
function get_root_element() {
    return jQuery('.wb-content');
}

function open_registration_form(e) {
    e.preventDefault();

    if (wsb_single_event.registration_url) {
        var new_tab = window.open(wsb_single_event.registration_url, '_blank');
        if (new_tab) {
            new_tab.focus()
        }
    } else {
        jQuery('[data-registration-form]').show();
        var $root = get_root_element();
        $root.addClass('wb-state__pre-registration');
        var offset = $root.find('[data-registration-form]').offset();
        jQuery('html, body').animate({
            scrollTop: offset && offset.top
        }, 400);
    }
}

function get_translated_error_messages() {
    return {
        required: wsb_single_event.error_required,
        email: wsb_single_event.error_email,
        url: wsb_single_event.error_url,
        date: wsb_single_event.error_date,
        dateiso: wsb_single_event.error_dateiso,
        nospace: wsb_single_event.error_nospace,
        digits: wsb_single_event.error_digits,
        upper: wsb_single_event.error_upper,
        floats: wsb_single_event.error_floats
    }
}

function register_attendee(e) {
    e.preventDefault();

    if (!wsb_single_event.is_registration_closed) {
        var $root = get_root_element();
        var form_helper = new FormHelper({
            $controls: $root.find('[data-control]')
        }, get_translated_error_messages());
        if (!form_helper.isValidFormData()) return;

        var form_data = prepare_form_data(form_helper.getFormData());
        $root.addClass('h-busy');
        jQuery(e.target).prop('disabled', true).addClass('h-busy');

        form_data.action = 'wsb_register_to_event';
        form_data._ajax_nonce = wsb_single_event.nonce;

        jQuery.ajax({
            type: 'POST',
            cache: false,
            url: wsb_single_event.ajax_url,
            data: form_data,
            dataType: 'json',
            success: function (data) {
                form_helper.clearForm();
                $root.addClass('wb-state__post-registration').removeClass('h-busy wb-state__pre-registration');
                jQuery(e.target).removeProp('disabled').removeClass('h-busy');
            }
        });
    } else {
        console.log("Registration is closed. The plugin is configured incorrectly");
    }
}

/**
 * Removes empty values from data, sent to the server
 * @param data {object} Form data
 * @return {object}
 */
function prepare_form_data(data) {
    data.event_id = Number(wsb_single_event.id);
    for (var item in data) {
        if (!data[item]) delete data[item];
    }
    return data;
}

class FormHelper {
    /**
     * Validate given controls
     * @param {Object} options
     * @param {jQuery} options.$controls       - optional list of validating controls
     * @param {Object} options.rules           - list of rule
     * @param {Object} [options.restriction]   - list of restriction
     * @param {Object} messages
     */
    constructor(options, messages = null) {
        this.$controls = options.$controls;

        this.messages = messages || this._getDefaultMessages();
        this.rules = jQuery.extend({}, options.rules, this._getRulesFromHtml(this.$controls));
        this.restriction = jQuery.extend({}, options.restriction, this._getRestrictionFromHtml(this.$controls));
        this.errors = [];

        this._assignEvents();
    }

    _getDefaultMessages() {
        return {
            required: "This field is required.",
            email: "Please enter a valid email address.",
            url: "Please enter a valid URL.",
            date: "Please enter a valid date.",
            dateiso: "Please enter a valid date (ISO).",
            nospace: "Please enter a valid number.",
            digits: "Please enter only digits.",
            upper: "",
            floats: "Please enter only digits."
        }
    }

    /**
     * @param $controls
     * @returns {Object} - list of rules
     * @private
     */
    _getRulesFromHtml($controls) {
        var self = this;
        var rules = {};

        $controls.each((index, item) => {
            var $item = jQuery(item);
            var nameField = $item.attr('name');
            var possibleRules = self.messages;

            if (!$item.attr('class')) return;
            if (!$item.attr('class').match(/_validate-/i)) return;

            if (!rules[nameField]) rules[nameField] = {};

            for (var rule in possibleRules) {
                var ruleClass = '_validate-' + rule;

                if ($item.hasClass(ruleClass)) {
                    rules[nameField][rule] = true;
                }
            }
        });
        return rules;
    }

    /**
     * @param $controls
     * @returns {Object} - list of rules
     * @private
     */
    _getRestrictionFromHtml($controls) {
        var self = this;
        var restriction = {};

        $controls.each((index, item) => {
            var $item = jQuery(item);
            var nameField = $item.attr('name');
            var possibleRestrict = self.messages;

            if (!$item.attr('class')) return;
            if (!$item.attr('class').match(/_restrict-/i)) return;

            if (!restriction[nameField]) restriction[nameField] = {};

            for (var restrict in possibleRestrict) {
                var restrictClass = '_restrict-' + restrict;

                if ($item.hasClass(restrictClass)) {
                    restriction[nameField][restrict] = true;
                }
            }
        });
        return restriction;
    }

    _assignEvents() {
        this.$controls
            .on('blur', this._onBlurControl.bind(this))
            .on('input change', this._onInputControl.bind(this))
    }


    _onBlurControl(e) {
        var $el = jQuery(e.currentTarget);
        this._isValidControl($el);
    }

    _onInputControl(e) {
        var $control = jQuery(e.currentTarget);
        this._removeError($control);
        this._restrictInput($control);
    }

    _isValidControl($control) {
        var validation = this._validateControl($control);

        if (validation.isValid) {
            this._removeError($control);
            return true;
        }

        this._setError($control, validation.message);
        return false;
    }

    /**
     * Validate given control
     * @param {jQuery} $control - element
     * @returns {Object} = isValid(Boolean), message(String)
     * @private
     */
    _validateControl($control) {
        var name = $control.attr('name');
        var rules = this.rules[name];
        var valueControl = this.getControlValue($control);
        var valid;

        for (var rule in rules) {
            valid = this[rule + 'Validator'](valueControl, $control);

            if (!valid) return {
                isValid: false,
                message: this.messages[rule]
            };
        }

        return {
            isValid: true
        };
    }

    isValidFormData() {
        var self = this;
        var valid = true;

        this.removeErrors();
        this.$controls.each((index, control) => {
            var isValidControl = self._isValidControl(jQuery(control));
            valid = valid && isValidControl;
        });

        return valid;
    }

    _restrictInput($control) {
        var name = $control.attr('name');
        var restriction = this.restriction[name];
        var value = this.getControlValue($control);

        if (!restriction) return;

        for (var restict in restriction) {
            value = this[restict + 'Restrict'](value);
        }
        this.setControlValue($control, value);
    }

    /**
     * Show or hide last error
     * @param {Boolean} condition
     * @param {jQuery} $control
     * @private
     */
    _showPreviousError(condition, $control = null) {
        if (this.$inputWithError) {
            this.$inputWithError
                .parent()
                .toggleClass('b-error_state_high', !condition)
                .toggleClass('b-error_state_error', condition)
        }
        this.$inputWithError = $control;
    }

    /**
     * Set error for control
     * @param {jQuery} $control
     * @param {String} errorText
     * @param {Boolean} showBubble
     */
    _setError($control, errorText, showBubble = true) {
        var $parent = $control.parent();
        var errorClass = 'b-error';
        if (errorText && errorText.length > 35) {
            errorClass = 'b-long-error'
        }
        var $error = $parent.find('.' + errorClass);

        if ($error.length) {
            $error.text(errorText);
        } else {
            jQuery('<div class="' + errorClass + '" />')
                .text(errorText)
                .appendTo($parent);
        }

        $parent.addClass('b-error_show');

        this.errors.push({
            name: $control.attr('name'),
            error: errorText
        })
    }

    _removeError($control) {
        var $parent = $control.parent();

        $parent.removeClass('b-error_show');

        this.errors = this.errors.filter(function (item) {
            return item.name !== $control.attr('name')
        })
    }

    /**
     * Set errors
     * @param {Array} errors - [{name: "email", error: "empty"}, {name: "password", error: "empty"}]
     */
    setErrors(errors) {
        this.$inputWithError = null;
        var index = 0;

        errors.forEach((item) => {
            var $currentControl = this.$controls.filter('[name="' + item.name + '"]').first();

            if (!$currentControl.length) return;
            this._setError($currentControl, item.error, false);
        });
    }

    removeErrors() {
        this.$controls.each((index, el) => {
            this._removeError(jQuery(el))
        });
    }

    addRule(name, rules) {
        this.rules[name] = rules;
    }

    removeRule(name) {
        delete this.rules[name];
    }

    // validators
    requiredValidator(value, $el) {
        if ($el.is('select')) {
            var val = $el.val();
            return val && val.length > 0;
        }
        if ($el.is('input[type="checkbox"]')) {
            return $el.prop('checked');
        }
        return value.length > 0;
    }

    emailValidator(value, $el) {
        return /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(value);
    }

    urlValidator(value, $el) {
        return /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(value);
    }

    dateValidator(value, $el) {
        return !/Invalid|NaN/.test(new Date(value).toString());
    }

    dateisoValidator(value, $el) {
        return /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test(value);
    }

    // restriction
    digitsRestrict(value) {
        return value.replace(/[^\d]+/g, '');
    }

    upperRestrict(value) {
        return value.toUpperCase();
    }

    // restriction
    floatsRestrict(value) {
        return value.replace(/[^\d\.]+/g, '');
    }

    nospaceRestrict(value) {
        return value.replace(/\s/g, '');
    }

    // Helper for form
    getFormData() {
        var formData = {};

        this.$controls.each((index, el) => {
            var $el = jQuery(el);
            var name = $el.attr('name');
            var value = this.getControlValue($el);

            if (!name || typeof value === "undefined") return;

            formData[name] = value;
        });
        return formData;
    }

    setFormData(formData) {
        var $controls = this.$controls;

        for (var field in formData) {
            if (formData.hasOwnProperty(field)) {
                var $control = $controls.filter('[name="' + field + '"]').first();

                if ($control.length) {
                    this.setControlValue($control, formData[field]);
                }
            }
        }
    }

    /**
     * Get list of errors with full title (from control title attribute)
     * @param {ListErrors} errors - list of errors
     * @returns {string}
     */
    getErrorsFull(errors) {
        var self = this;
        var arrErrors = errors || this.errors;
        var errorTxt = '';

        arrErrors.forEach((item) => {
            var $control = self.$controls.filter('[name="' + item.name + '"]').first();
            var name = $control.length ? $control.attr('title') : item.name;

            errorTxt += '<b>' + name + '</b>: ' + item.error + '  <br>';
        });

        return errorTxt;
    }

    clearForm() {
        this.$controls.each((index, el) => {
            var $el = jQuery(el);
            if (!$el.attr("disabled") || !$el.is(':radio')) $el.val('');
        });
    }

    /**
     * Universal assign value
     * @param {jQuery} $control
     * @param {String|Number|Boolean} value
     */
    setControlValue($control, value) {
        if ($control.is(':checkbox')) {
            $control.prop('checked', value)
        } else {
            $control.val(value);
        }
    }

    /**
     * Universal get value helper
     * @param {jQuery} $control
     * @returns {String|Boolean}
     */
    getControlValue($control) {
        if ($control.is(':checkbox')) {
            return $control.prop('checked');
        }

        if ($control.is(':radio')) {
            if ($control.is(':checked')) {
                return $control.val();
            } else {
                return undefined;
            }
        }

        return $control.val();
    }
}

jQuery(document).ready(function() {

    wsb_load_events('future-events-country', function (result) {
        jQuery('#upcoming-events').find('[data-events-list]').html(result);
    });
    jQuery('[data-registration-button]').on('click',  open_registration_form);
    jQuery('[data-widget-submit]').on('click', register_attendee);
});

function get_form() {
    return jQuery('#wsb-form');
}


function register_attendee(e) {
    e.preventDefault();

    if (!wsb_event.is_registration_closed) {
        const $form = get_form();
        const form_helper = new FormHelper({
            $controls: $form.find('[data-control]')
        }, get_translated_error_messages());
        if (!form_helper.isValidFormData()) return;

        const form_data = prepare_form_data(form_helper.getFormData());
        $form.addClass('h-busy');
        jQuery(e.target).prop('disabled', true).addClass('h-busy');

        form_data.action = 'wsb_register_to_event';
        form_data._ajax_nonce = wsb_event.nonce;

        jQuery.ajax({
            type: 'POST',
            cache: false,
            url: wsb_event.ajax_url,
            data: form_data,
            dataType: 'json'
        }).done(function(data) {
            var wsb_ga_key = wsb_ga.google_analytics_key;

            window.scrollTo({
                top: jQuery('#wsb-success').scrollTop(),
                behavior: 'smooth'
            });
            form_helper.clearForm();
            jQuery('#wsb-success').show();
            $form.hide();
            $form.removeClass('.h-busy');

            if( wsb_ga_key != '' ){
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)
                [0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                ga('create', wsb_ga_key, 'auto') ; 

                ga('send', 'event', 'Registration Completed', 'submit');
            }
        }).fail(function(jqXHR) {
            let message = (jqXHR.responseJSON && jqXHR.responseJSON.message) ?
                jqXHR.responseJSON.message :
                'Unknown server error';
            message += ' <br> Something is wrong. This error should not happen. Check console logs or ask a support team for help';
            jQuery('[data-form-major-error]').html(message);
            console.log(jqXHR.responseJSON);
        }).always(function(data) {
            jQuery(e.target).removeProp('disabled');
            $form.removeClass('h-busy');
        });
    } else {
        console.log("Registration is closed. The plugin is configured incorrectly");
    }
}

/**
 * Returns a set of translated error messages for a form helper
 * @return {{required: *, email: *, url: *, date: *, nospace: *, digits: *}}
 */
function get_translated_error_messages() {
    return {
        required: wsb_event.error_required,
        email: wsb_event.error_email,
        url: wsb_event.error_url,
        date: wsb_event.error_date,
        nospace: wsb_event.error_nospace,
        digits: wsb_event.error_digits,
    }
}

/**
 * Removes empty values from data, sent to the server
 * @param data {object} Form data
 * @return {object}
 */
function prepare_form_data(data) {
    data.event_id = Number(wsb_event.id);
    for (var item in data) {
        if (!data[item]) delete data[item];
    }
    return data;
}

class FormHelper {
    /**
     * Validate given controls
     * @param {Object} options
     * @param {JQuery} options.$controls       - optional list of validating controls
     * @param {Object} [options.rules]           - list of rule
     * @param {Object} messages
     */
    constructor(options, messages) {
        this.$controls = options.$controls;

        this.messages = messages;
        this.rules = jQuery.extend({}, options.rules);
        this.errors = [];

        this._assignEvents();
    }

    _assignEvents() {
        this.$controls
            .on('blur', this._onBlurControl.bind(this))
            .on('input change', this._onInputControl.bind(this))
    }

    _onBlurControl(e){
        const $el = jQuery(e.currentTarget);
        this._isValidControl($el);
    }

    _onInputControl(e){
        const $control = jQuery(e.currentTarget);
        this._removeError($control);
    }

    _isValidControl($control){
        const validation = this._validateControl($control);

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
    _validateControl($control){
        const name = $control.attr('name');
        const rules = this.rules[name];
        const valueControl = this.getControlValue($control);
        let valid;

        for (let rule in rules){
            valid = this[`${rule}Validator`](valueControl, $control);

            if (!valid) return {
                isValid: false,
                message: this.messages[rule]
            };
        }

        return {
            isValid: true
        };
    }

    isValidFormData(){
        const self = this;
        let valid = true;

        this.removeErrors();
        this.$controls.each((index, control) => {
            let isValidControl  = self._isValidControl(jQuery(control));
            valid = valid && isValidControl;
        });

        return valid;
    }

    /**
     * Show or hide last error
     * @param {Boolean} condition
     * @param {jQuery} $control
     * @private
     */
    _showPreviousError(condition, $control = null){
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
        const $parent = $control.parent();
        const $error = $parent.find('.b-error');

        if ($error.length) {
            $error.text(errorText);
        } else {
            jQuery('<div class="b-error" />')
                .text(errorText)
                .appendTo($parent);
        }

        $parent.addClass('b-error_show');

        this.errors.push({
            name: $control.attr('name'),
            error: errorText
        })
    }

    _removeError($control){
        const $parent = $control.parent();

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
        let index = 0;

        errors.forEach((item) => {
            const $currentControl = this.$controls.filter('[name="' + item.name + '"]').first();

            if (!$currentControl.length) return;
            this._setError($currentControl, item.error, false);
        })
    }

    removeErrors() {
        this.$controls.each((index, el) => {
            const $el = jQuery(el);
            this._removeError($el)
        })
    }


    // Helper for form
    getFormData(){
        let formData = {};

        this.$controls.each((index, el) => {
            const $el = jQuery(el);
            const name = $el.attr('name');
            if (name && formData[name] === undefined) {
                formData[name] = this.getControlValue($el)
            }
        });

        return formData;
    }

    setFormData(formData){
        const $controls = this.$controls;

        for( let field in formData){
            if (formData.hasOwnProperty(field)){
                let $control = $controls.filter(`[name="${field}"]`).first();

                if (!$control.length) return;

                this.setControlValue($control, formData[field]);
            }
        }
    }

    /**
     * Get list of errors with full title (from control title attribute)
     * @param {ListErrors} errors - list of errors
     * @returns {string}
     */
    getErrorsFull(errors) {
        const self = this;
        const arrErrors = errors || this.errors;
        let errorTxt = '';

        arrErrors.forEach((item) => {
            const $control = self.$controls.filter(`[name="${item.name}"]`).first();
            const name = $control.length? $control.attr('title'): item.name;

            errorTxt += `<b>${name}</b>: ${item.error}  <br>`;
        });

        return errorTxt;
    }

    clearForm() {
        this.$controls.each((index, el) => {
            const $el = jQuery(el);
            if (!$el.attr("disabled"))  $el.val('');
        })
    }

    /**
     * Universal assign value
     * @param {jQuery} $control
     * @param {String|Number|Boolean} value
     */
    setControlValue($control, value){
        if ($control.is(':checkbox')){
            $control.prop('checked', value)
        } else{
            $control.val(value);
        }
    }

    /**
     * Universal get value helper
     * @param {jQuery} $control
     * @returns {String|Boolean}
     */
    getControlValue($control){
        let value = null;

        if ($control.is(':checkbox')) {
            value = $control.prop('checked');
        } else if ($control.is(':radio') && $control.prop('checked')) {
            return $control.val();
        } else {
            value = $control.val();
        }

        return value;
    }
}

jQuery(document).ready(function() {
    jQuery('#wsb-success').hide();
    get_form().on('submit', register_attendee);
});

"use strict";

/**
 * Sends GA event on registration
 */
function submit_ga_event() {
	let wsb_ga_key = wsb_ga.google_analytics_key;

	if (wsb_ga_key !== '') {
		if (typeof ga === 'function') {

			ga('create', wsb_ga_key, 'auto');
			ga('send', 'event', 'Registration Completed', 'submit');

		} else {

			(function (i, s, o, g, r, a, m) {
				i['GoogleAnalyticsObject'] = r;
				i[r] = i[r] || function () {
					(i[r].q = i[r].q || []).push(arguments)
				}, i[r].l = 1 * new Date();
				a = s.createElement(o),
					m = s.getElementsByTagName(o)[0];
				a.async = 1;
				a.src = g;
				m.parentNode.insertBefore(a, m)
			})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

			ga('create', wsb_ga_key, 'auto');
			ga('send', 'event', 'Registration Completed', 'submit');
		}
	}
}


/**
 * Creates a Stripe payment form
 *
 * @returns {{validateInputs: validateInputs, clearCardInput: clearCardInput, createPaymentMethod: createPaymentMethod, disableCardInput: (function(*=): card|void|undefined|Promise<void>|*|IDBRequest<IDBValidKey>), confirmCardPayment: confirmCardPayment, stripeClient: *}}
 */


function create_stripe_card(stripeHolderEl, publicKey, stripeAccount) {
  var x = function x(tagName, attrs) {
    if (attrs === void 0) {
      attrs = null;
    }

    var el = document.createElement(tagName);
    if (attrs !== null) Object.keys(attrs).forEach(function (k) {
      el.setAttribute(k, attrs[k]);
    });
    return el;
  };

  var options = {};

  if (stripeAccount) {
    options.stripeAccount = stripeAccount;
  }

  var cl = Stripe(publicKey, options);
  var stripeCardHolderEl = x('div', {
    'class': 'wsb-stripe-card-element'
  });
  stripeHolderEl.appendChild(stripeCardHolderEl);
  var stripeCardErrorsHolderEl = x('div', {
    'class': 'wsb-stripe-card-error'
  });
  stripeHolderEl.appendChild(stripeCardErrorsHolderEl);
  var incompleteMessage = "Your card number is incomplete.";
  var elements = cl.elements();
  var stripeCardEl = elements.create('card', {
    hidePostalCode: true,
    style: {
      base: {
        color: '#32325d',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
          color: '#aab7c4'
        }
      },
      invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
      }
    }
  });
  var inputComplete = false;
  stripeCardEl.on('change', function (e) {
    inputComplete = e.complete;

    if (e.error) {
      stripeCardErrorsHolderEl.innerHTML = e.error.message;
    } else {
      stripeCardErrorsHolderEl.innerHTML = "";
    }
  });
  stripeCardEl.mount(stripeCardHolderEl);
  return {
    stripeClient: cl,
    disableCardInput: function disableCardInput(disable) {
      return stripeCardEl.update({
        disabled: disable
      });
    },
    clearCardInput: function clearCardInput() {
      stripeCardEl.clear();
      stripeCardErrorsHolderEl.innerHTML = "";
    },
    validateInputs: function validateInputs() {
      // It is also possible to use createToken method for card validation,
      // bit it's not a proper usage and we don't know about possible side effects of it
      // cl.createToken(stripeCardEl).then((token)=>console.log(token))
      //
      if (!inputComplete) {
        stripeCardErrorsHolderEl.innerHTML = incompleteMessage;
        stripeCardEl.focus(); // not work on iOS 13+

        return false;
      }

      return true;
    },
    confirmCardPayment: function confirmCardPayment(clientSecret, _ref) {
      var payment_method = _ref.payment_method;

      if (!inputComplete) {
        return Promise.reject(incompleteMessage);
      }

      return cl.confirmCardPayment(clientSecret, {
        payment_method: {
          card: stripeCardEl,
          billing_details: payment_method ? payment_method.billing_details : {}
        }
      });
    },
    createPaymentMethod: function createPaymentMethod(_ref2) {
      var billing_details = _ref2.billing_details;

      if (!inputComplete) {
        return Promise.reject(incompleteMessage);
      }

      return cl.createPaymentMethod({
        type: 'card',
        card: stripeCardEl,
        billing_details: billing_details || {}
      });
    }
  };
}
/**
 * Returns a set of translated error messages for a form helper
 *
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
    'form.error.attendee.exist': wsb_event.error_attendee_exist,
    'api.error.data.malformed': wsb_event.string_validation_errors
  };
}

var EventRegistrationForm = /*#__PURE__*/function () {
  function EventRegistrationForm(selector) {
    this.$root = jQuery(selector);
    this.locals = this._getDom();
    this.formHelper = new FormHelper({
      $controls: this.locals.$formControls
    }, get_translated_error_messages());
    this.cardPaymentEnabled = this._initStripeCard();
    this.invoicePaymentEnabled = !this._isPaymentActive() || this._invoicePaymentAllowed();

    this._assignEvents();

    this._init();
  }
  /**
   * @private
   */


  var _proto = EventRegistrationForm.prototype;

  _proto._getDom = function _getDom() {
    var $root = this.$root;
    return {
      $formControls: $root.find('[data-control]'),
      $btnSubmit: $root.find('[type="submit"]'),
      $cardSection: $root.find('[data-card-section]'),
      $success: jQuery('#wsb-success')
    };
  };

  _proto._init = function _init() {
    this.locals.$success.hide();
    this.initPromoActivation();
    this.initActiveTicketSelection();

    this._isNotSecure();

    this._checkPaymentMethods();
  };

  _proto._checkPaymentMethods = function _checkPaymentMethods() {
    if (this.cardPaymentEnabled || this.invoicePaymentEnabled || this._isFree()) {
      return;
    }

    this.$root.addClass('wsb-form-without-payment');
    this.$root.find('[data-payment-section]').hide();
    this.formIsLocked = true;
    this.locals.$btnSubmit.prop('disabled', true);
  };

  _proto._isNotSecure = function _isNotSecure() {
    if (this._isPaymentActive() && !this._isPageSecure()) {
      this.$root.addClass('wsb-form-not-secure');

      this._getCardPaymentOption().prop('disabled', 'disabled').removeProp('selected');
    }
  };

  _proto._getCardPaymentOption = function _getCardPaymentOption() {
    return this.$root.find('[data-control][name="payment_type"] option[value="Card"]');
  }
  /**
   * Returns true if the event is free
   * @return {boolean}
   * @private
   */
  ;

  _proto._isFree = function _isFree() {
    return wsb_payment && wsb_payment.free;
  }
  /**
   * Returns true if the payment configuration is available
   * @returns boolean
   * @private
   */
  ;

  _proto._isPaymentActive = function _isPaymentActive() {
    return wsb_payment && wsb_payment.active && wsb_payment.stripe_client_id && wsb_payment.stripe_public_key;
  }
  /**
   * Returns true if it's allowed to use card payments on this page
   * @returns boolean
   * @private
   */
  ;

  _proto._isPageSecure = function _isPageSecure() {
    return window.location.href.lastIndexOf('https', 0) === 0 || wsb_payment.test;
  };

  _proto._cardPaymentAllowed = function _cardPaymentAllowed() {
    return !!this._getCardPaymentOption().length;
  };

  _proto._invoicePaymentAllowed = function _invoicePaymentAllowed() {
    return !!this.$root.find('[data-control][name="payment_type"] option[value="Invoice"]').length;
  };

  _proto._cardPaymentSelected = function _cardPaymentSelected() {
    return this.$root.find('[data-control][name="payment_type"]').first().val() === "Card";
  };

  _proto._displayCardSection = function _displayCardSection(state) {
    if (state) {
      this.locals.$cardSection.removeAttr("style");
    } else {
      this.locals.$cardSection.css("display", "none");
    }
  };

  _proto._initStripeCard = function _initStripeCard() {
    if (!(this._cardPaymentAllowed() && this._isPaymentActive() && this._isPageSecure())) return false;
    this.stripeCard = create_stripe_card(this.$root.find("#stripe-placeholder")[0], wsb_payment.stripe_public_key, wsb_payment.stripe_client_id);

    this._displayCardSection(this._cardPaymentSelected());

    return true;
  }
  /**
   * @private
   */
  ;

  _proto._assignEvents = function _assignEvents() {
    this.$root.on('change', '[data-widget-agreed]', this._onChangeAgreed.bind(this)).on('click', '[data-widget-submit]', this._onSubmitForm.bind(this)).on('change', '[data-control][name="payment_type"]', this._onChangePaymentType.bind(this));
    this.$root.on('submit', this._onSubmitForm.bind(this));
  };

  _proto._onChangeAgreed = function _onChangeAgreed(e) {
    var isAgreedForm = $(e.currentTarget).prop('checked');
    this.locals.$btnSubmit.prop('disabled', !isAgreedForm);
  };

  _proto._onChangePaymentType = function _onChangePaymentType(e) {
    console.log('Card payment selected: ' + this._cardPaymentSelected());
    if (!this.cardPaymentEnabled) return;
    this.stripeCard.clearCardInput();

    this._displayCardSection(this._cardPaymentSelected());
  };

  _proto._submitSucceed = function _submitSucceed() {
    window.scrollTo({
      top: this.locals.$success.scrollTop(),
      behavior: 'smooth'
    });
    this.locals.$success.show();
    this.$root.hide(); // clear form and errors here

    this.formHelper.clearForm();

    this._unlockFromSubmit();

    this.cardPaymentEnabled && this.stripeCard.clearCardInput();
    submit_ga_event();
  };

  _proto._submitFail = function _submitFail(message) {
    this._showSubmitError(message);

    this._unlockFromSubmit();
  };

  _proto._showSubmitError = function _showSubmitError(message) {
    this.$root.find('[data-form-major-error]').text(message || "");
  };

  _proto._lockFormSubmit = function _lockFormSubmit() {
    this.formIsLocked = true; // show preloader above button

    this.locals.$btnSubmit.find("i").css("display", "inline-block");
  };

  _proto._unlockFromSubmit = function _unlockFromSubmit() {
    this.formIsLocked = false;
    this.locals.$btnSubmit.find("i").css("display", "none");
  };

  _proto._onSubmitForm = function _onSubmitForm(e) {
    e.preventDefault();
    console.log("SUBMIT");
    if (this.formIsLocked) return; // clear message

    this._showSubmitError();

    console.log("Form submitted");

    if (!this.formHelper.isValidFormData()) {
      return;
    }

    if (this._cardPaymentSelected() && !this.cardPaymentEnabled) {
      this._showSubmitError("Payment method not allowed.");

      return;
    }

    if (this._cardPaymentSelected()) {
      this._payAndSubmitRegistration();
    } else {
      this._submitRegistration();
    }
  };

  _proto._payAndSubmitRegistration = function _payAndSubmitRegistration() {
    var self = this;
    console.log("Enter card payment flow");

    if (!self.stripeCard.validateInputs()) {
      console.log("Failed card inputs validation");
      return;
    }

    var formData = self.formHelper.getFormData();
    var url = wsb_event.ajax_url;

    self._lockFormSubmit();

    self._sendFormData(url, this._prepareFormData(formData, true)).done(function (data) {
      self._processCardPayment(url, formData, data.data.stripe_client_secret);
    }).fail(function (response) {
      self._processFailResponse(response)
    });
  };

  _proto._submitRegistration = function _submitRegistration() {
    var self = this;
    console.log("Enter simple registration flow");
    var formData = self.formHelper.getFormData();
    var registrationUrl = wsb_event.ajax_url;

    self._lockFormSubmit();

    self._sendFormData(registrationUrl, this._prepareFormData(formData, false)).done(function () {
      self._submitSucceed();
    }).fail(function (response) {
      self._processFailResponse(response);
    });
  }
  /**
   * Removes empty values from data, sent to the server
   *
   * @param data {object} Form data
   * @param preRegister {boolean} True if the call is to pre-register
   * @return {object}
   */
  ;

  _proto._prepareFormData = function _prepareFormData(data, preRegister) {
    data.action = preRegister ? 'wsb_pre_register' : 'wsb_register';
    data._ajax_nonce = wsb_event.nonce;
    data.event_id = Number(wsb_event.id);

    for (var item in data) {
      if (!data[item]) {
        delete data[item];
      }
    }

    return data;
  };

  _proto._processFailResponse = function _processFailResponse(response) {
    var data = {}

    try {
      data = JSON.parse(response.responseText);
    }
    catch(e) {
      data.message = "Can't recognize server response";
    }

    this._submitFail(data.message);

    if (!data.info) return;
    var missedErrors = this.formHelper.setErrors(data.info);
    if (missedErrors.length > 0) {
      this._submitFail(missedErrors);
    }
  };

  _proto._prepareBillingDetails = function _prepareBillingDetails(formData) {
    return {
      "address": {
        "city": formData["billing.city"] || null,
        "country": formData["billing.country"] || null,
        "line1": formData["billing.street_1"] || null,
        "line2": formData["billing.street_2"] || null,
        "postal_code": formData["billing.postcode"] || null,
        "state": formData["billing.province"] || null
      },
      "name": (formData.first_name || "") + " " + (formData.last_name || "")
    };
  };

  _proto._processCardPayment = function _processCardPayment(url, formData, clientSecret) {
    var _this = this;

    var self = this;

    self._lockFormSubmit();

    this.stripeCard.confirmCardPayment(clientSecret, {
      billing_details: self._prepareBillingDetails(formData)
    }).then(function (result) {
      if (result.error) {
        // Show error to customer (e.g., insufficient funds)
        self._submitFail(result.error.message);

        return;
      } // The payment has been processed!


      if (result.paymentIntent && result.paymentIntent.status !== 'succeeded') {
        self._submitFail(result.paymentIntent.status);

        return;
      }

      formData['intent_id'] = result.paymentIntent.id;

      self._sendFormData(url, _this._prepareFormData(formData, false)).done(function () {
        self._submitSucceed();
      }).fail(function (response) {
        // it shouldn't happen in any case
        self._submitFail("Due to unexpected reason we can't complete the registration. " + "Please contact our support to resolve it manually");
      });
    });
  };

  _proto.initActiveTicketSelection = function initActiveTicketSelection() {
    var _this2 = this;

    var tickets = this.$root.find('#wsb-tickets input');
    tickets.on('change', function () {
      _this2.toggleTicket(tickets.not(':checked'), false);

      _this2.toggleTicket(tickets.filter(':checked'), true);
    });

    if (tickets.length > 0) {
      var activeTicket = tickets.first();
      this.toggleTicket(activeTicket, true);
    }
  };

  _proto.initPromoActivation = function initPromoActivation() {
    var _this3 = this;

    this.$root.find('[data-promo-link]').on('click', function (e) {
      e.preventDefault();

      _this3.$root.find('[data-promo-code]').toggle();
    });
  }
  /**
   * Switches active ticket
   * @param tickets {JQuery<HTMLElement>}
   * @param on {boolean}
   */
  ;

  _proto.toggleTicket = function toggleTicket(tickets, on) {
    var name = 'wsb-active';

    if (on) {
      tickets.prop('checked', 'true');
      tickets.parent().addClass(name);
    } else {
      tickets.removeProp('checked');
      tickets.parent().removeClass(name);
    }
  } // transport
  ;

  _proto._sendFormData = function _sendFormData(url, data) {
    data._ajax_nonce = wsb_event.nonce;
    return $.ajax({
      url: url,
      data: data,
      method: 'POST',
      dataType: 'json'
    });
  };

  EventRegistrationForm.plugin = function plugin(selector) {
    var $elems = jQuery(selector);
    if (!$elems.length) return;
    return $elems.each(function (index, el) {
      var $element = $(el);
      var data = $element.data('widget.server.detail');

      if (!data) {
        data = new EventRegistrationForm(el);
        $element.data('widget.server.detail', data);
      }
    });
  };

  return EventRegistrationForm;
}();

var FormHelper = /*#__PURE__*/function () {
  /**
   * Validate given controls
   *
   * @param {Object} options
   * @param {JQuery} options.$controls       - optional list of validating controls
   * @param {Object} [options.rules]           - list of rule
   * @param {Object} messages
   */
  function FormHelper(options, messages) {
    this.$controls = options.$controls;
    this.messages = messages;
    this.rules = jQuery.extend({}, options.rules);
    this.errors = [];

    this._assignEvents();
  }

  var _proto2 = FormHelper.prototype;

  _proto2._assignEvents = function _assignEvents() {
    this.$controls.on('blur', this._onBlurControl.bind(this)).on('input change', this._onInputControl.bind(this));
  };

  _proto2._onBlurControl = function _onBlurControl(e) {
    var $el = jQuery(e.currentTarget);

    this._isValidControl($el);
  };

  _proto2._onInputControl = function _onInputControl(e) {
    var $control = jQuery(e.currentTarget);

    this._removeError($control);
  };

  _proto2._isValidControl = function _isValidControl($control) {
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
   *
   * @param {jQuery} $control - element
   * @returns {Object} = isValid(Boolean), message(String)
   * @private
   */
  ;

  _proto2._validateControl = function _validateControl($control) {
    var name = $control.attr('name');
    var rules = this.rules[name];
    var valueControl = this.getControlValue($control);
    var valid;

    for (var rule in rules) {
      valid = this[rule + "Validator"](valueControl, $control);
      if (!valid) return {
        isValid: false,
        message: this.messages[rule]
      };
    }

    return {
      isValid: true
    };
  };

  _proto2.isValidFormData = function isValidFormData() {
    var self = this;
    var valid = true;
    this.removeErrors();
    this.$controls.each(function (index, control) {
      var isValidControl = self._isValidControl(jQuery(control));

      valid = valid && isValidControl;
    });
    return valid;
  }
  /**
   * Show or hide last error
   *
   * @param {Boolean} condition
   * @param {jQuery} $control
   * @private
   */
  ;

  _proto2._showPreviousError = function _showPreviousError(condition, $control) {
    if ($control === void 0) {
      $control = null;
    }

    if (this.$inputWithError) {
      this.$inputWithError.parent().toggleClass('b-error_state_high', !condition).toggleClass('b-error_state_error', condition);
    }

    this.$inputWithError = $control;
  }
  /**
   * Set error for control
   *
   * @param {jQuery} $control
   * @param {String} error
   * @param {Boolean} showBubble
   */
  ;

  _proto2._setError = function _setError($control, error, showBubble) {
    if (showBubble === void 0) {
      showBubble = true;
    }

    var $parent = $control.parent();
    var $error = $parent.find('.b-error');
    var errorText = this.messages[error] ? this.messages[error] : error;

    if ($error.length) {
      $error.text(errorText);
    } else {
      jQuery('<div class="b-error" />').text(errorText).appendTo($parent);
    }

    $parent.addClass('b-error_show');
    this.errors.push({
      name: $control.attr('name'),
      error: error
    });
  };

  _proto2._removeError = function _removeError($control) {
    var $parent = $control.parent();
    $parent.removeClass('b-error_show');
    this.errors = this.errors.filter(function (item) {
      return item.name !== $control.attr('name');
    });
  }
  /**
   * Set errors
   *
   * @param {Array} errors - [{name: "email", error: "empty"}, {name: "password", error: "empty"}]
   */
  ;

  _proto2.setErrors = function setErrors(errors) {
    this.$inputWithError = null;
    var missedErrors = [];

    for (var key in errors) {
      var $currentControl = this.$controls.filter('[name="' + key + '"]').first();

      if (!$currentControl.length) {
        const error = errors[key];
        const errorText = this.messages[error] ? this.messages[error] : error;
        missedErrors.push(`${key}: ${errorText}`);
        continue;
      }

      this._setError($currentControl, errors[key], false);
    }
    return missedErrors;
  };

  _proto2.removeErrors = function removeErrors() {
    var _this4 = this;

    this.$controls.each(function (index, el) {
      var $el = jQuery(el);

      _this4._removeError($el);
    });
  } // Helper for form
  ;

  _proto2.getFormData = function getFormData() {
    var _this5 = this;

    var formData = {};
    this.$controls.each(function (index, el) {
      var $el = jQuery(el);
      var name = $el.attr('name');

      if (name && formData[name] === undefined) {
        formData[name] = _this5.getControlValue($el);
      }
    });
    return formData;
  };

  _proto2.setFormData = function setFormData(formData) {
    var $controls = this.$controls;

    for (var field in formData) {
      if (formData.hasOwnProperty(field)) {
        var $control = $controls.filter("[name=\"" + field + "\"]").first();

        if (!$control.length) {
          return;
        }

        this.setControlValue($control, formData[field]);
      }
    }
  }
  /**
   * Get list of errors with full title (from control title attribute)
   *
   * @param {ListErrors} errors - list of errors
   * @returns {string}
   */
  ;

  _proto2.getErrorsFull = function getErrorsFull(errors) {
    var self = this;
    var arrErrors = errors || this.errors;
    var errorTxt = '';
    arrErrors.forEach(function (item) {
      var $control = self.$controls.filter("[name=\"" + item.name + "\"]").first();
      var name = $control.length ? $control.attr('title') : item.name;
      errorTxt += "<b>" + name + "</b> : " + item.error + " <br>";
    });
    return errorTxt;
  };

  _proto2.clearForm = function clearForm() {
    this.$controls.each(function (index, el) {
      var $el = jQuery(el);

      if (!$el.attr("disabled")) {
        $el.val('');
      }
    });
  }
  /**
   * Universal assign value
   *
   * @param {jQuery} $control
   * @param {String|Number|Boolean} value
   */
  ;

  _proto2.setControlValue = function setControlValue($control, value) {
    if ($control.is(':checkbox')) {
      $control.prop('checked', value);
    } else {
      $control.val(value);
    }
  }
  /**
   * Universal get value helper
   *
   * @param {jQuery} $control
   * @returns {String|Boolean}
   */
  ;

  _proto2.getControlValue = function getControlValue($control) {
    if ($control.is(':checkbox')) {
      return $control.prop('checked');
    } else if ($control.is(':radio')) {
      if ($control.prop('checked')) {
        return $control.val();
      } else {
        return undefined;
      }
    } else {
      return $control.val();
    }
  };

  return FormHelper;
}();

jQuery(document).ready(function () {
  EventRegistrationForm.plugin('#wsb-form');
});

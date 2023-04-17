"use strict";

/**
 * Sends GA event on registration
 */
function submitGaEvent() {
	const wsb_ga_key = wsb_ga.google_analytics_key;

	if (!wsb_ga_key) {
    return;
  }

  if (typeof ga === 'function') {
    ga('create', wsb_ga_key, 'auto', 'wsbIntegration');
    ga('wsbIntegration.send', 'event', 'Registration Completed', 'submit');
    return;
  }

  // check if google tag manager is initialized
  if (typeof window.dataLayer !== 'object') {
    return;
  }

  if (typeof gtag !== 'function') {
    function gtag(){dataLayer.push(arguments);}
  }

  gtag('event', 'submit', {
    'send_to': wsb_ga_key,
    'event_category': 'Registration Completed'
  });

}

/**
 * Logs the error in a correct format to the console
 *
 */
function logInfo(msg) {
  console.log('Workshop Butler INFO: ', msg);
}

/**
 * Creates a Stripe payment form
 *
 */
function createStripeCard(stripeHolderEl, publicKey, stripeAccount) {
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

function createPayPalButton(selector, registrationForm) {
  window.paypal.Buttons({
    // Button style
    style: {
      color: 'blue',
      shape: 'pill',
      label: 'pay',
      height: 50
    },
    // Set up the transaction
    createOrder: function createOrder(data, actions) {
      var ticket = registrationForm.getTotalAmount();
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: ticket.amount,
            // it doesn't change the selected currency, but works like assert
            currency_code: ticket.currency
          }
        }]
      });
    },
    // Finalize the transaction
    onApprove: function onApprove(data, actions) {
      return actions.order.capture().then(function (details) {
        registrationForm.showSubmitError('Payment has been accepted. Completing the registration...'); // Complete registration

        registrationForm.submitRegistration();
      });
    },
    onInit: function onInit(data, actions) {
      actions.disable();
      registrationForm.root[0].addEventListener('change', function (event) {
        if (registrationForm.root[0].checkValidity()) {
          actions.enable();
        } else {
          actions.disable();
        }
      });
    },
    onClick: function onClick(data, actions) {
      if (!registrationForm.root[0].checkValidity()) {
        registrationForm.root[0].reportValidity();
        return false;
      } // validate pre-registration here

      var formData = registrationForm.formHelper.getFormData();
      var url = wsb_event.ajax_url;

      return new Promise(function (resolve, reject) {
        registrationForm._sendFormData(url, registrationForm._prepareFormData(formData, true)).done(
            function (data) {
              resolve(actions.resolve());
          }).fail(function (response) {
            registrationForm.processFailResponse(response)
            resolve(actions.reject());
          });
      });
    },
    onCancel: function onCancel(data) {
      registrationForm.showSubmitError('Payment has been canceled');
    },
    onError: function onError(err) {
      registrationForm.showSubmitError('Checkout error');
    }
  }).render(selector);
}

/**
 * Returns a set of translated error messages for a form helper
 *
 * @return {{required: *, email: *, url: *, date: *, nospace: *, digits: *}}
 */
function getTranslatedErrorMessages() {
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

var TaxWidget = /*#__PURE__*/function () {
  function TaxWidget($el, taxExemptCallback, eventHashedId) {
    if (!$el.length) {
      this.enabled = false;
      return this;
    }
    this.enabled = true;
    this.root = $el;
    this.taxExemptCallback = taxExemptCallback;
    this.eventHashedId = eventHashedId;
    this.message = $el.find('[data-tax-widget-message]');
    this.applyButton = $el.find('[data-tax-widget-apply]');
    this.clearButton = $el.find('[data-tax-widget-clear]');
    this.input = $el.find('[data-tax-widget-value]');
    this.intentId = $el.find('[data-tax-intent-id]');
    this.resetOnChange = false;
    this.reset();
    this.activateEvents();
  }

  var _proto = TaxWidget.prototype;

  _proto.reset = function reset(resetInput) {
    if (resetInput === void 0) {
      resetInput = true;
    }

    this.renderApplyButton(true);
    this.renderClearButton(false);

    if (resetInput) {
      this.input.val('');
    }

    this.message.hide('fast');
    this.intentId.val('');
    this.resetOnChange = false;
    this.taxExemptCallback(false);
  };

  _proto.activateEvents = function activateEvents() {
    this.applyButton.on('click', this.onApplyClick.bind(this));
    this.clearButton.on('click', this.onClearClick.bind(this));
    this.input.on('input', this.onChangeValue.bind(this));
  };

  _proto.onApplyClick = function onApplyClick() {
    if (this.applyButton.hasClass('disabled')) {
      return;
    }

    this.apply(this.input.val());
  };

  _proto.onClearClick = function onClearClick() {
    if (this.clearButton.hasClass('disabled')) {
      return;
    }

    this.reset(true);
  };

  _proto.onChangeValue = function onChangeValue() {
    if (!this.resetOnChange) {
      return;
    }

    this.reset(false);
  };

  _proto.renderClearButton = function renderClearButton(enabled) {
    if (enabled === void 0) {
      enabled = true;
    }

    this.clearButton.toggleClass('disabled', !enabled);
  };

  _proto.renderApplyButton = function renderApplyButton(enabled) {
    if (enabled === void 0) {
      enabled = true;
    }

    this.applyButton.toggleClass('disabled', !enabled);
  };

  _proto.renderMessage = function renderMessage(type, text) {
    this.message.text(text);
    this.message.attr('class', type + '-message');
    this.message.show('fast');
  };

  _proto.apply = function apply(taxNumber) {
    var _this = this;

    if (!taxNumber) {
      return;
    }

    this.renderApplyButton(false);
    this.renderClearButton(true);
    this.resetOnChange = true;

    var validationData = {
      _ajax_nonce: wsb_event.nonce,
      action: 'wsb_tax_validation',
      number: taxNumber,
    };

    if (this.eventHashedId) {
      validationData.eventId = this.eventHashedId;
    }

    jQuery.ajax({
      url: wsb_event.ajax_url,
      data: validationData,
      method: 'GET',
      dataType: 'json'
    })
    .done(function (data) {
      _this.processOkResponse(data);
    })
    .fail(function (response) {
      _this.processFailResponse(response);
    });
  };

  _proto.processOkResponse = function processOkResponse(response) {
    if (!this.resetOnChange) {
      // FIXME: potential race condition may happen here
      return;
    }

    var data = response.data;

    logInfo(data);
    this.intentId.val(data.tax_intent_id);
    this.renderMessage(data.message_type, data.message_text);
    this.taxExemptCallback(data.tax_exempt);
  };

  _proto.processFailResponse = function processFailResponse(response) {
    logInfo(response);
    this.reset(false);
  };

  return TaxWidget;
}();

var EventRegistrationForm = /*#__PURE__*/function () {
  function EventRegistrationForm(selector) {
    this.root = jQuery(selector);
    this.formHelper = new FormHelper(
      { $controls: this.root.find('[data-control]')}, getTranslatedErrorMessages());
    this.cardSection = this.root.find('[data-card-section]');
    this.submitBtn = this.root.find('[type="submit"]');
    this.successMessage = jQuery('#wsb-success');
    this.stripeCard = null;
    this.cardPaymentEnabled = this.initStripeCard();
    this.payPalPaymentEnabled = this.initPayPal();
    this.invoicePaymentEnabled = !this.isCardPaymentActive() || this.invoicePaymentAllowed();
    this.formIsLocked = false;
    this.taxExempt = false;
    this.billingEU = true;
    this.activateEvents();
    this.init();
  }
  /**
   * @private
   */


  var _proto = EventRegistrationForm.prototype;

  _proto.init = function init() {
    this.successMessage.hide();
    this.initPromoActivation();
    this.initTaxApplication();
    this.initActiveTicketSelection();
    this.deactivateCardPayment();
    this.lockIfNoPaymentMethod();
  };

  _proto.activateEvents = function activateEvents() {
    this.root
      .on('click', '[data-widget-submit]', this.onSubmitForm.bind(this))
      .on('change', '[data-control][name="payment_type"]', this.onChangePaymentType.bind(this));
    this.root.on('submit', this.onSubmitForm.bind(this));
  }

  _proto.initStripeCard = function initStripeCard() {
    if (!(this.cardPaymentAllowed() && this.isCardPaymentActive() && this.isPageSecure())) {
      return false;
    }

    this.stripeCard = createStripeCard(this.root.find("#stripe-placeholder")[0], wsb_payment.stripe_public_key, wsb_payment.stripe_client_id);
    this.displayCardSection(this.cardPaymentSelected());
    return true;
  }

  _proto.initPayPal = function initPayPal() {
    var self = this;

    if (!(this.isPayPalActive() && this.payPalPaymentAllowed())) {
      return false;
    } // here we are guessing currency by the selected ticket

    var currency = this.getTotalAmount().currency;
    var el = document.createElement('script');
    el.setAttribute('src', "https://www.paypal.com/sdk/js?currency=" + currency + "&client-id=" + wsb_paypal_payment.client_id);
    el.addEventListener('load', function () {
      return createPayPalButton('#paypal-button-container', self);
    });
    document.head.appendChild(el);
    this.displayPayPalButton(this.payPalPaymentSelected());
    return true;
  }

  _proto.deactivateCardPayment = function deactivateCardPayment() {
    if (this.isCardPaymentActive() && !this.isPageSecure()) {
      this.root.addClass('wsb-form-not-secure');
      this.root.find('[data-control]select[name="payment_type"] option[value="Card"]').prop('disabled', 'disabled').removeProp('selected');
      this.root.find('[data-control]input[name="payment_type"][value="Card"]').prop('disabled', 'disabled').removeProp('checked');
    }
  }

  _proto.lockIfNoPaymentMethod = function lockIfNoPaymentMethod() {
    if (this.cardPaymentEnabled || this.invoicePaymentEnabled || this.payPalPaymentEnabled || this._isFree()) {
      return;
    }

    this.root.addClass('wsb-form-without-payment');
    this.root.find('[data-payment-section]').hide();
    this.formIsLocked = true;
    this.submitBtn.prop('disabled', true);
  }

  _proto._isFree = function _isFree() {
    return wsb_payment && wsb_payment.free;
  }


  _proto.cardPaymentAllowed = function cardPaymentAllowed() {
    var paymentTypeSelector = '[data-control][name="payment_type"]'; // check both radio and select variants

    return !!(this.root.find(paymentTypeSelector + ' option[value="Card"]').length || this.root.find(paymentTypeSelector + '[value="Card"]').length);
  }

  _proto.isPageSecure = function isPageSecure() {
    return window.location.href.lastIndexOf('https', 0) === 0 || wsb_payment.test;
  }

  _proto.isCardPaymentActive = function isCardPaymentActive() {
    return wsb_payment && wsb_payment.active && wsb_payment.stripe_client_id && wsb_payment.stripe_public_key;
  }

  _proto.isPayPalActive = function isPayPalActive() {
    return wsb_paypal_payment && wsb_paypal_payment.client_id !== undefined;
  }

  _proto.invoicePaymentAllowed = function invoicePaymentAllowed() {
    var paymentTypeSelector = '[data-control][name="payment_type"]'; // check both radio and select variants

    return !!(this.root.find(paymentTypeSelector + ' option[value="Invoice"]').length || this.root.find(paymentTypeSelector + '[value="Invoice"]').length);
  }

  _proto.payPalPaymentAllowed = function payPalPaymentAllowed() {
    var paymentTypeSelector = '[data-control][name="payment_type"]'; // check both radio and select variants

    return !!(this.root.find(paymentTypeSelector + ' option[value="PayPal"]').length || this.root.find(paymentTypeSelector + '[value="PayPal"]').length);
  }

  _proto.cardPaymentSelected = function cardPaymentSelected() {
    return !!this.root.find('[data-control][name="payment_type"] option[value="Card"]:checked').length;
  }

  _proto.payPalPaymentSelected = function payPalPaymentSelected() {
    return !!this.root.find('[data-control][name="payment_type"] option[value="PayPal"]:checked').length;
  }

  _proto.displayCardSection = function displayCardSection(state) {
    if (state) {
      this.cardSection.removeAttr('style');
    } else {
      this.cardSection.css('display', 'none');
    }
  }

  _proto.displayPayPalButton = function displayPayPalButton(state) {
    if (state) {
      this.root.find('#paypal-button-container').removeAttr('style');
      this.root.find('#default-submit-button').css('display', 'none');
    } else {
      this.root.find('#default-submit-button').removeAttr('style');
      this.root.find('#paypal-button-container').css('display', 'none');
    }
  }

  _proto.onChangePaymentType = function onChangePaymentType() {
    if (this.cardPaymentEnabled) {
      this.stripeCard.clearCardInput();
      this.displayCardSection(this.cardPaymentSelected());
    }

    if (this.payPalPaymentEnabled) {
      this.displayPayPalButton(this.payPalPaymentSelected());
    }
  }

  _proto.onSubmitForm = function onSubmitForm(e) {
    e.preventDefault();

    if (this.formIsLocked) {
      return;
    } // clear message


    this.showSubmitError('');

    if (!this.formHelper.isValidFormData()) {
      return;
    }

    if (this.payPalPaymentSelected() || this.cardPaymentSelected() && !this.cardPaymentEnabled) {
      this.showSubmitError('Payment method not allowed');
      return;
    }

    if (this.cardPaymentSelected()) {
      this.payAndSubmitRegistration();
    } else {
      this.submitRegistration();
    }
  }

  _proto.showSubmitError = function showSubmitError(message) {
    this.root.find('[data-form-major-error]').text(message || '');
  }

  _proto._sendFormData = function _sendFormData(url, data) {
    data._ajax_nonce = wsb_event.nonce;
    return jQuery.ajax({
      url: url,
      data: data,
      method: 'POST',
      dataType: 'json'
    });
  };

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

  _proto.payAndSubmitRegistration = function payAndSubmitRegistration() {
    var self = this;

    logInfo('Enter card payment flow');

    if (!self.stripeCard.validateInputs()) {
      logInfo('Failed card inputs validation');
      return;
    }

    var formData = self.formHelper.getFormData();
    var url = wsb_event.ajax_url;
    this.lockFormSubmit();


    self._sendFormData(url, this._prepareFormData(formData, true)).done(function (data) {
      var stripeClientSecret = data && data.data && data.data.stripe_client_secret;
      if(!stripeClientSecret){
        self.submitFail("Can't initiate payment");
        return;
      }
      self.processCardPayment(url, formData, stripeClientSecret);
    }).fail(function (response) {
      self.processFailResponse(response)
    });
  }

  _proto.lockFormSubmit = function lockFormSubmit() {
    this.formIsLocked = true; // show preloader above button

    this.submitBtn.find('i').css('display', 'inline-block');
  }

  _proto.unlockFromSubmit = function unlockFromSubmit() {
    this.formIsLocked = false;
    this.submitBtn.find('i').css('display', 'none');
  }


  _proto.getTotalAmount = function getTotalAmount() {
    var ticket = this.root.find('[name="ticket"]:checked');
    return {
      amount: ticket.data('amount') + (this.taxExempt ? 0 : ticket.data('tax') || 0),
      currency: ticket.data('currency')
    };
  }

  _proto.processCardPayment = function processCardPayment(url, formData, clientSecret) {
    var self = this;

    this.lockFormSubmit();
    this.stripeCard.confirmCardPayment(clientSecret, {
      billing_details: this.prepareBillingDetails(formData)
    }).then(function (result) {
      if (result.error) {
        // Show error to customer (e.g., insufficient funds)
        self.submitFail(result.error.message);
        return;
      } // The payment has been processed!


      if (result.paymentIntent && result.paymentIntent.status !== 'succeeded') {
        self.submitFail(result.paymentIntent.status);
        return;
      }

      formData.intent_id = result.paymentIntent.id;
      self._sendFormData(url, self._prepareFormData(formData, false)).done(function () {
        self.submitSucceed();
      }).fail(function (response) {
        // it shouldn't happen in any case
        self.submitFail("Due to unexpected reason we can't complete the registration. " + "Please contact our support to resolve it manually");
      });
    });
  }

  _proto.prepareBillingDetails = function prepareBillingDetails(formData) {
    return {
      address: {
        city: formData['billing.city'] || null,
        country: formData['billing.country'] || null,
        line1: formData['billing.street_1'] || null,
        line2: formData['billing.street_2'] || null,
        postal_code: formData['billing.postcode'] || null,
        state: formData['billing.province'] || null
      },
      name: (formData.first_name || '') + '' + (formData.last_name || '')
    };
  }

  _proto.submitFail = function submitFail(message) {
    this.showSubmitError(message);
    this.unlockFromSubmit();
  }

  _proto.submitSucceed = function _submitSucceed() {
    window.scrollTo({
      top: this.successMessage.scrollTop(),
      behavior: 'smooth'
    });
    this.successMessage.show();
    this.root.hide(); // clear form and errors here

    this.formHelper.clearForm();

    this.unlockFromSubmit();

    this.cardPaymentEnabled && this.stripeCard.clearCardInput();
    submitGaEvent();
  };


  _proto.submitRegistration = function submitRegistration() {
    var self = this;

    logInfo('Enter simple registration flow');
    var formData = self.formHelper.getFormData();
    var registrationUrl = wsb_event.ajax_url;
    this.lockFormSubmit();

    self._sendFormData(registrationUrl, this._prepareFormData(formData, false)).done(function () {
      self.submitSucceed();
    }).fail(function (response) {
      self.processFailResponse(response);
    });
  }

  _proto.processFailResponse = function _processFailResponse(response) {
    var data = {}

    try {
      data = JSON.parse(response.responseText);
    }
    catch(e) {
      data.message = "Can't recognize server response";
    }

    this.submitFail(data.message);

    if (!data.info) return;
    var missedErrors = this.formHelper.setErrors(data.info);
    if (missedErrors.length > 0) {
      this.submitFail(missedErrors);
    }
  };

  _proto.initPromoActivation = function initPromoActivation() {
    var self = this;

    this.root.find('[data-promo-link]').on('click', function (e) {
      e.preventDefault();

      self.root.find('[data-promo-code]').toggle();
    });
  };

  _proto.initTaxApplication = function initTaxApplication() {
    var self = this;

    this.root.find('[data-vat-apply-link]').on('click', function () {
      self.root.find('[data-tax-description]').hide('fast');
      self.root.find('#wsb-form-tax-widget').show('fast');
    });
    this.taxWidget = new TaxWidget(
      this.root.find('#wsb-form-tax-widget'),
      this.applyTaxExempt.bind(this),
      wsb_event.hashed_id
    );
    if (this.taxWidget.enabled) {
      this.root.find('[data-control]select[name="billing.country"]').on('change', this.onChangeBillingCountry.bind(this));
    }

  };

  _proto.initActiveTicketSelection = function initActiveTicketSelection() {
    var _this2 = this;

    var tickets = this.root.find('#wsb-tickets input');
    tickets.on('change', function () {
      _this2.toggleRadio(tickets.not(':checked'), false);
      _this2.toggleRadio(tickets.filter(':checked'), true);
    });

    if (tickets.length > 0) {
      var activeTicket = tickets.first();
      this.toggleRadio(activeTicket, true);
    }
  };

  _proto.toggleRadio = function toggleRadio(tickets, on) {
    var name = 'wsb-active';

    if (on) {
      tickets.prop('checked', 'true');
      tickets.parent().addClass(name);
    } else {
      tickets.removeProp('checked');
      tickets.parent().removeClass(name);
    }
  };

  _proto.applyTaxExempt = function applyTaxExempt(exempt) {
    this.taxExempt = exempt;
    if(exempt) {
      this.root.find('.wsb-ticket__tax').css('display', 'none');
    } else {
      this.root.find('.wsb-ticket__tax').removeAttr('style');
    }
  };

  _proto.onChangeBillingCountry = function onChangeBillingCountry(e) {
    const euCountries = wsb_event.eu_countries;
    const country = jQuery(e.target).val();
    logInfo('change country '+country);
    if (euCountries.includes(country)) {
      if (!this.billingEU) {
        this.applyTaxExempt(false);
        this.root.find('#wsb-form__billing-message')
        .text('Additional VAT has been applied. See ticket section for more details.').show(150).delay(5000).hide(150);
      }
      this.billingEU = true;
    } else {
      if (this.billingEU) {
        this.taxWidget.reset();
        this.root.find('#wsb-form__billing-message').text('VAT has been excluded').show(150).delay(5000).hide(150);
      }
      this.applyTaxExempt(true);
      this.billingEU = false;
    }
  }

  EventRegistrationForm.plugin = function plugin(selector) {
    var $elems = jQuery(selector);
    if (!$elems.length) return;
    return $elems.each(function (index, el) {
      var $element = jQuery(el);
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
        missedErrors.push(key+': '+errorText);
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
    var formData = {};
    this.$controls.each(function (index, el) {
      var $el = jQuery(el);
      var name = $el.attr('name');

      if (!(name && formData[name] === undefined)) {
        return;
      }

      if ($el.is(':checkbox')) {
        formData[name] = $el.prop('checked');
      } else if ($el.is(':radio')) {
        if ($el.prop('checked')) {
          formData[name] = $el.val();
        }
      } else {
        formData[name] = $el.val();
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

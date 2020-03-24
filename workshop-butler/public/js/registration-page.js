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
	const x = function (tagName, attrs = null) {
		const el = document.createElement(tagName);
		if (attrs !== null) Object.entries(attrs).forEach((e) => el.setAttribute(e[0], e[1]));
		return el;
	};

	const options = {};
	if (stripeAccount) {
		options.stripeAccount = stripeAccount;
	}

	const cl = Stripe(publicKey, options);

	const stripeCardHolderEl = x('div', {'class': 'wsb-stripe-card-element'});
	stripeHolderEl.appendChild(stripeCardHolderEl);
	const stripeCardErrorsHolderEl = x('div', {'class': 'wsb-stripe-card-error'});
	stripeHolderEl.appendChild(stripeCardErrorsHolderEl);
	const incompleteMessage = "Your card number is incomplete.";

	const elements = cl.elements();
	const stripeCardEl = elements.create('card', {
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

	let inputComplete = false;
	stripeCardEl.on('change', (e) => {
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
		disableCardInput: (disable) => stripeCardEl.update({disabled:disable}),
		clearCardInput: () => {
			stripeCardEl.clear();
			stripeCardErrorsHolderEl.innerHTML = ""
		},
		validateInputs: () => {
			// It is also possible to use createToken method for card validation,
			// bit it's not a proper usage and we don't know about possible side effects of it
			// cl.createToken(stripeCardEl).then((token)=>console.log(token))
			//
			if(!inputComplete) {
				stripeCardErrorsHolderEl.innerHTML = incompleteMessage;
				stripeCardEl.focus(); // not work on iOS 13+
				return false;
			}
			return true;
		},
		confirmCardPayment: (clientSecret, {payment_method}) => {
			if (!inputComplete) {
				return Promise.reject(incompleteMessage);
			}
			return cl.confirmCardPayment(clientSecret, {
				payment_method: {
					card: stripeCardEl,
					billing_details: payment_method ? payment_method.billing_details : {},
				},
			})
		},
		createPaymentMethod: ({billing_details}) => {
			if (!inputComplete) {
				return Promise.reject(incompleteMessage);
			}
			return cl.createPaymentMethod({
				type: 'card',
				card: stripeCardEl,
				billing_details: billing_details || {}
			})
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
	}
}

class EventRegistrationForm {

	constructor(selector) {
		this.$root = jQuery(selector);
		this.locals = this._getDom();
		this.formHelper = new FormHelper({
				$controls: this.locals.$formControls,
			}, get_translated_error_messages()
		);

		this.cardPaymentEnabled = this._initStripeCard();
		this.invoicePaymentEnabled = !this._isPaymentActive() || this._invoicePaymentAllowed();

		this._assignEvents();
		this._init();
	}

	/**
	 * @private
	 */
	_getDom() {
		const $root = this.$root;

		return {
			$formControls: $root.find('[data-control]'),
			$btnSubmit: $root.find('[type="submit"]'),
			$cardSection: $root.find('[data-card-section]'),
			$success: jQuery('#wsb-success'),
		};
	}

	_init() {
		this.locals.$success.hide();
		this.initPromoActivation();
		this.initActiveTicketSelection();
		this._isNotSecure();
		this._checkPaymentMethods();
	}

	_checkPaymentMethods() {
		if (this.cardPaymentEnabled || this.invoicePaymentEnabled) {
			return;
		}
		this.$root.addClass('wsb-form-without-payment');
		this.$root.find('[data-payment-section]').hide();
		this.formIsLocked = true;
		this.locals.$btnSubmit.prop('disabled', true);
	}

	_isNotSecure() {
		if (this._isPaymentActive() && !this._isPageSecure()) {
			this.$root.addClass('wsb-form-not-secure');
			this._getCardPaymentOption().prop('disabled', 'disabled').removeProp('selected');
		}
	}

	_getCardPaymentOption() {
		return this.$root.find('[data-control][name="payment_type"] option[value="Card"]');
	}

	/**
	 * Returns true if the payment configuration is available
	 * @returns boolean
	 * @private
	 */
	_isPaymentActive() {
		return wsb_payment && wsb_payment.active && wsb_payment.stripe_client_id && wsb_payment.stripe_public_key;
	}

	/**
	 * Returns true if it's allowed to use card payments on this page
	 * @returns boolean
	 * @private
	 */
	_isPageSecure() {
		return wsb_payment.secure || wsb_payment.test;
	}

	_cardPaymentAllowed() {
		return !!this._getCardPaymentOption().length;
	}

	_invoicePaymentAllowed() {
		return !!this.$root.find('[data-control][name="payment_type"] option[value="Invoice"]').length;
	}

	_cardPaymentSelected() {
		return this.$root.find('[data-control][name="payment_type"]').first().val() === "Card";
	}

	_displayCardSection(state) {
		if (state) {
			this.locals.$cardSection.removeAttr("style")
		} else {
			this.locals.$cardSection.css("display", "none");
		}
	}

	_initStripeCard() {

		if (!(
			this._cardPaymentAllowed()
			&& this._isPaymentActive()
			&& this._isPageSecure())
		) return false;

		this.stripeCard = create_stripe_card(
			this.$root.find("#stripe-placeholder")[0],
			wsb_payment.stripe_public_key,
			wsb_payment.stripe_client_id);

		this._displayCardSection(this._cardPaymentSelected());

		return true;
	}

    /**
     * @private
     */
	_assignEvents() {
		this.$root
			.on('change', '[data-widget-agreed]', this._onChangeAgreed.bind(this))
			.on('click', '[data-widget-submit]', this._onSubmitForm.bind(this))
			.on('change', '[data-control][name="payment_type"]', this._onChangePaymentType.bind(this));

		this.$root.on('submit', this._onSubmitForm.bind(this));
	}

	_onChangeAgreed(e) {
		const isAgreedForm = $(e.currentTarget).prop('checked');
		this.locals.$btnSubmit.prop('disabled', !isAgreedForm);
	}

	_onChangePaymentType(e) {
		console.log('Card payment selected: ' + this._cardPaymentSelected());
		if (!this.cardPaymentEnabled) return;
		this.stripeCard.clearCardInput();
		this._displayCardSection(this._cardPaymentSelected());
	}

	_submitSucceed() {
		window.scrollTo(
			{
				top: this.locals.$success.scrollTop(),
				behavior: 'smooth'
			}
		);
		this.locals.$success.show();
		this.$root.hide();
		// clear form and errors here
		this.formHelper.clearForm();
		this._unlockFromSubmit();
		this.cardPaymentEnabled && this.stripeCard.clearCardInput();
		submit_ga_event();
	}

	_submitFail(message) {
		this._showSubmitError(message);
		this._unlockFromSubmit();
	}

	_showSubmitError(message) {
		this.$root.find('[data-form-major-error]').text(message || "");
	}

	_lockFormSubmit() {
		this.formIsLocked = true;
		// show preloader above button
		this.locals.$btnSubmit.find("i").css("display", "inline-block");
	}

	_unlockFromSubmit() {
		this.formIsLocked = false;
		this.locals.$btnSubmit.find("i").css("display", "none");
	}

	_onSubmitForm(e) {
		e.preventDefault();
		console.log("SUBMIT");

		if (this.formIsLocked) return;

		// clear message
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
	}

	_payAndSubmitRegistration() {
		const self = this;
		console.log("Enter card payment flow");

		if (!self.stripeCard.validateInputs()) {
			console.log("Failed card inputs validation");
			return;
		}

		const formData = self.formHelper.getFormData();
		const url = wsb_event.ajax_url;

		self._lockFormSubmit();
		self._sendFormData(url, this._prepareFormData(formData, true))
			.done((data) => {
				self._processCardPayment(url, formData, data.data.stripe_client_secret);
			}).fail(self._processFailResponse);
	}

	_submitRegistration() {
		const self = this;

		console.log("Enter simple registration flow");

		const formData = self.formHelper.getFormData();
		const registrationUrl = wsb_event.ajax_url;

		self._lockFormSubmit();
		self._sendFormData(registrationUrl, this._prepareFormData(formData, false))
			.done(() => {
				self._submitSucceed();
			}).fail(self._processFailResponse);
	}

	/**
	 * Removes empty values from data, sent to the server
	 *
	 * @param data {object} Form data
	 * @param preRegister {boolean} True if the call is to pre-register
	 * @return {object}
	 */
	_prepareFormData(data, preRegister) {
		data.action = preRegister ? 'wsb_pre_register' : 'wsb_register';
		data._ajax_nonce = wsb_event.nonce;
		data.event_id = Number(wsb_event.id);
		for (const item in data) {
			if (!data[item]) {
				delete data[item];
			}
		}
		return data;
	}

	_processFailResponse = (response) => {
		const data = JSON.parse(response.responseText);
		this._submitFail(data.message);
		if (!(data.info)) return;
		this.formHelper.setErrors(data.info);
	};

	_prepareBillingDetails(formData) {
		return {
			"address": {
				"city": formData["billing.city"] || null,
				"country": formData["billing.country"] || null,
				"line1": formData["billing.street_1"] || null,
				"line2": formData["billing.street_2"] || null,
				"postal_code": formData["billing.postcode"] || null,
				"state": formData["billing.province"] || null
			},
			"name": (formData.first_name || "") + " " + (formData.last_name || ""),
		}
	}

	_processCardPayment(url, formData, clientSecret) {
		const self = this;

		self._lockFormSubmit();
		this.stripeCard.confirmCardPayment(
			clientSecret,
			{
				billing_details: self._prepareBillingDetails(formData)
			}
		).then((result) => {

			if (result.error) {
				// Show error to customer (e.g., insufficient funds)
				self._submitFail(result.error.message);
				return;
			}
			// The payment has been processed!
			if (result.paymentIntent && result.paymentIntent.status !== 'succeeded') {
				self._submitFail(result.paymentIntent.status);
				return;
			}

			formData['intent_id'] = result.paymentIntent.id;

			self._sendFormData(url, this._prepareFormData(formData, false))
				.done(() => {
					self._submitSucceed();
				})
				.fail((response) => {
					// it shouldn't happen in any case
					self._submitFail(
						"Due to unexpected reason we can't complete the registration. " +
						"Please contact our support to resolve it manually");
				});
		});
	}

	initActiveTicketSelection() {
		const tickets = this.$root.find('#wsb-tickets input');
		tickets.on('change', () => {
			this.toggleTicket(tickets.not(':checked'), false);
			this.toggleTicket(tickets.filter(':checked'), true);
		});
		if (tickets.length > 0) {
			const activeTicket = tickets.first();
			this.toggleTicket(activeTicket, true);
		}
	}

	initPromoActivation() {
		this.$root.find('[data-promo-link]').on('click', e => {
			e.preventDefault();
			this.$root.find('[data-promo-code]').toggle();
		});
	}


	/**
	 * Switches active ticket
	 * @param tickets {JQuery<HTMLElement>}
	 * @param on {boolean}
	 */
	toggleTicket(tickets, on) {
		const name = 'wsb-active';
		if (on) {
			tickets.prop('checked', 'true');
			tickets.parent().addClass(name);
		} else {
			tickets.removeProp('checked');
			tickets.parent().removeClass(name);
		}
	}

	// transport
	_sendFormData(url, data) {
		data._ajax_nonce = wsb_event.nonce;
		return $.ajax({
			url,
			data,
			method: 'POST',
			dataType: 'json',
		});
	}

	static plugin(selector) {
		const $elems = jQuery(selector);
		if (!$elems.length) return;

		return $elems.each((index, el) => {
			const $element = $(el);
			let data = $element.data('widget.server.detail');

			if (!data) {
				data = new EventRegistrationForm(el);
				$element.data('widget.server.detail', data);
			}
		});
	}
}

class FormHelper {
	/**
	 * Validate given controls
	 *
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

	_onBlurControl(e) {
		const $el = jQuery(e.currentTarget);
		this._isValidControl($el);
	}

	_onInputControl(e) {
		const $control = jQuery(e.currentTarget);
		this._removeError($control);
	}

	_isValidControl($control) {
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
	 *
	 * @param {jQuery} $control - element
	 * @returns {Object} = isValid(Boolean), message(String)
	 * @private
	 */
	_validateControl($control) {
		const name = $control.attr('name');
		const rules = this.rules[name];
		const valueControl = this.getControlValue($control);
		let valid;

		for (let rule in rules) {
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

	isValidFormData() {
		const self = this;
		let valid = true;

		this.removeErrors();
		this.$controls.each(
			(index, control) => {
				let isValidControl = self._isValidControl(jQuery(control));
				valid = valid && isValidControl;
			}
		);

		return valid;
	}

	/**
	 * Show or hide last error
	 *
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
	 *
	 * @param {jQuery} $control
	 * @param {String} error
	 * @param {Boolean} showBubble
	 */
	_setError($control, error, showBubble = true) {
		const $parent = $control.parent();
		const $error = $parent.find('.b-error');

		const errorText = this.messages[error] ? this.messages[error] : error;

		if ($error.length) {
			$error.text(errorText);
		} else {
			jQuery('<div class="b-error" />')
				.text(errorText)
				.appendTo($parent);
		}

		$parent.addClass('b-error_show');

		this.errors.push(
			{
				name: $control.attr('name'),
				error: error
			}
		)
	}

	_removeError($control) {
		const $parent = $control.parent();

		$parent.removeClass('b-error_show');

		this.errors = this.errors.filter(
			function (item) {
				return item.name !== $control.attr('name')
			}
		)
	}

	/**
	 * Set errors
	 *
	 * @param {Array} errors - [{name: "email", error: "empty"}, {name: "password", error: "empty"}]
	 */
	setErrors(errors) {
		this.$inputWithError = null;
		for(const key in errors) {
			const $currentControl = this.$controls.filter('[name="' + key + '"]').first();
			if (!$currentControl.length) {
				return;
			}
			this._setError($currentControl, errors[key], false);
		}
	}

	removeErrors() {
		this.$controls.each(
			(index, el) => {
				const $el = jQuery(el);
				this._removeError($el)
			}
		)
	}


	// Helper for form
	getFormData() {
		let formData = {};

		this.$controls.each(
			(index, el) => {
				const $el = jQuery(el);
				const name = $el.attr('name');
				if (name && formData[name] === undefined) {
					formData[name] = this.getControlValue($el)
				}
			}
		);

		return formData;
	}

	setFormData(formData) {
		const $controls = this.$controls;

		for (let field in formData) {
			if (formData.hasOwnProperty(field)) {
				let $control = $controls.filter(`[name="${field}"]`).first();

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
	getErrorsFull(errors) {
		const self = this;
		const arrErrors = errors || this.errors;
		let errorTxt = '';

		arrErrors.forEach(
			(item) => {
				const $control = self.$controls.filter(`[name="${item.name}"]`).first();
				const name = $control.length ? $control.attr('title') : item.name;
				errorTxt += `<b>${name}</b> : ${item.error} <br>`;
			}
		);

		return errorTxt;
	}

	clearForm() {
		this.$controls.each(
			(index, el) => {
				const $el = jQuery(el);
				if (!$el.attr("disabled")) {
					$el.val('');
				}
			}
		)
	}

	/**
	 * Universal assign value
	 *
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
	 *
	 * @param {jQuery} $control
	 * @returns {String|Boolean}
	 */
	getControlValue($control) {
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
	}
}

jQuery(document).ready(
	function () {
	    EventRegistrationForm.plugin('#wsb-form');
	}
);

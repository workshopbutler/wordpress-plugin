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

		this.cardPaymentEnabled = false;
		//this._initStripeCard();

		// if (!this.cardPaymentEnabled) {
		// 	console.log('Card payment allowed: ' + this._cardPaymentAllowed());
		// 	// const conf = CONFIG.payment || {};
		// 	const conf = {};
		// 	console.log('Stripe client id: ' + conf.stripeClientId);
		// 	console.log('Stripe key: ' + conf.stripeKey);
		// }

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
			$btnToggle: $root.find('[data-widget-register]'),
			$btnSubmit: $root.find('[data-widget-submit]'),
			$cardSection: $root.find('[data-card-section]'),
			$success: jQuery('#wsb-success'),
		};
	}

	_init() {
		this.locals.$success.hide();
		this.initPromoActivation();
		this.initActiveTicketSelection();
	}

	_cardPaymentAllowed() {
		return !!this.$root.find('[data-control][name="payment_type"] option[value="Card"]').length;
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
			&& CONFIG.payment
			&& CONFIG.payment.stripeClientId
			&& CONFIG.payment.stripeKey)
		) return false;

		this.stripeCard = createStripeCard(
			this.$root.find("#stripe-placeholder")[0],
			CONFIG.payment.stripeKey,
			CONFIG.payment.stripeClientId);

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
		const registrationUrl = self.$root.attr('action');
		// const preparePaymentUrl = self.$root.data("preparePaymentUrl");
		const preparePaymentUrl = wsb_event.ajax_url;

		self._lockFormSubmit();
		self._sendFormData(preparePaymentUrl, formData)
			.done((data) => {
				self._processCardPayment(registrationUrl, formData, data.data.client_secret);
			}).fail(self._processFailResponse);
	}

	_submitRegistration() {
		const self = this;

		console.log("Enter simple registration flow");

		const formData = self.formHelper.getFormData();
		// const registrationUrl = self.$root.attr('action');
		const registrationUrl = wsb_event.ajax_url;

		self._lockFormSubmit();
		self._sendFormData(registrationUrl, this._prepareFormData(formData))
			.done(() => {
				self._submitSucceed();
			}).fail(self._processFailResponse);
	}

	/**
	 * Removes empty values from data, sent to the server
	 *
	 * @param data {object} Form data
	 * @return {object}
	 */
	_prepareFormData(data) {
		data.action = 'wsb_register_to_event';
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

			self._sendFormData(url, formData)
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
		data.action = 'wsb_register_to_event';
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

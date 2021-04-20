<?php
/**
 * The file that defines the payment class for event
 *
 * @link       https://workshopbutler.com
 * @since      2.8.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;


/**
 * Trainer class which represents a card payment object in event
 *
 * @since      2.8.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class CardPayment {

	/**
	 * Creates CardPayment object from JSON
	 *
	 * @param object $json JSON to convert.
	 *
	 * @return CardPayment|null
	 * @since 2.8.0
	 */
	static function from_json( $json ) {
		return $json ? new CardPayment( $json->active, $json->stripe->key, $json->stripe->client_id ) : null;
	}

	/**
	 * True if card payments are active
	 *
	 * @since   2.8.0
	 * @var     boolean $active Activity flag
	 */
	public $active;

	/**
	 * Workshop Butler public Stripe key
	 *
	 * @since 2.8.0
	 * @var string $stripe_public_key
	 */
	public $stripe_public_key;

	/**
	 * Stripe id of connect account (https://stripe.com/docs/connect/enable-payment-acceptance-guide/accounts#save-the-id)
	 *
	 * @since 2.8.0
	 * @var string $stripe_client_id
	 */
	public $stripe_client_id;

	/**
	 * CardPayment constructor.
	 *
	 * @param boolean $active True if card payments are activated.
	 * @param string  $stripe_public_key Workshop Butler public Stripe key.
	 * @param string  $stripe_client_id Stripe id of connected account.
	 *
	 * @since 2.8.0
	 */
	function __construct( $active, $stripe_public_key, $stripe_client_id ) {
		$this->active            = $active;
		$this->stripe_public_key = $stripe_public_key;
		$this->stripe_client_id  = $stripe_client_id;
	}
}




/**
 * Trainer class which represents a paypal payment object in event
 *
 * @since      2.14.0
 * @package    WorkshopButler
 */
class PayPalPayment {

	/**
	 * Creates PayPalPayment object from JSON
	 *
	 * @param object $json JSON to convert.
	 *
	 * @return PayPalPayment|null
	 * @since 2.14.0
	 */
	static function from_json( $json ) {
		return $json ? new PayPalPayment( $json->active, $json->client_id ) : null;
	}

	/**
	 * True if paypal payments are active
	 *
	 * @since   2.14.0
	 * @var     boolean $active Activity flag
	 */
	public $active;


	/**
	 * PayPal client id
	 *
	 * @since 2.14.0
	 * @var string $stripe_client_id
	 */
	public $client_id;

	/**
	 * PayPalPayment constructor.
	 *
	 * @param boolean $active True if paypal payments are activated.
	 * @param string  $client_id PayPal client id of connected account.
	 *
	 * @since 2.14.0
	 */
	function __construct( $active, $client_id ) {
		$this->active     = $active;
		$this->client_id  = $client_id;
	}
}

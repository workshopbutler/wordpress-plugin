<?php
/**
 * Class Test_WSB_Ajax
 *
 * @package WorkshopButler
 */

require_once 'workshop-butler/public/includes/class-wsb-ajax.php';

/**
 * Tests for @WSB_Ajax class
 */
class Test_WSB_Ajax extends WP_UnitTestCase {

	/**
	 * Tests that simple form keys are passed correctly
	 */
	function test_changed_simple_form_keys() {
		$raw_data  = [
			'first_name' => 'Billy',
			'last_name'  => 'Burrow',
			'email'      => 'billy@wsb.com',
			'98xlfs'     => 'yes',
		];
		$form_data = Testable_WSB_Ajax::replace_changed_keys( $raw_data );
		$this->assertTrue( 'Billy' === $form_data['first_name'] );
		$this->assertTrue( 'Burrow' === $form_data['last_name'] );
		$this->assertTrue( 'billy@wsb.com' === $form_data['email'] );
		$this->assertTrue( 'yes' === $form_data['98xlfs'] );
	}

	/**
	 * Tests that 'billing' form keys are changed
	 */
	function test_changed_billing_form_keys() {
		$raw_data  = [
			'billing_country'  => 'DE',
			'billing_street_1' => 'Rua Bela Vista',
			'billing_province' => 'Lisbon',
			'billing_city'     => 'Lisbon',
			'billing_postcode' => '1982',
			'billing_street_2' => 'nothing',
		];
		$form_data = Testable_WSB_Ajax::replace_changed_keys( $raw_data );
		$this->assertTrue( 'DE' === $form_data['billing.country'] );
		$this->assertTrue( 'Rua Bela Vista' === $form_data['billing.street_1'] );
		$this->assertTrue( 'Lisbon' === $form_data['billing.province'] );
		$this->assertTrue( 'Lisbon' === $form_data['billing.city'] );
		$this->assertTrue( '1982' === $form_data['billing.postcode'] );
		$this->assertTrue( 'nothing' === $form_data['billing.street_2'] );
	}

	/**
	 * Tests that 'work' form keys are changed
	 */
	function test_changed_work_form_keys() {
		$raw_data  = [
			'work_country'  => 'DE',
			'work_street_1' => 'Rua Bela Vista',
			'work_province' => 'Lisbon',
			'work_city'     => 'Lisbon',
			'work_postcode' => '1982',
			'work_street_2' => 'nothing',
		];
		$form_data = Testable_WSB_Ajax::replace_changed_keys( $raw_data );
		$this->assertTrue( 'DE' === $form_data['work.country'] );
		$this->assertTrue( 'Rua Bela Vista' === $form_data['work.street_1'] );
		$this->assertTrue( 'Lisbon' === $form_data['work.province'] );
		$this->assertTrue( 'Lisbon' === $form_data['work.city'] );
		$this->assertTrue( '1982' === $form_data['work.postcode'] );
		$this->assertTrue( 'nothing' === $form_data['work.street_2'] );
	}
}

class Testable_WSB_Ajax extends WorkshopButler\WSB_Ajax {

	static function replace_changed_keys( $raw_data ) {
		return parent::replace_changed_keys( $raw_data );
	}
}

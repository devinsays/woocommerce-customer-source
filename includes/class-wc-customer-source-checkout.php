<?php
/**
 * WooCommerce Customer Source - Checkout.
 *
 * @class    WC_Customer_Source_Checkout
 * @author   DevPress
 * @package  WooCommerce Customer Source
 * @license  GPL-2.0+
 * @since    1.0.0
 */

if ( ! defined('ABSPATH') ) {
	exit; // Exit if accessed directly.
}

class WC_Customer_Source_Checkout {

	/**
	* Initialize the class.
	*/
	public static function init() {

		// Adds fields to checkout.
		add_filter( 'woocommerce_checkout_fields', __CLASS__ . '::customer_source_checkout_fields' );

	}

	/**
	 * Adds a select field and textarea to checkout.
	 *
	 * @access public
	 * @since    1.0.0
	 * @return void
	 */
	public static function customer_source_checkout_fields( $fields ) {

		// Adds customer source select field.
		$fields['order']['customer_source'] = array(
			'type' => 'select',
			'class' => array( 'form-row-wide' ),
			'label' => __( 'How did you find out about us?', 'woocommerce-customer-source'),
			'options' => array(
				'default' =>   __( '-- Choose an option --', 'woocommerce-customer-source'),
				'facebook' =>  __( 'Facebook', 'woocommerce-customer-source'),
				'google' =>    __( 'Google', 'woocommerce-customer-source'),
				'friend' =>    __( 'Friend', 'woocommerce-customer-source'),
				'other' =>     __( 'Other', 'woocommerce-customer-source'),
			)
		);

		// Adds customer source textarea.
		$fields['order']['customer_source_custom'] = array(
			'type' => 'textarea',
			'placeholder' => __( 'Let us know where you found out about us...', 'woocommerce-customer-source'),
		);

		return $fields;

	}

}

WC_Customer_Source_Checkout::init();

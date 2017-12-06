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

		// Saves fields on checkout.
		add_action( 'woocommerce_checkout_update_order_meta', __CLASS__ . '::save_customer_source_meta' ), 100, 2 );

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
			'placeholder' => __( 'Let us know where you found out about us...', 'woocommerce-customer-source' ),
		);

		return $fields;

	}

	/**
	 * Adds a select field and textarea to checkout.
	 *
	 * @access public
	 * @since    1.0.0
	 * @param int $order_id
	 * @param array $data
	 * @return void
	 */
	public static function save_customer_source_meta( $order_id, $data ) {

		$order = wc_get_order( $order_id );

		// Sanitize customer source data.
		$customer_source = wc_clean( wp_unslash( isset( $data['customer_source'] ) ? $data['customer_source'] : '' ) );
		$customer_source_custom = wc_clean( wp_unslash( isset( $data['customer_source_custom'] ) ? $data['customer_source_custom'] : '' ) );

		// Save order meta data.
		$order->update_meta_data( 'customer_source', $customer_source );
		$order->update_meta_data( 'customer_source_custom', $customer_source );
		$order->save();

	}

}

WC_Customer_Source_Checkout::init();

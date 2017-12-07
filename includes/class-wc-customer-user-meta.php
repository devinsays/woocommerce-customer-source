<?php
/**
 * WooCommerce Customer Source - User meta.
 *
 * @class    WC_Customer_Source_User_Meta
 * @author   DevPress
 * @package  WooCommerce Customer Source
 * @license  GPL-2.0+
 * @since    1.0.0
 */

if ( ! defined('ABSPATH') ) {
	exit; // Exit if accessed directly.
}

class WC_Customer_Source_User_Meta {

	/**
	* Initialize the class.
	*/
	public static function init() {

		// Add user meta once payment completes.
		add_action( 'woocommerce_payment_complete', __CLASS__ . '::add_user_meta', 100, 1 );

		// Display meta in customer profile.
		add_filter( 'woocommerce_customer_meta_fields', __CLASS__ . '::filter_customer_profile_meta_fields', 100, 1 );

	}

	/**
	 * Adds user meta once payment is complete.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param int $order_id
	 * @return void
	 */
	public static function add_user_meta( $order_id ) {

		// Get the order that was just placed.
		$order = wc_get_order( $order_id );

		// Get user id.
		$user_id = $order->get_user_id();

		// For guest checkout orders get_user_id returns 0.
		// If it's a guest checkout, return without saving meta.
		if ( ! $user_id ) {
			return;
		}

		// Get the meta data from the order.
		$customer_source = $order->get_meta( 'customer_source' );
		$customer_source_notes = $order->get_meta( 'customer_source_notes' );

		// Update customer source.
		if ( isset( $customer_source ) ) {
			update_user_meta( $user_id, 'customer_source', $customer_source );
		}

		// Update customer source custom.
		if ( isset( $customer_source ) ) {
			update_user_meta( $user_id, 'customer_source_notes', $customer_source_notes );
		}

	}

	/**
	 * Adds customer source to customer meta fields.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param int $order_id
	 * @return void
	 */
	public static function filter_customer_profile_meta_fields( $fields ) {

		$fields['customer_source'] = array(
			'title' => __( 'Customer source', 'woocommerce-customer-source' ),
			'fields' => array(
				'customer_source' => array(
					'label' => __( 'Customer source', 'woocommerce-customer-source' ),
					'description' => __( 'Customer reported referral source.', 'woocommerce-customer-source' ),
				),
				'customer_source_notes' => array(
					'label' => __( 'Customer source notes', 'woocommerce-customer-source' ),
					'description' => __( 'Customer notes if "other" was selected as referral source.', 'woocommerce-customer-source' ),
				)
			)
		);

		return $fields;
	}

}

WC_Customer_Source_User_Meta::init();

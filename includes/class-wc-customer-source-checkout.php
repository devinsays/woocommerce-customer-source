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

		// Saves fields to order.
		add_action( 'woocommerce_checkout_update_order_meta', __CLASS__ . '::save_customer_source_meta', 100, 2 );

		// Adds inline styles to hide customer_source_notes.
		add_action( 'wp_head', __CLASS__ . '::customer_source_notes_styles', 100, 2 );

		// Adds javascript for customer source custom textarea toggle.
		add_action( 'wp_footer', __CLASS__ . '::customer_source_notes_toggle', 100, 2 );

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
			'label' => __( 'How did you find out about us?', 'woocommerce-customer-source'),
			'options' => array(
				'default' =>   __( '-- Select an Option --', 'woocommerce-customer-source'),
				'facebook' =>  __( 'Facebook', 'woocommerce-customer-source'),
				'google' =>    __( 'Google', 'woocommerce-customer-source'),
				'friend' =>    __( 'Friend', 'woocommerce-customer-source'),
				'other' =>     __( 'Other', 'woocommerce-customer-source'),
			)
		);

		// Adds customer source textarea.
		$fields['order']['customer_source_notes'] = array(
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
		$save_required = false;

		// Sanitize customer source data.
		$customer_source = wc_clean( wp_unslash( isset( $data['customer_source'] ) ? $data['customer_source'] : '' ) );
		$customer_source_notes = wc_clean( wp_unslash( isset( $data['customer_source_notes'] ) ? $data['customer_source_notes'] : '' ) );

		// Save customer_source if specified.
		if ( '' !== $customer_source ) {
			$order->update_meta_data( 'customer_source', $customer_source );
			$save_required = true;
		}

		// Save customer_source_notes if 'other' was selected.
		if ( 'other' === $customer_source && '' !== $customer_source_notes ) {
			$order->update_meta_data( 'customer_source_notes', $customer_source_notes );
			$save_required = true;
		}

		// Save the meta to the order if there is data.
		if ( $save_required ) {
			$order->save();
		}

	}

	/**
	 * Inline styles to toggle the customer source custom textarea.
	 *
	 * @access public
	 * @since    1.0.0
	 * @return void
	 */
	public static function customer_source_notes_styles() {
		if ( function_exists( 'is_checkout' ) && is_checkout() ) { ?>
			<style>
				#customer_source_notes { display: none; }
				#customer_source_notes.active { display: block; }
			</style>
		<?php }
	}

	/**
	 * Inline javascript to toggle the customer source custom textarea.
	 *
	 * @access public
	 * @since    1.0.0
	 * @return void
	 */
	public static function customer_source_notes_toggle() {
		if ( function_exists( 'is_checkout' ) && is_checkout() ) { ?>
			<script>
				document.getElementById('customer_source').onchange = function() {
					if ( 'other' === this.children[this.selectedIndex].value ) {
						document.getElementById('customer_source_notes').className = 'active';
					} else {
						document.getElementById('customer_source_notes').className = '';
					}
				}
			</script>
		<?php }
	}

}

WC_Customer_Source_Checkout::init();

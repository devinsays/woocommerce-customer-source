<?php
/**
 * Plugin Name: WooCommerce Customer Source
 * Plugin URI: http://github.com/devinsays/woocommerce-customer-source
 * Description: Learn where your customers are coming from. Adds a select field to the WooCommerce checkout screen that asks how new customers found out about the store.
 * Version: 1.0.0
 * Author: DevPress
 * Author URI: https://devpress.com
 *
 * Requires at least: 4.5
 * Tested up to: 4.9.1
 * WC requires at least: 3.2.1
 * WC tested up to: 3.2.5
 *
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Text Domain: woocommerce-customer-source
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'WC_Customer_Source' ) ) :
class WC_Customer_Source {

	/**
	 * @var WC_Customer_Source - The single instance of the class.
	 *
	 * @access protected
	 * @static
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Plugin Version
	 *
	 * @access public
	 * @static
	 * @since  1.0.0
	 */
	public static $version = '1.0.0';

	/**
	 * Required WooCommerce Version
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public $required_woo = '3.0.0';

	/**
	 * Plugin path.
	 *
	 * @access public
	 * @static
	 * @since  1.3.0
	 */
	public static function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Main WC_Customer_Source Instance.
	 *
	 * Ensures only one instance of WC_Customer_Source is loaded or can be loaded.
	 *
	 * @access public
	 * @static
	 * @since  1.0.0
	 * @see    WC_Customer_Source()
	 * @return WC_Customer_Source - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Loads the plugin.
	 *
	 * @access public
	 * @since  1.3.0
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load_plugin' ) );
		add_action( 'init', array( $this, 'init_plugin' ) );

		// Include required files
		add_action( 'woocommerce_loaded', array( $this, 'includes' ) );
	}

	/**
	 * Check requirements on activation.
	 *
	 * @access public
	 * @since  1.3.0
	 */
	public function load_plugin() {
		// Check we're running the required version of WooCommerce.
		if ( ! defined( 'WC_VERSION' ) || version_compare( WC_VERSION, $this->required_woo, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'woocommerce_compatibility_notice' ) );
			return false;
		}
	}

	/**
	 * Display a warning message if minimum version of WooCommerce check fails.
	 *
	 * @access public
	 * @since  1.3.0
	 * @return void
	 */
	public function woocommerce_compatibility_notice() {
		echo '<div class="error"><p>' . sprintf( __( '%1$s requires at least %2$s v%3$s in order to function. Please upgrade %2$s.', 'woocommerce-coupon-restriction' ), 'WooCommerce Coupon Restrictions', 'WooCommerce', $this->required_woo ) . '</p></div>';
	}

	/**
	 * Initialize the plugin.
	 *
	 * @access public
	 * @since  1.3.0
	 * @return void
	 */
	public function init_plugin() {
		// Load translations.
		load_plugin_textdomain( 'woocommerce-customer-source', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
	}

	/**
	 * Includes classes that implement coupon restrictions.
	 *
	 * @access public
	 * @since    1.0.0
	 * @return void
	 */
	public function includes() {

		// Adds fields and metadata for coupons in admin screen.
		if ( is_admin() ) {
			include_once( $this->plugin_path() . '/includes/class-wc-customer-source-admin.php' );
		}

		// Validates coupons on checkout.
		if ( ! is_admin() ) {
			include_once( $this->plugin_path() . '/includes/class-wc-customer-source-checkout.php' );
		}

	}

}
endif;

return WC_Customer_Source::instance();

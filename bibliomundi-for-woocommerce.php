<?php
/**
 * Plugin Name: Bibliomundi For WooCommerce
 * Plugin URI: https://github.com/eltondev/Bibliomundi-For-WooCommerce
 * Description: Integration plugin Bibliomundi For WooCommerce 
 * Author: EltonDEV
 * Author URI: http://eltondev.com.br
 * Version: 1.0.1
 * License: GPLv2 or later
 * Text Domain: bibliomundi-for-wooCommerce
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WC_Bibliomundi' ) ) :

/**
 * WooCommerce Bibliomundi main class.
 */
class WC_Bibliomundi {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = '1.0.1';

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin public actions.
	 */
	private function __construct() {
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Checks with WooCommerce and WooCommerce Extra Checkout Fields for Brazil is installed.
		if ( class_exists( 'WC_Integration' ) && class_exists( 'Extra_Checkout_Fields_For_Brazil' ) ) {
			$this->includes();

			add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'dependencies_notice' ) );
		}
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$domain = 'bibliomundi-for-wooCommerce';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Includes.
	 *
	 * @return void
	 */
	private function includes() {
		include_once 'includes/class-wc-bibliomundi-simplexml.php';
		include_once 'includes/class-wc-bibliomundi-api.php';
		include_once 'includes/class-wc-bibliomundi-integration.php';
	}

	/**
	 * Add the Bibliomundi integration to WooCommerce.
	 *
	 * @param  array $methods WooCommerce integrations.
	 *
	 * @return array          Bibliomundi integration.
	 */
	public function add_integration( $methods ) {
		$methods[] = 'WC_Bibliomundi_Integration';

		return $methods;
	}

	/**
	 * Dependencies notice.
	 *
	 * @return string
	 */
	public function dependencies_notice() {
		echo '<div class="error"><p>' . sprintf(
			__( 'Bibliomundi WooCommerce depends on the last version of the %s and the %s to work!', 'bibliomundi-for-wooCommerce' ),
			'<a href="http://wordpress.org/extend/plugins/woocommerce/">' . __( 'WooCommerce', 'bibliomundi-for-wooCommerce' ) . '</a>',
			'<a href="http://wordpress.org/plugins/woocommerce-extra-checkout-fields-for-brazil/">' . __( 'WooCommerce Extra Checkout Fields for Brazil', 'bibliomundi-for-wooCommerce' ) . '</a>'
		) . '</p></div>';
	}
}

add_action( 'plugins_loaded', array( 'WC_Bibliomundi', 'get_instance' ), 0 );

endif;

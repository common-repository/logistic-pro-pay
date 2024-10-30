<?php
/**
 * Plugin Name: Logistic Pro Pay
 * Plugin URI: https://www.logisticprotrade.com/pay-api/woocommerce/
 * Description: Accept all major crypto currencies directly on your WooCommerce site in a seamless and secure checkout environment with Logistic Pro Pay.
 * Version: 1.0.0
 * Author: Logistic Pro Pay
 * Author URI: https://www.logisticprotrade.com/
 * License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: logistic-pro-pay
 * 
 * @package WordPress
 * @author Logistic Pro Trade
 * @since 1.0.0
 */



/**
 * Logistic Pro Pay Commerce Class
 */
class WC_LogisticProPay {


	/**
	 * Constructor
	 */
	public function __construct(){
		define( 'WC_LogisticProPay_VERSION', '1.0.0' );
		define( 'WC_LogisticProPay_TEMPLATE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' );
		define( 'WC_LogisticProPay_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		define( 'WC_LogisticProPay_PLUGIN_DIR', plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) . '/' );
		define( 'WC_LogisticProPay_MAIN_FILE', __FILE__ );

		// Actions
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ), 0 );
		add_filter( 'woocommerce_payment_gateways', array( $this, 'register_gateway' ) );
		//add_action( 'wp_enqueue_scripts', array( $this, 'add_bac_scripts' ) );

	}

	/**
	 * Add links to plugins page for settings and documentation
	 * @param  array $links
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		$subscriptions = ( class_exists( 'WC_Subscriptions_Order' ) ) ? '_subscriptions' : '';
		if ( class_exists( 'WC_Subscriptions_Order' ) && ! function_exists( 'wcs_create_renewal_order' ) ) {
			$subscriptions = '_subscriptions_deprecated';
		}
		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_gateway_logistic_pro_pay' . $subscriptions ) . '">' . __( 'Settings', 'wc-gateway-logistic-pro-pay' ) . '</a>',
			'<a href="https://www.logisticprotrade.com/">' . __( 'Support', 'wc-gateway-logistic-pro-pay' ) . '</a>',
			'<a href="https://www.logisticprotrade.com/">' . __( 'Docs', 'wc-gateway-logistic-pro-pay' ) . '</a>'
		);
		return array_merge( $plugin_links, $links );
	}

	/**
	 * Init localisations and files
	 */
	public function init() {

		if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
			return;
		}

		// Includes
		include_once( 'includes/class-wc-gateway-logistic-pro-pay.php' );

		// Localisation
		load_plugin_textdomain( 'wc-gateway-logistic-pro-pay', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	}

	/**
	 * Register the gateway for use
	 */
	public function register_gateway( $methods ) {

		$methods[] = 'WC_Gateway_Logistic_Pro_Pay';

		return $methods;

	}


	/**
	 * Include jQuery and our scripts
	 */
	function add_logistic_pro_pay_scripts() {

		wp_enqueue_style( 'logisticpropaystyle', WC_LogisticProPay_PLUGIN_DIR . 'css/logisticpropaystyle.css', false );
		wp_enqueue_script( 'logisticpropayscript', WC_LogisticProPay_PLUGIN_DIR . 'js/logisticScript.js', array( 'jquery' ), WC_LogisticProPay_VERSION, true );

	}

	/**
	 * Check if the user has any billing records in the Customer Vault
	 *
	 * @param $user_id
	 *
	 * @return bool
	 */
	function user_has_stored_data( $user_id ) {
		return get_user_meta( $user_id, 'customer_vault_ids', true ) != null;
	}


}

new WC_LogisticProPay();

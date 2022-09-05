<?php

add_action( 'wp_enqueue_scripts', 'wolmart_child_css', 1001 );

// Load CSS
function wolmart_child_css() {
	// wolmart child theme styles
	wp_deregister_style( 'styles-child' );
	wp_register_style( 'styles-child', esc_url( get_theme_file_uri() ) . '/style.css' );
	wp_enqueue_style( 'styles-child' );
}


add_filter( 'wcfm_marketplace_withdrwal_payment_methods', function( $payment_methods ) {
	$payment_methods['brain_tree'] = 'Brain Tree';
	return $payment_methods;
});

add_filter( 'wcfm_marketplace_settings_fields_withdrawal_payment_keys', function( $payment_keys, $wcfm_withdrawal_options ) {
	$gateway_slug = 'brain_tree';
	$withdrawal_brain_tree_client_id = isset( $wcfm_withdrawal_options[$gateway_slug.'_client_id'] ) ? $wcfm_withdrawal_options[$gateway_slug.'_client_id'] : '';
	$withdrawal_brain_tree_secret_key = isset( $wcfm_withdrawal_options[$gateway_slug.'_secret_key'] ) ? $wcfm_withdrawal_options[$gateway_slug.'_secret_key'] : '';
	$payment_brain_tree_keys = array(
	"withdrawal_".$gateway_slug."_client_id" => array('label' => __('Brain Tree Client ID', 'wc-multivendor-marketplace'), 'name' => 'wcfm_withdrawal_options['.$gateway_slug.'_client_id]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_live withdrawal_mode_'.$gateway_slug, 'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_live withdrawal_mode_'.$gateway_slug, 'value' => $withdrawal_brain_tree_client_id ),
	"withdrawal_".$gateway_slug."_secret_key" => array('label' => __('Brain Tree Secret Key', 'wc-multivendor-marketplace'), 'name' => 'wcfm_withdrawal_options['.$gateway_slug.'_secret_key]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_live withdrawal_mode_'.$gateway_slug, 'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_live withdrawal_mode_'.$gateway_slug, 'value' => $withdrawal_brain_tree_secret_key )
	);
	$payment_keys = array_merge( $payment_keys, $payment_brain_tree_keys );
	return $payment_keys;
}, 50, 2);

add_filter( 'wcfm_marketplace_settings_fields_withdrawal_charges', function( $withdrawal_charges, $wcfm_withdrawal_options, $withdrawal_charge ) {
	$gateway_slug = 'brain_tree';
	$withdrawal_charge_brain_tree = isset( $withdrawal_charge[$gateway_slug] ) ? $withdrawal_charge[$gateway_slug] : array();
	$payment_withdrawal_charges = array( "withdrawal_charge_".$gateway_slug => array( 'label' => __('Brain Tree Charge', 'wc-multivendor-marketplace'), 'type' => 'multiinput', 'name' => 'wcfm_withdrawal_options[withdrawal_charge]['.$gateway_slug.']', 'class' => 'withdraw_charge_block withdraw_charge_'.$gateway_slug, 'label_class' => 'wcfm_title wcfm_ele wcfm_fill_ele withdraw_charge_block withdraw_charge_'.$gateway_slug, 'value' => $withdrawal_charge_brain_tree, 'custom_attributes' => array( 'limit' => 1 ), 'options' => array(
	"percent" => array('label' => __('Percent Charge(%)', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele withdraw_charge_field withdraw_charge_percent withdraw_charge_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele withdraw_charge_field withdraw_charge_percent withdraw_charge_percent_fixed', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
	"fixed" => array('label' => __('Fixed Charge', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele withdraw_charge_field withdraw_charge_fixed withdraw_charge_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele withdraw_charge_field withdraw_charge_fixed withdraw_charge_percent_fixed', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
	"tax" => array('label' => __('Charge Tax', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'min' => '0.1', 'step' => '0.1'), 'hints' => __( 'Tax for withdrawal charge, calculate in percent.', 'wc-multivendor-marketplace' ) ),
	) ) );
	$withdrawal_charges = array_merge( $withdrawal_charges, $payment_withdrawal_charges );
	return $withdrawal_charges;
}, 50, 3);

add_filter( 'wcfm_marketplace_settings_fields_billing', function( $vendor_billing_fileds, $vendor_id ) {
	$gateway_slug = 'brain_tree';
	$vendor_data = get_user_meta( $vendor_id, 'wcfmmp_profile_settings', true );
	if( !$vendor_data ) $vendor_data = array();
		$brain_tree = isset( $vendor_data['payment'][$gateway_slug]['email'] ) ? esc_attr( $vendor_data['payment'][$gateway_slug]['email'] ) : '' ;
		$vendor_brain_tree_billing_fileds = array(
		$gateway_slug => array('label' => __('Brain Tree Email', 'wc-frontend-manager'), 'name' => 'payment['.$gateway_slug.'][email]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele paymode_field paymode_'.$gateway_slug, 'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_'.$gateway_slug, 'value' => $brain_tree ),
	);
	$vendor_billing_fileds = array_merge( $vendor_billing_fileds, $vendor_brain_tree_billing_fileds );
	return $vendor_billing_fileds;
}, 50, 2);

class WCFMmp_Gateway_Brain_Tree {
	public $id;
	public $message = array();
	public $gateway_title;
	public $payment_gateway;
	public $withdrawal_id;
	public $vendor_id;
	public $withdraw_amount = 0;
	public $currency;
	public $transaction_mode;
	private $reciver_email;
	public $test_mode = false;
	public $client_id;
	public $client_secret;
	public function __construct() {
		$this->id = 'brain_tree';
		$this->gateway_title = __('Brain Tree', 'wc-multivendor-marketplace');
		$this->payment_gateway = $this->id;
	}
	public function gateway_logo() { global $WCFMmp; return $WCFMmp->plugin_url . 'assets/images/'.$this->id.'.png'; }
	public function process_payment( $withdrawal_id, $vendor_id, $withdraw_amount, $withdraw_charges, $transaction_mode = 'auto' ) {
	global $WCFM, $WCFMmp;
	$this->withdrawal_id = $withdrawal_id;
	$this->vendor_id = $vendor_id;
	$this->withdraw_amount = $withdraw_amount;
	$this->currency = get_woocommerce_currency();
	$this->transaction_mode = $transaction_mode;
	$this->reciver_email = $WCFMmp->wcfmmp_vendor->get_vendor_payment_account( $this->vendor_id, $this->id );
	$withdrawal_test_mode = isset( $WCFMmp->wcfmmp_withdrawal_options['test_mode'] ) ? 'yes' : 'no';
	$this->client_id = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_client_id'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_client_id'] : '';
	$this->client_secret = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_secret_key'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_secret_key'] : '';
	if ( $withdrawal_test_mode == 'yes') {
		$this->test_mode = true;
		$this->client_id = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_test_client_id'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_test_client_id'] : '';
		$this->client_secret = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_test_secret_key'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->id.'_test_secret_key'] : '';
	}
	if ( $this->validate_request() ) {
		// Updating withdrawal meta
		$WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta( $this->withdrawal_id, 'withdraw_amount', $this->withdraw_amount );
		$WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta( $this->withdrawal_id, 'currency', $this->currency );
		$WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta( $this->withdrawal_id, 'reciver_email', $this->reciver_email );
		return array( 'status' => true, 'message' => __('New transaction has been initiated', 'wc-multivendor-marketplace') );
	} else {
		return $this->message;
	}
	}
	public function validate_request() {
		global $WCFMmp;
		return true;
	}
}


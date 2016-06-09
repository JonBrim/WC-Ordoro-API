<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_Settings_Ordoro {
	
	public function init() {
		// Filters
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_settings_page' ) );
	}

	public function __construct() {
		add_action( 'admin_init', array( $this, 'init' ) );
	}

	public function add_settings_page( $settings ) {
		$settings[] = include( 'class-wc-settings-page.php' );
		return $settings;
	}

}

new WC_Settings_Ordoro();
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_Settings_Ordoro_Page extends WC_Settings_Page {

	public function __construct() {
		$this->id    = 'odoro';
		$this->label = 'Ordoro';

		// Filters
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		
		// Actions
		add_action( 'woocommerce_settings_' . $this->id,      array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
		add_action( 'woocommerce_sections_' . $this->id,      array( $this, 'output_sections' ) );
	}

	public function get_settings() {
		$settings = array(
			'section_title' => array(
				'name'     => 'Ordoro API Settings',
				'type'     => 'title',
				'desc'     => '',
				'id'       => 'wc_ordoro_section_title'
			),
			'url' => array(
				'name'    => 'Url',
				'type'    => 'text',
				'id'      => 'wc_ordoro_url',
				'default' => 'https://api.ordoro.com'
			),
			'username' => array(
				'name' => 'Username',
				'type' => 'email',
				'id'   => 'wc_ordoro_username'
			),
			'password' => array(
				'name' => 'Password',
				'type' => 'password',
				'id'   => 'wc_ordoro_password'
			),
			'warehouse' => array(
				'name'     => 'Warehouse',
				'type'     => 'number',
				'id'       => 'wc_ordoro_warehouse',
				'default'  => '0'
			),
			'section_end' => array(
				'type' => 'sectionend',
				'id'   => 'wc_ordoro_section_end'
			)
		);
		return apply_filters( 'wc_ordoro_settings', $settings );
	}

}

new WC_Settings_Ordoro_Page();
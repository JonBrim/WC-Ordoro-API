<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_Ordoro {

	protected static $instance = null;

	public function __construct() {
		$this->includes();
		$this->initialize_classes();
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function includes() {
		require_once( 'class-wc-ordoro-resource.php' );
		require_once( 'class-wc-ordoro-resource-product.php' );

		do_action( 'ordoro_resources_loaded' );
	}

	public function initialize_classes() {
		$api_resources = apply_filters( 'ordoro_resources',
			array(
				'product'   => 'WC_Ordoro_Resource_Product',
			)
		);

		foreach ( $api_resources as $method => $class  ) {
			if ( class_exists( $class ) ) {
				$this->$method = new $class( $this );
			}
		}
	}
}
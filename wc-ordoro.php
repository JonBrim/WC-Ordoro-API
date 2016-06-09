<?php
/**
 * @package Ordoro
 * @version 1.1
 */
/*
Plugin Name: Ordoro - development
Description: 
Author: Jon Brim
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	// Functions
	require_once( 'functions/functions-core.php' );
	// Classes
	require_once( 'classes/class-wc-settings.php' );
	require_once( 'classes/class-wc-ordoro.php' );
}
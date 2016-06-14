<?php
/*
Plugin Name: ATX REST Menus
Plugin URI: http://ryan.hoover.ws
Description: Provides our demo endpoints for the REST API
Version: 0.1
Author: ryanshoover
Author URI: http://ryan.hoover.ws
Text Domain: rest-api-demo
*/

// Abort if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Simple function call to tell WordPress to include our endpoint
 */
function atx_rest_initialize() {
	// Load the REST Controller base class if we don't have one yet
	if ( ! class_exists( 'WP_REST_Controller' ) ) {
		require_once( plugin_dir_path(__FILE__) . 'endpoints/class-wp-rest-controller.php' );
	}

	// Load our own menus controller
	require_once( plugin_dir_path(__FILE__) . 'endpoints/class-wp-rest-menus-controller.php' );

	// Initialize our controller's routes
	$controller = new ATX_REST_Menus_Controller();
	$controller->register_routes();
}

// Hooked into the rest_api_init action so we're not loading code when we don't need it
add_action( 'rest_api_init', 'atx_rest_initialize' );

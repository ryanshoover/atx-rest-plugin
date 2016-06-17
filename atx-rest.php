<?php
/*
Plugin Name: ATX REST Examples
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
 * Load the REST Controller base class if we don't have one yet
 */
function atx_maybe_include_rest_controller() {
	if ( ! class_exists( 'WP_REST_Controller' ) ) {
		require_once( plugin_dir_path(__FILE__) . 'endpoints/class-wp-rest-controller.php' );
	}
}

// Hooked into the rest_api_init action so we're not loading code when we don't need it
add_action( 'rest_api_init', 'atx_maybe_include_rest_controller', 1 );






/**
 * Simple function call to tell WordPress to include our menu endpoint
 */
function atx_rest_initialize_menus() {
	// Load our own menus controller
	require_once( plugin_dir_path(__FILE__) . 'endpoints/class-wp-rest-menus-controller.php' );

	// Initialize our controller's routes
	$controller = new ATX_REST_Menus_Controller();
	$controller->register_routes();
}

// Hooked into the rest_api_init action so we're not loading code when we don't need it
add_action( 'rest_api_init', 'atx_rest_initialize_menus' );







/**
 * Render the atx_rest shortcode
 * @param  {arr}    $atts The shortcode's attributes
 * @return {string}       The rendered HTML string
 */
function atx_do_rest_shortcode( $atts ) {
	// Get our arguments
	$defaults = array(
		'url' => site_url( '/wp-json/wp/v2/posts' ),
		);
	$atts = wp_parse_args( $atts, $defaults );

	// Get the results from our URL
	$response = wp_remote_get( $atts['url'] );

	// Abort if we didn't get a good response
	if ( ! $response || 300 <= $response['response']['code'] ) {
		return;
	}

	// Initialize our output string
	$html = '';

	// Get the array of post content
	$posts = json_decode( $response['body'] );

	// Loop through the results and print each as a link
	$html .= '<ul>';

	foreach ( $posts as $the_post ) {
		$html .= "<li><a href=\"{$the_post->link}\">{$the_post->title->rendered}</a></li>";
	}

	$html .= '</ul>';

	// Send our list back as our response
	return $html;
}

// Declare our shortcode
add_shortcode( 'atx_rest', 'atx_do_rest_shortcode' );








/**
 * Initialize our image controller for the REST API
 */
function atx_rest_initialize_image() {
	// Load our own menus controller
	require_once( plugin_dir_path(__FILE__) . 'endpoints/class-wp-rest-image-controller.php' );

	// Initialize our controller's routes
	$controller = new ATX_REST_Image_Controller();
	$controller->register_routes();
}

// Hooked into the rest_api_init action so we're not loading code when we don't need it
add_action( 'rest_api_init', 'atx_rest_initialize_image' );



/**
 * If we're serving our special image endpoint, take over rendering the result
 * @param  {bool} $response Whether the response has been served
 * @param  {obj}  $result   The result of the REST API request
 * @param  {obj}  $request  The initial request
 * @return {bool}           Did we serve our own response?
 */
function atx_maybe_serve_rest_response( $response, $result, $request ) {

	$route = $request->get_route();

	if ( stristr( $route, '/atx/v1/image/' ) ) {
		echo $result->get_data();
		$response = true;
	}

	return $response;
}

// Filter the "pre serve request" to decide whether we should render it ourselves
add_filter( 'rest_pre_serve_request', 'atx_maybe_serve_rest_response', 10, 3 );

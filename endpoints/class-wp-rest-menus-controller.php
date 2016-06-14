<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists('ATX_REST_Menus_Controller') ) {

    class ATX_REST_Menus_Controller extends WP_REST_Controller {

        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes() {
            register_rest_route( 'atx/v1', '/menus/?' , array(
                array(
                    'methods'  => WP_REST_Server::READABLE,
                    'callback' => array( $this, 'get_items' ),
                ),
            ) );

            register_rest_route( 'atx/v1', '/menus/(?P<menu>[\w-]+)/?', array(
                array(
                    'methods'  => WP_REST_Server::READABLE,
                    'callback' => array( $this, 'get_item' ),
                ),
            ) );
        }

        /**
         * Get all registered nav menus.
         *
         * @param  WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_items( $request ) {
            // Get all the menu locations
            $locations = get_nav_menu_locations();

            // Return those locations to the requester
            return rest_ensure_response( $locations );
        }

        /**
         * Get all items in a nav menu.
         *
         * @param  WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_item( $request ) {
            // Get all the menu locations
            $locations = get_nav_menu_locations();

            // Get the menu location we requested
            $location = $request->get_param('menu');

            // If we passed in a string, get the menu location's ID number instead
            if ( is_string( $location ) && isset( $locations[ $location ] ) ) {
                $location = (int) $locations[ $location ];
            }

            // If we don't have that location, pass back an empty array
            if ( ! in_array( $location, $locations ) ) {
                return array();
            }

            // Find the menu assigned to our location
            $menu  = get_term( $location, 'nav_menu' );

            // Get the menu items for the menu
            $items = wp_get_nav_menu_items( $menu->term_id );

            // Return those items to the requester
            return rest_ensure_response( $items );
        }
    }
}

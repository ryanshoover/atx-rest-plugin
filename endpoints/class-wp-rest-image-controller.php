<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists('ATX_REST_Image_Controller') ) {

    class ATX_REST_Image_Controller extends WP_REST_Controller {

        private $image_size;
        private $image_type;

        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes() {
            register_rest_route( 'atx/v1', '/image/(?P<imageid>[\d]+)/?', array(
                array(
                    'methods'  => WP_REST_Server::READABLE,
                    'callback' => array( $this, 'get_item' ),
                ),
            ) );
        }

        /**
         * Get the image from the provided ID
         *
         * @param  WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_item( $request ) {

            // Make sure we've got a REST response to work with
            $response = rest_ensure_response( false );

            // Get the image ID from the request
            $image_id = $request->get_param('imageid');

            // Maybe get the image content from our image ID
            if ( $image_id ) {
                $image = $this->get_image_contents( $image_id );
                $response->set_data( $image );
            }

            // Set up the headers needed to render the image
            $response->header( 'Content-Length', $this->image_size );
            $response->header( 'Content-Type', $this->image_type );

            // Return our response with the image content and headers
            return $response;
        }

        /**
         * Get an image's content
         * @param  integer $image_id The ID of the attachment
         * @return string            The contents of the image
         */
        private function get_image_contents( $image_id = 0 ) {
            // Get the post that matches our account name
            $posts = get_posts( array(
                'post_type' => 'attachment',
                'post__in'  => array( $image_id ),
                ) );

            // Abort if we didn't find anything
            if ( empty( $posts ) ) {
                return false;
            }

            $image = array_pop( $posts );

            // Abort if this isn't an image
            if ( ! stristr( $image->post_mime_type, 'image' ) ) {
                return false;
            }

            // Sanitize the image url
            $image_url = stripslashes( $image->guid );

            // Create the path to the image
            $path = ABSPATH . str_replace( trailingslashit( site_url() ), '', $image_url );

            // Find out basic info about the image
            $image_info = getimagesize( $path );

            // Set the mime type of our image
            $this->image_type = $image_info['mime'];

            // Set the filesize of our image
            $this->image_size = filesize( $path );

            // Open the image file
            $fp = fopen( $path, 'rb' );

            // Start capturing output
            ob_start();

            // Output all the content of the image file
            fpassthru( $fp );

            // Get the stored output
            $image_content = ob_get_clean();

            // Return the image content
            return $image_content;
        }
    }
}

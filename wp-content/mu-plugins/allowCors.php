<?php

/*
Plugin Name: AllowCors
Description: AllowCors requests for wp api.
Version:     1.0
Author:      Sebastian Thulin, Helsingborg Stad
*/

namespace AllowCors;

class AllowCors
{
    public function __construct() {
        add_action('rest_api_init', array($this, 'allowCors'), 15);
    }

    public function allowCors($value) {
        remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
        add_filter('rest_pre_serve_request', function( $value ) {
            header('Access-Control-Allow-Origin: *');
            return $value;
        });
    }
}
new AllowCors();

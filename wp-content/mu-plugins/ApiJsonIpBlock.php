<?php
    add_action('rest_api_init', function() {
        if (!in_array($_SERVER['REMOTE_ADDR'], array(
            '91.106.193.250'
        ))) {
            return;
        }

        error_log("Blocked api request from " . $_SERVER['REMOTE_ADDR'] . " (blocked manually)");

        wp_send_json(array(
            'code' => 'quey_limit_exceeded',
            'message' => __("Your ip-adress has been banned due to too many failed query's. This may be due to repeating large set of resutls. Please dcontact administrator to remove this persistent ban."),
            'data' => ['status' => 429]
        ), 429);
    });

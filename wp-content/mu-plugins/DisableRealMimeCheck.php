<?php

function wp39550_disable_real_mime_check($data, $file, $filename, $mimes)
{
    $wp_filetype = wp_check_filetype($filename, $mimes);

    $ext = $wp_filetype['ext'];
    $type = $wp_filetype['type'];
    $proper_filename = $data['proper_filename'];

    return compact('ext', 'type', 'proper_filename');
}

add_filter('wp_check_filetype_and_ext', 'wp39550_disable_real_mime_check', 10, 4);

<?php

/*
* @author SergeiVL
*/

require_once( dirname(__FILE__) . '/wp-load.php' );
require_once( dirname(__FILE__) . '/wp-admin/includes/admin.php' );


$post_data = array(
    'post_title'    => 'New test post with an additional field',
    'post_content'  => 'Content of the test post with an additional field',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_category' => array(1)
);

// Create a post with required fields.
$post_id = wp_insert_post($post_data, true);
print_r($post_id);

// Set the value for the additional field:
// For example, take the numeric field "rating"
// Let's set the value to 80
update_post_meta($post_id , 'rating', 80);

// Add the "PostScript" string field
update_post_meta($post_id , 'post-script', 'Thank you for attention. Subscribe to my blog');

// For example, take a picture from my blog
$url = 'http://sergeivl.ru/wp-content/uploads/2017/02/svoyblog-300x233.jpg';

// We will add it as the cover to the current post

$description = "Cover for my new post";
$file_array = array();
$tmp = download_url($url);

preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches );
$file_array['name'] = basename($matches[0]);
$file_array['tmp_name'] = $tmp;

$media_id = media_handle_sideload($file_array, $post_id, $description);

if (is_wp_error($media_id)) {
    @unlink($file_array['tmp_name']);
    echo $media_id->get_error_messages();
}

@unlink($file_array['tmp_name'] );

set_post_thumbnail($post_id, $media_id);
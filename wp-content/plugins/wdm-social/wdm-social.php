<?php
/**
 * Plugin Name: Windmaniacs Social
 * Description: Manage social features in Windmaniacs products.
 * Author: Marco Vito Moscaritolo <marco@agavee.com>
 * Version: 1.0
 */

function wdm_social_enabled_post_types($post_types) {
  $post_types['wpsc-product'] = 'wpsc-product';
  return $post_types;
}

function wdm_social_wp_head() {
  if (isset($_GET['social_notify']) and $_GET['social_notify'] == '1' & isset($_GET['post'])) {
    $post_id = $_GET['post'];
    $post = get_post($post_id);

    // Only available post
    if (!$post or $post->post_type != 'wpsc-product') {
      return;
    }

    $user_current = wp_get_current_user();
    $user_post = get_userdata($post->post_author);

    if ($user_current->ID === $user_post->ID) {
      // Updatesocialsharing
      update_post_meta($post_id, '_social_notify', '1');
      Social_Request::factory('broadcast/options')->post(array(
        'post_ID' => $post_id,
        'location' => $location,
      ))->execute();
    }
  }
}

add_filter('social_broadcasting_enabled_post_types', 'wdm_social_enabled_post_types');

add_filter('wp_head', 'wdm_social_wp_head');

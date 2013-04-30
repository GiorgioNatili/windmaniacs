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

add_filter('social_broadcasting_enabled_post_types', 'wdm_social_enabled_post_types');

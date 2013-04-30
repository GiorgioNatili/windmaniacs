<?php

/**
 * Plugin Name: Windmaniacs Permalink
 * Description: Overrides wpsc product permalinks
 * Author: Alessio Piazza, Marco Vito Moscaritolo
 * Version: 1.1
 */

function wdm_permalink_wpsc_product_permalink($permalink, $post_id = 0) {
  $permalink = str_replace('/advertisements/', '/p/', $permalink);
  $permalink = str_replace('/wdm-', '/', $permalink);

  return $permalink;
}

add_filter('wpsc_product_permalink', 'wdm_permalink_wpsc_product_permalink');

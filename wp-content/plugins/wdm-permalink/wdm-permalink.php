<?php

/*
  Plugin Name: Windmaniacs Permalink
  Description: Overrides wpsc product permalinks
  Author: Alessio Piazza
  Version: 1.0
 */


/**
 * Save per-post options
 *
 * @package CustomPermalinks
 * @since 0.1
 */
function wdm_permalinks_save_post($id) {

  $post = get_post($id);

  // Only applies to WPSC products, don't stop on permalinks of other CPTs
  // Fixes http://code.google.com/p/wp-e-commerce/issues/detail?id=271
  if ($post->post_type != 'wpsc-product')
    return $permalink;

  //delete old permalink
  delete_post_meta( $id, 'custom_permalink' );

  $post_name = $post->post_name;
  $product_categories = wp_get_object_terms($id, 'wpsc_product_category');

  if(is_wp_error($product_categories) || empty($product_categories))
    return ;

  $product_category_slugs = array();

  $permalink = array();
  $permalink[] = 'p';

  //remove wdm prefix from category slug
  foreach ( $product_categories as $product_category ) {
    $slug = str_replace('wdm-','',$product_category->slug);
    $slug = explode('-',$slug);
    $permalink = array_merge($permalink,$slug);
  }

  $permalink[] = $post_name;
  $permalink = implode('/',$permalink);

  add_post_meta($id, 'custom_permalink', $permalink);
}

function wdm_permalinks_save_category($id) {
  if ( !isset($_REQUEST['custom_permalinks_edit']) || isset($_REQUEST['post_ID']) ) return;
  $newPermalink = ltrim(stripcslashes($_REQUEST['custom_permalink']),"/");

  if ( $newPermalink == custom_permalinks_original_category_link($id) )
    $newPermalink = '';

  $term = get_term($id, 'wpsc_product_category');
  wdm_permalinks_save_term($term, str_replace('%2F', '/', urlencode($newPermalink)));
}

function wdm_permalinks_save_term($term, $permalink) {

  custom_permalinks_delete_term($term->term_id);
  $table = get_option('custom_permalink_table');
  if ($permalink) {
    $table[$permalink] = array(
      'id' => $term->term_id,
      'kind' => 'wpsc_product_category',
      'slug' => $term->slug
    );
  }

  update_option('custom_permalink_table', $table);
}


function wdm_permalinks_redirect() {
  // Get request URI, strip parameters
  $url = parse_url(get_bloginfo('url')); $url = $url['path'];
  $request = ltrim(substr($_SERVER['REQUEST_URI'], strlen($url)),'/');
  if ( ($pos=strpos($request, "?")) ) $request = substr($request, 0, $pos);

  global $wp_query;

  $custom_permalink = '';
  $original_permalink = '';

  if(is_tax('wpsc_product_category')){

    $theTerm = $wp_query->get_queried_object();
    $custom_permalink = custom_permalinks_permalink_for_term($theTerm->term_id);

  }

  if ( $custom_permalink  ) {

    // Append any query compenent
    wp_redirect( get_home_url()."/".$custom_permalink, 301 );
    exit();
  }


}


function wdm_permalinks_request($query){
  global $wp;
  global $_CPRegisteredURL;

  $originalUrl = NULL;


}

add_action( 'template_redirect', 'wdm_permalinks_redirect', 5 );
add_action( 'save_post', 'wdm_permalinks_save_post' );
add_action( 'edited_wpsc_product_category', 'wdm_permalinks_save_category');
//add_filter( 'request', 'wdm_permalinks_request', 11, 1 );

<?php
/*
  Plugin Name: Windmaniacs Create AD
  Description: UI for creating roducts using custom form
  Author: Marco Vito Moscaritolo <marco@agavee.com>
  Version: 1.0
 */

/**
 * Function to create filter.
 *
 * @param string $atts
 *   Attributes to parse
 *
 * @return string
 *   Form to display
 */
function wdm_create_ad_form( $atts ) {
  //var_dump($wp_scripts);
  // Identify options
  extract( shortcode_atts( array(
    'lang' => 'lang',
  ), $atts ) );

  // Identify javascript file path.
  $script_path = WP_PLUGIN_URL . '/wdm-create-ad/js/wdm-create-ad.js';

  // Add JS file.
  wp_enqueue_script(
    'wdm-create-ad',
    $script_path,
    array('jquery')
  );

  // Add JS localization.
  wp_localize_script('wdm-create-ad', 'WPWdmCreateAd', array(
    'todo' => 'need info?',
  ));

  // Get current step.
  $step = wdm_create_ad_form_get_step(1);

  // Go to next step.
  if ($_POST['action_prev']) {
    $step--;
  }
  if ($_POST['action_next']) {
    $step++;
  }

  $hidden_value  = array();
  $default_value = array();

  // ===========================================================================
  // Load current value
  // ===========================================================================
  switch (TRUE) {
    case $step <= 5: // ========================================================

    case $step <= 4: // ========================================================

    case $step <= 3: // ========================================================
      $default_value['wdm-condiction'] = wdm_create_ad_form_get('wdm-condiction', '');
      $default_value['wdm-year']       = wdm_create_ad_form_get('wdm-year',       '');
      $default_value['wdm-style']      = wdm_create_ad_form_get('wdm-style',      '');

    case $step <= 2: // ========================================================
      $default_value['wdm-category'] = wdm_create_ad_form_get('wdm-category', FALSE);
      $default_value['wdm-subcats']  = wdm_create_ad_form_get('wdm-subcats',  FALSE);

      $languages = qtrans_getSortedLanguages();
      foreach ($languages as $langcode) {
        $default_value['wdm-title-'       . $langcode] = wdm_create_ad_form_get('wdm-title-'       . $langcode, '');
        $default_value['wdm-description-' . $langcode] = wdm_create_ad_form_get('wdm-description-' . $langcode, '');
      }

      $default_value['wdm-price']      = wdm_create_ad_form_get('wdm-price'     , '');
      $default_value['wdm-sale_price'] = wdm_create_ad_form_get('wdm-sale_price', '');

    case $step <= 1: // ========================================================
      $default_value['wdm-type'] = wdm_create_ad_form_get('wdm-type', 'wdm-windsurf-boards');
   }

  // ===========================================================================
  // Load hidden value
  // ===========================================================================
  switch (TRUE) {
    case $step >= 6: // ========================================================
    case $step >= 5: // ========================================================
    case $step >= 4: // ========================================================
      $hidden_value['wdm-condiction'] = wdm_create_ad_form_get('wdm-condiction', '');
      $hidden_value['wdm-year']       = wdm_create_ad_form_get('wdm-year',       '');
      $hidden_value['wdm-style']      = wdm_create_ad_form_get('wdm-style',      '');

    case $step >= 3: // ========================================================
      $hidden_value['wdm-category']    = wdm_create_ad_form_get('wdm-category',    '');
      $hidden_value['wdm-subcats']     = wdm_create_ad_form_get('wdm-subcats',     '');
      $hidden_value['wdm-price']       = wdm_create_ad_form_get('wdm-price'     ,  '');
      $hidden_value['wdm-sale_price']  = wdm_create_ad_form_get('wdm-sale_price',  '');

      foreach (qtrans_getSortedLanguages() as $langcode) {
        $hidden_value['wdm-title-'       . $langcode] = wdm_create_ad_form_get('wdm-title-'       . $langcode, '');
        $hidden_value['wdm-description-' . $langcode] = wdm_create_ad_form_get('wdm-description-' . $langcode, '');
      }

    case $step >= 2: // ========================================================
      $hidden_value['wdm-type'] = wdm_create_ad_form_get('wdm-type', '');

    case $step >= 1: // ========================================================
  }

  // ===========================================================================
  // Load default value
  // ===========================================================================
  switch($step) {
    case 1: // =================================================================
      break;

    case 2: // =================================================================
      include_once(__DIR__ . '/../wdm-search/wdm-search.php');

      // Generate data for current step
      $custom_taxonomies = wdm_get_custom_taxonomies();
      foreach ($custom_taxonomies as $id => $term) {
        if ($hidden_value['wdm-type'] === 'wdm-windsurf-boards' && strpos($term->slug, 'wdm-windsurf') === 0) {
          $custom_taxonomies_tmp[$id] = $term;
        }
        if ($hidden_value['wdm-type'] === 'wdm-kitesurfing-boards' && strpos($term->slug, 'wdm-kitesurfing') === 0) {
          $custom_taxonomies_tmp[$id] = $term;
        }
      }

      // Required in template
      $custom_taxonomies = $custom_taxonomies_tmp;
      $variable_select   = wdm_get_variable_select($default_value['wdm-category']);
      $languages         = qtrans_getSortedLanguages();
      break;

    case 3: // =================================================================
      $condictions       = wdm_create_ad_terms('wdm-condition');
      $years             = wdm_create_ad_terms('wdm-year');
      $styles            = wdm_create_ad_terms('wdm-style');
      break;

    case 4: // =================================================================
      // Ensure to add file management item
      if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
      }

      for ($i = 0; $i < 5; $i++) {
        $data_key = "uploadfiles_$i";
        if (!empty($_FILES) && isset($_FILES[$data_key])) {
          $upload = wp_handle_upload($_FILES[$data_key], array('test_form' => FALSE));

          if (isset( $upload['error']) && '0' != $upload['error']) {
            wp_die( 'There was an error uploading your file. ' );
          } else {
            var_dump($upload);
            // update_post_meta( $id, $data_key, $upload );
          }
        }
      }
      break;

    case 5: // =================================================================
      // Create post..
      $titles = array();
      $descs = array();
      foreach (qtrans_getSortedLanguages() as $langcode) {
        $titles[$langcode] = wp_strip_all_tags($hidden_value['wdm-title-' . $langcode]);
        $descs [$langcode] = $hidden_value['wdm-description-' . $langcode];
      }

      // Create post structure
      $post = array(
        'post_title'    => wdm_create_ad_generate_translation_blob($titles),
        'post_content'  => wdm_create_ad_generate_translation_blob($descs),
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'     => 'wpsc-product',
      );

      // Save post
      $post_id = wp_insert_post($post, $wp_error);

      // Condictions
      $terms = array($hidden_value['wdm-condiction']);var_dump($terms);
      wp_set_post_terms($post_id, $terms, 'wdm-condition', FALSE);

      $terms = array($hidden_value['wdm-year']);var_dump($terms);
      wp_set_post_terms($post_id, $terms, 'wdm-year', FALSE);

      $terms = array($hidden_value['wdm-style']);var_dump($terms);
      wp_set_post_terms($post_id, $terms, 'wdm-style', FALSE);

      // Save price
      add_post_meta($post_id, '_wpsc_price',         $hidden_value['wdm-price']);
      add_post_meta($post_id, '_wpsc_special_price', $hidden_value['wdm-sale_price']);

      $_SESSION['wdm-create-ad-post_id'][] = $post_it;
      // IMAGES
      // wp_insert_attachment( $attachment, $filename, $parent_post_id );
      break;

  }

  // Add right mimetype in form (only for image upload)
  if ($step == 4) {
    $form_extra_attr = 'enctype="multipart/form-data"';
  }

//  $post_id = 480;
//  $post   = get_post($post_id);
//  var_dump($post);
//  $terms  = get_the_terms($post_id, 'wdm-style');
//  var_dump($terms);
//  $terms  = get_the_terms($post_id, 'wdm-year');
//  var_dump($terms);
//  $terms  = get_the_terms($post_id, 'wdm-condition');
//  var_dump($terms);
//

  include(__DIR__ . "/form/form-wrapper.php");
}

/**
 * Find form current step.
 *
 * @param int $default_step
 *   The default step to display.
 *
 * @return int
 *   The step to show
 */
function wdm_create_ad_form_get_step($default_step = 0) {
  $step = wdm_create_ad_form_get('wdm_create_ad_form_step', $default_step);
  $step = (int) $step;
  if ($step > 6 || $step < 1) {
    $step = $default_step;
  }

  return $step;
}

/**
 * Find form specified value.
 *
 * @param int $item
 *   The item element to find.
 * @param int $default_step
 *   The default value to display.
 *
 * @return int
 *   The step to show
 */
function wdm_create_ad_form_get($item, $default_step = 0) {
  return isset($_POST[$item]) ? $_POST[$item] : $default_step;
}

/**
 * Return string to make element a value.
 */
function wdm_create_ad_is_default($value, $default, $type = 'selected') {
  if ($default == $value) {
    return " $type=\"$type\"";
  }
}

/**
 * Generate term array to display information in form.
 *
 * @param string $vocabulary
 *   The vocabulary identifier
 *
 * @return array
 *   The terms list; array key is term ID and value the term name.
 */
function wdm_create_ad_terms($vocabulary) {
  $terms = get_terms($vocabulary, array('hide_empty' => FALSE));
  $return = array();

  foreach ($terms as $term) {
    $return[$term->term_id] = $term->name;
  }

  return $return;
}


// Create shortcode for the form
add_shortcode( 'wdm-create-ad', 'wdm_create_ad_form' );

function wdm_create_ad_generate_translation_blob($data) {
  $data_string = '';
  foreach ($data as $langcode => $value) {
    $data_string .= '<!--:' .  $langcode . '-->' .  $value . '<!--:-->';
  }
  return $data_string;
}

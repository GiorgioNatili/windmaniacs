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
      // Managed from external function - $default_value['wdm-subcats']

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
      $hidden_value['wdm-price']       = wdm_create_ad_form_get('wdm-price'     ,  '');
      $hidden_value['wdm-sale_price']  = wdm_create_ad_form_get('wdm-sale_price',  '');

      foreach (qtrans_getSortedLanguages() as $langcode) {
        $hidden_value['wdm-title-'       . $langcode] = wdm_create_ad_form_get('wdm-title-'       . $langcode, '');
        $hidden_value['wdm-description-' . $langcode] = wdm_create_ad_form_get('wdm-description-' . $langcode, '');
      }

      $variable_select = wdm_get_variable_select($hidden_value['wdm-category']);
      foreach ($variable_select as $pos => $term) {
        $data = wdm_create_ad_form_get($term->taxonomy, '');
        $hidden_value["{$term->taxonomy}[$pos]"] = $data[$pos];
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

    case 5: // =================================================================
      // Ensure to add file management item
      if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
      }

      // Empty loaded files
      $_SESSION['wdm_loaded_files'] = array();

      for ($i = 0; $i < 5; $i++) {
        $data_key = "uploadfiles_$i";
        if (!empty($_FILES) && isset($_FILES[$data_key]) && $_FILES[$data_key]['size'] > 0) {
          $upload = wp_handle_upload($_FILES[$data_key], array('test_form' => FALSE));
          if (isset( $upload['error']) && '0' != $upload['error']) {
            wp_die( 'There was an error uploading your file. ' );
          } else {
            $_SESSION['wdm_loaded_files'][] = $upload;
          }
        }
      }
      // break;

//    case 5: // =================================================================
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
        'post_author'   => get_current_user_id(),
        'post_type'     => 'wpsc-product',
      );

      // Save post
      $post_id = wp_insert_post($post, $wp_error);

      // Category
      $product_category = get_term_by('slug', str_replace('ing-', '-', $hidden_value['wdm-type']), 'wpsc_product_category');
      $terms = array($product_category->term_id);
      wp_set_post_terms($post_id, $terms, 'wpsc_product_category', FALSE);

      // Sub category
      $product_sub_category = array();
      foreach (wdm_create_ad_form_get($hidden_value['wdm-category']) as $value) {
        $term = get_term_by('slug', $value, $hidden_value['wdm-category']);
        $product_sub_category[] = $term->term_id;
      }
      wp_set_post_terms($post_id, $product_sub_category, $hidden_value['wdm-category'], FALSE);

      // Condictions
      $terms = array($hidden_value['wdm-condiction']);
      wp_set_post_terms($post_id, $terms, 'wdm-condition', FALSE);

      // Year
      $terms = array($hidden_value['wdm-year']);
      wp_set_post_terms($post_id, $terms, 'wdm-year', FALSE);

      // Style
      $terms = array($hidden_value['wdm-style']);
      wp_set_post_terms($post_id, $terms, 'wdm-style', FALSE);

      // Price
      add_post_meta($post_id, '_wpsc_price', $hidden_value['wdm-price']);

      // Sale price
      add_post_meta($post_id, '_wpsc_special_price', $hidden_value['wdm-sale_price']);
      var_dump($_SESSION['wdm_loaded_files']);
      if (isset($_SESSION['wdm_loaded_files']) && is_array($_SESSION['wdm_loaded_files'])) {
        // you must first include the image.php file
        // for the function wp_generate_attachment_metadata() to work
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        foreach ($_SESSION['wdm_loaded_files'] as $upload) {
          var_dump($upload);
          // Create attachment post
          $attachment = array(
             'guid' => $upload['path'],
             'post_mime_type' => $upload['type'],
             'post_title' => $upload['filename'],
             'post_content' => '',
             'post_status' => 'inherit'
          );
          $attach_id = wp_insert_attachment( $attachment, $upload['filename']);
          $attach_data = wp_generate_attachment_metadata( $attach_id, $upload['filename'], $post_id);
          wp_update_attachment_metadata( $attach_id, $attach_data );
        }
        // Reset attachments data
        unset($_SESSION['wdm_loaded_files']);
      }

      // $_SESSION['wdm-create-ad-post_id'][] = $post_id;

      $salt = 'dslkfhadskfsdkf4534asdfasdf325423';
      $destination_url = get_permalink() . '?' . http_build_query(array(
        'wdm_data'  => $post_id . '-' . md5($post_id . $salt)
      ));
      $destination_url = urlencode($destination_url);
      break;
  }

  // Add right mimetype in form (only for image upload)
  if ($step == 4) {
    $form_extra_attr = 'enctype="multipart/form-data"';
  }

  // Dispaly reload button only on specified steps
  $button_current = ($step == 2);

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
 * @param int $default_value
 *   The default value to display.
 *
 * @return int
 *   The step to show
 */
function wdm_create_ad_form_get($item, $default_value = 0) {
  return isset($_POST[$item]) ? $_POST[$item] : $default_value;
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

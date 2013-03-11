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

  // Add JS file.
  $script_path = WP_PLUGIN_URL . '/wdm-create-ad/js/wdm-create-ad.js';

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

  $default_value = array();
  $hidden_value  = array();

  switch($step) {
    case 1:
      // Not required
      break;
    case 2:
      include WP_PLUGIN_URL . '/wdm-search/wdm-search.php';
      // Load data from prev step
      $hidden_value['wdm-type'] = wdm_create_ad_form_get('wdm-type',     FALSE);

      // Load data from current step
      $default_value['wdm-category'] = wdm_create_ad_form_get('wdm-category', FALSE);
      $default_value['wdm-subcats']  = wdm_create_ad_form_get('wdm-subcats',  FALSE);

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
      break;
    case 3:
      // Load data from prev step
      $hidden_value['wdm-type']     = wdm_create_ad_form_get('wdm-type',     FALSE);
      $hidden_value['wdm-category'] = wdm_create_ad_form_get('wdm-category', FALSE);
      $hidden_value['wdm-subcats']  = wdm_create_ad_form_get('wdm-subcats',  FALSE);

      // Load data for current step
      $default_value['wdm-title']       = wdm_create_ad_form_get('wdm-title',       array());
      $default_value['wdm-description'] = wdm_create_ad_form_get('wdm-description', array());
      $default_value['wdm-price']       = wdm_create_ad_form_get('wdm-price',       array());
      $default_value['wdm-sales_price'] = wdm_create_ad_form_get('wdm-sales_price', array());

      // Required in template
      $languages = qtrans_getSortedLanguages();
      break;

  }

  // Load form element.
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
  if ($step > 7 || $step < 1) {
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

add_shortcode( 'wdm-create-ad', 'wdm_create_ad_form' );

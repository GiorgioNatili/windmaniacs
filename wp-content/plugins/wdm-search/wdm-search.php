<?php
/*
  Plugin Name: Windmaniacs Search
  Description: Plugin for displaying custom products
  Author: Alessio Piazza
  Version: 1.0
 */

global $wpdb;

if (!defined('WP_CONTENT_URL'))
  define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
  define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if (!defined('WP_PLUGIN_URL'))
  define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if (!defined('WP_PLUGIN_DIR'))
  define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');

//Define and get plugin settings
function wdm_get_settings($from_db = FALSE){

  $settings = array(
    'wdm-focus'             => 'All',
    'wdm-hide-empty'        => '0',
    'wdm-exclude-cats'      => array(),
    'wdm-exclude-child'     => '0',
    'wdm-search-text'       => 'Search',
    'wdm-raw-excluded-cats' => array()
  );

  if ($from_db) {
    $db_settings = array();
    foreach ($settings as $key => $value) {
      $db_settings[$key] = get_option($key);
    }
    return $db_settings;
  } else {
    return $settings;
  }
}

//save settings into db
function wdm_set_settings($settings){
  foreach ($settings as $key => $value) {
    add_option($key,$value);
  }
}

//update settings into db
function wdm_update_settings($settings){
  foreach ($settings as $key => $value) {
    update_option($key,$value);
  }
}

//define new config page
function wdm_config_page(){
  if (function_exists('add_submenu_page')) {
    add_options_page('Windmaniacs Custom options product search','BK4 product Search',10,'wdm-search','config_page');
  }
}

//Callback function for 4k_config_page -> add_options_page
function config_page(){
  if (isset($_POST['wdm-settings-submit'])) {

    $nonce = $_REQUEST['_wpnonce'];
    if (!wp_verify_nonce($nonce,'wdm-updatesettings')) die('Security error');
    if (!current_user_can('manage_options')) die('Cannot edit options');

    $default_settings = wdm_get_settings($from_db = FALSE);
    $new_settings = array();

    //be sure only default key settings can be saved
    foreach ($default_settings as $key => $value) {
      if (array_key_exists($key,$_POST)) {
        $new_settings[$key] = mysql_real_escape_string($_POST[$key]);
      }

      if (!isset($_POST['wdm-hide-empty'])) {
        $new_settings['wdm-hide-empty'] = '0';
      }
    }

    //save settings to the db
    wdm_update_settings($new_settings);
  }

  $current_settings = wdm_get_settings($from_db = TRUE);

  include(WP_PLUGIN_DIR.'/wdm-search/wdm-options.php');
}

function wdm_search_form($wdmcat = 'wdm-windsurf-boards'){
  (qtrans_getLanguage() == 'it') ? $board = 'tavole' : $board = 'boards';
  $wdm_settings = wdm_get_settings($from_db = TRUE);
  $custom_taxonomies = wdm_get_custom_taxonomies();
  isset($_GET['wdm-category']) ? $wdmcat = $_GET['wdm-category'] : FALSE ;
  $variable_select = wdm_get_variable_select($wdmcat);
  //Show hide menuTab
  if (strpos($wdmcat,'windsurf')) {
    $hide_menutab_kite = TRUE;
  }
  if (strpos($wdmcat,'kitesurf')) {
    $hide_menutab_surf = TRUE;
  }

  $form = include('wdm-form.php');
  return $form;
}

//Generate form select from category id
function wdm_form_select($category_name,$category_id,$label,$term_key,$parent_id = FALSE, $sort = FALSE, $is_main = FALSE, $subkey = NULL){
  global $wpdb;

  /*if(!is_numeric($category_id)) {
    return;
  }*/

  if(!$category_name) {
    $table_name = WDM_TABLE_NAME;
    $query = "SELECT slug FROM $table_name WHERE id = $category_id";
    $res = $wpdb->get_row($query, OBJECT);
    $category_name = $res->slug;
  }

  $settings = wdm_get_settings($from_db = TRUE);
  ($parent_id) ? $parent = '&parent='.$parent_id: $parent = '';
  $categories = get_terms($category_name, 'hide_empty='.$wdm['wdm-hide-empty'].$parent);
  $temp_terms = array();

  foreach ($categories as $category) {
    $temp_terms[$category->slug] = $category->name;
  }

  if ($sort) {
    if ($sort == 'ASC') {
      natsort($temp_terms);
    }
    if ($sort == 'DESC') {
      natsort($temp_terms);
      $temp_terms = array_reverse($temp_terms,TRUE);
    }
  }

  //ADD a [] to the select name allowing multiple parameters submissions
  ($is_main) ? $key = $term_key . "[$subkey]" : $key = $term_key;

  $select = '<div class="select" id="'.$term_key.'"><label for="'.$term_key.'">'.$label.'</label><select name="'.$key. '">';
  $select .= '<option value="" '.$selected.' >'.$label.'</option>';

  //keep selected options visible for basic taxonomies
  foreach ($temp_terms as $slug => $name) {
    $selected = '';

    if(is_string($_GET[$term_key]) && $_GET[$term_key] == $slug) {
      $selected = 'selected="selected"';
    }

    if(is_array($_GET[$term_key])){
      foreach ($_GET[$term_key] as $k => $name2) {
        //set default value for variable taxonomies
        if(is_numeric($k)) {
          if($_GET[$term_key][$k] == $slug){
            $selected = 'selected="selected"';
          }
        }
        //Set default values for ranges common taxonomies
        if(is_string($k)) {
          if($_GET[$term_key][$subkey] == $slug){
            $selected = 'selected="selected"';
          }
        }
      }
    }
    $select .= '<option value="'.$slug.'" '.$selected.' >'.$name.'</option>';
  }

  $select .= '</select></div>';

  return $select;
}

function wdm_form_menu() {
  $taxonomies = wdm_get_custom_taxonomies();
  print_r($taxonomies);
}

function _wdm_get_max_taxonomy_value($category_name){
  $categories = get_terms($category_name, array('orderby' => 'slug','hide_empty' => 0));

  $temp_terms = array();

  foreach ($categories as $key => $term) {
    $temp_terms[] = $term->slug;
  }
  reset(natsort($temp_terms));

  return end($temp_terms);
}

function _wdm_get_min_taxonomy_value($category_name){
  $categories = get_terms($category_name, array('orderby' => 'slug','hide_empty' => 0));

  $temp_terms = array();

  foreach ($categories as $key => $term) {
    $temp_terms[] = $term->slug;
  }

  natsort($temp_terms);

  $first_term = array_slice($temp_terms,0,1);

  return $first_term[0];
}

function wdm_form_select_range($id, $label, $values, $sort = 'ASC', $subkey = NULL){

  if ($sort == 'DESC'){
    $values = array_reverse($values, TRUE);
  }

  $select = '<div class="select" id="'.$id.'"><label for="'.$id.'">'.$label.'</label><select name="'.$id. '['.$subkey.']">';
  $select .= '<option value="" '.$selected.' >'.$label.'</option>';

  foreach ($values as $key => $value){
    $selected = '';

    if(is_array($_GET[$id])){
      foreach ($_GET[$id] as $k => $name) {
        if($_GET[$id][$subkey] == $key){
          $selected = 'selected="selected"';
        }
      }
    }

    $select .= '<option value="'.$key.'" '.$selected.' >'.$value.'</option>';
  }

  $select .= '</select></div>';

  return $select;
}

//Add settings menu entry
add_action('admin_menu', 'wdm_config_page');

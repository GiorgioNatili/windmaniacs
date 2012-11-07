<?php
/*
  Plugin Name: Bid4Kite Search
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
function b4k_get_settings($from_db = FALSE){

  $settings = array(
    'b4k-focus'             => 'All',
    'b4k-hide-empty'        => '0',
    'b4k-exclude-cats'      => array(),
    'b4k-exclude-child'     => '0',
    'b4k-search-text'       => 'Search',
    'b4k-raw-excluded-cats' => array()
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
function b4k_set_settings($settings){
  foreach ($settings as $key => $value) {
    add_option($key,$value);
  }
}

//update settings into db
function b4k_update_settings($settings){
  foreach ($settings as $key => $value) {
    update_option($key,$value);
  }
}

//define new config page
function b4k_config_page(){
  if (function_exists('add_submenu_page')) {
    add_options_page('BK4 Custom options product search','BK4 product Search',10,'bk4-search','config_page');
  }
}

//Callback function for 4k_config_page -> add_options_page
function config_page(){
  if (isset($_POST['b4k-settings-submit'])) {

    $nonce = $_REQUEST['_wpnonce'];
    if (!wp_verify_nonce($nonce,'b4k-updatesettings')) die('Security error');
    if (!current_user_can('manage_options')) die('Cannot edit options');

    $default_settings = b4k_get_settings($from_db = FALSE);
    $new_settings = array();

    //be sure only default key settings can be saved
    foreach ($default_settings as $key => $value) {
      if (array_key_exists($key,$_POST)) {
        $new_settings[$key] = mysql_real_escape_string($_POST[$key]);
      }

      if (!isset($_POST['b4k-hide-empty'])) {
        $new_settings['b4k-hide-empty'] = '0';
      }
    }

    //save settings to the db
    b4k_update_settings($new_settings);
  }

  $current_settings = b4k_get_settings($from_db = TRUE);

  include(WP_PLUGIN_DIR.'/b4k-search/b4k-options.php');
}

function b4k_search_form(){
  (qtrans_getLanguage() == 'it') ? $board = 'tavole' : $board = 'boards';
  $b4k_settings = b4k_get_settings($from_db = TRUE);
  $custom_taxonomies = b4ktm_get_custom_taxonomies();
  isset($_GET['ctid']) ? $ctid = $_GET['ctid'] : $ctid = 4;
  $variable_select = b4ktm_get_variable_select($ctid);

  $form = include('b4k-form.php');
  return $form;
}

//Generate form select from category id
function b4k_form_select($category_name,$category_id,$label,$term_key,$parent_id = FALSE, $sort = FALSE){
  global $wpdb;

  if(!is_numeric($category_id)) {
    return;
  }

  if(!$category_name) {
    $table_name = B4KTM_TABLE_NAME;
    $query = "SELECT slug FROM $table_name WHERE id = $category_id";
    $res = $wpdb->get_row($query, OBJECT);
    $category_name = $res->slug;
  }

  $settings = b4k_get_settings($from_db = TRUE);
  ($parent_id) ? $parent = '&parent='.$parent_id: $parent = '';
  $categories = get_terms($category_name, 'hide_empty='.$b4k['b4k-hide-empty'].$parent);
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

  $select = '<div class="select" id="'.$term_key.'"><label for="'.$term_key.'">'.$label.'</label><select name="'.$term_key.'">';
  $select .= '<option value="" '.$selected.' >'.$label.'</option>';

  foreach ($temp_terms as $key => $name) {
    ($key == $_POST[$term_key]) ? $selected = 'selected="selected"' : $selected = '';
    $select .= '<option value="'.$key.'" '.$selected.' >'.$name.'</option>';
  }

  $select .= '</select></div>';

  return $select;
}

function b4k_form_menu() {
  $taxonomies = b4ktm_get_custom_taxonomies();
  print_r($taxonomies);
}

function _b4k_get_max_taxonomy_value($category_name){
  $categories = get_terms($category_name, array('orderby' => 'slug','hide_empty' => 0));

  $temp_terms = array();

  foreach ($categories as $key => $term) {
    $temp_terms[] = $term->slug;
  }
  reset(natsort($temp_terms));

  return end($temp_terms);
}

function _b4k_get_min_taxonomy_value($category_name){
  $categories = get_terms($category_name, array('orderby' => 'slug','hide_empty' => 0));

  $temp_terms = array();

  foreach ($categories as $key => $term) {
    $temp_terms[] = $term->slug;
  }

  natsort($temp_terms);

  $first_term = array_slice($temp_terms,0,1);

  return $first_term[0];
}

/*
//define new custom taxonomy
function create_product_year_taxonomy(){
  register_taxonomy('productyear',array('wpsc-product'), array(
      'label' => 'Anno prodotto',
      'name' =>  _x('Product year', 'taxonomy general name'),
      'hierarchical' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'productyear' ),
      'menu_name' => __( 'Product Year' ),
   ));
}

function create_product_size_taxonomy(){
  register_taxonomy('productsize',array('wpsc-product'), array(
      'label' => 'Misura prodotto',
      'name' =>  _x('Product size', 'taxonomy general name'),
      'hierarchical' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'productsize' ),
      'menu_name' => __( 'Product Size' ),
   ));
}

function create_product_status_taxonomy(){
  register_taxonomy('productstatus',array('wpsc-product'), array(
      'label' => 'Condizione prodotto',
      'name' =>  _x('Product status', 'taxonomy general name'),
      'hierarchical' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'productstatus' ),
      'menu_name' => __( 'Product Status' ),
   ));
}

function create_product_brand_taxonomy(){
  register_taxonomy('productbrand',array('wpsc-product'), array(
      'label' => 'Marca prodotto',
      'name' =>  _x('Product brand', 'taxonomy general name'),
      'hierarchical' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'productbrand' ),
      'menu_name' => __( 'Product Brand' ),
   ));
}

//remove taxonomy
//function remove_custom_taxonomy(){
//  register_taxonomy('productyear', array());
//}

//Save db settings
b4k_set_settings(b4k_get_settings(FALSE));

//register new custom taxonomy
add_action('init','create_product_year_taxonomy',0);
add_action('init','create_product_size_taxonomy',0);
add_action('init','create_product_status_taxonomy',0);
add_action('init','create_product_brand_taxonomy',0);
 */
//add_action('init','remove_taxonomy');

//Add settings menu entry
add_action('admin_menu', 'b4k_config_page');

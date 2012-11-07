<?php
/*
  Plugin Name: Bid4Kite Taxonomy Manager
  Description: UI for creating custom taxonomy for PRODUCTS
  Author: Alessio Piazza
  Version: 1.0
 */

global $b4ktm_version;
global $wpdb;
$b4ktm_version = '1.0';

if (!defined('WP_CONTENT_URL'))
  define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
  define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if (!defined('WP_PLUGIN_URL'))
  define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if (!defined('WP_PLUGIN_DIR'))
  define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
if(!define('B4KTM_TABLE_NAME'))
  define('B4KTM_TABLE_NAME', $wpdb->prefix . 'b4k_taxonomy_manager');

function b4ktm_install(){
  global $wpdb;
  global $b4ktm_version;

  $table_name = B4KTM_TABLE_NAME;

  //define B4K taxonomy manager table
  $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    time timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    slug tinytext NOT NULL,
    label tinytext NOT NULL,
    enabled tinyint NOT NULL,
    UNIQUE KEY id (id)
  )
  CHARACTER SET utf8 COLLATE utf8_general_ci;";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);

  add_option("b4ktm_db_version", $b4ktm_version);
}

function b4ktm_get_custom_taxonomies($enabled = TRUE){
  global $wpdb;

  //get all saved taxonomies
  $table_name = B4KTM_TABLE_NAME;

  $query = "SELECT * FROM $table_name";
  if ($enabled) {
    $query .= " WHERE enabled = 1 AND enabled != 2";
  } else {
    $query .= " WHERE enabled != 2";
  }
  $query .= " ORDER BY id";

  $res = $wpdb->get_results($query, OBJECT);

  return $res;
}

function b4ktm_get_custom_taxonomy($taxonomy_slug) {
  global $wpdb;
  $table_name = B4KTM_TABLE_NAME;
  $query = "SELECT id,slug,label FROM $table_name WHERE slug = '$taxonomy_slug' AND enabled = 1";
  $res = $wpdb->get_row($query, OBJECT);

  return $res;
}

function b4ktm_get_variable_select($cid) {
  global $wpdb;
  $table_name = B4KTM_TABLE_NAME;
  $query = "SELECT t.term_id, t.name, t.slug, tt.taxonomy, tt.parent, btm.id
            FROM wp_terms as t
            LEFT JOIN wp_term_taxonomy as tt on t.term_id = tt.term_id
            LEFT JOIN wp_b4k_taxonomy_manager as btm on tt.taxonomy = btm.slug
            where btm.id = $cid and tt.parent = 0";
  $res = $wpdb->get_results($query, OBJECT);

  return $res;
}

function b4ktm_check_slug($slug){
  global $wpdb;

  $table_name = B4KTM_TABLE_NAME;
  $query = "SELECT id FROM $table_name WHERE slug = $slug";
  $res = $wpdb->get_row($query, OBJECT);

  return $res;
}

function b4ktm_post_custom_taxonomies($custom_taxonomy){
  global $wpdb;

  $table_name = B4KTM_TABLE_NAME;
  $rows_affected = $wpdb->insert($table_name, array( 'time' => current_time('mysql'), 'slug' => $custom_taxonomy['slug'], 'label' => $custom_taxonomy['label'], 'enabled' => 1 ));

  return $rows_affected;
}

function b4ktm_update_custom_taxonomies($taxonomy_ids,$action){
  global $wpdb;

  if (is_array($taxonomy_ids)) {
    foreach ($taxonomy_ids as $tid) {
      $rows_updated = $wpdb->update(
        B4KTM_TABLE_NAME,
        array(
          'enabled' => $action  // integer (number)
        ),
        array('id' => $tid),
        array(
          '%d'
        ),
        array(
          '%d'
        )
      );
    }
  }

  return $rows_updated;
}

function b4ktm_alert_message($text){
  $message = "<script type=\"text/javascript\">
    jQuery().ready(function(){
      jQuery('.wrap > h2').parent().prev().after('<div class=\"update-nag\" style=\"margin-top:10px;\">$text</div>');
});
  </script>";

  return $message;
}

//define new config page
function b4k_taxonomymanager_config_page(){
  if (function_exists('add_submenu_page')) {
    add_options_page('BK4 Taxonomy manager','BK4 Taxonomy manager',10,'bk4-taxonomymanager','taxonomy_config_page');
  }
}

//Callback function for 4k_config_page -> add_options_page
function taxonomy_config_page(){

  if (isset($_POST['b4ktm-submit'])) {

    $custom_taxonomy = array();

    $custom_taxonomy['slug'] = 'b4ktm-'.mysql_real_escape_string($_POST['b4ktm-slug']);
    $custom_taxonomy['label'] = mysql_real_escape_string($_POST['b4ktm-label']);

    if (!empty($custom_taxonomy['slug']) && !empty($custom_taxonomy['label'])) {

      //check for already used slug
      $check_slug = b4ktm_check_slug($custom_taxonomy['slug']);

      if (empty($check_slug)) {

        $rows_affected = b4ktm_post_custom_taxonomies($custom_taxonomy);

        if ($rows_affected) {
          echo b4ktm_alert_message('tassonomia '.$custom_taxonomy['label'].' inserita');
        } else {
          echo b4ktm_alert_message('tassonomia NON inserita');
        }
      }
    }
  }

  if (isset($_POST['b4ktm-update'])) {
    $taxonomy_ids = array_map('mysql_real_escape_string',$_POST['b4ktm-id']);
    $action = mysql_real_escape_string($_POST['b4ktm-action']);
    $rows_updated = b4ktm_update_custom_taxonomies($taxonomy_ids,$action);

    if ($rows_updated) {
      echo b4ktm_alert_message('tassonomia aggiornata');
    }
  }

  $b4ktm_taxonomies = b4ktm_get_custom_taxonomies(FALSE);

  include(WP_PLUGIN_DIR.'/b4k-taxonomymanager/b4k-taxonomymanager-options.php');
}

//register custom taxonomies
function b4ktm_load_custom_taxonomies(){
  $b4ktm_taxonomies = b4ktm_get_custom_taxonomies();

  if (!empty($b4ktm_taxonomies)) {
      foreach ($b4ktm_taxonomies as $taxonomy) {
      register_taxonomy($taxonomy->slug,array('wpsc-product'), array(
          'label' => $taxonomy->label,
          'name' =>  _x($taxonomy->label, 'taxonomy general name'),
          'hierarchical' => true,
          'show_ui' => true,
          'query_var' => true,
          'rewrite' => array( 'slug' => $taxonomy->slug ),
          'menu_name' => __( $taxonomy->label ),
       ));
    }
  }
}

//load custom taxonomies
add_action('init','b4ktm_load_custom_taxonomies',0);

//install b4ktm table
register_activation_hook(__FILE__,'b4ktm_install');

//Add settings menu entry
add_action('admin_menu', 'b4k_taxonomymanager_config_page');

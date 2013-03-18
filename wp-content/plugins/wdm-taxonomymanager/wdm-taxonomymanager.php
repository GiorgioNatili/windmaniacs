<?php
/*
  Plugin Name: Windmaniacs Taxonomy Manager
  Description: UI for creating custom taxonomy for PRODUCTS
  Author: Alessio Piazza
  Version: 1.0
 */

global $wdm_version;
global $wpdb;
$wdm_version = '1.0';

if (!defined('WP_CONTENT_URL'))
  define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
  define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if (!defined('WP_PLUGIN_URL'))
  define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if (!defined('WP_PLUGIN_DIR'))
  define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
if(!defined('WDM_TABLE_NAME'))
  define('WDM_TABLE_NAME', $wpdb->prefix . 'wdm_taxonomy_manager');

function wdm_install(){
  global $wpdb;
  global $wdm_version;

  $table_name = WDM_TABLE_NAME;

  //define Windmaniacs taxonomy manager table
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

  add_option("wdm_db_version", $wdm_version);
}

function wdm_get_custom_taxonomies($enabled = TRUE){
  global $wpdb;

  //get all saved taxonomies
  $table_name = WDM_TABLE_NAME;

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

function wdm_get_custom_taxomoies_slug($enabled = TRUE){
  global $wpdb;

  $res = array();

  //get all saved taxonomies
  $table_name = WDM_TABLE_NAME;

  $query = "SELECT slug FROM $table_name";
  if ($enabled) {
    $query .= " WHERE enabled = 1 AND enabled != 2";
  } else {
    $query .= " WHERE enabled != 2";
  }
  $query .= " ORDER BY id";

  $results = $wpdb->get_results($query, ARRAY_A);

  foreach ($results as $row){
    $res[] = $row['slug'];
  }

  return $res;
}

function wdm_get_custom_taxonomy($taxonomy_slug) {
  global $wpdb;
  $table_name = WDM_TABLE_NAME;
  $query = "SELECT id,slug,label FROM $table_name WHERE slug = '$taxonomy_slug' AND enabled = 1";
  $res = $wpdb->get_row($query, OBJECT);

  return $res;
}

function wdm_get_variable_select($slug, $parent = 0) {
  global $wpdb;
  $table_name = WDM_TABLE_NAME;

  $query = "SELECT t.term_id, t.name, t.slug, tt.taxonomy, tt.parent, wdm.id
            FROM wp_terms as t
            LEFT JOIN wp_term_taxonomy as tt on t.term_id = tt.term_id
            LEFT JOIN wp_wdm_taxonomy_manager wdm on tt.taxonomy = wdm.slug
            where wdm.slug = '$slug' and tt.parent = $parent
            GROUP BY t.term_id ORDER BY t.name";

  $res = $wpdb->get_results($query, OBJECT);

  return $res;
}

function wdm_check_slug($slug){
  global $wpdb;

  $table_name = WDM_TABLE_NAME;
  $query = "SELECT id FROM $table_name WHERE slug = '$slug'";

  $res = $wpdb->get_row($query, OBJECT);

  return $res;
}

function wdm_post_custom_taxonomies($custom_taxonomy){
  global $wpdb;

  $table_name = WDM_TABLE_NAME;
  $rows_affected = $wpdb->insert($table_name, array( 'time' => current_time('mysql'), 'slug' => $custom_taxonomy['slug'], 'label' => $custom_taxonomy['label'], 'enabled' => 1 ));

  return $rows_affected;
}

function wdm_update_custom_taxonomies($taxonomy_ids,$action){
  global $wpdb;

  if (is_array($taxonomy_ids)) {
    foreach ($taxonomy_ids as $tid) {
      $rows_updated = $wpdb->update(
        WDM_TABLE_NAME,
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

function wdm_alert_message($text){
  $message = "<script type=\"text/javascript\">
    jQuery().ready(function(){
      jQuery('.wrap > h2').parent().prev().after('<div class=\"update-nag\" style=\"margin-top:10px;\">$text</div>');
});
  </script>";

  return $message;
}

//define new config page
function wdm_taxonomymanager_config_page(){
  if (function_exists('add_submenu_page')) {
    add_options_page('Windmaniacs Taxonomy manager','Windmaniacs Taxonomy manager',10,'wdm-taxonomymanager','taxonomy_config_page');
  }
}

//Callback function for 4k_config_page -> add_options_page
function taxonomy_config_page(){

  if (isset($_POST['wdm-submit'])) {

    $custom_taxonomy = array();

    $custom_taxonomy['slug'] = 'wdm-'.mysql_real_escape_string($_POST['wdm-slug']);
    $custom_taxonomy['label'] = mysql_real_escape_string($_POST['wdm-label']);

    if (!empty($custom_taxonomy['slug']) && !empty($custom_taxonomy['label'])) {

      //check for already used slug
      $check_slug = wdm_check_slug($custom_taxonomy['slug']);

      if (empty($check_slug)) {

        $rows_affected = wdm_post_custom_taxonomies($custom_taxonomy);

        if ($rows_affected) {
          echo wdm_alert_message('tassonomia '.$custom_taxonomy['label'].' inserita');
        } else {
          echo wdm_alert_message('tassonomia NON inserita');
        }
      } else {
        echo wdm_alert_message('tassonomia '.$custom_taxonomy['slug'].' già presente!');
      }
    }
  }

  if (isset($_POST['wdm-update'])) {
    $taxonomy_ids = array_map('mysql_real_escape_string',$_POST['wdm-id']);
    $action = mysql_real_escape_string($_POST['wdm-action']);
    $rows_updated = wdm_update_custom_taxonomies($taxonomy_ids,$action);

    if ($rows_updated) {
      echo wdm_alert_message('tassonomia aggiornata');
    }
  }

  $wdm_taxonomies = wdm_get_custom_taxonomies(FALSE);

  include(WP_PLUGIN_DIR.'/wdm-taxonomymanager/wdm-taxonomymanager-options.php');
}

//register custom taxonomies
function wdm_load_custom_taxonomies(){
  $wdm_taxonomies = wdm_get_custom_taxonomies();

  if (!empty($wdm_taxonomies)) {
      foreach ($wdm_taxonomies as $taxonomy) {
      register_taxonomy($taxonomy->slug,array('wpsc-product'), array(
          'label' => $taxonomy->label,
          'name' =>  _x($taxonomy->label, 'taxonomy general name'),
          'hierarchical' => true,
          'show_ui' => true,
          'query_var' => $taxonomy->slug,
          'rewrite' => array( 'slug' => $taxonomy->slug ),
          'menu_name' => __( $taxonomy->label ),
       ));
    }
  }
}

//load custom taxonomies
add_action('init','wdm_load_custom_taxonomies',0);

//install Windmaniacs table
register_activation_hook(__FILE__,'wdm_install');

//Add settings menu entry
add_action('admin_menu', 'wdm_taxonomymanager_config_page');


/*styling input see: http://wordpress.stackexchange.com/questions/7456/altering-the-appearance-of-custom-taxonomy-inputs
add_action('add_meta_boxes', 'b4ktm_add_meta_boxes',10,2);

function b4ktm_add_meta_boxes($post_type,$post){
  ob_start();
}

add_action('dbx_post_sidebar','b4ktm_dbx_post_sidebar');

function b4ktm_dbx_post_sidebar(){
  $html = ob_get_clean();
  $html = str_replace('"checkbox"','"radio"',$html);
  echo $html;
}*/

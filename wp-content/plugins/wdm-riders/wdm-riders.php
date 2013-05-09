<?php
/*
  Plugin Name: Windmaniacs Riders
  Description: Riders
  Author: Marco Vito Moscaritolo <marco@agavee.com>
  Version: 1.0
 */

function wdm_riders_create_post_type() {
  $labels = array(
    'name' => __( 'Riders' ),
    'singular_name' => __( 'Rider' )
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'has_archive' => true,
    'suppports' => array(
      'title',
      'editor',
      'author',
      'thumbnail',
      'excerpt',
      'trackbacks',
    ),
  );

  register_post_type( 'riders', $args);
}

function wdm_riders_register_meta_box() {
  // Check if plugin is activated or included in theme
  if ( !class_exists( 'RW_Meta_Box' ) ) {
    return;
  }
  $prefix = 'wdmr_';
  $meta_box = array(
    'id' => 'riders',
    'title' => 'Rider Information',
    'pages' => array( 'riders' ),
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
      array(
        'name' => 'Sail',
        'desc' => 'Insert rider sail',
        'id' => $prefix . 'sail',
        'type' => 'text',
      ),
      array(
        'name' => 'Weight',
        'desc' => 'Insert rider weight (kg)',
        'id' => $prefix . 'weight',
        'type' => 'text',
      ),
      array(
        'name' => 'Height',
        'desc' => 'Insert rider height (cm)',
        'id' => $prefix . 'height',
        'type' => 'text',
      ),
      array(
        'name' => 'Nationality',
        'desc' => 'Insert rider nationality',
        'id' => $prefix . 'nation',
        'type' => 'text',
      ),
      array(
        'name' => 'Residence',
        'desc' => 'Insert rider residence',
        'id' => $prefix . 'residence',
        'type' => 'text',
      ),
      array(
        'name' => 'Debut',
        'desc' => 'Insert rider debut year',
        'id' => $prefix . 'debut',
        'type' => 'text',
      ),
      array(
        'name' => 'Website',
        'desc' => 'Insert rider website URL',
        'id' => $prefix . 'website',
        'type' => 'text',
      ),
    )
  );

  new RW_Meta_Box( $meta_box );
}


function wdm_riders_rewrite_flush() {
  wdm_riders_create_post_type();

  flush_rewrite_rules();
}


add_action( 'init', 'wdm_riders_create_post_type' );
add_action( 'admin_init', 'wdm_riders_register_meta_box' );
register_activation_hook( __FILE__, 'wdm_riders_rewrite_flush' );

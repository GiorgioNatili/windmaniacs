<?php
add_action('init', 'prima_register_generated_sidebars', 15);
function prima_register_generated_sidebars() {
	$_sidebars = stripslashes_deep( get_option( PRIMA_SIDEBAR_SETTINGS ) );
	if ( !$_sidebars ) return;
	foreach ( (array)$_sidebars as $id => $info ) {
		if ( $id != '0' ) {
			register_sidebar(array(
				'name' => esc_html( $info['name'] ),
				'id' => $id,
				'description' => esc_html( $info['description'] ),
				'editable' => 1,
				'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
				'after_widget' => '</li>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>',
			));
		}
	}
}
function prima_get_sidebar( $default = '' ) {
	wp_reset_query();
	global $wp_query, $wp_registered_sidebars;
	$prima_registered_sidebars = $wp_registered_sidebars;
	$pre_sidebar = apply_filters('prima_sidebar', false);
	if ( false !== $pre_sidebar )
		return esc_attr($pre_sidebar);
	if ( is_singular() ) {
		$postid = $wp_query->post->ID;
		$posttype = $wp_query->post->post_type;
		$sidebar_inpost = prima_get_custom_field( "_prima_sidebar", $postid );
		if ( $sidebar_inpost && isset($prima_registered_sidebars[$sidebar_inpost]) )
			return $sidebar_inpost;
	}
	return $default;
}
function prima_dynamic_sidebar( $default = '' ) {
	$sidebar = prima_get_sidebar( $default );
	if ( $sidebar ) dynamic_sidebar( $sidebar );
}
function prima_dynamic_sidebar_name( $default = '' ) {
	global $wp_registered_sidebars;
	$sidebar = prima_get_sidebar( $default );
	if ( !$sidebar ) return false;
	if ( !isset($wp_registered_sidebars[$sidebar]) ) return false;	
	return $wp_registered_sidebars[$sidebar]['name'];
}
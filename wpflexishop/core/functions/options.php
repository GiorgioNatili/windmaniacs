<?php
/**
 * undocumented
 */
function prima_get_option($key, $setting = null) {
	$setting = $setting ? $setting : PRIMA_OPTIONS;
	$pre = apply_filters('prima_pre_get_option_'.$key, false, $setting);
	if ( false !== $pre )
		return $pre;
	$options = get_option($setting);
	if ( !is_array( $options ) || !array_key_exists($key, (array) $options) )
		return '';
	if ( is_array( $options[$key] ) ) 
		return $options[$key];
	$value = $options[$key];
	if ( is_ssl() ) $value = str_replace( 'http://', 'https://', $value );
	return stripslashes( wp_kses_decode_entities( $value ) );
}
function prima_option($key, $setting = null) {
	echo prima_get_option($key, $setting);
}

function prima_get_color($key) {
	$color = prima_get_option($key);
	if ( $color && $color != '#' )
		return $color;
	else
		return false;
}

/**
 * undocumented
 */
function prima_get_custom_field($field) {
	global $post;
	if ( null === $post ) return FALSE;
	$custom_field = get_post_meta($post->ID, $field, true);
	if ( $custom_field ) {
		return stripslashes( wp_kses_decode_entities( $custom_field ) );
	}
	else {
		return FALSE;
	}
}
function prima_custom_field($field) {
	echo prima_get_custom_field($field);
}

function prima_shortcode2id($shortcode, $type = 'page'){
	global $wpdb;
	$sql = "SELECT `ID` FROM `{$wpdb->posts}` WHERE `post_type` = '$type' AND `post_status` IN('publish','private') AND `post_content` LIKE '%$shortcode%' LIMIT 1";
	$page_id = $wpdb->get_var($sql);
	return apply_filters( 'prima_shortcode2id', $page_id );
}

/**
 * undocumented
 */
function prima_childtheme_file($file) {
	if ( ( PARENT_DIR != CHILD_DIR ) && file_exists(trailingslashit(CHILD_DIR).$file) ) 
		$url = trailingslashit(CHILD_URL).$file;
	else 
		$url = trailingslashit(PARENT_URL).$file;
	return $url;
}

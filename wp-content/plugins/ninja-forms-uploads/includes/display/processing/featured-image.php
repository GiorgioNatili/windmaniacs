<?php

add_action( 'init', 'ninja_forms_register_set_featured_image' );
function ninja_forms_register_set_featured_image(){
	add_action( 'ninja_forms_create_post', 'ninja_forms_prepare_set_featured_image' );
}

function ninja_forms_prepare_set_featured_image( $post_id ){
	global $ninja_forms_processing;
	$upload_id = '';
	foreach( $ninja_forms_processing->get_all_fields() as $field_id => $user_value ){
		$field_row = ninja_forms_get_field_by_id( $field_id );
		$field_type = $field_row['type'];
		$field_data = $field_row['data'];
		if( $field_type == '_upload' AND isset( $field_data['featured_image'] ) AND $field_data['featured_image'] == 1 ){
			$upload_id = $field_id;
		}
	}
	if( $upload_id != '' ){
		$user_value = $ninja_forms_processing->get_field_value( $upload_id );

		$file_name = $user_value[0]['file_path'].$user_value[0]['file_name'];
		ninja_forms_set_featured_image( $post_id, $file_name );
	}
}

function ninja_forms_set_featured_image( $post_id, $filename ) {
	$wp_filetype = wp_check_filetype( basename( $filename ), null );
	$attachment = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
		'post_content' => '',
		'post_status' => 'inherit'
	);
	$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
	// you must first include the image.php file
	// for the function wp_generate_attachment_metadata() to work
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
	if (wp_update_attachment_metadata( $attach_id,  $attach_data )) {
		// set as featured image
		return update_post_meta($post_id, '_thumbnail_id', $attach_id);
	}
}
<?php

add_action( 'init', 'ninja_forms_register_create_post_process' );
function ninja_forms_register_create_post_process(){
	add_action( 'ninja_forms_process', 'ninja_forms_create_post_process' );
	add_action ('ninja_forms_pre_process', 'ninja_forms_create_post_pre_process' );
}

function ninja_forms_create_post_pre_process(){
	global $ninja_forms_processing;
	if( isset( $_POST['_post_id'] ) ){
		$ninja_forms_processing->update_form_setting( 'post_id', $_POST['_post_id'] );
	}
}

function ninja_forms_create_post_process(){
	global $ninja_forms_processing, $current_user;
	get_currentuserinfo();

	$create_post = $ninja_forms_processing->get_form_setting( 'create_post' );
	$require_login = $ninja_forms_processing->get_form_setting( 'post_logged_in' );
	if($create_post == 1){
		if($require_login == 0 OR ($require_login == 1 AND is_user_logged_in())){
			

			//Get post information set by the admin
			$post_tax = $ninja_forms_processing->get_form_setting( 'post_tax' );
			$post_as = $ninja_forms_processing->get_form_setting( 'post_as' );
			$post_type = $ninja_forms_processing->get_form_setting( 'post_type' );
			$post_status = $ninja_forms_processing->get_form_setting( 'post_status' );

			//Get post information sent by the user.
			$post_title = $ninja_forms_processing->get_form_setting( 'post_title' );
			$post_content = $ninja_forms_processing->get_form_setting( 'post_content' );
			$post_tags = $ninja_forms_processing->get_form_setting( 'post_tags' );

			if( $ninja_forms_processing->get_form_setting( 'post_id' ) ){
				$post_id = $ninja_forms_processing->get_form_setting( 'post_id' );
			}else{
				$post_id = '';
			}

			$tmp_array = array();
			foreach( $post_tax as $tax ){
				$tax_terms = $ninja_forms_processing->get_form_setting( $tax.'_terms' );
				$tmp_array[$tax] = $tax_terms;
			}

			$args = array(
				'ID' 			 => $post_id,
				'post_author'    => $post_as,
				'post_content'   => $post_content,
				'post_status'    => $post_status,
				'post_title'     => $post_title,
				'post_type'      => $post_type,
				'tags_input'     => $post_tags,
				'tax_input'      => $tmp_array, 
			);

			// Insert the post into the database
			$post_id = wp_insert_post( $args );
			do_action( 'ninja_forms_create_post', $post_id );
		}
	}
}
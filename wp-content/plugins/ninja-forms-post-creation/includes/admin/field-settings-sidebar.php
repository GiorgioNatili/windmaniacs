<?php
add_action( 'admin_init', 'ninja_forms_register_sidebar_post_fields' );

function ninja_forms_register_sidebar_post_fields(){
	if(isset($_REQUEST['form_id'])){
		$form_id = $_REQUEST['form_id'];
	}else{
		$form_id = '';
	}
	if( function_exists( 'ninja_forms_get_current_tab' ) ){
		$current_tab = ninja_forms_get_current_tab();
	}
	if($form_id != '' AND $current_tab == 'field_settings'){
		$form_row = ninja_forms_get_form_by_id($form_id);
		if(is_array($form_row) AND !empty($form_row)){
			$form_data = $form_row['data'];
			if( isset( $form_data['create_post'] ) ){
				$create_post = $form_data['create_post'];
			}else{
				$create_post = '';
			}
			
		}else{
			$create_post = '';
		}
	}else{
		$create_post = '';
	}
	$args = array(
		'name' => 'Post Creation Fields',
		'page' => 'ninja-forms',
		'tab' => 'field_settings',
		'display_function' => 'ninja_forms_sidebar_display_fields'
	);
	if($create_post == 1){
		if( function_exists( 'ninja_forms_register_sidebar' ) ){
			ninja_forms_register_sidebar('post_fields', $args);
		}
	}
}
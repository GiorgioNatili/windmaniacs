<?php
add_action( 'admin_init', 'ninja_forms_mp_admin_js' );
function ninja_forms_mp_admin_js(){
	if( isset( $_REQUEST['form_id'] ) ){
		$form_id = $_REQUEST['form_id'];
	}else{
		$form_id = '';
	}
	if( $form_id != '' AND $form_id != 'new' AND $form_id != 'all' ){
		$form_row = ninja_forms_get_form_by_id( $form_id );
		$form_data = $form_row['data'];
		if( isset( $form_data['multi_part'] ) ){
			$multi_part = $form_data['multi_part'];
		}else{
			$multi_part = 0;
		}
		
		if( $multi_part == 1 ){
			if( isset( $_REQUEST['page'] ) AND $_REQUEST['page'] == 'ninja-forms' ){
				wp_enqueue_script( 'ninja-forms-mp-admin',
					NINJA_FORMS_MP_URL .'/js/min/ninja-forms-mp-admin.min.js',
					array( 'jquery', 'ninja-forms-admin', 'jquery-ui-droppable' ) );
			}
		}
	}
}

add_action( 'admin_init', 'ninja_forms_mp_admin_css' );
function ninja_forms_mp_admin_css(){
	if( isset( $_REQUEST['form_id'] ) ){
		$form_id = $_REQUEST['form_id'];
	}else{
		$form_id = '';
	}
	if( $form_id != '' AND $form_id != 'new' AND $form_id != 'all' ){
		$form_row = ninja_forms_get_form_by_id( $form_id );
		$form_data = $form_row['data'];
		if( isset( $form_data['multi_part'] ) ){
			$multi_part = $form_data['multi_part'];
		}else{
			$multi_part = 0;
		}
		
		if( $multi_part == 1 ){
			if( isset( $_REQUEST['page'] ) AND $_REQUEST['page'] == 'ninja-forms' ){
				wp_enqueue_style( 'ninja-forms-mp-admin', NINJA_FORMS_MP_URL .'/css/ninja-forms-mp-admin.css', array( 'ninja-forms-admin' ) );
			}
		}
	}
}
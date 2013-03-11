<?php

add_action( 'init', 'ninja_forms_register_mp_remove_save' );
function ninja_forms_register_mp_remove_save(){
	add_action( 'ninja_forms_pre_process', 'ninja_forms_mp_remove_save', 9 );
}

function ninja_forms_mp_remove_save(){
	global $ninja_forms_processing;

	if( isset( $ninja_forms_processing ) AND $ninja_forms_processing->get_form_setting( 'multi_part' ) ){
		$nav = '';

		$all_extras = $ninja_forms_processing->get_all_extras();
		if( is_array( $all_extras ) AND !empty( $all_extras ) ){
			foreach( $all_extras as $key => $val ){
				if( strpos( $key, '_mp_page_' ) !== false ){
					$nav = str_replace( '_mp_page_', '', $key );
					if( !$ninja_forms_processing->get_all_errors() ){
						$ninja_forms_processing->update_extra_value( '_current_page', $nav );
					}
				}
			}
		}

		if( $ninja_forms_processing->get_extra_value( '_prev' ) ){
			$nav = 'prev';
			remove_action( 'ninja_forms_pre_process', 'ninja_forms_req_fields_process' );
		}

		if( $ninja_forms_processing->get_extra_value( '_next' ) ){
			$nav = 'next';
		}

		if( $nav != '' ){
			$ninja_forms_processing->set_action( 'mp_save' );
			remove_action( 'ninja_forms_post_process', 'ninja_forms_save_sub' );
		}
	}
	
}

add_action( 'init', 'ninja_forms_mp_register_save_page' );
function ninja_forms_mp_register_save_page(){
	global $ninja_forms_processing;
	add_action( 'ninja_forms_pre_process', 'ninja_forms_mp_save_page', 1001 );
	add_action( 'ninja_forms_edit_sub_pre_process', 'ninja_forms_mp_save_page', 1001 );
}

function ninja_forms_mp_save_page(){
	global $ninja_forms_processing, $current_user, $ninja_forms_fields;;

	if( $ninja_forms_processing->get_form_setting( 'multi_part' ) ){

		$form_id = $ninja_forms_processing->get_form_ID();
		$all_fields = ninja_forms_get_fields_by_form_id( $form_id );

		if( is_array( $all_fields ) AND !empty( $all_fields ) ){
			$pages = array();
			$this_page = array();
			$x = 0;
			foreach( $all_fields as $field ){
				if( $field['type'] == '_page_divider' ){
					$x++;
				}
				$pages[$x][] = $field['id'];
			}
		}

		$page_count = count($pages);
		$ninja_forms_processing->update_extra_value( '_page_count', $page_count );

		if( !$ninja_forms_processing->get_all_errors() ){

			if( $ninja_forms_processing->get_extra_value( '_current_page' ) ){
				$current_page = $ninja_forms_processing->get_extra_value( '_current_page' );
			}else{
				$current_page = 1;
			}


			if( $ninja_forms_processing->get_extra_value( '_prev' ) ){
				$nav = 'prev';
				if( $current_page != 1 ){
					$current_page--;
				}
			}

			if( $ninja_forms_processing->get_extra_value( '_next' ) ){
				$nav = 'next';
				if( $current_page != $page_count ){
					$current_page++;
				}
			}
		
			$ninja_forms_processing->update_extra_value( '_current_page', $current_page );
		}
		
		
		$sub_id = $ninja_forms_processing->get_form_setting('sub_id');
		$user_id = $ninja_forms_processing->get_user_ID();
		$form_id = $ninja_forms_processing->get_form_ID();

		$field_data = $ninja_forms_processing->get_all_fields();

		if( $sub_id != '' ){
			$sub_row = ninja_forms_get_sub_by_id( $sub_id );
			$sub_data = $sub_row['data'];
			if( $ninja_forms_processing->get_action() == 'edit_sub' ){
				$status = $sub_row['status'];
			}else{
				$status = 0;
			}
		}else{
			$sub_data = array();
			$status = 0;
		}

		if(is_array($field_data) AND !empty($field_data)){
			foreach($field_data as $field_id => $user_value){
				array_push( $sub_data, array( 'field_id' => $field_id, 'user_value' => $user_value ) );
			}
		}

		$sub_data = array_values( $sub_data );

		foreach( $sub_data as $row ){
			$ninja_forms_processing->update_field_value( $row['field_id'], $row['user_value'] );
		}

		$field_data = $ninja_forms_processing->get_all_fields();
		$sub_data = array();

		if(is_array($field_data) AND !empty($field_data)){
			foreach($field_data as $field_id => $user_value){
				array_push( $sub_data, array( 'field_id' => $field_id, 'user_value' => $user_value ) );
			}
		}

		if( $ninja_forms_processing->get_action() == 'mp_save' ){

			$args = array(
				'form_id' => $form_id,
				'user_id' => $user_id,
				'status' => $status,
				'action' => 'mp_save',
				'data' => serialize( $sub_data ),
			);

			if($sub_id != ''){
				$args['sub_id'] = $sub_id;
				ninja_forms_update_sub($args);
			}else{
				$sub_id = ninja_forms_insert_sub($args);
			}

			$ninja_forms_processing->update_form_setting( 'sub_id', $sub_id );

			$ninja_forms_processing->add_error( '_mp_save', '' );
		}
	}
}
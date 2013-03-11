<?php

add_action( 'init', 'ninja_forms_save_progress_register_check_save' );
function ninja_forms_save_progress_register_check_save(){
	add_filter( 'ninja_forms_field', 'ninja_forms_save_progress_check_save', 11, 2 );
}

function ninja_forms_save_progress_check_save( $data, $field_id ){
	global $current_user, $ninja_forms_processing;

	get_currentuserinfo();
	$form_row = ninja_forms_get_form_by_field_id( $field_id );
	$form_id = $form_row['id'];
	
	if( isset( $current_user ) ){
		$user_id = $current_user->ID;	
	}else{
		$user_id = '';
	}
	if( !isset( $ninja_forms_processing ) AND $user_id != '' ){
		$sub_row = ninja_forms_get_saved_form( $user_id, $form_id );

		if( is_array( $sub_row ) AND !empty( $sub_row ) ){
			$sub_data = $sub_row['data'];
			if( is_array( $sub_data ) AND !empty( $sub_data ) ){
				foreach( $sub_data as $row ){
					if( $row['field_id'] == $field_id ){
						$data['default_value'] = $row['user_value'];
						break;
					}
				}
			}
		}
	}else if( isset( $ninja_forms_processing ) AND $ninja_forms_processing->get_error( '_save_progress' ) ){
		$clear_saved = $ninja_forms_processing->get_form_setting( 'clear_saved' );
		if( $clear_saved == 1 ){
			$data['default_value'] = '';
		}
	}
	return $data;
}
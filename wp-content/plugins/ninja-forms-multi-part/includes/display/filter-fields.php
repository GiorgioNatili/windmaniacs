<?php

add_action( 'ninja_forms_display_fields_array', 'ninja_forms_mp_filter_fields', 10, 2 );
function ninja_forms_mp_filter_fields( $field_results, $form_id ){
	global $ninja_forms_processing;

	$form_row = ninja_forms_get_form_by_id( $form_id );
	$form_data = $form_row['data'];

	if( isset( $_REQUEST['_current_page'] ) ){
		$current_page = $_REQUEST['_current_page'];
	}else{
		$current_page = 1;
	}

	if( isset( $ninja_forms_processing ) AND $ninja_forms_processing->get_extra_value( '_current_page' ) ){
		$current_page = $ninja_forms_processing->get_extra_value( '_current_page' );
	}

	if( isset( $form_data['multi_part'] ) ){
		$multi_part = $form_data['multi_part'];
	}else{
		$multi_part = 0;
	}
	
	if( $multi_part == 1 ){
		if( is_array( $field_results ) AND !empty( $field_results ) ){
			$pages = array();
			$this_page = array();
			$x = 0;
			foreach( $field_results as $field ){
				if( $field['type'] == '_page_divider' ){
					$x++;
				}
				$pages[$x][] = $field;
			}
		}
		$page_count = count($pages);
		$field_results = $pages[$current_page];
	}

	return $field_results;
}
<?php

add_action( 'ninja_forms_display_after_fields', 'ninja_forms_mp_nav' );
function ninja_Forms_mp_nav( $form_id ){
	global $ninja_forms_processing;
	$form_row = ninja_forms_get_form_by_id( $form_id );
	$form_data = $form_row['data'];
	$all_fields = ninja_forms_get_fields_by_form_id( $form_id );

	if( isset( $form_data['multi_part'] ) ){
		$multi_part = $form_data['multi_part'];
	}else{
		$multi_part = 0;
	}

	if( isset( $_REQUEST['_current_page'] ) ){
		$current_page = $_REQUEST['_current_page'];
	}else{
		$current_page = 1;
	}

	if( isset( $ninja_forms_processing ) AND $ninja_forms_processing->get_extra_value( '_current_page' ) ){
		$current_page = $ninja_forms_processing->get_extra_value( '_current_page' );
	}	

	if( isset( $ninja_forms_processing ) AND $ninja_forms_processing->get_form_setting( 'sub_id' ) ){
		$sub_id = $ninja_forms_processing->get_form_setting( 'sub_id' );
	}else{
		$sub_id = '';
	}

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

	if( $multi_part == 1 ){
		?>
		<input type="hidden" name="_sub_id" value="<?php echo $sub_id;?>">
		<input type="hidden" name="_current_page" value="<?php echo $current_page;?>">
		<?php
		if( $current_page != 1 ){
			?>
			<input type="submit" name="_prev" class="ninja-forms-mp-nav" id="ninja_forms_mp_prev" value="<?php _e( 'Previous', 'ninja-forms' );?>">	
			<?php
		}
		if( $current_page < $page_count ){
			?>
			<input type="submit" name="_next" class="ninja-forms-mp-nav" id="ninja_forms_mp_next" value="<?php _e( 'Next', 'ninja-forms' );?>">
			<?php
		}
	}
}
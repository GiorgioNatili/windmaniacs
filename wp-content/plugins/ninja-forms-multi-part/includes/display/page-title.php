<?php
/**
 * Outputs the HTML for the Multi-Part Form Page Title.
 *
**/
add_action( 'init', 'ninja_forms_register_mp_display_page_title' );
function ninja_forms_register_mp_display_page_title(){
	add_action( 'ninja_forms_display_before_fields', 'ninja_forms_mp_display_page_title', 9 );	
}

function ninja_forms_mp_display_page_title( $form_id ){
	global $ninja_forms_processing;
	$form_row = ninja_forms_get_form_by_id( $form_id );
	$form_data = $form_row['data'];
	if( (isset( $ninja_forms_processing ) AND $ninja_forms_processing->get_form_setting( 'processing_complete' ) != 1 ) OR !isset( $ninja_forms_processing ) ){
		if( isset( $form_data['mp_display_titles'] ) AND $form_data['mp_display_titles'] == 1 ){
			if( isset( $ninja_forms_processing ) ){
				$current_page = $ninja_forms_processing->get_extra_value( '_current_page' );
			}else{
				$current_page = 1;
			}
			$all_fields = ninja_forms_get_fields_by_form_id( $form_id );

			if( is_array( $all_fields ) AND !empty( $all_fields ) ){
				$pages = array();
				$this_page = array();
				$x = 0;
				foreach( $all_fields as $field ){
					if( $field['type'] == '_page_divider' ){
						$x++;
						$pages[$x]['page_title'] = $field['data']['page_name'];
					}
				}
			}

			if( isset( $pages[$current_page]['page_title'] ) ){
				$page_title = $pages[$current_page]['page_title'];
			}else{
				$page_title = '';
			}

			$page_count = count($pages);
			?>
			<h4><?php echo $page_title;?></h4>
			<?php
		}
	}
}
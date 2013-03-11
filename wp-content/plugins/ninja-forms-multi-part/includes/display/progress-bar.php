<?php
/**
 * Outputs the HTML for the Multi-Part Form progress bar.
 *
**/
add_action( 'init', 'ninja_forms_register_mp_display_progress_bar', 1001 );
function ninja_forms_register_mp_display_progress_bar(){
	global $ninja_forms_processing;
	if( !is_admin() ){
		if( isset( $ninja_forms_processing ) AND $ninja_forms_processing->get_form_setting( 'processing_complete' ) == 1 ){
			add_action( 'ninja_forms_display_before_form_wrap', 'ninja_forms_mp_display_progress_bar', 9 );
		}else{
			add_action( 'ninja_forms_display_before_fields', 'ninja_forms_mp_display_progress_bar', 8 );			
		}		
	}
}

function ninja_forms_mp_display_progress_bar( $form_id ){
	global $ninja_forms_processing;
	$form_row = ninja_forms_get_form_by_id( $form_id );
	$form_data = $form_row['data'];
	if( isset( $form_data['mp_progress_bar'] ) AND $form_data['mp_progress_bar'] == 1 ){
		if( isset( $ninja_forms_processing ) ){
			if( $ninja_forms_processing->get_form_setting( 'processing_complete' ) == 1 ){
				$percent = 100;
			}else{
				$current_page = $ninja_forms_processing->get_extra_value( '_current_page' );
				if( $current_page == 1 ){
					$percent = 0;
				}else{
					$current_page--;
					$page_count = $ninja_forms_processing->get_extra_value( '_page_count' );
					$percent = $current_page / $page_count;
					$percent = $percent * 100;	
					$percent = ceil( $percent );		
				}
			}
		}else{
			$percent = 0;
		}
		?>
		<div class="meter orange nostripes">
			<span style="width: <?php echo $percent;?>%"></span>
		</div>
		<?php
	}
}
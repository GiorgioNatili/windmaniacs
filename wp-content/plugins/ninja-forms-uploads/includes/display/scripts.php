<?php
add_action( 'ninja_forms_display_js', 'ninja_forms_upload_display_js', 10, 2 );
function ninja_forms_upload_display_js( $form_id ){
	if( !is_admin() ){
		$fields = ninja_forms_get_fields_by_form_id( $form_id );
		$output = false;
		$multi = false;
		foreach( $fields as $field ){
			if( $field['type'] == '_upload' ){
				if( !$output ){
					$output = true;
				}			
				if( !$multi AND $field['data']['upload_multi'] == 1 ){
					$multi = true;
				}
			}
		}
		if( $output ){
			wp_enqueue_script( 'ninja-forms-uploads-display',
				NINJA_FORMS_UPLOADS_URL .'/js/min/ninja-forms-uploads-display.min.js',
				array( 'jquery', 'ninja-forms-display' ) );	
			if( $multi ){
				wp_enqueue_script( 'jquery-multi-file',
					NINJA_FORMS_UPLOADS_URL .'/js/min/jquery.MultiFile.pack.js',
					array( 'jquery' ) );
			}
		}
	}
}
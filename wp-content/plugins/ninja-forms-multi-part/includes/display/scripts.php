<?php

add_action( 'ninja_forms_display_js', 'ninja_forms_mp_display_js', 10, 2 );
function ninja_forms_mp_display_js( $form_id ){
	$form_row = ninja_forms_get_form_by_id( $form_id );
	$form_data = $form_row['data'];
	if( isset( $form_data['multi_part'] ) AND $form_data['multi_part'] == 1 ){
		wp_enqueue_script( 'ninja-forms-mp-display',
			NINJA_FORMS_MP_URL .'/js/min/ninja-forms-mp-display.min.js',
			array( 'jquery', 'ninja-forms-display' ) );
	}
	
}

add_action( 'ninja_forms_display_css', 'ninja_forms_mp_display_css', 10, 2 );
function ninja_forms_mp_display_css( $form_id ){
	$form_row = ninja_forms_get_form_by_id( $form_id );
	$form_data = $form_row['data'];
	if( isset( $form_data['multi_part'] ) AND $form_data['multi_part'] == 1 ){
		wp_enqueue_style('ninja-forms-mp-display', NINJA_FORMS_MP_URL .'/css/ninja-forms-mp-display.css');
	}
}
<?php
add_action('init', 'ninja_forms_register_mp_settings_metabox', 10);

function ninja_forms_register_mp_settings_metabox(){
	$args = array(
		'page' => 'ninja-forms',
		'tab' => 'form_settings',
		'slug' => 'multi_part',
		'title' => __('Multi-Part settings', 'ninja-forms'),
		'display_function' => '',
		'state' => 'closed',
		'settings' => array(
			array(
				'name' => 'multi_part',
				'type' => 'checkbox',
				'desc' => '',
				'label' => __( 'Enable Multi-Part Form?', 'ninja-forms' ),
				'display_function' => '',
				'help' => __( 'If this box is checked Ninja Forms will allow multi-part form pages to be created.', 'ninja-forms' ),
				'default' => 0,
			),
			array(
				'name' => 'mp_progress_bar',
				'type' => 'checkbox',
				'desc' => '',
				'label' => __( 'Display Progress Bar?', 'ninja-forms' ),
				'display_function' => '',
				'help' => '',
				'default' => 0,
			),
			array(
				'name' => 'mp_breadcrumb',
				'type' => 'checkbox',
				'desc' => '',
				'label' => __( 'Display Breadcrumb Navigation?', 'ninja-forms' ),
				'display_function' => '',
				'help' => '',
				'default' => 0,
			),
			array(
				'name' => 'mp_display_titles',
				'type' => 'checkbox',
				'desc' => '',
				'label' => __( 'Display Page Titles?', 'ninja-forms' ),
				'display_function' => '',
				'help' => '',
				'default' => 0,
			),
		),
		'save_function' => 'ninja_forms_mp_save_form',
	);

	if( function_exists( 'ninja_forms_register_tab_metabox' ) ){
		ninja_forms_register_tab_metabox($args);
	}

}

function ninja_forms_mp_save_form( $form_id, $data ){
	$form_row = ninja_forms_get_form_by_id( $form_id );
	$form_data = $form_row['data'];
	if( isset( $form_data['multi_part'] ) ){
		$multi_part = $form_data['multi_part'];
	}else{
		$multi_part = 0;
	}
	if( $data['multi_part'] == 0 AND $multi_part == 1){
		$all_fields = ninja_forms_get_fields_by_form_id( $form_id );
		if( is_array( $all_fields ) AND !empty( $all_fields ) ){
			foreach( $all_fields as $field ){
				if( $field['type'] == '_page_divider' ){
					ninja_forms_delete_field( $field['id'] );
				}
			}
		}
	}else if( $data['multi_part'] == 1 AND $multi_part = 0 ){
		$args = array(
			'type' => '_page_divider',
			'order' => -1,	
		);
		ninja_forms_insert_field( $form_id, $args );
	}
}
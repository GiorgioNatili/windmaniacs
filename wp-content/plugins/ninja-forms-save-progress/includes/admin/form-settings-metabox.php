<?php
add_action('init', 'ninja_forms_register_form_settings_save_progress_metabox');
function ninja_forms_register_form_settings_save_progress_metabox(){
	$args = array(
		'page' => 'ninja-forms',
		'tab' => 'form_settings',
		'slug' => 'save_progress',
		'title' => __('Save Progress Settings', 'ninja-forms'),
		'display_function' => '',
		'state' => 'closed',
		'settings' => array(
			array(
				'name' => 'save_progress',
				'type' => 'checkbox',
				'desc' => '',
				'label' => __('Allow users to save progress?', 'ninja-forms'),
				'display_function' => '',
				'help' => __('', 'ninja-forms'),
			),
			array(
				'name' => 'clear_saved',
				'type' => 'checkbox',
				'desc' => '',
				'label' => __('Clear Saved Form?', 'ninja-forms'),
				'display_function' => '',
				'help' => __('', 'ninja-forms'),
			),				
			array(
				'name' => 'hide_saved',
				'type' => 'checkbox',
				'desc' => '',
				'label' => __('Hide Saved Form?', 'ninja-forms'),
				'display_function' => '',
				'help' => __('', 'ninja-forms'),
			),			
			array(
				'name' => 'clear_incomplete_saves',
				'type' => 'text',
				'label' => __('Number of days to keep incomplete form entries', 'ninja-forms'),
			),			
			array(
				'name' => 'save_msg',
				'type' => 'rte',
				'label' => __('Saved Form Message', 'ninja-forms'),
				'desc' => __('If you want to include field data entered by the user, for instance a name, you can put that field\'s label in brackets. i.e. [Firstname] or [Last Name]. This will tell Ninja Forms to replace the bracketed text with whatever input the user placed in that field. The label name must be entered exactly as you have it on the form field', 'ninja-forms'),
			),		
		),
	);
	if( function_exists( 'ninja_forms_register_tab_metabox' ) ){
		ninja_forms_register_tab_metabox($args);
	}
}
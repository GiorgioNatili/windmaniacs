<?php
//add_action('init', 'ninja_forms_register_tab_ajax_settings');

function ninja_forms_register_tab_ajax_settings(){
	$args = array(
		'name' => 'Ajax Submission', 
		'page' => 'ninja-forms-settings',
		'display_function' => '', 
		'save_function' => 'ninja_forms_save_ajax_settings',
	); 
	ninja_forms_register_tab('ajax_settings', $args);
	
}

add_action('init', 'ninja_forms_register_ajax_settings_metabox');

function ninja_forms_register_ajax_settings_metabox(){
	$args = array(
		'page' => 'ninja-forms-settings',
		'tab' => 'ajax_settings',
		'slug' => 'msg_format',
		'title' => __('Ajax Message Settings', 'ninja-forms'),
		'settings' => array(
			array(
				'name' => 'msg_format',
				'type' => 'radio',
				'label' => __('Ajax Message Format', 'ninja-forms'),
				'desc' => __('(Advanced setting: Ninja Forms will require you to create a javascript function named ninja_forms_blah to handle the server response from input.)', 'ninja-forms'),
				'options' => array(
					array('name' => 'Inline Messages', 'value' => 'inline'),
					array('name' => 'jQuery Modal Messages', 'value' => 'modal'),
					array('name' => 'Custom Message Display', 'value' => 'custom'),
				),
				'help_text' => __('Ninja Forms Test', 'ninja-forms'),
			),
		),
	);
	ninja_forms_register_tab_metabox($args);
}

function ninja_forms_save_ajax_settings($data){
	$plugin_settings = get_option("ninja_forms_settings");
	foreach($data as $key => $val){
		$plugin_settings[$key] = $val;
	}
	update_option("ninja_forms_settings", $plugin_settings);
}
<?php

/**
 * This function will be ran during the processing (process) of a form.
 * The goals here are to:
 *		Move the temporary file to its permanent location.
 *
 * @param int $field_id - ID number of the field that is currently being displayed.
 * @param array/string $user_value - the value of the field within the user-submitted form.
 */

function ninja_forms_field_upload_process($field_id, $user_value){
	global $ninja_forms_processing;

	$plugin_settings = get_option('ninja_forms_settings');
	$field_row = ninja_forms_get_field_by_id($field_id);
	$field_data = $field_row['data'];
	
	if(isset($plugin_settings['base_upload_dir'])){
		$base_upload_dir = $plugin_settings['base_upload_dir'];
	}else{
		$base_upload_dir = '';
	}

	if(isset($plugin_settings['custom_upload_dir'])){
		$custom_upload_dir = $plugin_settings['custom_upload_dir'];
	}else{
		$custom_upload_dir = '';
	}

	$tmp_upload_file = $ninja_forms_processing->get_field_value($field_id);
	
	if(is_array($tmp_upload_file) AND isset($tmp_upload_file[0]) AND !empty($tmp_upload_file[0])){
		foreach($tmp_upload_file as $file){
			$file_path = $file['file_path'];
			if($file_path != ''){
				$file_name = $file['file_name'];
				$user_file_name = $file['user_file_name'];

				$form_title = strtolower( stripslashes( trim( $ninja_forms_processing->get_form_setting('form_title') ) ) );
				$form_title = preg_replace("/[\/\&%#\$]/", "", $form_title);
				$form_title = preg_replace("/[\"\']/", "", $form_title);
				$form_title = preg_replace('/\s+/', '', $form_title);

				if(is_user_logged_in()){
					$current_user = wp_get_current_user();
					$user_name = $current_user->user_nicename;
				}else{
					$user_name = '';
				}

				if($custom_upload_dir != ''){
					$custom_upload_dir = stripslashes(trim($custom_upload_dir));

					$custom_upload_dir = str_replace("%filename%", $user_file_name, $custom_upload_dir);
					$custom_upload_dir = str_replace("%formtitle%", $form_title, $custom_upload_dir);
					$custom_upload_dir = str_replace("%date%", date('Y-m-d'), $custom_upload_dir);
					$custom_upload_dir = str_replace("%month%", date('m'), $custom_upload_dir);
					$custom_upload_dir = str_replace("%day%", date('d'), $custom_upload_dir);
					$custom_upload_dir = str_replace("%year%", date('Y'), $custom_upload_dir);
					$custom_upload_dir = str_replace("%username%", $user_name, $custom_upload_dir);

					if( strpos( $custom_upload_dir, '/' ) !== false ){
						$sep = '/';
					}else if( strpos( $custom_upload_dir, '\\' ) !== false ){
						$sep = '\\';
					}
					
					$tmp_upload_dir = explode( $sep, $custom_upload_dir );
					$x = 0;
					$tmp_dir = '';
					foreach( $tmp_upload_dir as $dir ){
						if( $x == 0 ){
							$tmp_dir = $base_upload_dir;
						}
						$tmp_dir = $tmp_dir.$sep.$dir;
						if( !is_dir($tmp_dir) ){
							mkdir( $tmp_dir );
						}
						$tmp_dir .= $sep;
						$x++;
					}
				}
				
				if( isset( $field_data['featured_image'] ) AND $field_data['featured_image'] == 1 ){
					$upload_dir = wp_upload_dir();
					$upload_dir = trailingslashit( $upload_dir['basedir'] );
				}else{
					$upload_dir = $base_upload_dir.$custom_upload_dir;
				}

				$file_dir = $upload_dir.$file_name;
				
				if(!$ninja_forms_processing->get_all_errors()){
					if( file_exists ( $file_path ) AND !is_dir( $file_path ) AND copy( $file_path, $file_dir ) ){
						$current_uploads = $ninja_forms_processing->get_field_value($field_id);
						if(is_array($current_uploads) AND !empty($current_uploads)){
							for($x = 0; $x < count($current_uploads); $x++){
								if($current_uploads[$x]['file_path'] == $file_path){
									$current_uploads[$x]['file_path'] = $upload_dir;
								}
							}
						}

						$ninja_forms_processing->update_field_value($field_id, $current_uploads);
						if(file_exists($file_path)){
							$dir = str_replace('ninja_forms_field_'.$field_id, '', $file_path);
							unlink($file_path);
							if(is_dir($dir)){
								rmdir($dir);
							}
						}
						
					}else{
						$ninja_forms_processing->add_error('upload_'.$field_id, __('File Upload Error '.$file_dir, 'ninja-forms'), $field_id);
					}
				}
			}
		}
		do_action('ninja_forms_upload_process', $field_id);
	}
}

/**
 * This section updates the upload database whenever a file is uploaded.
 *
 */

add_action('init', 'ninja_forms_register_upload_db_update');
function ninja_forms_register_upload_db_update(){
	add_action('ninja_forms_upload_process', 'ninja_forms_upload_db_update');
}

function ninja_forms_upload_db_update($field_id){
	global $wpdb, $ninja_forms_processing;

	$form_id = $ninja_forms_processing->get_form_ID();
	$user_id = $ninja_forms_processing->get_user_ID();
	$files = $ninja_forms_processing->get_field_value($field_id);
	if(is_array($files) AND !empty($files)){
		foreach($files as $f){
			$data = serialize($f);
			$wpdb->insert( NINJA_FORMS_UPLOADS_TABLE_NAME, array('user_id' => $user_id, 'form_id' => $form_id, 'field_id' => $field_id, 'data' => $data) );
		}
	}
}
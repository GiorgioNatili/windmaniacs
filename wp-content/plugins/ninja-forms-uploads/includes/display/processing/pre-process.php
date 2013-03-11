<?php

/**
 * This function will be ran during the pre processing (pre_process) of a form.
 * The goals here are to:
 *		Move the file to a temporary location.
 *		Save the temporary location along with the file name
 *
 * @param int $field_id - ID number of the field that is currently being displayed.
 * @param array/string $user_value - the value of the field within the user-submitted form.
 */

function ninja_forms_field_upload_pre_process($field_id, $user_value){
	global $ninja_forms_processing;
	
	$plugin_settings = get_option('ninja_forms_settings');
	$field_row = ninja_forms_get_field_by_id($field_id);
	$field_data = $field_row['data'];

	if(isset($field_data['upload_types'])){
		$upload_types = $field_data['upload_types'];
	}else{
		$upload_types = '';
	}
	
	if(isset($field_data['upload_rename'])){
		$upload_rename = $field_data['upload_rename'];
	}else{
		$upload_rename = '';
	}

	if(isset($field_data['email_attachment'])){
		$email_attachment = $field_data['email_attachment'];
	}else{
		$email_attachment = '';
	}
	
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

	if( isset( $_FILES['ninja_forms_field_'.$field_id] ) ){

		$files = array();
		$fdata = $_FILES['ninja_forms_field_'.$field_id];
		
		if(is_array($fdata['name'])){
			for($i=0;$i<count($fdata['name']);++$i){
		    	$files[]=array(
				    'name'    =>$fdata['name'][$i],
				    'type'  => $fdata['type'][$i],
				    'tmp_name'=>$fdata['tmp_name'][$i],
				    'error' => $fdata['error'][$i], 
				    'size'  => $fdata['size'][$i]  
			    );
		    }
		    $multi = true;
		}else{
			$files[0]=$fdata;
			$multi = false;
		}

		$file_error = false;
		foreach($files as $f){
			if(isset($f['error']) AND !empty($f['error'])){
				$file_error = true;
			}
		}
		if(!$file_error){
			foreach($files as $file){
				ninja_forms_field_upload_move_uploads($field_id, $file, $multi);
			}
		}else if(isset($_POST['_upload_'.$field_id]) AND is_array($_POST['_upload_'.$field_id])){
			$update_array = $_POST['_upload_'.$field_id];
			$ninja_forms_processing->update_field_value($field_id, $update_array);
		}

		//print_r($ninja_forms_processing->get_field_value($field_id));
	}
	do_action('ninja_forms_upload_pre_process', $field_id);
}

function ninja_forms_field_upload_move_uploads($field_id, $file_data, $multi = false){
	global $ninja_forms_processing;

	$plugin_settings = get_option('ninja_forms_settings');
	$field_row = ninja_forms_get_field_by_id($field_id);
	$field_data = $field_row['data'];

	if(isset($field_data['upload_types'])){
		$upload_types = $field_data['upload_types'];
	}else{
		$upload_types = '';
	}
	
	if(isset($field_data['upload_rename'])){
		$upload_rename = $field_data['upload_rename'];
	}else{
		$upload_rename = '';
	}

	if(isset($field_data['email_attachment'])){
		$email_attachment = $field_data['email_attachment'];
	}else{
		$email_attachment = '';
	}
	
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

	$random_string = ninja_forms_random_string(5);

	$tmp_upload_file = NINJA_FORMS_UPLOADS_DIR.'/tmp/'.$random_string.'/';
	if(is_dir($tmp_upload_file)){
		rmdir($tmp_upload_file);
	}
	mkdir($tmp_upload_file);

	$tmp_upload_file .= 'ninja_forms_field_'.$field_id;

	$file_name = '';

	move_uploaded_file($file_data['tmp_name'], $tmp_upload_file);
	$user_file_name = $file_data['name'];

	$orig_user_file_name = $user_file_name;

	if($user_file_name != ''){

		//Trim whitespace and replace special characters from our file name.
		$user_file_name = stripslashes(trim($user_file_name));
		$user_file_name = strtolower($user_file_name);
		$user_file_name = preg_replace("/[\/\&%#\$]/", "", $user_file_name);
		$user_file_name = preg_replace("/[\"\']/", "", $user_file_name);
		$user_file_name = preg_replace("/\s+/", "", $user_file_name);

		if(isset($upload_types) AND !empty($upload_types)){
			$user_file_array = explode(".", $user_file_name);
			$ext = array_pop($user_file_array);
			if(strpos($upload_types, $ext) === false){
				$ninja_forms_processing->add_error('upload_'.$field_id, __('File type is not allowed: '.$user_file_name, 'ninja-forms'), $field_id);
			}
		}

		$form_title = $ninja_forms_processing->get_form_setting('form_title');
		if(is_user_logged_in()){
			$current_user = wp_get_current_user();
			$user_name = $current_user->user_nicename;
		}else{
			$user_name = '';
		}

		//If we have a file naming convention, use it to change our file name.
		if(!empty($upload_rename)){
			if(is_array($user_file_array) AND !empty($user_file_array)){
				$user_file_name = implode($user_file_array);

			}

			$user_file_name = stripslashes(trim($user_file_name));

			$file_name = str_replace("%filename%", $user_file_name, $upload_rename);
			$file_name = str_replace("%formtitle%", $form_title, $file_name);
			$file_name = str_replace("%date%", date('Y-m-d'), $file_name);
			$file_name = str_replace("%month%", date('m'), $file_name);
			$file_name = str_replace("%day%", date('d'), $file_name);
			$file_name = str_replace("%year%", date('Y'), $file_name);
			$file_name = str_replace("%username%", $user_name, $file_name);
			$file_name .= '.'.$ext;
		}else{
			$file_name = $user_file_name;
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
		}

		$upload_path = $base_upload_dir.$custom_upload_dir;

		$file_url = str_replace($_SERVER['DOCUMENT_ROOT'], site_url(), $upload_path);
		$file_url .= $file_name;
	}
	
	if($multi){
		$update_array = $ninja_forms_processing->get_field_value($field_id);
		if(is_array($update_array)){
			if(empty($update_array[0])){
				$update_array = array();
			}
		}else{
			$update_array = array();
		}
	}else{
		$update_array = array();
	}

	$tmp_array['user_file_name'] = $orig_user_file_name;
	$tmp_array['file_name'] = $file_name;
	$tmp_array['file_path'] = $tmp_upload_file;
	$tmp_array['file_url'] = $file_url;

	array_push($update_array, $tmp_array);

	$update_array = apply_filters('ninja_forms_upload_pre_process_array', $update_array);

	$ninja_forms_processing->update_field_value($field_id, $update_array);

	if($email_attachment == 1 AND $file_name != ''){
		$attach_files = $ninja_forms_processing->get_form_setting('admin_attachments');
		array_push($attach_files, $upload_path.$file_name);
		$ninja_forms_processing->update_form_setting('admin_attachments', $attach_files);
	}
}
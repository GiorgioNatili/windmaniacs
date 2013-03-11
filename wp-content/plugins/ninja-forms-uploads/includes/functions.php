<?php
/**
 * Normally, Ninja Forms checks for an empty input to determine whether or not a field has been left blank.
 * This function will be called to provide custom 'required field' validation.
 * 
 * If both the $_FILES[] and $_POST['_upload_ID_user_file_name'] are empty, then the upload field has not been submitted.
 *
 * @param int $field_id - ID number of the field that is currently being displayed.
 * @param array/string $user_value - the value of the field within the user-submitted form.
 */

function ninja_forms_field_upload_req_validation($field_id, $user_value){
	global $ninja_forms_processing;
	$file_error = false;
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

	if(!isset($_POST['_upload_'.$field_id][0]['user_file_name'])){
		$name = false;
	}else{
		$name = true;
	}

	if($file_error AND !$name){
		return false;
	}else{
		return true;
	}
}

/**
 * This function will filter the values output into the submissions table for uploads.
 * Instead of outputting what is actually in the submission database, which is an array of values (file_name, file_path, file_url),
 * this filter will output a link to where the file is stored.
 *
 * @param array/string $user_value - the value of the field within the user-submitted form.
 * @param int $field_id - ID number of the field that is currently being displayed.
 */

function ninja_forms_field_upload_sub_td($user_value, $field_id){
	$field_row = ninja_forms_get_field_by_id($field_id);
	$field_type = $field_row['type'];
	if($field_type == '_upload'){
		if(is_array($user_value) AND isset($user_value[0]) AND !empty($user_value[0])){
			$new_value = '';
			$x = 0;
			foreach($user_value as $value){
				if($x > 0){
					$new_value .= ' , ';
				}				
				$new_value .= '<a href="'.$value['file_url'].'" target="_blank">'.$value['file_name'].'</a>';
				$x++;
			}
			$user_value = $new_value;
		}
	}

	return $user_value;
}

/**
 * This function will filter the values that are saved within the submission database.
 * It allows those editing the submission to replace files submitted by uploading new ones.
 *
 * @param array/string $user_value - the value of the field within the user-submitted form.
 * @param int $field_id - ID number of the field that is currently being displayed.
 */

function ninja_forms_field_upload_save_sub($user_value, $field_id){
	global $ninja_forms_processing;

	$field_row = ninja_forms_get_field_by_id($field_id);
	$field_type = $field_row['type'];
	$sub_id = $ninja_forms_processing->get_form_setting('sub_id');
	if($field_type == '_upload'){
		if($ninja_forms_processing->get_form_setting('doing_save')){
			ninja_forms_field_upload_pre_process($field_id, $user_value);
			$user_value = $ninja_forms_processing->get_field_value($field_id);
		}else{
			//Check to see if sub_id has been set. If it hasn't, then don't do any filtering.
			if($sub_id != ''){
				
				//Check to see if this is an upload field. If it is, we'll do some processing.
				//If not, we'll just return the $user_value that was passed.
				if(isset($_FILES['ninja_forms_field_'.$field_id]['error'][0]) AND $_FILES['ninja_forms_field_'.$field_id]['error'][0] != 4){
					ninja_forms_field_upload_pre_process($field_id, $user_value);
					ninja_forms_field_upload_process($field_id, $user_value);
					$user_value = $ninja_forms_processing->get_field_value($field_id);
				}else if(isset($_POST['_upload_'.$field_id])){
					$user_value = $_POST['_upload_'.$field_id];
				}
			}
		}
	}

	return $user_value;
}

function ninja_forms_field_upload_filter_data($data, $field_id){
	$field_row = ninja_forms_get_field_by_id($field_id);
	$field_type = $field_row['type'];
	if($field_type == '_upload'){
		$data['label'] = '';
	}
	return $data;
}

function ninja_forms_get_uploads($args = array()){
	global $wpdb;
	$where = '';
	$limit = '';
	$upload_id = '';

	if(!empty($args)){

		if(isset($args['form_id'])){
			$where .= 'WHERE `form_id` = '.$args['form_id'];
		}
		if(isset($args['id'])){
			if($where == ''){
				$where .= "WHERE ";
			}else{
				$where .= " AND ";
			}
			$where .= "`id` = ".$args['id'];
			$upload_id = $args['id'];
		}
		if(isset($args['begin_date'])){
			$begin_date = $args['begin_date'];
			$begin_date .= ' 23:59:59';
			$begin_date = strtotime($begin_date);
			$begin_date = date("Y-m-d g:i:s", $begin_date);
			if($where == ''){
				$where .= "WHERE ";
			}else{
				$where .= " AND ";
			}
			$where .= "DATE(date_updated) > '".$begin_date."'";
		}
		if(isset($args['end_date'])){
			$end_date = $args['end_date'];
			$end_date .= ' 23:59:59';
			$end_date = strtotime($end_date);
			$end_date = date("Y-m-d g:i:s", $end_date);
			if($where == ''){
				$where .= "WHERE ";
			}else{
				$where .= " AND ";
			}
			$where .= "DATE(date_updated) < '".$end_date."'";
		}
	}

	$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".NINJA_FORMS_UPLOADS_TABLE_NAME." ".$where." ORDER BY `date_updated` ASC", NINJA_FORMS_UPLOADS_TABLE_NAME), ARRAY_A);
	

	if( isset( $args['upload_types'] ) OR isset( $args['upload_name'] ) ){
		if(is_array($results) AND !empty($results)){
			$tmp_results = array();

			for ($x = 0; $x < count($results); $x++){
				$results[$x]['data'] = unserialize($results[$x]['data']);
				$data = $results[$x]['data'];
				$form_id = $results[$x]['form_id'];
				$form_row = ninja_forms_get_form_by_id($form_id);
				$form_data = $form_row['data'];
				$form_title = $form_data['form_title'];
				$results[$x]['data']['form_title'] = $form_title;
				
				$user_file_name = $data['user_file_name'];
				$user_file_array = explode(".", $user_file_name);
				$user_ext = array_pop($user_file_array);

				$file_name = $data['file_name'];
				$file_array = explode(".", $file_name);
				$ext = array_pop($file_array);

				if(isset($args['upload_name'])){
					if(stripos($file_name, $args['upload_name']) !== false OR stripos($user_file_name, $args['upload_name']) !== false){
						$file_name_found = true;
					}else{
						$file_name_found = false;
					}
				}
				if(isset($args['upload_types'])){
					if( stripos( $args['upload_types'], $user_ext ) !== false OR stripos( $args['upload_types'], $ext ) !== false ){
						$ext_found = true;
					}else{
						$ext_found = false;
					}
				}
				if(isset($args['upload_name']) AND isset($args['upload_types'])){
					if($file_name_found AND $ext_found){
						array_push($tmp_results, $results[$x]);
					}
				}else if(isset($args['upload_name']) AND !isset($args['upload_types'])){
					if($file_name_found){
						array_push($tmp_results, $results[$x]);
					}
				}else if(isset($args['upload_types']) AND !isset($args['upload_name'])){
					if($ext_found){
						array_push($tmp_results, $results[$x]);
					}
				}
			}
			$results = $tmp_results;
		}
	}else{
		if(is_array($results) AND !empty($results)){
			for ($x = 0; $x < count($results); $x++){
				$results[$x]['data'] = unserialize($results[$x]['data']);
			}
		}
	}
	
	ksort($results);
	array_values($results);

	if($upload_id != ''){
		$results = $results[0];
	}
	
	return $results;
}
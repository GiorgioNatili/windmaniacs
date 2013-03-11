<?php

//Register the Upload field
add_action('init', 'ninja_forms_register_field_upload');

function ninja_forms_register_field_upload(){
	$args = array(
		'name' => 'File Upload', //Required - This is the name that will appear on the add field button.
		'edit_options' => array( //Optional - An array of options to show within the field edit <li>. Should be an array of arrays.
			array(
				'type' => 'text', //What type of input should this be?
				'name' => 'upload_types', //What should it be named. This should always be a programmatic name, not a label.
				'label' => '<strong>' . __('Allowed File Types', 'ninja-forms') . '</strong><br/>' . __('Comma Separated List of allowed file types. An empty list means all file types are accepted. (i.e. .jpg, .gif, .png, .pdf) This is not fool-proof and can be tricked, please remember that there is always a danger in allowing users to upload files.'), //Label to be shown before the option.
				'class' => 'widefat', //Additional classes to be added to the input element.
			),
			array(
				'type' => 'text',
				'name' => 'upload_rename',
				'label' => '<strong>' . __('Rename Uploaded File', 'ninja-forms') . '</strong><br />' . __('Advanced renaming options. If you do not want to rename the files, leave this box blank', 'ninja-forms').' <a href="#" class="ninja-forms-rename-help">' . __('Get help renaming files', 'ninja-forms') . '</a>',
				'class' => 'widefat',
			),
			array(
				'type' => 'checkbox',
				'name' => 'email_attachment',
				'label' => __('Email file as an attachment to administrators.'),
				//'width' => 'thin',
			),
		),
		'edit_function' => 'ninja_forms_field_upload_edit',
		'display_function' => 'ninja_forms_field_upload_display', //Required - This function will be called to create output when a user accesses a form containing this element.
		//'sub_edit_function' => 'ninja_forms_field_upload_sub_edit',	//Optional - This will be called when an admin or user edits the a user submission.
		'group' => '', //Optional
		'edit_label' => true, //True or False
		'edit_label_pos' => true,
		'edit_req' => true,
		'edit_custom_class' => true,
		'edit_help' => true,
		'edit_meta' => false,
		'sidebar' => 'template_fields',
		'edit_conditional' => true,
		'conditional' => array(
			'value' => array(
				'type' => 'text',
			),
		),
		'pre_process' => 'ninja_forms_field_upload_pre_process',
		'edit_sub_pre_process' => 'ninja_forms_field_upload_pre_process',
		'process' => 'ninja_forms_field_upload_process',
		'edit_sub_process' => 'ninja_forms_field_upload_process',
		'req_validation' => 'ninja_forms_field_upload_req_validation',
	);
	
	if( isset( $_REQUEST['form_id'] ) ){
		$form_row = ninja_forms_get_form_by_id( $_REQUEST['form_id'] );
		$form_data = $form_row['data'];
		if( isset( $form_data['create_post'] ) AND $form_data['create_post'] == 1 ){
			$option = array(
				'type' => 'checkbox',
				'name' => 'featured_image',
				'label' => __('Set as featured image for the Post.'),
				'class' => 'ninja-forms-upload-multi',
				'width' => 'wide',
			);
			array_push( $args['edit_options'], $option );
		}
	}

	if( function_exists( 'ninja_forms_register_field' ) ){
		ninja_forms_register_field('_upload', $args);
	}
}

add_action( 'admin_init', 'ninja_forms_field_upload_register_filters' );
function ninja_forms_field_upload_register_filters(){
	add_filter('ninja_forms_view_sub_td', 'ninja_forms_field_upload_sub_td', 10, 2);
	//add_filter('ninja_forms_save_sub', 'ninja_forms_field_upload_save_sub', 10, 2);
	//add_filter('ninja_forms_field', 'ninja_forms_field_upload_filter_data', 10, 2);
}

function ninja_forms_field_upload_default_value($data, $field_id){
	global $ninja_forms_processing;
	$field_row = ninja_forms_get_field_by_id($field_id);
	$field_type = $field_row['type'];
	if($field_type == '_upload'){
		if(isset($ninja_forms_processing) AND $ninja_forms_processing->get_form_setting('doing_save')){
			$current_val = $ninja_forms_processing->get_field_value($field_id);
			$data['default_value'] = $current_val;
		}
	}
	return $data;
}

function ninja_forms_field_upload_edit($field_id, $data){
	if(isset($data['upload_multi'])){
		$upload_multi = $data['upload_multi'];
	}else{
		$upload_multi = '';
	}
	if(isset($data['upload_multi_count'])){
		$upload_multi_count = $data['upload_multi_count'];
	}else{
		$upload_multi_count = '';
	}

	?>
	<div class="description description-thin">
		<input type="hidden" name="ninja_forms_field_<?php echo $field_id;?>[upload_multi]" value="0">
		<input type="checkbox" id="field_<?php echo $field_id;?>_upload_multi" name="ninja_forms_field_<?php echo $field_id;?>[upload_multi]" value="1" class="ninja-forms-upload-multi" <?php checked($upload_multi, 1);?>>
		<label for="field_<?php echo $field_id;?>_upload_multi">
			<?php _e( 'Allow the user to upload multiple files.' , 'ninja-forms'); ?>
		</label>
	</div>

	<div class="description description-thin" style="" id="field_<?php echo $field_id;?>_upload_multi_count_label">
		<label for="field_<?php echo $field_id;?>_upload_multi_count">
			<input type="text" id="field_<?php echo $field_id;?>_upload_multi_count" name="ninja_forms_field_<?php echo $field_id;?>[upload_multi_count]" value="<?php echo $upload_multi_count;?>" class="widefat">
			<?php _e( 'How many files can be uploaded?' , 'ninja-forms'); ?><br />
		</label>
	</div>
	<?php
}

/**
 * This is the main display function that will be called on the front-end when a user is filling out a form.
 *
 * @param int $field_id - ID number of the field that is currently being displayed.
 * @param array $data - array of field data as it has been processed to this point.
 */

function ninja_forms_field_upload_display($field_id, $data){
	global $ninja_forms_processing;

	$plugin_settings = get_option('ninja_forms_settings');
	if(isset($plugin_settings['max_filesize'])){
		$max_filesize = $plugin_settings['max_filesize'] * 1000000;
	}else{
		$max_filesize = 2000000;
	}

	if(isset($data['upload_multi']) AND $data['upload_multi'] == 1){
		$upload_multi = 'multi';
	}else{
		$upload_multi = '';
	}

	if(isset($data['upload_multi_count'])){
		$upload_multi_count = $data['upload_multi_count'];
	}else{
		$upload_multi_count = '';
	}


	$user_file_name = '';
	$file_name = '';
	$file_path = '';
	$file_url = '';
	$prefill = false;

	if(isset($ninja_forms_processing) AND $ninja_forms_processing->get_error('upload_'.$field_id)){
		$field_error = true;
	}else{
		$field_error = false;
	}

	$user_value = '';

	if(isset($data['default_value']) AND !empty($data['default_value'])){
		if(is_array($data['default_value']) AND isset($data['default_value'][0]) AND !empty($data['default_value'][0])){
			$user_value = $data['default_value'];
			$prefill = true;
		}
	}else if(isset($ninja_forms_processing) AND $ninja_forms_processing->get_field_value($field_id) AND $ninja_forms_processing->get_all_errors()){
		$user_value = $ninja_forms_processing->get_field_value($field_id);
		$prefill = true;
	}

	if(is_array($user_value) AND isset($user_value[0]) AND !empty($user_value[0])){
		foreach($user_value as $val){
			if($user_file_name != ''){
				$user_file_name .= ', ';
			}
			$user_file_name .= $val['user_file_name'];
		}
	}

	if(count($user_value) > 1){
		$str_label = __('Files', 'ninja-forms');
	}else{
		$str_label = __('File', 'ninja-forms');
	}

	if($prefill AND !$field_error){
		?>
		<?php _e('Uploaded', 'ninja-forms');?> <?php echo $str_label;?>: <?php echo $user_file_name;?> - <a href="#" name="" id="ninja_forms_change_file_upload_<?php echo $field_id;?>" class="ninja-forms-change-file-upload"><?php _e('Change Uploaded', 'ninja-forms');?> <?php echo $str_label;?></a><br />
		<span id="ninja_forms_file_upload_<?php echo $field_id;?>" style="display:none;">
		<?php

	}

	?>

	<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_filesize;?>">
	<input type="file" name="ninja_forms_field_<?php echo $field_id;?>[]" id="ninja_forms_field_<?php echo $field_id;?>" class="<?php echo $upload_multi;?>" maxlength="<?php echo $upload_multi_count;?>">
	<input type="hidden" name="ninja_forms_field_<?php echo $field_id;?>[]" value="">
	<?php
	if($prefill AND !$field_error){
		if(is_array($user_value) AND !empty($user_value)){
			$x = 0;
			foreach($user_value as $val){
				?>
				<input type="hidden" name="_upload_<?php echo $field_id;?>[<?php echo $x;?>][user_file_name]" value="<?php echo $val['user_file_name'];?>">
				<input type="hidden" name="_upload_<?php echo $field_id;?>[<?php echo $x;?>][file_name]" value="<?php echo $val['file_name'];?>">
				<input type="hidden" name="_upload_<?php echo $field_id;?>[<?php echo $x;?>][file_path]" value="<?php echo $val['file_path'];?>">
				<input type="hidden" name="_upload_<?php echo $field_id;?>[<?php echo $x;?>][file_url]" value="<?php echo $val['file_url'];?>">
				<?php
				$x++;
			}
		}

		?>
		</span>
		<?php
	}
}

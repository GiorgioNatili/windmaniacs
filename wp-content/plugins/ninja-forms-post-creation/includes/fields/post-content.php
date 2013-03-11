<?php
add_action('init', 'ninja_forms_register_field_post_content');

function ninja_forms_register_field_post_content(){
	$args = array(
		'name' => 'Content',
		//'edit_function' => 'ninja_forms_field_checkbox_edit',
		'edit_options' => array(
			array(
				'type' => 'checkbox', //What type of input should this be?
				'name' => 'content_rte', //What should it be named. This should always be a programmatic name, not a label.
				'label' => __('Show Rich Text Editor?', 'ninja-forms'),
				'width' => 'wide',
				//'class' => 'widefat', //Additional classes to be added to the input element.
			),			
			array(
				'type' => 'rte', //What type of input should this be?
				'name' => 'default_value', //What should it be named. This should always be a programmatic name, not a label.
				'label' => __('Default Value', 'ninja-forms'),
				'width' => 'wide',
				'class' => 'widefat', //Additional classes to be added to the input element.
			),
		),
		'display_function' => 'ninja_forms_field_post_content_display',		
		'group' => '',	
		'edit_label' => true,
		'edit_label_pos' => true,
		'edit_req' => true,
		'edit_custom_class' => true,
		'edit_help' => true,
		'edit_meta' => false,
		'sidebar' => 'post_fields',
		'edit_conditional' => true,
		'conditional' => array(
			'value' => array(
				'type' => 'text',
			),
		),
		'limit' => 1,
		'save_sub' => false,
		'pre_process' => 'ninja_forms_field_post_content_pre_process',
	);

	if( function_exists( 'ninja_forms_register_field' ) ){
		ninja_forms_register_field('_post_content', $args);
	}
}

function ninja_forms_field_post_content_display($field_id, $data){
	if(isset($data['default_value'])){
		$default_value = $data['default_value'];
	}else{
		$default_value = '';
	}

	if(isset($data['content_rte'])){
		$content_rte = $data['content_rte'];
	}else{
		$content_rte = 0;
	}
	if(isset($data['class'])){
		$class = $data['class'];
	}else{
		$class = '';
	}

	if($content_rte == 1){
		wp_editor( $default_value, 'ninja_forms_field_'.$field_id );
	}else{
		?>
		<textarea name="ninja_forms_field_<?php echo $field_id;?>" id="ninja_forms_field_<?php echo $field_id;?>" class="<?php echo $class;?>"><?php echo $default_value;?></textarea>
		<?php
	}
}

function ninja_forms_field_post_content_pre_process($field_id, $user_value){
	global $ninja_forms_processing;

	$post_content = $ninja_forms_processing->get_form_setting('post_content');
	if($post_content != ''){
		//Loop through each submitted form field and replace any instances of [label].
		if($ninja_forms_processing->get_all_fields()){
			foreach($ninja_forms_processing->get_all_fields() as $key => $val){
				$field_row = ninja_forms_get_field_by_id($key);
				$data = $field_row['data'];
				$label = $data['label'];

				$value = '';
				if(is_array($val) AND !empty($val)){
					$x = 0;
					foreach($val as $v){
						if(!is_array($v)){
							$value .= $v;
							if($x != count($val)){
								$value .= ',';
							}
						}
						$x++;
					}
				}else{
					$value = $val;
				}
				$post_content = str_replace('['.$label.']', $value, $post_content);
			}
		}
		if($ninja_forms_processing->get_form_setting('post_content_location') == 'append'){
			$post_content = $user_value.$post_content;
		}else{
			$post_content = $post_content.$user_value;
		}
	}else{
		$post_content = $user_value;
	}
	
	$ninja_forms_processing->update_form_setting('post_content', $post_content);
}
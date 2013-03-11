<?php
add_action('init', 'ninja_forms_register_field_post_tags');

function ninja_forms_register_field_post_tags(){
	$args = array(
		'name' => 'Tags',
		//'edit_function' => 'ninja_forms_field_checkbox_edit',
		'edit_options' => array(
			array(
				'name' => 'adv_tags',
				'type' => 'checkbox',
				'label' => __( 'Show Advanced Tag Selector', 'ninja-forms' ),
				'default' => 1,
			),
		),
		'display_function' => 'ninja_forms_field_post_tags_display',		
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
		'pre_process' => 'ninja_forms_field_post_tags_pre_process',
	);

	if( function_exists( 'ninja_forms_register_field' ) ){
		ninja_forms_register_field('_post_tags', $args);
	}
}

function ninja_forms_field_post_tags_display($field_id, $data){
	global $ninja_forms_processing;

	$field_class = ninja_forms_get_field_class($field_id);
	
	if(isset($data['default_value'])){
		$default_value = $data['default_value'];
	}else{
		$default_value = '';
	}
	
	if(isset($data['label_pos'])){
		$label_pos = $data['label_pos'];
	}else{
		$label_pos = "left";
	}

	if(isset($data['label'])){
		$label = $data['label'];
	}else{
		$label = '';
	}
	
	if(isset($data['adv_tags'])){
		$adv_tags = $data['adv_tags'];
	}else{
		$adv_tags = 0;
	}

	if($label_pos == 'inside'){
		$default_value = $label;
	}

	if($default_value == ''){
		if(isset($ninja_forms_processing)){
			$post_tags = $ninja_forms_processing->get_form_setting('post_tags');
		}else{
			$form_row = ninja_forms_get_form_by_field_id($field_id);
			$post_tags = $form_row['data']['post_tags'];
		}
		
		if($post_tags){
			$post_tags = explode(',', $post_tags);
		}
	}else{
		$post_tags = $default_value;
	}
	if($adv_tags == 1){
		$string_tag = '';
	
		if(is_array( $post_tags ) AND !empty( $post_tags ) ){
			$x = 0;
			foreach( $post_tags as $tag ){
				if(is_object($tag)){
					$tag_name = $tag->name;
				}else{
					$tag_name = $tag;
				}
				if($x > 0){
					$string_tag .= ', ';
				}
				$string_tag .= $tag_name;
				$x++;
			}
		}
		
		?>
		<div class="tagsdiv" id="post_tag">
			<div class="jaxtag">
				<input type="hidden" name="ninja_forms_field_<?php echo $field_id;?>" id="ninja_forms_post_tag_hidden" value="<?php echo $string_tag;?>">
	 			<div class="ajaxtag hide-if-no-js">
					<label class="screen-reader-text" for="new-tag-post_tag"><?php _e( 'Tags', 'ninja_forms' );?></label>
					<div class="taghint" style=""><?php _e( 'Add New Tag', 'ninja-forms' );?></div>
					<p><input type="text" id="ninja_forms_post_tag" class="newtag form-input-tip" size="16" autocomplete="off" value="">
					<input type="button" id="ninja_forms_post_add_tag" class="button" value="Add"></p>
				</div>
				<p class="howto"><?php _e( 'Separate tags with commas', 'ninja-forms' );?></p>
				</div>
			<div class="tagchecklist">
				<?php
				if(is_array( $post_tags ) AND !empty( $post_tags ) ){
					$x = 0;
					foreach( $post_tags as $tag ){
						if(is_object($tag)){
							$tag_name = $tag->name;
						}else{
							$tag_name = $tag;
						}
						?>
						<span id="<?php echo $tag_name;?>">
							<a id="post_tag-<?php echo $x;?>" class="ninja-forms-del-tag">X</a>&nbsp;<?php echo $tag_name;?>
						</span>
						<?php
						$x++;
					}
				}
				?>
			</div>
		
			<br />
			<a href="#" class="" id="ninja_forms_show_tag_cloud">Choose from the most used tags</a>
		
			<div id="ninja_forms_tag_cloud" style="display:none;">
				<?php
				$args = array(
					'echo' => false,
					'format' => 'array',
				);
				$tag_cloud = wp_tag_cloud($args);
				if( is_array( $tag_cloud ) AND !empty( $tag_cloud ) ){
					foreach( $tag_cloud as $tag ){

						$first_quote = strpos( $tag, "href='");
						$first_quote = $first_quote + 6;
						$second_quote = strpos( $tag, "'", $first_quote );
						$length = $second_quote - $first_quote;
						$url = substr( $tag, $first_quote, $length );
						$tag = str_replace( $url, '#', $tag );

						$first_quote = strpos( $tag, "class='");
						$first_quote = $first_quote + 7;
						$second_quote = strpos( $tag, "'", $first_quote );
						$length = $second_quote - $first_quote;
						$orig_class = substr( $tag, $first_quote, $length );
						$class = $orig_class.' ninja-forms-tag';
						$tag = str_replace( $orig_class, $class, $tag );

						echo $tag." ";
					}
				}
				?>
			</div>
		</div>
		<?php
	}else{
		$post_tags = implode( ',', $post_tags );
		?>
		<input id="ninja_forms_field_<?php echo $field_id;?>" name="ninja_forms_field_<?php echo $field_id;?>" type="text" class="<?php echo $field_class;?>" value="<?php echo $post_tags;?>" />
		<?php
	}
}

function ninja_forms_field_post_tags_pre_process($field_id, $user_value){
	global $ninja_forms_processing;

	$ninja_forms_processing->update_form_setting('post_tags', $user_value);
}
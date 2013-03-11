<?php
add_action('init', 'ninja_forms_register_post_settings_metabox', 99);

function ninja_forms_register_post_settings_metabox(){
	$args = array(
		'page' => 'ninja-forms',
		'tab' => 'form_settings',
		'slug' => 'create_post',
		'title' => __('Post creation settings', 'ninja-forms'),
		'display_function' => '',
		'state' => 'closed',
		'settings' => array(
			array(
				'name' => 'create_post',
				'type' => 'checkbox',
				'desc' => '',
				'label' => __('Create Post From Input?', 'ninja-forms'),
				'display_function' => '',
				'help' => __('If this box is checked, Ninja Forms will create a post from user input.', 'ninja-forms'),
				'default' => 0,
			),
			array(
				'name' => 'post_logged_in',
				'type' => 'checkbox',
				'desc' => '',
				'label' => __('Users must be logged in to create post?', 'ninja-forms'),
				'display_function' => '',
				'help' =>'',
				'default' => 0,
			),
			array(
				'name' => 'post_as',
				'type' => '',
				'desc' => '',
				'label' => __('Users must be logged in to create post?', 'ninja-forms'),
				'display_function' => 'ninja_forms_metabox_post_as',
				'help' =>'',
			),
			array(
				'name' => 'post_status',
				'type' => 'select',
				'options' => array(
					array('name' => 'Draft', 'value' => 'draft'),
					array('name' => 'Pending', 'value' => 'pending'),
					array('name' => 'Publish', 'value' => 'publish'),
				),
				'desc' => '',
				'label' => __('Select a post status', 'ninja-forms'),
				'display_function' => '',
				'help' =>'',
			),
			array(
				'name' => 'post_type',
				'type' => '',
				'desc' => '',
				'label' => __('Users must be logged in to create post?', 'ninja-forms'),
				'display_function' => 'ninja_forms_metabox_post_type',
				'help' =>'',
			),
			array(
				'name' => 'post_terms',
				'type' => '',
				'desc' => '',
				'label' => __('Default post terms', 'ninja-forms'),
				'display_function' => 'ninja_forms_metabox_post_terms',
				'help' =>'',
			),
			array(
				'name' => 'post_tags',
				'type' => 'text',
				'label' => __('Default post tags', 'ninja-forms'),
				'display_function' => '',
				'help' =>'',
				'desc' => __('Comma separated list', 'ninja-forms'),
			),
			array(
				'name' => 'post_content',
				'type' => '',
				'display_function' => 'ninja_forms_metabox_post_content',
			),
		),
	);
	if( function_exists( 'ninja_forms_register_tab_metabox' ) ){
		ninja_forms_register_tab_metabox($args);
	}
}

function ninja_forms_metabox_post_as($form_id, $data){
	if(isset($data['post_as'])){
		$post_as = $data['post_as'];
	}else{
		$post_as = '';
	}
	?>
	<label for='post_as'>
	<?php
		_e('Users post as', 'ninja-forms');
		wp_dropdown_users( array('name' => 'post_as', 'id' => 'post_as', 'show_option_all' => '-- Themselves', 'selected' => $post_as) );
	?>
	</label>
	<?php
}

function ninja_forms_metabox_post_type($form_id, $data){
	$post_types = get_post_types();
	if(isset($data['post_type'])){
		$post_type = $data['post_type'];
	}else{
		$post_type = 'post';
	}

	_e('Select a post type', 'ninja-forms');
	?>
	<select id="" name="post_type" class="ninja-forms-post-type">
	<?php
	foreach($post_types as $type){
		if($type != 'nav_menu_item' && $type != 'mediapage' && $type != 'attachment' && $type != 'revision'){
			$type_obj = get_post_type_object($type);
			?>
		<option value="<?php echo $type_obj->name;?>" <?php selected($type_obj->name, $post_type);?>><?php echo $type_obj->labels->name;?></option>
			<?php
		}
	}
	?>
	</select>
	<?php
}

function ninja_forms_metabox_post_terms($form_id, $data){

	if( isset( $data['post_type'] ) ){
		$post_type = $data['post_type'];
	}else{
		$post_type = 'post';
	}

	if( isset( $data['post_tax'] ) ){
		$post_tax = $data['post_tax'];
	}else{
		$post_tax = array('category');
	}

	$taxonomies = get_object_taxonomies( $post_type );

	if(is_array($taxonomies) AND !empty($taxonomies)){

		?>
		<div>
			<img src="<?php echo NINJA_FORMS_URL;?>/images/ajax-loader.gif" id="post_tax_loader" style="display:none;">
		</div>
		<?php
		_e('Default Terms', 'ninja-forms');
		?>
		<div id="post_tax_div">
			<?php
			foreach($taxonomies as $key){
				if($key != 'post_tag' AND $key != 'post_format'){
					$val = get_taxonomies(array('name' => $key), 'objects');
					$val = $val[$key];
					?>
					<label>
						<input type="checkbox" id="" name="post_tax[]" class="ninja-forms-post-tax" value="<?php echo $key;?>" <?php checked( in_array( $key, $post_tax ) );?>> <?php echo $val->label;?>
					</label>
					<?php
				}
				$terms = get_terms( $key, array( 'parent' => 0, 'hide_empty' => false ) );
				if( in_array( $key, $post_tax ) ){
					$display = '';
				}else{
					$display = 'display:none;';
				}
				if( isset( $data[$key.'_terms'] ) ){
					$terms_array = $data[$key.'_terms'];
				}else{
					$terms_array = array();
				}
				?>
				<div id="post_tax_<?php echo $key;?>" class="" style="<?php echo $display;?>">
					<ul class="categorychecklist">
					<?php
					foreach($terms as $t){
						if($key != 'post_tag' AND $key != 'post_format'){
							?>
							<li>
								<label>
									<input type="checkbox" id="post_term_<?php echo $t->term_id;?>" name="<?php echo $key;?>_terms[]" value="<?php echo $t->term_id;?>" <?php checked( in_array( $t->term_id, $terms_array ) );?>> <?php echo $t->name;?>
								</label>
							<?php
							$child_terms = get_categories( array( 'child_of' => $t->term_id, 'hide_empty' => false ) );
							if( is_array( $child_terms ) AND !empty( $child_terms ) ){
								?>
								<ul class="children categorychecklist form-no-clear">
								<?php
								foreach( $child_terms as $child_term ){
								?>
								<li>
									<label>
										<input type="checkbox" id="post_term_<?php echo $child_term->term_id;?>" name="<?php echo $key;?>_terms[]" value="<?php echo $child_term->term_id;?>" <?php checked( in_array( $t->term_id, $terms_array ) );?>> <?php echo $child_term->name;?>
									</label>
								</li>
								<?php
								}
								?>
								</ul>
								<?php
							}
							?>
							</li>
							<?php
						}
					}
					?>
					</ul>
				</div>
				<?php
			}
			?>
		</div>
	<?php
	}
}

function ninja_forms_metabox_post_content($form_id, $data){
	if(isset($data['post_content'])){
		$post_content = $data['post_content'];
	}else{
		$post_content = '';
	}
	if($post_content == ''){
		$display = 'display:none;';
	}else{
		$display = '';
	}
	if(isset($data['post_content_location'])){
		$post_content_location = $data['post_content_location'];
	}else{
		$post_content_location = 'append';
	}
	?>
	<br />
	<div id="post_content_link">
		<a href="#" id="ninja_forms_toggle_post_content"><?php _e('View Advanced Post Content Options', 'ninja-forms');?></a>
	</div>
	<div id="post_content_div" style="<?php echo $display;?>">

		<label for="post_content">
			<?php _e('Advanced Post Content', 'ninja-forms');?>
		</label>
		<p>
			<textarea id="post_content" name="post_content" class="post-content"><?php echo $post_content;?></textarea>
		</p>
		<span class="howto">
			<?php _e('This feature can be used to create post content from other Ninja Forms fields. In the textarea above, enter the labels of the fields you wish to include in the content, surrounded by braces.', 'ninja-forms');?>
			<?php _e('For example, if you wanted to add the values submitted by the user for the First Name and Last Name fields, you would enter:', 'ninja-forms');?>
			<br />
			<br />
			<?php _e('First Name: [First Name] Last Name: [Last Name].', 'ninja-forms');?>
			<br />
			<br />
			<?php _e('This would add those values to the post content before it was inserted.', 'ninja-forms');?>
			<br />
			<br />
			<?php _e('If you do not add a "Post Content" field, this value will be used as the sole Post Content', 'ninja-forms');?>
		</span>
		<p class="radio">
			<label for="post_content_location_prepend"><input type="radio" id="post_content_location_prepend" name="post_content_location" value="prepend" <?php checked($post_content_location, 'prepend');?>> <?php _e('Prepend to Post Content', 'ninja-forms');?></label>
		</p>
		<p class="radio">
			<label for="post_content_location_append"><input type="radio" id="post_content_location_append" name="post_content_location" value="append" <?php checked($post_content_location, 'append');?>> <?php _e('Append to Post Content', 'ninja-forms');?></label>
		</p>
	</div>

	<?php
}
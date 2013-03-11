<?php
function ninja_forms_output_tab_metabox($form_id = '', $slug, $metabox){
	$plugin_settings = get_option( 'ninja_forms_settings' );
	if($form_id != ''){
		$form_row = ninja_forms_get_form_by_id($form_id);
		$current_settings = $form_row['data'];
	}else{
		$form_id = '';
		$current_settings = get_option("ninja_forms_settings");
	}

	$page = $metabox['page'];
	$tab = $metabox['tab'];

	$title = $metabox['title'];
	if(isset($metabox['settings'])){
		$settings = $metabox['settings'];
	}else{
		$settings = '';
	}

	if(isset($metabox['display_function'])){
		$display_function = $metabox['display_function'];
	}else{
		$display_function = '';
	}

	if($metabox['state'] == 'closed'){
		$state = 'display:none;';
	}else{
		$state = '';
	}

	if( isset( $plugin_settings['metabox_state'][$page][$tab][$slug] ) ){
		$state = $plugin_settings['metabox_state'][$page][$tab][$slug];
	}

	if( isset( $metabox['display_container'] ) ){
		$display_container = $metabox['display_container'];
	}else{
		$display_container = true;
	}

	if( $display_container ){
		?>
		<div id="ninja_forms_metabox_<?php echo $slug;?>" class="postbox ">
			<span class="item-controls">
				<a class="item-edit metabox-item-edit" id="edit_id" title="Edit Menu Item" href="#">Edit Menu Item</a>
			</span>
			<h3 class="hndle"><span><?php _e($title, 'ninja-forms');?></span></h3>
			<div class="inside" style="<?php echo $state;?>">
			<table class="form-table">
				<tbody>
		<?php
	}
	if(is_array($settings) AND !empty($settings)){
		foreach($settings as $s){
			$value = '';
			if(isset($s['name'])){
				$name = $s['name'];
			}else{
				$name = '';
			}
			$name_array = '';
			if( strpos( $name, '[') !== false ){
				$name_array = str_replace( ']', '', $name );
				$name_array = explode( '[', $name_array );
			}
			if(isset($s['type'])){
				$type = $s['type'];
			}else{
				$type = '';
			}
			if(isset($s['desc'])){
				$desc = $s['desc'];
			}else{
				$desc = '';
			}
			if(isset($s['help_text'])){
				$help_text = $s['help_text'];
			}else{
				$help_text = '';
			}
			if(isset($s['label'])){
				$label = $s['label'];
			}else{
				$label = '';
			}
			if(isset($s['class'])){
				$class = $s['class'];
			}else{
				$class = '';
			}
			if(isset($s['max_file_size'])){
				$max_file_size = $s['max_file_size'];
			}else{
				$max_file_size = '';
			}
			if(isset($s['default_value'])){
				$default_value = $s['default_value'];
			}else{
				$default_value = '';
			}

			if( is_array( $name_array ) ){
				$tmp = '';
				foreach( $name_array as $n ){
					if( $tmp == '' ){
						if( isset( $current_settings[$n] ) ){
							$tmp = $current_settings[$n];
						}
					}else{
						if( isset( $tmp[$n] ) ){
							$tmp = $tmp[$n];
						}
					}
				}
				$value = $tmp;
			}else{
				if(isset($current_settings[$name])){
					if(is_array($current_settings[$name])){
						$value = ninja_forms_stripslashes_deep($current_settings[$name]);
					}else{
						$value = stripslashes($current_settings[$name]);
					}
				}else{
					$value = '';
				}
			}

			if( $value == '' ){
				$value = $default_value;
			}
			
			//$value = ninja_forms_esc_html_deep( $value );
			if($s['type'] == 'text'){ 
				$value = ninja_forms_esc_html_deep( $value );
				?>
				<tr>
					<th>
						<?php echo $label; ?>
					</th>
					<td>
						<input type="text" class="code widefat <?php echo $class;?>" name="<?php echo $name;?>" id="" value="<?php echo $value;?>" />
						<?php if($help_text != ''){ ?>
						<a href="#" class="tooltip">
						    <img id="" class='ninja-forms-help-text' src="<?php echo NINJA_FORMS_URL;?>/images/question-ico.gif" title="">
						    <span>
						        <img class="callout" src="<?php echo NINJA_FORMS_URL;?>/images/callout.gif" />
						        <?php echo $help_text;?>
						    </span>
						</a>
						<?php } ?>
					</td>
				<?php
			}elseif($s['type'] == 'select'){ ?>
				<tr>
					<th>
						<?php echo $label; ?>
					</th>
					<td>
						<select name="<?php echo $name;?>" class="<?php echo $class;?>">
							<?php
							if(is_array($s['options']) AND !empty($s['options'])){
								foreach($s['options'] as $option){
									?>
									<option value="<?php echo $option['value'];?>" <?php selected($value, $option['value']); ?>><?php echo $option['name'];?></option>
									<?php
								}
							} ?>
						</select>
						<?php if($help_text != ''){ ?>
							<a href="#" class="tooltip">
							    <img id="" class='ninja-forms-help-text' src="<?php echo NINJA_FORMS_URL;?>/images/question-ico.gif" title="">
							    <span>
							        <img class="callout" src="<?php echo NINJA_FORMS_URL;?>/images/callout.gif" />
							        <?php echo $help_text;?>
							    </span>
							</a>
						<?php } ?>
					</td>
				</tr>
				<?php
			}elseif($s['type'] == 'checkbox'){ ?>
				<tr>
					<th>
						<label for="<?php echo $name;?>"><?php echo $label;?></label>
					</th>
					<td>
						<input type="hidden" name="<?php echo $name;?>" value="0">
						<input type="checkbox" name="<?php echo $name;?>" value="1" <?php checked($value, 1);?> id="<?php echo $name;?>" class="<?php echo $class;?>">
						<?php if($help_text != ''){ ?>
							<a href="#" class="tooltip">
							    <img id="" class='ninja-forms-help-text' src="<?php echo NINJA_FORMS_URL;?>/images/question-ico.gif" title="">
							    <span>
							        <img class="callout" src="<?php echo NINJA_FORMS_URL;?>/images/callout.gif" />
							        <?php echo $help_text;?>
							    </span>
							</a>
						<?php } ?>
					</td>
				</tr>
				<?php
			}elseif( $s['type'] == 'checkbox_list' ){
				?>
				<tr>
					<th>
						<label for="<?php echo $name;?>_select_all">- <?php _e( 'Select All', 'ninja-forms' );?></label>
					</th>
					<td>
						<input type="checkbox" name="" value="" id="<?php echo $name;?>_select_all" class="ninja-forms-select-all" title="ninja-forms-<?php echo $name;?>">
					</td>
				</tr>
				<?php
				if(is_array($s['options']) AND !empty($s['options'])){
					foreach( $s['options'] as $option ){
						$option_name = $option['name'];
						$option_value = $option['value'];
						?>
						<tr>
							<th>
								<label for="<?php echo $option_name;?>"><?php echo $option_name;?></label>
							</th>
							<td>
								<input type="checkbox" class="ninja-forms-<?php echo $name;?> <?php echo $class;?>" name="<?php echo $name;?>[]" value="<?php echo $option_value;?> " <?php checked($value, $option_value);?> id="<?php echo $option_name;?>">
							</td>
						</tr>
						<?php
					}
				}
			}elseif($s['type'] == 'radio'){
				if(is_array($s['options']) AND !empty($s['options'])){
					$x = 0; ?>
					<tr>
						<th>
							<?php echo $desc;?>
						</th>
							<?php foreach($s['options'] as $option){ ?>
								<input type="radio" name="<?php echo $name;?>" value="<?php echo $option['value'];?>" id="<?php echo $name."_".$x;?>" <?php checked($value, $option['value']);?> class="<?php echo $class;?>"> <label for="<?php echo $name."_".$x;?>"><?php echo $option['name'];?></label>
									<?php if($help_text != ''){ ?>
										<a href="#" class="tooltip">
										    <img id="" class='ninja-forms-help-text' src="<?php echo NINJA_FORMS_URL;?>/images/question-ico.gif" title="">
										    <span>
										        <img class="callout" src="<?php echo NINJA_FORMS_URL;?>/images/callout.gif" />
										        <?php echo $help_text;?>
										    </span>
										</a>
									<?php } ?>
								<br />
							<?php
								$x++;
							} ?>
						</th>
					</tr>
				<?php }
			}elseif($s['type'] == 'textarea'){ 
				$value = ninja_forms_esc_html_deep( $value );
				?>
				<tr>
					<th>
						<?php echo $label; ?>
					</th>
					<td>
						<textarea name="<?php echo $name;?>" class="<?php echo $class;?>"><?php echo $value;?></textarea>
					</td>
				</tr>
				<?php
			}elseif($s['type'] == 'rte'){ ?>
				<tr>
					<th>
						<?php echo $label; ?>
					</th>
					<td>
						<?php wp_editor($value, $name); ?>
					</td>
				</tr>
				<?php
			}else if($s['type'] == ''){
				$display_function = $s['display_function'];
				$arguments['form_id'] = $form_id;
				$arguments['data'] = $current_settings;
				call_user_func_array($display_function, $arguments);
			}else if($s['type'] == 'submit'){
				?>
				<tr>
					<td colspan="2">
					<input type="submit" name="<?php echo $name;?>" id="" class="<?php echo $class;?>" value="<?php echo $label;?>">
					</td>
				</tr>
				<?php
			}else if($s['type'] == 'file'){
				?>
				<tr>
					<td colspan="2">
						<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size;?>" />
						<input type="file" name="<?php echo $name;?>" id="<?php echo $id;?>" class="<?php echo $class;?>">
					</td>
				</tr>
				<?php
			}else if( $s['type'] == 'desc' ){
				?>
				<tr>
					<th>
						<?php echo $label; ?>
					</th>
					<td>
						<?php echo $desc;?>
					</td>
				</tr>
				<?php
			}else if( $s['type'] == 'hidden' ){
				?>
				<input type="hidden" name="<?php echo $name;?>" value="<?php echo $value;?>">
				<?php
			}
			if( $desc != '' AND $s['type'] != 'desc' ){
				?>
				<tr>
					<th>
						
					</th>
					<td class="howto">
						<?php echo $desc;?>
					</td>
				</tr>
				<?php
			}
		}
	}else if($display_function != ''){
		if( $form_id != '' ){
			$arguments['form_id'] = $form_id;
		}
		$arguments['metabox'] = $metabox;
		call_user_func_array($display_function, $arguments);
	}

	if( $display_container ){
		?>
			</tbody>
			</table>
			</div>
		</div>
		<?php
	}
}
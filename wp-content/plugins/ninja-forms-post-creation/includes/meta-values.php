<?php
add_action( 'admin_init', 'ninja_forms_register_meta_value_box' );
function ninja_forms_register_meta_value_box(){
	if( isset( $_REQUEST['page'] ) AND $_REQUEST['page'] == 'ninja-forms' AND isset( $_REQUEST['form_id'] ) AND $_REQUEST['form_id'] != 'new' ){
		$form_id = $_REQUEST['form_id'];
		$form_row = ninja_forms_get_form_by_id( $form_id );
		$form_data = $form_row['data'];
		if( isset( $form_data['create_post'] ) ){
			$create_post = $form_data['create_post'];
		}else{
			$create_post = 0;
		}
		
		if( $create_post == 1 ){
			add_action( 'ninja_forms_edit_field_after_registered', 'ninja_forms_meta_value_box' );
		}
	}
}

add_action( 'init', 'ninja_forms_register_add_meta_values' );
function ninja_forms_register_add_meta_values(){
	add_action( 'ninja_forms_create_post', 'ninja_forms_add_meta_values' );
}

function ninja_forms_meta_value_box( $field_id ){
	global $wpdb, $ninja_forms_fields;
	$field_row = ninja_forms_get_field_by_id($field_id);
	$field_type = $field_row['type'];
	$field_data = $field_row['data'];
	$reg_field = $ninja_forms_fields[$field_type];
	$field_process = $reg_field['process_field'];

	if( isset( $field_data['meta_value'] ) ){
		$meta_value = $field_data['meta_value'];
	}else{
		$meta_value = '';
	}

	if( $field_process ){
		$meta_keys = $wpdb->get_results( "SELECT meta_key FROM $wpdb->postmeta", ARRAY_A );
		$meta_array = array();
		foreach( $meta_keys as $key ){
			$first_char = substr( $key['meta_key'], 0, 1 );
			if( $first_char != '_' ){
				array_push( $meta_array, $key['meta_key'] );
			}
		}
		$meta_array = array_unique( $meta_array );

		?>
		<div class=" description description-wide" id="ninja_forms_meta_values">
		<label class="label">
			<?php _e( 'Attach this value to custom post meta', 'ninja-forms' );?>:
		</label><br />
		<?php
		if( is_array( $meta_array ) AND !empty( $meta_array ) ){
			$custom = true;
			if( $meta_value != '' ){
				foreach( $meta_array  as $meta ){
					if( $meta_value == $meta ){
						$custom = false;
					}
				}
			}
		}
		?>
		<select name="" id="ninja_forms_field_<?php echo $field_id;?>_meta_value" class="ninja-forms-meta-value">
			<option value="" <?php selected($meta_value, '');?>>- None</option>
			<option value="custom" <?php selected($custom, true);?>>- Custom -></option>
			<?php
			if( is_array( $meta_array ) AND !empty( $meta_array ) ){
				$custom = true;
				if( $meta_value != '' ){
					foreach( $meta_array  as $meta ){
						if( $meta_value == $meta ){
							$custom = false;
						}
					}
				}
				

				foreach( $meta_array  as $meta ){
					?>
					<option value="<?php echo $meta;?>" <?php selected( $meta_value, $meta );?>><?php echo $meta;?></option>
					<?php
				}
			}
			?>
		</select>
		<?php
		if( $custom ){
			$display_input = '';
		}else{
			$display_input = 'display:none;';
		}
		?>
		<input type="text" name="ninja_forms_field_<?php echo $field_id;?>[meta_value]" id="ninja_forms_field_<?php echo $field_id;?>_custom_meta_value" value="<?php echo $meta_value;?>" style="<?php echo $display_input;?>">
		</div>
		<?php
	}
}

function ninja_forms_add_meta_values( $post_id ){
	global $ninja_forms_processing;

	$all_fields = $ninja_forms_processing->get_all_fields();
	$create_post = $ninja_forms_processing->get_form_setting( 'create_post' );
	if( $create_post == 1 ){
		if( is_array( $all_fields ) AND !empty( $all_fields ) ){
			foreach( $all_fields as $key => $value ){
				$field_row = ninja_forms_get_field_by_id( $key );
				$field_data = $field_row['data'];
				if( isset( $field_data['meta_value'] ) AND $field_data['meta_value'] != '' ){
					update_post_meta( $post_id, $field_data['meta_value'], $value );
				}
			}
		}
	}
}
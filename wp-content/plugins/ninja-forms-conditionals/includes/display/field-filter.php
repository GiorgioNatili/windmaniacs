<?php
add_action( 'init', 'ninja_forms_register_conditionals_field_filter' );
function ninja_forms_register_conditionals_field_filter(){
	add_filter( 'ninja_forms_field', 'ninja_forms_conditionals_field_filter', 11, 2 );
}

function ninja_forms_conditionals_field_filter( $data, $field_id ){
	global $ninja_forms_processing, $current_user;
	get_currentuserinfo();

	if(isset($current_user)){
		$user_id = $current_user->ID;	
	}else{
		$user_id = '';
	}

	$x = 0;
	$display_style = '';
	$form_row = ninja_forms_get_form_by_field_id( $field_id );
	$form_id = $form_row['id'];

	if(isset($data['conditional']) AND is_array($data['conditional'])){
		foreach($data['conditional'] as $conditional){
			if(is_array($conditional['cr']) AND !empty($conditional['cr'])){
				foreach($conditional['cr'] as $cr){
					if( is_object( $ninja_forms_processing)){
						$user_value = $ninja_forms_processing->get_field_value($cr['field']);
					}else{
						$field_row = ninja_forms_get_field_by_id( $cr['field'] );
						$field_data = $field_row['data'];

						if( isset( $field_data['default_value'] ) ){
							$user_value = $field_data['default_value'];
						}else{
							$user_value = '';
						}
					}
					if( isset( $cr['value'] ) ){
						$pass = ninja_forms_conditional_compare($user_value, $cr['value'], $cr['operator']);
					}else{
						$pass = true;
					}
				}
			}
			
			switch( $conditional['action'] ){
				case 'show':
					if( !$pass ){
						$data['display_style'] = 'display:none;';
					}else{
						$data['display_style'] = '';
					}
					break;
				case 'hide':
					if( $pass ){
						$data['display_style'] = 'display:none;';
					}
					break;
				case 'change_value':
					if( $pass ){
						$data['default_value'] == $conditional['value'];
					}
					break;
				case 'add_value':
					if( $pass ){
						if( isset( $data['list']['options'] ) AND is_array( $data['list']['options'] ) ){
							if( !isset( $conditional['value']['value'] ) ){
								$value = $conditional['value']['label'];
							}else{
								$value = $conditional['value'];
							}
						}else{
							$data['list']['options'] = array();
						}
						array_push( $data['list']['options'], $value );						
					}
					break;
				case 'remove_value':
					if( $pass ){
						if( isset( $data['list']['options'] ) AND is_array( $data['list']['options'] ) ){
							for ($x=0; $x < count( $data['list']['options'] ) ; $x++) { 
								if( isset( $data['list_show_value'] ) AND $data['list_show_value'] == 1 ){
									if( $data['list']['options'][$x]['value'] == $conditional['value'] ){
										$data['list']['options'][$x]['display_style'] = 'display:none;';
									}
								}else{
									if( $data['list']['options'][$x]['label'] == $conditional['value'] ){
										$data['list']['options'][$x]['display_style'] = 'display:none;';
									}
								}
							}
							$data['list']['options'] = array_values( $data['list']['options'] );
						}
					}
					break;
			}
		}
	}
	
	return $data;
}

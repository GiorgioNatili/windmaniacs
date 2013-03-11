<?php
/**
 * Adds the sub_id to the form if a sub_id is set.
 *
**/
add_action('init', 'ninja_forms_register_display_sub_id');
function ninja_forms_register_display_sub_id(){
	add_action('ninja_forms_display_after_fields', 'ninja_forms_display_sub_id');
}

function ninja_forms_display_sub_id($form_id){
	global $current_user;
	get_currentuserinfo();

	if(isset($current_user)){
		$user_id = $current_user->ID;	
	}else{
		$user_id = '';
	}
	
	if($user_id  != ''){
		$sub_row = ninja_forms_get_saved_form( $user_id, $form_id );
		if(is_array($sub_row) AND !empty($sub_row)){
			$sub_id = $sub_row['id'];
			?>
			<input type="hidden" name="_sub_id" value="<?php echo $sub_id;?>">
			<?php
		}
	}
}
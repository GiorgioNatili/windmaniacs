<?php
/**
 * Outputs the HTML for the save button.
 *
**/
add_action('init', 'ninja_forms_register_display_save_button');
function ninja_forms_register_display_save_button(){
	add_action('ninja_forms_display_after_fields', 'ninja_forms_display_save_button');
}

function ninja_forms_display_save_button($form_id){
	$form_row = ninja_forms_get_form_by_id($form_id);
	$form_data = $form_row['data'];
	if( isset( $form_data['save_progress'] ) ){
		$save_progress = $form_data['save_progress'];
	}else{
		$save_progress = 0;
	}
	if(is_user_logged_in() AND $save_progress == 1 AND !is_admin()){
	?>
	<div id="ninja_forms_form_<?php echo $form_id;?>_save_progress">
		<input type="submit" name="_save_progress" value="Save Progress">
	</div>
	<?php
	}
}
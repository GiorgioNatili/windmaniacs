<?php
/**
 * Outputs the HTML for the ajax processing div. Only used when submitting via ajax.
 *
**/

//add_action('ninja_forms_display_before_form_wrap', 'ninja_forms_display_process_message');
function ninja_forms_display_process_message($form_id){
	global $ninja_forms_processing_error, $ninja_forms_processing_response;
	$plugin_settings = get_option("ninja_forms_settings");
	$form_row = ninja_forms_get_form_by_id($form_id);
	$process_msg = $plugin_settings['process_msg'];
	$ajax = $form_row['data']['ajax'];
	
	if($ajax == 1){
	?>
	<div id="ninja_forms_form_<?php echo $form_id;?>_process_msg" style="display:none;"><?php echo $process_msg;?> <img src="<?php echo NINJA_FORMS_URL;?>/images/loading.gif"></div>
	<?php
	}
}
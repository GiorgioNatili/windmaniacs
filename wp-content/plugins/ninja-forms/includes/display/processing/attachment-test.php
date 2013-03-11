<?php
//add_action('ninja_forms_pre_process', 'ninja_forms_attachment_test');
function ninja_forms_attachment_test(){
	global $ninja_forms_processing;
	$files = $ninja_forms_processing->get_form_setting('user_attachments');
	$file1 = NINJA_FORMS_DIR."/uploads/artificer.jpg";
	$file2 = NINJA_FORMS_DIR."/uploads/steve.jpg";
	array_push($files, $file1, $file2);
	$ninja_forms_processing->update_form_setting('user_attachments', $files);
}	
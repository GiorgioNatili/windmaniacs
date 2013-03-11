<?php
function ninja_forms_process(){
	global $wpdb, $ninja_forms_fields, $ninja_forms_processing;

	$ajax = $ninja_forms_processing->get_form_setting('ajax');
		
	if(!$ninja_forms_processing->get_all_errors()){
		do_action('ninja_forms_process');
		ninja_forms_post_process();
	}else{
		if($ajax == 1){
			header('Content-Type', 'text/html');
			echo json_encode(array('form_id' => $form_id, 'error' => $ninja_forms_processing_error[$form_id]));
			die();
		}else{
			//echo 'processing';
			//print_r($ninja_forms_processing->get_all_errors());		
		}
	}
}
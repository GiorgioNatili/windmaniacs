<?php
function ninja_forms_setup_processing_class(){
	global $ninja_forms_processing;
	//Set the form id
	$form_id = $_REQUEST['_form_id'];

	//Initiate our processing class with our designated global variable.
	$ninja_forms_processing = new Ninja_Forms_Processing($form_id);
	$ninja_forms_processing->setup_submitted_vars();
}



function ninja_forms_pre_process(){
	global $ninja_forms_processing;
	
	$ajax = $ninja_forms_processing->get_form_setting('ajax');

	do_action('ninja_forms_before_pre_process');	
	
	if(!$ninja_forms_processing->get_all_errors()){
		do_action('ninja_forms_pre_process');
	}
	
	if(!$ninja_forms_processing->get_all_errors()){
		ninja_forms_process();
	}else{
		if($ajax == 1){
			header('Content-Type', 'text/html');
			echo json_encode(array('form_id' => $form_id, 'error' => $ninja_forms_processing_error[$form_id], 'success' => $ninja_forms_processing_response[$form_id]));
			die();
		}else{
			//echo 'pre-processing';
			//print_r($ninja_forms_processing->get_all_errors());
		}
	}
}
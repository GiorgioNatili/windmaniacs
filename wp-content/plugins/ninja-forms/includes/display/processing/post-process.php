<?php
function ninja_forms_post_process(){
	global $wpdb, $ninja_forms_fields, $ninja_forms_processing;

	$ajax = $ninja_forms_processing->get_form_setting('ajax');
	
	if(!$ninja_forms_processing->get_all_errors()){
		
		do_action('ninja_forms_post_process');

		if($ajax == 1){
			header('Content-Type', 'text/html');
			echo json_encode(array('form_id' => $form_id, 'success' => $ninja_forms_processing_response[$form_id]));
			die();
		}else{
			$ninja_forms_processing->update_form_setting( 'processing_complete', 1 );
			$ninja_forms_processing->add_success_msg( 'success_msg', $ninja_forms_processing->get_form_setting( 'success_msg' ) );
			if( $ninja_forms_processing->get_form_setting( 'landing_page' ) != '' ){
				$_SESSION['ninja_forms_processing'] = $ninja_forms_processing->get_all_fields();
				header( 'Location: '.$ninja_forms_processing->get_form_setting( 'landing_page' ) );
				die();
			}
		}
	}else{
		if($ajax == 1){
			header('Content-Type', 'text/html');
			echo json_encode(array('form_id' => $form_id, 'error' => $ninja_forms_processing_error[$form_id]));
			die();
		}else{
			//echo 'post-processing';
			//print_r($ninja_forms_processing->get_all_errors());		
		}
	}
}
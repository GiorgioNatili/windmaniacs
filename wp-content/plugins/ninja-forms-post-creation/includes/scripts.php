<?php

add_action( 'admin_init', 'ninja_forms_post_creation_admin_js' );
function ninja_forms_post_creation_admin_js(){
	if(isset($_REQUEST['page']) AND $_REQUEST['page'] == 'ninja-forms'){
		wp_enqueue_script('ninja-forms-post-creation-admin',
			NINJA_FORMS_POST_URL .'/js/dev/ninja-forms-post-creation-admin.js',
			array('jquery', 'jquery-ui-dialog', 'jquery-ui-datepicker', 'jquery-form'));
	}
}

add_action( 'admin_init', 'ninja_forms_post_creation_admin_css' );
function ninja_forms_post_creation_admin_css(){
	if(isset($_REQUEST['page']) AND $_REQUEST['page'] == 'ninja-forms'){
		wp_enqueue_style('ninja-forms-post-creation-admin', NINJA_FORMS_POST_URL .'/css/ninja-forms-post-creation-admin.css');
	}
}
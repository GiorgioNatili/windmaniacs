<?php

function ninja_forms_uploads_activation(){
	global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	if($wpdb->get_var("SHOW TABLES LIKE '".NINJA_FORMS_UPLOADS_TABLE_NAME."'") == NINJA_FORMS_UPLOADS_TABLE_NAME) {
		$wpdb->query("DROP TABLE ".NINJA_FORMS_UPLOADS_TABLE_NAME);
	}

	$sql = "CREATE TABLE IF NOT EXISTS ".NINJA_FORMS_UPLOADS_TABLE_NAME." (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `user_id` int(11) DEFAULT NULL,
	  `form_id` int(11) NOT NULL,
	  `field_id` int(11) NOT NULL,
	  `data` longtext CHARACTER SET utf8 NOT NULL,
	  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
	
	dbDelta($sql);

	$opt = get_option( 'ninja_forms_settings' );

	if( isset( $opt['version'] ) ){
		$current_version = $opt['version'];
	}else{
		$current_version = '';
	}

	

	if( version_compare( $current_version, '2.0' , '<' ) OR $current_version == '' ){

		$opt['base_upload_dir'] = NINJA_FORMS_UPLOADS_DIR.'/uploads/';
		$opt['custom_upload_dir'] = '';
		$opt['upload_error'] = __('There was an error uploading your file.', 'ninja-forms');
		$opt['max_file_size'] = 2;

		update_option( 'ninja_forms_settings', $opt );
	}
}
<?php
/*
Plugin Name: Ninja Forms - Save Progress
Plugin URI: http://ninjaforms.com
Description: Save Progress add-on for Ninja Forms.
Version: 0.1
Author: The WP Ninjas
Author URI: http://wpninjas.net
*/

define("NINJA_FORMS_SAVE_PROGRESS_DIR", WP_PLUGIN_DIR."/ninja-forms-save-progress");
define("NINJA_FORMS_SAVE_PROGRESS_URL", plugins_url()."/ninja-forms-save-progress");
define("NINJA_FORMS_SAVE_PROGRESS_VERSION", 0.1);

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'NINJA_FORMS_SAVE_PROGRESS_EDD_SL_STORE_URL', 'http://wpninjas.com' ); // IMPORTANT: change the name of this constant to something unique to prevent conflicts with other plugins using this system
 
// the name of your product. This is the title of your product in EDD and should match the download title in EDD exactly
define( 'NINJA_FORMS_SAVE_PROGRESS_EDD_SL_ITEM_NAME', 'Save User Progress' ); // IMPORTANT: change the name of this constant to something unique to prevent conflicts with other plugins using this system

//Require EDD autoupdate file
if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater if it doesn't already exist
	require_once( NINJA_FORMS_SAVE_PROGRESS_DIR.'/includes/EDD_SL_Plugin_Updater.php' );	
}

$plugin_settings = get_option( 'ninja_forms_settings' );

// retrieve our license key from the DB
if( isset( $plugin_settings['save_progress_license'] ) ){
	$save_progress_license = $plugin_settings['save_progress_license'];
}else{
	$save_progress_license = '';
}

// setup the updater
$edd_updater = new EDD_SL_Plugin_Updater( NINJA_FORMS_SAVE_PROGRESS_EDD_SL_STORE_URL, __FILE__, array( 
		'version' 	=> NINJA_FORMS_SAVE_PROGRESS_VERSION, 		// current version number
		'license' 	=> $save_progress_license, 	// license key (used get_option above to retrieve from DB)
		'item_name'     => NINJA_FORMS_SAVE_PROGRESS_EDD_SL_ITEM_NAME, 	// name of this plugin
		'author' 	=> 'WP Ninjas'  // author of this plugin
	)
);

require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/admin/form-settings-metabox.php");
require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/admin/edit-sub-addon.php");
require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/admin/select-subs-option.php");
require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/admin/login-settings.php");
require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/admin/license-option.php");

require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/display/fields/restore-progress.php");

require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/display/form/login-form.php");
require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/display/form/save-progress-button.php");
require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/display/form/register-form.php");
require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/display/form/resume-link.php");
require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/display/form/sub-id.php");
require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/display/form/form-visibility.php");

require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/display/processing/save-progress.php");
require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/display/processing/register.php");
require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/display/processing/login.php");
require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/display/processing/save-sub-filter.php");

require_once(NINJA_FORMS_SAVE_PROGRESS_DIR."/includes/activation.php");

register_activation_hook( __FILE__, 'ninja_forms_save_progress_activation' );

function ninja_forms_get_saved_form( $user_id, $form_id ){
	global $wpdb;
	$sub_row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".NINJA_FORMS_SUBS_TABLE_NAME." WHERE `user_id` = %d AND `form_id` = %d AND `status` = 0 AND `saved` = 1 LIMIT 1", $user_id, $form_id ), ARRAY_A );
	if( is_array( $sub_row ) AND !empty( $sub_row ) ){
		$sub_row['data'] = unserialize( $sub_row['data'] );
	}
	return $sub_row;
}
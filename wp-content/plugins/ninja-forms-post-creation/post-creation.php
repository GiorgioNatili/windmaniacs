<?php
/*
Plugin Name: Ninja Forms - Post Creation
Plugin URI: http://ninjaforms.com
Description: Post Creation add-on for Ninja Forms.
Version: 0.1
Author: The WP Ninjas
Author URI: http://wpninjas.net
*/

define("NINJA_FORMS_POST_DIR", WP_PLUGIN_DIR."/ninja-forms-post-creation");
define("NINJA_FORMS_POST_URL", plugins_url()."/ninja-forms-post-creation");
define("NINJA_FORMS_POST_VERSION", 0.1);

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'NINJA_FORMS_POST_EDD_SL_STORE_URL', 'http://wpninjas.com' ); // IMPORTANT: change the name of this constant to something unique to prevent conflicts with other plugins using this system
 
// the name of your product. This is the title of your product in EDD and should match the download title in EDD exactly
define( 'NINJA_FORMS_POST_EDD_SL_ITEM_NAME', 'Front-End Posting' ); // IMPORTANT: change the name of this constant to something unique to prevent conflicts with other plugins using this system

//Require EDD autoupdate file
if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater if it doesn't already exist
	require_once( NINJA_FORMS_POST_DIR.'/includes/EDD_SL_Plugin_Updater.php' );	
}

$plugin_settings = get_option( 'ninja_forms_settings' );

// retrieve our license key from the DB
if( isset( $plugin_settings['post_creation_license'] ) ){
	$post_creation_license = $plugin_settings['post_creation_license'];
}else{
	$post_creation_license = '';
}

// setup the updater
$edd_updater = new EDD_SL_Plugin_Updater( NINJA_FORMS_POST_EDD_SL_STORE_URL, __FILE__, array( 
		'version' 	=> NINJA_FORMS_POST_VERSION, 		// current version number
		'license' 	=> $post_creation_license, 	// license key (used get_option above to retrieve from DB)
		'item_name'     => NINJA_FORMS_POST_EDD_SL_ITEM_NAME, 	// name of this plugin
		'author' 	=> 'WP Ninjas'  // author of this plugin
	)
);

require_once(NINJA_FORMS_POST_DIR."/includes/admin/form-settings-metabox.php");
require_once(NINJA_FORMS_POST_DIR."/includes/admin/field-settings-sidebar.php");
require_once(NINJA_FORMS_POST_DIR."/includes/admin/scripts.php");
require_once(NINJA_FORMS_POST_DIR."/includes/admin/license-option.php");

require_once(NINJA_FORMS_POST_DIR."/includes/display/processing/process.php");
require_once(NINJA_FORMS_POST_DIR."/includes/display/scripts.php");

require_once(NINJA_FORMS_POST_DIR."/includes/fields/post-title.php");
require_once(NINJA_FORMS_POST_DIR."/includes/fields/post-content.php");
require_once(NINJA_FORMS_POST_DIR."/includes/fields/post-tags.php");
require_once(NINJA_FORMS_POST_DIR."/includes/fields/post-terms.php");

require_once(NINJA_FORMS_POST_DIR."/includes/ajax.php");
require_once(NINJA_FORMS_POST_DIR."/includes/meta-values.php");
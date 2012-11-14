<?php 

define('THEME_VERSION', '1.2.3');

define('PARENT_DIR', TEMPLATEPATH);
define('PRIMA_CORE_DIR', trailingslashit(PARENT_DIR).'core');
define('PRIMA_FUNCTIONS_DIR', trailingslashit(PRIMA_CORE_DIR).'functions');
define('PRIMA_FRONTEND_DIR', trailingslashit(PRIMA_CORE_DIR).'frontend');
define('PRIMA_ADMIN_DIR', trailingslashit(PRIMA_CORE_DIR).'admin');
define('PRIMA_WIDGETS_DIR', trailingslashit(PRIMA_CORE_DIR).'widgets');
define('PRIMA_SHORTCODES_DIR', trailingslashit(PRIMA_CORE_DIR).'shortcodes');
define('PRIMA_DOCS_DIR', trailingslashit(PARENT_DIR).'documentation');
define('CHILD_DIR', STYLESHEETPATH);

define('PARENT_URL', get_bloginfo('template_directory'));
define('PRIMA_CORE_URL', trailingslashit(PARENT_URL).'core');
define('PRIMA_CORE_IMAGES_URL', trailingslashit(PRIMA_CORE_URL).'css/images');
define('PRIMA_CORE_JS_URL', trailingslashit(PRIMA_CORE_URL).'js');
define('PRIMA_CORE_CSS_URL', trailingslashit(PRIMA_CORE_URL).'css');
define('PRIMA_WIDGETS_URL', trailingslashit(PRIMA_CORE_URL).'widgets');
define('PRIMA_SHORTCODES_URL', trailingslashit(PRIMA_CORE_URL).'shortcodes');
define('PRIMA_DOCS_URL', trailingslashit(PARENT_URL).'documentation');
define('PRIMA_JS_URL', trailingslashit(PARENT_URL).'js');
define('PRIMA_CSS_URL', trailingslashit(PARENT_URL).'css');

define('CHILD_URL', get_bloginfo('stylesheet_directory'));
define('PRIMA_IMAGES_URL', trailingslashit(CHILD_URL).'images');

define('PRIMA_SIDEBAR_SETTINGS', 'prima_sidebars');

// add_editor_style();
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );

if ( PARENT_DIR !== CHILD_DIR ) {
	load_child_theme_textdomain( PRIMA_DOMAIN, trailingslashit(CHILD_DIR) . 'languages' );
	$locale = get_locale();
	$locale_file = trailingslashit(CHILD_DIR) . "languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );
}
else {
	load_theme_textdomain( PRIMA_DOMAIN, trailingslashit(PARENT_DIR) . 'languages' );
	$locale = get_locale();
	$locale_file = trailingslashit(PARENT_DIR) . "languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );
}

require_once( trailingslashit(PRIMA_FUNCTIONS_DIR). 'options.php' );
require_once( trailingslashit(PRIMA_FUNCTIONS_DIR). 'type-promotion.php' );
require_once( trailingslashit(PRIMA_FUNCTIONS_DIR). 'type-slider.php' );
require_once( trailingslashit(PRIMA_FUNCTIONS_DIR). 'type-testimonial.php' );
require_once( trailingslashit(PRIMA_FUNCTIONS_DIR). 'sidebars.php' );
require_once( trailingslashit(PRIMA_FUNCTIONS_DIR). 'widgets.php' );
require_once( trailingslashit(PRIMA_FUNCTIONS_DIR). 'menu.php' );
require_once( trailingslashit(PRIMA_FUNCTIONS_DIR). 'fonts.php' );
require_once( trailingslashit(PRIMA_FUNCTIONS_DIR). 'images.php' );
if(class_exists('WP_eCommerce'))
	require_once( trailingslashit(PRIMA_FUNCTIONS_DIR). 'wpsc.php' );

if ( !is_admin() ) {
	require_once( trailingslashit(PRIMA_FRONTEND_DIR). 'styles.php' );
	require_once( trailingslashit(PRIMA_FRONTEND_DIR). 'scripts.php' );
	require_once( trailingslashit(PRIMA_FRONTEND_DIR). 'title.php' );
	require_once( trailingslashit(PRIMA_FRONTEND_DIR). 'sliders.php' );
	require_once( trailingslashit(PRIMA_FRONTEND_DIR). 'fonts.php' );
	require_once( trailingslashit(PRIMA_FRONTEND_DIR). 'posts.php' );
	require_once( trailingslashit(PRIMA_FRONTEND_DIR). 'branding.php' );
	require_once( trailingslashit(PRIMA_FRONTEND_DIR). 'general.php' );
	if(class_exists('WP_eCommerce')) {
		require_once( trailingslashit(PRIMA_FRONTEND_DIR). 'wpsc.php' );
		require_once( trailingslashit(PRIMA_FRONTEND_DIR). 'wpsc-ajax.php' );
	}
}
if ( is_admin() ) {
	require_once( trailingslashit(PRIMA_ADMIN_DIR) . 'admin.php');
	require_once( trailingslashit(PRIMA_ADMIN_DIR) . 'functions.php');
	require_once( trailingslashit(PRIMA_ADMIN_DIR) . 'theme-settings.php');
	require_once( trailingslashit(PRIMA_ADMIN_DIR) . 'sidebar-settings.php');
	if(class_exists('WP_eCommerce')) {
		require_once( trailingslashit(PRIMA_ADMIN_DIR). 'metabox.php' );
	}
	if( PARENT_DIR != CHILD_DIR ) 
		require_once( trailingslashit(PRIMA_ADMIN_DIR) . 'childthemefiles.php');
}

if ( is_admin() ) {
	require_once( trailingslashit(PRIMA_WIDGETS_DIR) . 'forms.php' );
}
require_once( trailingslashit(PRIMA_WIDGETS_DIR) . 'general.php' );
if(class_exists('WP_eCommerce'))
	require_once( trailingslashit(PRIMA_WIDGETS_DIR). 'wpsc.php' );

require_once( trailingslashit(PRIMA_SHORTCODES_DIR) . 'shortcodes.php' );
if ( !is_admin() ) {
	require_once( trailingslashit(PRIMA_SHORTCODES_DIR) . 'theme.php' );
	require_once( trailingslashit(PRIMA_SHORTCODES_DIR) . 'post.php' );
	require_once( trailingslashit(PRIMA_SHORTCODES_DIR) . 'typography.php' );
	require_once( trailingslashit(PRIMA_SHORTCODES_DIR) . 'general.php' );
	require_once( trailingslashit(PRIMA_SHORTCODES_DIR) . 'conditional.php' );
	require_once( trailingslashit(PRIMA_SHORTCODES_DIR) . 'media.php' );
	require_once( trailingslashit(PRIMA_SHORTCODES_DIR) . 'prima.php' );
}
else {
	require_once( trailingslashit(PRIMA_SHORTCODES_DIR) . 'generator/tinymce.php' );
}


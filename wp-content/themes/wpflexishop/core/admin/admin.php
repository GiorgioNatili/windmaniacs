<?php
add_action('admin_menu', 'prima_add_admin_menu');
function prima_add_admin_menu() {
	global $menu, $_prima_settings_pagehook;
	$menu['58.995'] = array( '', 'manage_options', 'separator-prima', '', 'wp-menu-separator' );
	add_menu_page(PRIMA_THEME_NAME, PRIMA_THEME_NAME, 'manage_options', 'primathemes', 'prima_theme_settings_admin', PRIMA_CORE_CSS_URL.'/images/prima.png', '58.996');
	$_prima_settings_pagehook = add_submenu_page('primathemes', __('Theme Settings',PRIMA_DOMAIN), __('Theme Settings',PRIMA_DOMAIN), 'manage_options', 'primathemes', 'prima_theme_settings_admin');
	add_submenu_page('primathemes', __('Import/Export',PRIMA_DOMAIN), __('Import/Export',PRIMA_DOMAIN), 'manage_options', 'primathemes-import-export', 'prima_import_export_admin');
}
add_action('admin_menu', 'prima_add_childthemefiles_menu', 15);
function prima_add_childthemefiles_menu() {
	global $menu, $_prima_childthemefiles_pagehook;
	$_prima_childthemefiles_pagehook = ( PARENT_DIR != CHILD_DIR ) ? add_submenu_page('primathemes', __('Child Theme Files', PRIMA_DOMAIN), __('Child Theme Files', PRIMA_DOMAIN), 'manage_options', 'primathemes-childthemefiles', 'prima_childthemefiles_admin') : null;
}
add_action('admin_menu', 'prima_add_documentation_menu', 15);
function prima_add_documentation_menu() {
	global $menu, $_prima_documentation_pagehook;
	$_prima_documentation_pagehook = ( file_exists( PRIMA_DOCS_DIR . '/readme.txt' ) || file_exists( PRIMA_DOCS_DIR . '/changelog.txt' ) || file_exists( PRIMA_DOCS_DIR . '/license.txt' ) ) ? add_submenu_page('primathemes', __('Documentation', PRIMA_DOMAIN), __('Documentation', PRIMA_DOMAIN), 'manage_options', 'primathemes-documentation', 'prima_documentation_admin') : null;
}
add_action( 'admin_head', 'prima_admin_logo' );
function prima_admin_logo() {
	$logo = prima_get_option( "adminlogo" );
	global $wp_version;
	if ( version_compare( $wp_version, '3.1.4', '>' ) ) {
		if ( !$logo ) $logo = PRIMA_CORE_CSS_URL.'/images/prima.png';
	}
	else {
		if ( !$logo ) $logo = PRIMA_CORE_CSS_URL.'/images/adminlogo.png';
	}
	if ( !$logo ) return;
	echo '
	<style type="text/css">
	#wp-admin-bar-wp-logo > .ab-item .ab-icon, #wpadminbar.nojs #wp-admin-bar-wp-logo:hover > .ab-item .ab-icon, #wpadminbar #wp-admin-bar-wp-logo.hover > .ab-item .ab-icon, #header-logo { background-image: url('.$logo.') !important; background-position: center center; }
	</style>
	';
}
add_action('admin_init', 'prima_load_admin_styles');
function prima_load_admin_styles() {
	wp_enqueue_style('prima_admin_css', PRIMA_CORE_CSS_URL.'/admin.css');
}
function prima_theme_settings_defaults() {
	$defaults = array();
	return apply_filters('prima_theme_settings_defaults', $defaults);
}
add_action('admin_init', 'prima_register_theme_settings', 5);
function prima_register_theme_settings() {
	register_setting( PRIMA_OPTIONS, PRIMA_OPTIONS );
	add_option( PRIMA_OPTIONS, prima_theme_settings_defaults() );
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'primathemes' )
		return;
	if ( prima_get_option('reset') ) {
		update_option(PRIMA_OPTIONS, prima_theme_settings_defaults());
		wp_redirect( admin_url( 'admin.php?page=primathemes&reset=true' ) );
		exit;
	}
}
add_action('admin_notices', 'prima_theme_settings_notice');
function prima_theme_settings_notice() {
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'primathemes' )
		return;
	if ( isset( $_REQUEST['reset'] ) && $_REQUEST['reset'] == 'true' ) {
		echo '<div id="message" class="updated"><p><strong>'.__('Theme Settings Reset', PRIMA_DOMAIN).'</strong></p></div>';
	}
	elseif ( isset($_REQUEST['settings-updated']) && $_REQUEST['settings-updated'] == 'true') {  
		echo '<div id="message" class="updated"><p><strong>'.__('Theme Settings Saved', PRIMA_DOMAIN).'</strong></p></div>';
	}
}
add_action('admin_menu', 'prima_theme_settings_init');
function prima_theme_settings_init() {
	global $_prima_settings_pagehook;
	add_action('load-'.$_prima_settings_pagehook, 'prima_theme_settings_scripts');
	add_action('load-'.$_prima_settings_pagehook, 'prima_theme_settings_styles');
	add_filter( 'media_upload_tabs', 'prima_settings_upload_tabs', 100 );
	add_action( "admin_head-media-upload-popup", 'prima_settings_upload_head' );
	add_action('admin_print_styles-post.php', 'prima_post_settings_scripts');
	add_action('admin_print_styles-post-new.php', 'prima_post_settings_scripts');
}
function prima_post_settings_scripts() {	
	wp_enqueue_script('prima_post_js', PRIMA_CORE_JS_URL.'/post.js');	
}
function prima_theme_settings_scripts() {	
	global $_prima_settings_pagehook;
	add_thickbox();
	wp_enqueue_script('theme-preview');
	wp_enqueue_script('common');
	wp_enqueue_script('wp-lists');
	wp_enqueue_script('postbox');
	wp_enqueue_script('farbtastic');
	wp_enqueue_script('prima_admin_js', PRIMA_CORE_JS_URL.'/admin.js', array('farbtastic'));	
	$params = array(
		'pageHook'      => $_prima_settings_pagehook,
		'closeAll'     => __('Close All', PRIMA_DOMAIN),
		'openAll'     => __('Open All', PRIMA_DOMAIN)
	);
	wp_localize_script('prima_admin_js', 'primathemes', $params); 
}
function prima_theme_settings_styles() {	
	wp_enqueue_style('farbtastic');
}
function prima_settings_upload_tabs ( $tabs ) {
	global $wpdb;
	$post_id = intval($_REQUEST['post_id']);
	if ( !isset($_REQUEST['post_id']) ) return $tabs;
	if ( get_post_type($post_id) != 'primaframework' ) return $tabs;
	$tabs = array(
		'type' => __('Upload', PRIMA_DOMAIN)
	);
	$attachments = intval( $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM $wpdb->posts WHERE post_type = 'attachment' AND post_status != 'trash' AND post_parent = %d", $post_id ) ) );
	if ( !empty($attachments) ) {
		$tabs['gallery'] = sprintf(__('Previously Uploaded (%s)', PRIMA_DOMAIN), "<span id='attachments-count'>$attachments</span>");
	}
	return $tabs;
}
function prima_settings_upload_head () {
	global $wpdb;
	$post_id = intval($_REQUEST['post_id']);
	if ( !isset($_REQUEST['post_id']) ) return;
	if ( get_post_type($post_id) != 'primaframework' ) return;
?>
	<script type="text/javascript">
	jQuery(document).ready( function($) {
		$( '.savesend input.button, .media-item #go_button' ).attr( 'value', 'Use this File' );
		$( 'a.wp-post-thumbnail, div#gallery-settings, tr.post_title, tr.image_alt, tr.post_excerpt, tr.post_content, tr.url, tr.align, #media-upload p.ml-submit' ).hide();
		$('.savesend input.button, .media-item #go_button').live('load', function(event) { 
			$(this).attr( 'value', 'Use this File' );
		});
	});
	</script>
    <style type="text/css">
		a.wp-post-thumbnail, div#gallery-settings, tr.post_title, tr.image_alt, tr.post_excerpt, tr.post_content, tr.url, tr.align, #media-upload p.ml-submit { display: none; visibility: hidden; }
	</style>
<?php
}
add_filter('screen_layout_columns', 'prima_theme_settings_layout_columns', 10, 2);
function prima_theme_settings_layout_columns($columns, $screen) {
	global $_prima_settings_pagehook;
	if ($screen == $_prima_settings_pagehook) {
		$columns[$_prima_settings_pagehook] = 2;
	}
	return $columns;
}
function prima_theme_settings_admin() { 
	global $_prima_settings_pagehook;
?>	
	<div id="prima-theme-settings" class="wrap prima-metaboxes">
	<form method="post" action="options.php">
		<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
		<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
		<?php settings_fields(PRIMA_OPTIONS); // important! ?>
		<input type="hidden" name="<?php echo PRIMA_OPTIONS; ?>[theme_version]>" value="<?php echo esc_attr(prima_option('theme_version')); ?>" />
		<input type="hidden" name="<?php echo PRIMA_OPTIONS; ?>[datesave]>" value="<?php echo str_replace( array( "-", " ", ":"), '', current_time('mysql') ); ?>" />
		<?php screen_icon('options-prima'); ?>
		<h2>
			<?php echo PRIMA_THEME_NAME.' - '.__('Theme Settings', PRIMA_DOMAIN); ?><br/>
			<input type="submit" class="button-primary" value="<?php _e('Save Settings', PRIMA_DOMAIN) ?>" />
			<input type="submit" class="button-highlighted" name="<?php echo PRIMA_OPTIONS; ?>[reset]" value="<?php _e('Reset Settings', PRIMA_DOMAIN); ?>" onclick="return prima_confirm('<?php echo esc_js( __('Are you sure you want to reset?', PRIMA_DOMAIN) ); ?>');" />
		</h2>
		<div class="metabox-holder clearfix">
			<div class="postbox-container-left">
			<div class="postbox-container">
				<?php do_meta_boxes($_prima_settings_pagehook, 'column1', null); ?>
			</div>
			</div>
			<div class="postbox-container-right">
			<div class="postbox-container">
				<?php do_meta_boxes($_prima_settings_pagehook, 'column2', null); ?>
			</div>
			</div>
		</div>
		<div class="bottom-buttons">
			<input type="submit" class="button-primary" value="<?php _e('Save Settings', PRIMA_DOMAIN) ?>" />
			<input type="submit" class="button-highlighted" name="<?php echo PRIMA_OPTIONS; ?>[reset]" value="<?php _e('Reset Settings', PRIMA_DOMAIN); ?>" />
		</div>
	</form>
	</div>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles('<?php echo $_prima_settings_pagehook; ?>');
		});
		//]]>
	</script>

<?php
}
function prima_import_export_admin() { ?>
	<div class="wrap prima-metaboxes">
		<?php screen_icon('options-prima'); ?>
		<h2><?php echo PRIMA_THEME_NAME.' - '.__('Import/Export', PRIMA_DOMAIN); ?></h2>
		<div class="metabox-holder">
			<div class="postbox-container" style="width: 49%;">
                <div class="meta-box-sortables ui-sortable" id="column1-sortables">
                <div class="postbox " id="prima-import-theme-settings">
                <div title="Click to toggle" class="handlediv"><br></div>
                <h3 class="hndle"><span><?php _e('Import Theme Settings', PRIMA_DOMAIN); ?></span></h3>
                <div class="inside">
                    <p>
                        <form enctype="multipart/form-data" method="post" action="<?php echo admin_url('admin.php?page=primathemes-import-export'); ?>">
                            <?php wp_nonce_field('prima-import'); ?>
                            <input type="hidden" name="prima-import" value="1" />
                            <label for="prima-import-upload"><?php sprintf( __('Upload File: (Maximum Size: %s)', PRIMA_DOMAIN), ini_get('post_max_size') ); ?></label>
                            <input type="file" id="prima-import-upload" name="prima-import-upload" size="25" />
                            <input type="submit" class="button" value="<?php _e('Upload and import', PRIMA_DOMAIN); ?>" />
                        </form>
                    </p>
                    <hr class="div2" style="clear:both"/>
                </div>
                </div>
                </div>
			</div>
			<div class="postbox-container" style="width: 49%; display: block;">
                <div class="meta-box-sortables ui-sortable" id="column2-sortables">
                <div class="postbox " id="prima-export-theme-settings">
                <div title="Click to toggle" class="handlediv"><br></div>
                <h3 class="hndle"><span><?php _e('Export Theme Settings', PRIMA_DOMAIN); ?></span></h3>
                <div class="inside">
                    <p>
                        <form method="post" action="<?php echo admin_url('admin.php?page=primathemes-import-export'); ?>">
                            <?php wp_nonce_field('prima-export'); ?>
                            <!--
                            <select name="prima-export">
                                <option value="theme">Theme Settings</option>
                            </select>
                            -->
                            <input type="hidden" name="prima-export" value="theme"/>
                            <input type="submit" class="button" value="<?php _e('Download Theme Settings File', PRIMA_DOMAIN); ?>" />
                        </form>
                    </p>
                    <hr class="div2" style="clear:both"/>
                </div>
                </div>
                </div>
			</div>
		</div>
	</div>
<?php 
}
add_action('admin_notices', 'prima_import_export_notices');
function prima_import_export_notices() {
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'primathemes-import-export' )
		return;
	if ( isset( $_REQUEST['imported'] ) && $_REQUEST['imported'] == 'true' ) {
		echo '<div id="message" class="updated"><p><strong>'.__('Settings successfully imported!', PRIMA_DOMAIN).'</strong></p></div>';
	}
	elseif ( isset($_REQUEST['error']) && $_REQUEST['error'] == 'true') {  
		echo '<div id="message" class="error"><p><strong>'.__('There was a problem importing your settings. Please Try again.', PRIMA_DOMAIN).'</strong></p></div>';
	}
}
add_action( 'admin_init', 'prima_export' );
function prima_export() {
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'primathemes-import-export' )
		return;
	if ( empty( $_REQUEST['prima-export'] ) )
		return;
	check_admin_referer('prima-export');
	do_action('prima_export', $_REQUEST['prima-export']);
	$settings = array();
	if ( $_REQUEST['prima-export'] === 'theme' ) {		
		$settings = array(
			PRIMA_OPTIONS => get_option( PRIMA_OPTIONS )
		);
		$prefix = PRIMA_THEME_NAME.'-settings';
	}
	if ( !$settings ) return;
    $output = json_encode( (array)$settings );
    header( 'Content-Description: File Transfer' );
    header( 'Cache-Control: public, must-revalidate' );
    header( 'Pragma: hack' );
    header( 'Content-Type: text/plain' );
    header( 'Content-Disposition: attachment; filename="' . $prefix . '-' . date("Ymd-His") . '.json"' );
    header( 'Content-Length: ' . strlen($output) );
    echo $output;
    exit;
}
add_action( 'admin_init', 'prima_import' );
function prima_import() {
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'primathemes-import-export' )
		return;
	if ( empty( $_REQUEST['prima-import'] ) )
		return;
	check_admin_referer('prima-import');
	$upload = file_get_contents($_FILES['prima-import-upload']['tmp_name']);
	$options = json_decode( $upload, true );
	if ( !$options || $_FILES['prima-import-upload']['error'] ) {
		wp_redirect( admin_url( 'admin.php?page=primathemes-import-export&error=true' ) );
		exit;
	}
	foreach ( (array)$options as $key => $settings ) {
		update_option( $key, $settings );
	}
	wp_redirect( admin_url( 'admin.php?page=primathemes-import-export&imported=true' ) );
	exit;
}
function prima_documentation_admin() { ?>
	<div id="prima-readme-file" class="wrap prima-metaboxes">
		<?php screen_icon('options-prima'); ?>
		<h2><?php echo PRIMA_THEME_NAME.' - '.__('Documentation', PRIMA_DOMAIN); ?></h2>
		<div class="metabox-holder">
			<div class="postbox-container">
            <div class="meta-box-sortables" id="column1-sortables">
            	<?php
                if ( file_exists( PRIMA_DOCS_DIR . '/documentation.pdf' ) ) { ?>
                <div class="postbox " id="prima-docs-theme-settings">
                <h3 class="hndle"><span><?php _e('PDF Documentation', PRIMA_DOMAIN); ?></span></h3>
                <div class="inside">
                	<p><?php _e('Download detailed PDF documentation file', PRIMA_DOMAIN); ?></p>
                	<p><a href="<?php echo PRIMA_DOCS_URL.'/documentation.pdf'; ?>">documentation.pdf</a></p>
                </div>
                </div>
                <?php } ?>
            	<?php
                $readme = false;
                $readme = @file_get_contents(PRIMA_DOCS_DIR . '/readme.txt');
                if ( $readme && !empty($readme) ) { ?>
                <div class="postbox " id="prima-readme-theme-settings">
                <h3 class="hndle"><span><?php _e('Readme', PRIMA_DOMAIN); ?></span></h3>
                <div class="inside">
					<?php echo make_clickable( wpautop( $readme ) ); ?>
                </div>
                </div>
                <?php } ?>
            	<?php
                $license = false;
                $license = @file_get_contents(PRIMA_DOCS_DIR . '/license.txt');
                if ( $license && !empty($license) ) { ?>
                <div class="postbox " id="prima-license-theme-settings">
                <h3 class="hndle"><span><?php _e('License', PRIMA_DOMAIN); ?></span></h3>
                <div class="inside">
					<?php echo make_clickable( wpautop( $license ) ); ?>
                </div>
                </div>
                <?php } ?>
            	<?php
                $changelog = false;
                $changelog = @file_get_contents(PRIMA_DOCS_DIR . '/changelog.txt');
                if ( $changelog && !empty($changelog) ) { ?>
                <div class="postbox " id="prima-changelog-theme-settings">
                <h3 class="hndle"><span><?php _e('Changelog', PRIMA_DOMAIN); ?></span></h3>
                <div class="inside">
					<?php echo make_clickable( wpautop( $changelog ) ); ?>
                </div>
                </div>
                <?php } ?>
            </div>
			</div>
        </div>
	</div>
<?php
}
add_action('wpsc_presentation_settings_page', 'prima_wpsc_settings_page');
function prima_wpsc_settings_page() {
	if ( !function_exists( 'gold_shpcrt_display_gallery' ) ) {
	?>
	<h3 class="form_group"><?php _e( 'Additional Settings', 'wpsc' ); ?></h3>
	<table class='wpsc_options form-table'>
		<tr class="gallery_image_width">
			<th scope="row">
				<?php _e( "Gallery Thumbnail Image Size", 'wpsc' ); ?>:
			</th>
			<td>
				<?php _e( 'Width', 'wpsc' ); ?>:<input type='text' size='6' name='wpsc_options[wpsc_gallery_image_width]' value='<?php esc_attr_e( get_option( 'wpsc_gallery_image_width' ) ); ?>' /> 
				<?php _e( 'Height', 'wpsc' ); ?>:<input type='text' size='6' name='wpsc_options[wpsc_gallery_image_height]' value='<?php esc_attr_e( get_option( 'wpsc_gallery_image_height' ) ); ?>' /><br />

			</td>
		</tr>
	</table>
	<?php
	}
}
add_action('admin_menu', 'prima_wpsc_settings_init');
function prima_wpsc_settings_init() {
	add_action('admin_footer-settings_page_wpsc-settings', 'prima_wpsc_settings_scripts');
}
function prima_wpsc_settings_scripts() {
?>
<style type="text/css">
/* .wpsc_options {display: none;} */
</style>
<script type="text/javascript">/* Custom Scripts */
jQuery(document).ready(function($){
	var refreshID = setInterval(function() {
		<?php if ( !function_exists( 'product_display_grid' ) ) { ?>
		$("#options_presentation tr#wpsc-grid-settings").hide();
		<?php } ?>
		$("#options_presentation tr").has('#wpsc_crop_thumbnails1, #show_thumbnails1, #show_thumbnails_thickbox1, #wpsc_lightbox_thickbox1').hide();
		var image_size = $("#options_presentation tr").has('#wpsc_lightbox_thickbox1');
		// $("#options_presentation tr.gallery_image_width").remove().insertAfter(image_size);
	}, 1000);
});
</script>
<?php
}

add_action('admin_notices', 'prima_wpsc_admin_notice');
function prima_wpsc_admin_notice(){
	if(!current_user_can('manage_options')) return;
	if(!class_exists('WP_eCommerce')) {
		echo '
			<div class="error">
				<p>'.sprintf( __("Please install %s plugin!", PRIMA_DOMAIN), '<a href="http://wordpress.org/extend/plugins/wp-e-commerce">WP e-Commerce</a>').'</p>
			</div>
		';
	}
	else {
		global $prima_productspage_id, $prima_shoppingcart_id, $prima_transactionresults_id, $prima_userlog_id;
		if(!$prima_productspage_id) {
			echo '
				<div class="error">
					<p>'.__('<b>There is no "Products Page" page with [productspage] shortcode</b>. We need this page to display your products', PRIMA_DOMAIN).'</p>
				</div>
			';
		}
		else {
			update_option( 'product_list_url', get_permalink($prima_productspage_id) );
			$status = get_post_status($prima_productspage_id);
			$title = get_post_field( 'post_title', $prima_productspage_id );
			if ( $status == 'private' ) {
				echo '
					<div class="error">
						<p>'.sprintf(__('Please change the post visibility of <b>%s</b> page from <b>private</b> to <b>public</b>', PRIMA_DOMAIN), $title).'</p>
					</div>
				';
			}
		}
		if(!$prima_shoppingcart_id) {
			echo '
				<div class="error">
					<p>'.__('<b>There is no "Checkout" page with [shoppingcart] shortcode</b>. We need this page to display checkout page', PRIMA_DOMAIN).'</p>
				</div>
			';
		}
		else {
			update_option( 'shopping_cart_url', get_permalink($prima_shoppingcart_id) );
			update_option( 'checkout_url', get_permalink($prima_shoppingcart_id) );
			$status = get_post_status($prima_shoppingcart_id);
			$title = get_post_field( 'post_title', $prima_shoppingcart_id );
			if ( $status == 'private' ) {
				echo '
					<div class="error">
						<p>'.sprintf(__('Please change the post visibility of <b>%s</b> page from <b>private</b> to <b>public</b>', PRIMA_DOMAIN), $title).'</p>
					</div>
				';
			}
		}
		if(!$prima_transactionresults_id) {
			echo '
				<div class="error">
					<p>'.__('<b>There is no "Transaction Results" page with [transactionresults] shortcode</b>. We need this page to display transaction result after checkout complete', PRIMA_DOMAIN).'</p>
				</div>
			';
		}
		else {
			update_option( 'transact_url', get_permalink($prima_transactionresults_id) );
			$status = get_post_status($prima_transactionresults_id);
			$title = get_post_field( 'post_title', $prima_transactionresults_id );
			if ( $status == 'private' ) {
				echo '
					<div class="error">
						<p>'.sprintf(__('Please change the post visibility of <b>%s</b> page from <b>private</b> to <b>public</b>', PRIMA_DOMAIN), $title).'</p>
					</div>
				';
			}
		}
		if(!$prima_userlog_id) {
			echo '
				<div class="error">
					<p>'.__('<b>There is no "Your Account" page with [userlog] shortcode</b>. We need this page to display your buyer purchase history, billing/contact details, and downloads page when they logged-in to your website.', PRIMA_DOMAIN).'</p>
				</div>
			';
		}
		else {
			update_option( 'user_account_url', get_permalink($prima_userlog_id) );
			$status = get_post_status($prima_userlog_id);
			$title = get_post_field( 'post_title', $prima_userlog_id );
			if ( $status == 'private' ) {
				echo '
					<div class="error">
						<p>'.sprintf(__('Please change the post visibility of <b>%s</b> page from <b>private</b> to <b>public</b>', PRIMA_DOMAIN), $title).'</p>
					</div>
				';
			}
		}
	}
}

<?php 
add_action('admin_notices', 'prima_childthemefiles_notices');
function prima_childthemefiles_notices() {
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'primathemes-childthemefiles' )
		return;
	if ( isset( $_SESSION['prima_themefile_copy_result'] ) ) {
		if(in_array(false, $_SESSION['prima_themefile_copy_result'], true)) {
			echo '<div id="message" class="error"><p><strong>'.__('Error: the file could not be copied. Please make sure that your child theme directory is writable.', PRIMA_DOMAIN).'</strong></p></div>';
		}
		else {
			echo '<div id="message" class="updated"><p><strong>'.__('The file has been copied to your child theme directory.', PRIMA_DOMAIN).'</strong></p></div>';
		}
		unset($_SESSION['prima_themefile_copy_result']);
	}
	if ( isset( $_SESSION['prima_themefile_delete_result'] ) ) {
		if(in_array(false, $_SESSION['prima_themefile_delete_result'], true)) {
			echo '<div id="message" class="error"><p><strong>'.__('Error: the file could not be deleted. Please make sure that your child theme directory is writable.', PRIMA_DOMAIN).'</strong></p></div>';
		}
		else {
			echo '<div id="message" class="updated"><p><strong>'.__('The file has been deleted from your child theme directory.', PRIMA_DOMAIN).'</strong></p></div>';
		}
		unset($_SESSION['prima_themefile_delete_result']);
	}
	if ( !@is_writable( CHILD_DIR ) )
		echo '<div id="message" class="error"><p><strong>'.__('Your child theme directory is not writable. Please change your child theme folder permission!!!', PRIMA_DOMAIN).'</strong></p></div>';
}
function prima_childthemefiles_admin() {
$themefiles = prima_get_files( PARENT_DIR, 'php', '', false );
$themecss = prima_get_files( PARENT_DIR, 'css', '', false );
$themefiles = array_merge($themefiles,$themecss);
?>
	<div id="prima-childtheme-files" class="wrap prima-metaboxes">
		<?php screen_icon('options-prima'); ?>
		<h2><?php echo PRIMA_THEME_NAME.' - '.__('Child Theme Files', PRIMA_DOMAIN); ?></h2>
		<div class="metabox-holder clearfix">
			<div class="postbox-container" style="width:99%">
            <div class="meta-box-sortables" id="column1-sortables">
                <div class="postbox " id="prima-childthemefiles-settings">
                <h3 class="hndle"><span><?php _e('Files Management', PRIMA_DOMAIN); ?></span></h3>
                <div class="inside">
					<p><strong><?php _e('IMPORTANT!!!', PRIMA_DOMAIN); ?></strong></p>
					<ul>
					<li>- 
					<?php _e('You can edit a file from your Theme Editor page when this file exists in your child theme directory!', PRIMA_DOMAIN); ?>
					</li>
					<li>- 
					<?php _e('After copy or delete a file, please go to Settings - Store - Presentations and click "Flush Theme Cache" button!', PRIMA_DOMAIN); ?>
					</li>
					</ul>
					<br/><br/>
					<?php foreach ( $themefiles as $themefile ) { if ( $themefile != 'functions.php' && $themefile != 'style.css' ) { ?>
						<strong><?php echo $themefile ?></strong>
						<?php
						if ( file_exists( CHILD_DIR.'/'.$themefile ) ) {
							echo '<span style="color:blue">';
							_e('File exists in your child theme directory.', PRIMA_DOMAIN); 
							echo '</span>';
							echo '<form method="post" action="'.admin_url('admin.php?page=primathemes-childthemefiles').'">';
								wp_nonce_field('prima-childthemefiles-delete');
								echo '<input type="hidden" name="prima-themefile-delete" value="'.$themefile.'"/>';
								echo '<input type="submit" class="button button-primary" value="'.__('Delete this file from your child theme directory', PRIMA_DOMAIN).'" />';
							echo '</form>';
							echo '<br/><br/>';
						}
						else {
							echo '<span style="color:red">';
							_e("File doesn't exist in your child theme directory.", PRIMA_DOMAIN); 
							echo '</span>';
							echo '<form method="post" action="'.admin_url('admin.php?page=primathemes-childthemefiles').'">';
								wp_nonce_field('prima-childthemefiles-copy');
								echo '<input type="hidden" name="prima-themefile-copy" value="'.$themefile.'"/>';
								echo '<input type="submit" class="button" value="'.__('Copy this file to your child theme directory', PRIMA_DOMAIN).'" />';
							echo '</form>';
							echo '<br/><br/>';
						}
						?>
					<?php }} ?>
                </div>
                </div>
            </div>
			</div>
        </div>
	</div>
<?php
}
add_action( 'admin_init', 'prima_childthemefiles_copy' );
function prima_childthemefiles_copy() {
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'primathemes-childthemefiles' )
		return;
	if ( empty( $_REQUEST['prima-themefile-copy'] ) )
		return;
	check_admin_referer('prima-childthemefiles-copy');
	$themefile = $_REQUEST['prima-themefile-copy'];
	$_SESSION['prima_themefile_copy_result'][] = @copy( PARENT_DIR . '/' . $themefile, CHILD_DIR . '/' . $themefile );
}
add_action( 'admin_init', 'prima_childthemefiles_delete' );
function prima_childthemefiles_delete() {
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'primathemes-childthemefiles' )
		return;
	if ( empty( $_REQUEST['prima-themefile-delete'] ) )
		return;
	check_admin_referer('prima-childthemefiles-delete');
	$themefile = $_REQUEST['prima-themefile-delete'];
	$_SESSION['prima_themefile_delete_result'][] = @unlink( CHILD_DIR . '/' . $themefile );
}
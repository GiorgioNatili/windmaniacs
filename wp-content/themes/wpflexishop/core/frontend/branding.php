<?php
add_action( 'wp_head', 'prima_head_favicon');
function prima_head_favicon() {
	$icon = prima_get_option( 'favicon' );
	if ( !$icon ) return;
	echo '<link rel="shortcut icon" type="image/x-icon" href="'.$icon.'" />' . "\n";
}
add_action( 'login_head', 'prima_login_logo' );
function prima_login_logo() {
	$icon = prima_get_option( "loginlogo" );
	if ( !$icon ) return;
	echo '
	<style type="text/css">
	#login h1 a { background-image: url('.$icon.') !important; }
	</style>
	';
}

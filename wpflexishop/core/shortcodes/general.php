<?php
add_action( 'init', 'prima_add_general_shortcodes' );
function prima_add_general_shortcodes() {
	if ( !is_admin() ) {
		add_shortcode( 'year', 'prima_general_shortcode' );
		add_shortcode( 'date', 'prima_general_shortcode' );
		add_shortcode( 'search_form', 'prima_general_shortcode' );
		add_shortcode( 'feedburner_form', 'prima_general_shortcode' );
		add_shortcode( 'twitter', 'prima_general_shortcode' );
	}
}
function prima_general_shortcode($attr, $content=null, $code=""){
	switch($code){
		case 'year':
			return date( __( 'Y', PRIMA_DOMAIN ) );
		break;
		case 'date':
			$attr = shortcode_atts( array( 'format' => __( 'l, F j, Y', PRIMA_DOMAIN ) ), $attr );
			return date( $attr['format'] );
		break;
		case 'search_form':
			$attr = shortcode_atts( array( 'search_text' => __('Search this website&hellip;', PRIMA_DOMAIN), 'button_text' => __( 'Search', PRIMA_DOMAIN ) ), $attr );
			$attr['echo'] = false;
			return prima_search_form( $attr );
		break;
		case 'feedburner_form':
			$attr = shortcode_atts( array( 'id' => '', 'language' => __( 'en_US', PRIMA_DOMAIN ), 'input_text' => __( 'Enter your email address...', PRIMA_DOMAIN ), 'button_text' => __( 'Subscribe', PRIMA_DOMAIN ) ), $attr );
			$attr['echo'] = false;
			return prima_feedburner_form( $attr );
		break;
		case 'twitter':
			$attr = shortcode_atts( array( 'title' => '', 'usernames' => 'primathemes', 'limit' => 3 ), $attr );
			$out = '';
			if($attr['title']) $out .= '<h3 class="widget-title">'.$attr['title'].'</h3>';
			$out .= prima_get_twitter( $attr );
			return $out;
		break;
	}
}
/*
Based on RawR (Raw Revisited) for WordPress plugin
Credits:  Derek Simkowiak http://derek.simkowiak.net/
*/
class PrimaCode {
	function __construct() {
		if ( !function_exists('add_shortcode') ) return;
		$this->unformatted_shortcode_blocks = array();
		add_filter( 'the_content', array(&$this, 'get_unformatted_shortcode_blocks'), 8 );
		add_shortcode( 'prima_code', array(&$this, 'my_shortcode_handler2') );
	}
	function get_unformatted_shortcode_blocks( $content ) {
		global $shortcode_tags;
		$orig_shortcode_tags = $shortcode_tags;
		remove_all_shortcodes();
		add_shortcode( 'prima_code', array(&$this, 'my_shortcode_handler1') );
		$content = do_shortcode( $content );
		$shortcode_tags = $orig_shortcode_tags;
		return $content;
	}
	function my_shortcode_handler1( $atts, $content=null, $code="" ) {
		$this->unformatted_shortcode_blocks[] = $content;
		$content_index = count( $this->unformatted_shortcode_blocks ) - 1;
		return "[prima_code]" . $content_index . "[/prima_code]";
	}
	function my_shortcode_handler2( $atts, $content=null, $code="" ) {
		return '<pre><code>'.$this->unformatted_shortcode_blocks[ $content ].'</code></pre>';
	}
}
global $prima_code; 
$prima_code = new PrimaCode();

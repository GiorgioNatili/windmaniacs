<?php 
add_action('prima_styles', 'prima_font_styles');
function prima_font_styles() {
	$fonts = array_merge(prima_webfonts(), prima_customfonts(), prima_googlefonts());

	$bodyfont = prima_get_option('bodyfont'); 
	if ( $bodyfont ) {
		$fonts[$bodyfont]['font-family'] = str_replace('&quot;', '"', $fonts[$bodyfont]['font-family']);
		echo 'body { font-family: '.$fonts[$bodyfont]['font-family'].'; } '; 
	}

	$headerfont = prima_get_option('headerfont'); 
	if ( $headerfont ) {
		$fonts[$headerfont]['font-family'] = str_replace('&quot;', '"',$fonts[$headerfont]['font-family']);
		echo 'h1, h2, h3, #brief p, .prima-multislider-title { font-family: '.$fonts[$headerfont]['font-family'].'; } ';
	}

	$addifont = prima_get_option('additionalfont'); 
	if ( $addifont ) {
		$fonts[$addifont]['font-family'] = str_replace('&quot;', '"',$fonts[$addifont]['font-family']);
		echo 'blockquote, p.product-description, p.post-excerpt, .post-excerpt p, p.category-description, p.twitter-message, div#main-col ul.testimonials li blockquote, div#sidebar ul.testimonials li blockquote, div#footer ul.testimonials li blockquote, li.promotion div.promotion-text div.promotion-excerpt p { font-family: '.$fonts[$addifont]['font-family'].'; } ';
	}
}
add_action('wp_head', 'prima_googlefont_script');
function prima_googlefont_script() {
	$fonts = array_merge(prima_webfonts(), prima_customfonts(), prima_googlefonts());

	$bodyfont = prima_get_option('bodyfont'); 
	if ( $bodyfont && $fonts[$bodyfont]['type'] == 'google' ) {
		echo "\n<!-- Google Webfonts For Body -->\n";
		echo '<link href="http://fonts.googleapis.com/css?family=' . str_replace(" ","+",$fonts[$bodyfont]['family']) .'" rel="stylesheet" type="text/css" />'."\n\n";
	}
	elseif ( $bodyfont && $fonts[$bodyfont]['type'] == 'custom' ) {
		echo "\n<!-- Custom Fonts For Body -->\n";
		echo '<link href="'.CHILD_URL.'/fonts/'.$fonts[$bodyfont]['css'].'" rel="stylesheet" type="text/css" />'."\n\n";
	}

	$headerfont = prima_get_option('headerfont'); 
	if ( $headerfont && $fonts[$headerfont]['type'] == 'google' ) {
		echo "\n<!-- Google Webfonts For Header -->\n";
		echo '<link href="http://fonts.googleapis.com/css?family=' . str_replace(" ","+",$fonts[$headerfont]['family']) .'" rel="stylesheet" type="text/css" />'."\n\n";
	}
	elseif ( $headerfont && $fonts[$headerfont]['type'] == 'custom' ) {
		echo "\n<!-- Custom Fonts For Header -->\n";
		echo '<link href="'.CHILD_URL.'/fonts/'.$fonts[$headerfont]['css'].'" rel="stylesheet" type="text/css" />'."\n\n";
	}

	$addifont = prima_get_option('additionalfont'); 
	if ( $addifont && $fonts[$addifont]['type'] == 'google' ) {
		echo "\n<!-- Additional Google Webfonts -->\n";
		echo '<link href="http://fonts.googleapis.com/css?family=' . str_replace(" ","+",$fonts[$addifont]['family']) .'" rel="stylesheet" type="text/css" />'."\n\n";
	}
	elseif ( $addifont && $fonts[$addifont]['type'] == 'custom' ) {
		echo "\n<!-- Additional Custom Fonts  -->\n";
		echo '<link href="'.CHILD_URL.'/fonts/'.$fonts[$addifont]['css'].'" rel="stylesheet" type="text/css" />'."\n\n";
	}
}
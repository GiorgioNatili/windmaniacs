<?php 
$options = get_option(PRIMA_OPTIONS);
$output = '';
/* background colours */
if( $options['bodysetting'] == "color") :
	$output .= 'body#full {';
		$output .= 'background-image:none !important;';
		if ( $options['backgroundcol'] && $options['backgroundcol'] != '#' ) $output .= 'background-color:'.$options['backgroundcol'].' !important; ';
		if ( $options['paracolor'] && $options['paracolor'] != '#' ) $output .= 'color:'.$options['paracolor'].' !important; ';
	$output .= '}';
elseif( $options['bodysetting'] == "image") :
	$output .= 'body#full {';
		if ( $options['backgroundcol'] && $options['backgroundcol'] != '#' ) $output .= 'background-color:'.$options['backgroundcol'].' !important; ';
		if ( $options['paracolor'] && $options['paracolor'] != '#' ) $output .= 'color:'.$options['paracolor'].' !important; ';
		if( $options['bg_image'] ) $output .= 'background-image: url('.$options['bg_image'].') !important;';
		if( $options['bg_repeat'] ) $output .= 'background-repeat: '.$options['bg_repeat'].' !important;';
		if( $options['bg_position'] ) $output .= 'background-position: '.$options['bg_position'].' !important;';
		if( $options['bg_attachment'] ) $output .= 'background-attachment: '.$options['bg_attachment'].' !important;';
	$output .= '}';
endif;
if( $options['headercol'] && $options['headercol'] != '#' ) {
	$output .= '#header { background-color:'.$options['headercol'].' !important; }';
	$output .= 'body#full.page-template-frontpage-php #header, body#full.page-template-frontpage-php #leader,
	body#full.page-template-slideshow-php #header, body#full.page-template-slideshow-php #leader {
		background:none !important;
	}';
	$output .= 'body#full.page-template-frontpage-php #header-wrapper,
	body#full.page-template-slideshow-php #header-wrapper {
		background-color:'.$options['headercol'].' !important;
	}';
}
if ( $options['sliderbackground'] == "image" ) :
	if ( ($options['sliderbackcol'] && $options['sliderbackcol'] != '#') || $options['newleaderimage'] ) :
		$output .= 'body#full.page-template-frontpage-php #header, body#full.page-template-frontpage-php #leader,
		body#full.page-template-slideshow-php #header, body#full.page-template-slideshow-php #leader {
			background:none !important;
		}';
		$output .= 'body.page-template-frontpage-php #header-wrapper,
		body.page-template-slideshow-php #header-wrapper {';
			if( $options['newleaderimage'] ) $output .= 'background-image: url('.$options['newleaderimage'].') !important;';
			if( $options['sliderbackrepeat'] ) $output .= 'background-repeat: '.$options['sliderbackrepeat'].' !important;';
			if( $options['sliderbackpos'] ) $output .= 'background-position: '.$options['sliderbackpos'].' bottom !important;';
			if( $options['sliderbackcol'] && $options['sliderbackcol'] != '#' ) $output .= 'background-color: '.$options['sliderbackcol'].' !important;';
		$output .= '}';
	endif;
elseif ( $options['sliderbackground'] == "color" ) : 
	if ( $options['sliderbackcol'] && $options['sliderbackcol'] != '#' ) :
		$output .= 'body#full.page-template-frontpage-php #header, body#full.page-template-frontpage-php #leader,
		body#full.page-template-slideshow-php #header, body#full.page-template-slideshow-php #leader {
			background:none !important;
		}';
		$output .= 'body#full.page-template-frontpage-php #header-wrapper,
		body#full.page-template-slideshow-php #header-wrapper {
			background-image:url('.get_bloginfo('template_url').'/images/radial-gradient.png) center center !important;
			background-repeat:no-repeat !important;
			background-position:center bottom !important;
			background-color:'.$options['sliderbackcol'].' !important;
		}';
	endif;
else :
	$output .= 'body#full.page-template-frontpage-php #header, body#full.page-template-frontpage-php #leader,
	body#full.page-template-slideshow-php #header, body#full.page-template-slideshow-php #leader {
		background:none !important;
	}';
endif;
if( $options['footercol'] && $options['footercol'] != '#' ) {
	$output .= '#footer { background-color:'.$options['footercol'].' !important; }';
}
if( $options['copyrightcol'] && $options['copyrightcol'] != '#' ) {
	$output .= '#full #copyright { background-color:'.$options['copyrightcol'].' !important; }';
}
/* font colours */
if( $options['headingcol'] && $options['headercol'] != '#' ) {
	$output .= '#full h1, #full h2, #full h3, #full h4, #full h1 a, #full h2 a, #full h3 a, #full table.logdisplay strong, #full table.logdisplay tr.toprow td, #full table.logdisplay tr.toprow2 td, table.customer_details td:first-child, #checkout_page_container table.checkout_cart tr.header th { 
		color:'.$options['headingcol'].' !important; 
	}';
}
if( $options['paracolor'] && $options['paracolor'] != '#' ) {
	$output .= '#full p { color:'.$options['paracolor'].' !important; }';
	$output .= '#full div.blog-overview div.post-meta {
		border-top:1px dotted '.$options['paracolor'].' !important;
		border-bottom:1px dotted '.$options['paracolor'].' !important;
	}';
	$output .= '#full div.wpsc_page_numbers {
		border-top:1px dotted '.$options['paracolor'].' !important;
	}';
}
if( $options['linkcol'] && $options['linkcol'] != '#' ) {
	$output .= '#full a { color:'.$options['linkcol'].' !important; }';
}
if( $options['headerlinkcol'] && $options['headerlinkcol'] != '#' ) {
	$output .= '#full #header a, #full #user-nav a { color:'.$options['headerlinkcol'].' !important; }';
}
if( $options['footerheadercol'] && $options['footerheadercol'] != '#' ) {
	$output .= '#full #footer-top h3.widget-title, #full #footer-top h3.widget-title a { color:'.$options['footerheadercol'].' !important; }';
}
if( $options['footerfontcol'] && $options['footerfontcol'] != '#' ) {
	$output .= '#full #footer-top, #full #footer-top p { color:'.$options['footerfontcol'].' !important; }';
}
if( $options['footerlinkcol'] && $options['footerlinkcol'] != '#' ) {
	$output .= '#full #footer-top a, #full #footer-top a:hover { color:'.$options['footerlinkcol'].' !important; }';
}
if( $options['footerbottomheadercol'] && $options['footerbottomheadercol'] != '#' ) {
	$output .= '#full #footer-bottom h3, #full #footer-bottom h3 a { color:'.$options['footerbottomheadercol'].' !important; }';
}
if( $options['footerbottomfontcol'] && $options['footerbottomfontcol'] != '#' ) {
	$output .= '#full #footer-bottom, #full #footer-bottom p { color:'.$options['footerbottomfontcol'].' !important; }';
}
if( $options['footerbottomlinkcol'] && $options['footerbottomlinkcol'] != '#' ) {
	$output .= '#full #footer-bottom a, #full #footer-bottom a:hover { color:'.$options['footerbottomlinkcol'].' !important; }';
}
if( $options['copyrightfontcol'] && $options['copyrightfontcol'] != '#' ) {
	$output .= '#full #copyright p{ color:'.$options['copyrightfontcol'].' !important; }';
}
$output = prima_minify_css($output);
if( is_ssl() ) $output = str_replace('http://', 'https://', $output);
echo $output;
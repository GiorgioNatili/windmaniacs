<?php

add_filter('widget_text', 'do_shortcode');
add_shortcode('column', 'prima_typography_shortcodes');
add_shortcode('twocol_one', 'prima_typography_shortcodes');
add_shortcode('twocol_one_last', 'prima_typography_shortcodes');
add_shortcode('threecol_one', 'prima_typography_shortcodes');
add_shortcode('threecol_one_last', 'prima_typography_shortcodes');
add_shortcode('threecol_two', 'prima_typography_shortcodes');
add_shortcode('threecol_two_last', 'prima_typography_shortcodes');
add_shortcode('fourcol_one', 'prima_typography_shortcodes');
add_shortcode('fourcol_one_last', 'prima_typography_shortcodes');
add_shortcode('fourcol_two', 'prima_typography_shortcodes');
add_shortcode('fourcol_two_last', 'prima_typography_shortcodes');
add_shortcode('fourcol_three', 'prima_typography_shortcodes');
add_shortcode('fourcol_three_last', 'prima_typography_shortcodes');
add_shortcode('fivecol_one', 'prima_typography_shortcodes');
add_shortcode('fivecol_one_last', 'prima_typography_shortcodes');
add_shortcode('fivecol_two', 'prima_typography_shortcodes');
add_shortcode('fivecol_two_last', 'prima_typography_shortcodes');
add_shortcode('fivecol_three', 'prima_typography_shortcodes');
add_shortcode('fivecol_three_last', 'prima_typography_shortcodes');
add_shortcode('fivecol_four', 'prima_typography_shortcodes');
add_shortcode('fivecol_four_last', 'prima_typography_shortcodes');
add_shortcode('sixcol_one', 'prima_typography_shortcodes');
add_shortcode('sixcol_one_last', 'prima_typography_shortcodes');
add_shortcode('sixcol_two', 'prima_typography_shortcodes');
add_shortcode('sixcol_two_last', 'prima_typography_shortcodes');
add_shortcode('sixcol_three', 'prima_typography_shortcodes');
add_shortcode('sixcol_three_last', 'prima_typography_shortcodes');
add_shortcode('sixcol_four', 'prima_typography_shortcodes');
add_shortcode('sixcol_four_last', 'prima_typography_shortcodes');
add_shortcode('sixcol_five', 'prima_typography_shortcodes');
add_shortcode('sixcol_five_last', 'prima_typography_shortcodes');
add_shortcode('hr', 'prima_typography_shortcodes');
add_shortcode('divider', 'prima_typography_shortcodes');
add_shortcode('divider_flat', 'prima_typography_shortcodes');
add_shortcode('dropcap', 'prima_typography_shortcodes' );
add_shortcode('highlight', 'prima_typography_shortcodes' );
add_shortcode('unordered_list', 'prima_typography_shortcodes' );
add_shortcode('ordered_list', 'prima_typography_shortcodes' );
add_shortcode('tagline', 'prima_typography_shortcodes' );
add_shortcode('quote', 'prima_typography_shortcodes');
add_shortcode('box', 'prima_typography_shortcodes');
add_shortcode('button', 'prima_typography_shortcodes');
add_shortcode('tabs', 'prima_typography_shortcodes', 15 );
add_shortcode('tab', 'prima_typography_shortcodes', 20 );
add_shortcode('toggle', 'prima_typography_shortcodes');
add_shortcode('code', 'prima_typography_shortcodes');

function prima_typography_shortcodes($atts, $content=null, $code=""){
	switch($code){
		case 'column':
			extract(shortcode_atts(array( 'left' => '', 'right' => '' ), $atts));  
			$paddingleft = $left ? 'padding-left:'.$left.'px;' : '';  
			$paddingright = $right ? 'padding-right:'.$right.'px;' : '';  
			$style = ($paddingleft || $paddingright) ? ' style="'.$paddingleft.$paddingright.'"' : '';  
			return '<div class="ps-column" '.$style.'>' . prima_clean_shortcode($content) . '<div class="ps-divider flat"></div></div>';
		break;
		case 'twocol_one':
			return '<div class="twocol-one">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'twocol_one_last':
			return '<div class="twocol-one last">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'threecol_one':
			return '<div class="threecol-one">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'threecol_one_last':
			return '<div class="threecol-one last">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'threecol_two':
			return '<div class="threecol-two">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'threecol_two_last':
			return '<div class="threecol-two last">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'fourcol_one':
			return '<div class="fourcol-one">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'fourcol_one_last':
			return '<div class="fourcol-one last">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'fourcol_two':
			return '<div class="fourcol-two">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'fourcol_two_last':
			return '<div class="fourcol-two last">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'fourcol_three':
			return '<div class="fourcol-three">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'fourcol_three_last':
			return '<div class="fourcol-three last">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'fivecol_one':
			return '<div class="fivecol-one">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'fivecol_one_last':
			return '<div class="fivecol-one last">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'fivecol_two':
			return '<div class="fivecol-two">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'fivecol_two_last':
			return '<div class="fivecol-two last">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'fivecol_three':
			return '<div class="fivecol-three">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'fivecol_three_last':
			return '<div class="fivecol-three last">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'fivecol_four':
			return '<div class="fivecol-four">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'fivecol_four_last':
			return '<div class="fivecol-four last">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'sixcol_one':
			return '<div class="sixcol-one">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'sixcol_one_last':
			return '<div class="sixcol-one last">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'sixcol_two':
			return '<div class="sixcol-two">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'sixcol_two_last':
			return '<div class="sixcol-two last">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'sixcol_three':
			return '<div class="sixcol-three">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'sixcol_three_last':
			return '<div class="sixcol-three last">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'sixcol_four':
			return '<div class="sixcol-four">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'sixcol_four_last':
			return '<div class="sixcol-four last">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'sixcol_five':
			return '<div class="sixcol-five">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'sixcol_five_last':
			return '<div class="sixcol-five last">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'hr':
			extract(shortcode_atts(array( 'left' => '', 'right' => '', 'top' => '', 'bottom' => '', 'width' => '' ), $atts));  
			$marginleft = $left ? 'margin-left:'.$left.'px;' : '';  
			$marginright = $right ? 'margin-right:'.$right.'px;' : '';  
			$paddingtop = $top ? 'padding-top:'.$top.'px;' : '';  
			$marginbottom = $bottom ? 'margin-bottom:'.$bottom.'px;' : '';  
			$cwidth = $width ? 'width:'.$width.'px;' : '';  
			$style = $marginleft.$marginright.$paddingtop.$marginbottom.$cwidth;  
			$style = $style ? ' style="'.$style.'"' : '';  
			return '<div class="ps-hr" '.$style.'></div>';
		break;
		case 'divider':
			return '<div class="ps-divider"></div>';
		break;
		case 'divider_flat':
		    return '<div class="ps-divider flat"></div>';
		break;
		case 'dropcap':
			return '<span class="ps-dropcap">' . $content . '</span>';
		break;
		case 'highlight':
			return '<span class="ps-highlight">' . $content . '</span>';
		break;
		case 'tagline':
			extract(shortcode_atts(array( 'align' => '', 'left' => '', 'right' => '', 'fontsize' => '', 'fontfamily' => '', 'color' => '' ), $atts));  
			$align = $align ? 'align-'.$align : '';  
			$paddingleft = $left ? 'padding-left:'.$left.'px;' : '';  
			$paddingright = $right ? 'padding-right:'.$right.'px;' : '';  
			$fontsize = $fontsize ? 'font-size:'.$fontsize.'px;' : '';  
			$fontfamily = $fontfamily ? 'font-family:'.$fontfamily.';' : '';  
			$color = $color ? 'color:'.$color.';' : '';  
			$style = ($paddingleft || $paddingright || $fontsize || $fontfamily || $color) ? ' style="'.$paddingleft.$paddingright.$fontsize.$fontfamily.$color.'"' : '';  
			return '<h3 class="ps-tag-line ' . $align . '" '.$style.'>' . prima_clean_shortcode($content) . '</h3>';
		break;
		case 'unordered_list':
			extract(shortcode_atts(array( 'style' => 'default' ), $atts));  
			return '<div class="ps-unorderedlist ' . $style . '">' . prima_clean_shortcode($content) . '</div>' . "\n";
		break;
		case 'ordered_list':
			extract(shortcode_atts(array( 'style' => 'default' ), $atts));  
			return '<div class="ps-orderedlist ' . $style . '">' . prima_clean_shortcode($content) . '</div>' . "\n";
		break;
		case 'quote':
			extract(shortcode_atts(array( 'style' => '', 'float' => ''), $atts));
			$class = '';
			if ( $style ) $class .= ' '.$style;
			if ( $float ) $class .= ' '.$float;
			return '<div class="ps-quote' . $class . '"><p>' . prima_clean_shortcode($content) . '</p></div>';
		break;
		case 'box':
			extract(shortcode_atts(array( 'color' => '', 'style' => '', 'float' => '', 'border' => '', 'icon' => ''), $atts)); 
			$custom = '';								
			// if ( $icon ) $custom = ' style="padding-left:50px;background-image:url('.PRIMA_SHORTCODES_URL.'/images/'.$icon.'.png); background-repeat:no-repeat; background-position:20px 45%;"';
			$icon = ( $icon ) ? 'with-icon icon-'.$icon : '';
			return '<div class="ps-box '.$color.' '.$style.' '.$float.' '.$border.' '.$icon.'"'.$custom.'>' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'button':
			extract(shortcode_atts(array( 'size' => '', 'icon' => '', 'color' => '', 'class' => '', 'link' => '#', 'window' => ''), $atts));
			$custom = '';								
			// if ( $icon ) $custom = ' style="background:url('.PRIMA_SHORTCODES_URL.'/images/'.$icon.'.png) no-repeat left center; padding-left:25px; display:block;"';
			$icon = ( $icon ) ? 'class="with-icon icon-'.$icon.'"' : '';
			if ( $window ) $window = 'target="_blank" ';
			$output = '<a '.$window.' href="'.$link.'"class="ps-button '.$size.' '.$color.' '.$class.'"><span '.$icon.' '.$custom.'>' . prima_clean_shortcode($content) . '</span></a>';
			return $output;
		break;
		case 'tab':
			extract(shortcode_atts(array( 'title' => 'Tab' ), $atts ));		
			$tabid = 'tab-' . sanitize_title( $title );
			return '<div id="'.$tabid.'" class="ps-tab_content">' . prima_clean_shortcode($content) . '</div>';
		break;
		case 'tabs':
			extract( shortcode_atts( array( 'style' => 'default' ), $atts ) );
			// Extract the tab titles for use in the tabber widget.
			preg_match_all( '/tab title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );
			$tab_titles = array();
			$tabs_class = 'tab_titles';
			if ( isset( $matches[1] ) ) { $tab_titles = $matches[1]; } // End IF Statement
			$titles_html = '';
			if ( count( $tab_titles ) ) {
				$titles_html .= '<ul class="ps-tabs">' . "\n";
				foreach ( $tab_titles as $t ) {
					$tabid = ' #tab-' . sanitize_title( $t[0] );
					$titles_html .= '<li><a href="'.$tabid.'">' . $t[0] . '</a></li>' . "\n";
				}
				$titles_html .= '</ul>' . "\n";
			}
			return '<div id="tabs-' . rand(1, 100) . '" class="' . $style . '">' . $titles_html . '<div class="ps-tab_container">'. prima_clean_shortcode($content) . '</div>' . "\n" . '<div class="ps-divider "></div>' . "\n" . '</div><!--/.tabs-->';
		break;
		case 'toggle':
			extract(shortcode_atts(array( 'title' => 'Click here to show the content' ), $atts ));
			return '<div class="ps-toggle-container"><div class="ps-toggle-trigger"><a href="#">'.$title.'</a></div><div class="ps-toggle-content">' . prima_clean_shortcode($content) . '</div></div>';
		break;
		case 'code':
			extract(shortcode_atts(array(), $atts ));
			return '<pre><code>' . prima_clean_shortcode($content) . '</code></pre>';
		break;
	}
}
function prima_clean_shortcode($content) { 
	$content = str_replace(array("]<br />","]\n<br />"), ']', $content);
	$content = str_replace(array("<br />[","<br />\n["), '[', $content);
	$content = str_replace(array("]</p>","]\n</p>"), ']', $content);
	$content = str_replace(array("<p>[","</p>\n["), '[', $content);
	$content = preg_replace('/^<\/p>/', '', $content);
	$content = preg_replace('/<p>$/', '', $content);
	$content = do_shortcode( $content ); 
	return $content;
}
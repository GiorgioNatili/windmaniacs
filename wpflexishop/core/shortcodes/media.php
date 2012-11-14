<?php
add_shortcode('prima_vimeo', 'prima_vimeo_shortcode');
function prima_vimeo_shortcode( $atts ) {
	extract(shortcode_atts(array(
		'id' 	=> '30153918',
		'width' 	=> '700',
		'height' 	=> '394',
		'autoplay' 	=> false,
		'loop' 		=> false,
		'portrait' 	=> false,
		'title' 	=> false,
		'byline' 	=> false,
	), $atts));
	if ( $autoplay ) $autoplay = '&amp;autoplay=1'; 
	if ( $loop ) $loop = '&amp;loop=1'; 
	if ( !$portrait ) $portrait = '&amp;portrait=0'; 
	if ( !$title ) $title = '&amp;title=0'; 
	if ( !$byline ) $byline = '&amp;byline=0'; 
	$width_wrapper = $width < 980 ? $width : 980;
	$content = '<div class="video-wrapper" style="width:'.$width_wrapper.'px;"><div class="video-container"><iframe src="http://player.vimeo.com/video/'.$id.'?wmode=opaque'.$autoplay.$loop.$portrait.$title.$byline.'" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe></div></div>';
	return $content;
}
/*-----------------------------------------------------------------------------------*/
/* Youtube
/*-----------------------------------------------------------------------------------*/
add_shortcode('prima_youtube', 'prima_youtube_shortcode');
function prima_youtube_shortcode( $atts ) {
	extract(shortcode_atts(array(
		'id' 	=> 'chTkQgQKotA',
		'width' 	=> '700',
		'height' 	=> '386',
	), $atts));
	$width_wrapper = $width < 980 ? $width : 980;
	$content = '<div class="video-wrapper" style="width:'.$width_wrapper.'px;"><div class="video-container"><iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$id.'?wmode=opaque&amp;rel=0" frameborder="0" allowfullscreen></iframe></div></div>';
	return $content;
}

add_shortcode('prima_audio', 'prima_audio_shortcodes');
function prima_audio_shortcodes($atts, $content=null){
	global $prima_shortcodes_scripts, $prima_shortcodes_js;
	if ( !is_array($prima_shortcodes_js) ) $prima_shortcodes_js = array();
	extract(shortcode_atts(array( 
		'width' => 640,
		'mp3' => null,
		'ogg' => null
	), $atts));  
	$box_id = rand(1000, 9999);
	$output = '';
	if ( $mp3 || $ogg ) {
		$output .= '<div id="jquery_jplayer_'.$box_id.'" class="jp-jplayer"></div>';
        $output .= '<div class="jp-audio-container">';
        $output .= '<div class="jp-audio" style="width:'.$width.'px;">';
        $output .= '<div class="jp-type-single">';
        $output .= '<div id="jp_interface_'.$box_id.'" class="jp-interface">';
        $output .= '<ul class="jp-controls">';
        $output .= '<li><div class="seperator-first"></div></li>';
        $output .= '<li><div class="seperator-second"></div></li>';
        $output .= '<li><a href="#" class="jp-play" tabindex="1">play</a></li>';
        $output .= '<li><a href="#" class="jp-pause" tabindex="1">pause</a></li>';
        $output .= '<li><a href="#" class="jp-mute" tabindex="1" style="left:'.($width-70).'px;">mute</a></li>';
        $output .= '<li><a href="#" class="jp-unmute" tabindex="1" style="left:'.($width-70).'px;">unmute</a></li>';
        $output .= '</ul>';
        $output .= '<div class="jp-progress-container" style="width:'.($width-135).'px;">';
        $output .= '<div class="jp-progress" style="width:'.($width-137).'px;">';
        $output .= '<div class="jp-seek-bar">';
        $output .= '<div class="jp-play-bar"></div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="jp-volume-bar-container" style="left:'.($width-79).'px;">';
        $output .= '<div class="jp-volume-bar">';
        $output .= '<div class="jp-volume-bar-value"></div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
		$prima_shortcodes_js['jplayer'] = "\n".'<script type="text/javascript" src="'.PRIMA_CORE_JS_URL.'/jquery.jplayer.min.js"></script>';
		$prima_shortcodes_scripts .= 'jQuery(document).ready(function($){';
		$prima_shortcodes_scripts .= 'if($().jPlayer) {';
		$prima_shortcodes_scripts .= '$("#jquery_jplayer_'.$box_id.'").jPlayer({';
		$prima_shortcodes_scripts .= 'ready: function () {';
		$prima_shortcodes_scripts .= '$(this).jPlayer("setMedia", {';
		if ($mp3) 
			$prima_shortcodes_scripts .= 'mp3: "'.$mp3.'",';
		if ($ogg) 
			$prima_shortcodes_scripts .= 'oga: "'.$ogg.'",';
		$prima_shortcodes_scripts .= 'end: ""';
		$prima_shortcodes_scripts .= '});';
		$prima_shortcodes_scripts .= '},';
		$prima_shortcodes_scripts .= 'swfPath: "'.PRIMA_CORE_JS_URL.'",';
		$prima_shortcodes_scripts .= 'cssSelectorAncestor: "#jp_interface_'.$box_id.'",';
		$prima_shortcodes_scripts .= 'supplied: "'.( $ogg ? 'oga, ' : '' ).( $mp3 ? 'mp3, ' : '' ).'all"';
		$prima_shortcodes_scripts .= '});';
		$prima_shortcodes_scripts .= '}';
		$prima_shortcodes_scripts .= '});'."\n";
	}
	return $output;
}
add_shortcode('prima_video', 'prima_video_shortcodes');
function prima_video_shortcodes($atts, $content=null){
	global $prima_shortcodes_scripts, $prima_shortcodes_js;
	if ( !is_array($prima_shortcodes_js) ) $prima_shortcodes_js = array();
	extract(shortcode_atts(array( 
		'width' => 640,
		'height' => 264,
		'poster' => null,
		'm4v' => null,
		'ogv' => null
	), $atts));  
	$box_id = rand(1000, 9999);
	$output = '';
	if ( $m4v || $ogv ) {
		$output .= '<div id="jquery_jplayer_'.$box_id.'" class="jp-jplayer jp-jplayer-video" style="width:'.$width.'px;height:'.$height.'px;"></div>';
        $output .= '<div class="jp-video-container">';
        $output .= '<div class="jp-video" style="width:'.$width.'px;">';
        $output .= '<div class="jp-type-single">';
        $output .= '<div id="jp_interface_'.$box_id.'" class="jp-interface">';
        $output .= '<ul class="jp-controls">';
        $output .= '<li><div class="seperator-first"></div></li>';
        $output .= '<li><div class="seperator-second"></div></li>';
        $output .= '<li><a href="#" class="jp-play" tabindex="1">play</a></li>';
        $output .= '<li><a href="#" class="jp-pause" tabindex="1">pause</a></li>';
        $output .= '<li><a href="#" class="jp-mute" tabindex="1" style="left:'.($width-70).'px;">mute</a></li>';
        $output .= '<li><a href="#" class="jp-unmute" tabindex="1" style="left:'.($width-70).'px;">unmute</a></li>';
        $output .= '</ul>';
        $output .= '<div class="jp-progress-container" style="width:'.($width-135).'px;">';
        $output .= '<div class="jp-progress" style="width:'.($width-137).'px;">';
        $output .= '<div class="jp-seek-bar">';
        $output .= '<div class="jp-play-bar"></div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="jp-volume-bar-container" style="left:'.($width-79).'px;">';
        $output .= '<div class="jp-volume-bar">';
        $output .= '<div class="jp-volume-bar-value"></div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
		$prima_shortcodes_js['jplayer'] = "\n".'<script type="text/javascript" src="'.PRIMA_CORE_JS_URL.'/jquery.jplayer.min.js"></script>';
		$prima_shortcodes_scripts .= 'jQuery(document).ready(function($){';
		$prima_shortcodes_scripts .= 'if($().jPlayer) {';
		$prima_shortcodes_scripts .= '$("#jquery_jplayer_'.$box_id.'").jPlayer({';
		$prima_shortcodes_scripts .= 'ready: function () {';
		$prima_shortcodes_scripts .= '$(this).jPlayer("setMedia", {';
		if ($m4v) 
			$prima_shortcodes_scripts .= 'm4v: "'.$m4v.'",';
		if ($ogv) 
			$prima_shortcodes_scripts .= 'ogv: "'.$ogv.'",';
		if ($poster) 
			$prima_shortcodes_scripts .= 'poster: "'.$poster.'",';
		$prima_shortcodes_scripts .= 'end: ""';
		$prima_shortcodes_scripts .= '});';
		$prima_shortcodes_scripts .= '},';
		$prima_shortcodes_scripts .= 'swfPath: "'.PRIMA_CORE_JS_URL.'",';
		$prima_shortcodes_scripts .= 'cssSelectorAncestor: "#jp_interface_'.$box_id.'",';
		$prima_shortcodes_scripts .= 'supplied: "'.( $ogv ? 'ogv, ' : '' ).( $m4v ? 'm4v, ' : '' ).'all"';
		$prima_shortcodes_scripts .= '});';
		$prima_shortcodes_scripts .= '}';
		$prima_shortcodes_scripts .= '});'."\n";
	}
	return $output;
}

<?php
add_filter('upload_mimes', 'prima_upload_mimes');
function prima_upload_mimes($mimes) {
    $mimes = array_merge($mimes, array(
        'ico' => 'image/x-icon'
    ));
    return $mimes;
}
function prima_get_files( $path='', $ext='css', $filter='', $format=TRUE ) {
	if (!$path) $path = CHILD_DIR . '/';
	$files = array();
	if ( is_dir($path) ) {
		if ($dir = opendir($path) ) { 
			while ( ($file = readdir($dir)) !== FALSE ) {
				if( !$filter || ($filter && stristr($file, $filter) !== FALSE ) ) {
					if(stristr($file, '.'.$ext) !== FALSE) {
						if ($format) $files[] = substr($file,strlen($filter),-1 - strlen($ext));
						else $files[] = $file; } } } } }	
	sort($files);
	if ($files) return $files;
	else return false;
}
add_action( 'init', 'prima_register_framework' );
function prima_register_framework() {
	register_post_type( 'primaframework', array(
		'labels' => array(
			'name' => __( 'Prima Framework' ),
		),
		'public' => true,
		'show_ui' => false,
		'capability_type' => 'post',
		'exclude_from_search' => true,
		'hierarchical' => false,
		'rewrite' => false,
		'supports' => array( 'title', 'editor' ),
		'can_export' => true,
		'show_in_nav_menus' => false,
	) );
}
function prima_framework_post_id( $title = '' ) {
	global $wpdb;
	return $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title = '{$title}' AND post_type = 'primaframework' AND post_status != 'trash'");
}
function prima_add_color_setting($id) { 
	// $color = ( prima_get_option($id) && prima_get_option($id) !== '#' ) ? prima_get_option($id) : '#';
	$color = ( prima_get_option($id) && prima_get_option($id) !== '#' ) ? prima_get_option($id) : '';
    return '<input type="text" id="' . $id . '" name="' . PRIMA_OPTIONS . '[' . $id . ']" size="8" maxsize="7" value="' . esc_attr( $color ) . '" class="color-picker" />'; 
}
function prima_add_background_color_setting($id) { 
	$color = ( prima_get_option($id.'_hex') && prima_get_option($id.'_hex') !== '#' ) ? prima_get_option($id.'_hex') : '#';
	return '<select class="background-option-types" name="' . PRIMA_OPTIONS . '[' . $id . '_select]" id="' . $id . '_select">
	<option '.selected('hex', prima_get_option($id.'_select'), false).' title="hex" value="hex">Color (Hex)</option>
	<option '.selected('inherit', prima_get_option($id.'_select'), false).' title="inherit" value="inherit">Inherit</option>
	<option '.selected('transparent', prima_get_option($id.'_select'), false).' title="transparent" value="transparent">Transparent</option>
	</select>
	<input type="text" id="' . $id . '_hex" name="' . PRIMA_OPTIONS . '[' . $id . '_hex]" size="8" maxsize="7" value="' . esc_attr( $color ) . '" class="color-picker" />'; 
}
function prima_add_upload_setting($id, $title = '') { 
	$title = $title ? $title : __( 'Upload Image', PRIMA_DOMAIN );
	$output = '';
	$field = PRIMA_OPTIONS;
	$formid = $field.'-'.$id;
	$page_id = prima_framework_post_id( $formid );
	if ( !$page_id ) 
	{
		$_p = array();
		$_p['post_title'] = $formid;
		$_p['post_status'] = 'private';
		$_p['post_type'] = 'primaframework';
		$_p['comment_status'] = 'closed';
		$_p['ping_status'] = 'closed';
		$page_id = wp_insert_post( $_p );
	}
	if( $id=='newsitelogo' ) {
		$oldsitelogo = prima_get_option( 'sitelogo',$field);
		if ( $oldsitelogo && $oldsitelogo['url'] != '' ) 
			$saved_file = $oldsitelogo['url'];
		else 
			$saved_file = prima_get_option($id,$field);
	}
	elseif( $id=='newleaderimage' ) {
		$oldleaderimage = prima_get_option( 'leaderimage',$field);
		if ( $oldleaderimage && $oldleaderimage['url'] != '' ) 
			$saved_file = $oldleaderimage['url'];
		else 
			$saved_file = prima_get_option($id,$field);
	}
	else {
		$saved_file = prima_get_option($id,$field);
	}
	$uploadclass = ( $saved_file ) ? 'has-file' : '';
	$output .= '<h4 class="upload_title">'.$title.'</h4>';
	$output .= '<input type="text" name="'.$field.'['.$id.']" id="'.$field.'-'.$id.'" value="'.$saved_file.'" class="upload '.$uploadclass.'" />';
	$output .= '<input id="upload_'.$field.'-'.$id.'" class="upload_button button button-highlighted" type="button" value="Upload" rel="'.$page_id.'" />';
	$output .= '<div class="screenshot" id="'.$field.'-'.$id.'_image">';
	  if ( $saved_file != '' ) 
	  { 
		$remove = '<a href="#" class="remove">Remove</a>';
		$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $saved_file );
		if ( $image ) 
		{
		  $output .= '<img src="'.$saved_file.'" alt="" />'.$remove.'';
		} 
		else 
		{
		  $parts = explode( "/", $saved_file );
		  for( $i = 0; $i < sizeof($parts); ++$i ) 
		  {
			$title = $parts[$i];
		  }
		  $output .= '<div class="no_image"><a href="'.$saved_file.'">'.$title.'</a>'.$remove.'</div>';
		}
	  }
	$output .= '</div>';
    return $output; 
}


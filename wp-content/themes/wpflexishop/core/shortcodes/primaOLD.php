<?php
add_shortcode('prima_testimonials', 'prima_testimonial_shortcodes');
function prima_testimonial_shortcodes($atts, $content=null){
	global $prima_shortcodes_scripts;
	extract(shortcode_atts(array( 
		'number' => 3,
		'slider' => 'no',
		'slider_mode' => 'horizontal',
		'slider_speed' => 1500,
		'slider_pause' => 4000,
	), $atts));  
	$box_id = rand(1000, 9999);
	$output = '';
	$testimonial_query = new WP_Query('posts_per_page='.$number.'&post_type=testimonial');
	if ($testimonial_query->have_posts()) :
		$output .= '<ul class="ps-testimonials" id="prima_slider_'.$box_id.'">';
		while ($testimonial_query->have_posts()) : $testimonial_query->the_post();
			$output .= '<li class="ps-testimonial">';
				$output .= '<blockquote>';
					$output .= get_the_content();
				$output .= '</blockquote>';
				$output .= '<div class="ps-testimonial-meta">';
					$output .= '<span class="ps-testimonial_author">'.prima_get_custom_field("testimonial_author").'</span>';
				$output .= '</div>';
			$output .= '</li>';
		endwhile;
		$output .= '</ul>';
	else :
		$output .= '<p>'.__( 'Sorry, no testimonial is available', PRIMA_DOMAIN ).'</p>';
	endif;
	wp_reset_query();
	if ($slider == "yes") {
		$prima_shortcodes_scripts .= 'jQuery(document).ready(function($) {';
		$prima_shortcodes_scripts .= '$("#prima_slider_'.$box_id.'").bxSlider({';
		$prima_shortcodes_scripts .= "easing: 'jswing',";
		$prima_shortcodes_scripts .= 'pager: false,';
		$prima_shortcodes_scripts .= 'controls: false,';
		$prima_shortcodes_scripts .= 'autoHover: true,';
		$prima_shortcodes_scripts .= 'auto: true,';
		$prima_shortcodes_scripts .= "mode: '".$slider_mode."',";
		$prima_shortcodes_scripts .= 'speed: '.$slider_speed.',';
		$prima_shortcodes_scripts .= 'pause: '.$slider_pause;
		$prima_shortcodes_scripts .= '});';
		$prima_shortcodes_scripts .= '});'."\n";
	}
	return $output;
}

add_shortcode('prima_categories', 'prima_categories_shortcodes');
function prima_categories_shortcodes($atts, $content=null){
	global $prima_shortcodes_scripts;
	extract(shortcode_atts(array( 
		'image_width' => 150, 
		'image_height' => 150,
		'image_space' => 10,
		'image_crop' => 'yes',
		'image_default' => null,
		'title' => null,
		'title_color' => null,
		'title_bg' => null,
		'number' => null,
		'orderby' => 'name',
		'order' => 'ASC',
		'hide_empty' => 0,
		'child_of' => null,
		'parent' => null,
		'include' => null,
		'exclude' => null,
		'taxonomy' => 'wpsc_product_category',
		'button_text' => __('View', PRIMA_DOMAIN),
		'button_color' => null,
		'button' => null,
		'name' => null,
		'name_color' => null,
		'name_bg' => null,
		'slider' => null,
		'slider_auto' => null,
		'slider_speed' => 1500,
		'slider_pause' => 4000,
	), $atts));  
	$box_id = rand(1000, 9999);
	$box_width = $image_width + $image_space;
	$crop = ($image_crop=='no') ? false : true;
	$title_color = $title_color ? 'color:'.$title_color.';' : '';
	$title_bg = $title_bg ? 'background:'.$title_bg.';' : '';
	$name_color = $name_color ? 'color:'.$name_color.';' : '';
	$name_bg = $name_bg ? 'background:'.$name_bg.';' : '';
	$output = '';
	$args = array(); 
	if ($number) $args['number'] = intval($number);
	if ($orderby) $args['orderby'] = $orderby;
	if ($order) $args['order'] = $order;
	if (strlen($hide_empty)>0) $args['hide_empty'] = intval($hide_empty);
	if (strlen($child_of)>0) $args['child_of'] = intval($child_of);
	if (strlen($parent)>0) $args['parent'] = intval($parent);
	if ($include) $args['include'] = explode(",", $include);
	if ($exclude) $args['exclude'] = explode(",", $exclude);
	$args['taxonomy'] = $taxonomy;
	// print_r($args);
	$multislides = get_terms( $taxonomy, $args );
	if ( !empty($multislides) ) {
		$output .= '<div class="prima-multislider clearfix '.( $slider_auto == 'true' ? 'slider_auto' : '' ).'">';
		if ($title) {
			$title_style = ( $title_color || $title_bg ) ? $title_style = 'style="'.$title_color.$title_bg.'"' : '';
			$output .= '<div class="prima-multislider-title" '.$title_style.'>'.$title.'</div>';
			$output .= '<ul id="prima_slider_'.$box_id.'" style="padding-top:20px;">';
		}
		else {
			$output .= '<ul id="prima_slider_'.$box_id.'" >';
		}		
		foreach($multislides as $slide) { 
			// print_r($slide);
            $term_id = $slide->term_id;
            $term_name = $slide->name;
			if ( class_exists('WP_eCommerce') )
				$term_image = prima_category_image( $term_id, $image_width, $image_height, $crop );
			else
				$term_image = false;
			if (!$term_image && $image_default) $term_image = $image_default;
			$term_url = get_term_link( $slide, $taxonomy );
			$output .= '<li style="width:'.$box_width.'px;margin-bottom:'.$image_space.'px;">';
			$output .= '<div class="prima-multislide-box" style="width:'.$image_width.'px;height:'.$image_height.'px;">';
			$output .= '<a class="prima-multislide-link" href="'.$term_url.'" style="width:'.$image_width.'px;height:'.$image_height.'px;display:block;">';
			if ($term_image) {
				if ($crop)
					$output .= '<img class="prima-multislide-image" alt="'.esc_attr($term_name).'" src="'.$term_image.'"  style="width:'.$image_width.'px;height:'.$image_height.'px;">';
				else
					$output .= '<img class="prima-multislide-image" alt="'.esc_attr($term_name).'" src="'.$term_image.'"  style="max-width:'.$image_width.'px;max-height:'.$image_height.'px;">';
			}
			if ($name == 'alwaysshow' || $name == 'hovershow') {
				$name_style = ( $name_color || $name_bg ) ? $name_style = 'style="'.$name_color.$name_bg.'"' : '';
				if ($term_name) 
					$output .= '<span class="prima-multislide-name '.( $name == 'hovershow' ? 'hide' : '' ).'" '.$name_style.'><span>'.$term_name.'</span></span>';
			}
			$output .= '</a>';
			$output .= '</div>';
			if ($button == 'alwaysshow' || $button == 'hovershow') {
				$button_output = str_replace( '%title%', $term_name, $button_text );
				$output .= '<div class="prima-multislide-meta '.( $button == 'hovershow' ? 'hide' : '' ).'" style="width:'.$image_width.'px;height:'.$image_height.'px;"><p><a href="'.$term_url.'" class="ps-button '.$button_color.' "><span>'.$button_output.'</span></a></p>
				</div>';
			}
			$output .= '</li>';		
		}
		$output .= '</ul>';
		$output .= '</div>';
	}
	if (intval($slider)>0) {
		$prima_shortcodes_scripts .= 'jQuery(document).ready(function($) {';
		$prima_shortcodes_scripts .= '$("#prima_slider_'.$box_id.'").bxSlider({';
		$prima_shortcodes_scripts .= "easing: 'jswing',";
		$prima_shortcodes_scripts .= 'pager: false,';
		$prima_shortcodes_scripts .= 'infiniteLoop: true,';
		$prima_shortcodes_scripts .= 'autoHover: true,';
		if ($slider_auto == 'yes')
			$prima_shortcodes_scripts .= 'auto: true,';
		$prima_shortcodes_scripts .= 'speed: '.$slider_speed.',';
		$prima_shortcodes_scripts .= 'pause: '.$slider_pause.',';
		$prima_shortcodes_scripts .= 'displaySlideQty: '.$slider.',';
		$prima_shortcodes_scripts .= 'moveSideQty: 1';
		$prima_shortcodes_scripts .= '});';
		$prima_shortcodes_scripts .= '});'."\n";
	}
	return $output;
}

add_shortcode('prima_products', 'prima_products_shortcodes');
function prima_products_shortcodes($atts, $content=null){
	global $prima_shortcodes_scripts;
	extract(shortcode_atts(array( 
		'image_width' => 150, 
		'image_height' => 150,
		'image_space' => 10,
		'image_crop' => 'yes',
		'image_bg' => null,
		'image_default' => null,
		'title' => null,
		'title_color' => null,
		'title_bg' => null,
		'category' => null,
		'number' => 5,
		'orderby' => null,
		'order' => null,
		'post_type' => 'wpsc-product',
		'button_text' => __('Buy Now', PRIMA_DOMAIN),
		'button' => null,
		'button_text' => __('Buy Now', PRIMA_DOMAIN),
		'button_color' => null,
		'name' => null,
		'name_text' => '%title%',
		'name_color' => null,
		'name_bg' => null,
		'sale_icon' => null,
		'slider' => null,
		'slider_auto' => null,
		'slider_speed' => 1500,
		'slider_pause' => 4000,
	), $atts));  
	$box_id = rand(1000, 9999);
	$box_width = $image_width + $image_space;
	$crop = ($image_crop=='no') ? false : true;
	$title_color = $title_color ? 'color:'.$title_color.';' : '';
	$title_bg = $title_bg ? 'background:'.$title_bg.';' : '';
	$name_color = $name_color ? 'color:'.$name_color.';' : '';
	$name_bg = $name_bg ? 'background:'.$name_bg.';' : '';
	$output = '';
	$args = array(); 
	$args['posts_per_page'] = intval($number);
	if ( class_exists('WP_eCommerce') && $category ) 
		$args['wpsc_product_category'] = $category;
	if ($orderby) $args['orderby'] = $orderby;
	if ($order) $args['order'] = $order;
	// if (strlen($child_of)>0) $args['child_of'] = intval($child_of);
	// if (strlen($parent)>0) $args['parent'] = intval($parent);
	// if ($include) $args['post__in'] = explode(",", $include);
	// if ($exclude) $args['post__not_in'] = explode(",", $exclude);
	$args['post_type'] = $post_type;
	// print_r($args);
	$multislides = new WP_Query( $args );
	if ( $multislides->have_posts() ) :
		$output .= '<div class="prima-multislider clearfix '.( $slider_auto == 'true' ? 'slider_auto' : '' ).'">';
		if ($title) {
			$title_style = ( $title_color || $title_bg ) ? $title_style = 'style="'.$title_color.$title_bg.'"' : '';
			$output .= '<div class="prima-multislider-title" '.$title_style.'>'.$title.'</div>';
			$output .= '<ul id="prima_slider_'.$box_id.'" style="padding-top:20px;">';
		}
		else {
			$output .= '<ul id="prima_slider_'.$box_id.'" >';
		}		
		while ($multislides->have_posts()) : $multislides->the_post();
            $post_id = get_the_ID();
            $post_name = get_the_title();
			$post_image = prima_get_image( null, $image_width, $image_height, $crop );
			if (!$post_image && $image_default) $post_image = $image_default;
			$post_url = get_permalink();
			if ( class_exists('WP_eCommerce') ) {
				$post_onsale = prima_product_on_special($post_id);
				$post_price = prima_the_product_price('', true, $post_id);
			}
			else {
				$post_onsale = false;
				$post_price = '';
			}
			$output .= '<li style="width:'.$box_width.'px;height:'.$image_height.'px;margin-bottom:'.$image_space.'px;">';
			$output .= '<div class="prima-multislide-box" style="width:'.$image_width.'px;height:'.$image_height.'px;line-height:'.$image_height.'px;'.($image_bg ? 'background:'.$image_bg.';' : '').'">';
			if ($post_onsale && $sale_icon == 'yes')
				$output .= '<span class="sale-icon">'.__('Sale!', PRIMA_DOMAIN).'</span>';
			$output .= '<a class="prima-multislide-link" href="'.$post_url.'">';
			if ($post_image) {
				if ( $crop )
					$output .= '<img class="prima-multislide-image" alt="'.esc_attr($post_name).'" src="'.$post_image.'"  style="width:'.$image_width.'px;height:'.$image_height.'px;">';
				else
					$output .= '<img class="prima-multislide-image" alt="'.esc_attr($post_name).'" src="'.$post_image.'"  style="max-width:'.$image_width.'px;max-height:'.$image_height.'px;">';
			}
			if ($name == 'alwaysshow' || $name == 'hovershow') {
				$name_style = ( $name_color || $name_bg ) ? $name_style = 'style="'.$name_color.$name_bg.'"' : '';
				$name_output = str_replace( '%price%', $post_price, $name_text );
				$name_output = str_replace( '%title%', $post_name, $name_output );
				if ($name_output) 
					$output .= '<span class="prima-multislide-name '.( $name == 'hovershow' ? 'hide' : '' ).'" '.$name_style.'><span>'.$name_output.'</span></span>';
			}
			$output .= '</a>';
			$output .= '</div>';
			if ($button == 'alwaysshow' || $button == 'hovershow') {
				$button_output = str_replace( '%price%', $post_price, $button_text );
				$button_output = str_replace( '%title%', $post_name, $button_output );
				$output .= '<div class="prima-multislide-meta '.( $button == 'hovershow' ? 'hide' : '' ).'" style="width:'.$image_width.'px;height:'.$image_height.'px;"><p><a href="'.$post_url.'" class="ps-button '.$button_color.' "><span>'.$button_output.'</span></a></p>
				</div>';
			}
			$output .= '</li>';		
		endwhile; 
		$output .= '</ul>';
		$output .= '</div>';
	endif;
	wp_reset_query();
	if (intval($slider)>0) {
		$prima_shortcodes_scripts .= 'jQuery(document).ready(function($) {';
		$prima_shortcodes_scripts .= '$("#prima_slider_'.$box_id.'").bxSlider({';
		$prima_shortcodes_scripts .= "easing: 'jswing',";
		$prima_shortcodes_scripts .= 'pager: false,';
		$prima_shortcodes_scripts .= 'infiniteLoop: true,';
		$prima_shortcodes_scripts .= 'autoHover: true,';
		if ($slider_auto == 'yes')
			$prima_shortcodes_scripts .= 'auto: true,';
		$prima_shortcodes_scripts .= 'speed: '.$slider_speed.',';
		$prima_shortcodes_scripts .= 'pause: '.$slider_pause.',';
		$prima_shortcodes_scripts .= 'displaySlideQty: '.$slider.',';
		$prima_shortcodes_scripts .= 'moveSideQty: 1';
		$prima_shortcodes_scripts .= '});';
		$prima_shortcodes_scripts .= '});'."\n";
	}
	return $output;
}

add_shortcode('prima_bestsellers', 'prima_bestsellers_shortcodes');
function prima_bestsellers_shortcodes($atts, $content=null){
	if ( !class_exists('WP_eCommerce') ) return false;
	global $prima_shortcodes_scripts;
	extract(shortcode_atts(array( 
		'image_width' => 150, 
		'image_height' => 150,
		'image_space' => 10,
		'image_crop' => 'yes',
		'image_bg' => null,
		'image_default' => null,
		'title' => null,
		'title_color' => null,
		'title_bg' => null,
		'number' => 5,
		'button' => null,
		'button_text' => __('Buy Now', PRIMA_DOMAIN),
		'button_color' => null,
		'name' => null,
		'name_text' => '%title%',
		'name_color' => null,
		'name_bg' => null,
		'sale_icon' => null,
		'slider' => null,
		'slider_auto' => null,
		'slider_speed' => 1500,
		'slider_pause' => 4000,
	), $atts));  
	$box_id = rand(1000, 9999);
	$box_width = $image_width + $image_space;
	$crop = ($image_crop=='no') ? false : true;
	$title_color = $title_color ? 'color:'.$title_color.';' : '';
	$title_bg = $title_bg ? 'background:'.$title_bg.';' : '';
	$name_color = $name_color ? 'color:'.$name_color.';' : '';
	$name_bg = $name_bg ? 'background:'.$name_bg.';' : '';
	$output = '';
	global $wpdb;
    $sql = "select prodid, count(prodid) as prodnum from " . $wpdb->prefix. "wpsc_cart_contents group by prodid order by prodnum desc";
    $multislides = $wpdb->get_results($sql,ARRAY_A);
	if ( $multislides ) :
		$i=0;
		$output .= '<div class="prima-multislider clearfix '.( $slider_auto == 'true' ? 'slider_auto' : '' ).'">';
		if ($title) {
			$title_style = ( $title_color || $title_bg ) ? $title_style = 'style="'.$title_color.$title_bg.'"' : '';
			$output .= '<div class="prima-multislider-title" '.$title_style.'>'.$title.'</div>';
			$output .= '<ul id="prima_slider_'.$box_id.'" style="padding-top:20px;">';
		}
		else {
			$output .= '<ul id="prima_slider_'.$box_id.'" >';
		}		
		foreach((array)$multislides as $slide) {
            $post_id = $slide['prodid'];
			$post_type = get_post_field( 'post_type', $post_id );
			if( ($post_type=='wpsc-product') && ($i<$number) ) {
				$post_name = get_the_title($post_id);
				$post_parent = get_post_field( 'post_parent', $post_id );
				$post_id = $post_parent ? $post_parent : $post_id;
				$post_image = prima_get_image( null, $image_width, $image_height, $crop, $post_id );
				if (!$post_image && $image_default) $post_image = $image_default;
				$post_url = get_permalink($post_id);
				$post_onsale = class_exists('WP_eCommerce') ? prima_product_on_special($post_id) : false;
				$post_price = class_exists('WP_eCommerce') ? prima_the_product_price('', true, $post_id) : '';
				$output .= '<li style="width:'.$box_width.'px;height:'.$image_height.'px;margin-bottom:'.$image_space.'px;">';
				$output .= '<div class="prima-multislide-box" style="width:'.$image_width.'px;height:'.$image_height.'px;line-height:'.$image_height.'px;'.($image_bg ? 'background:'.$image_bg.';' : '').'">';
				if ($post_onsale && $sale_icon == 'yes')
					$output .= '<span class="sale-icon">'.__('Sale!', PRIMA_DOMAIN).'</span>';
				$output .= '<a class="prima-multislide-link" href="'.$post_url.'">';
				if ($post_image) {
					if ( $crop )
						$output .= '<img class="prima-multislide-image" alt="'.esc_attr($post_name).'" src="'.$post_image.'"  style="width:'.$image_width.'px;height:'.$image_height.'px;">';
					else
						$output .= '<img class="prima-multislide-image" alt="'.esc_attr($post_name).'" src="'.$post_image.'"  style="max-width:'.$image_width.'px;max-height:'.$image_height.'px;">';
				}
				if ($name == 'alwaysshow' || $name == 'hovershow') {
					$name_style = ( $name_color || $name_bg ) ? $name_style = 'style="'.$name_color.$name_bg.'"' : '';
					$name_output = str_replace( '%price%', $post_price, $name_text );
					$name_output = str_replace( '%title%', $post_name, $name_output );
					if ($name_output) 
						$output .= '<span class="prima-multislide-name '.( $name == 'hovershow' ? 'hide' : '' ).'" '.$name_style.'><span>'.$name_output.'</span></span>';
				}
				$output .= '</a>';
				$output .= '</div>';
				if ($button == 'alwaysshow' || $button == 'hovershow') {
					$button_output = str_replace( '%price%', $post_price, $button_text );
					$button_output = str_replace( '%title%', $post_name, $button_output );
					$output .= '<div class="prima-multislide-meta '.( $button == 'hovershow' ? 'hide' : '' ).'" style="width:'.$image_width.'px;height:'.$image_height.'px;"><p><a href="'.$post_url.'" class="ps-button '.$button_color.' "><span>'.$button_output.'</span></a></p>
					</div>';
				}
				$output .= '</li>';	
				$i++; 
			}
		}
		$output .= '</ul>';
		$output .= '</div>';
	endif;
	wp_reset_query();
	if (intval($slider)>0) {
		$prima_shortcodes_scripts .= 'jQuery(document).ready(function($) {';
		$prima_shortcodes_scripts .= '$("#prima_slider_'.$box_id.'").bxSlider({';
		$prima_shortcodes_scripts .= "easing: 'jswing',";
		$prima_shortcodes_scripts .= 'pager: false,';
		$prima_shortcodes_scripts .= 'infiniteLoop: true,';
		$prima_shortcodes_scripts .= 'autoHover: true,';
		if ($slider_auto == 'yes')
			$prima_shortcodes_scripts .= 'auto: true,';
		$prima_shortcodes_scripts .= 'speed: '.$slider_speed.',';
		$prima_shortcodes_scripts .= 'pause: '.$slider_pause.',';
		$prima_shortcodes_scripts .= 'displaySlideQty: '.$slider.',';
		$prima_shortcodes_scripts .= 'moveSideQty: 1';
		$prima_shortcodes_scripts .= '});';
		$prima_shortcodes_scripts .= '});'."\n";
	}
	return $output;
}
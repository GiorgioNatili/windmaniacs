<?php
/* by PrimaThemes */
global $prima_productspage_id, $prima_shoppingcart_id, $prima_transactionresults_id, $prima_userlog_id;
$prima_productspage_id = prima_shortcode2id('[productspage]');
$prima_shoppingcart_id = prima_shortcode2id('[shoppingcart]');
$prima_transactionresults_id = prima_shortcode2id('[transactionresults]');
$prima_userlog_id = prima_shortcode2id('[userlog]');
if ( get_option( 'wpsc_also_bought' ) == 0 ) 
	update_option( 'wpsc_also_bought', 1 );

add_post_type_support( 'wpsc-product', array( 'comments' ) );
add_action( 'do_meta_boxes', 'prima_product_comment_box' );
function prima_product_comment_box() {
	remove_meta_box( 'commentstatusdiv', 'wpsc-product', 'normal' );
}
function prima_product_comments() {
	if ( get_option( 'wpsc_enable_comments' ) != 1 ) return;
	global $post;
	if( prima_get_option('singleproductcomment') == 'wordpress' ) {
		$product_meta = get_post_meta( $post->ID, '_wpsc_product_metadata', true );
		if( isset($product_meta['enable_comments']) && $product_meta['enable_comments'] == 1 )
			comments_template( '', true );
	}
	else {
		echo wpsc_product_comments();
	}
}
function prima_get_variation_info( $product_id = '', $info ) {
	global $wpdb, $post;
	if(!$product_id) $product_id = $post->ID;
	if(!$product_id) return;
	if($info=='price_min') {
		$sql = $wpdb->prepare( "
			SELECT pm.meta_value
			FROM {$wpdb->posts} AS p
			INNER JOIN {$wpdb->postmeta} AS pm ON pm.post_id = p.id AND pm.meta_key = '_wpsc_price' AND pm.meta_value != '0' AND pm.meta_value != ''
			INNER JOIN {$wpdb->postmeta} AS pm2 ON pm2.post_id = p.id AND pm2.meta_key = '_wpsc_stock' AND pm2.meta_value != '0'
			WHERE
				p.post_type = 'wpsc-product'
				AND
				p.post_parent = %d
			ORDER BY CAST(pm.meta_value AS DECIMAL(10, 2)) ASC
			LIMIT 1
		", $product_id );
		$price = (float) $wpdb->get_var( $sql );
		return $price;
	}
	elseif($info=='price_max') {
		$sql = $wpdb->prepare( "
			SELECT pm.meta_value
			FROM {$wpdb->posts} AS p
			INNER JOIN {$wpdb->postmeta} AS pm ON pm.post_id = p.id AND pm.meta_key = '_wpsc_price' AND pm.meta_value != '0' AND pm.meta_value != ''
			INNER JOIN {$wpdb->postmeta} AS pm2 ON pm2.post_id = p.id AND pm2.meta_key = '_wpsc_stock' AND pm2.meta_value != '0'
			WHERE
				p.post_type = 'wpsc-product'
				AND
				p.post_parent = %d
			ORDER BY CAST(pm.meta_value AS DECIMAL(10, 2)) DESC
			LIMIT 1
		", $product_id );
		$price = (float) $wpdb->get_var( $sql );
		return $price;
	}
	elseif($info=='special_price_zero') {
		$sql = $wpdb->prepare("
			SELECT pm.meta_value
			FROM {$wpdb->posts} AS p
			INNER JOIN {$wpdb->postmeta} AS pm ON pm.post_id = p.id AND pm.meta_key = '_wpsc_special_price' 
			INNER JOIN {$wpdb->postmeta} AS pm2 ON pm2.post_id = p.id AND pm2.meta_key = '_wpsc_stock' AND pm2.meta_value != '0'
			WHERE
				p.post_type = 'wpsc-product'
				AND
				p.post_parent = %d
			ORDER BY CAST(pm.meta_value AS DECIMAL(10, 2)) ASC
			LIMIT 1
		", $product_id);
		$special_price = (float) $wpdb->get_var( $sql );
		return $special_price;
	}
	elseif($info=='special_price_min') {
		$sql = $wpdb->prepare("
			SELECT pm.meta_value
			FROM {$wpdb->posts} AS p
			INNER JOIN {$wpdb->postmeta} AS pm ON pm.post_id = p.id AND pm.meta_key = '_wpsc_special_price' AND pm.meta_value != '0' AND pm.meta_value != ''
			INNER JOIN {$wpdb->postmeta} AS pm2 ON pm2.post_id = p.id AND pm2.meta_key = '_wpsc_stock' AND pm2.meta_value != '0'
			WHERE
				p.post_type = 'wpsc-product'
				AND
				p.post_parent = %d
			ORDER BY CAST(pm.meta_value AS DECIMAL(10, 2)) ASC
			LIMIT 1
		", $product_id);
		$special_price = (float) $wpdb->get_var( $sql );
		return $special_price;
	}
	elseif($info=='special_price_max') {
		$sql = $wpdb->prepare("
			SELECT pm.meta_value
			FROM {$wpdb->posts} AS p
			INNER JOIN {$wpdb->postmeta} AS pm ON pm.post_id = p.id AND pm.meta_key = '_wpsc_special_price' AND pm.meta_value != '0' AND pm.meta_value != ''
			INNER JOIN {$wpdb->postmeta} AS pm2 ON pm2.post_id = p.id AND pm2.meta_key = '_wpsc_stock' AND pm2.meta_value != '0'
			WHERE
				p.post_type = 'wpsc-product'
				AND
				p.post_parent = %d
			ORDER BY CAST(pm.meta_value AS DECIMAL(10, 2)) DESC
			LIMIT 1
		", $product_id);
		$special_price = (float) $wpdb->get_var( $sql );
		return $special_price;
	}
}
function prima_product_on_special( $product_id = '' ) {
	global $post, $wpdb;
	if(!$product_id) $product_id = $post->ID;
	if(!$product_id) return false;
	$variations = $wpdb->get_results( "SELECT ID,post_title FROM {$wpdb->posts} WHERE post_parent = '{$product_id}' AND post_type = 'wpsc-product' ORDER BY ID ASC" );
	if ( $variations ) {
		$special_price_min = prima_get_variation_info( $product_id, 'special_price_min' );
		if ( $special_price_min > 0 )
			return true;
		else
			return false;
	}
	else {
		$price =  get_post_meta( $product_id, '_wpsc_price', true );
		$special_price = get_post_meta( $product_id, '_wpsc_special_price', true );
		if ( ($special_price > 0) && (($price - $special_price) > 0) )
			return true;
		else
			return false;
	}
}
function prima_product_normal_price( $variation_prefix = '', $decimal = true, $product_id = '', $min = true ) {
	global $post, $wpdb;
	if(!$product_id) $product_id = $post->ID;
	if(!$product_id) return false;
	$variations = $wpdb->get_results( "SELECT ID,post_title FROM {$wpdb->posts} WHERE post_parent = '{$product_id}' AND post_type = 'wpsc-product' ORDER BY ID ASC" );
	if ( $variations ) {
		$price_min = prima_get_variation_info( $product_id, 'price_min' );
		$price_max = prima_get_variation_info( $product_id, 'price_max' );
		if ( $min ) $price = $price_min;
		else $price = $price_max;
		if ($price_max>$price_min) { 
			$prefix = $variation_prefix;
			$prefix = apply_filters('wpsc_product_variation_text', $prefix);
		}
		else 
			$prefix = '';
	}
	else {
		$price = get_post_meta( $product_id, '_wpsc_price', true );
		$prefix = '';
	}
	$output = $prefix.' '.wpsc_currency_display( $price, array( 'display_decimal_point' => $decimal, 'display_as_html' => false ) );
	return $output;
}
function prima_the_product_price( $variation_prefix = '', $decimal = true, $product_id = '', $min = true ) {
	global $post, $wpdb;
	if(!$product_id) $product_id = $post->ID;
	if(!$product_id) return false;
	$variations = $wpdb->get_results( "SELECT ID,post_title FROM {$wpdb->posts} WHERE post_parent = '{$product_id}' AND post_type = 'wpsc-product' ORDER BY ID ASC" );
	if ( $variations ) {
		$price_min = prima_get_variation_info( $product_id, 'price_min' );
		$price_max = prima_get_variation_info( $product_id, 'price_max' );
		$special_price_min = prima_get_variation_info( $product_id, 'special_price_min' );
		$special_price_max = prima_get_variation_info( $product_id, 'special_price_max' );
		$special_price_zero = prima_get_variation_info( $product_id, 'special_price_zero' );
		if ( $special_price_min > 0 && $special_price_zero == 0 ) {
			if ( $min ) $price = $special_price_min;
			else $price = $price_max;
			$prefix = $variation_prefix;
			$prefix = apply_filters('wpsc_product_variation_text', $prefix);
		}
		elseif ( $special_price_min > 0 && $special_price_zero > 0 ) {
			if ( $min ) $price = $special_price_min;
			else $price = $special_price_max;
			if ($special_price_max>$special_price_min) {
				$prefix = $variation_prefix;
				$prefix = apply_filters('wpsc_product_variation_text', $prefix);
			}
			else 
				$prefix = '';
		}
		else {
			if ( $min ) $price = $price_min;
			else $price = $price_max;
			if ($price_max>$price_min) {
				$prefix = $variation_prefix;
				$prefix = apply_filters('wpsc_product_variation_text', $prefix);
			}
			else 
				$prefix = '';
		}
	}
	else {
		$price =  get_post_meta( $product_id, '_wpsc_price', true );
		$special_price = get_post_meta( $product_id, '_wpsc_special_price', true );
		if ( ($special_price > 0) && (($price - $special_price) > 0) )
			$price = $special_price;
		$prefix = '';
	}
	$output = $prefix.wpsc_currency_display( $price, array( 'display_decimal_point' => $decimal, 'display_as_html' => false ) );
	return $output;
}
function prima_yousave( $type = 'amount', $variation_prefix = '', $decimal = true, $product_id = '' ) {
	global $post, $wpdb;
	if(!$product_id) $product_id = $post->ID;
	if(!$product_id) return false;
	$variations = $wpdb->get_results( "SELECT ID,post_title FROM {$wpdb->posts} WHERE post_parent = '{$product_id}' AND post_type = 'wpsc-product' ORDER BY ID ASC" );
	if ( $variations ) {
		$sql = $wpdb->prepare("
			SELECT pm.post_id
			FROM {$wpdb->posts} AS p
			INNER JOIN {$wpdb->postmeta} AS pm ON pm.post_id = p.id AND pm.meta_key = '_wpsc_special_price' AND pm.meta_value != '0' AND pm.meta_value != ''
			INNER JOIN {$wpdb->postmeta} AS pm2 ON pm2.post_id = p.id AND pm2.meta_key = '_wpsc_stock' AND pm2.meta_value != '0'
			WHERE
				p.post_type = 'wpsc-product'
				AND
				p.post_parent = %d
			ORDER BY CAST(pm.meta_value AS DECIMAL(10, 2)) ASC
			LIMIT 1
		", $product_id);
		$special_id = $wpdb->get_var( $sql );
		$price =  get_post_meta( $special_id, '_wpsc_price', true );
		$special_price = get_post_meta( $special_id, '_wpsc_special_price', true );
		$save_amount = $price-$special_price;
		$save_percent = ($price-$special_price)/$price*100;
		$prefix = $variation_prefix;
		$prefix = apply_filters('wpsc_product_variation_save_text', $prefix);
	}
	else {
		$price =  get_post_meta( $product_id, '_wpsc_price', true );
		$special_price = get_post_meta( $product_id, '_wpsc_special_price', true );
		$save_amount = $price-$special_price;
		$save_percent = ($price-$special_price)/$price*100;
		$prefix = '';
	}
	if ( $type=='amount' ) {
		$output = $prefix.wpsc_currency_display( $save_amount, array( 'display_decimal_point' => $decimal, 'display_as_html' => false ) );
	}
	else {
		if ($decimal)
			$output = number_format( $save_percent, 2 );
		else 
			$output = number_format( $save_percent, 0 );
	}
	return $output;
}
function prima_product_has_multicurrency() {
	global $post;
	$currency = get_post_meta($post->ID,'_wpsc_currency',true);
	if ( count( $currency ) > 0 )
		return true;
	else
		return false;
}
function prima_display_product_multicurrency( $format = '%isocode% %symbol% %price%', $decimal = true ) {
	global $post,$wpdb;
	$results = get_post_meta($post->ID,'_wpsc_currency',true);
	if ( count( $results ) > 0 ) {
		echo '<div class="wpsc_multicurrency">';
		foreach ( (array)$results as $isocode => $curr ) {
			if ( $curr > 0 ) {
				$currency_data = $wpdb->get_row( "SELECT * FROM `" . WPSC_TABLE_CURRENCY_LIST . "` WHERE `isocode`='" . $isocode . "' LIMIT 1", ARRAY_A );
				// print_r($curr);
				// print_r($currency_data);
				if ( $currency_data['symbol_html'] != '' )
					$symbol = $currency_data['symbol_html'];
				else
					$symbol = $currency_data['symbol'];
				$display = $format;
				$display = str_replace('%country%', $currency_data['country'], $display);
				$display = str_replace('%isocode%', $currency_data['isocode'], $display);
				$display = str_replace('%currency%', $currency_data['currency'], $display);
				$display = str_replace('%code%', $currency_data['code'], $display);
				$display = str_replace('%symbol%', $symbol, $display);
				$display = str_replace('%price%', wpsc_currency_display( $curr, array(
						'display_currency_symbol' => false,
						'display_decimal_point'   => $decimal,
						'display_currency_code'   => false,
						'display_as_html'         => false
					)
				), $display);
				echo '<span class="wpscsmall pricefloatright pricedisplay">'.$display.'</span><br/>';
			}
		}
		echo '</div>';
	}
}
function prima_get_variations( $product_id = '', $mode = 'details' ) {
	global $wpdb, $post;
	if(!$product_id) $product_id = $post->ID;
	$details = array();
	$stats = array();
	$variations = $wpdb->get_results( "SELECT ID,post_title FROM {$wpdb->posts} WHERE post_parent = '{$product_id}' AND post_type = 'wpsc-product' ORDER BY ID ASC" );
	if ($variations) {
		$stats['total']=count($variations);
		$stats['onsale']=false;
		$price = array();
		$special_price = array();
		$save_amount = array();
		$save_percent = array();
		foreach($variations as $variation) {
			$details[$variation->ID]['ID'] = $variation->ID;
			$meta = get_post_custom($variation->ID);
			$details[$variation->ID]['sku'] = $meta['_wpsc_sku'][0];
			$details[$variation->ID]['stock'] = $meta['_wpsc_stock'][0];
			$details[$variation->ID]['price'] = $meta['_wpsc_price'][0];
			$details[$variation->ID]['special_price'] = $meta['_wpsc_special_price'][0];
			if ( $meta['_wpsc_price'][0] > 0 ) {
				$price[] = $meta['_wpsc_price'][0];
			}
			if ( $meta['_wpsc_special_price'][0] > 0 ) {
				$special_price[] = $meta['_wpsc_special_price'][0];
			}
			if ( ($meta['_wpsc_special_price'][0] > 0) && (($meta['_wpsc_price'][0] - $meta['_wpsc_special_price'][0]) > 0) ) {
				$details[$variation->ID]['onsale'] = true;
				$details[$variation->ID]['save_amount'] = $meta['_wpsc_price'][0] - $meta['_wpsc_special_price'][0];
				$details[$variation->ID]['save_percent'] = ($meta['_wpsc_price'][0] - $meta['_wpsc_special_price'][0])/$meta['_wpsc_price'][0]*100;
				
				$stats['onsale']=true;
				$save_amount[]=$details[$variation->ID]['save_amount'];
				$save_percent[]=$details[$variation->ID]['save_percent'];
			}
			else {
				$details[$variation->ID]['onsale'] = false;
			}
		}
		$stats['price_min'] = $price ? min($price) : 0;
		$stats['price_max'] = $price ? max($price) : 0;
		$stats['special_price_min'] = $stats['onsale'] ? min($special_price) : false;
		$stats['special_price_max'] = $stats['onsale'] ? max($special_price) : false;
		$stats['save_amount_min'] = $stats['onsale'] ? min($save_amount) : false;
		$stats['save_amount_max'] = $stats['onsale'] ? max($save_amount) : false;
		$stats['save_percent_min'] = $stats['onsale'] ? min($save_percent) : false;
		$stats['save_percent_max'] = $stats['onsale'] ? max($save_percent) : false;
	}
	if ( $mode == 'details' ) 
		return $details;
	else 
		return $stats;
}
function prima_category_image($category_id = null, $width = false, $height = false, $crop = true, $output = 'url' ) {
	$image = null;
	if($category_id < 1)
		$category_id = wpsc_category_id();
	$category_image = wpsc_get_categorymeta($category_id, 'image');
	$category_path = WPSC_CATEGORY_DIR.basename($category_image);
	$category_url = WPSC_CATEGORY_URL.basename($category_image);
	if(function_exists("gd_info") && file_exists($category_path) && is_file($category_path)) {
		if ( $width > 0 && $height > 0 ) {
			$vt_image = vt_resize2( basename($category_image), WPSC_CATEGORY_URL, WPSC_CATEGORY_DIR, $width, $height, $crop );
			if ($vt_image) 
				$image = $vt_image['url'];
			else
				$image = $category_url;
		}
	}
	if ( $image ) {
		if ( $output == 'url' ) {
			return $image;
		}
		elseif ( $output == 'image' ) {
			if ( $width && $height && $crop ) $style = 'style="width: '.$width.'px; height: '.$height.'px;"';
			$name = esc_attr(wpsc_category_name($category_id));
			return '<img class="wpsc_category_image" '.$style.' title="'.$name.'" alt="'.$name.'" src="'.$image.'">';
		}
	}
	return false;
}

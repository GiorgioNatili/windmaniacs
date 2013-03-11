<?php
// add_action( 'prima_scripts', 'prima_add_to_cart', 10000 );
function prima_add_to_cart() {
	ob_start();
	include_once( wpsc_get_template_file_path( 'wpsc-cart_widget.php' ) );
	$output = ob_get_contents();
	ob_end_clean();
	$output = str_replace( Array( "\n", "\r" ), '', trim($output) );
	$output = str_replace( "'", '"', $output );
	// $output = addslashes( $output );
	$items = sprintf( _n('%d item', '%d items', wpsc_cart_item_count(), PRIMA_DOMAIN), wpsc_cart_item_count() ); 
	echo 'jQuery(document).ready(function($){';
	echo "jQuery('div.shopping-cart-wrapper').html('$output');\n";
	echo "$('#cart-top a span.cartcount').text(\"".$items."\");";
	echo '});';
}
remove_action('template_redirect', 'wpsc_all_products_on_page');
add_action('template_redirect', 'prima_all_products_on_page');
function prima_all_products_on_page(){
	global $wp_query;
	global $prima_productspage_id, $prima_shoppingcart_id, $prima_transactionresults_id, $prima_userlog_id;
	$product_category = get_query_var( 'wpsc_product_category' );
	$product_tag = get_query_var ('product_tag' );
	$id = $wp_query->get_queried_object_id();
	if( get_query_var( 'post_type' ) == 'wpsc-product' || $product_category || $product_tag || ( $id && $id == $prima_productspage_id )){
		$templates = array();
		if ( $product_category && ! is_single() ) {
			array_push( $templates, "taxonomy-wpsc_product_category-{$product_category}.php", 'taxonomy-wpsc_product_category.php' );
		}
		if ( $product_tag && ! is_single() ) {
			array_push( $templates, "taxonomy-product_tag-{$product_tag}.php", 'taxonomy-product_tag.php' );
		}
		array_push( $templates, 'products.php', 'page.php', 'index.php' );
		if ( is_single() )
			array_unshift( $templates, 'single-wpsc-product.php' );
		// print_r($templates);
		$template = locate_template( $templates );
		// print_r($template);
		if ( !empty( $template ) && is_readable( $template ) ) {
			include_once( $template );
			exit;
		}
	}
	elseif ( ( $id && $id == $prima_shoppingcart_id ) || ( $id && $id == $prima_transactionresults_id ) || ( $id && $id == $prima_userlog_id ) ) {
		$template = locate_template( array( 'checkout.php' ) );
		if ( !empty( $template ) && is_readable( $template ) ) {
			include_once( $template );
			exit;
		}
	}
}
add_filter('search_template', 'prima_search_template');
function prima_search_template( $template ) {
	if (!isset($_GET['post_type'])) return $template;
	if ($_GET['post_type']!=='wpsc_product') return $template;
	$templates = array( 'search-wpsc-product.php', 'search.php', 'index.php' );
	return locate_template( $templates );
}
add_filter( 'pre_option_posts_per_page', 'prima_postsperpage_product_tag');
function prima_postsperpage_product_tag( $option ) {
	$product_tag = get_query_var ('product_tag' );
	if ( !$product_tag ) return $option;
	if ( get_option('wpsc_products_per_page') )
		return get_option('wpsc_products_per_page');
	else 
		return -1;
}
add_filter( 'pre_option_posts_per_page', 'prima_postsperpage_variations');
function prima_postsperpage_variations( $option ) {
	$variations = get_query_var ('variations' );
	if ( !$variations ) return $option;
	if ( get_option('wpsc_products_per_page') )
		return get_option('wpsc_products_per_page');
	else 
		return -1;
}
function prima_product_search() {
	$form = '<form role="search" method="get" class="productsearchform" action="' . home_url( '/' ) . '" >
	<div>
	<input type="text" value="'. esc_attr(__( 'Search Products &hellip;', PRIMA_DOMAIN )) .'" name="s" class="searchbox" />
	<input type="submit" class="searchsubmit" value="'. esc_attr(__( 'Search', PRIMA_DOMAIN )) .'" />
	<input type="hidden" name="post_type" value="wpsc_product" />
	</div>
	</form>';
	echo $form;
}
function prima_also_bought( $product_id, $number = 5, $width = 150, $height = 150 ) {
	if ( !$product_id ) return;
	if ( (int)$number <= 0 ) $number = 5;
	if ( (int)$width <= 10 ) $width = 150;
	if ( (int)$height <= 10 ) $height = 150;
	global $wpdb;
	$output = '';
	$q = "SELECT `" . $wpdb->posts . "`.* FROM `" . WPSC_TABLE_ALSO_BOUGHT . "`, `" . $wpdb->posts . "` WHERE `selected_product`='" . $product_id . "' AND `" . WPSC_TABLE_ALSO_BOUGHT . "`.`associated_product` = `" . $wpdb->posts . "`.`id` AND `" . $wpdb->posts . "`.`post_status` IN('publish','protected') ORDER BY `" . WPSC_TABLE_ALSO_BOUGHT . "`.`quantity` DESC LIMIT $number";
	$also_bought = $wpdb->get_results( $q, ARRAY_A );
	if ( count( $also_bought ) > 0 ) {
		$output .= '<div class="prima_cross_sales">';
		$output .= "<h3>" . __( 'People who bought this item also bought', PRIMA_DOMAIN ) . "</h3>";
		$output .= '<ul class="product-list clearfix">';
		foreach ( (array)$also_bought as $also_bought_data ) {
			$id = $also_bought_data['ID'];
			$output .= '<li class="product-listing" style="width: ' . $width . 'px;">';
            $output .= '<div class="padding">';
            $output .= '<div class="product-meta" style="width:'.$width.'px;height:'.$height.'px" >';
            $output .= '<div class="imagecol" style="margin:0;padding:0;text-align:center;width:'.$width.'px;height:'.$height.'px;line-height:'.$height.'px;">';
			$output .= '<a href="'.get_permalink($id).'">';
            $output .= '<img class="product_image" id="product_image_'.$id.'" alt="'.esc_attr(get_the_title($id)).'" src="'.prima_get_image( 0, $width, $height, true, $id ).'" />';
            $output .= '</a>';
            $output .= '</div><!--close imagecol-->';
			if ( !prima_get_option('singlehideprice') || !prima_get_option('singlehidesaleicon') ) :
				$variationprice = ( prima_get_option('singlevariationprice') == 'max' ) ? false : true;
				$pricedecimal = ( prima_get_option('singlehidedecimal') == 'hide' ) ? false : true;
				$output .= '<div class="wpsc_product_price">';
				if(prima_product_on_special($id)) :
					if ( !prima_get_option('singlehidesaleicon') ) :
						$output .= '<span class="sale-icon">'.__( 'Sale!', PRIMA_DOMAIN ).'</span>';
					endif;
					if ( !prima_get_option('singlehideprice') ) :
						$output .= '<span id="product_price_'.$id.'" class="pricedisplay sale-price">';
						$output .= prima_the_product_price( prima_get_option('singlevariationtext'), $pricedecimal, $id, $variationprice );
						$output .= '</span>';
					endif;
				else :
					if ( !prima_get_option('singlehideprice') ) :
						$output .= '<span id="product_price_'.$id.'" class="pricedisplay">';
						$output .= prima_the_product_price( prima_get_option('singlevariationtext'), $pricedecimal, $id, $variationprice );
						$output .= '</span>';
					endif;
				endif;
				$output .= '</div>';
			endif;
            $output .= '</div><!--close product-meta-->';
            $output .= '<h3 class="prodtitles">';
			$output .= '<a class="wpsc_product_title" href="'.get_permalink($id).'">'.get_the_title($id).'</a>';
            $output .= '</h3>';
			$output .= '</div><!--close padding-->';
			$output .= '</li>';
		}
		$output .= '</ul>';
		$output .= '</div>';
	}
	echo $output;
}
/** Get and return related products 
Credits: JigoShop plugin
*/
function prima_get_related( $product_id, $number = 5 ) {
	global $wpdb;
	// Related products are found from category and tag
	$tags_array = array(0);
	$cats_array = array(0);
	$tags = '';
	$cats = '';
	
	// Get tags
	$terms = wp_get_post_terms($product_id, 'product_tag');
	foreach ($terms as $term) {
		$tags_array[] = $term->term_id;
	}
	$tags = implode(',', $tags_array);
	
	$terms = wp_get_post_terms($product_id, 'wpsc_product_category');
	foreach ($terms as $term) {
		$cats_array[] = $term->term_id;
	}
	$cats = implode(',', $cats_array);

	$q = "
		SELECT p.ID
		FROM $wpdb->term_taxonomy AS tt, $wpdb->term_relationships AS tr, $wpdb->posts AS p
		WHERE 
			p.ID != $product_id
			AND p.post_status = 'publish'
			AND p.post_type = 'wpsc-product'
			AND
			(
				(
					tt.taxonomy ='wpsc_product_category'
					AND tt.term_taxonomy_id = tr.term_taxonomy_id
					AND tr.object_id  = p.ID
					AND tt.term_id IN ($cats)
				)
				OR 
				(
					tt.taxonomy ='product_tag'
					AND tt.term_taxonomy_id = tr.term_taxonomy_id
					AND tr.object_id  = p.ID
					AND tt.term_id IN ($tags)
				)
			)
		GROUP BY tr.object_id
		ORDER BY RAND()
		LIMIT $number;";

	$related = $wpdb->get_col($q);
	
	return $related;
}
function prima_related_products( $product_id, $number = 5, $width = 150, $height = 150 ) {
	if ( !$product_id ) return;
	if ( (int)$number <= 0 ) $number = 5;
	if ( (int)$width <= 10 ) $width = 150;
	if ( (int)$height <= 10 ) $height = 150;
	global $wpdb;
	$output = '';
	$related = prima_get_related( $product_id, $number );
	if (sizeof($related)>0) {
		$args=array(
			'post__in' => $related,
			'post__not_in' => array($product_id),
			'post_type'	=> 'wpsc-product',
			'ignore_sticky_posts'	=> 1,
			'posts_per_page' => $number,
			'orderby' => 'rand',
		);
		$related_query = new WP_Query($args);
		if ($related_query->have_posts()) :
			$output .= '<div class="prima_related_products">';
			$output .= "<h3>" . __( 'Related Products', PRIMA_DOMAIN ) . "</h3>";
			$output .= '<ul class="product-list clearfix">';
			while ($related_query->have_posts()) : $related_query->the_post();
				$id = get_the_ID();
				$output .= '<li class="product-listing" style="width: ' . $width . 'px;">';
				$output .= '<div class="padding">';
				$output .= '<div class="product-meta" style="width:'.$width.'px;height:'.$height.'px" >';
				$output .= '<div class="imagecol" style="margin:0;padding:0;text-align:center;width:'.$width.'px;height:'.$height.'px;line-height:'.$height.'px;">';
				$output .= '<a href="'.get_permalink().'">';
				$output .= '<img class="product_image" id="product_image_'.$id.'" alt="'.esc_attr(get_the_title()).'" src="'.prima_get_image( 0, $width, $height, true, $id ).'" />';
				$output .= '</a>';
				$output .= '</div><!--close imagecol-->';
				if ( !prima_get_option('singlehideprice') || !prima_get_option('singlehidesaleicon') ) :
					$variationprice = ( prima_get_option('singlevariationprice') == 'max' ) ? false : true;
					$pricedecimal = ( prima_get_option('singlehidedecimal') == 'hide' ) ? false : true;
					$output .= '<div class="wpsc_product_price">';
					if(prima_product_on_special($id)) :
						if ( !prima_get_option('singlehidesaleicon') ) :
							$output .= '<span class="sale-icon">'.__( 'Sale!', PRIMA_DOMAIN ).'</span>';
						endif;
						if ( !prima_get_option('singlehideprice') ) :
							$output .= '<span id="product_price_'.$id.'" class="pricedisplay sale-price">';
							$output .= prima_the_product_price( prima_get_option('singlevariationtext'), $pricedecimal, $id, $variationprice );
							$output .= '</span>';
						endif;
					else :
						if ( !prima_get_option('singlehideprice') ) :
							$output .= '<span id="product_price_'.$id.'" class="pricedisplay">';
							$output .= prima_the_product_price( prima_get_option('singlevariationtext'), $pricedecimal, $id, $variationprice );
							$output .= '</span>';
						endif;
					endif;
					$output .= '</div>';
				endif;
				$output .= '</div><!--close product-meta-->';
				$output .= '<h3 class="prodtitles">';
				$output .= '<a class="wpsc_product_title" href="'.get_permalink().'">'.get_the_title().'</a>';
				$output .= '</h3>';
				$output .= '</div><!--close padding-->';
				$output .= '</li>';
			endwhile;
			$output .= '</ul>';
			$output .= '</div>';
		endif;
		// wp_reset_query();
	}
	echo $output;
}

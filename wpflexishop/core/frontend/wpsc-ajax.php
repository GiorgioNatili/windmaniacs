<?php

    add_action( 'wpsc_alternate_cart_html', 'prima_update_cart_item_count' );
    function prima_update_cart_item_count(){
        if ( ($_REQUEST['wpsc_ajax_action'] == 'add_to_cart') && $_REQUEST['ajax'] == TRUE ) {
			$output = sprintf( _n('%d item', '%d items', wpsc_cart_item_count(), PRIMA_DOMAIN), wpsc_cart_item_count());
			echo "jQuery('#cart-top a span.cartcount').html('$output');";
			exit;
        }
    }
function prima_update_variation_stock() {
	global $wpdb, $wpsc_cart;
	$from = '';
	$change_price = true;
	foreach ( (array)$_POST['variation'] as $variation ) {
		if ( is_numeric( $variation ) ) {
			$variations[] = (int)$variation;
		}
	}
	$stock = wpsc_check_variation_stock_availability( (int)$_POST['product_id'], $variations );
	if ( is_numeric( $stock ) ) {
		if ( $stock > 0 ) {
			echo "variation_msg=\"" . sprintf( __('%s remaining stock', PRIMA_DOMAIN), $stock) . "\";\n";
			echo "variation_status= true \n";
		}
		else {
			echo "product_msg=\"" . __( 'Sorry, but this variation is out of stock.', PRIMA_DOMAIN ) . "\";\n";
			echo "variation_msg=\"" . __( 'Variation not in stock', PRIMA_DOMAIN ) . "\";\n";
			echo "variation_status= false \n";
		}
	}
	else{
		echo "variation_msg=\"" . $stock . __( 'Product in stock', PRIMA_DOMAIN ) . "\";\n";
		echo "variation_status= true \n";
	}
	exit();
}

// execute on POST and GET
if ( isset( $_REQUEST['update_variation_stock'] ) && ($_REQUEST['update_variation_stock'] == 'true') && is_numeric( $_POST['product_id'] ) ) {
	add_action( 'init', 'prima_update_variation_stock' );
}

function prima_update_variation_image() {
	global $wpdb, $wpsc_cart;
	$from = '';
	$change_price = true;
	foreach ( (array)$_POST['variation'] as $variation ) {
		if ( is_numeric( $variation ) ) {
			$variations[] = (int)$variation;
		}
	}
	$selected_post = (array)get_posts( array(
				'post_parent' => (int)$_POST['product_id'],
				'post_type' => "wpsc-product",
				'posts_per_page' => -1,
				'post_status' => 'all',
				'suppress_filters' => true
			) );
	foreach ( $selected_post as $variation ) {
		$matches = 0;
		$terms = wp_get_object_terms( $variation->ID, 'wpsc-variation' );
		foreach ( $terms as $term ) {
			if ( in_array( $term->term_id, $variations ) )
				$matches++;

			if ( $matches == count( $variations ) ) {
				$the_selected_product = $variation->ID;
			}
		}
	}
	if ( $the_selected_product ) {
		$crop = prima_get_option('singleuncropthumb') ? false : true;
		$image_width = get_option('single_view_image_width');
		$image_height = get_option('single_view_image_height');
		$full_img = prima_get_image( null, false, false, false, $the_selected_product );
		if ( !$full_img ) $full_img = prima_get_image( null, false, false, false, (int)$_POST['product_id'] );
		if ( !$full_img ) $full_img = PRIMA_IMAGES_URL.'/noimage.png';
		$single_img = prima_get_image( null, $image_width, $image_height, $crop, $the_selected_product );
		if ( !$single_img ) $single_img = prima_get_image( null, $image_width, $image_height, $crop, (int)$_POST['product_id'] );
		if ( !$single_img ) $single_img = PRIMA_IMAGES_URL.'/noimage.png';
		$img_title = esc_attr(get_the_title($the_selected_product));
		echo "variation_img_title=\"" . $img_title . "\";\n";
		echo "variation_full_img=\"" . $full_img . "\";\n";
		echo "variation_single_img=\"" . $single_img . "\"\n";
	}
	exit();
}

// execute on POST and GET
if ( isset( $_REQUEST['update_variation_image'] ) && ($_REQUEST['update_variation_image'] == 'true') && is_numeric( $_POST['product_id'] ) ) {
	add_action( 'init', 'prima_update_variation_image' );
}

function prima_update_single_image() {
	$image_id = str_replace( 'image_thumb_', '', $_POST['image_id'] );
	if ( (int)$image_id > 0 ) {
		$crop = prima_get_option('singleuncropthumb') ? false : true;
		$image_width = get_option('single_view_image_width');
		$image_height = get_option('single_view_image_height');
		$full_img = prima_get_image( (int)$image_id, false, false, false );
		$single_img = prima_get_image( (int)$image_id, $image_width, $image_height, $crop );
		$img_title = esc_attr(get_the_title((int)$image_id));
		echo "product_img_title=\"" . $img_title . "\";\n";
		echo "product_full_img=\"" . $full_img . "\";\n";
		echo "product_single_img=\"" . $single_img . "\"\n";
	}
	exit();
}

// execute on POST and GET
if ( isset( $_REQUEST['update_single_image'] ) && ($_REQUEST['update_single_image'] == 'true') && isset( $_POST['image_id'] ) ) {
	add_action( 'init', 'prima_update_single_image' );
}
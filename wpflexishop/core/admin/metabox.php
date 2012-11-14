<?php
add_action("admin_init", "prima_add_product_meta_box");
function prima_add_product_meta_box(){

    if(current_user_can('manage_options')){

        add_meta_box("prima-product-meta-box", __( "Custom Settings", PRIMA_DOMAIN ), "prima_product_meta_box", "wpsc-product", "normal", "high");

    }
}
function prima_product_meta_box() {
?>
	<input type="hidden" name="prima_product_meta_box_nonce" value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
	<p>
		<input type="checkbox" name="prima_product[_prima_hide_image]" id="prima_product[_prima_hide_image]" value="yes" <?php checked('yes', prima_get_custom_field('_prima_hide_image')); ?> /> 
		<label for="prima_product[_prima_hide_image]"><?php _e("Hide product images in single product page.", PRIMA_DOMAIN); ?></label><br/>
		<span class="description"><?php _e("<b>NOTE:</b> It is useful when you replace product images with custom content. Ex: video", PRIMA_DOMAIN); ?></span>
	</p>
	<p><?php _e("Enter custom content you would like output to the top of product images in single product page:", PRIMA_DOMAIN); ?><br />
	<textarea name="prima_product[_prima_content_top]" cols="39" rows="5"><?php prima_custom_field('_prima_content_top'); ?></textarea><br />
	<span class="description"><?php _e('<b>NOTE:</b>  You can use any shortcode / HTML code.', PRIMA_DOMAIN); ?></span></p>
	<p><?php _e("Enter custom content you would like output to the bottom of product images in single product page:", PRIMA_DOMAIN); ?><br />
	<textarea name=prima_product[_prima_content_bottom]" cols="39" rows="5"><?php prima_custom_field('_prima_content_bottom'); ?></textarea><br />
	<span class="description"><?php _e('<b>NOTE:</b>  You can use any shortcode / HTML code.', PRIMA_DOMAIN); ?></span></p>
<?php
}
add_action('save_post', 'prima_product_meta_box_save', 1, 2);
function prima_product_meta_box_save($post_id, $post) {
	//	verify the nonce
	if ( !isset($_POST['prima_product_meta_box_nonce']) || !wp_verify_nonce( $_POST['prima_product_meta_box_nonce'], plugin_basename(__FILE__) ) )
		return $post->ID;
	//	don't try to save the data under autosave, ajax, or future post.
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
	if ( defined('DOING_AJAX') && DOING_AJAX ) return;
	if ( defined('DOING_CRON') && DOING_CRON ) return;
	//	is the user allowed to edit the post or page?
	if ( ('page' == $_POST['post_type'] && !current_user_can('edit_page', $post->ID)) || !current_user_can('edit_post', $post->ID ) )
		return $post->ID;
	$product_defaults = array(
		'_prima_hide_image' => '',
		'_prima_content_top' => '',
		'_prima_content_bottom' => ''
	); 
	$product = wp_parse_args($_POST['prima_product'], $product_defaults);
	//	store the custom fields
	foreach ( (array)$product as $key => $value ) {
		if ( $post->post_type == 'revision' ) return; // don't try to store data during revision save
		//	sanitize the url before storage
		if ( $key == 'link' && $value ) $value = esc_url( $value );
		if ( $value )
			update_post_meta($post->ID, $key, $value);
		else
			delete_post_meta($post->ID, $key);
	}
}
add_action("admin_init", "prima_add_page_meta_box");
function prima_add_page_meta_box(){
	add_meta_box("prima-page-meta-box", __( "Page Template Settings", PRIMA_DOMAIN ), "prima_page_meta_box", "page", "normal", "high");
}
function prima_page_meta_box() {
?>
	<input type="hidden" name="prima_page_meta_box_nonce" value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
	<h4><?php _e( 'Products Slider Page Template Options', PRIMA_DOMAIN ); ?></h4>
	<p>
		<label><?php _e( 'Products IDs (separared by coma):', PRIMA_DOMAIN ); ?></label>
		<input type="text" name="prima_page[_prima_products]" value="<?php echo prima_custom_field("_prima_products"); ?>" class="widefat" />
	</p>
	<hr class="div"/>
	<h4><?php _e( 'Video Page Template Options', PRIMA_DOMAIN ); ?></h4>
	<p>
		<label><?php _e( 'Video Embed Code / Video Shortcode:', PRIMA_DOMAIN ); ?></label><br/>
		<textarea  class="widefat" name="prima_page[_prima_video]" cols="39" rows="5"><?php prima_custom_field('_prima_video'); ?></textarea>
	</p>
<?php
}
add_action('save_post', 'prima_page_meta_box_save', 1, 2);
function prima_page_meta_box_save($post_id, $post) {
	//	verify the nonce
	if ( !isset($_POST['prima_page_meta_box_nonce']) || !wp_verify_nonce( $_POST['prima_page_meta_box_nonce'], plugin_basename(__FILE__) ) )
		return $post->ID;
	//	don't try to save the data under autosave, ajax, or future post.
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
	if ( defined('DOING_AJAX') && DOING_AJAX ) return;
	if ( defined('DOING_CRON') && DOING_CRON ) return;
	//	is the user allowed to edit the post or page?
	if ( ('page' == $_POST['post_type'] && !current_user_can('edit_page', $post->ID)) || !current_user_can('edit_post', $post->ID ) )
		return $post->ID;
	$page_defaults = array(
		'_prima_products' => '',
		'_prima_video' => ''
	); 
	$page = wp_parse_args($_POST['prima_page'], $page_defaults);
	//	store the custom fields
	foreach ( (array)$page as $key => $value ) {
		if ( $post->post_type == 'revision' ) return; // don't try to store data during revision save
		//	sanitize the url before storage
		if ( $key == 'link' && $value ) $value = esc_url( $value );
		if ( $value )
			update_post_meta($post->ID, $key, $value);
		else
			delete_post_meta($post->ID, $key);
	}
}
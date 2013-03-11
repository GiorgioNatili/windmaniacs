<?php
add_action( 'init', 'prima_promotion_register' );
function prima_promotion_register() {
	register_post_type( 'promotion',
		array(
			'labels' => array(
			'name' => __('Promotions', PRIMA_DOMAIN ),
			'singular_name' => __('Shop Promotion', PRIMA_DOMAIN ),
			'add_new' => __('Add New', PRIMA_DOMAIN ),
			'add_new_item' => __('Add New Shop Promotion', PRIMA_DOMAIN ),
			'edit_item' => __('Edit Shop Promotion', PRIMA_DOMAIN ),
			'new_item' => __('New Shop Promotion', PRIMA_DOMAIN ),
			'view_item' => __('View Shop Promotion', PRIMA_DOMAIN ),
			'search_items' => __('Search Promotions', PRIMA_DOMAIN ),
			'not_found' =>  __('Nothing found', PRIMA_DOMAIN ),
			'not_found_in_trash' => __('Nothing found in Trash', PRIMA_DOMAIN ),
			'parent_item_colon' => ''
		),
		'public' => true,
		'rewrite' => array('slug' => 'promotions'),
		'show_ui' => true,
		'exclude_from_search' => true,
		'capability_type' => 'post',
		'supports' => array('title', 'thumbnail', 'excerpt')
		)
	);
}
add_action( 'do_meta_boxes', 'prima_add_promotion_image_box' );
function prima_add_promotion_image_box() {
	remove_meta_box( 'postimagediv', 'promotion', 'side' );
	add_meta_box( 'postimagediv', __('Promotion Image', PRIMA_DOMAIN), 'post_thumbnail_meta_box', 'promotion', 'normal', 'high' );
}
add_action("admin_init", "prima_add_promotion_meta_box");
function prima_add_promotion_meta_box(){
	add_meta_box("prima-promotion-meta-box", __( "Promotion Details", PRIMA_DOMAIN ), "prima_promotion_meta_box", "promotion", "normal", "low");
}
function prima_promotion_meta_box() {
	$link_type_options = array();
	if(class_exists('WP_eCommerce')) {
		$link_type_options['product'] = array(
				'value' => 'product',
				'label' => __( 'Link To Product', PRIMA_DOMAIN )
			);
		$link_type_options['category'] = array(
				'value' => 'category',
				'label' => __( 'Link To Category', PRIMA_DOMAIN )
			);
	}
	$link_type_options['external'] = array(
			'value' => 'external',
			'label' => __( 'External Link', PRIMA_DOMAIN )
		);
	$lightbox_options = array(
		'enable' => array(
		'value' => 'yes',
		'label' => __( 'Enable Lightbox', PRIMA_DOMAIN )
		),
		'disable' => array(
		'value' => 'no',
		'label' => __( 'Disable Lightbox', PRIMA_DOMAIN )
		)
	);
	$display_options = array(
		'enable' => array(
		'value' => 'yes',
		'label' => __( 'Show Promotion Details', PRIMA_DOMAIN )
		),
		'disable' => array(
		'value' => 'no',
		'label' => __( 'Hide Promotion Details', PRIMA_DOMAIN )
		)
	);
	?>
	<input type="hidden" name="prima_promotion_nonce" value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
	<div class="input radio">
		<?php
		foreach ( $link_type_options as $option ) {
			if ( prima_get_custom_field("link_type") && (prima_get_custom_field("link_type")==$option['value']) )
				$checked = "checked=\"checked\"";
			else
				$checked = '';
			?>
			<label class="description">
				<input type="radio" name="promotion[link_type]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> class="con-check" /> 
				<?php echo $option['label']; ?>
			</label>
		<?php } ?>
	</div>
	<div id="external" class="text hidden-field">
		<div class="input">
			<label><?php _e( 'Links To External:', PRIMA_DOMAIN ); ?></label>
			<input type="text" size="50" name="promotion[link]" value="<?php prima_custom_field("link"); ?>" />
		</div>
		<div class="input radio">
		<p><?php _e( 'Lightbox works for images, Vimeo, and Youtube links', PRIMA_DOMAIN ); ?> </p>
		<?php 
		foreach ( $lightbox_options as $option ) {
			if ( prima_get_custom_field("pretty_photo") && (prima_get_custom_field("pretty_photo")==$option['value']) )
				$checked = "checked=\"checked\"";
			else
				$checked = '';
			?>
			<label class="description">
				<input type="radio" name="promotion[pretty_photo]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> /> 
				<?php echo $option['label']; ?>
			</label>
		<?php } ?>
		</div>
	</div>
	<?php if(class_exists('WP_eCommerce')) { ?>
	<?php 
	$num_posts = wp_count_posts('wpsc-product')->publish; 
	if ( $num_posts > 200 ) :
	?>
	<div id="product" class="input text hidden-field">
		<label><?php _e( 'Links To Product:', PRIMA_DOMAIN ); ?> <?php _e( '(enter product link here)', PRIMA_DOMAIN ); ?></label>
		<input type="text" name="promotion[promotion_link]" value="<?php echo prima_custom_field("promotion_link"); ?>" />
	</div>
	<?php else : ?>
	<div id="product" class="input select hidden-field">
		<label><?php _e( 'Links To Product:', PRIMA_DOMAIN ); ?></label>
		<select name="promotion[promotion_link]">
			<?php
			$posts = &get_posts( array( 'post_type' => 'wpsc-product', 'numberposts' => 200, 'order' => 'ASC' ) );
			if ( $posts ) {
				// echo '<option value="">'.__('-- Choose One --', PRIMA_DOMAIN).'</option>';
				foreach ( $posts as $post ) {
					$promotion_link = prima_get_custom_field("promotion_link");
					$currentlink = str_replace("&amp;", "&", get_permalink($post->ID)); 
					$selected = ( $promotion_link && $promotion_link == $currentlink ) ? ' selected="selected"' : '';
					echo '<option value="'.get_permalink($post->ID).'"'.$selected.'>'.$post->post_title.'</option>';
				}
			} 
			else {
				echo '<option value="">'.__('No Product Available', PRIMA_DOMAIN).'</option>';
			}
			?>
		</select>
	</div>
	<?php endif; ?>
	<div id="category" class="input select hidden-field">
		<label><?php _e( 'Links To Category:', PRIMA_DOMAIN ); ?></label>
		<select name="promotion[promotion_link_category]">
			<?php
			$categories = &get_categories( array( 'taxonomy' => 'wpsc_product_category', 'hide_empty' => false ) );
			if ( $categories ) {
				// echo '<option value="">'.__('-- Choose One --', PRIMA_DOMAIN).'</option>';
				foreach ($categories as $category) {
					$promotion_link_category = prima_get_custom_field("promotion_link_category");
					$url = str_replace("&amp;", "&", get_term_link( $category, $taxonomy = 'wpsc_product_category')); 
					$selected = ( $promotion_link_category && $promotion_link_category == $url ) ? ' selected="selected"' : '';
					echo '<option value="'.get_term_link( $category, $taxonomy = 'wpsc_product_category').'"'.$selected.'>'.$category->name.'</option>';
				}
			} 
			else {
				echo '<option value="">'.__('No Product Category Available', PRIMA_DOMAIN).'</option>';
			}
			?>
		</select>
	</div>
	<div class="input text">
		<label><?php _e( 'Savings:', PRIMA_DOMAIN ); ?></label>
		<input type="text" name="promotion[saving]" value="<?php echo prima_custom_field("saving"); ?>" />
	</div>
	<div class="input text">
		<label><?php _e( 'Start Date:', PRIMA_DOMAIN ); ?></label>
		<input type="text" name="promotion[start_date]" value="<?php echo prima_custom_field("start_date"); ?>" class="date-input" />
	</div>
	<div class="input text">
		<label><?php _e( 'End Date:', PRIMA_DOMAIN ); ?></label>
		<input type="text" name="promotion[end_date]" value="<?php echo prima_custom_field("end_date"); ?>" class="date-input" />
	</div>
	<div class="input radio">
	<p><?php _e( 'You can show/hide promotion details (savings, start date, end date) in the slider', PRIMA_DOMAIN ); ?></p> 
	<?php 
	foreach ( $display_options as $option ) {
		if ( prima_get_custom_field("display_text") && (prima_get_custom_field("display_text")==$option['value']) )
			$checked = "checked=\"checked\"";
		else
			$checked = '';
		?>
		<label class="description">
			<input type="radio" name="promotion[display_text]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> /> 
			<?php echo $option['label']; ?>
		</label>
	<?php } ?>
	</div>
	<?php } ?>
<?php
}
add_filter("manage_edit-promotion_columns", "prima_promotion_columns");
function prima_promotion_columns($columns){
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => __( "Promotion Title", PRIMA_DOMAIN ),
		"meta-image" => __( "Image", PRIMA_DOMAIN ),
		"meta-link_type" => __( "Links Type", PRIMA_DOMAIN ),
		"meta-saving" => __( "Saving", PRIMA_DOMAIN ),
		"meta-start_date" => __( "Start Date", PRIMA_DOMAIN ),
		"meta-end_date" => __( "End Date", PRIMA_DOMAIN ),
	);
	return $columns;
}
add_action("manage_posts_custom_column",  "prima_promotion_custom_column");
function prima_promotion_custom_column($column){
	$link_type_options = array(
		'product' => array(
			'value' => 'product',
			'label' => __( 'Link To Product', PRIMA_DOMAIN )
		),
		'category' => array(
			'value' => 'category',
			'label' => __( 'Link To Product Category', PRIMA_DOMAIN )
		),
		'external' => array(
			'value' => 'external',
			'label' => __( 'External Link', PRIMA_DOMAIN )
		)
	);
	global $post;
	switch ($column) {
		case "meta-image":
			if( isset( $post->ID ) && has_post_thumbnail( $post->ID ) ) {
				$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
				$image = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail' );
				echo '<img src="'.$image[0].'" width="45" height="45" />';
			}
			break;
		case "meta-link_type":
			if (prima_get_custom_field("link_type")=='product') $link = prima_get_custom_field("promotion_link");
			elseif (prima_get_custom_field("link_type")=='category') $link = prima_get_custom_field("promotion_link_category");
			elseif (prima_get_custom_field("link_type")=='external') $link = prima_get_custom_field("link");
			if ($link)
				echo '<a href="'.$link.'">'.$link_type_options[prima_get_custom_field("link_type")]['label'].'</a>';
			break;
		case "meta-saving":
			prima_custom_field("saving");
			break;
		case "meta-end_date":
			prima_custom_field("end_date");
			break;
		case "meta-start_date":
			prima_custom_field("start_date");
			break;
	}
}
add_action('save_post', 'prima_promotion_save', 1, 2);
function prima_promotion_save($post_id, $post) {
	//	verify the nonce
	if ( !isset($_POST['prima_promotion_nonce']) || !wp_verify_nonce( $_POST['prima_promotion_nonce'], plugin_basename(__FILE__) ) )
		return $post->ID;
	//	don't try to save the data under autosave, ajax, or future post.
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
	if ( defined('DOING_AJAX') && DOING_AJAX ) return;
	if ( defined('DOING_CRON') && DOING_CRON ) return;
	//	is the user allowed to edit the post or page?
	if ( ('page' == $_POST['post_type'] && !current_user_can('edit_page', $post->ID)) || !current_user_can('edit_post', $post->ID ) )
		return $post->ID;
	$promotion_defaults = array(
		'link' => '',
		'saving' => '',
		'start_date' => '',
		'end_date' => '',
		'link_type' => '',
		'promotion_link' => '',
		'promotion_link_category' => '',
		'pretty_photo' => '',
		'display_text' => ''
	); 
	$promotion = wp_parse_args($_POST['promotion'], $promotion_defaults);
	//	store the custom fields
	foreach ( (array)$promotion as $key => $value ) {
		if ( $post->post_type == 'revision' ) return; // don't try to store data during revision save
		//	sanitize the url before storage
		if ( $key == 'link' && $value ) $value = esc_url( $value );
		if ( $value )
			update_post_meta($post->ID, $key, $value);
		else
			delete_post_meta($post->ID, $key);
	}
}

<?php
add_action( 'init', 'prima_slider_register' );
function prima_slider_register() {
	register_post_type( 'slider',
		array(
			'labels' => array(
			'name' => __('Sliders', PRIMA_DOMAIN ),
			'singular_name' => __('Slider', PRIMA_DOMAIN ),
			'add_new' => __('Add New', PRIMA_DOMAIN ),
			'add_new_item' => __('Add New Slider', PRIMA_DOMAIN ),
			'edit_item' => __('Edit Slider', PRIMA_DOMAIN ),
			'new_item' => __('New Slider', PRIMA_DOMAIN ),
			'view_item' => __('View Slider', PRIMA_DOMAIN ),
			'search_items' => __('Search Sliders', PRIMA_DOMAIN ),
			'not_found' =>  __('Nothing found', PRIMA_DOMAIN ),
			'not_found_in_trash' => __('Nothing found in Trash', PRIMA_DOMAIN ),
			'parent_item_colon' => ''
		),
		'public' => true,
		'rewrite' => array('slug' => 'sliders'),
		'show_ui' => true,
		'exclude_from_search' => true,
		'capability_type' => 'post',
		'supports' => array('title', 'thumbnail')
		)
	);
}
add_action("admin_init", "prima_add_slider_meta_box");
function prima_add_slider_meta_box(){
	add_meta_box("prima-slider-meta-box", __( "Slider Type", PRIMA_DOMAIN ), "prima_slider_meta_box", "slider", "normal", "low");
}
add_action( 'do_meta_boxes', 'prima_add_slider_image_box' );
function prima_add_slider_image_box() {
	remove_meta_box( 'postimagediv', 'slider', 'side' );
	add_meta_box( 'postimagediv', __('Slider Image for "Simple Slider" type', PRIMA_DOMAIN), 'post_thumbnail_meta_box', 'slider', 'normal', 'low' );
}
function prima_slider_meta_box() {
	$slider_type_options = array();
	$slider_type_options['simpleslider'] = array(
			'value' => 'simpleslider',
			'label' => __( 'Simple Slider', PRIMA_DOMAIN )
		);
	if(class_exists('WP_eCommerce')) {
		$slider_type_options['product'] = array(
				'value' => 'product',
				'label' => __( 'Product', PRIMA_DOMAIN )
			);
	}
	$slider_type_options['news'] = array(
			'value' => 'news',
			'label' => __( 'Post / Page', PRIMA_DOMAIN )
		);
	$slider_type_options['promotion'] = array(
			'value' => 'promotion',
			'label' => __( 'Promotion', PRIMA_DOMAIN )
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
	?>
	<input type="hidden" name="prima_slider_nonce" value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />

    <div class="input radio">
		<?php
		foreach ( $slider_type_options as $option ) {
			if ( prima_get_custom_field("sliderType") && (prima_get_custom_field("sliderType")==$option['value']) )
				$checked = "checked=\"checked\"";
			else
				$checked = '';
			?>
			<label class="description">
				<input type="radio" name="slider[sliderType]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> class="con-check" /> 
				<?php echo $option['label']; ?>
			</label>
		<?php } ?>
    </div>
	
	<div id="simpleslider" class="text hidden-field">
		<div class="input">
			<label><?php _e( 'Slider Link:', PRIMA_DOMAIN ); ?></label>
			<input type="text" size="50" name="slider[link]" value="<?php prima_custom_field("link"); ?>" />
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
				<input type="radio" name="slider[pretty_photo]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> /> 
				<?php echo $option['label']; ?>
			</label>
		<?php } ?>
		</div>
	</div>

	<?php if(class_exists('WP_eCommerce')) : ?>
	<?php 
	$num_posts = wp_count_posts('wpsc-product')->publish; 
	if ( $num_posts > 200 ) :
	?>
		<div id="product" class="input text hidden-field">
			<label><?php _e( 'Product:', PRIMA_DOMAIN ); ?> <?php _e( '(enter product ID here)', PRIMA_DOMAIN ); ?></label>
			<input type="text" name="slider[sliderProductId]" value="<?php echo prima_custom_field("sliderProductId"); ?>" />
		</div>
	<?php else : ?>
		<div id="product" class="input select hidden-field">
			<label><?php _e( 'Product:', PRIMA_DOMAIN ); ?></label>
			<select name="slider[sliderProductId]">
				<?php
				$posts = &get_posts( array( 'post_type' => 'wpsc-product', 'numberposts' => 200, 'orderby' => 'title', 'order' => 'ASC' ) );
				if ( $posts ) {
					// echo '<option value="">'.__('-- Choose One --', PRIMA_DOMAIN).'</option>';
					foreach ( $posts as $post ) {
						$slider_post_id = prima_get_custom_field("sliderProductId");
						$selected = ( $slider_post_id && $slider_post_id == $post->ID ) ? ' selected="selected"' : '';
						echo '<option value="'.$post->ID.'"'.$selected.'>'.$post->post_title.'</option>';
					}
				} 
				else {
					echo '<option value="">'.__('No Product Available', PRIMA_DOMAIN).'</option>';
				}
				?>
			</select>
		</div>
	<?php endif; ?>
	<?php endif; ?>
	
	<?php 
	$num_posts = wp_count_posts('post')->publish + wp_count_posts('page')->publish ; 
	if ( $num_posts > 200 ) :
	?>
		<div id="news" class="input text hidden-field">
			<label><?php _e( 'Post/Page:', PRIMA_DOMAIN ); ?> <?php _e( '(enter post/page ID here)', PRIMA_DOMAIN ); ?></label>
			<input type="text" name="slider[sliderPostId]" value="<?php echo prima_custom_field("sliderPostId"); ?>" />
		</div>
	<?php else : ?>
		<div id="news" class="input select hidden-field">
			<label><?php _e( 'Post:', PRIMA_DOMAIN ); ?></label>
			<select name="slider[sliderPostId]">
				<?php
				$posts = &get_posts( array( 'post_type' => array('post','page'), 'numberposts' => 200, 'orderby' => 'title', 'order' => 'ASC' ) );
				if ( $posts ) {
					// echo '<option value="">'.__('-- Choose One --', PRIMA_DOMAIN).'</option>';
					foreach ( $posts as $post ) {
						$slider_post_id = prima_get_custom_field("sliderPostId");
						$selected = ( $slider_post_id && $slider_post_id == $post->ID ) ? ' selected="selected"' : '';
						echo '<option value="'.$post->ID.'"'.$selected.'>'.$post->post_title.'</option>';
					}
				} 
				else {
					echo '<option value="">'.__('No Post/Page Available', PRIMA_DOMAIN).'</option>';
				}
				?>
			</select>
		</div>
	<?php endif; ?>
	
	<?php 
	$num_posts = wp_count_posts('promotion')->publish; 
	if ( $num_posts > 200 ) :
	?>
		<div id="promotion" class="input text hidden-field">
			<label><?php _e( 'Promotion:', PRIMA_DOMAIN ); ?> <?php _e( '(enter promotion ID here)', PRIMA_DOMAIN ); ?></label>
			<input type="text" name="slider[sliderPromotionId]" value="<?php echo prima_custom_field("sliderPromotionId"); ?>" />
		</div>
	<?php else : ?>
		<div id="promotion" class="input select hidden-field">
			<label><?php _e( 'Promotion:', PRIMA_DOMAIN ); ?></label>
			<select name="slider[sliderPromotionId]">
				<?php
				$posts = &get_posts( array( 'post_type' => 'promotion', 'numberposts' => 200, 'orderby' => 'title', 'order' => 'ASC' ) );
				if ( $posts ) {
					// echo '<option value="">'.__('-- Choose One --', PRIMA_DOMAIN).'</option>';
					foreach ( $posts as $post ) {
						$slider_post_id = prima_get_custom_field("sliderPromotionId");
						$selected = ( $slider_post_id && $slider_post_id == $post->ID ) ? ' selected="selected"' : '';
						echo '<option value="'.$post->ID.'"'.$selected.'>'.$post->post_title.'</option>';
					}
				} 
				else {
					echo '<option value="">'.__('No Promotion Available', PRIMA_DOMAIN).'</option>';
				}
				?>
			</select>
		</div>
	<?php endif; ?>
<?php
}
add_filter("manage_edit-slider_columns", "prima_slider_columns");
function prima_slider_columns($columns){
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => __( "Slider Title", PRIMA_DOMAIN ),
		"slider_type" => __( "Slider Type", PRIMA_DOMAIN ),
		"slider_name" => __( "Slider Name", PRIMA_DOMAIN ),
		"slider_id" => __( "Slider ID", PRIMA_DOMAIN ),
	);
	return $columns;
}
add_action("manage_posts_custom_column",  "prima_slider_custom_column");
function prima_slider_custom_column($column){
	$slider_type_options = array(
		'simpleslider' => array(
			'value' => 'simpleslider',
			'label' => __( 'Simple Slider', PRIMA_DOMAIN )
		),
		'product' => array(
			'value' => 'product',
			'label' => __( 'Product', PRIMA_DOMAIN )
		),
		'news' => array(
			'value' => 'news',
			'label' => __( 'Post / Page', PRIMA_DOMAIN )
		),
		'promotion' => array(
			'value' => 'promotion',
			'label' => __( 'Promotion', PRIMA_DOMAIN )
		)
	);
	global $post;
	if (prima_get_custom_field("sliderType")=='product') $postid = prima_get_custom_field("sliderProductId");
	elseif (prima_get_custom_field("sliderType")=='promotion') $postid = prima_get_custom_field("sliderPromotionId");
	elseif (prima_get_custom_field("sliderType")=='news') $postid = prima_get_custom_field("sliderPostId");
	else $postid = $post->ID;
	switch ($column) {
		case "slider_type":
			echo $slider_type_options[prima_get_custom_field("sliderType")]['label'];
			break;
		case "slider_name":
			
			echo '<a href="'.get_permalink($postid).'">'.get_post_field( 'post_title', $postid ).'</a>';
			break;
		case "slider_id":
			echo $postid;
			break;
	}
}
add_action('save_post', 'prima_slider_save', 1, 2);
function prima_slider_save($post_id, $post) {
	//	verify the nonce
	if ( !isset($_POST['prima_slider_nonce']) || !wp_verify_nonce( $_POST['prima_slider_nonce'], plugin_basename(__FILE__) ) )
		return $post->ID;
	//	don't try to save the data under autosave, ajax, or future post.
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
	if ( defined('DOING_AJAX') && DOING_AJAX ) return;
	if ( defined('DOING_CRON') && DOING_CRON ) return;
	//	is the user allowed to edit the post or page?
	if ( ('page' == $_POST['post_type'] && !current_user_can('edit_page', $post->ID)) || !current_user_can('edit_post', $post->ID ) )
		return $post->ID;
	$slider_defaults = array(
		'sliderType' => '',
		'sliderProductId' => '',
		'sliderPromotionId' => '',
		'sliderPostId' => '',
		'link' => '',
		'pretty_photo' => ''
	); 
	$slider = wp_parse_args($_POST['slider'], $slider_defaults);
	//	store the custom fields
	foreach ( (array)$slider as $key => $value ) {
		if ( $post->post_type == 'revision' ) return; // don't try to store data during revision save
		//	sanitize the url before storage
		if ( $key == 'link' && $value ) $value = esc_url( $value );
		if ( $value )
			update_post_meta($post->ID, $key, $value);
		else
			delete_post_meta($post->ID, $key);
	}
}

<?php
add_action( 'init', 'prima_testimonial_register' );
function prima_testimonial_register() {
	register_post_type( 'testimonial',
		array(
			'labels' => array(
			'name' => __('Testimonials', PRIMA_DOMAIN ),
			'singular_name' => __('Testimonial', PRIMA_DOMAIN ),
			'add_new' => __('Add New', PRIMA_DOMAIN ),
			'add_new_item' => __('Add New Testimonial', PRIMA_DOMAIN ),
			'edit_item' => __('Edit Testimonial', PRIMA_DOMAIN ),
			'new_item' => __('New Testimonial', PRIMA_DOMAIN ),
			'view_item' => __('View Testimonial', PRIMA_DOMAIN ),
			'search_items' => __('Search Testimonials', PRIMA_DOMAIN ),
			'not_found' =>  __('Nothing found', PRIMA_DOMAIN ),
			'not_found_in_trash' => __('Nothing found in Trash', PRIMA_DOMAIN ),
			'parent_item_colon' => ''
		),
		'public' => true,
		'rewrite' => array('slug' => 'testimonials'),
		'show_ui' => true,
		'exclude_from_search' => true,
		'capability_type' => 'post',
		'supports' => array('title','editor')
		)
	);
}
add_action("admin_init", "prima_add_testimonial_meta_box");
function prima_add_testimonial_meta_box(){
	add_meta_box("prima-testimonial-meta-box", __( "Testimonial Details", PRIMA_DOMAIN ), "prima_testimonial_meta_box", "testimonial", "normal", "low");
}
function prima_testimonial_meta_box() {
	?>
	<input type="hidden" name="prima_testimonial_nonce" value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
	<div class="input text">
		<label><?php _e( 'Testimonial Author:', PRIMA_DOMAIN ); ?></label>
		<input type="text" name="testimonial[testimonial_author]" value="<?php prima_custom_field("testimonial_author"); ?>" />
	</div>
<?php
}
add_filter("manage_edit-testimonial_columns", "prima_testimonial_columns");
function prima_testimonial_columns($columns){
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => __( "Testimonial Title", PRIMA_DOMAIN ),
		"testimonial_author" => __( "Testimonial Author", PRIMA_DOMAIN )
	);
	return $columns;
}
add_action("manage_posts_custom_column",  "prima_testimonial_custom_column");
function prima_testimonial_custom_column($column){
	global $post;
	switch ($column) {
		case "testimonial_author":
		echo prima_get_custom_field("testimonial_author");
		break;
	}
}
add_action('save_post', 'prima_testimonial_save', 1, 2);
function prima_testimonial_save($post_id, $post) {
	//	verify the nonce
	if ( !isset($_POST['prima_testimonial_nonce']) || !wp_verify_nonce( $_POST['prima_testimonial_nonce'], plugin_basename(__FILE__) ) )
		return $post->ID;
	//	don't try to save the data under autosave, ajax, or future post.
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
	if ( defined('DOING_AJAX') && DOING_AJAX ) return;
	if ( defined('DOING_CRON') && DOING_CRON ) return;
	//	is the user allowed to edit the post or page?
	if ( ('page' == $_POST['post_type'] && !current_user_can('edit_page', $post->ID)) || !current_user_can('edit_post', $post->ID ) )
		return $post->ID;
	$testimonial_defaults = array(
		'testimonial_author' => ''
	); 
	$testimonial = wp_parse_args($_POST['testimonial'], $testimonial_defaults);
	//	store the custom fields
	foreach ( (array)$testimonial as $key => $value ) {
		if ( $post->post_type == 'revision' ) return; // don't try to store data during revision save
		//	sanitize the url before storage
		if ( $key == 'link' ) $value = esc_url( $value );
		if ( $value )
			update_post_meta($post->ID, $key, $value);
		else
			delete_post_meta($post->ID, $key);
	}
}

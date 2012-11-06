<?php
global $wp_query, $product_page_active, $product_page_width, $product_sidebar, $product_sidebar_width;
$product_sidebar = prima_get_option('productssidebar');
if (prima_get_option('themelayout') == 'boxed') {
	$product_page_width = 920;
	$product_sidebar_width = 176;
}
else {
	$product_page_width = 980;
	$product_sidebar_width = 188;
}
$product_page_active = true;

get_header();
?>
<!--HEREEEEE-->
<div id="header-wrapper">
	<?php get_template_part( 'flexi-header' ); ?>
    <div id="leader" class="container">
        <div class="margin clearfix">
			<?php if (class_exists('WP_eCommerce') && prima_get_option('searchformproducts')) prima_product_search(); ?>
			<?php 
			if ( is_category() || is_tag() || is_tax() ) {
				$term = $wp_query->get_queried_object();
				echo '<h1>'.$term->name.'</h1>';
			}
			elseif ( is_single() ) {
				$post_id = $wp_query->get_queried_object_id();
   				$field_string = get_post_field( 'post_title', $post_id );
   				$field_array = qtrans_split($field_string);
   				$field_lang = qtrans_getLanguage();
				echo '<h1>'.$field_array[$field_lang].'</h1>';
			}
			else {
				global $prima_productspage_id;
				$field_string = get_post_field( 'post_title', $prima_productspage_id );
   				$field_array = qtrans_split($field_string);
   				$field_lang = qtrans_getLanguage();
				echo '<h1>'.$field_array[$field_lang].'</h1>';
			}
			if( is_tax('wpsc_product_category') || is_single() ) {
				if ( is_tax('wpsc_product_category') )
					$category_slug = $wp_query->query_vars['wpsc_product_category'] ? $wp_query->query_vars['wpsc_product_category'] : $_GET['wpsc_product_category'];
				else {
					$categories = wp_get_object_terms( $wp_query->post->ID , 'wpsc_product_category' );
					if(count($categories) > 1 && isset($wpsc_query->query_vars['wpsc_product_category']))
						$category_slug = $wpsc_query->query_vars['wpsc_product_category'];
					elseif(count($categories) > 0)
						$category_slug = $categories[0]->slug;
				}
				$category_id = wpsc_category_id($category_slug);
				if( $category_id && get_option('show_category_thumbnails') && wpsc_category_image($category_id) ) {
					echo '<div class="group-thumbnail">';
					echo '<img src="'.prima_category_image($category_id, 100, 100, true).'" alt="'.wpsc_category_name($category_id).'" title="'.wpsc_category_name($category_id).'" class="thumbnail" />';
					echo '</div>';
				}
				wpsc_output_breadcrumbs( array( 'before-breadcrumbs' => '<div class="wpsc-breadcrumbs breadcrumb">' ) );
			}
			$catdesc = ''; $subcats = '';
			if ( ( is_tax('wpsc_product_category') || is_single() ) && $category_id && get_option('wpsc_category_description') && wpsc_category_description($category_id) ) {
				$catdesc = '<p class="category-description">'.wpsc_category_description($category_id).'</p>';
			}
			if ( is_tax('wpsc_product_category') && $category_id ) {
				$subcat_args = array( 'taxonomy' => 'wpsc_product_category', 
				'title_li' => '', 'show_count' => 0, 'hide_empty' => 0, 'echo' => false,
				'show_option_none' => '', 'depth' => 1, 'child_of' => $category_id );
				if(get_option('show_category_count') == 1) $subcat_args['show_count'] = 1;
				$subcategories = wp_list_categories( $subcat_args );
				if ( $subcategories ) {
					$subcats = __('Subcategories:',PRIMA_DOMAIN).' <ul class="wpsc_inline_categories">'.$subcategories.'</ul>';
				} 
			}
			if ( $catdesc || $subcats ) {
				echo '<div class="wpsc_category_details">'.$catdesc.$subcats.'</div>';
			}
			?>
        </div>
    </div>
</div>

<div id="content-wrapper">
	<div id="products" class="row container">
		<div class="margin clearfix">
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>			
				<?php the_content(); ?>
			<?php endwhile; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
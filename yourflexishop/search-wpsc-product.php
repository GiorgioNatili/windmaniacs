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
<div id="header-wrapper">
	<?php get_template_part( 'flexi-header' ); ?>
    <div id="leader" class="container">
        <div class="margin clearfix">
			<?php if (class_exists('WP_eCommerce') && prima_get_option('searchformproducts')) prima_product_search(); ?>
			<?php 
			$term = $wp_query->get_queried_object(); 
			echo '<h1>'.sprintf( __( 'Product Search Results for "%1$s"', PRIMA_DOMAIN ), esc_attr( get_search_query() ) ).'</h1>'; 
			?>
        </div>
    </div>
</div>
<div class="custom-search-widget">
	<?php echo $q_config['language']; ?>
    <form role="search" method="get" class="searchform" action="http://bid4kite.gnstudio.biz/">
    	<div>
    		<input type="radio" value="kite" name="type">
    		<input type="radio" value="tavole" name="type">
    	</div>
        <div>
            <input type="text" value="Search..." name="s" class="searchbox">
            <input type="submit" class="searchsubmit" value="Search">
        </div>
    </form>
</div>

<div id="content-wrapper">
	<div id="products" class="row container">
		<div class="margin clearfix">
			<?php
			global $query_string;
			if ( get_option('wpsc_products_per_page') )
				$posts_per_page = get_option('wpsc_products_per_page');
			else 
				$posts_per_page = -1;
			query_posts($query_string.'&post_type=wpsc-product&posts_per_page='.$posts_per_page);
			$template = locate_template( 'wpsc-products_page.php' );
			if ( !empty( $template ) && is_readable( $template ) ) {
				include_once( $template );
			}
			?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
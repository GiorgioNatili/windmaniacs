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
			echo '<h1>'.__('Product Tag:', PRIMA_DOMAIN).' '.$term->name.'</h1>'; 
			?>
        </div>
    </div>
</div>

<div id="content-wrapper">
	<div id="products" class="row container">
		<div class="margin clearfix">
			<?php
			$template = locate_template( 'wpsc-products_page.php' );
			if ( !empty( $template ) && is_readable( $template ) ) {
				include_once( $template );
			}
			?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
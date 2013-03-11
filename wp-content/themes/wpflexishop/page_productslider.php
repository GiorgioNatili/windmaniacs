<?php
/**
 * Template Name: Products Slider
 */
global $wp_query, $current_page_id;
$orig_query = $wp_query;
$current_page_id = $wp_query->get_queried_object_id();
get_header();
?>

<div id="header-wrapper">
	<?php get_template_part( 'flexi-header' ); ?>
    <div id="leader" class="container">
        <div class="margin clearfix">
			<?php
            $post_id = $wp_query->get_queried_object_id();
            $title = get_post_field( 'post_title', $post_id );
			$width_product = prima_get_option('sliderproductwidth') ? prima_get_option('sliderproductwidth') : 500;
			$width = (prima_get_option('themelayout') == 'boxed') ? 896 : 980;
			$height = prima_get_option('sliderheight') ? prima_get_option('sliderheight') : 375;
			$products = prima_get_custom_field("_prima_products");
			$products_array = explode(",", $products);
			?>
			<h1 class="page-special-title hide"><?php echo $title; ?></h1>
			<?php if ($products) : ?>
                <div id="featured-slider">
                    <div id="feature-wrapper">
                    <div id="features">
                        <ul class="feature-list">
							<?php prima_slider_product($products, $width_product, $height); ?>
                        </ul>
                    </div>
                    </div>
                </div>
				<?php if (count($products_array) > 1) : ?>
					<div id="slider-controls"></div>
				<?php endif; ?>
			<?php endif; ?>
        </div>
    </div>
</div>

<div id="content-wrapper">
	<div id="main-content" class="container">	
		<div class="margin">
			<div id="full-col">
				<?php wp_reset_query(); ?>
				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', PRIMA_DOMAIN ), 'after' => '' ) ); ?>
					<?php edit_post_link( __( 'Edit', PRIMA_DOMAIN ), '', '' ); ?>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
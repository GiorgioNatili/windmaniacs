<?php
global $wp_query, $product_page_width, $product_sidebar, $product_sidebar_width;
$product_sidebar = false;
if (prima_get_option('themelayout') == 'boxed') { $product_page_width = 665; }
else { $product_page_width = 720; }

get_header(); ?>

<div id="header-wrapper">
	<?php get_template_part( 'flexi-header' ); ?>
    <div id="leader" class="container">
        <div class="margin clearfix">
			<?php if (class_exists('WP_eCommerce') && prima_get_option('searchformsingle')) prima_product_search(); ?>
        	<?php $post_id = $wp_query->get_queried_object_id();
			$title = get_post_field( 'post_title', $post_id ); ?>
            <h1><?php echo $title; ?></h1>
        </div>
    </div>
</div>

<div id="content-wrapper">
	<div id="main-content" class="container">	
		<div class="margin">
			<div id="main-col">
				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>	
					<ul class="testimonials">
					<li class="testimonial">
						<blockquote>
							<?php the_content();?>
						</blockquote>
						<div class="testimonial-meta">
							<span class="testimonial_author"><?php prima_custom_field("testimonial_author"); ?></span>
						</div>
					</li>
					</ul>
				<?php endwhile; ?>
			</div>
			<div id="sidebar">
				<div class="sidebar-inner">
					<?php get_sidebar(); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
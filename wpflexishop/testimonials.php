<?php
/**
 * Template Name: Testimonials
 */
get_header(); ?>

<div id="header-wrapper">
	<?php get_template_part( 'flexi-header' ); ?>
    <div id="leader" class="container">
        <div class="margin clearfix">
			<?php if (class_exists('WP_eCommerce') && prima_get_option('searchformpage')) prima_product_search(); ?>
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
				<?php $testimonial_query = new WP_Query('posts_per_page=-1&post_type=testimonial'); ?>
				<?php if ($testimonial_query->have_posts()) : ?>
				<ul class="testimonials">
				<?php while ($testimonial_query->have_posts()) : $testimonial_query->the_post(); ?>
				<li class="testimonial">
					<blockquote>
						<?php the_content();?>
					</blockquote>
					<div class="testimonial-meta">
						<span class="testimonial_author"><?php prima_custom_field("testimonial_author"); ?></span>
					</div>
				</li>
				<?php endwhile; ?>
				</ul>
				<?php else : ?>
					<p><?php _e( 'Sorry, no testimonial is available', PRIMA_DOMAIN ); ?></p>
				<?php endif; ?>
				<?php wp_reset_query(); ?>
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
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
			<?php if (class_exists('WP_eCommerce') && prima_get_option('searchformindex')) prima_product_search(); ?>
			<h1><?php echo sprintf( __( 'Search Results for "%1$s"', PRIMA_DOMAIN ), esc_attr( get_search_query() ) ); ?></h1>
        </div>
    </div>
</div>

<div id="content-wrapper">
	<div id="main-content" class="container">	
		<div class="margin">
			<div id="main-col">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="blog-post blog-overview">
                    <div class="post-content clearfix">
					<?php 
					$width = 100;
					$height = 100;
					$thumbnail = prima_get_image( false, $width, $height, true ); 
					if ( $thumbnail )
						echo '<img class="attachment-post-thumbnail alignleft" src="'.$thumbnail.'" title="'.the_title_attribute('echo=0').'" alt="'.the_title_attribute('echo=0').'" width="'.$width.'" height="'.$height.'" />';
					$post_type = get_post_field( 'post_type', get_the_ID() );
					if ( $post_type == 'post' ) $type = __('[Blog Post]', PRIMA_DOMAIN);
					elseif ( $post_type == 'page' ) $type = __('[Page]', PRIMA_DOMAIN);
					elseif ( $post_type == 'wpsc-product' ) $type = __('[Product]', PRIMA_DOMAIN);
					?>
					<p><?php echo $type ?> <strong><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></strong></p>
                    <?php the_excerpt();?>
                    </div>
                </div>
                <?php endwhile; ?>
					<?php pagination(); ?>
                <?php else : ?>
					<p><strong><?php _e( 'Not Found', PRIMA_DOMAIN ); ?></strong></p>
					<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', PRIMA_DOMAIN ); ?></p>
					<?php get_search_form(); ?>
				<?php endif; ?>
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
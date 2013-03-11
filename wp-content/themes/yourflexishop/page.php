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
			<?php if (class_exists('WP_eCommerce') && prima_get_option('searchformpage')) prima_product_search(); ?>
        	<h1><?php the_title(); ?></h1>
        </div>
    </div>
</div>
<div id="content-wrapper">
	<div id="main-content" class="container">
		<div class="margin">
			<div id="main-col">
				<div id="page">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						<?php the_content(); ?>
	                    <?php wp_link_pages( array( 'before' => '' . __( 'Pages:', PRIMA_DOMAIN ), 'after' => '' ) ); ?>
	                    <?php edit_post_link( __( 'Edit', PRIMA_DOMAIN ), '', '' ); ?>
					<?php endwhile; ?>
	                <?php else : ?>
						<p><strong><?php _e( 'Not Found', PRIMA_DOMAIN ); ?></strong></p>
						<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', PRIMA_DOMAIN ); ?></p>
						<?php get_search_form(); ?>
					<?php endif; ?>
				</div>
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

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
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>	
                    <div id="single-post">	
                        <div class="post-meta">
                            <p><?php _e( 'Posted on', PRIMA_DOMAIN ); ?> <?php the_time(__( 'F j, Y', PRIMA_DOMAIN )); ?></p>
                        </div>
                        <?php 
						if( has_post_thumbnail() && !prima_get_option('blogposthideimage') ) { 
							$img_id = get_post_thumbnail_id( get_the_ID() );
							$img_url = prima_get_image( $img_id, 690, 282, true );
							$img_full_url = prima_get_image( $img_id );
							if ($img_url) 
								echo '<a rel="prettyPhoto" href="'.$img_full_url.'" title="'.the_title_attribute('echo=0').'" class="large-blog-image"><img src="'.$img_url.'" alt="'.the_title_attribute('echo=0').'"/></a>';
						}
						?>
                        <div class="post-content">
							<?php the_content(); ?>
							<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', PRIMA_DOMAIN ), 'after' => '' ) ); ?>
							<p>Filed under <?php the_category(', ') ?></p>
							<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>
							<?php edit_post_link( __( 'Edit', PRIMA_DOMAIN ), '', '' ); ?>
                        </div>
                    </div>
					<?php comments_template( '', true ); ?>
				<?php endwhile; ?>
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
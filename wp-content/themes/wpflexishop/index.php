<?php
global $wp_query, $product_page_width, $product_sidebar, $product_sidebar_width;
$product_sidebar = false;
if (prima_get_option('themelayout') == 'boxed') { $product_page_width = 665; }
else { $product_page_width = 720; }
get_header(); ?>

<div id="header-wrapper">
	<?php get_template_part( 'flexi-header' ); ?>
    <?php
        if ( is_404() )
            $title = __('404 - Not Found', PRIMA_DOMAIN);
        elseif ( is_search() )
            $title = sprintf( __( 'Search Results for "%1$s"', PRIMA_DOMAIN ), esc_attr( get_search_query() ) );
        elseif ( is_author() )
            $title = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
        elseif ( is_date() ) {
            if ( get_query_var( 'minute' ) && get_query_var( 'hour' ) )
                $title = sprintf( __( 'Archive for %1$s', PRIMA_DOMAIN ), get_the_time( __( 'g:i a', PRIMA_DOMAIN ) ) );
            elseif ( get_query_var( 'minute' ) )
                $title = sprintf( __( 'Archive for minute %1$s', PRIMA_DOMAIN ), get_the_time( __( 'i', PRIMA_DOMAIN ) ) );
            elseif ( get_query_var( 'hour' ) )
                $title = sprintf( __( 'Archive for %1$s', PRIMA_DOMAIN ), get_the_time( __( 'g a', PRIMA_DOMAIN ) ) );
            elseif ( is_day() )
                $title = sprintf( __( 'Archive for %1$s', PRIMA_DOMAIN ), get_the_time( __( 'F jS, Y', PRIMA_DOMAIN ) ) );
            elseif ( get_query_var( 'w' ) )
                $title = sprintf( __( 'Archive for week %1$s of %2$s', PRIMA_DOMAIN ), get_the_time( __( 'W', PRIMA_DOMAIN ) ), get_the_time( __( 'Y', PRIMA_DOMAIN ) ) );
            elseif ( is_month() )
                $title = sprintf( __( 'Archive for %1$s', PRIMA_DOMAIN ), single_month_title( ' ', false) );
            elseif ( is_year() )
                $title = sprintf( __( 'Archive for %1$s', PRIMA_DOMAIN ), get_the_time( __( 'Y', PRIMA_DOMAIN ) ) );
        }
        elseif ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) {
            $post_type = get_post_type_object( get_query_var( 'post_type' ) );
            $title = $post_type->labels->name;
        }
        elseif ( is_category() || is_tag() || is_tax() ) {
            $term = $wp_query->get_queried_object();
            $title = $term->name;
        }
        elseif ( is_singular() ) {
            $post_id = $wp_query->get_queried_object_id();
            $title = get_post_field( 'post_title', $post_id );
        }
        elseif ( is_home() && get_option('show_on_front') != 'page' ) {
			$title = get_bloginfo( 'description' );
        }
        elseif ( is_home() && get_option('show_on_front') == 'page' && get_option('page_for_posts') > 0 ) {
			$title = __('Latest News', PRIMA_DOMAIN);
        }
	?>
    <div id="leader" class="container">
        <div class="margin clearfix">
			<?php if (class_exists('WP_eCommerce') && prima_get_option('searchformindex')) prima_product_search(); ?>
			<h1><?php echo $title; ?></h1>
        </div>
    </div>
</div>

<div id="content-wrapper">
	<div id="main-content" class="container">	
		<div class="margin">
			<div id="main-col">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="blog-post blog-overview">
                    <div class="post-header clearfix">
                        <h2 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                        <div class="post-meta">
                            <p><?php _e( 'Posted on', PRIMA_DOMAIN ); ?> <?php the_time(__( 'F j, Y', PRIMA_DOMAIN )); ?><?php comments_popup_link('0', '1', '%', 'comment-count'); ?></p>
                        </div>
                        <?php 
						if( has_post_thumbnail() && !prima_get_option('bloghideimage') ) { 
							$width = (prima_get_option('themelayout') == 'boxed') ? 655 : 690;
							$height = 282;
							$img_id = get_post_thumbnail_id( get_the_ID() );
							$img_url = prima_get_image( $img_id, $width, $height, true );
							if ($img_url) 
								echo '<a href="'.get_permalink().'" title="'.the_title_attribute('echo=0').'" class="large-blog-image"><img src="'.$img_url.'" alt="'.the_title_attribute('echo=0').'"/></a>';
						}
						?>
                    </div>
                    <div class="post-content">
                        <?php 
						if( prima_get_option('blogcontent') == 'content' ) { 
							the_content(  __( 'Continue reading &hellip;', PRIMA_DOMAIN ) );
							wp_link_pages( array( 'before' => '' . __( 'Pages:', PRIMA_DOMAIN ), 'after' => '' ) );
							edit_post_link( __( 'Edit', PRIMA_DOMAIN ), '', '' );
						}
						else {
							the_excerpt();
						}
						?>
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
<div id="footer-top" class="clearfix">

<?php if ( is_active_sidebar( 'blog-footer-top-widget-area' ) ) : ?>
    <div class="footer-top-left col-2">
        <div class="col-wrapper">
            <?php dynamic_sidebar( 'blog-footer-top-widget-area' ); ?>
        </div>
    </div>
<?php else : ?>
    <div id="blog-panel" class="col-2">
        <div class="col-wrapper">
            <h3 class="widget-title"><?php _e( 'Latest News', PRIMA_DOMAIN ); ?></h3>
            <ul class="blog-list">
			<?php $footernews = prima_get_option('footernews') ? (int)prima_get_option('footernews') : '2'; ?>
			<?php query_posts('posts_per_page='.$footernews.'&post_type=post'); ?>
            <?php global $more; $more = 0; ?>
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php $postid = get_the_ID(); ?>
                <li class="post">
                    <div class="post-header">
						<?php $img_url = prima_get_image( false, 75, 75, true, $postid );
						if ($img_url) : ?>
							<a href="<?php the_permalink() ?>" rel="bookmark" class="thumbnail">
								<img alt="<?php the_title_attribute() ?>" class="attachment-post-thumbnail wp-post-image" src="<?php echo $img_url ?>">
							</a>
						<?php endif; ?>
                        <h3><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
                        <div class="post-meta">
                            <?php flexishop_posted_on(); ?>
                        </div>
                    </div>
                    <div class="post-excerpt">
                        <?php echo the_excerpt();?>
                    </div>
                </li>
			<?php endwhile; endif; ?>
            <?php wp_reset_query(); ?>
            </ul>
        </div>
    </div>
<?php endif; ?>

<div class="footer-top-widgets col-2">
<?php if ( is_active_sidebar( 'first-footer-top-widget-area' ) ) : ?>
    <div class="col-2 col-right col-first">
        <div class="col-wrapper">
            <?php dynamic_sidebar( 'first-footer-top-widget-area' ); ?>
        </div>
    </div>
<?php else : ?>
	<div class="col-2">
		<div class="col-wrapper">
		<h3 class="widget-title"><?php _e( 'Twitter', PRIMA_DOMAIN ); ?></h3>
		<div id="footer-twitter" class="widget-container">
			<?php 
			$twitter = prima_get_option('twitter') ? prima_get_option('twitter') : 'primathemes';
			$footertweets = prima_get_option('footertweets') ? (int)prima_get_option('footertweets') : '3';
			$attr = prima_twitter( array( 
				'usernames' => $twitter, 
				'limit' => $footertweets, 
				'interval' => 600 
			)); 
			?>
        </div>
		</div>
	</div>
<?php endif; ?>

<?php if ( is_active_sidebar( 'second-footer-top-widget-area' ) ) : ?>
    <div class="col-2 col-right">
        <div class="col-wrapper">
            <?php dynamic_sidebar( 'second-footer-top-widget-area' ); ?>
        </div>
    </div>
<?php else : ?>
	<div class="col-2 col-right">
		<div class="col-wrapper">
		<h3 class="widget-title"><?php _e( 'Widget Area', PRIMA_DOMAIN ); ?></h3>
		<div class="widget-container">
			<p><?php printf(__('This is <strong>"%1$s"</strong> widget area. Visit your <a href="%2$s">Widgets Page</a> to add new widget to this area.', PRIMA_DOMAIN), __('Third Footer Top', PRIMA_DOMAIN), admin_url('widgets.php')); ?></p>
		</div>
		</div>
	</div>
<?php endif; ?>

</div><!-- .footer-top-widgets -->

</div><!-- #footer-top -->

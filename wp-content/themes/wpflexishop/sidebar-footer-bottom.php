<div id="footer-bottom" class="clearfix">

<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
    <ul class="col-5">
        <?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
    </ul>
<?php else : ?>
	<ul class="col-5">
    	<li class="widget-container">
            <h3><?php _e( 'Recent Posts', PRIMA_DOMAIN ); ?></h3>
            <ul>
            <?php wp_get_archives( array( 'type' => 'postbypost', 'limit' => 8 ) ); ?>
            </ul>
        </li>
	</ul>
<?php endif; ?>

<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>
    <ul class="col-5">
        <?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
    </ul>
<?php else : ?>
	<ul class="col-5">
    	<li class="widget-container">
            <h3><?php _e( 'Blog Categories', PRIMA_DOMAIN ); ?></h3>
            <ul>
            <?php wp_list_categories( array( 'title_li' => '', 'show_count' => 1, 'hide_empty' => 1 )); ?>
            </ul>
        </li>
	</ul>
<?php endif; ?>

<?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) : ?>
    <ul class="col-5">
        <?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
    </ul>
<?php else : ?>
    <ul class="col-5">
		<?php wp_list_bookmarks( array (     
		'title_li'         => __('Bookmarks', PRIMA_DOMAIN),
		'title_before'     => '<h3>',
		'title_after'      => '</h3>',
		'category_before'  => '<li class="widget-container">',
		'category_after'   => '</li>' ) ); ?> 
    </ul>
<?php endif; ?>

<?php if ( is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
    <ul class="col-5">
        <?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
    </ul>
<?php else : ?>
	<ul class="col-5">
    	<li class="widget-container">
            <h3><?php _e( 'About Us', PRIMA_DOMAIN ); ?></h3>
			<?php $aboutus = prima_get_option('aboutus');
			if ( !$aboutus ) $aboutus = __( 'This is a short paragraph about us. It can be found in the theme options page', PRIMA_DOMAIN );
			echo wpautop( $aboutus ); ?>
        </li>
	</ul>
<?php endif; ?>

<?php if ( is_active_sidebar( 'fifth-footer-widget-area' ) ) : ?>
    <ul class="col-5 col-right">
        <?php dynamic_sidebar( 'fifth-footer-widget-area' ); ?>
    </ul>
<?php else : ?>
	<ul class="col-5 col-right">
    	<li class="widget-container">
        </li>
	</ul>
<?php endif; ?>
</div>

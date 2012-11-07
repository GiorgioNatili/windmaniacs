<div id="footer-bottom" class="clearfix">

<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
    <ul class="col-3">
        <?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
    </ul>
<?php else : ?>
	<ul class="col-3">
    	<li class="widget-container">
            <h3><?php _e( 'Recent Posts', PRIMA_DOMAIN ); ?></h3>
            <ul>
            <?php wp_get_archives( array( 'type' => 'postbypost', 'limit' => 8 ) ); ?>
            </ul>
        </li>
	</ul>
<?php endif; ?>

<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>
    <ul class="col-3">
        <?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
    </ul>
<?php else : ?>
    <div class="col-3 footer-newsletter">
	   <?php front_end_form(); //newsletter form ?>
    </div>
<?php endif; ?>

<?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) : ?>
    <ul class="col-3">
        <?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
    </ul>
<?php else : ?>
    <div class="col-3 footer-contact-form">
        <?php echo do_shortcode( '[contact-form-7 id="215" title="Footer Contact Form"]'); //contact form?>
    </div>
<?php endif; ?>

<?php if ( is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
    <ul class="col-3">
        <?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
    </ul>
<?php else : ?>
	<div class="col-3">
    </div>
<?php endif; ?>

<?php if ( is_active_sidebar( 'fifth-footer-widget-area' ) ) : ?>
    <ul class="col-3 col-right">
        <?php dynamic_sidebar( 'fifth-footer-widget-area' ); ?>
    </ul>
<?php else : ?>
	<div class="col-3">
    </div>
<?php endif; ?>
</div>

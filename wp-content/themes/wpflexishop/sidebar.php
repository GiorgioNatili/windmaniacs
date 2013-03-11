<ul class="sidebar-widgets">
<?php if ( is_active_sidebar( prima_get_sidebar( 'primary-widget-area' ) ) ) : ?>
	<?php prima_dynamic_sidebar( 'primary-widget-area' ); ?>
<?php else : ?>
    <li class="widget-container widget_archive">
        <h3 class="widget-title"><?php _e( 'Archives', PRIMA_DOMAIN ); ?></h3>
        <ul>
			<?php wp_get_archives( 'type=monthly' ); ?>
        </ul>
    </li>
    <li class="widget-container widget_meta">
        <h3 class="widget-title"><?php _e( 'Meta', PRIMA_DOMAIN ); ?></h3>
        <ul>
			<?php wp_register(); ?>
			<li><?php wp_loginout(); ?></li>
			<?php wp_meta(); ?>
        </ul>
    </li>
<?php endif; ?>
<div id="cart-top">
    <span class='checkout-top cartcount'>
        <a target='_parent' href='<?php echo get_option('shopping_cart_url'); ?>'>
            <img src="<?php bloginfo('template_url') ?>/images/basket.png" alt="shopping basket" />
            <span class='cartcount'>
				<?php printf( _n('%d item', '%d items', wpsc_cart_item_count(), PRIMA_DOMAIN), wpsc_cart_item_count() ); ?>
			</span>
        </a>
    </span>
    <span class='amount-top items'></span>
    <div id="small-cart" class="shopping-cart-wrapper widget_wp_shopping_cart">
	<?php get_template_part( 'wpsc-cart_widget' ); ?>
    </div>
</div>

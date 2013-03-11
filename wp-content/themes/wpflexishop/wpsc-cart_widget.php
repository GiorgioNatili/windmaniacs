		<?php if(isset($cart_messages) && count($cart_messages) > 0) { ?>
            <?php foreach((array)$cart_messages as $cart_message) { ?>
              <span class="cart_message"><?php echo $cart_message; ?></span>
            <?php } ?>
        <?php } ?>
        <div id="small-cart-header">
            <div class="cart-message">
                <h4><?php _e( 'Shopping Cart', PRIMA_DOMAIN ); ?></h4>
            </div>
            <div class='cart-items'>
                <span class='cartcount'>
                    <?php echo wpsc_cart_item_count(); ?>
                </span>
            </div>
        </div>
        <?php if(wpsc_cart_item_count() > 0): ?>
        <table class='shoppingcart'>
            <tr>
                <th id='product'><?php echo __('Product', PRIMA_DOMAIN); ?></th>
                <th id='quantity'><?php echo __('Qty', PRIMA_DOMAIN); ?></th>
                <th id='price'><?php echo __('Price', PRIMA_DOMAIN); ?></th>
                <th id="remove">&nbsp;</th>
            </tr>
            <?php while(wpsc_have_cart_items()): wpsc_the_cart_item(); ?>
                <tr>
                    <td><?php echo wpsc_cart_item_name(); ?></td>
                    <td><?php echo wpsc_cart_item_quantity(); ?></td>
                    <td><?php echo wpsc_cart_item_price(); ?></td>
                    <td class="cart-widget-remove"><form action="" method="post" class="adjustform">
                    <input type="hidden" name="quantity" value="0" />
                    <input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>" />
                    <input type="hidden" name="wpsc_update_quantity" value="true" />
                    <input class="remove_button" type="submit" />
                    </form></td>
                </tr>	
            <?php endwhile; ?>
            <tfoot>
                <tr class="cart-widget-total">
                    <td class="cart-widget-count">
                        <?php printf( _n('%d item', '%d items', wpsc_cart_item_count(), PRIMA_DOMAIN), wpsc_cart_item_count() ); ?>
                    </td>
                    <td class="pricedisplay checkout-total" colspan='3'>
                        <?php _e('Total', PRIMA_DOMAIN); ?>: <?php echo wpsc_cart_total_widget( false, false ,false ); ?>
						<br />
						<small><?php _e( '(shipping and tax excluded)', PRIMA_DOMAIN ); ?></small>
                    </td>
                </tr>
            </tfoot>
        </table>
        
        <div class="small-cart-links"><a class="checkout-link" target='_parent' href='<?php echo get_option('shopping_cart_url'); ?>'><?php echo __('Checkout', PRIMA_DOMAIN); ?></a>
            <form action='' method='post' class='wpsc_empty_the_cart'>
            <input type='hidden' name='wpsc_ajax_action' value='empty_cart' />
			<a target="_parent" href="<?php echo htmlentities(add_query_arg('wpsc_ajax_action', 'empty_cart', home_url('/')), ENT_QUOTES, 'UTF-8'); ?>" class="emptycart" title="<?php _e('Empty Cart', PRIMA_DOMAIN); ?>"><?php _e('Empty Cart', PRIMA_DOMAIN); ?></a>
        </form>
        
        </div>
    <?php else: ?>
        <p class="empty"><?php echo __('Your shopping cart is empty', PRIMA_DOMAIN); ?></p>
        <p class="visitshop">
          <a target='_parent' href="<?php echo get_option('product_list_url'); ?>"><?php echo __('Visit the shop', PRIMA_DOMAIN); ?></a>
        </p>
    <?php endif; ?>
    
    <?php
    wpsc_google_checkout();
    ?>
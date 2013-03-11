<?php
get_header();
?>

<div id="header-wrapper">
	<?php get_template_part( 'flexi-header' ); ?>
    <div id="leader" class="container">
        <div class="margin clearfix">
            <h1>
			<?php if (class_exists('WP_eCommerce') && prima_get_option('searchformcheckout')) prima_product_search(); ?>
			<?php wp_title(); ?>
            <?php 
				global $prima_shoppingcart_id;
				if ( $prima_shoppingcart_id && is_page( $prima_shoppingcart_id ) )
					echo '<span class="checkout_totals pricedisplay checkout-total" > / '.wpsc_cart_total().'</span>'; 
			?>
            </h1>
        </div>
    </div>
</div>

<div id="content-wrapper">
	<div id="checkout" class="row container">
		<div class="margin">
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>			
				<?php the_content(); ?>
			<?php endwhile; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
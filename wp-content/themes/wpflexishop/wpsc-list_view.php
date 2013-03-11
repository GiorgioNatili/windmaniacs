<?php
global $wp_query, $product_page_active, $product_page_width, $product_sidebar, $product_sidebar_width;
$product_page_width = $product_page_width ? $product_page_width : 980;
$flexi_display_categories = wpsc_display_categories();
$flexi_display_products = wpsc_display_products();
if($product_sidebar == 'yes') {
	$productsperrow = intval(($product_page_width-$product_sidebar_width+20)/(get_option('product_image_width')+10));
	$catsperrow = intval(($product_page_width-$product_sidebar_width+20)/(get_option('category_image_width')+10));
}
else {
	$productsperrow = intval(($product_page_width+10)/(get_option('product_image_width')+10));
	$catsperrow = intval(($product_page_width+10)/(get_option('category_image_width')+10));
}
?>
<div id="flexishop_products_page_container" class="clearfix">
	
<?php if ( $product_page_active ) 
do_action('wpsc_top_of_products_page'); // Plugin hook for adding things to the top of the products page, like the live search ?>

<div id="products_page_container" class="wrap wpsc_container <?php if($product_sidebar == 'yes') : ?>products-sidebar<?php endif; ?> clearfix">

<div id="main_products_page_container">

<?php if($flexi_display_categories): ?>

	<?php if(wpsc_category_grid_view()) :?>
        <div class="flexi_category_grid wpsc_categories wpsc_category_grid group">
            <?php wpsc_start_category_query(array('category_group'=> get_option('wpsc_default_category'), 'show_thumbnails'=> 1)); ?>
                <a href="<?php wpsc_print_category_url();?>" class="wpsc_category_grid_item  <?php wpsc_print_category_classes_section(); ?>" title="<?php wpsc_print_category_name(); ?>">
                    <?php wpsc_print_category_image(get_option('category_image_width'),get_option('category_image_height')); ?>
                    <span class="category-name"><?php wpsc_print_category_name(); ?>
					<?php if ( 1 == get_option( 'show_category_count') ) wpsc_print_category_products_count( "(",")" ); ?>
                    </span>
                </a>
                <?php wpsc_print_subcategory("", ""); ?>
            <?php wpsc_end_category_query(); ?>
        </div><!--close wpsc_categories-->
	<?php else:?>
		<?php $instance_categories = get_terms( 'wpsc_product_category', 'hide_empty=0&parent=0');
        foreach($instance_categories as $categories){ 
            $term_id = $categories->term_id;
            $term_name = $categories->name;
            ?>
            <h4><a href="<?php echo get_term_link( $categories, 'wpsc_product_category' ); ?>"><?php echo $term_name; ?></a></h4>
            <?php $subcat_args = array( 'taxonomy' => 'wpsc_product_category', 
            'title_li' => '', 'show_count' => 0, 'hide_empty' => 0, 'echo' => false, 'hierarchical' => false,
            'show_option_none' => '', 'child_of' => $term_id ); ?>
			<?php if(get_option('show_category_count') == 1) $subcat_args['show_count'] = 1; ?>
            <?php $subcategories = wp_list_categories( $subcat_args ); ?>
            <?php if ( $subcategories ) { ?>
            <ul class='wpsc_inline_categories'><?php echo $subcategories ?></ul>
            <?php } ?>
            <?php
        } 
        ?>
	<?php endif; ?>
    
<?php endif; ?>

<?php if($flexi_display_products): ?>

	<?php if (wpsc_have_products()) : ?>
    
		<?php 
		if(wpsc_has_pages_top()) {
			ob_start();
			wpsc_pagination();
            $wpsc_pagination = ob_get_clean(); 
			if ($wpsc_pagination) echo '<div class="wpsc_page_numbers_top">'.$wpsc_pagination.'</div>';
		}
		?>
    
		<table class="default_product_display list_productdisplay">
			<?php /** start the product loop here */?>
			<?php $alt = 0;	?>
			<?php while (wpsc_have_products()) :  wpsc_the_product(); ?>
				<?php
				$alt++;
				if ($alt %2 == 1) { $alt_class = 'alt'; } else { $alt_class = ''; }
				?>
				<tr  class="product_view_<?php echo wpsc_the_product_id(); ?> <?php echo $alt_class;?>">
					<td>
                    <?php 
					$thumb_width = 70;
					$thumb_height = 70;
					if(wpsc_the_product_thumbnail()) $thumb = wpsc_the_product_thumbnail($thumb_width,$thumb_height);
					else $thumb = PRIMA_IMAGES_URL.'/noimage.png';
					?>					
                    <img alt="<?php echo wpsc_the_product_title(); ?>" width="<?php echo $thumb_width ?>" height="<?php echo $thumb_height ?>" title="<?php echo wpsc_the_product_title(); ?>" src="<?php echo $thumb; ?>"/>
                    </td>
					
					<td >
						<a class="wpsc_product_title" href="<?php echo wpsc_the_product_permalink(); ?>">
							<strong><?php echo wpsc_the_product_title(); ?></strong>
						</a>
					</td>

					<td>
                        <?php if(wpsc_product_has_stock()) : ?>
                            <span id="stock_display_<?php echo wpsc_the_product_id(); ?>" class="in_stock"><?php _e('Product in stock', PRIMA_DOMAIN); ?></span>
                        <?php else: ?>
                            <span id="stock_display_<?php echo wpsc_the_product_id(); ?>" class="out_of_stock"><?php _e('Product not in stock', PRIMA_DOMAIN); ?></span>
                        <?php endif; ?>
					</td>

					<td>
						<span id="product_price_<?php echo wpsc_the_product_id(); ?>" class="pricedisplay"><?php echo prima_the_product_price( __('from', PRIMA_DOMAIN ), true ); ?></span>
					</td>
					
					<td>
						<?php if(wpsc_product_external_link(wpsc_the_product_id()) != '') : ?>
							<?php	$action =  wpsc_product_external_link(wpsc_the_product_id()); ?>
						<?php else: ?>
							<?php	$action =  wpsc_this_page_url(); ?>						
						<?php endif; ?>
						<form id="product_<?php echo wpsc_the_product_id(); ?>" class='product_form'  enctype="multipart/form-data" action="<?php echo $action; ?>" method="post" name="product_<?php echo wpsc_the_product_id(); ?>">

							<?php /** the variation group HTML and loop */?>
							<div class="wpsc_variation_forms">
								<?php while (wpsc_have_variation_groups()) : wpsc_the_variation_group(); ?>
									<p>
										<label for="<?php echo wpsc_vargrp_form_id(); ?>"><?php echo wpsc_the_vargrp_name(); ?>:</label>
										<?php /** the variation HTML and loop */?>
										<select class='wpsc_select_variation' name="variation[<?php echo wpsc_vargrp_id(); ?>]" id="<?php echo wpsc_vargrp_form_id(); ?>">
										<?php while (wpsc_have_variations()) : wpsc_the_variation(); ?>
											<option value="<?php echo wpsc_the_variation_id(); ?>"><?php echo wpsc_the_variation_name(); ?></option>
										<?php endwhile; ?>
										</select>
									</p>
								<?php endwhile; ?>
							</div>
							<?php /** the variation group HTML and loop ends here */?>

							<?php if(wpsc_has_multi_adding()): ?>
                                <div class="quantity-meta">
                                    <p>
                                    <label><?php _e('Quantity', PRIMA_DOMAIN); ?>: </label>
                                    <span class="wpsc_quantity_update">
                                    <input class="flexi_quantity_update" type="text" id="wpsc_quantity_update_<?php echo wpsc_the_product_id(); ?>" name="wpsc_quantity_update" size="2" value="1" />
                                    <input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>"/>
                                    <input type="hidden" name="wpsc_update_quantity" value="true" />
                                    </span><!--close wpsc_quantity_update-->
                                    </p>
                                </div>
							<?php endif ;?>
						
							<input type="hidden" value="add_to_cart" name="wpsc_ajax_action"/>
							<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="product_id"/>

							
							<?php if((get_option('hide_addtocart_button') == 0) &&  (get_option('addtocart_or_buynow') !='1')) : ?>
								<?php if(wpsc_product_has_stock()) : ?>
									<div class='wpsc_buy_button_container'>
											<?php if(wpsc_product_external_link(wpsc_the_product_id()) != '') : ?>
											<?php 	$action =  wpsc_product_external_link(wpsc_the_product_id()); ?>
											<input class="wpsc_buy_button" type='button' value='<?php echo __('Buy Now', 'flexishop'); ?>' onclick='gotoexternallink("<?php echo $action; ?>")'>
											<?php else: ?>
										<input type="submit" value="<?php echo __('Add To Cart', 'flexishop'); ?>" name="Buy" class="wpsc_buy_button" id="product_<?php echo wpsc_the_product_id(); ?>_submit_button"/>
											<?php endif; ?>
										<div class='wpsc_loading_animation'>
											<img title="Loading" alt="Loading" src="<?php echo WPSC_URL; ?>/images/indicator.gif" class="loadingimage"/>
											<?php echo __('Updating cart...', 'flexishop'); ?>
										</div>
									</div>
								<?php else : ?>
									<p class='soldout'><?php echo __('This product has sold out.', 'flexishop'); ?></p>
								<?php endif ; ?>
							<?php endif ; ?>
							
						</form>
					</td>
				</tr>

			<?php endwhile; ?>
			<?php /** end the product loop here */?>
		</table>
        
		<?php 
		if(wpsc_has_pages_bottom()) {
			ob_start();
			wpsc_pagination();
            $wpsc_pagination = ob_get_clean(); 
			if ($wpsc_pagination) echo '<div class="wpsc_page_numbers_bottom">'.$wpsc_pagination.'</div>';
		}
		?>
        
	<?php else : ?>
		<?php if(wpsc_product_count() == 0):?>
			<p><?php  _e('There are no products in this group.', PRIMA_DOMAIN); ?></p>
		<?php endif ; ?>
	<?php endif; ?>
	
<?php endif; ?>

</div><!--close main_products_page_container-->

<?php if($product_sidebar == 'yes') : ?>
	<div id="products-sidebar" class="main-products-sidebar">
		<?php if ( !prima_get_option('productscatssidebar') ) : ?>
		<div class="sidebar-categories">
			<?php $instance_categories = get_terms( 'wpsc_product_category', 'hide_empty=0&parent=0');
            foreach($instance_categories as $categories){ 
                $term_id = $categories->term_id;
                $term_name = $categories->name;
                ?>
                <div class='categories-group' id='sidebar_categorisation_group_<?php echo $term_id; ?>'>
                    <h4 class='wpsc_category_title'><a href="<?php echo get_term_link( $categories, 'wpsc_product_category' ); ?>"><?php echo $term_name; ?></a></h4>
                        <?php $subcat_args = array( 'taxonomy' => 'wpsc_product_category', 
                        'title_li' => '', 'show_count' => 0, 'hide_empty' => 0, 'echo' => false,
                        'show_option_none' => '', 'child_of' => $term_id ); ?>
                        <?php if(get_option('show_category_count') == 1) $subcat_args['show_count'] = 1; ?>
                        <?php $subcategories = wp_list_categories( $subcat_args ); ?>
                        <?php if ( $subcategories ) { ?>
                        <ul class='wpsc_categories wpsc_top_level_categories'><?php echo $subcategories ?></ul>
                        <?php } ?>
                    <div class='clear_category_group'></div>
                </div>
                <?php
            } 
            ?>
		</div>	 
		<?php endif; ?>	
		<?php if ( is_active_sidebar( 'products-sidebar' ) ) : ?>
			<ul class="xoxo">
				<?php dynamic_sidebar( 'products-sidebar' ); ?>
			</ul>
		<?php endif; ?>	
	</div>
<?php endif; ?>

</div><!--close products_page_container-->

<?php if ( $product_page_active ) 
do_action( 'wpsc_theme_footer' ); ?> 	
    
</div><!--close flexishop_products_page_container-->
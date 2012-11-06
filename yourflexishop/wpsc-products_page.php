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
if ( intval(prima_get_option('productsperrow')) > 0 ) {
	$productsperrow = intval(prima_get_option('productsperrow'));
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
    
		<?php $i = 1; /** start the product loop here */?>
        
		<?php while (wpsc_have_products()) :  wpsc_the_product(); ?>
		<?php if(($i % $productsperrow) == 1) echo '<ul class="product-list clearfix">'; ?>

			<li class="product-listing<?php if(!wpsc_the_product_thumbnail()) echo " no-image"; else echo " yes-image"; ?> default_product_display product_view_<?php echo wpsc_the_product_id(); ?> <?php if(($i % $productsperrow) == 0) echo 'col-right'; ?>" style="width:<?php echo get_option('product_image_width'); ?>px">      	
            <div class="padding">
			<?php if(wpsc_product_external_link(wpsc_the_product_id()) != '') : ?>
                <?php $action =  wpsc_product_external_link(wpsc_the_product_id()); ?>
            <?php else: ?>
				<?php $action = htmlentities(wpsc_this_page_url(), ENT_QUOTES, 'UTF-8' ); ?>					
            <?php endif; ?>					
            <form class="product_form"  enctype="multipart/form-data" action="<?php echo $action; ?>" method="post" name="product_<?php echo wpsc_the_product_id(); ?>" id="product_<?php echo wpsc_the_product_id(); ?>" >
            <input type="hidden" value="add_to_cart" name="wpsc_ajax_action"/>
            <input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="product_id"/>
				  
				<?php if(wpsc_show_thumbnails()) :?>
                <div class="product-meta" style="width:<?php echo get_option('product_image_width'); ?>px;height:<?php echo get_option('product_image_height'); ?>px" >
					
					<?php $crop = prima_get_option('productsuncropthumb') ? false : true ?>
					<?php if(wpsc_the_product_thumbnail()) :?> 	   
                        <div class="imagecol" style="margin:0;padding:0;text-align:center;width:<?php echo get_option('product_image_width'); ?>px;height:<?php echo get_option('product_image_height'); ?>px;line-height:<?php echo get_option('product_image_height'); ?>px;">
							<?php if ( prima_get_option('productsimagelink') == 'image' ) : ?>
                                <a rel="prettyPhoto" class="preview_link" href="<?php echo wpsc_the_product_image(); ?>" title="<?php echo esc_attr(wpsc_the_product_title()); ?>" style="display:block;">
							<?php else : ?>
                                <a href="<?php echo wpsc_the_product_permalink(); ?>">
							<?php endif; ?> 
                            <img class="product_image" id="product_image_<?php echo wpsc_the_product_id(); ?>" alt="<?php echo esc_attr(wpsc_the_product_title()); ?>" src="<?php echo prima_get_image( 0, get_option('product_image_width'), get_option('product_image_height'), $crop ); ?>" <?php if(!$crop) echo 'style="margin:0 auto;width:auto;vertical-align:middle;"' ?>/>
                            </a>
                        </div><!--close imagecol-->
                    <?php else: ?> 
                        <div class="imagecol item_no_image">
                            <a href="<?php echo wpsc_the_product_permalink(); ?>">
                            <img class="no-image" id="product_image_<?php echo wpsc_the_product_id(); ?>" alt="No Image" title="<?php echo wpsc_the_product_title(); ?>" src="<?php echo PRIMA_IMAGES_URL; ?>/noimage.png" width="<?php echo get_option('product_image_width'); ?>" height="<?php echo get_option('product_image_height'); ?>" <?php if(!$crop) echo 'style="margin:0 auto;width:auto;vertical-align:middle;"' ?> />
                            </a>
                        </div><!--close item_no_image-->
                    <?php endif; ?> 
                    
					<?php if ( prima_get_option('productsimagehover') == 'readmore' ) : ?>
						<?php $btnleftpos = intval((get_option('product_image_width') - 109 )/2); ?>
                        <?php $btntoppos = intval((get_option('product_image_height') - 46 )/2); ?>
                        <a class="read-more-but" href="<?php echo wpsc_the_product_permalink(); ?>" title="<?php echo esc_attr(wpsc_the_product_title()); ?>" style="top:<?php echo $btntoppos; ?>px;left:<?php echo $btnleftpos; ?>px"><?php _e( 'Read More', PRIMA_DOMAIN ); ?></a>
					<?php elseif ( prima_get_option('productsimagehover') == 'buynow' ) : ?>
                        <?php if(wpsc_product_has_stock()) : ?>
							<?php $btnleftpos = intval((get_option('product_image_width') - 87 )/2); ?>
                            <?php $btntoppos = intval((get_option('product_image_height') - 43 )/2); ?>
							<?php if(wpsc_product_external_link(wpsc_the_product_id()) != '') : ?>
                                <?php $action = wpsc_product_external_link( wpsc_the_product_id() ); ?>
                                <input  style="top:<?php echo $btntoppos; ?>px;left:<?php echo $btnleftpos; ?>px" class="wpsc_buy_button" type="submit" value="<?php echo wpsc_product_external_link_text( wpsc_the_product_id(), __( 'Buy Now', PRIMA_DOMAIN ) ); ?>" onclick="return gotoexternallink('<?php echo $action; ?>', '<?php echo wpsc_product_external_link_target( wpsc_the_product_id() ); ?>')">
                            <?php else: ?>
                                <input  style="top:<?php echo $btntoppos; ?>px;left:<?php echo $btnleftpos; ?>px" type="submit" value="<?php _e('Add To Cart', PRIMA_DOMAIN); ?>" name="Buy" class="wpsc_buy_button" id="product_<?php echo wpsc_the_product_id(); ?>_submit_button"/>
                            <?php endif; ?>
						<?php endif; ?>
                    <?php endif; ?> 
                    
					<?php if ( !prima_get_option('productshideprice') || !prima_get_option('productshidesaleicon') ) : ?>
					<?php $variationprice = ( prima_get_option('productsvariationprice') == 'max' ) ? false : true; ?>
					<?php $pricedecimal = ( prima_get_option('productshidedecimal') == 'hide' ) ? false : true; ?>
                    <div class="wpsc_product_price <?php if(wpsc_product_on_special()) echo "product-sale" ?>">
                        <?php if(wpsc_product_is_donation()) : ?>
							<?php if ( !prima_get_option('productshideprice') ) : ?>
								<span id="product_price_<?php echo wpsc_the_product_id(); ?>" class="pricedisplay">
								<?php echo __('Donation', PRIMA_DOMAIN); ?></span>					
                            <?php endif; ?>
                        <?php else : ?>
                            <?php if(prima_product_on_special()) : ?>
								<?php if ( !prima_get_option('productshidesaleicon') ) : ?>
									<span class="sale-icon"><?php _e( 'Sale!', PRIMA_DOMAIN ); ?></span>
								<?php endif; ?>
								<?php if ( !prima_get_option('productshideprice') ) : ?>
									<span id="product_price_<?php echo wpsc_the_product_id(); ?>" class="pricedisplay sale-price">
									<?php echo prima_the_product_price( prima_get_option('productsvariationtext'), $pricedecimal, get_the_ID(), $variationprice ); ?></span>					
								<?php endif; ?>
                            <?php else : ?>
								<?php if ( !prima_get_option('productshideprice') ) : ?>
									<span id="product_price_<?php echo wpsc_the_product_id(); ?>" class="pricedisplay">
									<?php echo prima_the_product_price( prima_get_option('productsvariationtext'), $pricedecimal, get_the_ID(), $variationprice ); ?></span>					
								<?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
					<?php endif; ?>
                    
                </div><!--close product-meta-->
				<?php endif; ?> 
                
                <div class="producttext productcol">
                
					<?php if ( !prima_get_option('productshidetitle') ) : ?>
                    <h3 class="prodtitles">
                        <?php if(get_option('hide_name_link') == 1) : ?>
                            <span><?php echo wpsc_the_product_title(); ?></span>
                        <?php else: ?> 
                            <a class="wpsc_product_title" href="<?php echo wpsc_the_product_permalink(); ?>"><?php echo wpsc_the_product_title(); ?></a>
                        <?php endif; ?> 				
						<?php edit_post_link( __( '[Edit]', PRIMA_DOMAIN ) ); ?>
                    </h3>
					<?php endif; ?> 
                        
					<?php							
                        do_action('wpsc_product_before_description', wpsc_the_product_id(), $wp_query->post);
                        do_action('wpsc_product_addons', wpsc_the_product_id());
                    ?>

					<?php if ( prima_get_option('productsdesc') == 'description' ) : ?>
                        <div class="wpsc_description">
							<?php if ( (int)prima_get_option('productsdesclimit') ) : ?>
								<?php the_content_limit( (int)prima_get_option('productsdesclimit'), '' ); ?>
							<?php else : ?>
								<?php echo do_shortcode(wpsc_the_product_description()); ?>
							<?php endif; ?>
                        </div><!--close wpsc_description-->
					<?php elseif ( prima_get_option('productsdesc') == 'additional' ) : ?>
						<?php if(wpsc_the_product_additional_description()) : ?>
                        <div class="wpsc_description">
							<?php if ( (int)prima_get_option('productsadddesclimit') ) : ?>
								<?php the_excerpt_limit( (int)prima_get_option('productsadddesclimit'), '' ); ?>
							<?php else : ?>
								<?php echo do_shortcode(wpautop(wpsc_the_product_additional_description())); ?>
							<?php endif; ?>
                        </div><!--close additional_description-->
                        <?php endif; ?>
					<?php elseif ( prima_get_option('productsdesc') == 'both' ) : ?>
                        <div class="wpsc_description">
							<?php if ( (int)prima_get_option('productsdesclimit') ) : ?>
								<?php the_content_limit( (int)prima_get_option('productsdesclimit'), '' ); ?>
							<?php else : ?>
								<?php echo do_shortcode(wpsc_the_product_description()); ?>
							<?php endif; ?>
                        </div><!--close wpsc_description-->
						<?php if(wpsc_the_product_additional_description()) : ?>
                        <div class="additional_description_container">
                            <img class="additional_description_button"  src="<?php echo PRIMA_IMAGES_URL; ?>/icon_window_expand.gif" alt="Additional Description" />
                            <a href="<?php echo wpsc_the_product_permalink(); ?>" class="additional_description_link"><?php _e('More Details', PRIMA_DOMAIN); ?></a>
                            <div class="additional_description">
								<?php if ( (int)prima_get_option('productsadddesclimit') ) : ?>
									<?php the_excerpt_limit( (int)prima_get_option('productsadddesclimit'), '' ); ?>
								<?php else : ?>
									<?php echo do_shortcode(wpautop(wpsc_the_product_additional_description())); ?>
								<?php endif; ?>
                            </div><!--close additional_description-->
                        </div><!--close additional_description_container-->
                        <?php endif; ?>
                    <?php endif; ?>
                    
					<?php do_action( 'wpsc_product_addon_after_descr', wpsc_the_product_id() ); ?>

					<?php if ( prima_get_option('productsvariations') ) : ?>
					<?php if (wpsc_have_variation_groups()) : ?>
						<div class="wpsc_variation_forms">
						<?php while (wpsc_have_variation_groups()) : wpsc_the_variation_group(); ?>
                            <p>
                            <label for="<?php echo wpsc_vargrp_form_id(); ?>"><?php echo wpsc_the_vargrp_name(); ?>:</label>
                            <br/>
                            <select class="wpsc_select_variation" name="variation[<?php echo wpsc_vargrp_id(); ?>]" id="<?php echo wpsc_vargrp_form_id(); ?>">
                            <?php while (wpsc_have_variations()) : wpsc_the_variation(); ?>
                                <option value="<?php echo wpsc_the_variation_id(); ?>" <?php echo wpsc_the_variation_out_of_stock(); ?>><?php echo wpsc_the_variation_name(); ?></option>
                            <?php endwhile; ?>
                            </select>
                            </p> 
                        <?php endwhile; ?>
						</div><!--close wpsc_variation_forms-->
					<?php endif; ?>
                    <?php endif; ?>
                            
					<?php if ( prima_get_option('productsquantity') ) : ?>
                        <div class="quantity-meta">
                            <p>
                            <label><?php _e('Quantity', PRIMA_DOMAIN); ?>: </label>
                            <span class="wpsc_quantity_update">
                            <input type="text" id="wpsc_quantity_update_<?php echo wpsc_the_product_id(); ?>" name="wpsc_quantity_update" size="2" value="1" />
                            <input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>"/>
                            <input type="hidden" name="wpsc_update_quantity" value="true" />
                            </span><!--close wpsc_quantity_update-->
                            </p>
                        </div>
                    <?php endif; ?>

					<?php if ( prima_get_option('productsbuynow') ) : ?>
						<?php if(wpsc_product_is_donation()) : ?>
                            <p>
                            <label for="donation_price_<?php echo wpsc_the_product_id(); ?>"><?php _e('Donation', PRIMA_DOMAIN); ?>: </label>
                            <input type="text" id="donation_price_<?php echo wpsc_the_product_id(); ?>" name="donation_price" value="<?php echo wpsc_calculate_price(wpsc_the_product_id()); ?>" size="6" />
                            </p>
                        <?php endif; ?> 
                        <?php if(wpsc_product_has_stock()) : ?>
							<?php if(wpsc_product_external_link(wpsc_the_product_id()) != '') : ?>
								<?php $action = wpsc_product_external_link( wpsc_the_product_id() ); ?>
                                <input class="wpsc_buy_button" type="submit" value="<?php echo wpsc_product_external_link_text( wpsc_the_product_id(), __( 'Buy Now', PRIMA_DOMAIN ) ); ?>" onclick="return gotoexternallink('<?php echo $action; ?>', '<?php echo wpsc_product_external_link_target( wpsc_the_product_id() ); ?>')">
                            <?php else: ?>
                                <input type="submit" value="<?php _e('Add To Cart', PRIMA_DOMAIN); ?>" name="Buy" class="wpsc_buy_button" id="product_<?php echo wpsc_the_product_id(); ?>_submit_button"/>
                            <?php endif; ?>
                            <div class="wpsc_loading_animation">
                                <img title="Loading" alt="Loading" src="<?php echo wpsc_loading_animation_url(); ?>" />
                                <?php _e('Updating cart...', PRIMA_DOMAIN); ?>
                            </div><!--close wpsc_loading_animation-->
                        <?php endif ; ?>
                    <?php endif; ?>
							
					<?php if ( prima_get_option('productspaypalbuynow') ) : ?>
						<?php echo wpsc_buy_now_button(wpsc_the_product_id()); ?>
					<?php endif ; ?>
                    
					<?php if ( prima_get_option('productsstockavail') ) : ?>
                        <?php if(wpsc_product_has_stock()) : ?>
							<?php if(wpsc_product_remaining_stock()) : ?>
								<p id="stock_display_<?php echo wpsc_the_product_id(); ?>" class="in_stock"><?php printf( __('%s remaining stock', PRIMA_DOMAIN), wpsc_product_remaining_stock()); ?></p>
							<?php else : ?>						
								<p id="stock_display_<?php echo wpsc_the_product_id(); ?>" class="in_stock"><?php _e('Product in stock', PRIMA_DOMAIN); ?></p>
							<?php endif; ?>						
                        <?php else: ?>
                            <p id="stock_display_<?php echo wpsc_the_product_id(); ?>" class="out_of_stock"><?php _e('Product not in stock', PRIMA_DOMAIN); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                    
				</div><!--close producttext-->
                
            </form>                    
			</div><!--close padding-->
			</li><!--close product-listing-->
			
		<?php if(($i % $productsperrow) == 0) echo "</ul>"; ?>
		<?php $i++; ?> 
		<?php endwhile; ?>
		<?php if(($i % $productsperrow) !== 1) echo "</ul>"; ?>
        
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
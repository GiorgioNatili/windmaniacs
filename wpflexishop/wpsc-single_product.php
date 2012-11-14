<?php
global $wp_query, $product_page_active, $product_page_width, $product_sidebar_width;
$product_page_width = $product_page_width ? $product_page_width : 980;
$product_sidebar_width = $product_sidebar_width ? $product_sidebar_width : 188;
if ($product_page_active) {
	$product_sidebar = prima_get_option('singleproductsidebar');
}
if($product_sidebar == 'yes') {
	$single_product_page_width = $product_page_width - $product_sidebar_width - 20;
	$flexi_imagecol_width = get_option('single_view_image_width')+13;
	$flexi_textcol_width = $single_product_page_width-$flexi_imagecol_width-30;
	if ($flexi_textcol_width < 250) $flexi_textcol_width = $single_product_page_width;
}
else {
	$flexi_imagecol_width = get_option('single_view_image_width')+13;
	$flexi_textcol_width = $product_page_width-$flexi_imagecol_width-30;
	if ($flexi_textcol_width < 250) $flexi_textcol_width = $product_page_width;
}
?>

<div id="flexishop_products_page_container" class="clearfix">
	
<?php if ( $product_page_active ) 
do_action('wpsc_top_of_products_page'); // Plugin hook for adding things to the top of the products page, like the live search ?>
	
<div id="products_page_container" class="wpsc_container single_container <?php if($product_sidebar == 'yes') : ?>products-sidebar<?php endif; ?> ">

<div id="single_product_page_container">

<?php while ( wpsc_have_products() ) : wpsc_the_product(); ?>

<div class="single_product_display group">

    <div class="single-imagecol" style="width:<?php echo $flexi_imagecol_width ?>px">
    	
		<?php echo do_shortcode( prima_get_custom_field('_prima_content_top') ); ?>
		
		<?php 
		if ( !prima_get_custom_field('_prima_hide_image') ) :
			$crop = prima_get_option('singleuncropthumb') ? false : true;
			$attachments = get_children( array( 
				'post_parent' => wpsc_the_product_id(), 
				'post_status' => 'inherit', 
				'post_type' => 'attachment', 
				'post_mime_type' => 'image', 
				'numberposts' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC'
			) );
			$main_slideable = (count($attachments)>1) ? 'main_image_slideable' : '';
			$image_width = get_option('single_view_image_width');
			$image_height = get_option('single_view_image_height');
			echo '<div class="main_image clearfix" id="main_image_'.wpsc_the_product_id().'">';
				$main_img_id = get_post_thumbnail_id( wpsc_the_product_id() );
				if ( $main_img_id ) $first_img_id = $main_img_id;
				elseif ( !$main_img_id && count($attachments)>0 ) $first_img_id = array_shift(array_keys($attachments));
				if ( isset($first_img_id) ) {
					$full_img = prima_get_image( $first_img_id, false, false, false );
					$single_img = prima_get_image( $first_img_id, $image_width, $image_height, $crop );
					$img_title = get_post_meta( $first_img_id, '_wp_attachment_image_alt', true );
					if(!$img_title) $img_title = get_post_field( 'post_title', $first_img_id );
				}
				else {
					$full_img = PRIMA_IMAGES_URL.'/noimage.png';
					$single_img = PRIMA_IMAGES_URL.'/noimage.png';
					$img_title = get_post_field( 'post_title', wpsc_the_product_id() );
				}
				echo '<a rel="prettyPhoto['.wpsc_the_product_id().']" class="preview_link" href="'.$full_img.'" title="'.esc_attr($img_title).'" style="margin:0;padding:0;text-align:center;width:'.($image_width+10).'px;display:block;">';
				echo '<img class="product_image" id="product_image_'.wpsc_the_product_id().'" alt="'.esc_attr(wpsc_the_product_title()).'" src="'.$single_img.'" style="margin:0 auto;width:auto;vertical-align:middle;" />';
				echo '</a>';
			echo '</div>';
			$thumb_width = get_option('wpsc_gallery_image_width') ? get_option('wpsc_gallery_image_width') : 96;
			$thumb_height = get_option('wpsc_gallery_image_height') ? get_option('wpsc_gallery_image_height') : 96;
			if (count($attachments)>1) {
				echo '<div class="single_image_thumb clearfix">';
				if ($main_img_id) {
					$thumb_img = prima_get_image( $main_img_id, $thumb_width, $thumb_height, true );
					$full_img = prima_get_image( $main_img_id, false, false, false );
					$img_title = get_post_meta( $main_img_id, '_wp_attachment_image_alt', true );
					if(!$img_title) $img_title = get_post_field( 'post_title', $main_img_id );
					echo '<a id="image_thumb_'.$main_img_id.'" class="image_thumb_clickable" href="'.$full_img.'" title="'.esc_attr($img_title).'"><img src="'.$thumb_img.'" width="'.$thumb_width.'" height="'.$thumb_height.'" alt="'.esc_attr(wpsc_the_product_title()).'" /></a>'; 
				}
				foreach ( $attachments as $id => $attachment ) {
					if ( $id == $main_img_id )
						continue; 
					$thumb_img = prima_get_image( $id, $thumb_width, $thumb_height, true );
					$full_img = prima_get_image( $id, false, false, false );
					$img_title = get_post_meta( $id, '_wp_attachment_image_alt', true );
					if(!$img_title) $img_title = get_post_field( 'post_title', $id );
					echo '<a id="image_thumb_'.$id.'" class="image_thumb_clickable" href="'.$full_img.'" title="'.esc_attr($img_title).'"><img src="'.$thumb_img.'" width="'.$thumb_width.'" height="'.$thumb_height.'" alt="'.esc_attr(wpsc_the_product_title()).'" /></a>'; 
				}
				echo '</div>';
				echo '<div class="multi_image_box hide">';
				foreach ( $attachments as $id => $attachment ) {
					$multi_img = prima_get_image( $id, $image_width, $image_height, $crop );
					$full_img = prima_get_image( $id, false, false, false );
					$multi_img_title = get_post_meta( $id, '_wp_attachment_image_alt', true );
					if(!$multi_img_title) $multi_img_title = get_post_field( 'post_title', $id );
					echo '<a rel="prettyPhoto['.wpsc_the_product_id().']" id="single_image_thumb_'.$id.'" href="'.$full_img.'" title="'.esc_attr($multi_img_title).'"><img src="'.$multi_img.'" alt="'.esc_attr(wpsc_the_product_title()).'" /></a>'; 
				}
				echo '</div>';
			}
		endif;
		?>
        
		<?php echo do_shortcode( prima_get_custom_field('_prima_content_bottom') ); ?>

		<?php if ( !prima_get_option('singlehidesaleicon') ) : ?>
		<?php if(prima_product_on_special()) : ?>
            <span class="sale-icon-single" style="z-index:101"><?php _e( 'Sale!', PRIMA_DOMAIN ); ?></span>
        <?php endif; ?>
        <?php endif; ?>
         
    </div><!--close single-imagecol-->

    <div class="productcol single-producttext" style="width:<?php echo $flexi_textcol_width ?>px">			

		<?php if ( !prima_get_option('singlehideprice') ) : ?>
		<?php $variationprice = ( prima_get_option('singlevariationprice') == 'max' ) ? false : true; ?>
		<?php $pricedecimal = ( prima_get_option('singlehidedecimal') == 'hide' ) ? false : true; ?>
        <div class="wpsc_product_price">
            <?php if(!wpsc_product_is_donation()) : ?>
                <?php if(prima_product_on_special()) : ?>
                    <p class="pricedisplay <?php echo wpsc_the_product_id(); ?>"><?php _e('Price', PRIMA_DOMAIN); ?>: <span class="oldprice" id="old_product_price_<?php echo wpsc_the_product_id(); ?>"><?php echo prima_product_normal_price( __('from', PRIMA_DOMAIN ), true ); ?></span></p>
                    <h3 class="sale-price-header">
			<?php _e( 'On Sale:', PRIMA_DOMAIN ); ?><span id="product_price_<?php echo wpsc_the_product_id(); ?>" class="pricedisplay sale-price"><?php echo prima_the_product_price( prima_get_option('singlevariationtext'), $pricedecimal, get_the_ID(), $variationprice ); ?></span>
                    </h3>
                    <p class="pricedisplay product_<?php echo wpsc_the_product_id(); ?>"><?php _e('You save', PRIMA_DOMAIN); ?>: <span class="yousave" id="yousave_<?php echo wpsc_the_product_id(); ?>"><?php echo prima_yousave( 'amount', __('up to', PRIMA_DOMAIN), $pricedecimal ); ?>! (<?php echo prima_yousave( 'percent', '', $pricedecimal ); ?>%)</span></p>
                <?php else : ?>
                    <h3 class=""><?php _e( 'Price:', PRIMA_DOMAIN ); ?> 
                    <span id="product_price_<?php echo wpsc_the_product_id(); ?>" class="pricedisplay">
                    <?php echo prima_the_product_price( prima_get_option('singlevariationtext'), $pricedecimal, get_the_ID(), $variationprice ); ?></span>
                    </h3>
                <?php endif; ?>
                <?php if(prima_product_has_multicurrency()) : ?>
                     <!-- multi currency code -->
                    <?php /* available tags: %country% %isocode% %currency% %symbol% %code% %price% */ ?>
                    <?php echo prima_display_product_multicurrency('%isocode% %symbol% %price%', true); ?>
                <?php endif; ?>
            <?php endif; ?>
        </div><!--close wpsc_product_price -->
		<?php endif; ?>

		<?php if(prima_get_option('singlesku')) : ?>
        <?php if(wpsc_product_sku()) : ?>
        <div class="wpsc_sku">
            <p><?php _e('SKU: ', PRIMA_DOMAIN); echo wpsc_product_sku(); ?>
        </div><!--close wpsc_sku-->
        <?php endif; ?>
        <?php endif; ?>

        <?php do_action('wpsc_product_before_description', wpsc_the_product_id(), $wp_query->post); ?>
        
        <?php if(wpsc_the_product_description()) : ?>
        <div class="wpsc_description">
            <?php echo do_shortcode(wpsc_the_product_description()); ?>
        </div><!--close wpsc_description-->
        <?php endif; ?>

        <?php do_action( 'wpsc_product_addons', wpsc_the_product_id() ); ?>		

        <?php if(wpsc_the_product_additional_description()) : ?>
        <div class="additional_description_container">
            <img class="additional_description_button"  src="<?php echo PRIMA_IMAGES_URL; ?>/icon_window_expand.gif" alt="Additional Description" />
            <a href="<?php echo wpsc_the_product_permalink(); ?>" class="additional_description_link"><?php _e('More Details', PRIMA_DOMAIN); ?></a>
            <div class="additional_description">
                <p><?php echo do_shortcode(wpsc_the_product_additional_description()); ?></p>
            </div><!--close additional_description-->
        </div><!--close additional_description_container-->
        <?php endif; ?>

        <?php do_action( 'wpsc_product_addon_after_descr', wpsc_the_product_id() ); ?>
        
        <?php if (wpsc_have_custom_meta()) : ?>
        <!--<div class="custom_meta">
            <?php while ( wpsc_have_custom_meta() ) : wpsc_the_custom_meta(); ?>
                <strong><?php echo wpsc_custom_meta_name(); ?>: </strong><?php echo wpsc_custom_meta_value(); ?><br />
            <?php endwhile; ?>
        </div>--><!--close custom_meta-->
        <?php endif; ?>
        
        <form class="product_form" enctype="multipart/form-data" action="<?php echo wpsc_this_page_url(); ?>" method="post" name="1" id="product_<?php echo wpsc_the_product_id(); ?>">
        
		<?php if ( wpsc_product_has_personal_text() ) : ?>
            <fieldset class="custom_text">
                <legend><?php _e( 'Personalize Your Product', PRIMA_DOMAIN ); ?></legend>
                <p><?php _e( 'Complete this form to include a personalized message with your purchase.', PRIMA_DOMAIN ); ?></p>
                <textarea cols='55' rows='5' name="custom_text"></textarea>
            </fieldset>
        <?php endif; ?>
    
        <?php if ( wpsc_product_has_supplied_file() ) : ?>
            <fieldset class="custom_file">
                <legend><?php _e( 'Upload a File', PRIMA_DOMAIN ); ?></legend>
                <p><?php _e( 'Select a file from your computer to include with this purchase.', PRIMA_DOMAIN ); ?></p>
                <input type="file" name="custom_file" />
            </fieldset>
        <?php endif; ?>	
            
		<?php if (wpsc_have_variation_groups() || wpsc_has_multi_adding()) : ?>
        <div class="single-product-meta">
            
            <?php if (wpsc_have_variation_groups()) { ?>
            <div class="wpsc_variation_forms">
                <ul class="variation-groups">
                <?php while (wpsc_have_variation_groups()) : wpsc_the_variation_group(); ?>
                    <li>
                        <label for="<?php echo wpsc_vargrp_form_id(); ?>"><?php echo wpsc_the_vargrp_name(); ?>:</label>
                        <?php /** the variation HTML and loop */?>
                        <select class='wpsc_select_variation' name="variation[<?php echo wpsc_vargrp_id(); ?>]" id="<?php echo wpsc_vargrp_form_id(); ?>">
                        <?php while (wpsc_have_variations()) : wpsc_the_variation(); ?>
                            <option value="<?php echo wpsc_the_variation_id(); ?>" <?php echo wpsc_the_variation_out_of_stock(); ?>><?php echo wpsc_the_variation_name(); ?></option>
                        <?php endwhile; ?>
                        </select> 
                    </li>
                <?php endwhile; ?>
                </ul>
            </div><!--close wpsc_variation_forms-->
            <?php } ?>
            
			<?php if(wpsc_has_multi_adding()): ?>
            <div class="quantity-meta">
                    <fieldset>
                    <label><?php _e('Quantity', PRIMA_DOMAIN); ?>: </label>
                    <span class="wpsc_quantity_update">
                    <input class="flexi_quantity_update" type="text" id="wpsc_quantity_update_<?php echo wpsc_the_product_id(); ?>" name="wpsc_quantity_update" size="2" value="1" />
                    <input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>"/>
                    <input type="hidden" name="wpsc_update_quantity" value="true" />
                    </span><!--close wpsc_quantity_update-->
                    </fieldset>
            </div>
			<?php endif ;?>
            
        </div>
        <?php endif; ?>	

        <div class="wpsc_product_price">
        
            <?php if(wpsc_show_stock_availability()): ?>
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
            
            <?php if(wpsc_product_is_donation()) : ?>
                <label for="donation_price_<?php echo wpsc_the_product_id(); ?>"><?php _e('Donation', PRIMA_DOMAIN); ?>: </label>
                <input type="text" id="donation_price_<?php echo wpsc_the_product_id(); ?>" name="donation_price" value="<?php echo wpsc_calculate_price(wpsc_the_product_id()); ?>" size="6" />
            <?php endif; ?>
        </div><!--close wpsc_product_price-->
            
        <input type="hidden" value="add_to_cart" name="wpsc_ajax_action" />
        <input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="product_id" />					
        
        <?php if( wpsc_product_is_customisable() ) : ?>
            <input type="hidden" value="true" name="is_customisable"/>
        <?php endif; ?>

		<?php if((get_option('hide_addtocart_button') == 0) &&  (get_option('addtocart_or_buynow') !='1')) : ?>
            <?php if(wpsc_product_has_stock()) : ?>
                <div class="wpsc_buy_button_container">
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
                </div><!--close wpsc_buy_button_container-->
            <?php else : ?>
				<?php if(!wpsc_show_stock_availability()): ?>
                    <p class="soldout"><?php _e('This product has sold out.', PRIMA_DOMAIN); ?></p>
				<?php endif ; ?>
            <?php endif ; ?>
        <?php endif ; ?>
        
		<?php if(!wpsc_product_is_donation() && wpsc_show_pnp()) : ?>
            <h4 class="shipping"><?php _e('Shipping', PRIMA_DOMAIN); ?>:<span class="pp_price"><?php echo wpsc_product_postage_and_packaging(); ?></span></h4>
        <?php endif; ?>							
           
        </form><!--close product_form-->
    
        <?php if ( (get_option( 'hide_addtocart_button' ) == 0 ) && ( get_option( 'addtocart_or_buynow' ) == '1' ) )
        echo wpsc_buy_now_button( wpsc_the_product_id() );  ?>
        
        <?php if(prima_get_option('singleproductcategory'))
		echo get_the_term_list( '', 'wpsc_product_category', '<p>'.__('Product Categories:', PRIMA_DOMAIN).' ', ', ', '</p>' ) ?>
		
        <?php if(prima_get_option('singleproducttag'))
		echo get_the_term_list( '', 'product_tag', '<p>'.__('Product Tags:', PRIMA_DOMAIN).' ', ', ', '</p>' ) ?>
    
    	<?php echo wpsc_product_rater();  ?>

        <?php if ( get_option( 'wpsc_share_this' ) == 1 ): ?>
            <div class="st_sharethis" displayText="ShareThis"></div>
        <?php endif; ?>
        
        <?php if(wpsc_show_fb_like()): ?>
            <div class="FB_like">
            <iframe src="https://www.facebook.com/plugins/like.php?href=<?php echo wpsc_the_product_permalink(); ?>&amp;layout=standard&amp;show_faces=true&amp;width=435&amp;action=like&amp;font=arial&amp;colorscheme=light" frameborder="0"></iframe>
            </div><!--close FB_like-->
        <?php endif; ?>

        <?php include("prod_footer_custom.php"); ?>
        
    </div><!--close productcol-->
		
    <form onsubmit="submitform(this);return false;" action="<?php echo wpsc_this_page_url(); ?>" method="post" name="product_<?php echo wpsc_the_product_id(); ?>" id="product_extra_<?php echo wpsc_the_product_id(); ?>">
        <input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="prodid"/>
        <input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="item"/>
    </form>
                    
</div><!--close single_product_display-->
		
<?php 
if(prima_get_option('singlecrosssales')) prima_also_bought( wpsc_the_product_id(), prima_get_option('singlecrosssalesnumber'), prima_get_option('singlecrosssaleswidth'), prima_get_option('singlecrosssalesheight') );  ?>

<?php if(prima_get_option('singlerelated')) prima_related_products( wpsc_the_product_id(), prima_get_option('singlerelatednumber'), prima_get_option('singlerelatedwidth'), prima_get_option('singlerelatedheight') );  ?>

<?php prima_product_comments(); ?>

<?php endwhile; ?>

<?php if ( $product_page_active ) 
do_action( 'wpsc_theme_footer' ); ?> 	

</div><!--close single_product_page_container-->

<?php if($product_sidebar == 'yes') : ?>
	<div id="products-sidebar" class="single-products-sidebar">
		<?php if ( !prima_get_option('singleproductcatssidebar') ) : ?>
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
		<?php if ( is_active_sidebar( 'single-product-sidebar' ) ) : ?>
			<ul class="xoxo">
				<?php dynamic_sidebar( 'single-product-sidebar' ); ?>
			</ul>
		<?php endif; ?>	
	</div>
<?php endif; ?>

</div><!--close products_page_container-->

<?php if ( $product_page_active ) 
do_action( 'wpsc_theme_footer' ); ?> 	

</div><!--close flexi_products_page_container-->
<?php
/**
 * Template Name: Frontpage
 */
global $wp_query, $current_page_id;
$orig_query = $wp_query;
$current_page_id = $wp_query->get_queried_object_id();
get_header();
?>

<div id="header-wrapper">
	<?php get_template_part( 'flexi-header' ); ?>
	<?php $products = array(); $promotions = array(); $posts = array(); ?>
	<?php $slider_query = new WP_Query('post_type=slider&posts_per_page=-1'); ?>
	<?php if ($slider_query->have_posts()) : ?>
    <div id="leader" class="container">
        <div class="margin">
			<?php
            $post_id = $wp_query->get_queried_object_id();
            $title = get_post_field( 'post_title', $post_id );
			?>
			<h1 class="page-special-title hide"><?php echo $title; ?></h1>
            <div id="featured-slider" class="clearfix">
                <div id="feature-wrapper">
                <div id="features">
                    <ul class="feature-list">
                        <?php while ($slider_query->have_posts()) : $slider_query->the_post();
							$width_product = prima_get_option('sliderproductwidth') ? prima_get_option('sliderproductwidth') : 500;
							$width = (prima_get_option('themelayout') == 'boxed') ? 896 : 980;
							$height = prima_get_option('sliderheight') ? prima_get_option('sliderheight') : 375;
                            $postid = get_the_ID();
                            $slider_type = prima_get_custom_field("sliderType");
                            if ($slider_type == 'simpleslider'){
                                prima_slider_simple($postid, $width, $height);
                            }
                            elseif ($slider_type == 'product'){
                                $slider_id = prima_get_custom_field("sliderProductId");
                                prima_slider_product($slider_id, $width_product, $height);
                            }
                            elseif ($slider_type == 'news'){
                                $slider_id = prima_get_custom_field("sliderPostId");
                                prima_slider_post($slider_id, $width, $height);
                            }
                            elseif ($slider_type == 'promotion'){
                                $slider_id = prima_get_custom_field("sliderPromotionId");
                                prima_slider_promotion($slider_id, $width, $height);
                            }
                        endwhile; 
                        ?>
                    </ul>
                </div>
                </div>
            </div>
            <div id="slider-controls"></div>
        </div>
    </div>
	<?php endif; ?>
</div>

<div id="content-wrapper">

	<?php if( prima_get_option('sitedesc') ) : ?>
	<div id="brief" class="container">
		<div class="margin">
			<p><?php echo prima_get_option('sitedesc'); ?></p>
		</div>
	</div>
	<?php endif; ?>
    
	<?php if (is_active_sidebar( 'home-left'  ) || is_active_sidebar( 'home-middle' ) || is_active_sidebar( 'home-right'  )) : ?>
	<div id="front-content">
		<div class="margin">
		<?php if ( is_active_sidebar( 'home-left' ) ) : ?>
			<div class="col-3">
				<?php dynamic_sidebar( 'home-left' ); ?>
			</div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'home-middle' ) ) : ?>
			<div class="col-3">
				<?php dynamic_sidebar( 'home-middle' ); ?>
			</div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'home-right' ) ) : ?>
			<div class="col-3 col-right">
				<?php dynamic_sidebar( 'home-right' ); ?>
			</div>
		<?php endif; ?>
		</div>
	</div>	
	<?php endif; ?>

	<div id="main-content" class="container">	
		<div class="margin">
			<div id="full-col">
				<?php
				if (prima_get_option('themelayout') == 'boxed')
					$page_width = 920;
				else
					$page_width = 980;
				?>
				
				<?php 
				if (prima_get_option('homepagecategories')) {
					$title = __( 'Categories', PRIMA_DOMAIN );
					$number = prima_get_option('homepagecatnumber') ? prima_get_option('homepagecatnumber') : 10;
					$width = prima_get_option('homepagecatwidth') ? prima_get_option('homepagecatwidth') : 186;
					$height = prima_get_option('homepagecatheight') ? prima_get_option('homepagecatheight') : 186;
					$name = prima_get_option('homepagecatname') ? 'name="alwaysshow"' : '';
					$slider = prima_get_option('homepagecatslide') ? intval($page_width / $width) : 'no';
					$slider_auto = prima_get_option('homepagecatslideauto') ? 'yes' : 'no';
					$catsid = prima_get_option('homepagecatsid');
					if ( !$catsid || ($catsid == 'groups') ) $catsid = 'parent="0"';
					elseif ( $catsid == 'all' ) $catsid = ''; 
					elseif ( is_numeric($catsid) ) $catsid = 'child_of="'.$catsid.'"'; 
					$title_bg = prima_get_color('frontcatsbgcol') ? ' title_bg="'.prima_get_color('frontcatsbgcol').'"' : '';
					$title_color = prima_get_color('frontcatscol') ? ' title_color="'.prima_get_color('frontcatscol').'"' : '';
					$name_bg = prima_get_color('frontcatnamebgcol') ? ' name_bg="'.prima_get_color('frontcatnamebgcol').'"' : '';
					$name_color = prima_get_color('frontcatnamecol') ? ' name_color="'.prima_get_color('frontcatnamecol').'"' : '';

					echo do_shortcode('[prima_categories '.$catsid.' title="'.$title.'" number="'.$number.'" image_width="'.$width.'" image_height="'.$height.'" '.$name.' slider="'.$slider.'" slider_auto="'.$slider_auto.'" '.$title_bg.$title_color.$name_bg.$name_color.']');
				}
				?>
				
				<?php 
				if (prima_get_option('homepagebestsellers')) {
					$title = __( 'Best Sellers', PRIMA_DOMAIN );
					$number = prima_get_option('homepagebestnumber') ? prima_get_option('homepagebestnumber') : 10;
					$width = prima_get_option('homepagebestwidth') ? prima_get_option('homepagebestwidth') : 186;
					$height = prima_get_option('homepagebestheight') ? prima_get_option('homepagebestheight') : 186;
					$name = prima_get_option('homepagebestname') ? 'name="alwaysshow"' : '';
					$slider = prima_get_option('homepagebestslide') ? intval($page_width / $width) : 'no';
					$slider_auto = prima_get_option('homepagebestslideauto') ? 'yes' : 'no';
					$sale_icon = prima_get_option('homepagebestsale') ? 'sale_icon="yes"' : '';
					$title_bg = prima_get_color('frontbestsbgcol') ? ' title_bg="'.prima_get_color('frontbestsbgcol').'"' : '';
					$title_color = prima_get_color('frontbestscol') ? ' title_color="'.prima_get_color('frontbestscol').'"' : '';
					$name_bg = prima_get_color('frontbestprodbgcol') ? ' name_bg="'.prima_get_color('frontbestprodbgcol').'"' : '';
					$name_color = prima_get_color('frontbestprodcol') ? ' name_color="'.prima_get_color('frontbestprodcol').'"' : '';
					
					echo do_shortcode('[prima_bestsellers '.$sale_icon.' title="'.$title.'" number="'.$number.'" image_width="'.$width.'" image_height="'.$height.'" '.$name.' slider="'.$slider.'" slider_auto="'.$slider_auto.'" '.$title_bg.$title_color.$name_bg.$name_color.']'); 
				}
				?>
				
				<?php 
				if (prima_get_option('homepagelatestproducts')) {
					$title = __( 'Latest Products', PRIMA_DOMAIN );
					$number = prima_get_option('homepagelatestnumber') ? prima_get_option('homepagelatestnumber') : 10;
					$width = prima_get_option('homepagelatestwidth') ? prima_get_option('homepagelatestwidth') : 186;
					$height = prima_get_option('homepagelatestheight') ? prima_get_option('homepagelatestheight') : 186;
					$name = prima_get_option('homepagelatestname') ? 'name="alwaysshow"' : '';
					$slider = prima_get_option('homepagelatestslide') ? intval($page_width / $width) : 'no';
					$slider_auto = prima_get_option('homepagelatestslideauto') ? 'yes' : 'no';
					$sale_icon = prima_get_option('homepagelatestsale') ? 'sale_icon="yes"' : '';
					$title_bg = prima_get_color('frontlatestsbgcol') ? ' title_bg="'.prima_get_color('frontlatestsbgcol').'"' : '';
					$title_color = prima_get_color('frontlatestscol') ? ' title_color="'.prima_get_color('frontlatestscol').'"' : '';
					$name_bg = prima_get_color('frontlatestprodbgcol') ? ' name_bg="'.prima_get_color('frontlatestprodbgcol').'"' : '';
					$name_color = prima_get_color('frontlatestprodcol') ? ' name_color="'.prima_get_color('frontlatestprodcol').'"' : '';
					
					echo do_shortcode('[prima_products '.$sale_icon.' title="'.$title.'" number="'.$number.'" image_width="'.$width.'" image_height="'.$height.'" '.$name.' slider="'.$slider.'" slider_auto="'.$slider_auto.'" '.$title_bg.$title_color.$name_bg.$name_color.']'); 
				}
				?>
				
				<?php 
				if (prima_get_option('homepagetestimonials')) {
					$number = prima_get_option('homepagetestinumber') ? prima_get_option('homepagetestinumber') : 3;
					echo do_shortcode('[prima_testimonials slider="yes" number="'.$number.'"]'); 
				}
				?>

				<?php $frontpage_query = new WP_Query( array ( 'page_id' => $current_page_id ) ); ?>
				<?php if ( $frontpage_query->have_posts() ) while ( $frontpage_query->have_posts() ) : $frontpage_query->the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
    
</div>

<?php get_footer(); ?>
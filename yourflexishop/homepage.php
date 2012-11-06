<?php
/**
 * Template Name: Homepage
 */
global $wp_query, $product_page_width, $product_sidebar, $product_sidebar_width;
$product_sidebar = false;
if (prima_get_option('themelayout') == 'boxed') { $product_page_width = 920; }
else { $product_page_width = 980; }

get_header(); ?>

<div id="header-wrapper">
	<?php get_template_part( 'flexi-header' ); ?>
	<?php $slider_query = new WP_Query('post_type=slider&posts_per_page=-1'); ?>
	<?php if ($slider_query->have_posts()) : ?>
    <div id="leader" class="container">
        <div class="margin clearfix">
			<h1 class="page-special-title hide"><?php the_title(); ?></h1>
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
	<div id="main-content" class="container">
    <div id="search-key-words">
        <form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
            <input type="text" size="put_a_size_here" name="s" id="s" value="Ricerca per parole chiave" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/>
        </form>
    </div>
	<?php b4k_search_form(); ?>
		<div class="margin">
			<div id="full-col">
				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
                    <?php wp_link_pages( array( 'before' => '' . __( 'Pages:', PRIMA_DOMAIN ), 'after' => '' ) ); ?>
                    <?php edit_post_link( __( 'Edit', PRIMA_DOMAIN ), '', '' ); ?>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>

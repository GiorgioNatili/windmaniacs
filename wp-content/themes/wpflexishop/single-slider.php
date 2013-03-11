<?php
global $wp_query, $current_page_id;
$orig_query = $wp_query;
$current_page_id = $wp_query->get_queried_object_id();
get_header();
?>

<div id="header-wrapper">
	<?php get_template_part( 'flexi-header' ); ?>
    <div id="leader" class="container">
        <div class="margin">
			<?php $title = get_post_field( 'post_title', $current_page_id ); ?>
			<h1 class="page-special-title hide"><?php echo $title; ?></h1>
            <div id="featured-slider" class="clearfix">
                <div id="feature-wrapper">
                <div id="features">
                    <ul class="feature-list">
                        <?php 
							$width_product = prima_get_option('sliderproductwidth') ? prima_get_option('sliderproductwidth') : 500;
							$width = (prima_get_option('themelayout') == 'boxed') ? 896 : 980;
							$height = prima_get_option('sliderheight') ? prima_get_option('sliderheight') : 375;
                            $postid = get_the_ID();
                            $slider_type = prima_get_custom_field("sliderType", $current_page_id);
                            if ($slider_type == 'simpleslider'){
                                prima_slider_simple($postid, $width, $height);
                            }
                            elseif ($slider_type == 'product'){
                                $slider_id = prima_get_custom_field("sliderProductId", $current_page_id);
                                prima_slider_product($slider_id, $width_product, $height);
                            }
                            elseif ($slider_type == 'news'){
                                $slider_id = prima_get_custom_field("sliderPostId", $current_page_id);
                                prima_slider_post($slider_id, $width, $height);
                            }
                            elseif ($slider_type == 'promotion'){
                                $slider_id = prima_get_custom_field("sliderPromotionId", $current_page_id);
                                prima_slider_promotion($slider_id, $width, $height);
                            }
                        ?>
                    </ul>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
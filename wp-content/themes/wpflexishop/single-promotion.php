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
							prima_slider_promotion($current_page_id, $width, $height);
                        ?>
                    </ul>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
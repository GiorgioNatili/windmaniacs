<?php

function prima_slider_simple($slider_id, $width = null, $height = null){ 
	if ( !$width ) {
		if (prima_get_option('themelayout') == 'boxed') $width = 896;
		else $width = 980;
	}
	if ( !$height ) {
		$height = prima_get_option('sliderheight') ? prima_get_option('sliderheight') : 375;
	}
	$args = array(
		'suppress_filters' => true,
		'post__in'  => explode(",", $slider_id),
		'post_type' => array('slider')
	); 
	$postid = $slider_id;
	$post_thumbnail_id = get_post_thumbnail_id( $postid );
	if ($post_thumbnail_id)
		$image = prima_get_image($post_thumbnail_id, $width, $height);
	if ( isset($image) && $image ) :
	?>
	<li class="feature">
		<div class="post-background">
			<?php $custom = get_post_custom($postid);
			$link = isset($custom["link"][0]) ? $custom["link"][0] : false;
			?>
			<?php 
			if ($link ) {
				if ($custom['pretty_photo'][0] == 'yes') 
					echo '<a href="'.$link.'" rel="prettyPhoto" title="'.esc_attr(get_the_title()).'">'; 
				else
					echo '<a href="'.$link.'" title="'.esc_attr(get_the_title()).'" class="featured-blog-image">'; 
			}
			?>
			<?php if($image) echo '<img src="'.$image.'" width="'.$width.'" alt="'.esc_attr(get_the_title()).'" />'; ?>
            <?php if ($link ) { echo '</a>'; } ?>
		</div>
	</li>
	<?php
	endif;
}

function prima_slider_post($slider_id, $width = null, $height = null){ 
	if ( !$width ) {
		if (prima_get_option('themelayout') == 'boxed') $width = 896;
		else $width = 980;
	}
	if ( !$height ) {
		$height = prima_get_option('sliderheight') ? prima_get_option('sliderheight') : 375;
	}
	$args = array(
		'suppress_filters' => true,
		'post__in'  => explode(",", $slider_id),
		'post_type' => array('post','page')
	); ?>
	<?php $feature_query = new WP_Query($args); ?>
	<?php if ($feature_query->have_posts()) : while ($feature_query->have_posts()) : $feature_query->the_post(); ?>
	<?php $postid = get_the_ID(); ?>
	<li class="feature">
		<div class="post-background">
			<?php 
			$post_thumbnail_id = get_post_thumbnail_id( $postid );
			if ($post_thumbnail_id)
				$image = prima_get_image($post_thumbnail_id, $width, $height);
            ?>
			<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="featured-blog-image">
			<?php if($image) echo '<img src="'.$image.'" width="'.$width.'" alt="'.esc_attr(get_the_title()).'" />'; ?>
            </a>
		</div>
		<?php if ( prima_get_option('sliderposttitle') || prima_get_option('sliderpostexcerpt') ) : ?>
		<div class="feature-post-wrapper">
			<?php if ( prima_get_option('sliderposttitle') ) : ?>
			<div class="post-header">
				<h3><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
			</div>
			<?php endif; ?>
			<?php if ( prima_get_option('sliderpostexcerpt') ) : ?>
			<div class="post-excerpt">
				<?php the_excerpt();?>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</li>
	<?php endwhile; endif;
}

function prima_slider_promotion($slider_id, $width = null, $height = null){
	if ( !$width ) {
		if (prima_get_option('themelayout') == 'boxed') $width = 896;
		else $width = 980;
	}
	if ( !$height ) {
		$height = prima_get_option('sliderheight') ? prima_get_option('sliderheight') : 375;
	}
	$args = array(
		'suppress_filters' => true,
		'post__in'  => explode(",", $slider_id),
		'post_type' => 'promotion'
	);
	?>
	<?php $promotion_query = new WP_Query($args); ?>
	<?php if ($promotion_query->have_posts()) : while ($promotion_query->have_posts()) : $promotion_query->the_post(); ?>
	<?php $postid = get_the_ID(); ?>
	<?php 
		$post_thumbnail_id = get_post_thumbnail_id( $postid );
		if ($post_thumbnail_id)
			$image = prima_get_image($post_thumbnail_id, $width, $height);
	?>
	<?php $postid = get_the_ID(); ?>
	<?php
	$saving = get_post_meta($postid,"saving",true);
	$external_link = get_post_meta($postid,"link",true);
	$link_type = get_post_meta($postid,"link_type",true);
	$start_date = get_post_meta($postid,"start_date",true);
	$end_date = get_post_meta($postid,"end_date",true);
	$promotion_link = get_post_meta($postid,"promotion_link",true);
	$promotion_link_category = get_post_meta($postid,"promotion_link_category",true);
	if ($link_type == "category") $link = $promotion_link_category;
	elseif ($link_type =="product") $link = $promotion_link;
	elseif ($link_type == "external") $link = $external_link;
	?>
	<li class="promotion">
		<?php if (get_post_meta($postid,"display_text",true) == 'yes') : ?>
		<div class="promotion-text">
			<div class="promotion-header">
				<h3><?php the_title(); ?></h3>
			</div>
			<div class="promotion-meta">
				<?php _e('Savings:', PRIMA_DOMAIN) ?> <span class="saving"><?php echo $saving ?></span>
				<?php _e('Start Date:', PRIMA_DOMAIN) ?> <span class="start-date"><?php echo $start_date ?></span>
				<?php _e('End Date:', PRIMA_DOMAIN) ?> <span class="end-date"><?php echo $end_date ?></span>
			</div>
			<?php if ( get_the_excerpt() ) { ?>
			<div class="promotion-excerpt">
				<?php the_excerpt();?>
			</div>
			<?php } ?>
		</div>
		<?php endif; ?>
		<div class="promotion-header">
			<?php 
			if ($link ) {
				if (get_post_meta($postid,"pretty_photo",true) == 'yes' && $link_type == 'external') 
					echo '<a href="'.$link.'" rel="prettyPhoto" title="'.esc_attr(get_the_title()).'">'; 
				else
					echo '<a href="'.$link.'" title="'.esc_attr(get_the_title()).'">'; 
			}
			?>
			<?php if($image) echo '<img src="'.$image.'" width="'.$width.'" alt="'.esc_attr(get_the_title()).'" />'; ?>
            <?php if ($link ) { echo '</a>'; } ?>
		</div>
	</li>
	<?php endwhile; endif;
}

function prima_slider_product($slider_id, $width = null, $height = null){
	if(!class_exists('WP_eCommerce')) return;
	if ( !$width ) {
		$width = prima_get_option('sliderproductwidth') ? prima_get_option('sliderproductwidth') : 500;
	}
	if ( !$height ) {
		$height = prima_get_option('sliderheight') ? prima_get_option('sliderheight') : 375;
	}
	$args = array(
		'suppress_filters' => true,
		'post__in'  => explode(",", $slider_id),
		'post_type' => 'wpsc-product'
	);
	?>
	<?php $product_query = new WP_Query($args); ?>
	<?php if ($product_query->have_posts()) : while ($product_query->have_posts()) : $product_query->the_post(); ?>
    <li class="feature-product">
		<?php if(wpsc_the_product_thumbnail()) :?>
        <div class="product-image">
            <a href="<?php echo wpsc_the_product_permalink(); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
                <img class="product_image" id="product_image_<?php echo wpsc_the_product_id(); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" title="<?php echo esc_attr(get_the_title()); ?>" src="<?php echo wpsc_the_product_thumbnail($width,$height); ?>" />
            </a>
        </div>
        <?php else: ?>
        <div class="product-image product-thumb item_no_image">
            <a href="<?php echo wpsc_the_product_permalink(); ?>">
                <img class="no-image" id="product_image_<?php echo wpsc_the_product_id(); ?>" alt="<?php _e( 'No Image', PRIMA_DOMAIN ); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" src="<?php echo PRIMA_IMAGES_URL; ?>/noimage.png" width="<?php echo $width; ?>" />
            </a>
        </div>
        <?php endif; ?>
        <div class="product-content">
            <h2 class="prodtitles">
                <a class="wpsc_product_title" href="<?php echo wpsc_the_product_permalink(); ?>"><?php echo wpsc_the_product_title(); ?></a>
            </h2>
			<?php if ( prima_get_option('sliderproductprice') ) : ?>
				<p><?php echo __('Price:', PRIMA_DOMAIN).' '.prima_the_product_price( __('from', PRIMA_DOMAIN ), true ); ?></p>
            <?php endif; ?>
			<?php if ( prima_get_option('sliderproductdesc') ) : ?>
            <div class="description">
				<?php if ( (int)prima_get_option('sliderproductdesclimit') ) : ?>
					<?php the_content_limit( (int)prima_get_option('sliderproductdesclimit'), '' ); ?>
				<?php else : ?>
					<?php echo wpsc_the_product_description(); ?>
				<?php endif; ?>
            </div>
            <?php endif; ?>
			<?php if ( prima_get_option('sliderproductadddesc') ) : ?>
            <div class="description">
				<?php if ( (int)prima_get_option('sliderproductadddesclimit') ) : ?>
					<?php the_excerpt_limit( (int)prima_get_option('sliderproductadddesclimit'), '' ); ?>
				<?php else : ?>
					<?php echo wpautop(wpsc_the_product_additional_description()); ?>
				<?php endif; ?>
            </div>
            <?php endif; ?>
            <a href="<?php echo wpsc_the_product_permalink(); ?>" class="buy-now"><?php _e( 'Buy Now', PRIMA_DOMAIN ); ?></a>
            </div>
    </li>
	<?php endwhile; endif;
}


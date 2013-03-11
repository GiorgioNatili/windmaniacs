<?php
/**
 * Template Name: Slideshow
 */
global $wp_query, $current_page_id;
$orig_query = $wp_query;
$current_page_id = $wp_query->get_queried_object_id();
get_header();
?>

<div id="header-wrapper">
	<?php get_template_part( 'flexi-header' ); ?>
    <div id="leader" class="container">
        <div class="margin clearfix">
			<?php
            $post_id = $wp_query->get_queried_object_id();
            $title = get_post_field( 'post_title', $post_id );
			?>
			<h1 class="page-special-title hide"><?php echo $title; ?></h1>
			<?php 
            global $post; 
            $attachments = get_children( array( 
				'post_parent' => $post->ID, 
				'post_status' => 'inherit', 
				'post_type' => 'attachment', 
				'post_mime_type' => 'image',
				'numberposts' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC'
			) );
            if ( !empty($attachments) ) {
                $i=0;
                $slideshow = '';
                foreach ( $attachments as $id => $attachment ) {
                    if (prima_get_option('themelayout') == 'boxed') $width = 896;
                    else $width = 980;
                    $height = prima_get_option('sliderheight') ? prima_get_option('sliderheight') : 375;
                    $src = prima_get_image($id, $width, $height);
                    $url =  $attachment->post_content ? esc_url($attachment->post_content) : '';
                    $slideshow .= '<li class="promotion"><div class="promotion-header" style="text-align:center;">';
                    if($url) $slideshow .= '<a href="'.$url.'" >';
                    $slideshow .= '<img src="'.$src.'" alt="'.esc_attr($attachment->post_title).'"/>';
                    if($url) $slideshow .= '</a>';
                    $slideshow .= '</div></li>';
                }
            }
			?>
			<?php if ($slideshow) : ?>
                <div id="featured-slider">
                    <div id="feature-wrapper">
                    <div id="features">
                        <ul class="feature-list">
							<?php echo $slideshow; ?>
                        </ul>
                    </div>
                    </div>
                </div>
				<?php if (count($attachments) > 1) : ?>
					<div id="slider-controls"></div>
				<?php endif; ?>
			<?php endif; ?>
        </div>
    </div>
</div>

<div id="content-wrapper">
	<div id="main-content" class="container">	
		<div class="margin">
			<div id="full-col">
				<?php wp_reset_query(); ?>
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
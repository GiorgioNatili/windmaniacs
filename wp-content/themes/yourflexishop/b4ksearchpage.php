<?php

/**
 * Template Name: WDM Search Page
 */

include(WP_PLUGIN_DIR.'/wdm-search/wdm-query.php');

global $wp_query, $product_page_width, $product_sidebar, $product_sidebar_width;

$product_sidebar = false;
if (prima_get_option('themelayout') == 'boxed') {
  $product_page_width = 665;
}
else {
  $product_page_width = 720;
}

$img_width = 185;
$img_height = 185;
?>
<?php get_header(); ?>

<div id="header-wrapper">
  <?php get_template_part( 'flexi-header' ); ?>
  <div id="leader" class="container">
    <div class="margin clearfix">
      <?php if (class_exists('WP_eCommerce') && prima_get_option('searchformpage')): ?>
        <?php prima_product_search(); ?>
      <?php endif; ?>
      <h1><?php the_title(); ?></h1>
    </div>
  </div>
</div>

<div id="content-wrapper">
  <div id="main-content" class="container">
    <?php wdm_search_form(); ?>
    <div class="margin">
      <div id="main-col">
      <?php if ($pageposts->have_posts()): ?>
        <?php while ($pageposts->have_posts()): ?>
          <?php $pageposts->the_post(); ?>
          <div class="blog-post blog-overview">
            <div class="post-content clearfix">
            <?php $thumbnail = prima_get_image( false, $img_width, $img_height, true ); ?>
            <?php if ( $thumbnail ): ?>
              <img class="attachment-post-thumbnail alignleft" src="<?php echo $thumbnail; ?>" title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>" width="<?php echo $img_width; ?>" height="<?php echo $img_height; ?>" />
            <?php endif; ?>
            <div class="overlay">
              <div class="price"><?php echo prima_the_product_price( prima_get_option('productsvariationtext'), $pricedecimal, get_the_ID(), $variationprice ); ?></div>
                <h3><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
                <div class="excerpt"><?php the_excerpt();?></div>
                <a href="<?php the_permalink() ?>" class="read-more"><?php _e('Show') ?></a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
        <?php pagination(); ?>
      <?php else : ?>
        <div id="divNotFound">
          <p ><strong><h1><?php _e( 'Not Found', PRIMA_DOMAIN ); ?><br /><br /></h1></strong></p>
          <p><?php _e( 'Apologies, but the page you requested could not be found. <br />Perhaps searching will help.', PRIMA_DOMAIN ); ?></p>
        </div>
      <?php endif; ?>
      </div>
      <div id="home-sidebar">
      <?php if ( is_active_sidebar( 'home-right' ) ) : ?>
        <?php dynamic_sidebar( 'home-right' ); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>

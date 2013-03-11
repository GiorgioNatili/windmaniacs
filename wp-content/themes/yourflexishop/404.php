<?php

/*
Template Name: WDM Search Page
*/

include(WP_PLUGIN_DIR.'/wdm-search/wdm-query.php');

global $wp_query, $product_page_width, $product_sidebar, $product_sidebar_width;

$product_sidebar = false;
if (prima_get_option('themelayout') == 'boxed') { $product_page_width = 665; }
else { $product_page_width = 720; }

get_header(); ?>

<div id="header-wrapper">
  <?php get_template_part( 'flexi-header' ); ?>
    <div id="leader" class="container">
        <div class="margin clearfix">
      <?php if (class_exists('WP_eCommerce') && prima_get_option('searchformpage')) prima_product_search(); ?>
          <h1><?php the_title(); ?></h1>
        </div>
    </div>
</div>

<div id="content-wrapper">
  <div id="main-content" class="container">
    <?php wdm_search_form(); ?>
    <div class="margin">
      <div id="main-col">
      
        <div id="divNotFound">
          <p ><strong><h1><?php _e( 'Error 404: file not found', PRIMA_DOMAIN ); ?><br /><br /></h1></strong></p>
        
          <p><?php _e( 'Apologies, but the page you requested could not be found. <br />Perhaps searching will help.', PRIMA_DOMAIN ); ?></p>
        </div>
      

      </div>
            <div id="home-sidebar">
                <?php if ( is_active_sidebar( 'home-right' ) ) : ?>
                <?php dynamic_sidebar( 'home-right' ); ?>
                <?php endif; ?>
            </div>
      <!-- <div id="sidebar">
        <div class="sidebar-inner">
          <?php //get_sidebar(); ?>
        </div>
      </div> -->
    </div>
  </div>
</div>

<?php get_footer(); ?>



















<?php get_header(); ?>





   <h1>Error 404: file not found</h1></p>

    <p ><a href="http://www.windmaniacs.com"><img src="http://www.windmaniacs.com/img/nuvola.png" title="windmaniacs" alt="windmaniacs" border="0"/></a></p>

    <p >Apologies, but the page you requested could not be found.<br />

	<a href="http://www.windmaniacs.com">Perhaps searching will help</a>

    </p>
    
    
<?php get_sidebar(); ?>
<?php get_footer(); ?>







<?php

add_action( 'widgets_init', 'flexishop_widgets_init' );
function flexishop_widgets_init() {

	register_sidebar( array(
		'name' => __( 'Primary Widget Area', PRIMA_DOMAIN ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', PRIMA_DOMAIN ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Products Sidebar', PRIMA_DOMAIN ),
		'id' => 'products-sidebar',
		'description' => __( 'The sidebar on the products page', PRIMA_DOMAIN ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Single Product Sidebar', PRIMA_DOMAIN ),
		'id' => 'single-product-sidebar',
		'description' => __( 'The sidebar on the single product page', PRIMA_DOMAIN ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Home Left', PRIMA_DOMAIN ),
		'id' => 'home-left',
		'description' => __( 'The left home column', PRIMA_DOMAIN ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Home Middle', PRIMA_DOMAIN ),
		'id' => 'home-middle',
		'description' => __( 'The center home column', PRIMA_DOMAIN ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Home Right', PRIMA_DOMAIN ),
		'id' => 'home-right',
		'description' => __( 'The right home column', PRIMA_DOMAIN ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'First Footer Top', PRIMA_DOMAIN ),
		'id' => 'blog-footer-top-widget-area',
		'description' => __( 'The first footer top widget area', PRIMA_DOMAIN ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Second Footer Top', PRIMA_DOMAIN ),
		'id' => 'first-footer-top-widget-area',
		'description' => __( 'The second footer top widget area', PRIMA_DOMAIN ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Third Footer Top', PRIMA_DOMAIN ),
		'id' => 'second-footer-top-widget-area',
		'description' => __( 'The third footer top widget area', PRIMA_DOMAIN ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'First Footer Bottom', PRIMA_DOMAIN ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer bottom widget area', PRIMA_DOMAIN ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Second Footer Bottom', PRIMA_DOMAIN ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer bottom widget area', PRIMA_DOMAIN ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Third Footer Bottom', PRIMA_DOMAIN ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer bottom widget area', PRIMA_DOMAIN ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Fourth Footer Bottom', PRIMA_DOMAIN ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer bottom widget area', PRIMA_DOMAIN ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Fifth Footer Bottom', PRIMA_DOMAIN ),
		'id' => 'fifth-footer-widget-area',
		'description' => __( 'The fifth footer bottom widget area', PRIMA_DOMAIN ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
}

add_action( 'widgets_init', 'flexishop_remove_recent_comments_style' );
function flexishop_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}

<?php
add_action( 'init', 'prima_add_theme_shortcodes' );
function prima_add_theme_shortcodes() {
	if ( !is_admin() ) {
		add_shortcode( 'bloginfo', 'prima_theme_shortcode' );
		add_shortcode( 'site-link', 'prima_theme_shortcode' );
		add_shortcode( 'wp-link', 'prima_theme_shortcode' );
		add_shortcode( 'theme-link', 'prima_theme_shortcode' );
		add_shortcode( 'child-link', 'prima_theme_shortcode' );
		add_shortcode( 'loginout-link', 'prima_theme_shortcode' );
		add_shortcode( 'query-counter', 'prima_theme_shortcode' );
		add_shortcode( 'nav-menu', 'prima_theme_shortcode' );
		add_shortcode( 'dropdown-categories', 'prima_theme_shortcode' );
		add_shortcode( 'list-categories', 'prima_theme_shortcode' );
		add_shortcode( 'tag-cloud', 'prima_theme_shortcode' );
		add_shortcode( 'list-authors', 'prima_theme_shortcode' );
		add_shortcode( 'list-bookmarks', 'prima_theme_shortcode' );
		add_shortcode( 'dropdown-pages', 'prima_theme_shortcode' );
		add_shortcode( 'list-pages', 'prima_theme_shortcode' );
	}
}
function prima_theme_shortcode($attr, $content=null, $code=""){
	switch($code){
		case 'bloginfo':
			$attr = shortcode_atts( array( 'show' => '' ), $attr );
			return get_bloginfo( $attr['show'] );
		break;
		case 'site-link':
			return '<a class="site-link" href="' . home_url() . '" title="' . get_bloginfo( 'name' ) . '" rel="home"><span>' . get_bloginfo( 'name' ) . '</span></a>';
		break;
		case 'wp-link':
			return '<a class="wp-link" href="http://wordpress.org" title="' . __( 'Powered by WordPress, state-of-the-art semantic personal publishing platform', PRIMA_DOMAIN ) . '"><span>' . __( 'WordPress', PRIMA_DOMAIN ) . '</span></a>';
		break;
		case 'theme-link':
			$data = get_theme_data( TEMPLATEPATH . '/style.css' );
			return '<a class="theme-link" href="' . esc_url( $data['URI'] ) . '" title="' . esc_attr( $data['Name'] ) . '"><span>' . esc_attr( $data['Name'] ) . '</span></a>';
		break;
		case 'child-link':
			$data = get_theme_data( STYLESHEETPATH . '/style.css' );
			return '<a class="child-link" href="' . esc_url( $data['URI'] ) . '" title="' . esc_attr( $data['Name'] ) . '"><span>' . esc_attr( $data['Name'] ) . '</span></a>';
		break;
		case 'loginout-link':
			if ( is_user_logged_in() )
				$out = '<a class="logout-link" href="' . wp_logout_url( esc_url( $_SERVER['HTTP_REFERER'] ) ) . '" title="' . __( 'Log out of this account', PRIMA_DOMAIN ) . '">' . __( 'Log out', PRIMA_DOMAIN ) . '</a>';
			else
				$out = '<a class="login-link" href="' . wp_login_url( esc_url( $_SERVER['HTTP_REFERER'] ) ) . '" title="' . __( 'Log into this account', PRIMA_DOMAIN ) . '">' . __( 'Log in', PRIMA_DOMAIN ) . '</a>';
			return $out;
		break;
		case 'query-counter':
			if ( !current_user_can( 'edit_themes' ) ) return false;
			$out = sprintf( __( 'This page loaded in %1$s seconds with %2$s database queries.', PRIMA_DOMAIN ), timer_stop( 0, 3 ), get_num_queries() );
			return $out;
		break;
		case 'nav-menu':
			$attr = shortcode_atts(
				array(
					'menu' => '',
					'container' => 'div',
					'container_id' => '',
					'container_class' => 'nav-menu',
					'menu_id' => '',
					'menu_class' => '',
					'link_before' => '',
					'link_after' => '',
					'before' => '',
					'after' => '',
					'fallback_cb' => 'wp_page_menu',
					'walker' => ''
				),
				$attr
			);
			$attr['echo'] = false;
			return wp_nav_menu( $attr );
		break;
		case 'dropdown-categories':
			$attr = shortcode_atts(
				array(
					'show_option_all' => '', 'show_option_none' => '',
					'orderby' => 'id', 'order' => 'ASC',
					'show_last_update' => 0, 'show_count' => 0,
					'hide_empty' => 1, 'child_of' => 0,
					'exclude' => '', 
					'selected' => 0, 'hierarchical' => 0,
					'name' => 'cat', 'id' => '',
					'class' => 'postform', 'depth' => 0,
					'tab_index' => 0, 'taxonomy' => 'category',
					'hide_if_empty' => false
				),
				$attr
			);
			$attr['echo'] = false;
			return wp_dropdown_categories( $attr );
		break;
		case 'list-categories':
			$attr = shortcode_atts(
				array(
					'show_option_all' => '', 'show_option_none' => __('No categories', PRIMA_DOMAIN),
					'orderby' => 'name', 'order' => 'ASC',
					'show_last_update' => 0, 'style' => 'list',
					'show_count' => 0, 'hide_empty' => 1,
					'use_desc_for_title' => 1, 'child_of' => 0,
					'feed' => '', 'feed_type' => '',
					'feed_image' => '', 'exclude' => '',
					'exclude_tree' => '', 'current_category' => 0,
					'hierarchical' => true, 'title_li' => '',
					'depth' => 0, 'taxonomy' => 'category'
				),
				$attr
			);
			$attr['echo'] = false;
			return wp_list_categories( $attr );
		break;
		case 'tag-cloud':
			$attr = shortcode_atts(
				array(
					'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => 45,
					'format' => 'flat', 'separator' => "\n", 'orderby' => 'name', 'order' => 'ASC',
					'exclude' => '', 'include' => '', 'link' => 'view', 'taxonomy' => 'post_tag'
				),
				$attr
			);
			$attr['echo'] = false;
			return wp_tag_cloud( $attr );
		break;
		case 'list-authors':
			$attr = shortcode_atts(
				array(
					'optioncount' => false, 'exclude_admin' => true,
					'show_fullname' => false, 'hide_empty' => true,
					'feed' => '', 'feed_image' => '', 'feed_type' => '',
					'style' => 'list', 'html' => true
				),
				$attr
			);
			$attr['echo'] = false;
			return wp_list_authors( $attr );
		break;
		case 'list-bookmarks':
			$attr = shortcode_atts(
				array(
					'orderby' => 'name', 'order' => 'ASC',
					'limit' => -1, 'category' => '', 'exclude_category' => '',
					'category_name' => '', 'hide_invisible' => 1,
					'show_updated' => 0,
					'categorize' => 1, 'title_li' => '',
					'title_before' => '<h2>', 'title_after' => '</h2>',
					'category_orderby' => 'name', 'category_order' => 'ASC',
					'class' => 'linkcat', 'category_before' => '<li id="%id" class="%class">',
					'category_after' => '</li>'
				),
				$attr
			);
			$attr['echo'] = false;
			return wp_list_bookmarks( $attr );
		break;
		case 'dropdown-pages':
			$attr = shortcode_atts(
				array(
					'depth' => 0, 'child_of' => 0,
					'selected' => 0,
					'name' => 'page_id', 'id' => '',
					'show_option_none' => '', 'show_option_no_change' => '',
					'option_none_value' => ''
				),
				$attr
			);
			$attr['echo'] = false;
			return wp_dropdown_pages( $attr );
		break;
		case 'list-pages':
			$attr = shortcode_atts(
				array(
					'depth' => 0, 'show_date' => '',
					'date_format' => get_option('date_format'),
					'child_of' => 0, 'exclude' => '',
					'title_li' => '',
					'authors' => '', 'sort_column' => 'menu_order, post_title',
					'link_before' => '', 'link_after' => '', 'walker' => '',
				),
				$attr
			);
			$attr['echo'] = false;
			return wp_list_pages( $attr );
		break;
	}
}
<?php
/**
 * undocumented
 */
add_filter( 'wp_title', 'prima_title', 10, 3);
function prima_title( $title, $sep = '', $seplocation = '' ) {
	if ( is_home() ) $title = get_bloginfo('name');
	global $wp_query;
	$doctitle = '';
	if ( is_404() )
		$doctitle = __('404 - Not Found', PRIMA_DOMAIN);
	elseif ( is_search() )
		$doctitle = sprintf( __( 'Search Results for "%1$s"', PRIMA_DOMAIN ), esc_attr( get_search_query() ) );
	elseif ( ( is_home() || is_front_page() ) )
		$doctitle = get_bloginfo( 'name' ) . ' | ' . get_bloginfo( 'description' );
	elseif ( is_author() )
		$doctitle = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
	elseif ( is_date() ) {
		if ( get_query_var( 'minute' ) && get_query_var( 'hour' ) )
			$doctitle = sprintf( __( 'Archive for %1$s', PRIMA_DOMAIN ), get_the_time( __( 'g:i a', PRIMA_DOMAIN ) ) );

		elseif ( get_query_var( 'minute' ) )
			$doctitle = sprintf( __( 'Archive for minute %1$s', PRIMA_DOMAIN ), get_the_time( __( 'i', PRIMA_DOMAIN ) ) );

		elseif ( get_query_var( 'hour' ) )
			$doctitle = sprintf( __( 'Archive for %1$s', PRIMA_DOMAIN ), get_the_time( __( 'g a', PRIMA_DOMAIN ) ) );

		elseif ( is_day() )
			$doctitle = sprintf( __( 'Archive for %1$s', PRIMA_DOMAIN ), get_the_time( __( 'F jS, Y', PRIMA_DOMAIN ) ) );

		elseif ( get_query_var( 'w' ) )
			$doctitle = sprintf( __( 'Archive for week %1$s of %2$s', PRIMA_DOMAIN ), get_the_time( __( 'W', PRIMA_DOMAIN ) ), get_the_time( __( 'Y', PRIMA_DOMAIN ) ) );

		elseif ( is_month() )
			$doctitle = sprintf( __( 'Archive for %1$s', PRIMA_DOMAIN ), single_month_title( ' ', false) );

		elseif ( is_year() )
			$doctitle = sprintf( __( 'Archive for %1$s', PRIMA_DOMAIN ), get_the_time( __( 'Y', PRIMA_DOMAIN ) ) );
	}
	elseif ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) {
		$post_type = get_post_type_object( get_query_var( 'post_type' ) );
		$doctitle = $post_type->labels->name;
	}
	elseif ( is_category() || is_tag() || is_tax() ) {
		$term = $wp_query->get_queried_object();
		$doctitle = $term->name;
	}
	elseif ( is_singular() ) {
		$post_id = $wp_query->get_queried_object_id();
		$doctitle = get_post_field( 'post_title', $post_id );
	}
	if (get_query_var('paged')) {
		$doctitle .= ' - ' . __( 'Page ' , PRIMA_DOMAIN) . get_query_var('paged');
	}
	$doctitle = esc_attr($doctitle);
	// $doctitle = htmlentities(stripslashes($doctitle));
	if ($doctitle) return $doctitle;
	else return $title;
}

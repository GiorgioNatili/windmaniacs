<?php
add_action( 'init', 'prima_add_post_shortcodes' );
function prima_add_post_shortcodes() {
	if ( !is_admin() ) {
		add_shortcode( 'post-title', 'prima_post_shortcode' );
		add_shortcode( 'post-content', 'prima_post_shortcode' );
		add_shortcode( 'post-excerpt', 'prima_post_shortcode' );
		add_shortcode( 'post-image', 'prima_post_shortcode' );
		add_shortcode( 'post-author', 'prima_post_shortcode' );
		add_shortcode( 'post-terms', 'prima_post_shortcode' );
		add_shortcode( 'post-comments-link', 'prima_post_shortcode' );
		add_shortcode( 'post-date', 'prima_post_shortcode' );
		add_shortcode( 'post-edit-link', 'prima_post_shortcode' );
		add_shortcode( 'post-shortlink', 'prima_post_shortcode' );
	}
}
function prima_post_shortcode($attr, $content=null, $code=""){
	switch($code){
		case 'post-title':
			$attr = shortcode_atts( array( 'container' => 'h4', 'class' => '', 'link' => '1' ), $attr );
			$title = get_the_title();
			$link = get_permalink();
			if ( $attr['link'] )
				$title = '<a href="'.$link.'" title="'.the_title_attribute('echo=0').'" rel="bookmark">'.$title.'</a>';
			if ( $attr['container'] )
				$title = '<'.$attr['container'].' class="'.$attr['class'].'">'.$title.'</'.$attr['container'].'>';
			return $title;
		break;
		case 'post-content':
			global $more; $more = 0; 
			$attr = shortcode_atts( array( 'more' => '' ), $attr );
			return get_the_content( $attr['more'] );
		break;
		case 'post-excerpt':
			$attr = shortcode_atts( array( 'more' => '', 'limit' => '' ), $attr );
			$the_excerpt = get_the_excerpt();
			$the_excerpt = str_replace( '[...]', '', $the_excerpt );
			if ( $attr['limit'] ) {
				$the_excerpt = substr( $the_excerpt, 0, $attr['limit'] );
				$the_excerpt = substr( $the_excerpt, 0, strrpos( $the_excerpt, ' '));
			}
			$the_excerpt .= '&hellip;';
			if ( $attr['more'] )
				$the_excerpt .= ' <a class="more-link" href="'.get_permalink().'">'.$attr['more'].'</a>';
			return $the_excerpt;
		break;
		case 'post-image':
			global $post;
			$attr = shortcode_atts( array( 
				'post_id' => $post->ID,
				'meta_key' => array( 'Thumbnail', 'thumbnail' ),
				'the_post_thumbnail' => true, // WP 2.9+ image function
				'attachment' => true,
				'image_scan' => true,
				'default_image' => false,
				'size' => '',
				'width' => '48',
				'height' => '48',
				'quality' => 80,
				'link_to_post' => true,
				'alignment' => 'left',
				'image_class' => false,
				'cache' => true
			), $attr );
			return prima_get_image( $attr );
		break;
		case 'post-author':
			$attr = shortcode_atts( array( 'before' => '', 'after' => '' ), $attr );
			$author = '<span class="author vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . get_the_author_meta( 'display_name' ) . '">' . get_the_author_meta( 'display_name' ) . '</a></span>';
			return $attr['before'] . $author . $attr['after'];
		break;
		case 'post-terms':
			global $post;
			$attr = shortcode_atts( array( 'id' => $post->ID, 'taxonomy' => 'post_tag', 'separator' => ', ', 'before' => '', 'after' => '' ), $attr );
			$attr['before'] = '<span class="' . $attr['taxonomy'] . '">' . $attr['before'];
			$attr['after'] .= '</span>';
			return get_the_term_list( $attr['id'], $attr['taxonomy'], $attr['before'], $attr['separator'], $attr['after'] );
		break;
		case 'post-comments-link':
			$comments_link = '';
			$number = get_comments_number();
			$attr = shortcode_atts( array( 'zero' => __( 'Leave a response', PRIMA_DOMAIN ), 'one' => __( '1 Response', PRIMA_DOMAIN ), 'more' => __( '%1$s Responses', PRIMA_DOMAIN ), 'css_class' => 'comments-link', 'none' => '', 'before' => '', 'after' => '' ), $attr );
			if ( 0 == $number && !comments_open() && !pings_open() ) {
				if ( $attr['none'] )
					$comments_link = '<span class="' . esc_attr( $attr['css_class'] ) . '">' . $attr['none'] . '</span>';
			}
			elseif ( $number == 0 )
				$comments_link = '<a class="' . esc_attr( $attr['css_class'] ) . '" href="' . get_permalink() . '#respond" title="' . sprintf( __( 'Comment on %1$s', PRIMA_DOMAIN ), the_title_attribute( 'echo=0' ) ) . '">' . $attr['zero'] . '</a>';
			elseif ( $number == 1 )
				$comments_link = '<a class="' . esc_attr( $attr['css_class'] ) . '" href="' . get_comments_link() . '" title="' . sprintf( __( 'Comment on %1$s', PRIMA_DOMAIN ), the_title_attribute( 'echo=0' ) ) . '">' . $attr['one'] . '</a>';
			elseif ( $number > 1 )
				$comments_link = '<a class="' . esc_attr( $attr['css_class'] ) . '" href="' . get_comments_link() . '" title="' . sprintf( __( 'Comment on %1$s', PRIMA_DOMAIN ), the_title_attribute( 'echo=0' ) ) . '">' . sprintf( $attr['more'], $number ) . '</a>';
			if ( $comments_link )
				$comments_link = $attr['before'] . $comments_link . $attr['after'];
			return $comments_link;
		break;
		case 'post-date':
			$attr = shortcode_atts( array( 'before' => '', 'after' => '', 'format' => get_option( 'date_format' ) ), $attr );
			$published = '<abbr class="published" title="' . sprintf( get_the_time( __( 'l, F jS, Y, g:i a', PRIMA_DOMAIN ) ) ) . '">' . sprintf( get_the_time( $attr['format'] ) ) . '</abbr>';
			return $attr['before'] . $published . $attr['after'];
		break;
		case 'post-edit-link':
			global $post;
			$post_type = get_post_type_object( $post->post_type );
			if ( !current_user_can( "edit_{$post_type->capability_type}", $post->ID ) )
				return '';
			$attr = shortcode_atts( array( 'before' => '', 'after' => '' ), $attr );
			return $attr['before'] . '<span class="edit"><a class="post-edit-link" href="' . get_edit_post_link( $post->ID ) . '" title="' . sprintf( __( 'Edit %1$s', PRIMA_DOMAIN ), $post->post_type ) . '">' . __( 'Edit', PRIMA_DOMAIN ) . '</a></span>' . $attr['after'];
		break;
		case 'post-shortlink':
			global $post;
			$attr = shortcode_atts(
				array(
					'text' => __( 'Shortlink', PRIMA_DOMAIN ),
					'title' => the_title_attribute( array( 'echo' => false ) ),
					'before' => '',
					'after' => ''
				),
				$attr
			);
			$shortlink = wp_get_shortlink( $post->ID );
			return "{$attr['before']}<a class='shortlink' href='{$shortlink}' title='{$attr['title']}' rel='shortlink'>{$attr['text']}</a>{$attr['after']}";
		break;
	}
}
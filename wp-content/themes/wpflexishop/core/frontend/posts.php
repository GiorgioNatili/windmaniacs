<?php

function flexishop_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', PRIMA_DOMAIN ) . '</a>';
}

function flexishop_auto_excerpt_more( $more ) {
	return ' &hellip;' . flexishop_continue_reading_link();
}
add_filter( 'excerpt_more', 'flexishop_auto_excerpt_more' );

function content($num) {  
  $limit = $num+1;  
  $permalink = get_permalink();
  $content = str_split(get_the_content()); 
  $length = count($content);
  if ($length>=$num) {
    $content = array_slice( $content, 0, $num);  
    $content = implode("",$content)."<a href='$permalink'>".__( 'Read More', PRIMA_DOMAIN )."</a>";  
    echo $content;  
  } else { 
    the_content();
  }
}
if ( ! function_exists( 'flexishop_comment' ) ) :

function flexishop_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment-wrapper clearfix">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 60 ); ?>
			<?php printf( __( '%s', PRIMA_DOMAIN ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', PRIMA_DOMAIN ); ?></em>
			<br />
		<?php endif; ?>
		<div class="comment-body">
			<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
				<?php
					/* translators: 1: date, 2: time */
					printf( __( '%1$s at %2$s', PRIMA_DOMAIN ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', PRIMA_DOMAIN ), ' ' );
				?>
			</div><!-- .comment-meta .commentmetadata -->
	
			<div class="comment-text"><?php comment_text(); ?></div>
	
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</div>
		</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', PRIMA_DOMAIN ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', PRIMA_DOMAIN), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

if ( ! function_exists( 'flexishop_posted_on' ) ) :

function flexishop_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', PRIMA_DOMAIN ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', PRIMA_DOMAIN ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;

if ( ! function_exists( 'flexishop_posted_in' ) ) :

function flexishop_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', PRIMA_DOMAIN );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', PRIMA_DOMAIN );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', PRIMA_DOMAIN );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;


function pagination($pages = '', $range = 2)
{  
     $showitems = ($range * 2)+1;  

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         echo "<div class='pagination'>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
         echo "</div>\n";
     }
}

add_filter( 'wpsc_jpeg_quality', 'prima_jpeg_quality' );
function prima_jpeg_quality( $quality ) {
	return 90;
}

/**
Text Formatting 
Credits: Genesis Framework 
 **/
function prima_truncate_phrase($phrase, $max_characters) {
	$phrase = trim( $phrase );
	if ( strlen($phrase) > $max_characters ) {
		// Truncate $phrase to $max_characters + 1
		$phrase = substr($phrase, 0, $max_characters + 1);
		// Truncate to the last space in the truncated string.
		$phrase = trim(substr($phrase, 0, strrpos($phrase, ' ')));
		$phrase .= __( ' &hellip;', PRIMA_DOMAIN );
	}
	return $phrase;
}
function get_the_content_limit($max_char, $more_link_text = '(more...)', $stripteaser = 0) {
	$content = get_the_content('', $stripteaser);
	// Strip tags and shortcodes
	$content = strip_shortcodes($content);
	$content = strip_tags($content, '<script>,<style>');
	// Inline styles/scripts
	$content = trim(preg_replace('#<(s(cript|tyle)).*?</\1>#si', '', $content));
	$content = prima_truncate_phrase($content, $max_char);
	if ( $more_link_text ) {
		$link = apply_filters( 'get_the_content_more_link', sprintf( '<a href="%s" class="more-link">%s</a>', get_permalink(), $more_link_text ) );
		$output = sprintf('<p>%s %s</p>', $content, $link);
	}
	else {
		$link = '';
		$output = sprintf('<p>%s</p>', $content);
	}
	return apply_filters('get_the_content_limit', $output, $content, $link, $max_char);
}
function the_content_limit($max_char, $more_link_text = '(more...)', $stripteaser = 0) {
	$content = get_the_content_limit($max_char, $more_link_text, $stripteaser);
	echo apply_filters('the_content_limit', $content);
}
function get_the_excerpt_limit($max_char, $more_link_text = '(more...)', $stripteaser = 0) {
	$content = get_the_excerpt();
	// Strip tags and shortcodes
	$content = strip_shortcodes($content);
	$content = strip_tags($content, '<script>,<style>');
	// Inline styles/scripts
	$content = trim(preg_replace('#<(s(cript|tyle)).*?</\1>#si', '', $content));
	$content = prima_truncate_phrase($content, $max_char);
	if ( $more_link_text ) {
		$link = apply_filters( 'get_the_excerpt_more_link', sprintf( '<a href="%s" class="more-link">%s</a>', get_permalink(), $more_link_text ) );
		$output = sprintf('<p>%s %s</p>', $content, $link);
	}
	else {
		$link = '';
		$output = sprintf('<p>%s</p>', $content);
	}
	return apply_filters('get_the_excerpt_limit', $output, $content, $link, $max_char);
}
function the_excerpt_limit($max_char, $more_link_text = '(more...)', $stripteaser = 0) {
	$content = get_the_excerpt_limit($max_char, $more_link_text, $stripteaser);
	echo apply_filters('the_excerpt_limit', $content);
}


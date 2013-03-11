<?php
add_filter( 'body_class', 'prima_special_page_class' );
function prima_special_page_class( $classes ) {
	if(
		is_page_template('frontpage.php') || 
		is_page_template('slideshow.php') || 
		is_page_template('page_video.php') || 
		is_page_template('page_productslider.php') || 
		is_singular('promotion') || 
		is_singular('slider') 
	) 
		$classes[] = 'page-template-special';
	return $classes;
}
add_action( 'get_header', 'prima_checkout_ssl', 5 );
function prima_checkout_ssl() {
	global $prima_shoppingcart_id;
	if ( $prima_shoppingcart_id && is_page( $prima_shoppingcart_id ) && get_option( "wpsc_force_ssl" ) ) :
		remove_all_actions('wp_head');
		remove_all_actions('wp_footer');
		remove_all_actions('wp_enqueue_scripts');
		remove_all_actions('wp_print_styles');
		remove_all_actions('wp_print_head_scripts');
		remove_all_actions('wp_print_footer_scripts');
		// Default WP Actions
		add_action( 'wp_head',             'wp_enqueue_scripts',            1     );
		add_action( 'wp_head',             'wp_print_styles',               8     );
		add_action( 'wp_head',             'wp_print_head_scripts',         9     );
		add_action( 'wp_footer',           'wp_print_footer_scripts'              );
		// Important WP Actions
		add_action( 'wp_head', 'prima_head_favicon');
		add_action('wp_head', 'prima_googlefont_script');
		remove_action('wp_head', 'prima_load_dynamic_style', 1101);
		add_action('wp_head', 'prima_load_stylesheet', 5);
		add_action('wp_head', 'prima_load_wpsc_stylesheet', 5);
		add_action('wp_head', 'prima_load_shortcode_stylesheet', 5);
		add_action('wp_head', 'prima_prettyphoto_styles');
		add_action('wp_footer', 'prima_load_dynamic_scripts', 1001);
		add_action('wp_footer', 'prima_load_custom_scripts', 1001);
		wp_deregister_script('sharethis');
	endif;
}
add_filter( 'get_search_form', 'wpflexishop_search_form' );
function wpflexishop_search_form( $form ) {
    $form = '<form role="search" method="get" class="searchform" action="' . home_url( '/' ) . '" >
    <div>
    <input type="text" value="'.__( 'Search...', PRIMA_DOMAIN ).'" name="s" class="searchbox" />
    <input type="submit" class="searchsubmit" value="'. esc_attr(__( 'Search', PRIMA_DOMAIN )) .'" />
    </div>
    </form>';
    return $form;
}
function prima_search_form( $args = array() ) {
	$defaults = array(
		'search_text' => __('Search this website&hellip;', PRIMA_DOMAIN),
		'button_text' => __( 'Search', PRIMA_DOMAIN ),
		'echo' => false
	);
	$args = apply_filters( 'prima_search_form_args', $args );
	$args = wp_parse_args( $args, $defaults );
	$search_text = get_search_query() ? esc_js( get_search_query() ) : esc_js( $args['search_text'] );
	$button_text = esc_attr( $args['button_text'] );
	$onfocus = " onfocus=\"if (this.value == '$search_text') {this.value = '';}\"";
	$onblur = " onblur=\"if (this.value == '') {this.value = '$search_text';}\"";
	$searchform = '
		<form method="get" class="searchform" action="' . get_option('home') . '/" >
			<input type="text" value="'. $search_text .'" name="s" class="searchtext"'. $onfocus . $onblur .' />
			<input type="submit" class="searchsubmit" value="'. $button_text .'" />
		</form>
	';
	if ($args['echo']) echo $searchform;
	else return $searchform;
}
function prima_feedburner_form( $args = array() ) {
	$defaults = array(
		'id' => '', 
		'language' => __( 'en_US', PRIMA_DOMAIN ), 
		'input_text' => __( 'Enter your email address...', PRIMA_DOMAIN ), 
		'button_text' => __( 'Subscribe', PRIMA_DOMAIN ),
		'echo' => false
	);
	$args = apply_filters( 'prima_search_form_args', $args );
	$args = wp_parse_args( $args, $defaults );
	$id = esc_js( $args['id'] );
	$language = esc_attr( $args['language'] );
	$input_text = esc_attr( $args['input_text'] );
	$button_text = esc_attr( $args['button_text'] );
	$onsubmit = " onsubmit=\"window.open('http://feedburner.google.com/fb/a/mailverify?uri=$id', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true\" ";
	$onfocus = " onfocus=\"if (this.value == '$input_text') {this.value = '';}\" ";
	$onblur = " onblur=\"if (this.value == '') {this.value = '$input_text';}\" ";
	if ( $id ) {
		$feedburnerform = '
			<form class="feedburner-subscribe" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" '. $onsubmit .'>
				<input type="text" value="'. $input_text .'" class="feedburnertext"'. $onfocus . $onblur .' />
				<input type="hidden" value="'. $id .'" name="uri"/>
				<input type="hidden" name="loc" value="'. $language .'"/>
				<input type="submit" class="feedburnersubmit" value="'. $button_text .'" />
			</form>
		';
	}
	else {
		$feedburnerform = '<p>'.__ ( 'No Feedburner ID was available', PRIMA_DOMAIN ).'</p>';
	}
	if ($args['echo']) echo $feedburnerform;
	else return $feedburnerform;
}
function prima_twitter( $args = array() ) {
	echo prima_get_twitter( $args );
}
function prima_get_twitter( $args = array() ) {
	$defaults = array(
		'usernames' => 'primathemes', 
		'limit' => 3, 
		'interval' => 600, 
		'format' => '<p class="twitter-message">%tweet%</p>',
		'before' => '',
		'after' => ''
	);
	$args = apply_filters( 'prima_twitter_args', $args );
	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	$output = '';
	$username_for_feed = str_replace(" ", "+OR+from%3A", $usernames);
	$feed_url = "http://twitter.com/statuses/user_timeline/" . $username_for_feed . ".atom?count=" . $limit;
	$usernames_for_file = str_replace(" ", "-", $usernames);
	$now = time();
	/* Check for a cache contentd twitter. */
	// delete_option( 'prima_twitter_cache' );
	$twitter_cache = get_option( 'prima_twitter_cache' );
	if ( !is_array( $twitter_cache ) )
		$twitter_cache = array();
	/* Cache exists */
	$rss = null;
	if ( isset($twitter_cache[$usernames_for_file]['content']) && $twitter_cache[$usernames_for_file]['content'] ) {
		$rss = $twitter_cache[$usernames_for_file]['content'];
		$last = $twitter_cache[$usernames_for_file]['time'];
		if ( ( $now - $last ) > $interval ) {
			$get_rss = prima_get_contents($feed_url);
			$feed = str_replace("&amp;", "&", $get_rss);
			$feed = str_replace("&lt;", "<", $feed);
			$feed = str_replace("&gt;", ">", $feed);
			$clean = explode("<entry>", $feed);
			$clean = str_replace("&quot;", "'", $clean);
			$clean = str_replace("&apos;", "'", $clean);
			if (count($clean) - 1) {
				// we got good results from twitter
				$rss = $clean;
				$output .= "<!-- ".__('SUCCESS: Twitter feed used to update cache content.',PRIMA_DOMAIN)." -->";
				$twitter_cache[$usernames_for_file]['content'] = $rss;
				$twitter_cache[$usernames_for_file]['time'] = $now;
				delete_option( 'prima_twitter_cache' );
				add_option( 'prima_twitter_cache', $twitter_cache );
			} 
			else {
				// we didn't get anything back from twitter
				$output .= "<!-- ".__('ERROR: Twitter feed was blank! Using cache content.',PRIMA_DOMAIN)." -->";
			}
		}
		else {
			$output .= "<!-- ".__('SUCCESS: Cache file was recent enough to read from.',PRIMA_DOMAIN)." -->";
		}
	}
	else {
		$get_rss = prima_get_contents($feed_url);
		$feed = str_replace("&amp;", "&", $get_rss);
		$feed = str_replace("&lt;", "<", $feed);
		$feed = str_replace("&gt;", ">", $feed);
		$clean = explode("<entry>", $feed);
		$clean = str_replace("&quot;", "'", $clean);
		$clean = str_replace("&apos;", "'", $clean);
		if (count($clean) - 1) {
			// we got good results from twitter
			$rss = $clean;
			$output .= "<!-- ".__('SUCCESS: Twitter feed used to update cache content',PRIMA_DOMAIN)." -->";
			$twitter_cache[$usernames_for_file]['content'] = $rss;
			$twitter_cache[$usernames_for_file]['time'] = $now;
			delete_option( 'prima_twitter_cache' );
			add_option( 'prima_twitter_cache', $twitter_cache );
		} 
		else {
			// we didn't get anything back from twitter
			$output .= str_replace('%tweet%',__('The Twitter feed was blank while processing your request. Please try again.',PRIMA_DOMAIN),$format);
		}
	}
	if (count($rss) - 1) {
		for ($i = 1; $i <= $rss; $i++) {
			if ($i == $limit + 1) break;
			$entry_close = explode("</entry>", $rss[$i]);
			$clean_content_1 = explode("<content type=\"html\">", $entry_close[0]);
			$clean_content = explode("</content>", $clean_content_1[1]);
			$clean_name_2 = explode("<name>", $entry_close[0]);
			$clean_name_1 = explode("(", $clean_name_2[1]);
			if(isset($clean_name_1[1]))
				$clean_name = explode(")</name>", $clean_name_1[1]);
			else
				$clean_name = explode(")</name>", $clean_name_1[0]);
			$clean_user = explode(" (", $clean_name_2[1]);
			$clean_lower_user = strtolower($clean_user[0]);
			$clean_uri_1 = explode("<uri>", $entry_close[0]);
			$clean_uri = explode("</uri>", $clean_uri_1[1]);
			$clean_time_1 = explode("<published>", $entry_close[0]);
			$clean_time = explode("</published>", $clean_time_1[1]);
			$unix_time = strtotime($clean_time[0]);
			$tweetmessage = prima_tweet_linkify($clean_content[0]);
			$pretty_time = sprintf(__('about %s ago', PRIMA_DOMAIN), human_time_diff($unix_time));
			$tweet=$tweetmessage.' <br/><small>'.$pretty_time.'</small>';
			$tweet=str_replace('%tweet%',$tweet,$format);
			$output .= $tweet;
		}
	} 
	else { // if there aren't any tweets
		$tweet=str_replace('%tweet%',__('No twitter updates available.',PRIMA_DOMAIN),$format);
		$output .= $tweet;
	}
	return $output;
}
function prima_get_contents( $url ) {
	if ( function_exists('curl_init') ) {
		$output = file_get_contents_curl( $url );
	}
	elseif ( function_exists('file_get_contents') ) {
		$output = file_get_contents( $url );
	}
	else {
		return false;
	}
	return $output;
}
function file_get_contents_curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
/*  Snipe.net http://www.snipe.net/2009/09/php-twitter-clickable-links/ */
function prima_tweet_linkify($tweet) {
	$tweet = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $tweet);
	$tweet = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $tweet);
	$tweet = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $tweet);
	$tweet = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $tweet);
	return $tweet;
}

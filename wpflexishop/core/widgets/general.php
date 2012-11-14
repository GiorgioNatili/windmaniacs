<?php
add_action( 'widgets_init', 'prima_register_general_widgets' );
function prima_register_general_widgets() {
	register_widget('Prima_RecentComments_Widget');
	register_widget('Prima_FeedburnerForm_Widget');
	register_widget('Prima_Twitter_Widget');
	register_widget('Prima_Flickr_Widget');
}
class Prima_RecentComments_Widget extends WP_Widget {
	function Prima_RecentComments_Widget() {
		$widget_ops = array( 'classname' => 'prima_recent_comments', 'description' => __('Display most recent comments', PRIMA_DOMAIN) );
		$control_ops = array( 'id_base' => 'prima-recent-comments' );
		$this->WP_Widget( 'prima-recent-comments', '::Prima - '.__('Recent Comments', PRIMA_DOMAIN), $widget_ops, $control_ops );
		$this->alt_option_name = 'prima_recent_comments';
		add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
		add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache') );
	}
	function flush_widget_cache() {
		wp_cache_delete('prima_recent_comments', 'widget');
	}
	function widget( $args, $instance ) {
		global $comments, $comment;
		$cache = wp_cache_get('prima_recent_comments', 'widget');
		if ( ! is_array( $cache ) )
			$cache = array();
		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$number = (int)$instance['number'];
		$avatar = $instance['avatar'] ? '1' : '0';
		$avatar_size = (int)$instance['avatar_size'];
		$excerpt_length = (int)$instance['excerpt_length'];
 		$output = '';
		$output .= $before_widget;
		if ( $title ) $output .= $before_title . $title . $after_title;
		$comments = get_comments(array( 'number' => $number, 'status' => 'approve', 'type' => 'comment' ));
		if ($comments) {
			$output .= '<ul>';
			foreach ($comments as $comment) :
				$comment_link = get_comment_link($comment->comment_ID);
				$output .= ( $avatar ? '<li class="group comment-with-avatar">' : '<li class="group">' );
				$output .= '<p>'; 
				if ( $avatar ) $output .= '<a href="'. $comment_link.'">'.get_avatar($comment, $size=$avatar_size).'</a>';
				$output .= '<a href="'.$comment_link.'"><strong>'.$comment->comment_author.'</strong>:</a> '.substr(get_comment_excerpt( $comment->comment_ID ), 0, $excerpt_length).'&hellip;';
				$output .= '</p>'; 
				$output .= '</li>'; 
			endforeach;
			$output .= '</ul>';
		}
		else {
			$output .= __( 'No comments were found', PRIMA_DOMAIN );
		}
		$output .= $after_widget;
		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set('prima_recent_comments', $cache, 'widget');
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = ( (int)$new_instance['number'] > 0 ? (int)$new_instance['number'] : '3' );
		$instance['avatar'] = ( isset( $new_instance['avatar'] ) ? 1 : 0 );
		$instance['avatar_size'] = ( (int)$new_instance['avatar_size'] > 0 ? (int)$new_instance['avatar_size'] : '48' );
		$instance['excerpt_length'] = ( (int)$new_instance['excerpt_length'] > 0 ? (int)$new_instance['excerpt_length'] : '75' );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => 'Recent Comments', 'number' => 3, 'avatar' => true, 'avatar_size' => 48, 'excerpt_length' => 75 );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', PRIMA_DOMAIN), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		prima_widget_input_text_small( __('Number of comments to show:', PRIMA_DOMAIN), $this->get_field_id( 'number' ), $this->get_field_name( 'number' ), $instance['number'] );
		prima_widget_input_checkbox( __( 'Show avatar', PRIMA_DOMAIN ), $this->get_field_id( 'avatar' ), $this->get_field_name( 'avatar' ), checked( $instance['avatar'], true, false ) );
		prima_widget_input_text_small( __('Avatar size (px):', PRIMA_DOMAIN), $this->get_field_id( 'avatar_size' ), $this->get_field_name( 'avatar_size' ), $instance['avatar_size'] );
		prima_widget_input_text_small( __('Comment excerpt length:', PRIMA_DOMAIN), $this->get_field_id( 'excerpt_length' ), $this->get_field_name( 'excerpt_length' ), $instance['excerpt_length'] );
	}
}
class Prima_SearchForm_Widget extends WP_Widget {
	function Prima_SearchForm_Widget() {
		$widget_ops = array( 'classname' => 'prima_searchform', 'description' => __('Display search form', PRIMA_DOMAIN) );
		$control_ops = array( 'id_base' => 'prima-searchform' );
		$this->WP_Widget( 'prima-searchform', '::Prima - '.__('Search Form', PRIMA_DOMAIN), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		$attr = array( 'search_text' => $instance['search_text'], 'button_text' => $instance['button_text'], 'echo' => true );
		prima_search_form( $attr );
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['search_text'] = strip_tags( $new_instance['search_text'] );
		$instance['button_text'] = strip_tags( $new_instance['button_text'] );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => '', 'search_text' => __('Search this website&hellip;', PRIMA_DOMAIN), 'button_text' => __( 'Search', PRIMA_DOMAIN ) );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', PRIMA_DOMAIN), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		prima_widget_input_text( __('Input text:', PRIMA_DOMAIN), $this->get_field_id( 'search_text' ), $this->get_field_name( 'search_text' ), $instance['search_text'] );
		prima_widget_input_text( __('Button text:', PRIMA_DOMAIN), $this->get_field_id( 'button_text' ), $this->get_field_name( 'button_text' ), $instance['button_text'] );
	}
}
class Prima_Twitter_Widget extends WP_Widget {
	function Prima_Twitter_Widget() {
		$widget_ops = array( 'classname' => 'prima_twitter', 'description' => __('Display most recent Twitter feed', PRIMA_DOMAIN) );
		$control_ops = array( 'id_base' => 'prima-twitter' );
		$this->WP_Widget( 'prima-twitter', '::Prima - '.__('Twitter Feed', PRIMA_DOMAIN), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		$attr = array( 'usernames' => $instance['usernames'], 'limit' => $instance['limit'], 'interval' => $instance['interval'] );
		$attr['interval'] = $attr['interval']*60;
		prima_twitter( $attr );
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['usernames'] = strip_tags( $new_instance['usernames'] );
		$instance['limit'] = strip_tags( $new_instance['limit'] );
		$instance['interval'] = strip_tags( $new_instance['interval'] );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => '', 'usernames' => 'primathemes', 'limit' => '3', 'interval' => '10' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', PRIMA_DOMAIN), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		prima_widget_input_text( __('Twitter username:', PRIMA_DOMAIN), $this->get_field_id( 'usernames' ), $this->get_field_name( 'usernames' ), $instance['usernames'] );
		prima_widget_input_text( __('Number of tweets:', PRIMA_DOMAIN), $this->get_field_id( 'limit' ), $this->get_field_name( 'limit' ), $instance['limit'] );
		$interval = array('5' => __('5 minutes', PRIMA_DOMAIN), '10' => __('10 minutes', PRIMA_DOMAIN), '15' => __('15 minutes', PRIMA_DOMAIN), '30' => __('30 minutes', PRIMA_DOMAIN),  '60' => __('1 hour', PRIMA_DOMAIN), 
'120' => __('1 hour', PRIMA_DOMAIN), '240' => __('4 hour', PRIMA_DOMAIN), '720' => __('12 hour', PRIMA_DOMAIN), '1440' => __('24 hours', PRIMA_DOMAIN) ); 
		prima_widget_select_single( __( 'Load new Tweets every:', PRIMA_DOMAIN ), $this->get_field_id( 'interval' ), $this->get_field_name( 'interval' ), $instance['interval'], $interval, false );
	}
}
class Prima_FeedburnerForm_Widget extends WP_Widget {
	function Prima_FeedburnerForm_Widget() {
		$widget_ops = array( 'classname' => 'prima_feedburnerform', 'description' => __('Display Feedburner subscription form', PRIMA_DOMAIN) );
		$control_ops = array( 'id_base' => 'prima-feedburnerform' );
		$this->WP_Widget( 'prima-feedburnerform', '::Prima - '.__('Feedburner Form', PRIMA_DOMAIN), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		if ( $instance['id'] && $instance['intro_text'] )
			echo wpautop( $instance['intro_text'] );
		$attr = array( 'id' => $instance['id'], 'input_text' => $instance['input_text'], 'button_text' => $instance['button_text'], 'echo' => true );
		prima_feedburner_form( $attr );
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['intro_text'] = strip_tags( $new_instance['intro_text'] );
		$instance['id'] = strip_tags( $new_instance['id'] );
		$instance['input_text'] = strip_tags( $new_instance['input_text'] );
		$instance['button_text'] = strip_tags( $new_instance['button_text'] );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => '', 'intro_text' => '', 'id' => '', 'input_text' => __( 'Enter your email address...', PRIMA_DOMAIN ), 'button_text' => __( 'Subscribe', PRIMA_DOMAIN ) );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', PRIMA_DOMAIN), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		prima_widget_input_text( __('Introduction text:', PRIMA_DOMAIN), $this->get_field_id( 'intro_text' ), $this->get_field_name( 'intro_text' ), $instance['intro_text'] );
		prima_widget_input_text( __('Feedburner ID:', PRIMA_DOMAIN), $this->get_field_id( 'id' ), $this->get_field_name( 'id' ), $instance['id'] );
		prima_widget_input_text( __('Input text:', PRIMA_DOMAIN), $this->get_field_id( 'input_text' ), $this->get_field_name( 'input_text' ), $instance['input_text'] );
		prima_widget_input_text( __('Button text:', PRIMA_DOMAIN), $this->get_field_id( 'button_text' ), $this->get_field_name( 'button_text' ), $instance['button_text'] );
	}
}
class Prima_Flickr_Widget extends WP_Widget {
	function Prima_Flickr_Widget() {
		$widget_ops = array( 'classname' => 'prima_flickr', 'description' => __('Display photos from Flickr', PRIMA_DOMAIN) );
		$control_ops = array( 'id_base' => 'prima-flickr' );
		$this->WP_Widget( 'prima-flickr', '::Prima - '.__('Flickr', PRIMA_DOMAIN), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$source = $instance['source'];
		$user_ID = $instance['user_ID'];
		$group_ID = $instance['user_ID'];
		$set_ID = $instance['set_ID'];
		$tag = $instance['tag'];
		$display = $instance['display'];
		$number = $instance['number'];
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		$params = 'count='.$number.'.&amp;display='.$display.'&amp;size=s&amp;layout=x';
		if ($source=='user')
			$params .= '&amp;source=user&amp;user='.$user_ID; 
		elseif ($source=='group')
			$params .= '&amp;source=group&amp;group='.$group_ID; 
		elseif ($source=='user_set')
			$params .= '&amp;source=user_set&amp;set='.$set_ID; 
		elseif ($source=='all_tag')
			$params .= '&amp;source=all_tag&amp;tag='.$tag; 
		echo '<div id="prima-flickr-wrapper" class="group"><script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?'.$params.'"></script></div>';
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['source'] = strip_tags( $new_instance['source'] );
		$instance['user_ID'] = strip_tags( $new_instance['user_ID'] );
		$instance['group_ID'] = strip_tags( $new_instance['group_ID'] );
		$instance['set_ID'] = strip_tags( $new_instance['set_ID'] );
		$instance['tag'] = strip_tags( trim($new_instance['tag']) );
		$instance['display'] = strip_tags( $new_instance['display'] );
		$instance['number'] = (int)$new_instance['number'];
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => __('Flickr Photos', PRIMA_DOMAIN), 'source' => 'user', 'user_ID' => '', 'group_ID' => '', 'set_ID' => '', 'tag' => '', 'display' => 'latest', 'number' => 10 );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', PRIMA_DOMAIN), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		$source_opt = array('user' => __('User', PRIMA_DOMAIN), 'group' => __('Group', PRIMA_DOMAIN), 'user_set' => __('User Set', PRIMA_DOMAIN), 'all_tag' => __('Tag', PRIMA_DOMAIN)); 
		prima_widget_select_single( __( 'Source:', PRIMA_DOMAIN ), $this->get_field_id( 'source' ), $this->get_field_name( 'source' ), $instance['source'], $source_opt, false );
		prima_widget_input_text( __('User ID:', PRIMA_DOMAIN), $this->get_field_id( 'user_ID' ), $this->get_field_name( 'user_ID' ), $instance['user_ID'] );
		echo '<p><small>'.__('* find your user ID using', PRIMA_DOMAIN).' <a href="http://www.idgettr.com" target="_blank">idGettr</a></small></p>';
		prima_widget_input_text( __('Group ID:', PRIMA_DOMAIN), $this->get_field_id( 'group_ID' ), $this->get_field_name( 'group_ID' ), $instance['group_ID'] );
		prima_widget_input_text( __('Set ID:', PRIMA_DOMAIN), $this->get_field_id( 'set_ID' ), $this->get_field_name( 'set_ID' ), $instance['set_ID'] );
		prima_widget_input_text( __('Tag (separated by comma):', PRIMA_DOMAIN), $this->get_field_id( 'tag' ), $this->get_field_name( 'tag' ), $instance['tag'] );
		$display_opt = array('latest' => __('Latest', PRIMA_DOMAIN), 'random' => __('Random', PRIMA_DOMAIN));
		prima_widget_select_single( __( 'Display:', PRIMA_DOMAIN ), $this->get_field_id( 'display' ), $this->get_field_name( 'display' ), $instance['display'], $display_opt, false );
		prima_widget_input_text_small( __('Number of photos (from 1 to 10):', PRIMA_DOMAIN), $this->get_field_id( 'number' ), $this->get_field_name( 'number' ), $instance['number'] );
	}
}


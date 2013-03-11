<?php
add_action( 'init', 'prima_add_conditional_shortcodes' );
function prima_add_conditional_shortcodes() {
	if ( !is_admin() ) {
		add_shortcode('is_logged_in', 'prima_conditional_shortcode');
		add_shortcode('not_logged_in', 'prima_conditional_shortcode');
		add_shortcode('is_home', 'prima_conditional_shortcode');
		add_shortcode('not_home', 'prima_conditional_shortcode');
		add_shortcode('is_front_page', 'prima_conditional_shortcode');
		add_shortcode('not_front_page', 'prima_conditional_shortcode');
		add_shortcode('is_single', 'prima_conditional_shortcode');
		add_shortcode('not_single', 'prima_conditional_shortcode');
		add_shortcode('is_sticky', 'prima_conditional_shortcode');
		add_shortcode('not_sticky', 'prima_conditional_shortcode');
		add_shortcode('is_page', 'prima_conditional_shortcode');
		add_shortcode('not_page', 'prima_conditional_shortcode');
		add_shortcode('is_page_template', 'prima_conditional_shortcode');
		add_shortcode('not_page_template', 'prima_conditional_shortcode');
		add_shortcode('is_category', 'prima_conditional_shortcode');
		add_shortcode('not_category', 'prima_conditional_shortcode');
		add_shortcode('is_tag', 'prima_conditional_shortcode');
		add_shortcode('not_tag', 'prima_conditional_shortcode');
		add_shortcode('has_tag', 'prima_conditional_shortcode');
		add_shortcode('has_not_tag', 'prima_conditional_shortcode');
		add_shortcode('is_tax', 'prima_conditional_shortcode');
		add_shortcode('not_tax', 'prima_conditional_shortcode');
		add_shortcode('is_author', 'prima_conditional_shortcode');
		add_shortcode('not_author', 'prima_conditional_shortcode');
		add_shortcode('is_date', 'prima_conditional_shortcode');
		add_shortcode('not_date', 'prima_conditional_shortcode');
		add_shortcode('is_year', 'prima_conditional_shortcode');
		add_shortcode('is_month', 'prima_conditional_shortcode');
		add_shortcode('is_day', 'prima_conditional_shortcode');
		add_shortcode('is_time', 'prima_conditional_shortcode');
		add_shortcode('is_archive', 'prima_conditional_shortcode');
		add_shortcode('not_archive', 'prima_conditional_shortcode');
		add_shortcode('is_search', 'prima_conditional_shortcode');
		add_shortcode('not_search', 'prima_conditional_shortcode');
		add_shortcode('is_404', 'prima_conditional_shortcode');
		add_shortcode('not_404', 'prima_conditional_shortcode');
		add_shortcode('is_singular', 'prima_conditional_shortcode');
		add_shortcode('not_singular', 'prima_conditional_shortcode');
		add_shortcode('in_the_loop', 'prima_conditional_shortcode');
		add_shortcode('not_in_the_loop', 'prima_conditional_shortcode');
		add_shortcode('is_multisite', 'prima_conditional_shortcode');
		add_shortcode('not_multisite', 'prima_conditional_shortcode');
	}
}
function prima_conditional_shortcode($attr, $content=null, $code=""){
	switch($code){
		case 'is_logged_in':
			if(is_user_logged_in()) return do_shortcode($content);
		break;
		case 'not_logged_in':
			if(!is_user_logged_in()) return do_shortcode($content);
		break;
		case 'is_home':
			if(is_home()) return do_shortcode($content);
		break;
		case 'not_home':
			if(!is_home()) return do_shortcode($content);
		break;
		case 'is_front_page':
			if(is_front_page()) return do_shortcode($content);
		break;
		case 'not_front_page':
			if(!is_front_page()) return do_shortcode($content);
		break;
		case 'is_single':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(is_single($id)) return do_shortcode($content);
		break;
		case 'not_single':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(!is_single($id)) return do_shortcode($content);
		break;
		case 'is_sticky':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(is_sticky($id)) return do_shortcode($content);
		break;
		case 'not_sticky':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(!is_sticky($id)) return do_shortcode($content);
		break;
		case 'is_page':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(is_page($id)) return do_shortcode($content);
		break;
		case 'not_page':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(!is_page($id)) return do_shortcode($content);
		break;
		case 'is_page_template':
			extract( shortcode_atts( array( 'template' => ""), $attr));
			if(is_page_template($template)) return do_shortcode($content);
		break;
		case 'not_page_template':
			extract( shortcode_atts( array( 'template' => ""), $attr));
			if(!is_page_template($template)) return do_shortcode($content);
		break;
		case 'is_category':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(is_category($id)) return do_shortcode($content);
		break;
		case 'not_category':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(!is_category($id)) return do_shortcode($content);
		break;
		case 'is_tag':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(is_tag($id)) return do_shortcode($content);
		break;
		case 'not_tag':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(!is_tag($id)) return do_shortcode($content);
		break;
		case 'has_tag':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(has_tag($id)) return do_shortcode($content);
		break;
		case 'has_not_tag':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(!has_tag($id)) return do_shortcode($content);
		break;
		case 'is_tax':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(is_tax($id)) return do_shortcode($content);
		break;
		case 'not_tax':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(!is_tax($id)) return do_shortcode($content);
		break;
		case 'is_author':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(is_author($id)) return do_shortcode($content);
		break;
		case 'not_author':
			extract( shortcode_atts( array( 'id' => ""), $attr));
			if(!is_author($id)) return do_shortcode($content);
		break;
		case 'is_date':
			if(is_date()) return do_shortcode($content);
		break;
		case 'not_date':
			if(!is_date()) return do_shortcode($content);
		break;
		case 'is_year':
			if(is_year()) return do_shortcode($content);
		break;
		case 'is_month':
			if(is_month()) return do_shortcode($content);
		break;
		case 'is_day':
			if(is_day()) return do_shortcode($content);
		break;
		case 'is_time':
			if(is_time()) return do_shortcode($content);
		break;
		case 'is_archive':
			if(is_archive()) return do_shortcode($content);
		break;
		case 'not_archive':
			if(!is_archive()) return do_shortcode($content);
		break;
		case 'is_search':
			if(is_search()) return do_shortcode($content);
		break;
		case 'not_search':
			if(!is_search()) return do_shortcode($content);
		break;
		case 'is_404':
			if(is_404()) return do_shortcode($content);
		break;
		case 'not_404':
			if(!is_404()) return do_shortcode($content);
		break;
		case 'is_singular':
			extract( shortcode_atts( array( 'type' => ""), $attr));
			if(is_singular($type)) return do_shortcode($content);
		break;
		case 'not_singular':
			extract( shortcode_atts( array( 'type' => ""), $attr));
			if(!is_singular($type)) return do_shortcode($content);
		break;
		case 'in_the_loop':
			if(in_the_loop()) return do_shortcode($content);
		break;
		case 'not_in_the_loop':
			if(!in_the_loop()) return do_shortcode($content);
		break;
		case 'is_multisite':
			if(is_multisite()) return do_shortcode($content);
		break;
		case 'not_multisite':
			if(!is_multisite()) return do_shortcode($content);
		break;
	}
}
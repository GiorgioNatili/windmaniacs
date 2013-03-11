<?php
add_action( 'widgets_init', 'prima_register_wpsc_widgets' );
function prima_register_wpsc_widgets() {
	register_widget('Prima_ProductSearchForm_Widget');
}
class Prima_ProductSearchForm_Widget extends WP_Widget {
	function Prima_ProductSearchForm_Widget() {
		$widget_ops = array( 'classname' => 'prima_productsearchform', 'description' => __('Display product search form', PRIMA_DOMAIN) );
		$control_ops = array( 'id_base' => 'prima-productsearchform' );
		$this->WP_Widget( 'prima-productsearchform', '::Prima - '.__('Product Search', PRIMA_DOMAIN), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		$form = '<form role="search" method="get" class="productsearchform" action="' . home_url( '/' ) . '" >
		<div>
		<input type="text" value="'.$instance['search_text'].'" name="s" class="searchbox" />
		<input type="submit" class="searchsubmit" value="'. esc_attr(__( 'Search', PRIMA_DOMAIN )) .'" />
		<input type="hidden" name="post_type" value="wpsc_product" />
		</div>
		</form>';
		echo $form;
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['search_text'] = strip_tags( $new_instance['search_text'] );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => '', 'search_text' => __('Search products&hellip;', PRIMA_DOMAIN), 'button_text' => __( 'Search', PRIMA_DOMAIN ) );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', PRIMA_DOMAIN), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		prima_widget_input_text( __('Input text:', PRIMA_DOMAIN), $this->get_field_id( 'search_text' ), $this->get_field_name( 'search_text' ), $instance['search_text'] );
	}
}

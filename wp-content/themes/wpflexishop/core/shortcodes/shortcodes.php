<?php 
function prima_shortcodes() {
	$shortcodes = array();
	$shortcodes[] = 'hr';
	// $shortcodes[] = 'divider';
	// $shortcodes[] = 'divider_flat';
	$shortcodes[] = 'dropcap';
	$shortcodes[] = 'highlight';
	// $shortcodes[] = 'unordered_list';
	// $shortcodes[] = 'ordered_list';
	$shortcodes[] = 'tagline';
	$shortcodes[] = 'quote';
	$shortcodes[] = 'box';
	$shortcodes[] = 'button';
	$shortcodes[] = 'tabs';
	$shortcodes[] = 'toggle';
	$shortcodes[] = 'year';
	$shortcodes[] = 'date';
	$shortcodes[] = 'search_form';
	$shortcodes[] = 'feedburner_form';
	$shortcodes[] = 'is_logged_in';
	$shortcodes[] = 'not_logged_in';
	return apply_filters('prima_shortcodes', $shortcodes);
}
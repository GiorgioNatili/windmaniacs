<?php

function b4k_add_metafilter($key,$value,$operator = '=',$type = 'numeric'){
	$filter = array('meta_key' => $key,'meta_value' => $value,'meta_compare' => $operator, 'type' => $type);
	return $filter;
}

$args = array( 'post_type' => 'wpsc-product','post_status' => 'publish');
$default_categories = array('b4k_brand','b4k_type','b4k_status');
$conditional_categories = array('b4k_year_min','b4k_year_max','b4k_size_min','b4k_size_max');
$conditional_fields = array('b4k_pmin','b4k_pmax');

$temp_categories = array();

//Loop to add default wpsc_product_category 
foreach ($default_categories as $filter_key) {
	if (isset($_POST[$filter_key]) && !empty($_POST[$filter_key])) {
		$temp_filters[] = mysql_real_escape_string($_POST[$filter_key]);
	}
}

if (!empty($temp_filters)) {
	$args['wpsc_product_category'] = implode('+',$temp_filters);	
}

//ad Filetr for only MIN year
if (isset($_POST['b4k_year_min']) && isset($_POST['b4k_year_max']) && !empty($_POST['b4k_year_min']) && empty($_POST['b4k_year_max'])) {
	$start_year = mysql_real_escape_string($_POST['b4k_year_min']);
	$end_year = _b4k_get_max_taxonomy_value('26');
	$temp_filters = array();
	for ($i=$start_year; $i <= $end_year; $i++) { 
		$temp_filters[] = $i;
	}	
	$args['wpsc_product_category'] .= ','.implode(',',$temp_filters);
}

//ad Filetr for only MAX year
if (isset($_POST['b4k_year_min']) && isset($_POST['b4k_year_max']) && empty($_POST['b4k_year_min']) && !empty($_POST['b4k_year_max'])) {
	$end_year = mysql_real_escape_string($_POST['b4k_year_max']);
	$start_year = _b4k_get_min_taxonomy_value('26');
	$temp_filters = array();
	for ($i=$start_year; $i <= $end_year; $i++) { 
		$temp_filters[] = $i;
	}	
	$args['wpsc_product_category'] .= ','.implode(',',$temp_filters);
}

//ad Filetr for MIN AND MAX year setted by user
if (isset($_POST['b4k_year_min']) && isset($_POST['b4k_year_max']) && !empty($_POST['b4k_year_min']) && !empty($_POST['b4k_year_max'])) {
	$start_year = mysql_real_escape_string($_POST['b4k_year_min']);
	$end_year = mysql_real_escape_string($_POST['b4k_year_max']);
	$temp_filters = array();
	for ($i=$start_year; $i <= $end_year; $i++) { 
		$temp_filters[] = $i;
	}	
	$args['wpsc_product_category'] .= ','.implode(',',$temp_filters);
}

//Add filter for only min price
if (isset($_POST['b4k_pmin']) && isset($_POST['b4k_pmax']) && !empty($_POST['b4k_pmin']) && empty($_POST['b4k_pmax'])) {
	$args = $args+b4k_add_metafilter('_wpsc_price', mysql_real_escape_string($_POST['b4k_pmin']),'=>');
}

//Add filter for only max price
if (isset($_POST['b4k_pmin']) && isset($_POST['b4k_pmax']) && empty($_POST['b4k_pmin']) && !empty($_POST['b4k_pmax'])) {
	$args = $args+b4k_add_metafilter('_wpsc_price', mysql_real_escape_string($_POST['b4k_pmax']),'<=');
}

//Add filter for BOTH max price
if (isset($_POST['b4k_pmin']) && isset($_POST['b4k_pmax']) && !empty($_POST['b4k_pmin']) && !empty($_POST['b4k_pmax'])) {
	$args['meta_query'][] = b4k_add_metafilter('_wpsc_price', array(mysql_real_escape_string($_POST['b4k_pmin']),mysql_real_escape_string($_POST['b4k_pmax'])),'BETWEEN');
}

//Generate query
$pageposts = new WP_Query($args);

print '<pre>';
print_r($args);
print '</pre>';

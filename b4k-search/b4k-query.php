<?php

function b4k_add_metafilter($key,$value,$operator = '=',$type = 'numeric'){
	$filter = array('meta_key' => $key,'meta_value' => $value,'meta_compare' => $operator, 'type' => $type);
	return $filter;
}

$args = array( 'post_type' => 'wpsc-product','post_status' => 'publish');

$categories = array('b4k_type' => 'wpsc_product_category','b4k_brand' => 'productbrand','b4k_type' => 'producttype','b4k_status' => 'productstatus', 'b4k_text' => 's');

$range_categories = array('b4k_year_min' => 'productyear','b4k_year_max' => 'productyear','b4k_size_min' => 'productsize','b4k_size_max' => 'productsize');

$range_fields = array('b4k_pmin','b4k_pmax');

$temp_filters = array();

//Loop to add default and custom NON range filter by category 
foreach ($categories as $filter_key => $filter_category) {
	if (isset($_POST[$filter_key]) && !empty($_POST[$filter_key])) {
		$temp_filters[$filter_category] = mysql_real_escape_string($_POST[$filter_key]);
	}
}

if (!empty($temp_filters)) {
	foreach ($temp_filters as $key => $value) {
		$args[$key] = $value;
	}
}

//RANGE FILTERS
//ad Filetr for only MIN year
if (isset($_POST['b4k_year_min']) && isset($_POST['b4k_year_max']) && !empty($_POST['b4k_year_min']) && empty($_POST['b4k_year_max'])) {
	$start = mysql_real_escape_string($_POST['b4k_year_min']);
	$end = _b4k_get_max_taxonomy_value('productyear');
	$temp_filters = array();
	for ($i=$start; $i <= $end; $i++) { 
		$temp_filters[] = $i;
	}	
	$args['productyear'] = implode(',',$temp_filters);
}

//ad Filetr for only MAX year
if (isset($_POST['b4k_year_min']) && isset($_POST['b4k_year_max']) && empty($_POST['b4k_year_min']) && !empty($_POST['b4k_year_max'])) {
	$end = mysql_real_escape_string($_POST['b4k_year_max']);
	$start = _b4k_get_min_taxonomy_value('productyear');
	$temp_filters = array();
	for ($i=$start; $i <= $end; $i++) { 
		$temp_filters[] = $i;
	}	
	$args['productyear'] = implode(',',$temp_filters);
}

//ad Filetr for MIN AND MAX year setted by user
if (isset($_POST['b4k_year_min']) && isset($_POST['b4k_year_max']) && !empty($_POST['b4k_year_min']) && !empty($_POST['b4k_year_max'])) {
	$start = mysql_real_escape_string($_POST['b4k_year_min']);
	$end = mysql_real_escape_string($_POST['b4k_year_max']);
	$temp_filters = array();
	for ($i=$start; $i <= $end; $i++) { 
		$temp_filters[] = $i;
	}	
	$args['productyear'] = implode(',',$temp_filters);
}


//Add Filter for ONLY min size
if (isset($_POST['b4k_size_min']) && isset($_POST['b4k_size_max']) && !empty($_POST['b4k_size_min']) && empty($_POST['b4k_size_max'])) {

	$start = mysql_real_escape_string($_POST['b4k_size_min']);
	$end = _b4k_get_max_taxonomy_value('productsize');
	
	$temp_filters = array();
	for ($i=$start; $i <= $end; $i++) { 
		$temp_filters[] = $i;
	}	
	$args['productsize'] = implode(',',$temp_filters);
}


//Add Filter for ONLY max size
if (isset($_POST['b4k_size_min']) && isset($_POST['b4k_size_max']) && empty($_POST['b4k_size_min']) && !empty($_POST['b4k_size_max'])) {
	$end = mysql_real_escape_string($_POST['b4k_size_max']);
	$start = _b4k_get_min_taxonomy_value('productsize');
	$temp_filters = array();
	for ($i=$start; $i <= $end; $i++) { 
		$temp_filters[] = $i;
	}	
	$args['productsize'] = implode(',',$temp_filters);
}

//ad Filetr for MIN AND MAX year setted by user
if (isset($_POST['b4k_size_min']) && isset($_POST['b4k_size_max']) && !empty($_POST['b4k_size_min']) && !empty($_POST['b4k_size_max'])) {
	$start = mysql_real_escape_string($_POST['b4k_size_min']);
	$end = mysql_real_escape_string($_POST['b4k_size_max']);
	$temp_filters = array();
	for ($i=$start; $i <= $end; $i++) { 
		$temp_filters[] = $i;
	}	
	$args['productsize'] = implode(',',$temp_filters);
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

//print '<pre>';
//print_r($args);
//print '</pre>';
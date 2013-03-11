<?php
function b4k_add_metafilter($key,$value,$operator = '=',$type = 'numeric'){
  $filter = array('meta_key' => $key,'meta_value' => $value,'meta_compare' => $operator, 'type' => $type);
  return $filter;
}

function wdm_add_taxquery($category, $field = 'slug', $terms, $operator = 'AND') {
  return array('tax_query' => array(
    'taxonomy'  => $category,
    'field'     => $field,
    'terms'     => $terms,
    'include_children'  => false,
    'operator'          => $operator,
  ));
};

function wdm_add_metaquery($key, $value, $compare) {
  return array('meta_query' => array(
    'key'  => $category,
    'value'     => $value,
    'compare'     => $compare,
  ));
};


// Default args
$args = array( 'post_type' => 'wpsc-product','post_status' => 'publish');

//Get default maincategory
$main_category = filter_var($_GET['wdm-category'], FILTER_SANITIZE_STRING);

//Custom categories
$custom_categories =  wdm_get_custom_taxomoies_slug();

$filter_values = $_GET;

//Add price filter via meta-query
//Include meta filters like prices via meta_query
if(isset($filter_values['wdm-price'])) {
$filter = 'wdm-price';
if(!empty($filter_values['wdm-price'][0])){

  //Price have ranges values, they come as an array
  if(is_array($filter_values[$filter])) {

    //Unset empty value from query
    foreach($filter_values[$filter] as $key => $value) {
      if(empty($value)) {
        unset($filter_values[$filter][$key]);

        //depending on which range value get passend the meta_query operator
        //should change accordingly. Both values set a AND, first value sets a
        //greater than, last value sets a smaller than
        switch ($key) {
            case 'min':
                $op = '<=';
                $k = 1;
                break;
              case 'max':
                $op = '>=';
                $k = 0;
                break;
          }
        }
      }

    //set operator if it's not setted yet.
    isset($op) ? TRUE : $op = 'BETWEEN';
    count($filter_values[$filter]) > 1 ? $values = $filter_values[$filter] : $values = $filter_values[$filter][$k];
    $args['meta_query'][] = array(
                          'key' => '_wpsc_price',
                          'value' => $values,
                          'compare' => $op
                        );
    }
  }

  //remove already setted filter
  unset($filter_values[$filter]);
}

//Include the main category (boards, sails, masts, booms accessories)
//main categories are like product types and they use the wpsc_product_category taxonomy
if(isset($filter_values['wdm-category'])) {
  $filter = 'wdm-category';

  if (!empty($_GET['wdm-category'])) {

    $args['tax_query'][] =  array(
                                'taxonomy' => 'wpsc_product_category',
                                'field' => 'slug',
                                'terms' => $_GET[$filter],
                                'include_children' => false,
                                'operator' => 'AND'
                              );
  }

  unset($filter_values[$filter]);
}

//Include conditon and style standard filters
if(isset($filter_values['wdm-condition'])) {
  $filter = 'wdm-condition';

  if (!empty($_GET['wdm-condition'])) {

    $args['tax_query'][] =  array(
                                'taxonomy' => $filter,
                                'field' => 'slug',
                                'terms' => $_GET[$filter],
                                'include_children' => false,
                                'operator' => 'AND'
                              );
  }

  unset($filter_values[$filter]);
}

if(isset($filter_values['wdm-style'])) {
  $filter = 'wdm-style';
  if(!empty($_GET['wdm-style'])) {
    $args['tax_query'][] =  array(
                                'taxonomy' => $filter,
                                'field' => 'slug',
                                'terms' => $_GET[$filter],
                                'include_children' => false,
                                'operator' => 'AND'
                              );
  }

  unset($filter_values[$filter]);
}

if(isset($filter_values['wdm-year'])){
  $filter = 'wdm-year';
  if(!empty($filter_values['wdm-year'])) {
    //Exception for Year Taxonomy. it's a taxonomy, therefor to simulate a "range like" search
    //we need to generate all the other term values

    //if both year are passed, set the
    //stat and end range values.
    isset($filter_values[$filter]['min']) ? $start = $filter_values[$filter]['min'] : $start = _wdm_get_min_taxonomy_value('wdm-year');
    isset($filter_values[$filter]['max']) ? $end = $filter_values[$filter]['max'] : $end = _wdm_get_max_taxonomy_value('wdm-year');

    $terms = range('2001','2012');
    $operator = 'IN';

    $args['tax_query'][] =  array(
                              'taxonomy' => $filter,
                              'field' => 'slug',
                              'terms' => $terms,
                              'include_children' => false,
                              'operator' => $operator
                            );
  }

  unset($filter_values[$filter]);
}


//Add the remaining filters
foreach(array_keys($filter_values) as $filter) {
  if(in_array($filter,$custom_categories)) {

  //Unset empty value from query
  foreach($filter_values[$filter] as $key => $value) {
    if(empty($value)) {
      unset($filter_values[$filter][$key]);
    }
  }

    $terms = $filter_values[$filter];

    $args['tax_query'][] =  array(
                          'taxonomy' => $filter,
                          'field' => 'slug',
                          'terms' => $terms,
                          'include_children' => false,
                          'operator' => 'AND'
                        );

    unset($filter_values[$filter]);
  }
}


//Add search text string
if(isset($filter_values['s'])) {
  $filter = 's';
  $args['s'] = $filter_values[$filter];
  unset($filter_values[$filter]);
}

//Generate query
$pageposts = new WP_Query($args);

/**
 * Debugging
print '<div style="background-color:#fff;  padding-top:60px">';
print '<pre>';
$table = get_option('custom_permalink_table');
print_r($table);
$term = get_term('92', 'wpsc_product_category');
print_r($term);
//print_r($custom_categories);
//print '<br/>'
print '</pre>';
//print '<br/>';
//print_r($pageposts->request);
//
*/

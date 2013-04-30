<?php
/*
  Plugin Name: Windmaniacs Create AD
  Description: UI for creating roducts using custom form
  Author: Marco Vito Moscaritolo <marco@agavee.com>
  Version: 1.0
 */

/**
 * Function to create filter.
 *
 * @param string $atts
 *   Attributes to parse
 *
 * @return string
 *   Form to display
 */
function wdm_create_ad_form( $atts ) {
  // Constant salt required to user auth
  $wdm_salt = 'dslkfhadskfsdkf4534asdfasdf325423';

  wp_enqueue_style('wdm-create-ad');

  // Get current step.
  $step = wdm_create_ad_form_get_step(1);

  // Is a callback page
  if (isset($_GET['wdm_data'])) {
    list($post_id, $md5) = explode('-', $_GET['wdm_data']);
    if (md5($post_id . $wdm_salt ) != $md5) {
      wp_die(_('You are try to access to wrong page.'));
    }
    else {
      // Move to last step
      $step = 6;

      // Set post author to current user
      $post = array();
      $post['ID'] = $post_id;
      $post['post_author'] = get_current_user_id();

      // Update the post into the database
      wp_update_post($post);
    }
  }

  // Identify options
  extract( shortcode_atts( array(
    'lang' => 'lang',
  ), $atts ) );

  // Identify javascript file path.
  $script_path = WP_PLUGIN_URL . '/wdm-create-ad/js/wdm-create-ad.js';

  // Add JS file.
  wp_enqueue_script(
    'wdm-create-ad',
    $script_path,
    array('jquery')
  );

  // Add JS localization.
  wp_localize_script('wdm-create-ad', 'WPWdmCreateAd', array(
    'todo' => 'need info?',
  ));

  // Go to next step.
  if ($_POST['action_prev']) {
    $step--;
  }
  if ($_POST['action_next']) {
    $step++;
  }

  $hidden_value  = array();
  $default_value = array();

  // ===========================================================================
  // Load current value
  // ===========================================================================
  switch (TRUE) {
    case $step <= 5: // ========================================================

    case $step <= 4: // ========================================================

    case $step <= 3: // ========================================================
      $default_value['wdm-condiction'] = wdm_create_ad_form_get('wdm-condiction', '');
      $default_value['wdm-year']       = wdm_create_ad_form_get('wdm-year',       '');
      $default_value['wdm-style']      = wdm_create_ad_form_get('wdm-style',      '');

    case $step <= 2: // ========================================================
      $default_value['wdm-category'] = wdm_create_ad_form_get('wdm-category', FALSE);
      // Managed from external function - $default_value['wdm-subcats']

      $languages = qtrans_getSortedLanguages();
      foreach ($languages as $langcode) {
        $default_value['wdm-title-'       . $langcode] = wdm_create_ad_form_get('wdm-title-'       . $langcode, '');
        $default_value['wdm-description-' . $langcode] = wdm_create_ad_form_get('wdm-description-' . $langcode, '');
      }

      $default_value['wdm-price']      = wdm_create_ad_form_get('wdm-price'     , '');
      $default_value['wdm-sale_price'] = wdm_create_ad_form_get('wdm-sale_price', '');

    case $step <= 1: // ========================================================
      $default_value['wdm-type'] = wdm_create_ad_form_get('wdm-type', 'wdm-windsurf-boards');
   }

  // ===========================================================================
  // Load hidden value
  // ===========================================================================
  switch (TRUE) {
    case $step >= 6: // ========================================================
    case $step >= 5: // ========================================================
    case $step >= 4: // ========================================================
      $hidden_value['wdm-condiction'] = wdm_create_ad_form_get('wdm-condiction', '');
      $hidden_value['wdm-year']       = wdm_create_ad_form_get('wdm-year',       '');
      $hidden_value['wdm-style']      = wdm_create_ad_form_get('wdm-style',      '');

    case $step >= 3: // ========================================================
      $hidden_value['wdm-category']    = wdm_create_ad_form_get('wdm-category',    '');
      $hidden_value['wdm-price']       = wdm_create_ad_form_get('wdm-price'     ,  '');
      $hidden_value['wdm-sale_price']  = wdm_create_ad_form_get('wdm-sale_price',  '');

      foreach (qtrans_getSortedLanguages() as $langcode) {
        $hidden_value['wdm-title-'       . $langcode] = wdm_create_ad_form_get('wdm-title-'       . $langcode, '');
        $hidden_value['wdm-description-' . $langcode] = wdm_create_ad_form_get('wdm-description-' . $langcode, '');
      }

      $variable_select = wdm_get_variable_select($hidden_value['wdm-category']);
      foreach ($variable_select as $pos => $term) {
        $data = wdm_create_ad_form_get($term->taxonomy, '');
        $hidden_value["{$term->taxonomy}[$pos]"] = $data[$pos];
      }

    case $step >= 2: // ========================================================
      $hidden_value['wdm-type'] = wdm_create_ad_form_get('wdm-type', '');

    case $step >= 1: // ========================================================
  }

  // ===========================================================================
  // Load default value
  // ===========================================================================
  switch($step) {
    case 1: // =================================================================
      break;

    case 2: // =================================================================
      include_once(__DIR__ . '/../wdm-search/wdm-search.php');

      // Generate data for current step
      $custom_taxonomies = wdm_get_custom_taxonomies();
      foreach ($custom_taxonomies as $id => $term) {
        if ($hidden_value['wdm-type'] === 'wdm-windsurf-boards' && strpos($term->slug, 'wdm-windsurf') === 0) {
          $custom_taxonomies_tmp[$id] = $term;
        }
        if ($hidden_value['wdm-type'] === 'wdm-kitesurfing-boards' && strpos($term->slug, 'wdm-kitesurfing') === 0) {
          $custom_taxonomies_tmp[$id] = $term;
        }
      }

      // Required in template
      $custom_taxonomies = $custom_taxonomies_tmp;
      $variable_select   = wdm_get_variable_select($default_value['wdm-category']);
      $languages         = qtrans_getSortedLanguages();
      break;

    case 3: // =================================================================
      $condictions       = wdm_create_ad_terms('wdm-condition');
      $years             = wdm_create_ad_terms('wdm-year');
      $styles            = wdm_create_ad_terms('wdm-style');
      break;

    case 5: // =================================================================
      // Ensure to add file management item
      if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
      }

      // Empty loaded files
      $product_images = array();

      for ($i = 0; $i < 5; $i++) {
        $data_key = "uploadfiles_$i";
        if (!empty($_FILES) && isset($_FILES[$data_key]) && $_FILES[$data_key]['size'] > 0) {
          $upload = wp_handle_upload($_FILES[$data_key], array('test_form' => FALSE));
          if (isset( $upload['error']) && '0' != $upload['error']) {
            wp_die( 'There was an error uploading your file. ' );
          } else {
            $product_images[] = $upload;
          }
        }
      }
      // break;

//    case 5: // =================================================================
      // TODO: refactor post creation to use wpsc_insert_product

      // Create post..
      $titles = array();
      $descs = array();
      foreach (qtrans_getSortedLanguages() as $langcode) {
        $titles[$langcode] = wp_strip_all_tags($hidden_value['wdm-title-' . $langcode]);
        $descs [$langcode] = $hidden_value['wdm-description-' . $langcode];
      }

      // Create post structure
      $post = array(
        'post_title'    => wdm_create_ad_generate_translation_blob($titles),
        'post_content'  => wdm_create_ad_generate_translation_blob($descs),
        'post_status'   => 'draft',
        'post_author'   => get_current_user_id(),
        'post_type'     => 'wpsc-product',
      );

      // Save post
      $post_id = wp_insert_post($post, $wp_error);

      // Category
      $product_category = get_term_by('slug', str_replace('ing-', '-', $hidden_value['wdm-type']), 'wpsc_product_category');
      $terms = array($product_category->term_id);
      wp_set_post_terms($post_id, $terms, 'wpsc_product_category', FALSE);

      // Sub category
      $product_sub_category = array();
      foreach (wdm_create_ad_form_get($hidden_value['wdm-category']) as $value) {
        $term = get_term_by('slug', $value, $hidden_value['wdm-category']);
        $product_sub_category[] = $term->term_id;
      }
      wp_set_post_terms($post_id, $product_sub_category, $hidden_value['wdm-category'], FALSE);

      // Condictions
      $terms = array($hidden_value['wdm-condiction']);
      wp_set_post_terms($post_id, $terms, 'wdm-condition', FALSE);

      // Year
      $terms = array($hidden_value['wdm-year']);
      wp_set_post_terms($post_id, $terms, 'wdm-year', FALSE);

      // Style
      $terms = array($hidden_value['wdm-style']);
      wp_set_post_terms($post_id, $terms, 'wdm-style', FALSE);

      // Price
      add_post_meta($post_id, '_wpsc_price', $hidden_value['wdm-price']);

      // Sale price
      add_post_meta($post_id, '_wpsc_special_price', $hidden_value['wdm-sale_price']);

      // you must first include the image.php file
      // for the function wp_generate_attachment_metadata() to work
      require_once(ABSPATH . 'wp-admin/includes/image.php');

      // Only first image need to be stored as thumbnail
      $first_image = TRUE;

      // Manage all available images
      foreach ($product_images as $upload) {
        $filename = basename($upload['file']);
        // Create attachment post
        $attachment = array(
           'guid' => $upload['url'],
           'post_mime_type' => $upload['type'],
           'post_title' => $filename,
           'post_content' => '',
           'post_status' => 'inherit',
           'post_parent' => $post_id,
        );
        $attach_id = wp_insert_attachment( $attachment, $filename);
        $attach_data = wp_generate_attachment_metadata( $attach_id, $filename, $post_id);
        wp_update_attachment_metadata( $attach_id, $attach_data );

        // This add only the thumb image..
        if ($first_image) {
          add_post_meta($post_id, '_thumbnail_id', $attach_id);
          $first_image = FALSE;
        }
      }

      // $_SESSION['wdm-create-ad-post_id'][] = $post_id;

      $destination_url = get_permalink() . '?' . http_build_query(array(
        'wdm_data'  => $post_id . '-' . md5($post_id . $wdm_salt)
      ));
      $destination_url = urlencode($destination_url);

      wdm_create_ad_send_creation_mail($post);
      break;
    case 6:
      // Move back to result page
      $step = 5;
      break;
  }

  // Add right mimetype in form (only for image upload)
  if ($step == 4) {
    $form_extra_attr = 'enctype="multipart/form-data"';
  }

  // Dispaly reload button only on specified steps
  $button_current = ($step == 2);

  include(__DIR__ . "/form/form-wrapper.php");
}

/**
 * Find form current step.
 *
 * @param int $default_step
 *   The default step to display.
 *
 * @return int
 *   The step to show
 */
function wdm_create_ad_form_get_step($default_step = 0) {
  $step = wdm_create_ad_form_get('wdm_create_ad_form_step', $default_step);
  $step = (int) $step;
  if ($step > 6 || $step < 1) {
    $step = $default_step;
  }

  return $step;
}

/**
 * Find form specified value.
 *
 * @param int $item
 *   The item element to find.
 * @param int $default_value
 *   The default value to display.
 *
 * @return int
 *   The step to show
 */
function wdm_create_ad_form_get($item, $default_value = 0) {
  return isset($_POST[$item]) ? $_POST[$item] : $default_value;
}

/**
 * Return string to make element a value.
 */
function wdm_create_ad_is_default($value, $default, $type = 'selected') {
  if ($default == $value) {
    return " $type=\"$type\"";
  }
}

/**
 * Generate term array to display information in form.
 *
 * @param string $vocabulary
 *   The vocabulary identifier
 *
 * @return array
 *   The terms list; array key is term ID and value the term name.
 */
function wdm_create_ad_terms($vocabulary) {
  $terms = get_terms($vocabulary, array('hide_empty' => FALSE));
  $return = array();

  foreach ($terms as $term) {
    $return[$term->term_id] = $term->name;
  }

  return $return;
}

function wdm_create_ad_get_column_headers() {
  return array(
    'image'  => '',
    'title'  => _('Name'),
    'description'  => _('Description'),
    'price'  => _('Price'),
    'sale_price' => _('Sale Price'),
  );
}

/**
 * Create a page with product for current user
 */
function wdm_create_ad_products() {
  if ( is_user_logged_in() ) {
    global $current_user;
    get_currentuserinfo();
  } else {
    return _("You are not allowed to see content here");
  }

  // $products = query_posts('post_type="wpsc-product"&author=' . $current_user->ID);
  query_posts('post_type="wpsc-product"&author=' . $current_user->ID);

  $headers = wdm_create_ad_get_column_headers();

  $output  = '<table>';
  $output .=   '<thead>';
  $output .=     '<tr>';
  foreach ($headers as $header) {
    $output .=     '<th>' . $header. '</th>';
  }
  $output .=     '</tr>';
  $output .=   '</thead>';
  $output .=   '<tbody>';

  if ( have_posts() ) {
    while ( have_posts() ) {
      the_post();
      $output .= '<tr>';
      $output .=   '<td>' . 'IMAGE' . '</td>';
      $output .=   '<td>' . get_the_title() . '</td>';
      $output .=   '<td>' . get_the_excerpt() . '</td>';
      $output .=   '<td>' . 'PRICE' . '</td>';
      $output .=   '<td>' . 'SALE_PRICE' . '</td>';
      $output .= '</tr>';
    }
  }

  $output .=   '</tbody>';
  $output .= '</table>';

  wp_reset_query();

  return $output;
}

function wdm_create_ad_generate_translation_blob($data) {
  $data_string = '';
  foreach ($data as $langcode => $value) {
    $data_string .= '<!--:' .  $langcode . '-->' .  $value . '<!--:-->';
  }
  return $data_string;
}

function wdm_create_ad_send_creation_mail($post = NULL) {
  // Mail configuration
  $mail_to = get_option('admin_email','g.natili@gnstudio.com');
  $mail_subj = _('Someone added a new product');
  $mail_body = file_get_contents(__DIR__ . '/mail/product-create.html');

  // Some basic replacement
  foreach (array('siteurl', 'blogname', 'blogdescription', 'admin_email') as $value) {
    $mail_body = str_replace("[site:$value]", get_option($value), $mail_body);
  }

  $mail_body = str_replace("[post:id]",       $post->ID,    $mail_body);
  $mail_body = str_replace("[post:title]",    $post->post_title, $mail_body);
  $mail_body = str_replace("[post:status]",   $post->post_status, $mail_body);
  $mail_body = str_replace("[post:modified]", $post->post_modified, $mail_body);


  // Set filter to send HTML mail
  add_filter('wp_mail_content_type', 'wdm_create_ad_mail_content_type');

  // Send mail
  wp_mail( $mail_to, $mail_subj, $mail_body);

  // Reset mail filter
  remove_filter( 'wp_mail_content_type', 'wdm_create_ad_mail_content_type');
}

function wdm_create_ad_mail_content_type() {
  return 'text/html';
}

// Create shortcode for the form
add_shortcode( 'wdm-create-ad', 'wdm_create_ad_form' );

// Create shortcode for product list
add_shortcode( 'wdm-list-products', 'wdm_create_ad_products' );

// Register CSS
wp_register_style('wdm-create-ad', plugins_url('css/style.css', __FILE__));

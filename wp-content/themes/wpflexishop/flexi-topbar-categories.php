<div id="header-categories">
    <h4 class="top-nav-header">
    	<a href='<?php echo get_option('product_list_url'); ?>'>
		<?php _e( 'Products', PRIMA_DOMAIN ); ?>
        </a>
    </h4>
	<?php 
	$instance_categories = get_terms( 'wpsc_product_category', 'hide_empty=0&parent=0'); 
	$cats_class = 'header-categories-drop';
	if(prima_get_option('topcatsmode')=='horizontal') {
		$cats_num = count($instance_categories);
		$cats_perrow = $cats_num > 5 ? 5 : $cats_num;
		$cats_width = 125;
		$cats_megawidth = ( 125 + 20 + 20 ) * $cats_perrow - 40;
		$cats_class .= ' header-categories-horz';
		$i = 0;
	}
	?>
    <div class="<?php echo $cats_class; ?>" <?php if(isset($cats_megawidth)) echo 'style="width:'.$cats_megawidth.'px"'; ?>>
        <?php 
		foreach($instance_categories as $categories){ 
            $term_id = $categories->term_id;
            $term_name = $categories->name;
			$cat_class = 'categories-group';
			if(prima_get_option('topcatsmode')=='horizontal') {
				$i++;
				if ($i%$cats_perrow==1) $cat_class .= ' categories-group-first';
				if ($i%$cats_perrow==0) $cat_class .= ' categories-group-last';
			}
            ?>
            <div class='<?php echo $cat_class; ?>' id='top_categorisation_group_<?php echo $term_id; ?>'>
                <h4 class='wpsc_category_title'><a href="<?php echo get_term_link( $categories, 'wpsc_product_category' ); ?>"><?php echo $term_name; ?></a></h4>
				<?php if(prima_get_option('topcatsdepth')!='no') { ?>
                    <?php $subcat_args = array( 'taxonomy' => 'wpsc_product_category', 
                    'title_li' => '', 'show_count' => 0, 'hide_empty' => 0, 'echo' => false,
                    'show_option_none' => '', 'child_of' => $term_id ); ?>
                    <?php if(get_option('show_category_count') == 1) $subcat_args['show_count'] = 1; ?>
                    <?php if(prima_get_option('topcatsdepth')) $subcat_args['depth'] = prima_get_option('topcatsdepth'); ?>
                    <?php $subcategories = wp_list_categories( $subcat_args ); ?>
                    <?php if ( $subcategories ) { ?>
                    <ul class='wpsc_categories wpsc_top_level_categories'><?php echo $subcategories ?></ul>
                    <?php } ?>
				<?php } ?>
                <div class='clear_category_group'></div>
            </div>
            <?php
        } 
        ?>
    </div>
</div>

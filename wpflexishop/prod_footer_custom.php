<div class="custom_product_footer">
	<div class="lt">
		<?php echo get_the_term_list( '', 'wpsc_product_category', '<p>'.__('Product Categories:', PRIMA_DOMAIN).' ', ', ', '</p>' ) ?>
		<?php echo get_the_term_list( '', 'product_tag', '<p>'.__('Product Tags:', PRIMA_DOMAIN).' ', ', ', '</p>' ); ?>
	</div>
	<div class="rt">
		<span>Per saperne di più contatta: <?php echo the_author_link(); ?></span>
	</div>
	<?php if (function_exists('wp_authorbox')) echo wp_authorbox(); ?>
</div>
<div class="addthis_container_custom">
	<?php
		/**$url = get_permalink(); 
		$title = get_the_title();
		$style = array(
		    //'size' => '20', // size of the icons.  Either 16 or 32
		    'services' => 'facebook_like,tweet,plus_one_share_counter', // the services you want to always appear
		    'preferred' => '0', // the number of auto personalized services
		    'more' => true,
		    'counter' => true // if you want to have a more button at the end
		);
		do_action('addthis_widget',$url ,$title, $style);
		**/
	?>
	<div class="addthis_toolbox addthis_default_style ">
		<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
		<a class="addthis_button_tweet"></a>
		<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
		<a class="addthis_counter addthis_pill_style"></a>
	</div>
</div>
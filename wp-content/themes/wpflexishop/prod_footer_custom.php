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
<div class="custom_product_footer">
	<div class="lt">
		<?php echo get_the_term_list( '', 'wpsc_product_category', '<p>'.__('Product Categories:', PRIMA_DOMAIN).' ', ', ', '</p>' ) ?>
		<?php echo get_the_term_list( '', 'product_tag', '<p>'.__('Product Tags:', PRIMA_DOMAIN).' ', ', ', '</p>' ); ?>
	</div>
	
</div>
<h3 class="contact-profile">CONTACT PROFILE</h3>
<div class="box-author">

	<?php echo get_avatar( get_the_author_meta('ID'), 100 ); ?>
	<ul>
		<li>First name:<span>&nbsp;<?php echo the_author_meta('first_name'); ?></span></li>
		<li>Last name:<span>&nbsp;<?php echo the_author_meta('last_name'); ?></span></li>
		<li>Email:<span>&nbsp;<?php echo antispambot(the_author_meta('user_email')); ?></span></a></li>
		<?php
		$post_author = get_the_author_meta('ID'); //author id
		$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_author = $post_author AND post_type IN ('wpsc-product') and post_status = 'publish'" ); ?>
		<li>Products published:<span>&nbsp;<?php echo $count; ?></span></li>
		<?php $registered = ($user_info->user_registered . "\n");?>
		<li>Maniac since:<span>&nbsp;<?php echo date("n/j/Y", strtotime($registered)); ?></span></li>
		

	</ul>
	<div class="wdm-separator"></div>
	<div class="button-author">
		<a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author_meta('first_name'); ?>'S PAGE</a>  
		<a class="last" href="mailto:<?php echo antispambot(the_author_meta('user_email')); ?>" target="_blank">Send an email</a>
	</div>
	<div class="clear"></div>
</div>


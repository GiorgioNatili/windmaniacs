<?php
add_filter('prima_theme_settings_defaults', 'prima_theme_settings_load_defaults');
function prima_theme_settings_load_defaults($defaults) {
	 $defaults = array(
		'themelayout' => 'simple',
		'aboutus' => __( 'This can be found in the theme options page under appearance, this is also a widget area', PRIMA_DOMAIN ),
		'copyright' => __( 'Copyright 2011 FlexiShop', PRIMA_DOMAIN ),
		'sitedesc' => __( 'This is a short paragraph which should be used to give an overview of your products. It can be found under in the theme options page', PRIMA_DOMAIN ),
		'usernav' => 'yes',
		'topcategories' => 'yes',
		'topcatsdepth' => '1',
		'topcatsmode' => 'horizontal',
		'topcart' => 'yes',
		'saledate' => '',
		'slidertrans' => 'fade',
		'sliderspeed' => '1500',
		'sliderpause' => '4000',
		'sliderauto' => 'true',
		'sliderproductprice' => 'yes',
		'sliderproductdesc' => 'yes',
		'sliderproductdesclimit' => 180,
		'sliderposttitle' => 'yes',
		'sliderpostexcerpt' => 'yes',
		'sliderbackground' => 'default',
		'sliderbackgroundpos' => 'center',
		'sliderbackgroundrepeat' => 'repeat',
		'homepagecategories' => '1',
		'homepagecatname' => '1',
		'homepagecatslide' => 'yes',
		'homepagecatsid' => 'groups',
		'homepagebestsellers' => '0',
		'homepagelatestproducts' => '1',
		'homepagelatestname' => '1',
		'homepagelatestsale' => '1',
		'homepagelatestslide' => 'yes',
		'footertop' => '1',
		'footernews' => '2',
		'twitter' => 'primathemes',
		'footertweets' => '3',
		'footerbottom' => '',
		'sitelogo' => '',
		'leaderimage' => '',
		'bodysetting' => '',
		'backgroundcol' => '',
		'headercol' => '',
		'boxedcol' => '',
		'footercol' => '',
		'copyrightcol' => '',
		'headingcol' => '',
		'paracolor' => '',
		'linkcol' => '',
		'footerheadercol' => '',
		'footerlinkcol' => '',
		'footerfontcol' => '',
		'headerlinkcol' => '',
		'copyrightfontcol' => '',
		'headerfont' => '',
		'bodyfont' => '',
		'customcss' => ''
	 );
	return $defaults;
}
add_action('admin_menu', 'prima_theme_settings_load_boxes');
function prima_theme_settings_load_boxes() {
	global $_prima_settings_pagehook;
	add_action('load-'.$_prima_settings_pagehook, 'prima_theme_settings_boxes');
}
function prima_theme_settings_boxes() {
	global $_prima_settings_pagehook;
	add_meta_box('prima-theme-settings-general', __('General Settings', PRIMA_DOMAIN), 'prima_theme_settings_general_box', $_prima_settings_pagehook, 'column1');
	add_meta_box('prima-theme-settings-topbar', __('Top Bar', PRIMA_DOMAIN), 'prima_theme_settings_topbar_box', $_prima_settings_pagehook, 'column1');
	add_meta_box('prima-theme-settings-productsearch', __('Leader Product Search', PRIMA_DOMAIN), 'prima_theme_settings_productsearch_box', $_prima_settings_pagehook, 'column1');
	add_meta_box('prima-theme-settings-blog', __('Blog / Archives Page', PRIMA_DOMAIN), 'prima_theme_settings_blog_box', $_prima_settings_pagehook, 'column1');
	add_meta_box('prima-theme-settings-frontpage', __('Frontpage Template', PRIMA_DOMAIN), 'prima_theme_settings_frontpage_box', $_prima_settings_pagehook, 'column1');
	add_meta_box('prima-theme-settings-productspage', __('Products Page', PRIMA_DOMAIN), 'prima_theme_settings_productspage_box', $_prima_settings_pagehook, 'column1');
	add_meta_box('prima-theme-settings-singleproductspage', __('Single Product Page', PRIMA_DOMAIN), 'prima_theme_settings_singleproductspage_box', $_prima_settings_pagehook, 'column1');
	add_meta_box('prima-theme-settings-footer', __('Footer', PRIMA_DOMAIN), 'prima_theme_settings_footer_box', $_prima_settings_pagehook, 'column1');
	// add_meta_box('prima-theme-settings-scripts', __('Header/Footer Scripts', PRIMA_DOMAIN), 'prima_theme_settings_scripts_box', $_prima_settings_pagehook, 'column1');
	add_meta_box('prima-theme-settings-branding', __('Branding', PRIMA_DOMAIN), 'prima_theme_settings_branding_box', $_prima_settings_pagehook, 'column2');
	add_meta_box('prima-theme-settings-fonts', __('Font Family', PRIMA_DOMAIN), 'prima_theme_settings_fonts_box', $_prima_settings_pagehook, 'column2');
	add_meta_box('prima-theme-settings-design', __('Main Design Settings', PRIMA_DOMAIN), 'prima_theme_settings_design_box', $_prima_settings_pagehook, 'column2');
	add_meta_box('prima-theme-settings-optionaldesign', __('Optional Design Settings', PRIMA_DOMAIN), 'prima_theme_settings_optionaldesign_box', $_prima_settings_pagehook, 'column2');
	add_meta_box('prima-theme-settings-customcss', __('Custom CSS', PRIMA_DOMAIN), 'prima_theme_settings_customcss_box', $_prima_settings_pagehook, 'column2');
}
function prima_theme_settings_general_box() { ?>
	<p>
	<?php _e("Overall Theme Layout:", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[themelayout]">
		<option style="padding-right:10px;" value="simple" <?php selected('simple', prima_get_option('themelayout')); ?>><?php _e("Simple", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="full" <?php selected('full', prima_get_option('themelayout')); ?>><?php _e("Full", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="boxed" <?php selected('boxed', prima_get_option('themelayout')); ?>><?php _e("Boxed", PRIMA_DOMAIN); ?></option>
	</select>
    </p>
	<p><?php _e("About Us:", PRIMA_DOMAIN); ?><br />
	<textarea name="<?php echo PRIMA_OPTIONS; ?>[aboutus]" cols="39" rows="2"><?php prima_option('aboutus'); ?></textarea>
    </p>
	<p><?php _e("Copyright Text:", PRIMA_DOMAIN); ?><br />
	<textarea name="<?php echo PRIMA_OPTIONS; ?>[copyright]" cols="39" rows="2"><?php prima_option('copyright'); ?></textarea>
    </p>
<?php
}
function prima_theme_settings_productsearch_box() { 
	if(!class_exists('WP_eCommerce')) {
		echo '<p>'.sprintf( __("The options is available when %s plugin is active.", PRIMA_DOMAIN), '<a href="http://wordpress.org/extend/plugins/wp-e-commerce">WP e-Commerce</a>').'</p>'; 
		echo '<div style="display: none;">'; 
	}
	?>
	<p>
	<?php _e("Show Product Search Form:", PRIMA_DOMAIN); ?>
    </p>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[searchformindex]" id="<?php echo PRIMA_OPTIONS; ?>[searchformindex]" value="yes" <?php checked('yes', prima_get_option('searchformindex')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[searchformindex]"><?php _e("Blog/Archives Page", PRIMA_DOMAIN); ?></label>
    </p>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[searchformsingle]" id="<?php echo PRIMA_OPTIONS; ?>[searchformsingle]" value="yes" <?php checked('yes', prima_get_option('searchformsingle')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[searchformsingle]"><?php _e("Single Post Page", PRIMA_DOMAIN); ?></label>
    </p>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[searchformpage]" id="<?php echo PRIMA_OPTIONS; ?>[searchformpage]" value="yes" <?php checked('yes', prima_get_option('searchformpage')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[searchformpage]"><?php _e("Page", PRIMA_DOMAIN); ?></label>
    </p>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[searchformproducts]" id="<?php echo PRIMA_OPTIONS; ?>[searchformproducts]" value="yes" <?php checked('yes', prima_get_option('searchformproducts')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[searchformproducts]"><?php _e("Products Page", PRIMA_DOMAIN); ?></label>
    </p>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[searchformcheckout]" id="<?php echo PRIMA_OPTIONS; ?>[searchformcheckout]" value="yes" <?php checked('yes', prima_get_option('searchformcheckout')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[searchformcheckout]"><?php _e("Checkout Page", PRIMA_DOMAIN); ?></label>
    </p>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[searchform404]" id="<?php echo PRIMA_OPTIONS; ?>[searchform404]" value="yes" <?php checked('yes', prima_get_option('searchform404')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[searchform404]"><?php _e("404 Not Found Page", PRIMA_DOMAIN); ?></label>
    </p>
	<div class="notice">
	<p>
	<?php _e('Note: This is not Product Search from Gold Cart Plugin', PRIMA_DOMAIN); ?>
	</p>
	</div>
	<?php
	if(!class_exists('WP_eCommerce')) echo '</div>'; 
}
function prima_theme_settings_blog_box() { 
	?>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[bloghideimage]" id="<?php echo PRIMA_OPTIONS; ?>[bloghideimage]" value="yes" <?php checked('yes', prima_get_option('bloghideimage')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[bloghideimage]"><?php _e("Hide Featured Image at Blog Page", PRIMA_DOMAIN); ?></label>
    </p>
	<p>
	<?php _e("Post Content Display at Blog Page:", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[blogcontent]">
		<option style="padding-right:10px;" value="" <?php selected('', prima_get_option('blogcontent')); ?>><?php _e("Post Excerpt (Summary)", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="content" <?php selected('content', prima_get_option('blogcontent')); ?>><?php _e("Post Content (Full)", PRIMA_DOMAIN); ?></option>
	</select>
    </p>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[blogposthideimage]" id="<?php echo PRIMA_OPTIONS; ?>[blogposthideimage]" value="yes" <?php checked('yes', prima_get_option('blogposthideimage')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[blogposthideimage]"><?php _e("Hide Featured Image at Single Post Page", PRIMA_DOMAIN); ?></label>
    </p>
	<?php
}
function prima_theme_settings_frontpage_box() { ?>
	<p>
	<?php _e("Slider Height:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[sliderheight]" value="<?php echo esc_attr( prima_get_option('sliderheight') ); ?>" size="5" />
	<?php _e("px", PRIMA_DOMAIN); ?><br/>
    <span class="description"><?php _e("You may need to reupload your slider image if you use big height value", PRIMA_DOMAIN); ?></span>
    </p>
	<p><?php _e("Slider Transition Effect:", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[slidertrans]">
		<option style="padding-right:10px;" value="fade" <?php selected('fade', prima_get_option('slidertrans')); ?>><?php _e("Fade", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="horizontal" <?php selected('horizontal', prima_get_option('slidertrans')); ?>><?php _e("Horizontal Slide", PRIMA_DOMAIN); ?></option>
	</select></p>
	<p>
	<?php _e("Slider Speed:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[sliderspeed]" value="<?php echo esc_attr( prima_get_option('sliderspeed') ); ?>" size="5" />
	<?php _e("(in miliseconds)", PRIMA_DOMAIN); ?>
    </p>
	<p>
	<?php _e("Slider Pause:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[sliderpause]" value="<?php echo esc_attr( prima_get_option('sliderpause') ); ?>" size="5" />
	<?php _e("(in miliseconds)", PRIMA_DOMAIN); ?>
    </p>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[sliderauto]" id="<?php echo PRIMA_OPTIONS; ?>[sliderauto]" value="true" <?php checked('true', prima_get_option('sliderauto')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[sliderauto]"><?php _e("Slider Auto Play", PRIMA_DOMAIN); ?></label>
    </p>
    <hr class="div" style="clear:both"/>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[sliderposttitle]" id="<?php echo PRIMA_OPTIONS; ?>[sliderposttitle]" value="yes" <?php checked('yes', prima_get_option('sliderposttitle')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[sliderposttitle]"><?php _e("Show Post Title in News(Post) Slider", PRIMA_DOMAIN); ?></label>
    </p>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[sliderpostexcerpt]" id="<?php echo PRIMA_OPTIONS; ?>[sliderpostexcerpt]" value="yes" <?php checked('yes', prima_get_option('sliderpostexcerpt')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[sliderpostexcerpt]"><?php _e("Show Post Excerpt in News(Post) Slider", PRIMA_DOMAIN); ?></label>
    </p>
    <hr class="div" style="clear:both"/>
	<?php
	if(!class_exists('WP_eCommerce')) {
		echo '<div style="display: none;">'; 
	}
	?>
		<p>
		<?php _e("Custom Image Width in Product Slider:", PRIMA_DOMAIN); ?>
		<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[sliderproductwidth]" value="<?php echo esc_attr( prima_get_option('sliderproductwidth') ); ?>" size="5" />
		<?php _e("px", PRIMA_DOMAIN); ?>
		</p>
		<p>
		<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[sliderproductprice]" id="<?php echo PRIMA_OPTIONS; ?>[sliderproductprice]" value="yes" <?php checked('yes', prima_get_option('sliderproductprice')); ?> /> 
		<label for="<?php echo PRIMA_OPTIONS; ?>[sliderproductprice]"><?php _e("Show Price in Product Slider", PRIMA_DOMAIN); ?></label>
		</p>
		<p>
		<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[sliderproductdesc]" id="<?php echo PRIMA_OPTIONS; ?>[sliderproductdesc]" value="yes" <?php checked('yes', prima_get_option('sliderproductdesc')); ?> /> 
		<label for="<?php echo PRIMA_OPTIONS; ?>[sliderproductdesc]"><?php _e("Show Product Description in Product Slider", PRIMA_DOMAIN); ?></label>
		</p>
		<p>
		<?php _e("Limit Product Description in Product Slider:", PRIMA_DOMAIN); ?>
		<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[sliderproductdesclimit]" value="<?php echo esc_attr( prima_get_option('sliderproductdesclimit') ); ?>" size="5" />
		<?php _e("characters", PRIMA_DOMAIN); ?>
		</p>
		<p>
		<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[sliderproductadddesc]" id="<?php echo PRIMA_OPTIONS; ?>[sliderproductadddesc]" value="yes" <?php checked('yes', prima_get_option('sliderproductadddesc')); ?> /> 
		<label for="<?php echo PRIMA_OPTIONS; ?>[sliderproductadddesc]"><?php _e("Show Product Additional Description in Product Slider", PRIMA_DOMAIN); ?></label>
		</p>
		<p>
		<?php _e("Limit Product Additional Description in Product Slider:", PRIMA_DOMAIN); ?>
		<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[sliderproductadddesclimit]" value="<?php echo esc_attr( prima_get_option('sliderproductadddesclimit') ); ?>" size="5" />
		<?php _e("characters", PRIMA_DOMAIN); ?>
		</p>
		<hr class="div" style="clear:both"/>
	<?php if(!class_exists('WP_eCommerce')) echo '</div>'; ?>
	<p><?php _e("Brief Company/Product Overview:", PRIMA_DOMAIN); ?><br />
	<textarea name="<?php echo PRIMA_OPTIONS; ?>[sitedesc]" cols="39" rows="2"><?php prima_option('sitedesc'); ?></textarea>
    </p>
<hr class="div" style="clear:both"/>
<h4><?php _e( 'Product Categories Section', PRIMA_DOMAIN ); ?></h4>
<?php
if(!class_exists('WP_eCommerce')) {
	echo '<p>'.sprintf( __("The options is available when %s plugin is active.", PRIMA_DOMAIN), '<a href="http://wordpress.org/extend/plugins/wp-e-commerce">WP e-Commerce</a>').'</p>'; 
	echo '<div style="display: none;">'; 
}
?>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[homepagecategories]" id="<?php echo PRIMA_OPTIONS; ?>[homepagecategories]" value="1" <?php checked(1, prima_get_option('homepagecategories')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[homepagecategories]"><?php _e("Show Product Categories", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<?php _e("Number of categories:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[homepagecatnumber]" value="<?php echo esc_attr( prima_get_option('homepagecatnumber') ); ?>" size="6" />
    </p>
	<p>
	<?php _e("Image Width:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[homepagecatwidth]" value="<?php echo esc_attr( prima_get_option('homepagecatwidth') ); ?>" size="6" />
    </p>
	<p>
	<?php _e("Image Height:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[homepagecatheight]" value="<?php echo esc_attr( prima_get_option('homepagecatheight') ); ?>" size="6" />
    </p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[homepagecatname]" id="<?php echo PRIMA_OPTIONS; ?>[homepagecatname]" value="1" <?php checked(1, prima_get_option('homepagecatname')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[homepagecatname]"><?php _e("Show Product Categories Name", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[homepagecatslide]" id="<?php echo PRIMA_OPTIONS; ?>[homepagecatslide]" value="yes" <?php checked('yes', prima_get_option('homepagecatslide')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[homepagecatslide]"><?php _e("Slideable?", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[homepagecatslideauto]" id="<?php echo PRIMA_OPTIONS; ?>[homepagecatslideauto]" value="yes" <?php checked('yes', prima_get_option('homepagecatslideauto')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[homepagecatslideauto]"><?php _e("Auto Play Slider?", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<?php _e("Show Product Category:", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[homepagecatsid]">
		<option style="padding-right:10px;" value="all" <?php selected('all', prima_get_option('homepagecatsid')); ?>><?php _e("-- All --", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="groups" <?php selected('groups', prima_get_option('homepagecatsid')); ?>><?php _e("-- Top Level Categories --", PRIMA_DOMAIN); ?></option>
		<?php $instance_categories = get_terms( 'wpsc_product_category', 'hide_empty=0&parent=0');
		foreach($instance_categories as $categories){ 
			$term_id = $categories->term_id;
			$term_name = $categories->name;
			?>
			<option style="padding-right:10px;" value="<?php echo $term_id ?>" <?php selected($term_id, prima_get_option('homepagecatsid')); ?>><?php echo __('Child of', PRIMA_DOMAIN).' "'.$term_name.'"' ?></option>
		 <?php } ?>   
	</select>
	</p>
<?php if(!class_exists('WP_eCommerce')) echo '</div>'; ?>
<hr class="div" style="clear:both"/>
<h4><?php _e( 'Best Sellers Section', PRIMA_DOMAIN ); ?></h4>
<?php
if(!class_exists('WP_eCommerce')) {
	echo '<p>'.sprintf( __("The options is available when %s plugin is active.", PRIMA_DOMAIN), '<a href="http://wordpress.org/extend/plugins/wp-e-commerce">WP e-Commerce</a>').'</p>'; 
	echo '<div style="display: none;">'; 
}
?>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[homepagebestsellers]" id="<?php echo PRIMA_OPTIONS; ?>[homepagebestsellers]" value="1" <?php checked(1, prima_get_option('homepagebestsellers')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[homepagebestsellers]"><?php _e("Show Best Sellers", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<?php _e("Number of products:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[homepagebestnumber]" value="<?php echo esc_attr( prima_get_option('homepagebestnumber') ); ?>" size="6" />
    </p>
	<p>
	<?php _e("Image Width:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[homepagebestwidth]" value="<?php echo esc_attr( prima_get_option('homepagebestwidth') ); ?>" size="6" />
    </p>
	<p>
	<?php _e("Image Height:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[homepagebestheight]" value="<?php echo esc_attr( prima_get_option('homepagebestheight') ); ?>" size="6" />
    </p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[homepagebestname]" id="<?php echo PRIMA_OPTIONS; ?>[homepagebestname]" value="1" <?php checked(1, prima_get_option('homepagebestname')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[homepagebestname]"><?php _e("Show Product Name", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[homepagebestsale]" id="<?php echo PRIMA_OPTIONS; ?>[homepagebestsale]" value="1" <?php checked(1, prima_get_option('homepagebestsale')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[homepagebestsale]"><?php _e("Show Sale Icon", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[homepagebestslide]" id="<?php echo PRIMA_OPTIONS; ?>[homepagebestslide]" value="yes" <?php checked('yes', prima_get_option('homepagebestslide')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[homepagebestslide]"><?php _e("Slideable?", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[homepagebestslideauto]" id="<?php echo PRIMA_OPTIONS; ?>[homepagebestslideauto]" value="yes" <?php checked('yes', prima_get_option('homepagebestslideauto')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[homepagebestslideauto]"><?php _e("Auto Play Slider?", PRIMA_DOMAIN); ?></label>
	</p>
<?php if(!class_exists('WP_eCommerce')) echo '</div>'; ?>
<hr class="div" style="clear:both"/>
<h4><?php _e( 'Latest Products Section', PRIMA_DOMAIN ); ?></h4>
<?php
if(!class_exists('WP_eCommerce')) {
	echo '<p>'.sprintf( __("The options is available when %s plugin is active.", PRIMA_DOMAIN), '<a href="http://wordpress.org/extend/plugins/wp-e-commerce">WP e-Commerce</a>').'</p>'; 
	echo '<div style="display: none;">'; 
}
?>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[homepagelatestproducts]" id="<?php echo PRIMA_OPTIONS; ?>[homepagelatestproducts]" value="1" <?php checked(1, prima_get_option('homepagelatestproducts')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[homepagelatestproducts]"><?php _e("Show Latest Products", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<?php _e("Number of products:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[homepagelatestnumber]" value="<?php echo esc_attr( prima_get_option('homepagelatestnumber') ); ?>" size="6" />
    </p>
	<p>
	<?php _e("Image Width:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[homepagelatestwidth]" value="<?php echo esc_attr( prima_get_option('homepagelatestwidth') ); ?>" size="6" />
    </p>
	<p>
	<?php _e("Image Height:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[homepagelatestheight]" value="<?php echo esc_attr( prima_get_option('homepagelatestheight') ); ?>" size="6" />
    </p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[homepagelatestname]" id="<?php echo PRIMA_OPTIONS; ?>[homepagelatestname]" value="1" <?php checked(1, prima_get_option('homepagelatestname')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[homepagelatestname]"><?php _e("Show Product Name", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[homepagelatestsale]" id="<?php echo PRIMA_OPTIONS; ?>[homepagelatestsale]" value="1" <?php checked(1, prima_get_option('homepagelatestsale')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[homepagelatestsale]"><?php _e("Show Sale Icon", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[homepagelatestslide]" id="<?php echo PRIMA_OPTIONS; ?>[homepagelatestslide]" value="yes" <?php checked('yes', prima_get_option('homepagelatestslide')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[homepagelatestslide]"><?php _e("Slideable?", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[homepagelatestslideauto]" id="<?php echo PRIMA_OPTIONS; ?>[homepagelatestslideauto]" value="yes" <?php checked('yes', prima_get_option('homepagelatestslideauto')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[homepagelatestslideauto]"><?php _e("Auto Play Slider?", PRIMA_DOMAIN); ?></label>
	</p>
<?php if(!class_exists('WP_eCommerce')) echo '</div>'; ?>
<hr class="div" style="clear:both"/>
<h4><?php _e( 'Testimonials Section', PRIMA_DOMAIN ); ?></h4>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[homepagetestimonials]" id="<?php echo PRIMA_OPTIONS; ?>[homepagetestimonials]" value="1" <?php checked(1, prima_get_option('homepagetestimonials')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[homepagetestimonials]"><?php _e("Show Testimonials", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<?php _e("Number of testimonials:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[homepagetestinumber]" value="<?php echo esc_attr( prima_get_option('homepagetestinumber') ); ?>" size="6" />
    </p>
<?php
}
function prima_theme_settings_topbar_box() {
	if(!class_exists('WP_eCommerce')) {
		echo '<p>'.sprintf( __("The options is available when %s plugin is active.", PRIMA_DOMAIN), '<a href="http://wordpress.org/extend/plugins/wp-e-commerce">WP e-Commerce</a>').'</p>'; 
		echo '<div style="display: none;">'; 
	}
	?>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[usernav]" id="<?php echo PRIMA_OPTIONS; ?>[usernav]" value="yes" <?php checked('yes', prima_get_option('usernav')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[usernav]"><?php _e("User Navigation (Register/Signin Link)", PRIMA_DOMAIN); ?></label>
    </p>
    <hr class="div" style="clear:both"/>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[topcategories]" id="<?php echo PRIMA_OPTIONS; ?>[topcategories]" value="yes" <?php checked('yes', prima_get_option('topcategories')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[topcategories]"><?php _e("Top Product Categories", PRIMA_DOMAIN); ?></label>
    </p>
	<p><?php _e("Subcategory depth level:", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[topcatsdepth]">
		<option style="padding-right:10px;" value="no" <?php selected('no', prima_get_option('topcatsdepth')); ?>><?php _e("No", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="1" <?php selected('1', prima_get_option('topcatsdepth')); ?>>1</option>
		<option style="padding-right:10px;" value="2" <?php selected('2', prima_get_option('topcatsdepth')); ?>>2</option>
		<option style="padding-right:10px;" value="3" <?php selected('3', prima_get_option('topcatsdepth')); ?>>3</option>
		<option style="padding-right:10px;" value="" <?php selected('', prima_get_option('topcatsdepth')); ?>><?php _e("All", PRIMA_DOMAIN); ?></option>
	</select></p>
	<p><?php _e("Mode:", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[topcatsmode]">
		<option style="padding-right:10px;" value="vertical" <?php selected('vertical', prima_get_option('topcatsmode')); ?>><?php _e("Vertical", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="horizontal" <?php selected('horizontal', prima_get_option('topcatsmode')); ?>><?php _e("Horizontal", PRIMA_DOMAIN); ?></option>
	</select></p>
    <hr class="div" style="clear:both"/>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[topcart]" id="<?php echo PRIMA_OPTIONS; ?>[topcart]" value="yes" <?php checked('yes', prima_get_option('topcart')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[topcart]"><?php _e("Top Shopping Cart", PRIMA_DOMAIN); ?></label>
    </p>
	<?php
	if(!class_exists('WP_eCommerce')) echo '</div>'; 
}
function prima_theme_settings_productspage_box() { 
	if(!class_exists('WP_eCommerce')) {
		echo '<p>'.sprintf( __("The options is available when %s plugin is active.", PRIMA_DOMAIN), '<a href="http://wordpress.org/extend/plugins/wp-e-commerce">WP e-Commerce</a>').'</p>'; 
		echo '<div style="display: none;">'; 
	}
	?>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[productssidebar]" id="<?php echo PRIMA_OPTIONS; ?>[productssidebar]" value="yes" <?php checked('yes', prima_get_option('productssidebar')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[productssidebar]"><?php _e("Products Page Sidebar", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[productscatssidebar]" id="<?php echo PRIMA_OPTIONS; ?>[productscatssidebar]" value="disable" <?php checked('disable', prima_get_option('productscatssidebar')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[productscatssidebar]"><?php _e("Disable Product Categories at Product Sidebar", PRIMA_DOMAIN); ?></label>
	</p>
	<div class="notice">
	<p>
	<?php _e('These options below are used when you choose <strong>Default View</strong> for Product Display!', PRIMA_DOMAIN); ?>
	</p>
	</div>
	<p>
	<?php _e("Products Per Row:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[productsperrow]" value="<?php echo esc_attr( prima_get_option('productsperrow') ); ?>" size="5" /></p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[productsuncropthumb]" id="<?php echo PRIMA_OPTIONS; ?>[productsuncropthumb]" value="yes" <?php checked('yes', prima_get_option('productsuncropthumb')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[productsuncropthumb]"><strong><?php _e("Not Cropped Thumbnails", PRIMA_DOMAIN); ?></strong></label>
    </p>
	<p>
    <span class="description"><?php _e('Go to Settings - Store - Presentations to change <strong>Default Product Thumbnail Size</strong>', PRIMA_DOMAIN); ?></span>
    </p>
	<p><?php _e("Product Image link to:", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[productsimagelink]">
		<option style="padding-right:10px;" value="link" <?php selected('link', prima_get_option('productsimagelink')); ?>><?php _e("Product Link", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="image" <?php selected('image', prima_get_option('productsimagelink')); ?>><?php _e("Image Popup", PRIMA_DOMAIN); ?></option>
	</select>
	</p>
	<p><?php _e("Product Image Hover Effect", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[productsimagehover]">
		<option style="padding-right:10px;" value="none" <?php selected('none', prima_get_option('productsimagehover')); ?>><?php _e("None", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="readmore" <?php selected('readmore', prima_get_option('productsimagehover')); ?>><?php _e("Read More Button", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="buynow" <?php selected('buynow', prima_get_option('productsimagehover')); ?>><?php _e("Buy Now Button", PRIMA_DOMAIN); ?></option>
	</select>
	</p>
	<p><?php _e("Product Variation Price Type", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[productsvariationprice]">
		<option style="padding-right:10px;" value="min" <?php selected('min', prima_get_option('productsvariationprice')); ?>><?php _e("Minimum Price", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="max" <?php selected('max', prima_get_option('productsvariationprice')); ?>><?php _e("Maximum Price", PRIMA_DOMAIN); ?></option>
	</select>
	</p>
	<p>
	<?php _e("Product Variation Text:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[productsvariationtext]" value="<?php echo esc_attr( prima_get_option('productsvariationtext') ); ?>" size="27" />
	</p>
	<p>
    <span class="description"><?php _e("For example: from, start from, up to, etc", PRIMA_DOMAIN); ?></span>
    </p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[productshidedecimal]" id="<?php echo PRIMA_OPTIONS; ?>[productshidedecimal]" value="hide" <?php checked('hide', prima_get_option('productshidedecimal')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[productshidedecimal]"><?php _e("Hide Price Decimal", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[productshideprice]" id="<?php echo PRIMA_OPTIONS; ?>[productshideprice]" value="hide" <?php checked('hide', prima_get_option('productshideprice')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[productshideprice]"><?php _e("Hide Product Price", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[productshidesaleicon]" id="<?php echo PRIMA_OPTIONS; ?>[productshidesaleicon]" value="hide" <?php checked('hide', prima_get_option('productshidesaleicon')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[productshidesaleicon]"><?php _e("Hide Sale Icons", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[productshidetitle]" id="<?php echo PRIMA_OPTIONS; ?>[productshidetitle]" value="hide" <?php checked('hide', prima_get_option('productshidetitle')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[productshidetitle]"><?php _e("Hide Product Title", PRIMA_DOMAIN); ?></label>
	</p>
	<p><?php _e("Product Description", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[productsdesc]">
		<option style="padding-right:10px;" value="both" <?php selected('both', prima_get_option('productsdesc')); ?>><?php _e("Description + Additional Description", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="description" <?php selected('description', prima_get_option('productsdesc')); ?>><?php _e("Description", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="additional" <?php selected('additional', prima_get_option('productsdesc')); ?>><?php _e("Additional Description", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="" <?php selected('', prima_get_option('productsdesc')); ?>><?php _e("None", PRIMA_DOMAIN); ?></option>
	</select>
	</p>
	<p>
	<?php _e("Limit Product Description:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[productsdesclimit]" value="<?php echo esc_attr( prima_get_option('productsdesclimit') ); ?>" size="5" />
	<?php _e("characters", PRIMA_DOMAIN); ?>
	</p>
	<p>
	<?php _e("Limit Product Additional Description:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[productsadddesclimit]" value="<?php echo esc_attr( prima_get_option('productsadddesclimit') ); ?>" size="5" />
	<?php _e("characters", PRIMA_DOMAIN); ?>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[productsvariations]" id="<?php echo PRIMA_OPTIONS; ?>[productsvariations]" value="yes" <?php checked('yes', prima_get_option('productsvariations')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[productsvariations]"><?php _e("Show Product Variations", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[productsquantity]" id="<?php echo PRIMA_OPTIONS; ?>[productsquantity]" value="yes" <?php checked('yes', prima_get_option('productsquantity')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[productsquantity]"><?php _e("Show Product Quantity Field", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[productsbuynow]" id="<?php echo PRIMA_OPTIONS; ?>[productsbuynow]" value="yes" <?php checked('yes', prima_get_option('productsbuynow')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[productsbuynow]"><?php _e("Show Buy Now Button After Description", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[productspaypalbuynow]" id="<?php echo PRIMA_OPTIONS; ?>[productspaypalbuynow]" value="yes" <?php checked('yes', prima_get_option('productspaypalbuynow')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[productspaypalbuynow]"><?php _e("Show Paypal Buy Now Button After Description", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[productsstockavail]" id="<?php echo PRIMA_OPTIONS; ?>[productsstockavail]" value="yes" <?php checked('yes', prima_get_option('productsstockavail')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[productsstockavail]"><?php _e("Show Product Stock Availability", PRIMA_DOMAIN); ?></label>
	</p>
	<?php
	if(!class_exists('WP_eCommerce')) echo '</div>'; 
}
function prima_theme_settings_singleproductspage_box() { 
	if(!class_exists('WP_eCommerce')) {
		echo '<p>'.sprintf( __("The options is available when %s plugin is active.", PRIMA_DOMAIN), '<a href="http://wordpress.org/extend/plugins/wp-e-commerce">WP e-Commerce</a>').'</p>'; 
		echo '<div style="display: none;">'; 
	}
	?>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[singleproductsidebar]" id="<?php echo PRIMA_OPTIONS; ?>[singleproductsidebar]" value="yes" <?php checked('yes', prima_get_option('singleproductsidebar')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[singleproductsidebar]"><?php _e("Show Single Product Sidebar", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[singleproductcatssidebar]" id="<?php echo PRIMA_OPTIONS; ?>[singleproductcatssidebar]" value="disable" <?php checked('disable', prima_get_option('singleproductcatssidebar')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[singleproductcatssidebar]"><?php _e("Disable Product Categories at Single Product Sidebar", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[singleuncropthumb]" id="<?php echo PRIMA_OPTIONS; ?>[singleuncropthumb]" value="yes" <?php checked('yes', prima_get_option('singleuncropthumb')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[singleuncropthumb]"><strong><?php _e("Not Cropped Images", PRIMA_DOMAIN); ?></strong></label>
    </p>
	<p>
    <span class="description"><?php _e('Go to Settings - Store - Presentations to change <strong>Single Product Image Size</strong>', PRIMA_DOMAIN); ?></span>
    </p>
	<p><?php _e("Product Variation Price Type", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[singlevariationprice]">
		<option style="padding-right:10px;" value="min" <?php selected('min', prima_get_option('singlevariationprice')); ?>><?php _e("Minimum Price", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="max" <?php selected('max', prima_get_option('singlevariationprice')); ?>><?php _e("Maximum Price", PRIMA_DOMAIN); ?></option>
	</select>
	</p>
	<p>
	<?php _e("Product Variation Text:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[singlevariationtext]" value="<?php echo esc_attr( prima_get_option('singlevariationtext') ); ?>" size="27" />
	</p>
	<p>
    <span class="description"><?php _e("For example: from, start from, up to, etc", PRIMA_DOMAIN); ?></span>
    </p>
	<p>
	<input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[singlehidedecimal]" id="<?php echo PRIMA_OPTIONS; ?>[singlehidedecimal]" value="hide" <?php checked('hide', prima_get_option('singlehidedecimal')); ?> /> 
	<label for="<?php echo PRIMA_OPTIONS; ?>[singlehidedecimal]"><?php _e("Hide Price Decimal", PRIMA_DOMAIN); ?></label>
	</p>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[singlehideprice]" id="<?php echo PRIMA_OPTIONS; ?>[singlehideprice]" value="hide" <?php checked('hide', prima_get_option('singlehideprice')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[singlehideprice]"><?php _e("Hide Product Price", PRIMA_DOMAIN); ?></label>
    </p>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[singlehidesaleicon]" id="<?php echo PRIMA_OPTIONS; ?>[singlehidesaleicon]" value="hide" <?php checked('hide', prima_get_option('singlehidesaleicon')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[singlehidesaleicon]"><?php _e("Hide Sale Icon", PRIMA_DOMAIN); ?></label>
    </p>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[singlesku]" id="<?php echo PRIMA_OPTIONS; ?>[singlesku]" value="yes" <?php checked('yes', prima_get_option('singlesku')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[singlesku]"><?php _e("Show Product SKU", PRIMA_DOMAIN); ?></label>
    </p>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[singleproductcategory]" id="<?php echo PRIMA_OPTIONS; ?>[singleproductcategory]" value="yes" <?php checked('yes', prima_get_option('singleproductcategory')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[singleproductcategory]"><?php _e("Show Product Category Lists", PRIMA_DOMAIN); ?></label>
    </p>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[singleproducttag]" id="<?php echo PRIMA_OPTIONS; ?>[singleproducttag]" value="yes" <?php checked('yes', prima_get_option('singleproducttag')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[singleproducttag]"><?php _e("Show Product Tag Lists", PRIMA_DOMAIN); ?></label>
    </p>
	<p><?php _e("Product Comment Settings", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[singleproductcomment]">
		<option style="padding-right:10px;" value="" <?php selected('', prima_get_option('singleproductcomment')); ?>><?php _e("Default WPEC Setting - IntenseDebate", PRIMA_DOMAIN); ?></option>
		<option style="padding-right:10px;" value="wordpress" <?php selected('wordpress', prima_get_option('singleproductcomment')); ?>><?php _e("Wordpress Comments", PRIMA_DOMAIN); ?></option>
	</select>
    </p>
    <hr class="div" style="clear:both"/>
    <h4><?php _e( 'Cross Sales - "Users who bought this also bought" item', PRIMA_DOMAIN ); ?></h4>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[singlecrosssales]" id="<?php echo PRIMA_OPTIONS; ?>[singlecrosssales]" value="1" <?php checked(1, prima_get_option('singlecrosssales')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[singlecrosssales]"><?php _e("Display Cross Sales", PRIMA_DOMAIN); ?></label>
    </p>
	<p>
	<?php _e("Number of products:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[singlecrosssalesnumber]" value="<?php echo esc_attr( prima_get_option('singlecrosssalesnumber') ); ?>" size="6" />
    </p>
	<p>
	<?php _e("Image Width:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[singlecrosssaleswidth]" value="<?php echo esc_attr( prima_get_option('singlecrosssaleswidth') ); ?>" size="6" />
    </p>
	<p>
	<?php _e("Image Height:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[singlecrosssalesheight]" value="<?php echo esc_attr( prima_get_option('singlecrosssalesheight') ); ?>" size="6" />
    </p>
    <hr class="div" style="clear:both"/>
    <h4><?php _e( 'Related Products', PRIMA_DOMAIN ); ?></h4>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[singlerelated]" id="<?php echo PRIMA_OPTIONS; ?>[singlerelated]" value="1" <?php checked(1, prima_get_option('singlerelated')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[singlerelated]"><?php _e("Display Related Products", PRIMA_DOMAIN); ?></label>
    </p>
	<p>
	<?php _e("Number of products:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[singlerelatednumber]" value="<?php echo esc_attr( prima_get_option('singlerelatednumber') ); ?>" size="6" />
    </p>
	<p>
	<?php _e("Image Width:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[singlerelatedwidth]" value="<?php echo esc_attr( prima_get_option('singlerelatedwidth') ); ?>" size="6" />
    </p>
	<p>
	<?php _e("Image Height:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[singlerelatedheight]" value="<?php echo esc_attr( prima_get_option('singlerelatedheight') ); ?>" size="6" />
    </p>
	<?php
	if(!class_exists('WP_eCommerce')) echo '</div>'; 
}
function prima_theme_settings_footer_box() { ?>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[footertop]" id="<?php echo PRIMA_OPTIONS; ?>[footertop]" value="1" <?php checked(1, prima_get_option('footertop')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[footertop]"><?php _e("Enable Footer Top Section", PRIMA_DOMAIN); ?></label>
    </p>
	<p><?php _e("Number of latest news:", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[footernews]">
		<option style="padding-right:10px;" value="1" <?php selected('1', prima_get_option('footernews')); ?>>1</option>
		<option style="padding-right:10px;" value="2" <?php selected('2', prima_get_option('footernews')); ?>>2</option>
		<option style="padding-right:10px;" value="3" <?php selected('3', prima_get_option('footernews')); ?>>3</option>
		<option style="padding-right:10px;" value="4" <?php selected('4', prima_get_option('footernews')); ?>>4</option>
		<option style="padding-right:10px;" value="5" <?php selected('5', prima_get_option('footernews')); ?>>5</option>
		<option style="padding-right:10px;" value="6" <?php selected('6', prima_get_option('footernews')); ?>>6</option>
		<option style="padding-right:10px;" value="7" <?php selected('7', prima_get_option('footernews')); ?>>7</option>
		<option style="padding-right:10px;" value="8" <?php selected('8', prima_get_option('footernews')); ?>>8</option>
	</select></p>
	<p>
	<?php _e("Twitter Username:", PRIMA_DOMAIN); ?>
	<input type="text" name="<?php echo PRIMA_OPTIONS; ?>[twitter]" value="<?php echo esc_attr( prima_get_option('twitter') ); ?>" size="27" />
    </p>
	<p><?php _e("Number of tweets:", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[footertweets]">
		<option style="padding-right:10px;" value="1" <?php selected('1', prima_get_option('footertweets')); ?>>1</option>
		<option style="padding-right:10px;" value="2" <?php selected('2', prima_get_option('footertweets')); ?>>2</option>
		<option style="padding-right:10px;" value="3" <?php selected('3', prima_get_option('footertweets')); ?>>3</option>
		<option style="padding-right:10px;" value="4" <?php selected('4', prima_get_option('footertweets')); ?>>4</option>
		<option style="padding-right:10px;" value="5" <?php selected('5', prima_get_option('footertweets')); ?>>5</option>
		<option style="padding-right:10px;" value="6" <?php selected('6', prima_get_option('footertweets')); ?>>6</option>
		<option style="padding-right:10px;" value="7" <?php selected('7', prima_get_option('footertweets')); ?>>7</option>
		<option style="padding-right:10px;" value="8" <?php selected('8', prima_get_option('footertweets')); ?>>8</option>
	</select></p>
    <hr class="div" style="clear:both"/>
	<p>
    <input type="checkbox" name="<?php echo PRIMA_OPTIONS; ?>[footerbottom]" id="<?php echo PRIMA_OPTIONS; ?>[footerbottom]" value="1" <?php checked(1, prima_get_option('footerbottom')); ?> /> 
    <label for="<?php echo PRIMA_OPTIONS; ?>[footerbottom]"><?php _e("Enable Footer Bottom Section", PRIMA_DOMAIN); ?></label>
    </p>
<?php
}
function prima_theme_settings_scripts_box() { ?>
	<p><?php _e("Enter scripts/code you would like output to <code>wp_head()</code>:", PRIMA_DOMAIN); ?><br />
	<textarea name="<?php echo PRIMA_OPTIONS; ?>[header_scripts]" cols="39" rows="5"><?php prima_option('header_scripts'); ?></textarea><br />
	<span class="description"><?php _e('<b>NOTE:</b> The <code>wp_head()</code> hook executes immediately before the closing <code>&lt;/head&gt;</code> tag in the document source', PRIMA_DOMAIN); ?></span></p>
	<p><?php _e("Enter scripts/code you would like output to <code>wp_footer()</code>:", PRIMA_DOMAIN); ?><br />
	<textarea name="<?php echo PRIMA_OPTIONS; ?>[footer_scripts]" cols="39" rows="5"><?php prima_option('footer_scripts'); ?></textarea><br />
	<span class="description"><?php _e('<b>NOTE:</b> The <code>wp_footer()</code> hook executes immediately before the closing <code>&lt;/body&gt;</code> tag in the document source', PRIMA_DOMAIN); ?></span></p>
<?php
}
function prima_theme_settings_branding_box() {  ?>
	<p><?php _e("Logo URL", PRIMA_DOMAIN); ?>
    <br/><span class="description"><?php _e("png, jpg or gif file", PRIMA_DOMAIN); ?></span></p>
	<?php echo prima_add_upload_setting('newsitelogo', __("Upload Your Logo: png, jpg or gif file", PRIMA_DOMAIN)); ?>
    <hr class="div" style="clear:both"/>
	<p><?php _e("Login Logo URL", PRIMA_DOMAIN); ?>
    <br/><span class="description"><?php _e("310x70px png, jpg or gif file", PRIMA_DOMAIN); ?></span></p>
	<?php echo prima_add_upload_setting('loginlogo', __("Upload Your Login Logo: 310x70px png, jpg or gif file", PRIMA_DOMAIN)); ?>
    <hr class="div" style="clear:both"/>
	<?php 
	global $wp_version; 
	if ( version_compare( $wp_version, '3.1.4', '>' ) ) :
	?>
		<p><?php _e("Admin Bar Logo URL", PRIMA_DOMAIN); ?>
		<br/><span class="description"><?php _e("16x16px png, jpg or gif file", PRIMA_DOMAIN); ?></span></p>
		<?php echo prima_add_upload_setting('adminlogo', __("Upload Your Admin Bar Logo: 16x16px png, jpg or gif file", PRIMA_DOMAIN)); ?>
		<hr class="div" style="clear:both"/>
	<?php else : ?>
		<p><?php _e("Admin Bar Logo URL", PRIMA_DOMAIN); ?>
		<br/><span class="description"><?php _e("32x23px png, jpg or gif file", PRIMA_DOMAIN); ?></span></p>
		<?php echo prima_add_upload_setting('adminlogo', __("Upload Your Admin Bar Logo: 32x32px png, jpg or gif file", PRIMA_DOMAIN)); ?>
		<hr class="div" style="clear:both"/>
	<?php endif; ?>	
	<p><?php _e("Favicon URL", PRIMA_DOMAIN); ?>
    <br/><span class="description"><?php _e("16x16px ico, png, jpg, or gif file", PRIMA_DOMAIN); ?></span></p>
	<?php echo prima_add_upload_setting('favicon', __("Upload Your Favicon: 16x16px ico, png, jpg, or gif file", PRIMA_DOMAIN)); ?>
    <br class="div" style="clear:both"/>
    <?php
}
function prima_theme_settings_design_box() { ?>
    <h4><?php _e( 'Body Section', PRIMA_DOMAIN ); ?></h4>
	<p><?php _e("Body Heading Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('headingcol'); ?></p>
	<p><?php _e("Body Paragraph Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('paracolor'); ?></p>
	<p><?php _e("Default Link Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('linkcol'); ?></p>
	<p><?php _e("Background Settings:", PRIMA_DOMAIN); ?>
	<label><input type="radio" name="<?php echo PRIMA_OPTIONS; ?>[bodysetting]" value="" <?php checked('', prima_get_option('bodysetting')); ?> />
	<?php _e("Default", PRIMA_DOMAIN); ?></label>
	<label><input type="radio" name="<?php echo PRIMA_OPTIONS; ?>[bodysetting]" value="image" <?php checked('image', prima_get_option('bodysetting')); ?> />
	<?php _e("Use Image", PRIMA_DOMAIN); ?></label>
	<label><input type="radio" name="<?php echo PRIMA_OPTIONS; ?>[bodysetting]" value="color" <?php checked('color', prima_get_option('bodysetting')); ?> />
	<?php _e("Use Color", PRIMA_DOMAIN); ?></label>
	</p>
	<p><?php _e("Background Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('backgroundcol'); ?></p>
	<p><?php _e("Background Image URL", PRIMA_DOMAIN); ?></p>
	<?php echo prima_add_upload_setting('bg_image', __("Upload Your Background Image", PRIMA_DOMAIN)); ?>
    <br style="clear:both"/>
	<p><?php _e("Background Repeat:", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[bg_repeat]">
		<option style="padding-right:10px;" value="repeat" <?php selected('repeat', prima_get_option('bg_repeat')); ?>>repeat</option>
		<option style="padding-right:10px;" value="no-repeat" <?php selected('no-repeat', prima_get_option('bg_repeat')); ?>>no-repeat</option>
		<option style="padding-right:10px;" value="repeat-x" <?php selected('repeat-x', prima_get_option('bg_repeat')); ?>>repeat-x</option>
		<option style="padding-right:10px;" value="repeat-y" <?php selected('repeat-y', prima_get_option('bg_repeat')); ?>>repeat-y</option>
	</select>
    <br/><span class="description"><?php _e("Sets if/how a background image will be repeated", PRIMA_DOMAIN); ?></span></p>
	<p><?php _e("Background Position:", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[bg_position]">
		<option style="padding-right:10px;" value="top left" <?php selected('top left', prima_get_option('bg_position')); ?>>top left</option>
		<option style="padding-right:10px;" value="top center" <?php selected('top center', prima_get_option('bg_position')); ?>>top center</option>
		<option style="padding-right:10px;" value="top right" <?php selected('top right', prima_get_option('bg_position')); ?>>top right</option>
		<option style="padding-right:10px;" value="center left" <?php selected('center left', prima_get_option('bg_position')); ?>>center left</option>
		<option style="padding-right:10px;" value="center center" <?php selected('center center', prima_get_option('bg_position')); ?>>center center</option>
		<option style="padding-right:10px;" value="center right" <?php selected('center right', prima_get_option('bg_position')); ?>>center right</option>
		<option style="padding-right:10px;" value="bottom left" <?php selected('bottom left', prima_get_option('bg_position')); ?>>bottom left</option>
		<option style="padding-right:10px;" value="bottom center" <?php selected('bottom center', prima_get_option('bg_position')); ?>>bottom center</option>
		<option style="padding-right:10px;" value="bottom right" <?php selected('bottom right', prima_get_option('bg_position')); ?>>bottom right</option>
	</select>
    <br/><span class="description"><?php _e("Sets the starting position of a background image", PRIMA_DOMAIN); ?></span></p>
	<p><?php _e("Background Attachment:", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[bg_attachment]">
		<option style="padding-right:10px;" value="scroll" <?php selected('scroll', prima_get_option('bg_attachment')); ?>>scroll</option>
		<option style="padding-right:10px;" value="fixed" <?php selected('fixed', prima_get_option('bg_attachment')); ?>>fixed</option>
	</select>
    <br/><span class="description"><?php _e("Sets whether a background image is fixed or scrolls with the rest of the page", PRIMA_DOMAIN); ?></span></p>
	
    <!-- boxed layout specific -->
    <div <?php if(prima_get_option('themelayout')!='boxed') echo 'style="display:none;"'; ?>>
    <hr class="div" style="clear:both"/>
	<p><?php _e("Boxed Background Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('boxedcolor'); ?></p>
    </div>
    
    <hr class="div" style="clear:both"/>
    <h4><?php _e( 'Header Section', PRIMA_DOMAIN ); ?></h4>
    <!-- full layout specific -->
    <div <?php if(prima_get_option('themelayout')!='full') echo 'style="display:none;"'; ?>>
	<p><?php _e("Header Background Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('headercol'); ?></p>
    </div>
	<p><?php _e("Header Link Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('headerlinkcol'); ?></p>

    <div <?php if(prima_get_option('themelayout')!='full') echo 'style="display:none;"'; ?>>
    <hr class="div" style="clear:both"/>
    <h4><?php _e( 'Slider Background Section', PRIMA_DOMAIN ); ?></h4>
	<p><?php _e("Slider Background Settings:", PRIMA_DOMAIN); ?>
	<label><input type="radio" name="<?php echo PRIMA_OPTIONS; ?>[sliderbackground]" value="" <?php checked('', prima_get_option('sliderbackground')); ?> />
	<?php _e("Default", PRIMA_DOMAIN); ?></label>
	<label><input type="radio" name="<?php echo PRIMA_OPTIONS; ?>[sliderbackground]" value="image" <?php checked('image', prima_get_option('sliderbackground')); ?> />
	<?php _e("Use Image", PRIMA_DOMAIN); ?></label>
	<label><input type="radio" name="<?php echo PRIMA_OPTIONS; ?>[sliderbackground]" value="color" <?php checked('color', prima_get_option('sliderbackground')); ?> />
	<?php _e("Use Color", PRIMA_DOMAIN); ?></label>
	</p>
	<p><?php _e("Slider Background Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('sliderbackcol'); ?></p>
	<p><?php _e("Slider Background Image URL", PRIMA_DOMAIN); ?></p>
	<?php echo prima_add_upload_setting('newleaderimage', __("Upload Your Slider Background Image", PRIMA_DOMAIN)); ?>
    <br style="clear:both"/>
	<p><?php _e("Slider Background Repeat:", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[sliderbackrepeat]">
		<option style="padding-right:10px;" value="repeat" <?php selected('repeat', prima_get_option('sliderbackrepeat')); ?>>repeat</option>
		<option style="padding-right:10px;" value="no-repeat" <?php selected('no-repeat', prima_get_option('sliderbackrepeat')); ?>>no-repeat</option>
		<option style="padding-right:10px;" value="repeat-x" <?php selected('repeat-x', prima_get_option('sliderbackrepeat')); ?>>repeat-x</option>
		<option style="padding-right:10px;" value="repeat-y" <?php selected('repeat-y', prima_get_option('sliderbackrepeat')); ?>>repeat-y</option>
	</select>
    <br/><span class="description"><?php _e("Sets if/how a background image will be repeated", PRIMA_DOMAIN); ?></span></p>
	<p><?php _e("Slider Background Position:", PRIMA_DOMAIN); ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[sliderbackpos]">
		<option style="padding-right:10px;" value="top left" <?php selected('top left', prima_get_option('sliderbackpos')); ?>>top left</option>
		<option style="padding-right:10px;" value="top center" <?php selected('top center', prima_get_option('sliderbackpos')); ?>>top center</option>
		<option style="padding-right:10px;" value="top right" <?php selected('top right', prima_get_option('sliderbackpos')); ?>>top right</option>
		<option style="padding-right:10px;" value="center left" <?php selected('center left', prima_get_option('sliderbackpos')); ?>>center left</option>
		<option style="padding-right:10px;" value="center center" <?php selected('center center', prima_get_option('sliderbackpos')); ?>>center center</option>
		<option style="padding-right:10px;" value="center right" <?php selected('center right', prima_get_option('sliderbackpos')); ?>>center right</option>
		<option style="padding-right:10px;" value="bottom left" <?php selected('bottom left', prima_get_option('sliderbackpos')); ?>>bottom left</option>
		<option style="padding-right:10px;" value="bottom center" <?php selected('bottom center', prima_get_option('sliderbackpos')); ?>>bottom center</option>
		<option style="padding-right:10px;" value="bottom right" <?php selected('bottom right', prima_get_option('sliderbackpos')); ?>>bottom right</option>
	</select>
    <br/><span class="description"><?php _e("Sets the starting position of a background image", PRIMA_DOMAIN); ?></span></p>
    </div>

    <hr class="div" style="clear:both"/>
    <h4><?php _e( 'Footer Section', PRIMA_DOMAIN ); ?></h4>
    <!-- full layout specific -->
    <div <?php if(prima_get_option('themelayout')!='full') echo 'style="display:none;"'; ?>>
	<p><?php _e("Footer Background Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('footercol'); ?></p>
    </div>
    <!-- boxed/simple layout specific -->
    <div <?php if(prima_get_option('themelayout')=='full') echo 'style="display:none;"'; ?>>
	<p><?php _e("Footer Top - Widget Title Background Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('footerheaderbgcol'); ?></p>
    </div>
	<p><?php _e("Footer Top - Widget Title Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('footerheadercol'); ?></p>
	<p><?php _e("Footer Top - Font Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('footerfontcol'); ?></p>
	<p><?php _e("Footer Top - Link Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('footerlinkcol'); ?></p>
	<p><?php _e("Footer Bottom - Widget Title Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('footerbottomheadercol'); ?></p>
	<p><?php _e("Footer Bottom - Font Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('footerbottomfontcol'); ?></p>
	<p><?php _e("Footer Bottom - Link Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('footerbottomlinkcol'); ?></p>

    <hr class="div" style="clear:both"/>
    <h4><?php _e( 'Copyright Section', PRIMA_DOMAIN ); ?></h4>
	<p><?php _e("Copyright Font Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('copyrightfontcol'); ?></p>
    <!-- boxed/full layout specific -->
    <div <?php if(prima_get_option('themelayout')=='simple') echo 'style="display:none;"'; ?>>
	<p><?php _e("Copyright Background Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('copyrightcol'); ?></p>
    </div>

    <?php
}
function prima_theme_settings_optionaldesign_box() { ?>
    <h4><?php _e( 'Top Navigation Bar Section', PRIMA_DOMAIN ); ?></h4>
	<p><?php _e("Top Background Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('topbarbgcol'); ?></p>
	<p><?php _e("Menu Background Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('topbarmenubgcol'); ?></p>
	<p><?php _e("Menu Text Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('topbarmenucol'); ?></p>
	<p><?php _e("Top Content Background Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('topbarcontentbgcol'); ?></p>
	<p><?php _e("Top Content Title Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('topbarcontenttitlecol'); ?></p>
	<p><?php _e("Top Content Text Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('topbarcontentcol'); ?></p>
	<p><?php _e("Top Content Border Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('topbarbordercol'); ?></p>
	<p><?php _e("Top Cart Count and Checkout Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('topbarcountcol'); ?></p>
    
    <hr class="div" style="clear:both"/>
    <h4><?php _e( 'Navigation Menu Section', PRIMA_DOMAIN ); ?></h4>
	<p><?php _e("Menu Text Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('headermenucol'); ?></p>
	<p><?php _e("Menu Background Color (Hover):", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('headermenubghovercol'); ?></p>
	<p><?php _e("Menu Text Color (Hover):", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('headermenuhovercol'); ?></p>
	<p><?php _e("Sub Menu Background Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('headersubmenubgcol'); ?></p>
	<p><?php _e("Sub Menu Text Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('headersubmenucol'); ?></p>
	<p><?php _e("Sub Menu Border Color:", PRIMA_DOMAIN); ?>
	<?php echo prima_add_color_setting('headersubmenubordercol'); ?></p>

    <hr class="div" style="clear:both"/>

	<h4><?php _e( 'Frontpage Categories Section', PRIMA_DOMAIN ); ?></h4>
	<?php
	if(!class_exists('WP_eCommerce')) {
		echo '<p>'.sprintf( __("The options is available when %s plugin is active.", PRIMA_DOMAIN), '<a href="http://wordpress.org/extend/plugins/wp-e-commerce">WP e-Commerce</a>').'</p>'; 
		echo '<div style="display: none;">'; 
	}
	?>
		<p><?php _e("Title Background Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('frontcatsbgcol'); ?></p>
		<p><?php _e("Title Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('frontcatscol'); ?></p>
		<p><?php _e("Category Name Background Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('frontcatnamebgcol'); ?></p>
		<p><?php _e("Category Name Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('frontcatnamecol'); ?></p>
	<?php if(!class_exists('WP_eCommerce')) echo '</div>'; ?>
	<hr class="div" style="clear:both"/>

	<h4><?php _e( 'Frontpage Best Sellers Section', PRIMA_DOMAIN ); ?></h4>
	<?php
	if(!class_exists('WP_eCommerce')) {
		echo '<p>'.sprintf( __("The options is available when %s plugin is active.", PRIMA_DOMAIN), '<a href="http://wordpress.org/extend/plugins/wp-e-commerce">WP e-Commerce</a>').'</p>'; 
		echo '<div style="display: none;">'; 
	}
	?>
		<p><?php _e("Title Background Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('frontbestsbgcol'); ?></p>
		<p><?php _e("Title Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('frontbestscol'); ?></p>
		<p><?php _e("Product Title Background Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('frontbestprodbgcol'); ?></p>
		<p><?php _e("Product Title Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('frontbestprodcol'); ?></p>
	<?php if(!class_exists('WP_eCommerce')) echo '</div>'; ?>
	<hr class="div" style="clear:both"/>

	<h4><?php _e( 'Frontpage Latest Products Section', PRIMA_DOMAIN ); ?></h4>
	<?php
	if(!class_exists('WP_eCommerce')) {
		echo '<p>'.sprintf( __("The options is available when %s plugin is active.", PRIMA_DOMAIN), '<a href="http://wordpress.org/extend/plugins/wp-e-commerce">WP e-Commerce</a>').'</p>'; 
		echo '<div style="display: none;">'; 
	}
	?>
		<p><?php _e("Title Background Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('frontlatestsbgcol'); ?></p>
		<p><?php _e("Title Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('frontlatestscol'); ?></p>
		<p><?php _e("Product Title Background Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('frontlatestprodbgcol'); ?></p>
		<p><?php _e("Product Title Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('frontlatestprodcol'); ?></p>
	<?php if(!class_exists('WP_eCommerce')) echo '</div>'; ?>
	<hr class="div" style="clear:both"/>

    <h4><?php _e( 'Products Page Section', PRIMA_DOMAIN ); ?></h4>
	<?php
	if(!class_exists('WP_eCommerce')) {
		echo '<p>'.sprintf( __("The options is available when %s plugin is active.", PRIMA_DOMAIN), '<a href="http://wordpress.org/extend/plugins/wp-e-commerce">WP e-Commerce</a>').'</p>'; 
		echo '<div style="display: none;">'; 
	}
	?>
		<p><?php _e("Price Background Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('pricedisplaybgcol'); ?></p>
		<p><?php _e("Price Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('pricedisplaycol'); ?></p>
		<p><?php _e("Sale Price Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('salepricedisplaycol'); ?></p>
	<?php if(!class_exists('WP_eCommerce')) echo '</div>'; ?>
    <hr class="div" style="clear:both"/>

    <h4><?php _e( 'Checkout Page Section', PRIMA_DOMAIN ); ?></h4>
	<?php
	if(!class_exists('WP_eCommerce')) {
		echo '<p>'.sprintf( __("The options is available when %s plugin is active.", PRIMA_DOMAIN), '<a href="http://wordpress.org/extend/plugins/wp-e-commerce">WP e-Commerce</a>').'</p>'; 
		echo '<div style="display: none;">'; 
	}
	?>
		<p><?php _e("Checkout Title Background Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('checkouttitlebgcol'); ?></p>
		<p><?php _e("Checkout Title Text Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('checkouttitlecol'); ?></p>
		<p><?php _e("Checkout Text Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('checkoutcol'); ?></p>
		<p><?php _e("Checkout Table Row Background Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('checkoutrowbgcol'); ?></p>
		<p><?php _e("Checkout Table Alternate Row Background Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('checkoutaltrowbgcol'); ?></p>
		<p><?php _e("Checkout Table Border Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('checkoutbordercol'); ?></p>
	<?php if(!class_exists('WP_eCommerce')) echo '</div>'; ?>
	<hr class="div" style="clear:both"/>
		
    <h4><?php _e( 'Fancy Notification Section', PRIMA_DOMAIN ); ?></h4>
	<?php
	if(!class_exists('WP_eCommerce')) {
		echo '<p>'.sprintf( __("The options is available when %s plugin is active.", PRIMA_DOMAIN), '<a href="http://wordpress.org/extend/plugins/wp-e-commerce">WP e-Commerce</a>').'</p>'; 
		echo '<div style="display: none;">'; 
	}
	?>
		<p><?php _e("Notification Background Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('fancybgcol'); ?></p>
		<p><?php _e("Notification  Text Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('fancycol'); ?></p>
		<p><?php _e("Checkout Link Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('fancycheckoutcol'); ?></p>
		<p><?php _e("Continue Link Color:", PRIMA_DOMAIN); ?>
		<?php echo prima_add_color_setting('fancycontinuecol'); ?></p>
	<?php if(!class_exists('WP_eCommerce')) echo '</div>'; ?>

    <?php
}
function prima_theme_settings_fonts_dropdown( $key  ) { ?>
	<select name="<?php echo PRIMA_OPTIONS; ?>[<?php echo $key ?>]">
        <option style="padding-right: 10px;" value=''><?php _e("Default Font", "flexishop") ?></option>
        <optgroup label="--- Standard Fonts ---">
        <?php $selected = prima_get_option($key); ?>
        <?php
            foreach (prima_webfonts() as $option ) {
                $label = $option['label'];
                if ( $selected == $option['value'] )
                    echo "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
                else
                    echo "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";
            }
        ?>
        </optgroup>
		<?php $customfonts = prima_customfonts();
		if (!empty($customfonts)) : ?>
        <optgroup label="--- Custom Fonts ---">
        <?php
            foreach (prima_customfonts() as $option ) {
                $label = $option['label'];
                if ( $selected == $option['value'] )
                    echo "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
                else
                    echo "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";
            }
        ?>
        </optgroup>
		<?php endif; ?>
        <optgroup label="--- Google Fonts ---">
        <?php
            foreach (prima_googlefonts() as $option ) {
                $label = $option['label'];
                if ( $selected == $option['value'] )
                    echo "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
                else
                    echo "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";
            }
        ?>
        </optgroup>
	</select>
    <?php
}
function prima_theme_settings_fonts_box() { ?>
	<p><?php _e("Heading Font:", PRIMA_DOMAIN); ?>
	<?php prima_theme_settings_fonts_dropdown( 'headerfont' ); ?>
	</p>
    <hr class="div" style="clear:both"/>
	<p><?php _e("Body Font:", PRIMA_DOMAIN); ?>
	<?php prima_theme_settings_fonts_dropdown( 'bodyfont' ); ?>
	</p>
    <hr class="div" style="clear:both"/>
	<p><?php _e("Additional Font:", PRIMA_DOMAIN); ?>
	<?php prima_theme_settings_fonts_dropdown( 'additionalfont' ); ?><br/>
    <span class="description"><?php _e("Note: Additional font is applied to blockquote, testimonials, product category description, post excerpt in Latest News widget and Frontpage Slider, Twitter widget.", PRIMA_DOMAIN); ?></span>
	</p>
    <?php
}
function prima_theme_settings_customcss_box() { ?>
	<p><?php _e("Quickly add some CSS to your theme:", PRIMA_DOMAIN); ?><br />
	<textarea name="<?php echo PRIMA_OPTIONS; ?>[customcss]" cols="39" rows="8"><?php prima_option('customcss'); ?></textarea>
    </p>
<?php
}


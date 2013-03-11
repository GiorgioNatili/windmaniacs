<?php
/**
 * Better Backgrounds
 * A Wordpress Plugin
 * 
 * An enhanced version of the standard Wordpress custom background script.
 * 
 * @package Wordpress
 * @subpackage better-backgrounds
 * @version 3.0.7
 * 
 * 
 * Copyright 2011 by Dave Coleman (email: dave@davetcoleman.com)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 */

load_plugin_textdomain( 'bbg', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	
/**
 * The better background class.
 *
 * @package WordPress
 * @subpackage better_backgrounds
 */
class Better_Backgrounds {

	/**
	 * Holds the page menu hook.
	 *
	 * @var string
	 * @access private
	 */
	var $page = '';

	/**
	 * PHP4 Constructor - Register administration header callback.
	 *
	 * @return Better_Background
	 */
	function Better_Backgrounds() 
	{
		
		
		//Remove any saved background images from the standard Wordpress Custom Background functionality
		//This is only run on the admin screen so its ok we do it everytime
		remove_theme_mod('background_image');
		remove_theme_mod('background_image_thumb');
		
	}

	/**
	 * Set up the hooks for the Better Backgrounds admin page.
	 *
	 */
	function init() 
	{
		//Check if user has access to change this plugin's settings
		if ( ! current_user_can('edit_theme_options') )
			return;
			
		//Add admin page to Wordpress Admin
		$this->page = $page = add_theme_page(__('Better Backgrounds'), __('Better Backgrounds'), 'edit_theme_options', 'better-backgrounds', array(&$this, 'admin_page'));
	
		//Setup admin save feature
		add_action("load-$page", array(&$this, 'admin_load'), 49);
		
	}

	/**
	 * Check if the plugin settings are saved in the DB in the old format
	 * This function has been disabled because it has been over 4 months since the related upgrade was performed
	 * Re-enable and run once to perform the upgrade if you are upgrading the plugin late
	 * 
	 
	function check_upgrade(){
		
		//Check if old option bbg_path still exists in their wp_options table
		$path = get_option('bbg_path','DOES_NOT_EXIST');
		if($path != 'DOES_NOT_EXIST')	//The entry does exist in table
		{
			set_theme_mod('background_path', $path);
			delete_option('bbg_path');
		}
		else
		{
			return;	//if the path doesn't exist same processor time and don't check the others
		}
		
		$value = get_option('bbg_layout','DOES_NOT_EXIST');
		if($value != 'DOES_NOT_EXIST')	//The entry does exist in table
		{
			if($value == 'fixed'){
				$value = 'better';
				set_theme_mod('background_attachment', 'fixed');
			}elseif($value == 'scrolling'){
				$value = 'better';
				set_theme_mod('background_attachment', 'scroll');
			}else{ //if($value == 'basic')
				$value = 'classic';
				set_theme_mod('background_attachment', 'scroll');
			}
			
			set_theme_mod('background_layout', $value);
			delete_option('bbg_layout');
		}
		
		$value = get_option('bbg_interval','DOES_NOT_EXIST');
		if($value != 'DOES_NOT_EXIST')	//The entry does exist in table
		{
			set_theme_mod('background_interval', $value);
			delete_option('bbg_interval');
		}
		
		$value = get_option('bbg_interval_timer','DOES_NOT_EXIST');
		if($value != 'DOES_NOT_EXIST')	//The entry does exist in table
		{
			set_theme_mod('background_interval_timer', $value);
			delete_option('bbg_interval_timer');
		}
		
		$value = get_option('bbg_fade','DOES_NOT_EXIST');
		if($value != 'DOES_NOT_EXIST')	//The entry does exist in table
		{
			set_theme_mod('background_fade', $value);
			delete_option('bbg_fade');
		}
	
		$value = get_option('bbg_height','DOES_NOT_EXIST');
		if($value != 'DOES_NOT_EXIST')	//The entry does exist in table
		{
			set_theme_mod('background_height', $value);
			delete_option('bbg_height');
		}
	
	}*/
	
	/**
	 * Execute Better Backgrounds modification.
	 *
	 */
	function admin_load() 
	{
		
		//Enqueue scripts when Wordpress is ready for it, fix for Wordpress 3.3
		add_action('admin_enqueue_scripts', array( &$this, 'admin_enqueue' ) );

		//Check for POST submission
		if ( empty($_POST) )
			return;

		if ( isset($_POST['background-path']) ) {
			check_admin_referer('custom-background');
			//TODO: check that is valid path?
			$path = $_POST['background-path'];
			
			set_theme_mod('background_path', $path);
		}
		
		if ( isset($_POST['background-layout']) ) {
			check_admin_referer('custom-background');
			if ( in_array($_POST['background-layout'], array('better','classic','developer')) )
				$layout = $_POST['background-layout'];
			else
				$layout = 'better';
			
			set_theme_mod('background_layout', $layout);
		}
		
		if ( isset($_POST['background-repeat']) ) {
			check_admin_referer('custom-background');
			if ( in_array($_POST['background-repeat'], array('repeat', 'no-repeat', 'repeat-x', 'repeat-y')) )
				$repeat = $_POST['background-repeat'];
			else
				$repeat = 'no-repeat';
			set_theme_mod('background_repeat', $repeat);
		}

		if ( isset($_POST['background-position-x']) ) {
			check_admin_referer('custom-background');
			if ( in_array($_POST['background-position-x'], array('center', 'right', 'left')) )
				$position = $_POST['background-position-x'];
			else
				$position = 'left';
			set_theme_mod('background_position_x', $position);
		}

		if ( isset($_POST['background-attachment']) ) {
			check_admin_referer('custom-background');
			if ( in_array($_POST['background-attachment'], array('fixed', 'scroll')) )
				$attachment = $_POST['background-attachment'];
			else
				$attachment = 'fixed';
			set_theme_mod('background_attachment', $attachment);
		}

		if ( isset($_POST['background-color']) ) {
			check_admin_referer('custom-background');
			$color = preg_replace('/[^0-9a-fA-F]/', '', $_POST['background-color']);
			if ( strlen($color) == 6 || strlen($color) == 3 )
				set_theme_mod('background_color', $color);
			else
				set_theme_mod('background_color', '');
		}

		if ( isset($_POST['background-fade']) ) {			
			//The fade option is a checkbox so its a little different:
			
			check_admin_referer('custom-background');
			set_theme_mod('background_fade', 'on');
		}else{
			set_theme_mod('background_fade', '');
		}
		
		if ( isset($_POST['background-fade-speed']) ) {
			check_admin_referer('custom-background');
			
			//TODO: value check
			//if ( in_array($_POST['background-interval'], array('session', 'page', 'continuous')) )
			$speed = $_POST['background-fade-speed'];
			//else
			//$interval = 'continuous';
			
			set_theme_mod('background_fade_speed', $speed);
		}

		if ( isset($_POST['background-fade-out']) ) {			
			//The fade option is a checkbox so its a little different:
			
			check_admin_referer('custom-background');
			set_theme_mod('background_fade_out', 'on');
		}else{
			set_theme_mod('background_fade_out', '');
		}
		
		if ( isset($_POST['background-fade-out-speed']) ) {
			check_admin_referer('custom-background');
			
			//TODO: value check
			//if ( in_array($_POST['background-interval'], array('session', 'page', 'continuous')) )
			$speed = $_POST['background-fade-out-speed'];
			//else
			//$interval = 'continuous';
			
			set_theme_mod('background_fade_out_speed', $speed);
		}

		if ( isset($_POST['background-height']) ) {
			check_admin_referer('custom-background');
			//TODO: check that is valid height
			$height = $_POST['background-height'];
			
			set_theme_mod('background_height', $height);
		}
		
		if ( isset($_POST['background-interval']) ) {
			check_admin_referer('custom-background');
			if ( in_array($_POST['background-interval'], array('session', 'page', 'continuous')) )
				$interval = $_POST['background-interval'];
			else
				$interval = 'continuous';
			set_theme_mod('background_interval', $interval);
		}
		
		if ( isset($_POST['background-interval-timer']) ) {
			check_admin_referer('custom-background');
			//TODO: check that is valid
			$interval_timer = $_POST['background-interval-timer'];
			
			set_theme_mod('background_interval_timer', $interval_timer);
		}
		
		if ( isset($_POST['background-selector']) ) {
			check_admin_referer('custom-background');
			//TODO: check that is valid
			$selector = $_POST['background-selector'];
			
			set_theme_mod('background_selector', $selector);
		}
		
		

		$this->updated = true;
	}

	/**
	 * Set up the enqueue for the CSS & JavaScript files.
	 * 
	 */
	function admin_enqueue() 
	{
		
		get_current_screen()->add_help_tab( array(
			'id'	=> 'my_help_tab',
			'title' => __('Better Backgrounds Help'),
			'content' => '<p>' . __( 'You can customize the look of your site without touching any of your theme&#8217;s code by using a Better Backgrounds..' ) . '</p>' .
						 '<p>' . __( '<a href="http://davetcoleman.com/blog/better-backgrounds-wordpress-plugin" target="_blank">Documentation on Better Backgrounds</a>' ) . '</p>'
		));
		
		
		//$plugin_path = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		$plugin_path = WP_PLUGIN_URL.'/better-backgrounds/';
				
		wp_enqueue_script('farbtastic');
		wp_enqueue_style('farbtastic');
		
		wp_register_script( 'better-backgrounds', $plugin_path.'better-backgrounds.js?ver2=3.0.1');
		wp_enqueue_script('better-backgrounds');
	}

	/**
	 * Display the Better Backgrounds page.
	 *
	 */
	function admin_page() 
	{
		global $bbg_version;
		
		
		
//HTML CSS JS ----------------------------------------------------------------------------------
?>
	
<style type="text/css">

	.postbox .inside {
		padding: 10px 15px;
	}
	.postbox .inside .form-table th{
		width:130px;
		padding-left:0px;
	}
	.bbg_layout_columns {
		vertical-align: middle;
	}
	.bbg_layout_columns label{
		font-weight:bold;
		verticle-align:top;
	}

</style>
			
<div class="wrap" id="better-backgrounds">

	<?php screen_icon(); ?>

	<h2><?php _e('Better Backgrounds', 'bbg'); ?></h2>
	<i><?php _e('Keep your site looking fresh!', 'bbg'); ?></i> &nbsp;&nbsp;
	<a href="http://davetcoleman.com/blog/better-backgrounds-wordpress-plugin" target="_blank"><?php _e('Help & Support', 'bbg'); ?></a>
	<br /><br />
	
	<form method="post" action="">
	
		<?php if ( !empty($this->updated) ): ?>
			<div id="message" class="updated">
				<p><?php printf( __( 'Background updated. <a href="%s">Visit your site</a> to see how it looks.' ), home_url( '/' ) ); ?></p>
			</div>
		<?php endif; ?>
	
		<div class="metabox-holder">
			
			<!-- Preview Box... no time, this feature coming soon :-) 
			<div class="postbox ">
	        	<div class="handlediv" title="Click to toggle"><br></div>
	        	<h3 class="hndle"><span><?php _e('Background Preview', 'bbg'); ?></span></h3>
	        	<div class="inside" >
	        		It is important that you ensure your backgrounds looks good on all resolution monitors. The following box simulates the resolution of a Apple Cinema Display - a really big monitor with a resolution of 2560 by 1440.  
	        		
	        		<?php
	        		//Apple Cinema Monitor: 2560 by 1440
	        		//Use 3.5:1 reduction scale 
	        		$dim['x'] = 2560/3.5;
	        		$dim['y'] = 1440/3.5;
	        		?>
	        	
	        		<div id="custom-background-image" style="height:<?php echo $dim['y']?>px;width:<?php echo $dim['x']?>px;">
	        		
	        		</div>
	        	
	        	</div>
	        </div>-->
	        
	        <!-- Begin Background Images Box -->
	        <div class="postbox ">
	        	<div class="handlediv" title="Click to toggle"><br></div>
	        	<h3 class="hndle"><span><?php _e('Background Images', 'bbg'); ?></span></h3>
	        	<div class="inside">
	        		<? if (! is_plugin_active('nextgen-gallery/nggallery.php')): ?>
						<?php _e('Note: You are in manual upload mode and may need server access via FTP to add background images. For easier use we recommend you install the <a href="http://wordpress.org/extend/plugins/nextgen-gallery/">NextGen Gallery</a> Wordpress plugin and return to this page.', 'bbg'); ?>
						<br /><br />
					<? endif; ?>

					<?php _e('Find and upload your desired images in .png, .gif, or .jpg formats.', 'bbg'); ?>
					<?php _e('Based on today\'s browser display statistics, a good recommended image size is 1280px x 1024px.', 'bbg'); ?>
	        		<table class="form-table">
			            <tbody>	
							<tr>
								<th>
									<?php _e('Images Location:', 'bbg'); ?>
								</th>
								<td>
									<?php 
									// If NextGen Galley plugin is installed, force user to use that because it makes this interface way easier. possible todo: allow NGG users to override this if they want. 
									if (is_plugin_active('nextgen-gallery/nggallery.php')): ?>
										
										<input type="hidden" name="background-path" id="background-path" size="65" value="<?php echo get_theme_mod('background_path',''); ?>" /> 
									 
										<?php
										global $wpdb;
										$galleries = $wpdb->get_results( "SELECT gid, name, path FROM ".$wpdb->prefix."ngg_gallery" );
										
										?>
										<select id="ngg-galleries-select" style="width:400px;">
											<option value=""><i><?php _e('Choose your NextGen Gallery', 'bbg'); ?></i></option>
																								  
											<?php foreach ($galleries as $gallery) { ?>
												<option value="<?php echo $gallery->path; ?>" <?php echo (get_theme_mod('background_path')==$gallery->path?'selected="selected"':''); ?> ><?php echo $gallery->gid . ' - '. $gallery->name; ?></option>
											<?php } ?>
											
										</select>
											  
										<script>                                        
											jQuery("#ngg-galleries-select").change(function () {
												var ngGallery = jQuery(this).val();
												jQuery("#background-path").val(ngGallery);
											});
										</script>
											
										
										
										<div class="description">
				                    		<?php _e('Upload images to a new ','bbg'); ?>
											<a href="admin.php?page=nggallery-add-gallery"><?php _e('Nextgen Gallery', 'bbg'); ?></a>
				                    	</div>
									<?php else: ?>
								
										<b><?php _e('Enter the server sub-folder path', 'bbg'); ?></b> <?php _e('to the folder containing your background images:', 'bbg'); ?>
										<br/>
										<?php echo str_replace('\\','/', ABSPATH);  ?>
										<input type="text" name="background-path" id="background-path" size="65" value="<?php echo get_theme_mod('background_path',''); ?>" /> 
										<i><?php _e('(e.g. wp-content/uploads/bg-images/)', 'bbg'); ?></i>
										
									<?php endif; ?>
								</td>
				            </tr>
				        </tbody>
					</table>
		        </div>
		   </div>
		   
		   <!-- Begin Background Layout Box -->
		   <div class="postbox ">
	        	<div class="handlediv" title="Click to toggle"><br></div>
	        	<h3 class="hndle"><span><?php _e('Background Layout', 'bbg'); ?></span></h3>
	        	<div class="inside">
	        		<?php _e('Choose how you want the background to be displayed on your website.', 'bbg'); ?>
	        		
	        		<table class="form-table">
			            <tbody>	
							<tr id="bbg_option_layout">
								<th >
									<?php _e('Layout Mode:', 'bbg'); ?>
								</th>
								<td>
									<input type="radio" id="background-layout-better" name="background-layout" value="better" <?php checked('better', get_theme_mod('background_layout', 'better')); ?> >
									<b><?php _e('Better Background', 'bbg'); ?></b> &nbsp;&nbsp;<i><?php _e('Recommended', 'bbg'); ?></i>
									<br />
									<?php _e('Easiest to implement and offers the most options and customizations. Scales it to fit browser window exactly.', 'bbg') ?> 
									<br />
									<input type="radio" id="background-layout-classic" name="background-layout" value="classic" <?php checked('classic', get_theme_mod('background_layout', 'better')); ?> >
									<b><?php _e('Classic Background', 'bbg'); ?></b>
									<br />
									<?php _e('Uses the standard body background css attribute. Does not scale the background to fit the page\'s width or support fade in. Works like the regular Wordpress Custom Background functionality.', 'bbg') ?>
									<br />
									<input type="radio" id="background-layout-developer" name="background-layout" value="developer" <?php checked('developer', get_theme_mod('background_layout', 'better')); ?> >
									<b><?php _e('Developer', 'bbg'); ?></b>
									<br />
									<?php _e('Specify exactly where to place the background on the webpage.', 'bbg') ?> 
									<br />
								</td>
				            </tr>
				        </tbody>
					</table>
		        </div>
		   </div>
		   
		   
		   <!-- Begin Background Layout Options Box -->
		   <div class="postbox ">
	        	<div class="handlediv" title="Click to toggle"><br></div>
	        	<h3 class="hndle"><span><?php _e('Background Layout Options', 'bbg'); ?></span></h3>
	        	<div class="inside">
	        		<?php _e('Customize your chosen background layout.', 'bbg'); ?>
	        		
	        		<table class="form-table">
			            <tbody>	
							<tr id="bbg_option_color">
								<th scope="row"><?php _e( 'Color' ); ?></th>
								<td>
									<fieldset><legend class="screen-reader-text"><span><?php _e( 'Background Color', 'bbg' ); ?></span></legend>
									<input type="text" name="background-color" id="background-color" size=10 value="<?php echo '#'.esc_attr(get_theme_mod('background_color','')); ?>" />
									<a class="hide-if-no-js" href="#" id="pickcolor"><?php _e('Select a Color'); ?></a>
									<div id="colorPickerDiv" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"><div class="farbtastic"><div class="color" style="background-color: rgb(255, 0, 0); "></div><div class="wheel"></div><div class="overlay"></div><div class="h-marker marker" style="left: 97px; top: 13px; "></div><div class="sl-marker marker" style="left: 147px; top: 147px; "></div></div></div>
									</fieldset>
								</td>
							</tr>
							<tr id="bbg_option_attachment">
								<th>
									<?php _e('Attachment', 'bbg'); ?>
								</th>
								<td>
									<fieldset><legend class="screen-reader-text"><span><?php _e( 'Background Attachment', 'bbg' ); ?></span></legend>
									<label>
									<input name="background-attachment" id="background-attachment-scroll" type="radio" value="scroll" <?php checked('scroll', get_theme_mod('background_attachment', 'scroll')); ?> />
									<?php _e('Scroll', 'bbg') ?></label>
									<label>
									<input name="background-attachment" id="background-attachment-fixed" type="radio" value="fixed" <?php checked('fixed', get_theme_mod('background_attachment', 'scroll')); ?> />
									<?php _e('Fixed', 'bbg') ?></label>
									</fieldset>
									<?php _e('\'Fixed\' - the background image will not move as the page scrolls down.', 'bbg'); ?>
								</td>
							</tr>
							
							<tr id="bbg_option_position">
								<th scope="row">
									<?php _e( 'Position', 'bbg' ); ?>
								</th>
								<td>
									<fieldset><legend class="screen-reader-text"><span><?php _e( 'Background Position', 'bbg' ); ?></span></legend>
									<label>
									<input name="background-position-x" type="radio" value="left"<?php checked('left', get_theme_mod('background_position_x', 'left')); ?> />
									<?php _e('Left', 'bbg') ?></label>
									<label>
									<input name="background-position-x" type="radio" value="center"<?php checked('center', get_theme_mod('background_position_x', 'left')); ?> />
									<?php _e('Center', 'bbg') ?></label>
									<label>
									<input name="background-position-x" type="radio" value="right"<?php checked('right', get_theme_mod('background_position_x', 'left')); ?> />
									<?php _e('Right', 'bbg') ?></label>
									</fieldset>
								</td>
							</tr>
							
							<tr id="bbg_option_repeat">
								<th scope="row"><?php _e( 'Repeat', 'bbg' ); ?></th>
								<td>
									<fieldset><legend class="screen-reader-text"><span><?php _e( 'Background Repeat', 'bbg' ); ?></span></legend>
										<label><input type="radio" name="background-repeat" value="no-repeat"<?php checked('no-repeat', get_theme_mod('background_repeat', 'no-repeat')); ?>> <?php _e('No Repeat', 'bbg'); ?></option></label>
										<label><input type="radio" name="background-repeat" value="repeat"<?php checked('repeat', get_theme_mod('background_repeat', 'no-repeat')); ?>> <?php _e('Tile', 'bbg'); ?></option></label>
										<label><input type="radio" name="background-repeat" value="repeat-x"<?php checked('repeat-x', get_theme_mod('background_repeat', 'no-repeat')); ?>> <?php _e('Tile Horizontally', 'bbg'); ?></option></label>
										<label><input type="radio" name="background-repeat" value="repeat-y"<?php checked('repeat-y', get_theme_mod('background_repeat', 'no-repeat')); ?>> <?php _e('Tile Vertically', 'bbg'); ?></option></label>
									</fieldset>
								</td>
							</tr>
							
							<tr id="bbg_option_fade">
								<th scope="row"><?php _e('Fade In', 'bbg') ?></th>
								<td>
									<fieldset><legend class="screen-reader-text"><span><?php _e('Fade In Background', 'bbg') ?></span></legend>
									<input type="checkbox" name="background-fade" id="background-fade" value="on" <?php checked('on', get_theme_mod('background_fade', 'on')); ?> />
									<?php _e('Use the jQuery fade in effect to show your background. Looks best with a black background color.', 'bbg'); ?>											
									</fieldset>
								</td>
							</tr>
							
							<tr id="bbg_option_fade_speed">
								<th scope="row"><?php _e('Fade In Speed', 'bbg') ?></th>
								<td>
									<fieldset><legend class="screen-reader-text"><span><?php _e('Fade In Speed', 'bbg') ?></span></legend>
									<input type="text" name="background-fade-speed" size=10 value="<?php echo get_theme_mod('background_fade_speed','400'); ?>" /> <i><?php _e('milliseconds', 'bbg') ?> (e.g. "400")</i>
									</fieldset>
								</td>
							</tr>

							<tr id="bbg_option_fade_out">
								<th scope="row"><?php _e('Fade Out', 'bbg') ?></th>
								<td>
									<fieldset><legend class="screen-reader-text"><span><?php _e('Fade Out Background', 'bbg') ?></span></legend>
									<input type="checkbox" name="background-fade-out" id="background-fade-out" value="on" <?php checked('on', get_theme_mod('background_fade_out', 'off')); ?> />
									<?php _e('Use the jQuery fade out effect to show your background. Looks best with a black background color.', 'bbg'); ?>											
									</fieldset>
								</td>
							</tr>
							
							<tr id="bbg_option_fade_out_speed">
								<th scope="row"><?php _e('Fade Out Speed', 'bbg') ?></th>
								<td>
									<fieldset><legend class="screen-reader-text"><span><?php _e('Fade Out Speed', 'bbg') ?></span></legend>
									<input type="text" name="background-fade-out-speed" size=10 value="<?php echo get_theme_mod('background_fade_out_speed','400'); ?>" /> <i><?php _e('milliseconds', 'bbg') ?> (e.g. "400")</i>
									</fieldset>
								</td>
							</tr>
							
							<tr id="bbg_option_height">
								<th scope="row"><?php _e('Height', 'bbg'); ?></th>
								<td>
									<fieldset><legend class="screen-reader-text"><span><?php _e('Fade In Background', 'bbg') ?></span></legend>
									<input type="text" name="background-height" size=10 value="<?php echo get_theme_mod('background_height',''); ?>" /> <i>(e.g. "800px")</i>											
									</fieldset>
									<?php _e('Full page width, scrolling layout only. There is no height by default. If you want backgrounds to fit width and height of your page, you can set the height to 100%. Otherwise you can set the height in pixels.', 'bbg'); ?></i>
								</td>
							</tr>
							
						</tbody>
					</table>
		        </div>
		   </div>
			   
		   <!-- Random Image Change Interval -->
		   <div class="postbox ">
	        	<div class="handlediv" title="Click to toggle"><br></div>
	        	<h3 class="hndle"><span><?php _e('Random Image Change Interval', 'bbg'); ?></span></h3>
	        	<div class="inside">
	        		<?php _e('How often your background image changes.', 'bbg'); ?>
	        		
	        		<table class="form-table">
			            <tbody>	
							<tr id="option_attachment">
								<th>
									<?php _e('Change Interval', 'bbg'); ?>
								</th>
								<td>
									<input type="radio" id="background-interval1" name="background-interval" value="session" <?php checked('session', get_theme_mod('background_interval', 'continuous')); ?>> 
									<label><b><?php _e('Once A Visitor Session', 'bbg'); ?></b></label>
									<br />
									<?php _e('The same random image will be used on every page of the site, until they visit on a different session (uses cookies).', 'bbg'); ?>
									<br />
								
									<input type="radio" id="background-interval2" name="background-interval" value="page" <?php checked('page', get_theme_mod('background_interval', 'continuous')); ?>> 
									<label><b><?php _e('Every Page Refresh', 'bbg'); ?></b></label>
									<br />
									<?php _e('Every time a visitor changes pages a new random image is chosen.', 'bbg'); ?>
									<br />
								
									<input type="radio" id="background-interval3" name="background-interval" value="continuous" <?php checked('continuous', get_theme_mod('background_interval', 'continuous')); ?>> 
									<label><b><?php _e('Continuously On Timer Interval (Slideshow)', 'bbg'); ?></b></label>
									<br />
									
									<?php _e('Background images will loop through like a slideshow.', 'bbg'); ?>
									<br />
									<?php _e('Change Background Every', 'bbg'); ?> <input type="text" size=2 name="background-interval-timer" value="<?php echo get_theme_mod('background_interval_timer', 10); ?>"> <?php _e('seconds', 'bbg'); ?>
							
								</td>
							</tr>
							
						</tbody>
					</table>
		        </div>
			</div>
		   
			<!-- Developer Instructions -->
		   	<div class="postbox " id="bbg_option_developer">
	        	<div class="handlediv" title="Click to toggle"><br></div>
	        	<h3 class="hndle"><span><?php _e('Developer Integration', 'bbg'); ?></span></h3>
	        	<div class="inside">
	        		
	        		<table class="form-table">
			            <tbody>	
							<tr>
								<th>
									<?php _e('Object Selector', 'bbg'); ?>
								</th>
								<td>
									<?php _e('If you want to customize where the background image is to be placed you can specify the id or class name of a DOM object, like a &lt;div&gt;. 
									This plugin will automatically insert the css to set the object\'s \'background:\' property and use javascript to change the background or apply effects, as specified in the options above.', 'bbg') ?>
									<br /> 
									<fieldset><legend class="screen-reader-text"><span><?php _e('Object Selector', 'bbg') ?></span></legend>
									<input type="text" name="background-selector" size=15 value="<?php echo get_theme_mod('background_selector',''); ?>" /> <i>(e.g. "#left_column")</i>											
									</fieldset>									
								</td>
							</tr>
							<tr>
								<th>
									<?php _e('Function Call', 'bbg'); ?>
								</th>
								<td>
									<?php _e('The function call is probably not necessary but is included here for reference', 'bbg')?><br />
									<?php _e('To insert the custom background image into your custom theme, you can use something similar to the following code:', 'bbg') ?>
									<br />
									<code>&lt;img src='&lt;?=background_image();?&gt;' /&gt;</code>
									<br />
									<?php _e('Or to add the background image url to a variable, use something like this:', 'bbg') ?>
									<br />
									<code>$background = get_background_image();</code>
									<br />
									<i><?php _e('Note: this function only works in Wordpress 3.0 and higher.', 'bbg') ?></i>
								</td>
							</tr>
						</tbody>
					</table>
		        </div>
		   </div>
		   

		</div>

		<?php wp_nonce_field('custom-background'); ?>
		<?php submit_button( null, 'primary', 'save-background-options' ); ?>
	</form>
	
	
	<br />
	<div style="float:right;font-style:italic;">Better Backgrounds Version <?php echo $bbg_version; ?></div>
			
</div>

<?php
//END OF HTML CSS JS ----------------------------------------------------


	}	//end of admin_page() function	

	
	/**
	 * Add Settings link to Admin plugins page - code from GD Star Ratings
	 * 
	 */
	function bbg_add_settings_link($links, $file) {
		
		if ($file == 'better-backgrounds/better-backgrounds.php'){
			$settings_link = '<a href="themes.php?page=better-backgrounds">'.__("Settings", "bbg").'</a>';
			array_unshift($links, $settings_link);
		}
		return $links;
	}

} //end of class

<?php
/*
Plugin Name: Better Backgrounds
Plugin URI:  http://davetcoleman.com/blog/better-backgrounds-wordpress-plugin
Version:     3.0.7
Description: Random background image every visitor session, page refresh, or timed slideshow interval. Choose between fixed full screen background or scrolling background.
Author:      Dave Coleman
Author URI:  http://davetcoleman.com/

Copyright 2011 by Dave Coleman (email: dave@davetcoleman.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
$bbg_version = '3.0.7';
	
//Only include the admin config code if in Wordpress Admin Backend	
if(is_admin()){
	require_once('bbg_admin.php');
	$GLOBALS['custom_background'] =& new Better_Backgrounds();
	add_action( 'admin_menu', array( &$GLOBALS['custom_background'], 'init' ) );
	add_filter('plugin_action_links', array( &$GLOBALS['custom_background'], 'bbg_add_settings_link' ), 10, 2 );
}else{
	//Prevent the default wordpress custom background functionality from loading
	$GLOBALS['custom_background'] = true;
	
}

//Remove Wordpress' default Custom Background Support If Theme Has It Enabled
//$GLOBALS['custom_background'] = false;	//This is a variable that will prevent the add_custom_background from running

//Frontend Display Functions -----------------------------------------------------------------------------

//Load plugin options
	$bbg_layout = get_theme_mod('background_layout','better');
	$bbg_repeat = get_theme_mod('background_repeat','no-repeat');
	$bbg_position_x = get_theme_mod('background_position_x','left');
	$bbg_attachment = get_theme_mod('background_attachment','fixed');
	$default_color = defined('BACKGROUND_COLOR') ? BACKGROUND_COLOR : '';
	$bbg_color = get_theme_mod('background_color', $default_color);
	$bbg_fade = get_theme_mod('background_fade', 0);
	$bbg_fade_speed = get_theme_mod('background_fade_speed', 400);
	$bbg_fade_out = get_theme_mod('background_fade_out', 0);
	$bbg_fade_out_speed = get_theme_mod('background_fade_out_speed', 400);
	$bbg_height = get_theme_mod('background_height','100%');
	$bbg_interval = get_theme_mod('background_interval','page');
	$bbg_interval_timer = get_theme_mod('background_interval_timer','10');
	$bbg_selector = get_theme_mod('background_selector','');
	//$bbg_link = get_theme_mod('background_link','');
	
	

//Only require jQuery be loaded if this option has been checked
	if($bbg_fade || $bbg_fade_out || $bbg_interval == 'continuous')
		add_action('wp_enqueue_scripts', 'bbg_enqueue' );
		
//Start the session now before headers are sent out
	if($bbg_interval == 'session')
		session_start();
	
//Add the background html code filter hook
	add_filter( 'wp_head', 'bbg_header' );	 
	add_filter( 'wp_footer', 'bbg_footer' ); 

//Set bbg_image as global so it can be passed to the footer
global $bbg_image;


//Enqueue scripts when Wordpress is ready for it, fix for Wordpress 3.3
function bbg_enqueue()
{
	wp_enqueue_script("jquery");
}

//Load the URL to the background image
function bbg_loadBG()
{
	global $bbg_image, $bbg_interval;
	
	if($bbg_interval == 'session')
	{
		//Check if there is already a BG saved in session
		session_start();
		
		if(isset($_SESSION['bbg_background']))	//already have session bg 
		{
			$bbg_image = $_SESSION['bbg_background'];	//get from cookie
		}
		else
		{
			$bbg_image = generateRandomImage();	//pull from folder a random bg
			$_SESSION['bbg_background'] = $bbg_image;	//save image to session
		}
	}
	else
	{
		$bbg_image = generateRandomImage();	//pull from folder a rand bg
	}	
	
	//The background image link is defined so that it can be referrenced by the default 
	//Wordpress get_background_image() function in wp-includes/theme.php
	define('BACKGROUND_IMAGE', $bbg_image);
}	
	

function bbg_header()
{
	global $bbg_interval, $bbg_layout, $bbg_interval_timer, $bbg_height, $bbg_image, $bbg_attachment, $bbg_repeat, $bbg_position_x, $bbg_color, $bbg_selector, $bbg_version;
	  
		
	//Create and embed the style sheet ----------------------------------------------
	
	?>
	<!-- Better Backgrounds <?php echo $bbg_version; ?> - http://davetcoleman.com/blog/better-backgrounds-wordpress-plugin -->
	<style type="text/css">
	<?php
	
	//All layout types have the page's body's background color set.
	$style = $bbg_color ? "background-color: #$bbg_color;" : '';
	
	
	//Decided what to do based on layout
	switch($bbg_layout){
		//-------------------------------------------------------------------------------------------------------
		case 'better':
			//Determine if the background should scroll with page 
			if($bbg_attachment == 'fixed'):
					//This Background Layout Based on "CSS-Only Technique #1" http://css-tricks.com/perfect-full-page-background-image/			
			  ?>	img.bg-rand { min-height: 100%; min-width: 1024px; width: 100%; height: auto; position: fixed; top: 0; left: 0; z-index:-1; } @media screen and (max-width: 1024px) { img.bg-rand { left: 50%; margin-left: -512px; } }<?php
		//-------------------------------------------------------------------------------------------------------	
			else:
				//Create the css height property if applicable
				if(!empty($bbg_height))
					$bbg_height = 'height:'. $bbg_height .';';	
					
			  ?>	img.bg-rand { position:absolute; top:0; left:0; z-index:-1; width:100%; <?php echo $bbg_height; ?> } <?php
			endif;
			
			break;
		//-------------------------------------------------------------------------------------------------------
		case 'classic':
				
			//This code is from Wordpress default: wp-includes/theme.php	
			$style .= " background-image: url('$bbg_image');";
	
			if ( ! in_array( $bbg_repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
				$bbg_repeat = 'repeat';
			$style .= " background-repeat: $bbg_repeat;";
	
			if ( ! in_array( $bbg_position_x, array( 'center', 'right', 'left' ) ) )
				$bbg_position_x = 'left';
			$style .= " background-position: top $bbg_position_x;";
	
			if ( ! in_array( $bbg_attachment, array( 'fixed', 'scroll' ) ) )
				$bbg_attachment = 'scroll';
			$style .= " background-attachment: $bbg_attachment;";
			
			break;
		//-------------------------------------------------------------------------------------------------------
		case 'developer':
			  echo $bbg_selector; ?> { background-image: url('<?php echo $bbg_image ?>'); } <?php
			  
		break;
	}
	
	//Output body style
	$style = trim($style);
	if(!empty($style)):
		  ?>	body { <?php echo $style; ?> } <?php
	endif;
	
	?>
	</style>
	<!-- End Better Backgrounds -->
	<?php
	
}

//Now output the img tag and any javascript
function bbg_footer()
{
	global $bbg_link, $bbg_fade, $bbg_fade_speed, $bbg_fade_out, $bbg_fade_out_speed, $bbg_image, $bbg_interval, $bbg_interval_timer, $bbg_layout, $bbg_selector;
	
	?>
	<!-- Better Backgrounds -->
	<?php
	
	//Outuput the actual image if not in basic mode
	//Set the alt to empty to allow it to properly validate in non-HTML5 themes
	if($bbg_layout == 'better'):
		?>
		<img id="bg-rand" class="bg-rand" src="<?php echo $bbg_image; ?>" alt="" />
		<?php
	endif;
	
	//Output a preloader image if the interval is continuous:
	//Set the src to the same image as above just so that the html validates
	if($bbg_interval == 'continuous' || $bbg_fade):
		?>
		<img id="bg-preloader" style="display:none;"  src="<?php echo $bbg_image; ?>" alt="Hidden Preloader"/>
		<?php
	endif;
	
	//Open the scripting tags if we need javascipt on this page:
	if($bbg_interval == 'continuous' || $bbg_fade || $bbg_fade_out):
		?>
		<script type="text/javascript">
			<?php
			//Find the DOM object we will be modifying
			if($bbg_layout == 'better')
				$bbg_selector = "#bg-rand";	//The standard 'better' layout has a pre-defined selector tag
			if($bbg_layout == 'classic')
				$bbg_selector = "body"; //The classic layout always modifies the 'body' element
			
			//cache the DOM objects for increased JS speed
			?>
			var bbg_imgObject = jQuery("<?php echo $bbg_selector; ?>");
			var bbg_imgPreload = jQuery("#bg-preloader");
			<?php 
			
			//Check if jQuery fade effect is to be used
			if($bbg_fade): 
				?>
				bbg_imgObject.hide();
				bbg_imgPreload.load(
					function(){	//fade the image in once it is done loading, then start the continuous slideshow if applicable
						bbg_imgPreload.unbind();	//remove the load event
						bbg_imgObject.fadeIn(<?php echo $bbg_fade_speed ?>, "linear" <?php echo ($bbg_interval == 'continuous')?',startSlideshow':''; ?>);
					}
				).each(function(){ // this is a hack for IE6 and Opera to ensure that the slideshow starts. sometimes .load() is not reliable in browsers
					if(jQuery.browser.msie && parseInt(jQuery.browser.version) == 6 || jQuery.browser.opera)
					{
						jQuery(this).trigger("load");
					}
				});
				<?php
			//Check if not fade but still continuous slideshow
			elseif($bbg_interval == 'continuous'):
				?>
				//Start the timer
				jQuery(document).ready(startSlideshow);
				
				<?php 
			endif;

			//Timer Interval Slideshow
			if($bbg_interval == 'continuous'):
				?>
				var bbg_timer;

				<?php bbg_outputJSList(); //Make the javascript array of slideshow images ?>
				
				//This prevents us from having to use setInterval, which is bad for jQuery animation effects
				function startSlideshow(){
					
					bbg_timer = setTimeout("bbg_changeBackground()", <?php echo $bbg_interval_timer * 1000; ?>);
					//Preload next background when DOM is done loading
					bbg_imgPreload.attr('src',bbg_image_base_path+'/'+bbg_images[bbg_image_array_pointer]);
					
				}
				
				//Change the background
				function bbg_changeBackground(){
					clearTimeout(bbg_timer);
					
					<?php if($bbg_layout == 'classic'): //body tag doesn't support fade ?>
					
						jQuery("body").css('background-image','url('+bbg_image_base_path+'/'+bbg_images[bbg_image_array_pointer]+')');
						startSlideshow();	//restart

					<?php elseif($bbg_layout == 'developer'): ?>
						//Developers: feel free to change this to fit your needs. This was simply for one project I had
						bbg_imgObject.hide().css('background-image','url('+bbg_image_base_path+'/'+bbg_images[bbg_image_array_pointer]+')')
							.fadeIn(<?php echo $bbg_fade_speed ?>,'linear', startSlideshow);
						
					<?php elseif($bbg_fade && $bbg_fade_out): //fade the image in and out  ?>
					
						bbg_imgObject.fadeOut(<?php echo $bbg_fade_out_speed ?>,'linear', function(){
							bbg_imgObject.attr('src',bbg_image_base_path+'/'+bbg_images[bbg_image_array_pointer])
								.fadeIn(<?php echo $bbg_fade_speed ?>,'linear', startSlideshow);
						});

					<?php elseif($bbg_fade): //fade the image in only  ?>
					
						bbg_imgObject.hide().attr('src',bbg_image_base_path+'/'+bbg_images[bbg_image_array_pointer])
							.fadeIn(<?php echo $bbg_fade_speed ?>,'linear', startSlideshow);

					<?php elseif($bbg_fade_out): //fade the image out only  ?>
					
						bbg_imgObject.fadeOut(<?php echo $bbg_fade_out_speed ?>,'linear', function(){
							bbg_imgObject.attr('src',bbg_image_base_path+'/'+bbg_images[bbg_image_array_pointer])
								.fadeIn(10,'linear', startSlideshow);
						});
												
					<?php else: //just change image, no fade ?>
					
						bbg_imgObject.attr('src',bbg_image_base_path+'/'+bbg_images[bbg_image_array_pointer]);
						startSlideshow();	//restart
					
					<?php endif; ?>
					
					//Get next image index
					bbg_image_array_pointer ++;

					//Loop if at end
					if(bbg_image_array_pointer >= bbg_images.length)
						bbg_image_array_pointer = 0;
				}
					
				<?php
			endif;
			
			?>
		</script>
		
	<?php endif; ?>
	<!-- End Better Backgrounds -->
	<?php
}

		
$bbg_image_array_pointer = 0;
$bbg_image_array = null;
$bbg_image_base_path = null;

//This is where the random image is chosen
function generateRandomImage()
{
	global $bbg_image_array_pointer, $bbg_image_array, $bbg_image_base_path;
	
	$imageSubPath = get_theme_mod('background_path','/wp-content/uploads/bg-images/');
    $physicalPath = ABSPATH . $imageSubPath;
	$bbg_image_base_path = get_bloginfo('wpurl') . "/" . $imageSubPath;
	
    $image_types = array('jpg','png','gif'); // Array of valid image types
    $image_directory = @opendir($physicalPath);

	//Check if directory does not exist
	if($image_directory === false){
		return false;
	}
	
	
	//Loop through every file in directory
    while($image_file = readdir($image_directory))
    {
				
	    foreach($image_types as $ext) { // for each extension check the extension
		
			if (preg_match('/\.'.$ext.'$/i', $image_file)) { // faster than ereg, case insensitive
				//Add file to image array
				$bbg_image_array[] = $image_file;
				// End foreach loop
				break;
			}
		}
    }
	
    closedir($image_directory); // We're not using it anymore
    
    // seed for PHP < 4.2
    mt_srand((double)microtime()*1000000); 
    
	//Choose a random index from the array
	$bbg_image_array_pointer = mt_rand(0,count($bbg_image_array)-1);
	
	//Get the index's image
    $chosen_image = $bbg_image_base_path.'/'.$bbg_image_array[$bbg_image_array_pointer];
    
    
	//Progress to the next index
	$bbg_image_array_pointer ++;
	if($bbg_image_array_pointer >= count($bbg_image_array))
		$bbg_image_array_pointer = 0;
	
	return $chosen_image;
}

//This creates a list of backgrounds for the javascript to loop through
function bbg_outputJSList(){
	global $bbg_image_array_pointer, $bbg_image_array, $bbg_image_base_path;
		
	?>
		var bbg_image_base_path = "<?php echo $bbg_image_base_path; ?>";
		var bbg_image_array_pointer = <?php echo $bbg_image_array_pointer; ?>;
		var bbg_images = new Array(<?php echo count($bbg_image_array); ?>);
		
	<?php foreach ($bbg_image_array as $key => $value): ?>
		bbg_images[<?php echo $key; ?>] = "<?php echo $value; ?>";
	<?php endforeach;
	
}


bbg_loadBG();

















	
	
	
	
	
	
	
	
=== Better Backgrounds ===
Contributors: waffleguy4
Tags: background, backgrounds, bg, image, images, multiple, random, fixed, full, screen, slideshow, fade, changing
Requires at least: 2.0.2
Tested up to: 3.3.1
Stable tag: 3.0.7

Random background image every visitor session, page refresh, or timed slideshow interval. Choose between fixed full screen or scrolling background.

== Description ==

Keep your site looking fresh with changing backgrounds!

Show a random background image every visitor session (using a cookie), page refresh, or timed slideshow interval. Choose between fixed full screen background, full width scrolling background, or classic non-scaled backgrounds. Supports background fade effects and optionally integrates with NextGen Gallery plugin. Replaces the built-in Wordpress Background settings page.

Makes having cool backgrounds easy.

= Features! =
* Randomly change the background images - once per visitor session (using a cookie), once per page refresh or continuously using a slideshow-like timer.
* 3 Layout options - “Better” “Classic” and “Developer” - with many options
* Optionally scales your background to fit the user's exact monitor size.
* Allows for random backgrounds to repeat/tile
* Optionally fade in and/or fade out the background dynamically using jQuery.
* Pre-loads next image in slideshows option to reduce flickering & lag.
* Upload background images using the NextGen Gallery plugin (must install first) or manually through FTP uploads
* Cross-Browser Tested with Wordpress' default Twenty Ten 1.3 and Twenty Eleven 1.3 themes. Passes W3C Validation.
* Works with most custom themes
* Allows for custom integration with DOM object names and function calls.
* Internationalized to Spanish, French, Portuguese and Chinese

= Technical Details =

The plugin is optimized to not affect your page's load time because it requires no extra style sheets or javascript files to be loaded. The only exception is the popular jQuery framework if you choose to have a fading background or slideshow-like image changing, but chances are you are already using jQuery and the user already has this saved in their cache.

When the fade in or out options are enabled the background will not appear until the image is fully loaded in browser to improve the effect appearance. If you want to have the background to load sooner there are instruction on this plugin's homepage on how to set that up manually.

The developer mode allows you to have a randomly changing div, span, etc on any element. Simply provide the class or id name and it will add the background-image for you. There is also an optional function to insert the random image into the html anywhere on the page.

When changing background image every visitor session, a temporary cookie is made so that the chosen background image remains the same for the entire visit of the user on your site. The next time they visit your site, and once the browser cookie has expired, a new image will be randomly chosen. This is usually every 6 hours, but may vary due to various reasons. 

= Languages =
* English
* Spanish
* French
* Chinese (thanks Jie)
* Brazilian Portuguese (thanks Raphael Suzuki)
* Translate for us!

= More Info =
[Plugin Homepage](http://davetcoleman.com/blog/better-backgrounds-wordpress-plugin)

[Live Demo](http://www.davetcoleman.com/better-backgrounds/)

= Contributors = 
[Dave Coleman](http://davetcoleman.com/),

== Installation ==

1. Install and activate through your Wordpress' Admin plugins page.
2. Go to Wordpress Admin->Appearance->Better Backgrounds
3. Do what the page says to do.

Kabam! You've got better backgrounds.

== Screenshots ==

1. **Better Background, Fixed.** Recommended. Background remains the same as you scroll down the page. Scaled and centered to fill entire browser window. Easiest background to implement well. 
2. **Better Background, Scrolling.** Background aligned to top of screen and moves out of view as you scroll down the page. Scaled to full browser width and custom height (default: full browser height). A graphical fade on the bottom of the background image or secondary background pattern is recommended. 
3. **Classic, Scrolling.** Classic background - more standards compliant using the body's background tag. Does not scale the background to fit the page's width, so make sure background is high enough resolution for widescreen monitors. Does not support fading in. 
3. **Plugin Admin Options Page**


== Changelog ==

= 1.0.0 - April 19 2011 =
* Started project as "Random Background Image Per Session"

= 1.0.1 - April 27 2011 =
* Tweaked documentation

= 1.1.0 - May 3 2011 =
* Added the BG height and disable session persistence options on the settings page. Improved instructions and durability
* Tweaked documentation

= 1.2.1 - May 20 2011 =
* Added fixed background support
* Re-vamped settings page and step by step instructions
* Added optional jQuery fade-in (thanks Dubois)
* Added multi-lingual support (thanks Dubois)
* Added integration with NextGen Gallery (thanks Dubois)
* Translated to French (thanks Dubois)
* Removed custom class option - you can just use ".bg-rand" or "#bg-rand" in your style sheet

= 2.0.0 - May 27 2011 =
* Changed plugin name to "Better Backgrounds"
* Added third "Basic Unstretched Centered Image, Scrolling" layout
* Fixed session start header error
* Added slideshow feature to allow background to change using js
* Re-organized settings page
* Added background color setting

= 2.0.1 - May 29 2011 =
* Fixed several core bugs

= 2.0.2 - May 31 2011 =
* NextGen Gallery drop down bug fix
* "Full Page Width & Custom Height, Scrolling" - background height fix
* Documentation update

= 3.0.0 - June 18 2011 =
* Redesign of admin options page
* Plugin setup to now completely replace Wordpress' standard 'Custom Background' feature
* Combined the 'fixed' and 'full width and scrolling' options and renamed to 'Better Background'
* Renamed 'Basic' background to 'Classic'
* Added position and repeat options to 'Classic' layout
* Added a Developer layout option to allow more customized API-like usages
* Added ability for developers to specify what DOM object to apply the background image
* Made the plugin's settings specific to each theme you choose
* Optimized the random image selection and array sorting
* Added alt tags to 'Better' layout mode images for W3C validation
* Added nonce field for increased admin security

= 3.0.1 - June 18th 2011 =
* Fixed Fade-In checkbox bug
* Fixed Developer layout mode bug
* Added fade effect timer speed customization

= 3.0.2 - June 18th 2011 =
* SVN Problems

= 3.0.3 - June 22nd 2011 =
* Bug fix: classic background - image always positioned to the left
* Bug fix: get_background_color() removed for Wordpress versions < 3.0.0
* Javascript/jQuery optimizations of effects and timers
* Changed fade-in effect to first wait for image to fully load before showing

= 3.0.4 - August 4th 2011 =
* Bug fix: developer mode with fade effect with change interval of every page refresh
* Bug fix: IE <9 does not support console.log, forgot to remove after development

= 3.0.5 - September 25th 2011 =
* Bug fix: plugin can break when switching between various layout modes in certain order
* Added French translation
* Updated Spanish translation

= 3.0.6 - October 14th, 2011 =
* Bug fix: removed all variable & depreciation warnings when WP_DEBUG is true
* Disabled the check for settings upgrade because this update has been out for over 4 months

= 3.0.7 - February 19th, 2012 =
* Added new fade out effect!
* Added Chinease language (thanks to Jie!)
* Added Brazilian Portuguese language (thanks Raphael Suzuki!)
* Removed depreciated add_contextual_help function for Wordpress 3.3
* Moved depreciated wp_enqueue_script call method for Wordpress 3.3
* Fixed Opera jQuery.load() bug - slideshow instantly starts now in Opera

= To-Do =
* Preview Window
* Add links to backgrounds
* Support for non-NextGen Gallery upload system
* Choose images per category
* Admin settings validation
* Developer mode: allow for <img> tags with .src
* Developer mode: check if id/class name is given
* Optional full length image urls for CDN services
* Choose image based on page

== Feedback ==

If you make any changes, customizations or translations to the plugin that you think can benefit all, please email me your code and I will happily merge your enhancements to the plugin. I'll welcome any feedback!

If you are having problems with the plugin, be sure to mention which browser/version and a link to the site with the plugin installed.

Any feature requests or bug fixes will have guaranteed & expedited answering with a PayPal donation made with the donate button on the following page:

http://davetcoleman.com/blog/better-backgrounds-wordpress-plugin
dave@davetcoleman.com

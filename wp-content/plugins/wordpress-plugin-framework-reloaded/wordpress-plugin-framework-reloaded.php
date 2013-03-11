<?php
/*
Plugin Name: Wordpress Plugin Framework Reloaded
Version: 0.2.1
Description: Based on Xavisys Plugin Framework, Wordpress Plugin Framework and Simple Plugin Framework. Like it? <a href="http://smsh.me/7kit" target="_blank" title="Paypal Website"><strong>Donate</strong></a> | <a href="http://www.amazon.co.uk/wishlist/2NQ1MIIVJ1DFS" target="_blank" title="Amazon Wish List">Amazon Wishlist</a>
Donate link: http://smsh.me/7kit
Author: Lopo Lencastre de Almeida - iPublicis.com
Author URI: http://ipublicis.com/
Plugin URI: http://wordpress.org/extend/plugins/wordpress-pluging-framework-reloaded/
License: GNU LGPL v3 or later

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License version 3 as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Load the framework file
 */
require_once( 'framework-reloaded.php' );

/**
 * Where you put all your class code
 */
class wpfrMain extends WordpressPluginFrameworkReloaded {
	/**
	 * @var wpfrMain - Static property to hold our singleton instance
	 */
	static $instance = false;
	
	protected function _init() {

		/**
		 * Definition of global values
		 */
		$this->_hook = 'wpfReloaded';
		$this->_pluginDir = str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		$this->_file = plugin_basename(__FILE__);
		$this->_slug = untrailingslashit( $this->_pluginDir );
		$this->_appLogoFile = plugin_dir_url( __FILE__ ).'/images/ipublicis-logo-32.png';
		$this->_pageTitle = "Wordpress Plugin Framework Reloaded";
		$this->_menuTitle = "WPF Reloaded";
		$this->_accessLevel = 'manage_options';
		$this->_optionGroup = 'wpfr-options';
		$this->_optionNames = array('wpfr');
		$this->_optionCallbacks = array();
		$this->_appFeed = 'http://w3.ipublicis.com/newswire/ipublicis/feed';
		$this->_donationID = '7kit';
		$this->_wishlistID = 'A7HJYTOILQO5';
		$this->_contactURL = 'http://w3.ipublicis.com/contact-us';
		$this->_dashboardWidget = array( 	'inc' => 'iPublicis!COM',
																	'url' => 'http://w3.ipublicis.com/',
																	'rss' => 'http://w3.ipublicis.com/rss.xml', 
																	'ico' => plugin_dir_url( __FILE__ ).'/images/ipublicis-logo-32.png'  );
		$this->_sidebarNews = array(  false, false );

		/**
		 * Add filters and actions
		 */
		register_activation_hook( $this->_file, array( $this, 'activate' ) );
		register_deactivation_hook( $this->_file, array( $this, 'deactivate' ) );
	}

	protected function _postSettingsInit() {
		$this->activate();
	}

	public function addOptionsMetaBoxes() {
			add_meta_box( $this->_slug . '-about-us', __('About ', 'framework-reloaded') . $this->_pageTitle, array($this, 'aboutWPFRMetaBox'), $this->_slug . '-about-us', 'main');
			add_meta_box( $this->_slug . '-feed', __('Plugin Latest News.', 'framework-reloaded'), array($this, 'appFeedMetaBox'),  $this->_slug.'-about-us', 'main');
	}

	public function registerOptionsPage() {
		if ( $current_user->ID == 1 || current_user_can( $this->_accessLevel ) ) {
			if( is_callable( array( $this, 'about_page' ) ) )  {
				add_options_page(	$this->_pageTitle, $this->_menuTitle, $this->_accessLevel, 
												$this->_hook . '-options', array( $this, 'about_page' ), $this->_appLogoFile ); 
			}
		}
	}

	/**
	 * Function to instantiate our class and make it a singleton
	 */
	public static function getInstance() {
		if ( !self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function activate() {
		$wpfr_version = "0.2";
		add_option( 'wpfr_version', $wpfr_version );
	}

	public function deactivate() {
		if( get_option('wpfr_version') ) {
			delete_option( 'wpfr_version' );
		}
	}
	
} /* END OF CLASS */

// Instantiate our class
$wpfReloaded = wpfrMain::getInstance();

?>

<?php
/*
 * Wordpress Plugin Framework Reloaded 0.2
 * Library file to be included by other plugins
 */
if (!class_exists('WordpressPluginFrameworkReloaded')) {
	/**
	 * Abstract class WordpressPluginFrameworkReloaded used as a WordPress Plugin framework
	 *
	 * @abstract
	 */
	abstract class WordpressPluginFrameworkReloaded {


		/**
		 * @var string  Menu Sections
		 */
		protected $PARENT_MENU_DASHBOARD = "index.php";
		protected $PARENT_MENU_WRITE = "post-new.php";
		protected $PARENT_MENU_MANAGE = "edit.php";
		protected $PARENT_MENU_PAGES = "edit-pages.php";
		protected $PARENT_MENU_COMMENTS = "edit-comments.php";
   		protected $PARENT_MENU_BLOGROLL = "link-manager.php";
   		protected $PARENT_MENU_PRESENTATION = "themes.php";
   		protected $PARENT_MENU_PLUGINS = "plugins.php";
   		protected $PARENT_MENU_USERS = "users.php";
   		protected $PARENT_MENU_OPTIONS = "options-general.php";
   
		/**
		 * @var string Plugin parent menu
		 */
		protected $_menuHook = 'options-general.php';

		/**
		 * @var string Your own Plugin parent menu name.
		 */
		protected $_selfMainMenu = '';

		/**
		 * @var array Plugin settings
		 */
		protected $_settings;

		/**
		 * @var string - The options page name used in the URL
		 */
		protected $_hook = '';

		/**
		 * @var string - The filename for the main plugin file
		 */
		protected $_file = '';

		/**
		 * @var string - The directory where the plugin is
		 */
		protected $_pluginDir = '';

		/**
		 * @var string - The application icon id on CSS. Ex: 'wpfr' for '#icon-wpfr'
		 */
		 protected $_appIcon = '';

		/**
		 * @var string - The main menu icon filename
		 */
		 protected $_appIconFile = '';
		
		/**
		 * @var string - The Options page logo 32px filename
		 */
		protected $_appLogoFile = ''; 

		/**
		 * @var string - The options page title
		 */
		protected $_pageTitle = '';

		/**
		 * @var string - The options page menu title
		 */
		protected $_menuTitle = '';

		/**
		 * @var string - The access level required to see the options page
		 */
		protected $_accessLevel = '';

		/**
		 * @var string - The option group to register
		 */
		protected $_optionGroup = '';

		/**
		 * @var array - An array of options to register to the option group
		 */
		protected $_optionNames = array();

		/**
		 * @var array - An associated array of callbacks for the options, option name should be index, callback should be value
		 */
		protected $_optionCallbacks = array();

		/**
		 * @var string - The plugin slug used on WordPress.org and/or WordpressPluginFrameworkReloaded forums
		 */
		protected $_slug = '';

		/*
		 * @var string - The HTML text for the about app box, if using own menu
		 */
		protected $_aboutText = ''; 

		/**
		 * @var string - The button ID for the PayPal button, override this generic one with a plugin-specific one
		 */
		protected $_donationID = '';

		/**
		 * @var string - The button ID for the PayPal button, override this generic one with a plugin-specific one
		 */
		protected $_wishlistID = '';
		
		/**
		 * @var string - Contact for hiring the developer. Can be http: or mailto: addresses
		 */
		protected $_contactURL  = '';
		
		/**
		 * @var array - Values for the Dashboard Widget
		 */
		protected $_dashboardWidget = array();
		
		/**
		 * @var array - Should show Latest News Sidebars or not
		 */
		protected $_sidebarNews = array(  true, true );

		/**
		 * This is our constructor, which is private to force the use of getInstance()
		 * @return void
		 */
		protected function __construct() {
			if ( is_callable( array($this, '_init') ) ) {
				$this->_dashboardWidget = array( 'inc' => 'iPublicis!COM',	
																		  'url' => 'http://w3.ipublicis.com/',
																		  'rss' => 'http://w3.ipublicis.com/rss.xml', 
																		  'ico' => plugin_dir_url( __FILE__ ).'/images/ipublicis-logo-32.png'  );
				$this->_init();
			}
			$this->_getSettings();
			if ( is_callable( array($this, '_postSettingsInit') ) ) {
				$this->_postSettingsInit();
			}
			add_filter( 'init', array( $this, 'init_locale' ) );
			add_action( 'admin_init', array( $this, 'registerOptions' ) );
			add_filter( 'plugin_action_links', array( $this, 'addPluginPageLinks' ), 10, 2 );
			
			add_action( 'admin_menu', array( $this, 'registerOptionsPage' ) );
			if ( is_callable(array( $this, 'addOptionsMetaBoxes' )) ) {
				add_action( 'admin_init', array( $this, 'addOptionsMetaBoxes' ) );
			}

			if ( !empty($this->_selfMainMenu) ) { 
				add_action( 'admin_menu', array( $this, 'registerAboutPage' ) );
				add_action( 'admin_init', array( $this, 'addAboutMetaBoxes' ) );
			}

			add_action( 'admin_init', array( $this, 'addDefaultSidebarMetaBoxes' ) );
			add_action( 'wp_dashboard_setup', array( $this, 'addDashboardWidgets' ), null, 9 );
			add_action( 'admin_print_scripts', array( $this,'optionsPageScripts' ) );
			add_action( 'admin_print_styles', array( $this,'optionsPageStyles' ) );
			/**
			 * Add update messages that can be attached to the CURRENT release (not
			 * this one), but only for 2.8+
			 */
			global $wp_version;
			if ( version_compare('2.8', $wp_version, '<=') ) {
				add_action ( 'in_plugin_update_message-'.$this->_file , array ( $this , 'changelog' ), null, 2 );
			}
			
		}

		/**
		 * Function to instantiate our class and make it a singleton
		 */
		abstract public static function getInstance();

		public function init_locale() {
			$lang_dir = '/i18n';
			$wpfr_dir = 'wordpress-plugin-framework-reloaded/';
			load_plugin_textdomain( $this->_slug, WP_PLUGIN_DIR . '/' . $this->_pluginDir . $lang_dir, $this->_pluginDir . $lang_dir);
			load_plugin_textdomain( 'framework-reloaded', WP_PLUGIN_DIR . '/' . $wpfr_dir . $lang_dir, $wpfr_dir . $lang_dir);
		}

		protected function _getSettings() {
			foreach ( $this->_optionNames as $opt ) {
				$this->_settings[$opt] = apply_filters($this->_slug.'-opt-'.$opt, get_option($opt));
			}
		}

		public function registerOptions() {
			foreach ( $this->_optionNames as $opt ) {
				if ( !empty($this->_optionCallbacks[$opt]) && is_callable( $this->_optionCallbacks[$opt] ) ) {
					$callback = $this->_optionCallbacks[$opt];
				} else {
					$this->_optionCallbacks[$opt] = '';
				}
				register_setting( $this->_optionGroup, $opt, $callback );
			}
		}

		public function unregisterOptions() { // unset options
			foreach ( $this->_optionNames as $opt ) {
				if ( !empty($this->_optionCallbacks[$opt]) && is_callable( $this->_optionCallbacks[$opt] ) ) {
					$callback = $this->_optionCallbacks[$opt];
				} else {
					$this->_optionCallbacks[$opt] = '';
				}
				echo $this->_optionGroup.': '.$opt.' = '.$callback;
				unregister_setting( $this->_optionGroup, $opt );
			}
		}

		/**
		 * Update message, used in the admin panel to show messages to users.
		 */
		public function _optionMessage($message) {
			$notice = "<div id=\"message\" class=\"updated fade\"><p>".$message."</p></div>\n";
			add_action('admin_notices', create_function( '', "echo '$notice';" ) );
		}

		public function aboutThisApplication () {
			require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

			$plugin = plugins_api( 'plugin_information', array( 'slug' => $this->_slug ) );

			if ( !$plugin || is_wp_error( $plugin ) || empty( $plugin->sections['description'] ) ) {
				return;
			}

			$aboutit = $plugin->sections['description'];

			$replace = array(
				'<ul>'	=> '<ul style="list-style: disc inside; padding-left: 15px; font-weight: normal;">',
				'translation made by' => __("translation made by", 'framework-reloaded'),
				'<h4>'	=> '<h4 style="margin-bottom:0;">',
			);

			return str_replace( array_keys($replace), $replace, $aboutit );

		}

		public function translators () {
			require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

			$plugin = plugins_api( 'plugin_information', array( 'slug' => $this->_slug ) );

			if ( !$plugin || is_wp_error( $plugin ) || empty( $plugin->sections['other_notes'] ) ) {
				return;
			}

			$translators = $plugin->sections['other_notes'];

			$pos = strpos( $translators, '<h4>' . preg_replace('/[^\+\.]/', '', "Credits for present translations" ) );
			if ( $pos !== false ) {
				$changes = trim( substr( $translators, $pos + strlen( "<h4>Credits for present translations</h4>" ) ) );
			}

			$changes = "<p style='padding-left: 8px;'>" . __("We would like to thank to the following volunteers without whom we would not have this plugin available for a broader public. Thank you.", 'framework-reloaded') . "</p>" . $changes;

			$replace = array(
				'<ul>'	=> '<ul style="list-style: disc inside; padding-left: 20px; font-weight: normal;">',
				'translation made by' => __("translation made by", 'framework-reloaded'),
//				'<h4>'	=> '<h4 style="margin-bottom:0;">',
			);

			return str_replace( array_keys($replace), $replace, $changes );

		}

		public function changelog ($pluginData, $newPluginData) {
			require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

			$plugin = plugins_api( 'plugin_information', array( 'slug' => $newPluginData->slug ) );

			if ( !$plugin || is_wp_error( $plugin ) || empty( $plugin->sections['changelog'] ) ) {
				return;
			}

			$changes = $plugin->sections['changelog'];
			$pos = strpos( $changes, '<h4>' . preg_replace('/[^\d\.]/', '', $pluginData['Version'] ) );
			if ( $pos !== false ) {
				$changes = trim( substr( $changes, 0, $pos ) );
			}

			$replace = array(
				'<ul>'	=> '<ul style="list-style: disc inside; padding-left: 15px; font-weight: normal;">',
				'<h4>'	=> '<h4 style="margin-bottom:0;">',
			);
			echo str_replace( array_keys($replace), $replace, $changes );
		}

		public function registerOptionsPage() {
			
			if ( $current_user->ID == 1 || current_user_can( $this->_accessLevel ) ) {
				if ( is_callable( array( $this, 'options_page' ) ) ) {
					if( !empty( $this->_selfMainMenu ) ) {
					
						if(function_exists(add_object_page)) { //
							add_object_page( 	$this->_pageTitle, $this->_menuTitle,  $this->_accessLevel, 
															$this->_hook. '-main', '', 
															plugin_dir_url( __FILE__ ) . $this->_appIconFile );
						} else {
							add_menu_page (	$this->_pageTitle, $this->_menuTitle, $this->_accessLevel, 
															$this->_hook. '-main', '', 
															plugin_dir_url( __FILE__ ) . $this->_appIconFile ); 
						}
						add_submenu_page(	$this->_hook. '-main', 
															sprintf( __('%s Settings', 'framework-reloaded'), $this->_pageTitle),  
															__('Settings', 'framework-reloaded'), $this->_accessLevel, 
															$this->_hook . '-main', array( $this, 'options_page' ) ); 
					} else {
						switch( $this->_menuHook ) {  // Default  
							case $this->PARENT_MENU_MANAGE : 
								add_management_page( $this->_pageTitle, $this->_menuTitle, $this->_accessLevel, 
															$this->_hook . '-options', array( $this, 'options_page' ), $this->_appLogoFile ); 
															break;
							case $this->PARENT_MENU_WRITE : 
								add_posts_page(	$this->_pageTitle, $this->_menuTitle, $this->_accessLevel, 
															$this->_hook . '-options', array( $this, 'options_page' ), $this->_appLogoFile ); 
															break;
							case $this->PARENT_MENU_PAGES : 
								add_pages_page( $this->_pageTitle, $this->_menuTitle, $this->_accessLevel, 
															$this->_hook . '-options', array( $this, 'options_page' ), $this->_appLogoFile ); 
															break;
							case $this->PARENT_MENU_COMMENTS : 
								add_comments_page(	$this->_pageTitle, $this->_menuTitle, $this->_accessLevel, 
															$this->_hook . '-options', array( $this, 'options_page' ), $this->_appLogoFile ); 
															break;
							case $this->PARENT_MENU_BLOGROLL : 
								add_blogroll_page(	$this->_pageTitle, $this->_menuTitle, $this->_accessLevel, 
															$this->_hook . '-options', array( $this, 'options_page' ), $this->_appLogoFile ); 
															break;
							case $this->PARENT_MENU_PRESENTATION : 
								add_theme_page(	$this->_pageTitle, $this->_menuTitle, $this->_accessLevel, 
															$this->_hook . '-options', array( $this, 'options_page' ), $this->_appLogoFile ); 
															break;
							case $this->PARENT_MENU_USERS : 
								add_users_page(	$this->_pageTitle, $this->_menuTitle, $this->_accessLevel, 
															$this->_hook . '-options', array( $this, 'options_page' ), $this->_appLogoFile ); 
															break;
							default : 
								add_submenu_page( $this->_menuHook, $this->_menuTitle, $this->_pageTitle, $this->_accessLevel, 
															$this->_hook . '-options', array( $this, 'options_page' ) ); 
						}
					}
				}
			}
		}

		public function registerAboutPage() {
			
			if ( $current_user->ID == 1 || current_user_can( $this->_accessLevel ) ) {
				if( !empty( $this->_selfMainMenu ) && is_callable( array( $this, 'about_page' ) ) ) {
					add_submenu_page(	$this->_hook. '-main', __('About ', 'framework-reloaded') . $this->_pageTitle, 
														__('Overview', 'framework-reloaded'), $this->_accessLevel, 
														$this->_hook. '-about-us', array( $this, 'about_page' ) );  // Default 
				}
			}
		}

		protected function _filterBoxesMain($boxName) {
			if ( 'main' == strtolower($boxName) ) {
				return false;
			}
			return $this->_filterBoxesHelper($boxName, 'main');
		}

		protected function _filterBoxesSidebar($boxName) {
			return $this->_filterBoxesHelper($boxName, 'sidebar');
		}

		protected function _filterBoxesHelper($boxName, $test) {
			return ( strpos( strtolower($boxName), strtolower($test) ) !== false );
		}

		public function options_page() {
			global $wp_meta_boxes;
			$allBoxes = array_keys( $wp_meta_boxes[$this->_slug] );
			$mainBoxes = array_filter( $allBoxes, array( $this, '_filterBoxesMain' ) );
			unset($mainBoxes['main']);
			sort($mainBoxes);
			if( empty( $this->_selfMainMenu ) ) { $mainWidth = "75"; } else { $mainWidth = "99"; }
			?>
				<div class="wrap">
					<?php screen_icon($this->_appIcon); // $this->screenIconLink($this->_appIcon); ?>
					<h2><?php echo esc_html($this->_pageTitle); ?></h2>
					<div class="metabox-holder">
						<div class="postbox-container" style="width:<?php echo $mainWidth; ?>%;">
							<form action="options.php" method="post">
								<?php settings_fields( $this->_optionGroup ); ?>
								<?php do_meta_boxes( $this->_slug, 'main', '' ); ?>
								<p class="submit">
									<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Update Options &raquo;', 'framework-reloaded'); ?>" />
								</p>
							</form>
							<?php
							foreach( $mainBoxes as $context ) {
								do_meta_boxes( $this->_slug, $context, '' );
							}
							?>
						</div>
			<?php
			if( empty( $this->_selfMainMenu ) ) {
				$sidebarBoxes = array_filter( $allBoxes, array( $this, '_filterBoxesSidebar' ) );
				unset($sidebarBoxes['sidebar']);
				sort($sidebarBoxes);
			?>
						<div class="postbox-container" style="width:24%;">
							<?php
							foreach( $sidebarBoxes as $context ) {
								do_meta_boxes( $this->_slug, $context, '' );
							}
							?>
						</div>
			<?php } ?>
					</div>
				</div>
				<?php
				if( $_GET['updated'] == 'true' ) {
					$notice = "<div id='message' class='updated fade'><p>".__('Settings updated.', 'framework-reloaded')."</p></div>\n";
					echo $notice; 
				}
		}

		public function about_page() {
			global $wp_meta_boxes;
			$allBoxes = array_keys( $wp_meta_boxes[$this->_slug . '-about-us'] );
			$mainBoxes = array_filter( $allBoxes, array( $this, '_filterBoxesMain' ) );
			unset($mainBoxes['main']);
			sort($mainBoxes);
			$allBoxes = array_keys( $wp_meta_boxes[$this->_slug] );
			$sidebarBoxes = array_filter( $allBoxes, array( $this, '_filterBoxesSidebar' ) );
			unset($sidebarBoxes['sidebar']);
			sort($sidebarBoxes);
			?>
				<div class="wrap">
					<?php screen_icon($this->_appIcon); // $this->screenIconLink($this->_appIcon); ?>
					<h2><?php echo esc_html($this->_pageTitle) . ' - ' . __('Overview', 'framework-reloaded'); ?></h2>
					<div class="metabox-holder">
						<div class="postbox-container" style="width:75%;">
							<?php do_meta_boxes( $this->_slug . '-about-us', 'main', '' ); ?>
							<?php 
							foreach( $mainBoxes as $context ) {
								do_meta_boxes( $this->_slug . '-about-us', $context, '' );
							}
							?>
						</div>
						<div class="postbox-container" style="width:24%;">
							<?php
							foreach( $sidebarBoxes as $context ) {
								do_meta_boxes( $this->_slug, $context, '' );
							}
							?>
						</div>
					</div>
				</div>
				<?php
		}

		public function addPluginPageLinks( $links, $file ){
			if ( $file == $this->_file ) {
				// Add Widget Page link to our plugin
				$link = $this->getOptionsLink();
				array_unshift( $links, $link );

				// Add Support Forum link to our plugin
				$link = $this->getSupportForumLink();
				array_unshift( $links, $link );
			}
			return $links;
		}

		public function getSupportForumLink( $linkText = '' ) {
			if ( empty($linkText) ) {
				$linkText = __( 'Support Forum', 'framework-reloaded' );
			}
			return '<a href="' . $this->getSupportForumUrl() . '">' . $linkText . '</a>';
		}

		public function getSupportForumUrl($app = '') {
			if( empty($app) ) { $app = untrailingslashit( $this->_pluginDir ); } else { $app = 'wordpress-plugin-framework-reloaded'; }
			return 'http://wordpress.org/tags/' . $app. '?forum_id=10#postform';
		}

		public function getOptionsLink( $linkText = '' ) {
			if ( empty($linkText) ) {
				$linkText = __( 'Settings', 'framework-reloaded' );
			}
			return '<a href="' . $this->getOptionsUrl() . '">' . $linkText . '</a>';
		}

		public function getOptionsUrl() {
			return admin_url( $this->_menuHook . '?page=' . $this->_hook );
		}

		public function optionsPageStyles() {
			if (isset($_GET['page']) && $_GET['page'] == $this->_hook) {
				wp_enqueue_style('dashboard');
				wp_enqueue_style('wpfr-options-css', WP_PLUGIN_URL . '/wordpress-plugin-framework-reloaded/framework-reloaded.css');
				wp_enqueue_style($this->_slug.'-options-css', plugin_dir_url( __FILE__ ) . 'authorbox_admin_css');
			}
		}

		public function addAboutMetaBoxes() {
			// add_meta_box('id', 'title', 'callback', 'page', 'context', 'priority');
			add_meta_box( $this->_slug . '-about-us', __('About ', 'framework-reloaded') . $this->_pageTitle, array($this, 'aboutUsMetaBox'), $this->_slug . '-about-us', 'main');
			add_meta_box( $this->_slug . '-feed', __('Plugin Latest News.', 'framework-reloaded'), array($this, 'appFeedMetaBox'),  $this->_slug.'-about-us', 'main');
			add_meta_box( $this->_slug . '-about-wpfr', __('About Wordpress Plugin Framework Reloaded', 'framework-reloaded'), array($this, 'aboutWPFRMetaBox'), $this->_slug . '-about-us', 'main');
		}

		public function aboutUsMetaBox() {
			$wpOrgInfo = $this->aboutThisApplication();
			echo '<table class="form-table"><tr valign="top"><th>' . __($wpOrgInfo, $this->_slug) . '</th></tr>';
			echo '<tr><td>' . __($this->_aboutText, $this->_slug) . '</td></tr></table>';
		}

		public function aboutWPFRMetaBox() {
			$support	= $this->getSupportForumUrl('wordpress-plugin-framework-reloaded');
			$appUrl 	= 'http://wordpress.org/extend/plugins/wordpress-plugin-framework-reloaded';
			$wpUsers = 'http://profiles.wordpress.org/';
			$otherShoudlers =  __('This project was possible due to the previous work of ', 'framework-reloaded').
											'<a href="' . $wpUsers . 'aaroncampbell" target="_blank">Aaron Campbell</a>, '.
											'<a href="' . $wpUsers . 'delayedinsanity" target="_blank">Mark Waterous</a>, '.
											'<a href="' . $wpUsers . 'husterk" target="_blank">Husterk</a>, '.
											'<a href="' . $wpUsers . 'leonardomartinez" target="_blank">Leonardo Martinez</a>, '.
											'<a href="' . $wpUsers . 'jdevalk" target="_blank">Joost De Valk</a>, '.
											'<a href="' . $wpUsers . 'ozh" target="_blank">Ozh</a> '.
											__('and so many others. Thank you all and also a special <em>thank you</em> to all the Wordpress developers.','framework-reloaded');
			$wpfrStr 	= '<table class="form-table"><tr valign="top"><th><strong>Wordpress Plugin Framework Reloaded</strong> '.
								__( 'was developed by', 'framework-reloaded' ).
								' <a href="http://w3.ipublicis.com/category/wordpress/wpfr" target="_blank">iPublicis!COM</a> '.
								__( 'and released under the GNU LGPL license, version 3.', 'framework-reloaded' ).
								'<p>' . $otherShoudlers . '</p></th></tr></table>';
			_e( $wpfrStr, 'framework-reloaded');
		}

		public function appFeedMetaBox() {
			$args = array(
				'url'	=> $this->_appFeed,
				'items'	=> '5',
			);
			echo '<table class="form-table"><tr valign="top"><tr><td><div class="rss-widget">';
			wp_widget_rss_output( $args );
			echo "</div></td></tr></table>";
		}

		public function addDefaultSidebarMetaBoxes() {
			add_meta_box( $this->_slug . '-like-this', __('Do you like this Plugin?', 'framework-reloaded'), array($this, 'likeThisMetaBox'), $this->_slug, 'sidebar');
			add_meta_box( $this->_slug . '-support', __('Need Support?', 'framework-reloaded'), array($this, 'supportMetaBox'), $this->_slug, 'sidebar');
			add_meta_box( $this->_slug . '-translators', __('Translators Credits', 'framework-reloaded'), array($this, 'translatorsMetaBox'), $this->_slug, 'sidebar');
			if( empty( $this->_selfMainMenu ) ) {
				if( $this->_sidebarNews[0] ) 
					add_meta_box( $this->_slug . '-feed', __('Plugin Latest News.', 'framework-reloaded'), array($this, 'appFeedMetaBox'),  $this->_slug, 'sidebar');
				if( $this->_sidebarNews[1] ) 
					add_meta_box( $this->_slug . '-wpfr-feed', __('WPFR Latest News.', 'framework-reloaded'), array($this, 'wpfrFeedMetaBox'),  $this->_slug, 'sidebar');
			}
			add_meta_box( $this->_slug . '-help-us', __('Help Us', 'framework-reloaded'), array($this, 'helpUsMetaBox'), $this->_slug, 'sidebar');
		}

		public function helpUsMetaBox() {
			echo '<a name="' . $this->_slug . '-help-us-info' . '"></a><p style="padding-left: 8px;">';
			$url = "http://plugins.trac.wordpress.org/browser/" . $this->_slug . "/trunk/i18n/" . $this->_slug . ".pot";
            echo sprintf(__("You too can help. Just <a href='%s' target='_blank'>dowload</a> the .pot file included and send your translation back to us. We just need the resulting .po and .mo files. Thanks!", 'framework-reloaded'), $url) . '</p>';
			echo '<p style="padding-left: 8px;">';
			echo __('More at ', 'framework-reloaded') . '<a href="http://codex.wordpress.org/I18n_for_WordPress_Developers" target="_blank">I18n for WordPress_Developers</a>.<br />';
			echo __('Please, use something like ', 'framework-reloaded') . '<a href="http://www.poedit.net/" target="_blank">POEdit</a>.';
			echo '</p>';
		}

		public function translatorsMetaBox() {
			$i18n = $this->translators();
			if( !empty( $i18n  ) ) echo $i18n;
		}


		public function likeThisMetaBox() {
			echo '<p style="padding-left: 8px;">';
			_e("We spend a lot of effort on Free Software development. Any help would be highly appreciated. Thanks!", 'framework-reloaded');
			echo '</p><ul>';

			$url = apply_filters('wpfr-plugin-url-'.$this->_slug, 'http://wordpress.org/extend/plugins/' . $this->_pluginDir);
			echo "<li style='padding-left: 38px; background:transparent url(".plugin_dir_url( __FILE__ )."images/icon-promote.png ) no-repeat scroll center left; background-position: 16px 50%; text-decoration: none;'><a href='{$url}' target='_blank'>";
			_e('Link to it. Others may use it.', 'framework-reloaded');
			echo "</a></li>";

			$url = 'http://wordpress.org/extend/plugins/' . untrailingslashit( $this->_pluginDir ) ;
			echo "<li style='padding-left: 38px; background:transparent url(".plugin_dir_url( __FILE__ )."images/icon-rating.png ) no-repeat scroll center left; background-position: 16px 50%; text-decoration: none;'><a href='{$url}' target='_blank'>";
			_e('Give it a good rating on WordPress.org.', 'framework-reloaded');
			echo "</a></li>";

			$url = 'http://smsh.me/' . $this->_donationID;
			echo "<li style='padding-left: 38px; background:transparent url(".plugin_dir_url( __FILE__ )."images/icon-paypal.gif ) no-repeat scroll center left; background-position: 16px 50%; text-decoration: none;'><a href='{$url}' target='_blank'>";
			_e("Donate something to our team.", 'framework-reloaded');
			echo "</a></li>";

			$url = 'http://www.amazon.co.uk/registry/wishlist/' . $this->_wishlistID;
			echo "<li style='padding-left: 38px; background:transparent url(".plugin_dir_url( __FILE__ )."images/icon-amazon.gif ) no-repeat scroll center left; background-position: 16px 50%; text-decoration: none;'><a href='{$url}' target='_blank'>";
			_e("Send us a gift to show your appreciation.", 'framework-reloaded');
			echo "</a></li>";

			$url = '#' . $this->_slug . '-help-us-info';
			echo "<li style='padding-left: 38px; background:transparent url(".plugin_dir_url( __FILE__ )."images/icon-translate.png ) no-repeat scroll center left; background-position: 16px 50%; text-decoration: none;'><a href='{$url}'>";
			_e("Help us translating it.", 'framework-reloaded');
			echo "</a></li>";

			echo '</ul>';
		}

		public function supportMetaBox() {
			echo '<p style="padding-left: 8px;">';
			_e('If you have any problems with this plugin or ideas for improvements or enhancements, please use them...', 'framework-reloaded');
			echo '</p><ul>';

			$url = $this->getSupportForumUrl();
			echo "<li style='padding-left: 38px; background:transparent url(".plugin_dir_url( __FILE__ )."images/icon-wordpress.png ) no-repeat scroll center left; background-position: 16px 50%; text-decoration: none;'><a href='{$url}' target='_blank'>";
			_e('Support Forums.', 'framework-reloaded');
			echo "</a></li>";

			$url = "http://plugins.trac.wordpress.org/query?status=new&status=assigned&status=reopened&component=" . untrailingslashit( $this->_pluginDir ) . "&order=priority";
			echo "<li style='padding-left: 38px; background:transparent url(".plugin_dir_url( __FILE__ )."images/icon-trac.gif ) no-repeat scroll center left; background-position: 16px 50%; text-decoration: none;'><a href='{$url}' target='_blank'>";
			_e('Report a Bug.', 'framework-reloaded');
			echo "</a></li>";

			if( !empty( $this->_contactURL ) ) {
				$url = $this->_contactURL;
				echo "<li style='padding-left: 38px; background:transparent url(".plugin_dir_url( __FILE__ )."images/icon-hire.png ) no-repeat scroll center left; background-position: 16px 50%; text-decoration: none;'><a href='{$url}' target='_blank'>";
				_e('We are available for hire!', 'framework-reloaded');
				echo "</a></li>";
			}

			echo '</ul>';
		}

		public function wpfrFeedMetaBox() {
			$args = array(
				'url'	=> 'http://w3.ipublicis.com/category/wordpress/wpfr/feed',
				'items'	=> '5',
			);
			echo '<table class="form-table"><tr valign="top"><tr><td><div class="rss-widget">';
			wp_widget_rss_output( $args );
			echo "</div></td></tr></table>";
		}

		public function addDashboardWidgets() {
			$title = sprintf( __('%s Latest News', 'framework-reloaded'),  $this->_dashboardWidget['inc'] );
			wp_add_dashboard_widget( 'dashboardb_wpfr' , $title , array( $this, 'dashboardWidget' ) );
		}

		public function dashboardWidget() {
			$args = array(
				'url'						=> $this->_dashboardWidget['rss'],
				'items'					=> '3',
				'show_date'			=> 1,
				'show_summary'	=> 1,
			);
			echo '<div class="rss-widget">';
			echo '<a href="' . $this->_dashboardWidget['url'] . '"><img class="alignright" src="'. 
						$this->_dashboardWidget['ico'] . '" /></a>';
			wp_widget_rss_output( $args );
			echo '<p style="border-top: 1px solid #CCC; padding-top: 10px; font-weight: bold;">';
			echo '<a href="' . $this->_dashboardWidget['rss'] . '"><img src="'
                             .get_bloginfo('wpurl').'/wp-includes/images/rss.png" alt=""/> '.__('Subscribe with RSS', 'framework-reloaded').'</a>';
			echo "</p>";
			echo "</div>";
		}

		public function screenIconLink($name = '') {
			echo '<a href="' . $this->_dashboardWidget['url'] . '">';
			screen_icon($name);
			echo '</a>';
		}

		public function optionsPageScripts() {
			if (isset($_GET['page']) && $_GET['page'] == $this->_hook) {
				wp_enqueue_script('postbox');
				wp_enqueue_script('dashboard');
			}
		}
	}
}

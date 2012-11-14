<div id="header" class="container">
    <div class="margin clearfix">
	   <h2 id="logo">
			<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
				<?php 
				$sitelogo = prima_get_option( 'newsitelogo' );
				if (!$sitelogo) {
					$oldsitelogo = prima_get_option( 'sitelogo' );
					if ( $oldsitelogo && $oldsitelogo['url'] !== '' ) $sitelogo = $oldsitelogo['url'];
				}
				if ($sitelogo) echo '<img src="'.$sitelogo.'" alt="'.get_bloginfo( 'name' ).'" />';
				else echo get_bloginfo( 'name' );
				?>
			</a>
		</h2>
        <div id="topnav" role="navigation">
         <?php if ( has_nav_menu( 'primary' ) ) : ?>
            <?php wp_nav_menu( array( 'menu_class' => 'superfish', 'theme_location' => 'primary' ) ); ?>
         <?php else : ?>
            <ul class="superfish">
            <li><a href="<?php echo get_option('home'); ?>/"><?php _e('Home',PRIMA_DOMAIN); ?></a></li>
            	<?php 
				$exclude = ( (get_option('show_on_front') == 'page') && get_option('page_on_front') ) ? '&exclude='.get_option('page_on_front') : '';
            	wp_list_pages('title_li=&sort_column=menu_order'.$exclude); ?>
            </ul>
         <?php endif; ?>
        </div>
    </div>
</div>

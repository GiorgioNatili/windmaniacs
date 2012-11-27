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
		<?php include("mynav.php");?>
    </div>
    <div id="search-key-words">
        <form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
            <input type="text" size="put_a_size_here" name="s" id="s" placeholder="Search" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/>
        </form>
    </div>
</div>

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
</div>

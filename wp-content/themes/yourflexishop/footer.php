<div id="adsense" style="text-align: center;">
	<script type="text/javascript"><!--
	google_ad_client = "ca-pub-1022629305995486";
	/* Wind Maniacs Horizontal */
	google_ad_slot = "8216634837";
	google_ad_width = 728;
	google_ad_height = 90;
	//-->
	</script>
	<script type="text/javascript"
	src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
</div>
<?php if ( prima_get_option('footertop') || prima_get_option('footerbottom') ) : ?>
<div id="footer" class="container">
	<div class="margin">
		<?php 
		if ( prima_get_option('footertop') )
			get_sidebar( 'footer-top' ); 
		if ( prima_get_option('footerbottom') )
			get_sidebar( 'footer-bottom' ); 
		?>
	</div>
</div>
<?php endif; ?>

</div><!-- close #flexi-wrapper -->

<div id="copyright" class="container">
	<div class="margin">
		<?php $copyright = prima_get_option('copyright');
        if ( !$copyright ) $copyright = __( 'Copyright &copy; 2011 &middot; WP FlexiShop', PRIMA_DOMAIN );
        echo wpautop( $copyright ); ?>
	</div>
</div>

<?php if ( prima_get_option('topcategories') || prima_get_option('topcart') ) : ?>
<div id="top-header">
    <div class="margin">
        <div id="top-header-nav">
			<?php if(prima_get_option('topcart')&&class_exists('WP_eCommerce')) get_template_part( 'flexi-topbar-cart' ); ?>
			<?php if(prima_get_option('topcategories')&&class_exists('WP_eCommerce')) get_template_part( 'flexi-topbar-categories' ); ?>
        </div>
    </div>
</div>
<?php endif; ?>
</div> <!-- end shadow -->
<?php wp_footer(); ?>
</body>
</html>
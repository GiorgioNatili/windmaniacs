<?php if ( !empty($query) ) : ?>
<div id="productspage-featured-slider">
<div id="featured-slider">
	<div id="feature-wrapper">
	<div id="features">
		<ul class="feature-list">
		<?php
			$width_product = prima_get_option('sliderproductwidth') ? prima_get_option('sliderproductwidth') : 500;
			$width = (prima_get_option('themelayout') == 'boxed') ? 896 : 980;
			$height = prima_get_option('sliderheight') ? prima_get_option('sliderheight') : 375;
			foreach ( $query as $product ) :
				prima_slider_product($product->ID, $width_product, $height);
			endforeach;
		?>
		</ul>
	</div>
	</div>
</div>
<?php if (count($query) > 1) : ?>
	<div id="slider-controls"></div>
<?php endif; ?>
</div>
<?php endif; ?>
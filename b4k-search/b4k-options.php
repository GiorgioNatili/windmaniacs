<div class="wrap">
	<h2>Bid4Kite options search</h2>
	<form action="" method="post">
		<?php if (function_exists('wp_nonce_field')){
				wp_nonce_field('b4k-updatesettings');
			} 
		?>
		<table>
			<tr>
				<th><label for="b4k-search-text">Display text in Search box</label></th>
				<td><input type="text" name="b4k-search-text" value="<?php echo $current_settings['b4k-search-text'] ?>"/></td>
			</tr>
			<tr>
				<th><label for="b4k-hide-empty">Hide terms with no product associated</label></th>
				<td><input type="checkbox" name="b4k-hide-empty" value="1" <?php ($current_settings['b4k-hide-empty'] == '1') ? print 'checked="checked"' : FALSE  ?>/></td>
			</tr>
			<tr>
				<td><input type="submit" name="b4k-settings-submit" value="Invia" /></td>
			</tr>
		</table>
	</form>
</div>
<div class="wrap">
	<h2>Windmaniacs options search</h2>
	<form action="" method="post">
		<?php if (function_exists('wp_nonce_field')){
				wp_nonce_field('wdm-updatesettings');
			}
		?>
		<table>
			<tr>
				<th><label for="wdm-search-text">Display text in Search box</label></th>
				<td><input type="text" name="wdm-search-text" value="<?php echo $current_settings['wdm-search-text'] ?>"/></td>
			</tr>
			<tr>
				<th><label for="wdm-hide-empty">Hide terms with no product associated</label></th>
				<td><input type="checkbox" name="wdm-hide-empty" value="1" <?php ($current_settings['wdm-hide-empty'] == '1') ? print 'checked="checked"' : FALSE  ?>/></td>
			</tr>
			<tr>
				<td><input type="submit" name="wdm-settings-submit" value="Invia" /></td>
			</tr>
		</table>
	</form>
</div>

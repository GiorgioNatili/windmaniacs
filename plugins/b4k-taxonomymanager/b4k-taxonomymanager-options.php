<div class="wrap">
	<h2>Custom Taxonomy Manager</h2>
	<h3>Create a new custom Taxonomy</h3>
	<form action="" method="post">
		<?php if (function_exists('wp_nonce_field')){
				wp_nonce_field('b4k-taxonomy-updatesettings');
			}
		?>
		<table class="form-table">
			<tr>
				<th><label for="b4ktm-slug">Taxonomy machine name</label></th>
				<td>b4ktm-<input type="text" name="b4ktm-slug" value=""/></td>
			</tr>
			<tr>
				<th><label for="b4ktm-label">Taxonomy Label</label></th>
				<td><input type="text" name="b4ktm-label" value=""/></td>
			</tr>
			<tr>
				<td><input type="submit" name="b4ktm-submit" value="Create taxonomy" class="button-secondary"/></td>
			</tr>
		</table>
	</form>
</div>
<?php if (!empty($b4ktm_taxonomies)): ?><div class="wrap">
	<h3>List of all custom taxonomies</h3>
	<form action="" method="post">
		<table class="form-table">
			<thead>
				<tr>
					<th>Select</th>
					<th>Label</th>
					<th>Slug</th>
					<th>Enabled</th>
			  </tr>
			</thead>
			<tbody>
				<?php foreach ($b4ktm_taxonomies as $taxonomy): ?>
				<tr>
					<td><input type="checkbox" name="b4ktm-id[]" value="<?php print $taxonomy->id ?>"/></td>
					<td><?php print $taxonomy->label ?></td>
					<td><?php print $taxonomy->slug ?></td>
					<td><?php ($taxonomy->enabled == '1') ? print 'enabled' : print 'disabled' ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
		<label for="name">Action</label>
		<select name="b4ktm-action" id="b4ktm-action" size="1">
			<option value="0">Disable</option>
			<option value="1">Enable</option>
			<option value="2">Delete</option>
		</select>
		<input type="submit" name="b4ktm-update" value="Update" class="button-secondary"/>
	</form>
</div>
<?php endif ?>

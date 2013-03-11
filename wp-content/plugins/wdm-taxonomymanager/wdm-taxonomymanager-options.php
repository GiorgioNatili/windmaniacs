<div class="wrap">
  <h2>Custom Taxonomy Manager</h2>
  <h3>Create a new custom Taxonomy</h3>
  <form action="" method="post">
    <?php if (function_exists('wp_nonce_field')){
        wp_nonce_field('wdm-taxonomy-updatesettings');
      }
    ?>
    <table class="form-table">
      <tr>
        <th><label for="wdm-slug">Taxonomy machine name</label></th>
        <td>wdm-<input type="text" name="wdm-slug" value=""/></td>
      </tr>
      <tr>
        <th><label for="wdm-label">Taxonomy Label</label></th>
        <td><input type="text" name="wdm-label" value=""/></td>
      </tr>
      <tr>
        <td><input type="submit" name="wdm-submit" value="Create taxonomy" class="button-secondary"/></td>
      </tr>
    </table>
  </form>
</div>
<?php if (!empty($wdm_taxonomies)): ?><div class="wrap">
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
        <?php foreach ($wdm_taxonomies as $taxonomy): ?>
        <tr>
          <td><input type="checkbox" name="wdm-id[]" value="<?php print $taxonomy->id ?>"/></td>
          <td><?php print $taxonomy->label ?></td>
          <td><?php print $taxonomy->slug ?></td>
          <td><?php ($taxonomy->enabled == '1') ? print 'enabled' : print 'disabled' ?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <label for="name">Action</label>
    <select name="wdm-action" id="wdm-action" size="1">
      <option value="0">Disable</option>
      <option value="1">Enable</option>
      <option value="2">Delete</option>
    </select>
    <input type="submit" name="wdm-update" value="Update" class="button-secondary"/>
  </form>
</div>
<?php endif ?>

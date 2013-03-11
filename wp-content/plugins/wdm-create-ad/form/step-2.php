<h2>Main category</h2>
<select name="wdm-category">
  <option value=""><?php _e('Please select'); ?></option>
<?php foreach ($custom_taxonomies as $taxonomy):?>
  <option value="<?php print $taxonomy->slug; ?>"><?php print $taxonomy->label; ?></option>
<?php endforeach?>
</select>

<h2>Sub category</h2>
<?php foreach ($variable_select as $term): ?>
  <?php print wdm_form_select($term->taxonomy, $term->id, $term->name, $term->taxonomy, $term->term_id, FALSE, TRUE); ?>
<?php endforeach?>

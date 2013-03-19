<h2>Main category</h2>
<select name="wdm-category">
  <option value=""><?php _e('Please select'); ?></option>
<?php foreach ($custom_taxonomies as $taxonomy):?>
  <option value="<?php print $taxonomy->slug; ?>" <?php print wdm_create_ad_is_default($taxonomy->slug, $default_value['wdm-category']) ?>><?php print $taxonomy->label; ?></option>
<?php endforeach?>
</select>

<fieldset class="post-category">
<h2>Sub category</h2>
<?php foreach ($variable_select as $term): ?>
  <?php print wdm_form_select($term->taxonomy, $term->id, $term->name, $term->taxonomy, $term->term_id, FALSE, TRUE); ?>
<?php endforeach?>
</fieldset>

<fieldset class="post-description">
<?php foreach ($languages as $langcode): ?>
  <fieldset class="language-<?php  print $langcode; ?>">
    <label><?php print $langcode; ?></label>

    <label for="wdm-title-<?php  print $langcode; ?>"><?php _e("Title"); ?></label>
    <input type="text" name="wdm-title-<?php print $langcode; ?>" value="<?php print $default_value['wdm-title-' . $langcode]; ?>"/>

    <label for="wdm-description-<?php  print $langcode; ?>"><?php _e("Description"); ?></label>
    <input type="text" name="wdm-description-<?php print $langcode; ?>" value="<?php print $default_value['wdm-description-' . $langcode]; ?>"/>
  </fieldset>
<?php endforeach; ?>
</fieldset>

<label for="wdm-price"><?php _e("Price"); ?></label>
<input type="text" name="wdm-price" value="<?php print $default_value['wdm-price']; ?>"/>

<label for="wdm-sale_price"><?php _e("Sale price"); ?></label>
<input type="text" name="wdm-sale_price" value="<?php print $default_value['wdm-sale_price']; ?>"/>

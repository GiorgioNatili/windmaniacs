<?php foreach ($languages as $langcode): ?>
<fieldset>
  <label><?php _e($langcode); ?></label>

  <label for="wdm-title[<?php  print $langcode; ?>]"><?php _e("Title"); ?></label>
  <input type="text" name="wdm-title[<?php print $langcode; ?>]" />

  <label for="wdm-description[<?php  print $langcode; ?>]"><?php _e("Description"); ?></label>
  <input type="text" name="wdm-description[<?php print $langcode; ?>]" /></label>

  <label for="wdm-price[<?php  print $langcode; ?>]"><?php _e("Price"); ?></label>
  <input type="text" name="wdm-price[<?php print $langcode; ?>]" /></label>

  <label for="wdm-sale_price[<?php  print $langcode; ?>]"><?php _e("Sale price"); ?></label>
  <input type="text" name="wdm-sale_price[<?php print $langcode; ?>]" /></label>
</fieldset>
<?php endforeach; ?>

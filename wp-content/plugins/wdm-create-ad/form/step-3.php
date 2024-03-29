<div id="stepcondition">
  <div id="selectcondition" class="select">
    <label for="wdm-condiction"><?php _e("Please select Condiction", 'qtranslate'); ?></label>
    <select name="wdm-condiction">
      <option value=""><?php _e('Condition', 'qtranslate'); ?></option>
    <?php foreach ($condictions as $value => $label):?>
      <option value="<?php print $value; ?>" <?php print wdm_create_ad_is_default($value, $default_value['wdm-condiction']) ?>><?php print $label; ?></option>
    <?php endforeach?>
    </select>
  </div>

  <div id="selectyear" class="select">
    <label for="wdm-year"><?php _e("Year", 'qtranslate'); ?></label>
    <select name="wdm-year">
      <option value=""><?php _e('Please select Year', 'qtranslate'); ?></option>
    <?php foreach ($years as $value => $label):?>
      <option value="<?php print $value; ?>" <?php print wdm_create_ad_is_default($value, $default_value['wdm-year']) ?>><?php print $label; ?></option>
    <?php endforeach?>
    </select>
  </div>

  <div id="selectstyle" class="select">
    <label for="wdm-style"><?php _e("Style", 'qtranslate'); ?></label>
    <select name="wdm-style">
      <option value=""><?php _e('Please select style', 'qtranslate'); ?></option>
    <?php foreach ($styles as $value => $label):?>
      <option value="<?php print $value; ?>" <?php print wdm_create_ad_is_default($value, $default_value['wdm-style']) ?>><?php print $label; ?></option>
    <?php endforeach?>
    </select>
  </div>
</div>

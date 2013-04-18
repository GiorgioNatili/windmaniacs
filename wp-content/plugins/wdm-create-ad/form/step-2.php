<!-- <h2>Main category</h2> -->
<div id="selectabletags" class="select">

  <select id="wdm-ad-main-category" name="wdm-category" class="selected">
    <option value=""><?php _e('Please select your product category'); ?></option>
  <?php foreach ($custom_taxonomies as $taxonomy):?>
    <option value="<?php print $taxonomy->slug; ?>" <?php print wdm_create_ad_is_default($taxonomy->slug, $default_value['wdm-category']) ?>><?php print $taxonomy->label; ?></option>
  <?php endforeach?>
  </select>
</div>

<div id="dynamic-select">
  <fieldset class="post-category">
  <!-- <h2>Sub category</h2> -->
  <?php foreach ($variable_select as $term): ?>
    <?php print wdm_form_select($term->taxonomy, $term->id, $term->name, $term->taxonomy, $term->term_id, FALSE, TRUE); ?>
  <?php endforeach?>
  </fieldset>
</div>

<fieldset class="post-description">
<div id="inputdati-sx">
  <label for="wdm-price"><?php _e("Price"); ?></label>
  <input type="text" name="wdm-price" value="<?php print $default_value['wdm-price']; ?>"  onblur="if(this.value=='')this.value=this.defaultValue;" onfocus="if(this.value==this.defaultValue)this.value='';" placeholder="Prezzo" name="s" size="put_a_size_here" class="input-left"/>

  <label for="wdm-sale_price"><?php _e("Sale price"); ?></label>
  <input type="text" name="wdm-sale_price" value="<?php print $default_value['wdm-sale_price']; ?>"  onblur="if(this.value=='')this.value=this.defaultValue;" onfocus="if(this.value==this.defaultValue)this.value='';" placeholder="Prezzo scontato" name="s" size="put_a_size_here" class="input-left"/>
</div>


<div id="tabscontainer">
        <ul>
          <?php foreach ($languages as $langcode): ?>
                <li><a href="#tabs-<?php  print $langcode; ?>"><?php  print ucwords($langcode); ?></a></li>
          <?php endforeach; ?>
        </ul>

        <span class="clear" ></span>
        <?php foreach ($languages as $langcode): ?>
        <div id="tabs-<?php  print $langcode; ?>">
          <fieldset class="language-<?php  print $langcode; ?>">
            <!--<label><?php print $langcode; ?></label>-->
            <label for="wdm-title-<?php  print $langcode; ?>"><?php _e("Title"); ?></label>
            <input id="titolo" type="text" onblur="if(this.value=='')this.value=this.defaultValue;" onfocus="if(this.value==this.defaultValue)this.value='';" placeholder="<?php _e("Insert Title"); ?>" name="wdm-title-<?php print $langcode; ?>" value="<?php print $default_value['wdm-title-' . $langcode]; ?>" class="input-left" />

            <label for="wdm-description-<?php  print $langcode; ?>"><?php _e("Description"); ?></label>
            <textarea name="wdm-description-<?php print $langcode; ?>" value="<?php print $default_value['wdm-description-' . $langcode]; ?>" placeholder="<?php _e('Insert Description'); ?>" class="text-area-right" onKeyDown="limitText(this.form.desc,this.form.countdown,500);" onKeyUp="limitText(this.form.desc,this.form.countdown,500);"></textarea>
          </fieldset>
        </div>
        <?php endforeach; ?>
</div>

</fieldset>

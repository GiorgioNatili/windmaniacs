<form id="wdm-create-ad-<?php print $step; ?>" class="wdm-create-ad" method="post" <?php print $form_extra_attr?>>
<div id="spinvariablecontainer"></div>
<div class="formcarbonbackground">
  <div id="stepnavbar">
    <ul>
      <li id="step1" class="<?php print ($step == 1) ? 'active' : 'off'; ?>"><span class="step"> <?php _e('Step', 'qtranslate'); ?>n&deg;1</span><br />
        <span class="descriptionstep"> <?php _e('Choose the category', 'qtranslate'); ?></span>
      </li>
      <li id="step2" class="<?php print ($step == 2) ? 'active' : 'off'; ?>"><span class="step"> <?php _e('Step', 'qtranslate'); ?> n&deg;2</span><br />
        <span class="descriptionstep"><?php _e('Select one or more subcategories and enter the details of the announcement', 'qtranslate'); ?></span>
      </li>
      <li id="step3" class="<?php print ($step == 3) ? 'active' : 'off'; ?>"><span class="step"><?php _e('Step', 'qtranslate'); ?> n&deg;3</span><br />
        <span class="descriptionstep"><?php _e('Declares a state of use', 'qtranslate'); ?> <br /><?php _e('Your product for sale', 'qtranslate'); ?></span>
      </li>
      <li id="step4" class="<?php print ($step == 4) ? 'active' : 'off'; ?>"><span class="step"><?php _e('Step', 'qtranslate'); ?> n&deg;4</span><br />
        <span class="descriptionstep"><?php _e('Upload up to 5 images', 'qtranslate'); ?></span>
      </li>
      <li id="step5" class="<?php print ($step == 5) ? 'active' : 'off'; ?>"><span class="step"><?php _e('Step', 'qtranslate'); ?> n&deg;5</span><br />
        <span class="descriptionstep"><?php _e('Public announcement', 'qtranslate'); ?></span>
      </li>
    </ul>
  </div>

  <?php include("step-$step.php"); ?>

  <?php foreach($hidden_value as $name => $value): ?>
    <input type="hidden" name="<?php print $name; ?>" value="<?php print $value; ?>">
  <?php endforeach; ?>

  <input type="hidden" name="wdm_create_ad_form_step" value="<?php print $step; ?>" />
  </div>

  <?php if ($step > 1 && $step  < 5): ?>
    <input type="submit" name="action_prev" class="downstepnavigator"  value="<?php print _e('Step', 'qtranslate') . ' ' . ($step - 1); ?>" /></button>
  <?php endif; ?>
  <?php if ($button_current): ?>
    <input type="submit" name="submit" class="currentstepnavigator" value="Submit" />
  <?php endif; ?>
  <?php if ($step < 5): ?>
    <input type="submit" class="upstepnavigator" name="action_next"   value="<?php print _e('Step', 'qtranslate') . ' ' . ($step + 1); ?>"></button>
  <?php endif; ?>
</form>

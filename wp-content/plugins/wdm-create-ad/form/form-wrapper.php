<form id="wdm-create-ad-<?php print $step; ?>" class="wdm-create-ad" method="post" <?php print $form_extra_attr?>>

  <?php include("step-$step.php"); ?>

  <?php foreach($hidden_value as $name => $value): ?>
    <input type="hidden" name="<?php print $name; ?>" value="<?php print $value; ?>">
  <?php endforeach; ?>

  <input type="hidden" name="wdm_create_ad_form_step" value="<?php print $step; ?>" />

  <?php if ($step > 1 && $step  < 5): ?>
    <input type="submit" name="action_prev"   value="<?php print _('Step') . ' ' . ($step - 1); ?>" /></button>
  <?php endif; ?>
  <?php if ($button_current): ?>
    <input type="submit" name="submit"        value="Submit" />
  <?php endif; ?>
  <?php if ($step < 5): ?>
    <input type="submit" name="action_next"   value="<?php print _('Step') . ' ' . ($step + 1); ?>"></button>
  <?php endif; ?>
</form>

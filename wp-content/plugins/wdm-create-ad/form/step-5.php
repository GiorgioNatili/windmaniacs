<?php if (is_user_logged_in()): ?>
  <h2><?php print _('Congratulation, your annunce will be publish as fast as possible.') ?></h2>
<?php else: ?>
  <h2><a href="<?php print site_url('wp-login.php?redirect_to=' . $destination_url, 'login') ?>"><?php print _('Already a windmaniacs'); ?></a></h2>
  <h2><a href="<?php print site_url('wp-login.php?action=register&redirect_to=' . $destination_url, 'login') ?>"><?php print _('Still not a windmaniac'); ?></a></h2>
<?php endif; ?>

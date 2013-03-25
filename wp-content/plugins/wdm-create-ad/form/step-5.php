<?php if (is_user_logged_in()): ?>
  <?php wp_redirect('http://www.google.com/'); exit; ?>
<?php else: ?>
  <h2><a href="<?php print site_url('wp-login.php?redirect_to=' . $destination_url, 'login') ?>"><?php print _('Already a windmaniacs'); ?></a></h2>
  <h2><a href="<?php print site_url('wp-login.php?action=register&redirect_to=' . $destination_url, 'login') ?>"><?php print _('Still not a windmaniac'); ?></a></h2>
<?php endif; ?>

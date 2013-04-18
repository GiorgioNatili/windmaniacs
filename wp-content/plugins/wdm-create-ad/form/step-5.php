<div id="buttonarea">
    <?php if (is_user_logged_in()): ?>
      <h2><?php print _('Congratulation, your annunce will be publish as fast as possible.') ?></h2>
    <?php else: ?>
      <a href="<?php print site_url('wp-login.php?redirect_to=' . $destination_url, 'login') ?>" id="already">
        <h2>ALREADY A <br />WINDMANIAC?</h2>
        <h4>(LOGIN AND PUBLISH YOUR ADV)</h4>
      </a>

      <a href="<?php print site_url('wp-login.php?action=register&redirect_to=' . $destination_url, 'login') ?>" id="stillnot">
        <h2>STILL NOT A <br />WINDMANIAC?</h2>
        <h4>(CONNECT WITH YOUR FACEBOOK OR TWITTER ACCOUNT)</h4>
      </a>
    <?php endif; ?>
</div>

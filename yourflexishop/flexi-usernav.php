<div id="user-nav">
    <div class="margin <?php if ( prima_get_option('topcategories') ) echo 'topcategories-active' ?> <?php if ( prima_get_option('topcart') ) echo 'topcart-active' ?>">
     <?php echo qtrans_generateLanguageSelectCode('text','my_custom_language'); ?>
     <ul>
        <?php if ( is_user_logged_in() ) : ?>
            <li class="no"><a class="black" href="<?php echo wp_logout_url(get_option('home')); ?>"><?php _e( 'Logout', PRIMA_DOMAIN ); ?></a></li>
            <li><a href="<?php echo get_option('user_account_url'); ?>"><?php _e( 'Your Account', PRIMA_DOMAIN ); ?></a></li>
        <?php else : ?>
            <li><a href="<?php echo get_option('home'); ?>/wp-login.php?action=register"><?php _e( 'Register', PRIMA_DOMAIN ); ?></a></li>
            <li class="no"><a href="<?php echo get_option('user_account_url'); ?>"><?php _e( 'Sign In', PRIMA_DOMAIN ); ?></a></li>
        <?php endif; ?>
    </ul>
   
    </div>
</div>

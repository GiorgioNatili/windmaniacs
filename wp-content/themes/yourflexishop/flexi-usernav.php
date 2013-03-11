<div id="user-nav">
    <div class="margin <?php if ( prima_get_option('topcategories') ) echo 'topcategories-active' ?> <?php if ( prima_get_option('topcart') ) echo 'topcart-active' ?>">
        <div id="social-connect">
            <a href="https://www.facebook.com/Windmaniacs?fref=ts" alt="" class="social-connect-fb" target="_blank"></a>
            <a href="https://twitter.com/windmaniacs" alt="" class="social-connect-tw" target="_blank"></a>
            <a href="http://pinterest.com/windmaniacs/" alt="" class="social-connect-pt" target="_blank"></a>
        </div>
        <div id="languages">
            <span class="lang-sx"></span>
            <?php echo qtrans_generateLanguageSelectCode('text','my_custom_language'); ?>
            <span class="lang-dx"></span>
        </div>
        <div id="log-in">
            <ul>
                <?php if ( is_user_logged_in() ) : ?>
                    <li class="no"><a class="black" href="<?php echo wp_logout_url(get_option('home')); ?>"><?php _e( 'Logout', PRIMA_DOMAIN ); ?></a></li>
                    <li>|</li>
                    <li><a href="<?php echo get_option('user_account_url'); ?>?edit_profile=true"><?php _e( 'Your Account', PRIMA_DOMAIN ); ?></a></li>
                <?php else : ?>
                    <li><a href="<?php echo get_option('home'); ?>/wp-login.php?action=register"><?php _e( 'Register', PRIMA_DOMAIN ); ?></a></li>
                    <li>|</li>
                    <li class="no"><a href="<?php echo get_option('user_account_url'); ?>"><?php _e( 'Sign In', PRIMA_DOMAIN ); ?></a></li>
                <?php endif; ?>
            </ul>
       </div>
    </div>
</div>

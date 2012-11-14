<?php
/**
 * The User Account Theme.
 *
 * Displays everything within the user account.  Hopefully much more useable than the previous implementation.
 *
 * @todo This basically shows 'screens' for each of the following: Purchase History, Your Details, Downloads.  Could argue that these should be separate templates.
 *
 * @package WPSC
 * @since WPSC 3.8
 */
global $files, $separator, $purchase_log, $col_count, $products, $links; ?>

<div class="wrap">
	<div class="user-profile-links">
		<a href="<?php echo get_option( 'user_account_url' ); ?>"><?php _e('Purchase History',PRIMA_DOMAIN); ?></a> |
		<a href="<?php echo get_option( 'user_account_url' ) . $separator . "edit_profile=true"; ?>"><?php _e('Your Details',PRIMA_DOMAIN); ?></a> |
		<a href="<?php echo get_option( 'user_account_url' ) . $separator . "downloads=true"; ?>"><?php _e('Your Downloads',PRIMA_DOMAIN); ?></a>
		<?php do_action('wpsc_additional_user_profile_links', '|'); ?>
	</div>

	<br />
	<!-- 	START OF PROFILE PAGE -->
	<?php if ( is_wpsc_profile_page() ) : ?>

		<form method="post">

			<?php echo validate_form_data(); ?>

			<table>

				<?php wpsc_display_form_fields(); ?>

				<tr>
					<td></td>
					<td>
						<input type="hidden" value="true" name="submitwpcheckout_profile" />
						<input type="submit" value="<?php _e( 'Save Profile', PRIMA_DOMAIN ); ?>" name="submit" />
					</td>
				</tr>
			</table>
		</form>
	<!-- 	START OF DOWNLOADS PAGE -->
	<?php elseif ( is_wpsc_downloads_page() ) : ?>

		<?php if ( wpsc_has_downloads() ) : ?>

				<table class="logdisplay">
					<tr>
						<th><?php _e( 'File Names', PRIMA_DOMAIN ); ?> </th>
						<th><?php _e( 'Downloads Left', PRIMA_DOMAIN ); ?> </th>
						<th><?php _e( 'Date', PRIMA_DOMAIN ); ?> </th>
					</tr>

					<?php
						$i = 0;
						foreach ( (array)$files as $file ) :

							$alternate = "";

							if ( ( $i % 2 ) != 1 )
								$alternate = "class='alt'";
					?>

							<tr <?php echo $alternate; ?>>
								<td>
					<?php
						if ( $products[$i]['downloads'] > 0 )
							echo "<a href = " . $links[$i] . ">" . $file['post_title'] . "</a>";
						else
							echo $file['post_title'] . "";

					?>

								</td>
								<td><?php echo $products[$i]['downloads']; ?></td>
								<td><?php echo date( get_option( "date_format" ), strtotime( $products[$i]['datetime'] ) ); ?></td>
							</tr>
					<?php
							$i++;
						endforeach;
					?>

				</table>
		<?php else : ?>

			<?php _e( 'You have not purchased any downloadable products yet.', PRIMA_DOMAIN ); ?>

		<?php endif; ?>
	<!-- 	START OF PURCHASE HISTORY PAGE -->
	<?php else : ?>
		
		<?php if ( is_user_logged_in() ) : ?>

			<?php if ( wpsc_has_purchases() ) : ?>

				<table class="logdisplay">

				<?php if ( wpsc_has_purchases_this_month() ) : ?>
					
						<tr class="toprow">
							<td><strong><?php _e( 'Status', PRIMA_DOMAIN ); ?></strong></td>
							<td><strong><?php _e( 'Date', PRIMA_DOMAIN ); ?></strong></td>
							<td><strong><?php _e( 'Price', PRIMA_DOMAIN ); ?></strong></td>

							<?php if ( get_option( 'payment_method' ) == 2 ) : ?>

								<td><strong><?php _e( 'Payment Method', PRIMA_DOMAIN ); ?></strong></td>

							<?php endif; ?>

						</tr>

						<?php wpsc_user_details(); ?>

				<?php else : ?>

						<tr>
							<td colspan="<?php echo $col_count; ?>">

								<?php _e( 'No transactions for this month.', PRIMA_DOMAIN ); ?>

							</td>
						</tr>

				<?php endif; ?>

				</table>

			<?php else : ?>

				<table>
					<tr>
						<td><?php _e( 'There have not been any purchases yet.', PRIMA_DOMAIN ); ?></td>
					</tr>
				</table>

			<?php endif; ?>

		<?php else : ?>

			<?php _e( 'You must be logged in to use this page. Please use the form below to login to your account.', PRIMA_DOMAIN ); ?>

			<form name="loginform" id="loginform" action="<?php bloginfo('url'); ?>/wp-login.php" method="post">
				<p>
					<label><?php _e( 'Username:', PRIMA_DOMAIN ); ?><br /><input type="text" name="log" id="log" value="" size="20" tabindex="1" /></label>
				</p>

				<p>
					<label><?php _e( 'Password:', PRIMA_DOMAIN ); ?><br /><input type="password" name="pwd" id="pwd" value="" size="20" tabindex="2" /></label>
				</p>

				<p>
					<label>
						<input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="3" />
						<?php _e( 'Remember me', PRIMA_DOMAIN ); ?>
					</label>
				</p>

				<p class="submit">
					<input type="submit" name="submit" id="submit" value="<?php _e( 'Login &raquo;', PRIMA_DOMAIN ); ?>" tabindex="4" />
					<input type="hidden" name="redirect_to" value="<?php echo get_option( 'user_account_url' ); ?>" />
				</p>
			</form>

		<?php endif; ?>

	<?php endif; ?>

</div>

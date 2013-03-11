<?php
/*
Template Name: Contact
*/
if(isset($_POST['prima_contact_submit'])) {
	if(trim($_POST['prima_contact_name']) === '') {
		$error_name = __( 'Please enter your name.', PRIMA_DOMAIN );
		$error_form = true;
	}
	else {
		$send_name = trim($_POST['prima_contact_name']);
	}
	if(trim($_POST['prima_contact_email']) === '')  {
		$error_email = __( 'Please enter your email address.', PRIMA_DOMAIN );
		$error_form = true;
	} 
	elseif (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['prima_contact_email']))) {
		$error_email = __( 'You entered an invalid email address.', PRIMA_DOMAIN );
		$error_form = true;
	} 
	else {
		$send_email = trim($_POST['prima_contact_email']);
	}
	if(trim($_POST['prima_contact_message']) === '') {
		$error_messages = __( 'Please enter a message.', PRIMA_DOMAIN );
		$error_form = true;
	} 
	else {
		if(function_exists('stripslashes'))
			$send_messages = stripslashes(trim($_POST['prima_contact_message']));
		else
			$send_messages = trim($_POST['prima_contact_message']);
	}
	if(!isset($error_form)) {
		$send_address = get_option('prima_admin_email');
		if (!isset($send_address) || ($send_address == '') ){
			$send_address = get_option('admin_email');
		}
		$send_subject = sprintf( __('[Contact Form] From %s', PRIMA_DOMAIN), $send_name );
		$send_body = "".__('Name:', PRIMA_DOMAIN)." $send_name \n\n".__('Email:', PRIMA_DOMAIN)." $send_email \n\n".__('Messages:', PRIMA_DOMAIN).": $send_messages";
		$send_headers = 'From: '.$send_name.' <'.$send_address.'>' . "\r\n" . 'Reply-To: ' . $send_email;
		$sent_status = mail($send_address, $send_subject, $send_body, $send_headers);
		if (isset($sent_status) && $sent_status )
			$email_sent = true;
		else
			$email_sent_error = true;
	}
} 
get_header(); ?>

<div id="header-wrapper">
	<?php get_template_part( 'flexi-header' ); ?>
    <div id="leader" class="container">
        <div class="margin clearfix">
			<?php if (class_exists('WP_eCommerce') && prima_get_option('searchformpage')) prima_product_search(); ?>
            <h1>
			<?php wp_title(); ?>
            </h1>
        </div>
    </div>
</div>

<div id="content-wrapper">
	<div id="checkout" class="row container">
		<div class="margin">
			<?php if(isset($email_sent) && $email_sent == true) { ?>
				<div class="success"><?php _e('Thanks, your email was sent successfully.', PRIMA_DOMAIN) ?></div>
			<?php } elseif(isset($email_sent_error) && $email_sent_error == true) { ?>
				<div class="notice"><?php _e('Sorry, your email was not sent. Please try again!', PRIMA_DOMAIN) ?></div>
			<?php } else { ?>
				<?php if(isset($error_form)) { ?>
					<div class="error"><?php _e('Sorry, an error occured.', PRIMA_DOMAIN) ?></div>
				<?php } ?>
			<?php } ?>
			<div class="col-2">
				<form action="<?php the_permalink(); ?>" id="contactForm" method="post" class="clearfix">
					<p><label for="prima_contact_name"><?php _e('Name:', PRIMA_DOMAIN) ?></label>
						<input type="text" name="prima_contact_name" id="prima_contact_name" value="<?php if(isset($_POST['prima_contact_name'])) echo $_POST['prima_contact_name'];?>" class="required requiredField" />
						<?php if($error_name != '') { ?>
							<span class="contactform-error"><?php echo $error_name; ?></span> 
						<?php } ?>
					</p>
					<p><label for="prima_contact_email"><?php _e('Email:', PRIMA_DOMAIN) ?></label>
						<input type="text" name="prima_contact_email" id="prima_contact_email" value="<?php if(isset($_POST['prima_contact_email']))  echo $_POST['prima_contact_email'];?>" class="required requiredField email" />
						<?php if($error_email != '') { ?>
							<span class="contactform-error"><?php echo $error_email; ?></span>
						<?php } ?>
					</p>
					<p class="textarea"><label for="prima_contact_message"><?php _e('Messages:', PRIMA_DOMAIN) ?></label>
						<textarea name="prima_contact_message" id="prima_contact_message" rows="20" cols="30" class="required requiredField"><?php if(isset($_POST['prima_contact_message'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['prima_contact_message']); } else { echo $_POST['prima_contact_message']; } } ?></textarea>
						<?php if($error_messages != '') { ?>
							<span class="contactform-error"><?php echo $error_messages; ?></span> 
						<?php } ?>
					</p>
					<input type="hidden" name="prima_contact_submit" id="prima_contact_submit" value="true" />
					<button name="submit" type="submit" id="prima_contact_button" tabindex="5"><?php _e('Send Email', PRIMA_DOMAIN) ?></button>
				</form>
			</div>
			<div class="col-2 col-right">
				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>			
					<?php the_content(); ?>
				<?php endwhile; ?>
			</div>
			<br style="clear: both" />
		</div>
	</div>
</div>

<?php get_footer(); ?>
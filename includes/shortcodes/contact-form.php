<?php
/**
 * Contact Form Shortcode
 *
 * Displays a contact form.
 *
 * @package Forest Assistant
 * @subpackage Forest
 */
function forest_contact_form_sc() {

	$nameError         = __( 'Please enter your name.', 'stag' );
	$emailError        = __( 'Please enter your email address.', 'stag' );
	$emailInvalidError = __( 'You entered an invalid email address.', 'stag' );
	$commentError      = __( 'Please enter a message.', 'stag' );

	$errorMessages = array();

	if ( isset( $_POST['submitted'] ) ) {
		if ( trim( $_POST['contactName'] ) === '' ) {
			$errorMessages['nameError'] = $nameError;
			$hasError                   = true;
		} else {
			$name = trim( $_POST['contactName'] );
		}

		if ( trim( $_POST['email'] ) === '' ) {
			$errorMessages['emailError'] = $emailError;
			$hasError                    = true;
		} elseif ( ! is_email( trim( $_POST['email'] ) ) ) {
			$errorMessages['emailInvalidError'] = $emailInvalidError;
			$hasError                           = true;
		} else {
			$email = trim( $_POST['email'] );
		}

		if ( trim( $_POST['comments'] ) === '' ) {
			$errorMessages['commentError'] = $commentError;
			$hasError                      = true;
		} else {
			if ( function_exists( 'stripslashes' ) ) {
				$comments = stripslashes( trim( $_POST['comments'] ) );
			} else {
				$comments = trim( $_POST['comments'] );
			}
		}

		if ( ! isset( $hasError ) ) {
			$emailTo = forest_get_thememod_value( 'forest_contact_email' );
			if ( ! isset( $emailTo ) || ( $emailTo == '' ) ) {
				$emailTo = get_option( 'admin_email' );
			}
			$subject = '[Contact Form] From ' . $name;

			$body  = "Name: $name \n\nEmail: $email \n\nMessage: $comments \n\n";
			$body .= "--\n";
			$body .= 'This mail is sent via contact form on ' . get_bloginfo( 'name' ) . "\n";
			$body .= home_url();

			$headers = 'From: ' . $name . ' <' . $email . '>' . "\r\n" . 'Reply-To: ' . $email;

			wp_mail( $emailTo, $subject, $body, $headers );
			$emailSent = true;
		}
	}

	?>
	<?php if ( isset( $emailSent ) && $emailSent === true ) : ?>

<div class="stag-alert green">
	<p><?php _e( 'Thanks, your email was sent successfully.', 'stag' ); ?></p>
</div>

<?php else : ?>

<form action="<?php the_permalink(); ?>" id="contactForm" class="contact-form" method="post">

	<hr class="stag-divider plain">

	<h2><?php _e( 'Send us a Message', 'stag' ); ?></h2>

	<div class="grids">
		<p class="grid-6">
			<label for="contactName"><?php _e( 'Your Name', 'stag' ); ?></label>
			<input type="text" name="contactName" id="contactName" value="<?php if ( isset( $_POST['contactName'] ) ) { echo $_POST['contactName']; } ?>">
			<?php if ( isset( $errorMessages['nameError'] ) ) { ?>
				<span class="error"><?php echo $errorMessages['nameError']; ?></span>
			<?php } ?>
		</p>

		<p class="grid-6">
			<label for="email"><?php _e( 'Your Email', 'stag' ); ?></label>
			<input type="text" name="email" id="email" value="<?php if ( isset( $_POST['email'] ) ) { echo $_POST['email']; } ?>">
			<?php if ( isset( $errorMessages['emailError'] ) ) { ?>
				<span class="error"><?php echo $errorMessages['emailError']; ?></span>
			<?php } ?>
			<?php if ( isset( $errorMessages['emailInvalidError'] ) ) { ?>
				<span class="error"><?php echo $errorMessages['emailInvalidError']; ?></span>
			<?php } ?>
		</p>
	</div>

	<p class="commentsText">
		<label for="commentsText"><?php _e( 'Your Message', 'stag' ); ?></label>
		<textarea rows="8" name="comments" id="commentsText"><?php
			if ( isset( $_POST['comments'] ) ) {
				if ( function_exists( 'stripslashes' ) ) {
					echo stripslashes( $_POST['comments'] );
				} else {
					echo $_POST['comments'];
				}
			} ?></textarea>
		<?php if ( isset( $errorMessages['commentError'] ) ) { ?>
			<span class="error"><?php echo $errorMessages['commentError']; ?></span>
		<?php } ?>
	</p>

	<p class="buttons">
		<input type="submit" id="submitted" class="contact-form-button" name="submitted" value="<?php _e( 'Send Message', 'stag' ); ?>">
	</p>
</form>

	<?php
endif;
}
add_shortcode( 'forest_contact_form', 'forest_contact_form_sc' );

<?php
// Customizes the post password field

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

function outputPostPasswordForm( $output, $post ) {
	$loginurl = \site_url() . '/wp-login.php?action=postpass';
	$label    = 'pwbox-' . ( ! empty( $post->ID ) ? $post->ID : \base64_encode( \random_bytes( 8 ) ) ); ?>
	<div class="compact-form compact-password-form">
		<form class="compact-form" method="POST" action="<?php echo $loginurl; ?>">
			<p>
				This content is password protected. To view it, please enter your password below:
			</p>
			<label for="<?php echo $label; ?>" class="screen-reader-text">Password</label>
			<div class="compact-form--inside_wrapper">
				<input type="password" name="post_password" id="<?php echo $label; ?>"  aria-label="Password" spellcheck="false">
				<button type="submit" class="has-icon" aria-label="Submit">
					<svg height="24" viewBox="0 0 24 24" width="24">
						<path d="m19.2 4v16h-7.4v-1.5h5.9v-13h-5.9v-1.5zm-5 8.3c.1-.2.1-.4 0-.6 0-.1-.1-.2-.2-.2l-4.1-4.2c-.3-.3-.8-.3-1.1 0s-.3.8 0 1.1l2.9 2.9h-6.9c-.4-.1-.8.3-.8.7s.3.8.8.8h6.9l-2.9 2.9c-.3.3-.3.8 0 1.1.1.1.3.2.5.2s.4-.1.5-.2l4.2-4.2c.1-.1.2-.2.2-.3z"/>
					</svg>
				</button>
			</div>
		</form>
	</div>
	<?php
}
\add_filter( 'the_password_form', ns( 'outputPostPasswordForm' ), 9999, 2 );

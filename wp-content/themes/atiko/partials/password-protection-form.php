<?php
/**
 * Custom WordPress password protection form output
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add label based on post ID
$post  = get_post( $post );
$label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );

// Main classes
$classes = 'password-protection-box clr';

// Add container for full-screen layout to center it
if ( 'full-screen' == wpex_content_area_layout() ) {
	$classes .= ' container';
} ?>

<div class="<?php echo esc_attr( $classes ); ?>">
	<form action="<?php echo esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ); ?>" method="post">
		<h2><?php esc_html_e( 'Password Protected', 'total' ); ?></h2>
		<p><?php esc_html_e( 'This content is password protected. To view it please enter your password below:', 'total' ); ?></p>
		<input name="post_password" id="<?php echo esc_attr( $label ); ?>" type="password" size="20" maxlength="20" placeholder="<?php esc_attr_e( 'Password', 'total' ); ?>" /><input type="submit" name="Submit" value="<?php esc_attr_e( 'Submit', 'total' ); ?>" />
	</form>
</div>
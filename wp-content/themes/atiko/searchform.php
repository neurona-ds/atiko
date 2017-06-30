<?php
/**
 * The template for displaying search forms
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Placeholder
$placeholder = apply_filters( 'wpex_mobile_searchform_placeholder', __( 'Search', 'total' ), 'main' ); ?>

<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>"<?php wpex_aria_landmark( 'searchform' ); ?>>
	<label>
		<span class="screen-reader-text"><?php echo esc_html( $placeholder ); ?></span>
		<input type="search" class="field" name="s" placeholder="<?php echo esc_attr( $placeholder ); ?>" />
	</label>
	<?php if ( defined( 'ICL_LANGUAGE_CODE' ) ) : ?>
		<input type="hidden" name="lang" value="<?php echo( ICL_LANGUAGE_CODE ); ?>"/>
	<?php endif; ?>
	<button type="submit" class="searchform-submit"><span class="fa fa-search" aria-hidden="true"></span><span class="screen-reader-text"><?php esc_html_e( 'Submit', 'total' ); ?></span></button>
</form>
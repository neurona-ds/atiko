<?php
/**
 * Header Logo
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define variables
$logo_url   = wpex_header_logo_url();
$logo_img   = wpex_header_logo_img();
$logo_icon  = wpex_header_logo_icon();
$logo_title = wpex_header_logo_title();

// Overlay Header logo (make sure overlay header is enabled first)
$overlay_logo = wpex_has_overlay_header() ? wpex_overlay_header_logo_img() : '';

// Get corret logo image dimensions
$dims = '';
if ( $logo_img ) {
	$width  = wpex_header_logo_img_width();
	$height = wpex_header_logo_img_height();
	$dims   = ( $width && $height ) ? ' width="' . $width . '" height="' . $height . '"' : '';
} ?>

<div id="site-logo" class="<?php echo esc_attr( wpex_header_logo_classes() ); ?>">
	<div id="site-logo-inner" class="clr">
		<?php if ( $logo_img || $overlay_logo ) : ?>
			<?php
			// Custom site-wide image logo
			if ( $logo_img && ! $overlay_logo ) : ?>
				<a href="<?php echo esc_url( $logo_url ); ?>" title="<?php echo esc_attr( $logo_title ); ?>" rel="home" class="main-logo"><img src="<?php echo esc_url( $logo_img ); ?>" alt="<?php echo esc_attr( $logo_title ); ?>" class="logo-img" data-no-retina<?php echo $dims; ?> /></a>
			<?php endif; ?>
			<?php
			// Custom header-overlay logo => Must be added on it's own HTML. IMPORTANT!
			if ( $overlay_logo ) : ?>
				<a href="<?php echo esc_url( $logo_url ); ?>" title="<?php echo esc_attr( $logo_title ); ?>" rel="home" class="overlay-header-logo"><img src="<?php echo esc_url( $overlay_logo ); ?>" alt="<?php echo esc_attr( $logo_title ); ?>" class="logo-img" data-no-retina<?php echo $dims; ?> /></a>
			<?php endif; ?>
		<?php else : ?>
			<a href="<?php echo esc_url( $logo_url ); ?>" title="<?php echo esc_attr( $logo_title ); ?>" rel="home" class="site-logo-text"><?php echo $logo_icon; ?><?php echo esc_html( $logo_title ); ?></a>
		<?php endif; ?>
		<?php wpex_hook_site_logo_inner(); ?>
	</div><!-- #site-logo-inner -->
</div><!-- #site-logo -->
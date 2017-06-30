<?php
/**
 * Product Loop Start
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.0.0
 */

// Define classes and apply filter for easy modification
$classes = 'products wpex-row clr';
if ( wpex_get_mod( 'woo_entry_equal_height', false ) ) {
	$classes .= ' match-height-grid';
}
$classes = apply_filters( 'wpex_woo_loop_wrap_classes', $classes ); ?>

<ul class="<?php echo esc_attr( $classes );?>">
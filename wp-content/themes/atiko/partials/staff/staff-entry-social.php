<?php
/**
 * Staff entry social links template part
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Display if enabled
if ( wpex_get_mod( 'staff_entry_social', true ) ) {
	echo wpex_get_staff_social();
}
<?php
/**
 * Setup theme hooks
 *
 * @package Total WordPress Theme
 * @subpackage Hooks
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Array of theme hooks
 *
 * @since 2.0.0
 */
function wpex_theme_hooks() {
	return array(
		'outer_wrap' => array(
			'label' => __( 'Outer Wrap', 'total' ),
			'hooks' => array(
				'wpex_outer_wrap_before',
				'wpex_outer_wrap_after',
			),
		),
		'wrap' => array(
			'label' => __( 'Wrap', 'total' ),
			'hooks' => array(
				'wpex_hook_wrap_before',
				'wpex_hook_wrap_top',
				'wpex_hook_wrap_bottom',
				'wpex_hook_wrap_after'
			),
		),
		'topbar' => array(
			'label' => __( 'Top Bar', 'total' ),
			'hooks' => array(
				'wpex_hook_topbar_before',
				'wpex_hook_topbar_inner',
				'wpex_hook_topbar_after',
			),
		),
		'header' => array(
			'label' => __( 'Header', 'total' ),
			'hooks' => array(
				'wpex_hook_header_before',
				'wpex_hook_header_top',
				'wpex_hook_header_inner',
				'wpex_hook_header_bottom',
				'wpex_hook_header_after',
			),
		),
		'header_logo' => array(
			'label' => __( 'Logo', 'total' ),
			'hooks' => array(
				'wpex_hook_site_logo_inner',
			),
		),
		'main_menu' => array(
			'label' => __( 'Main Menu', 'total' ),
			'hooks' => array(
				'wpex_hook_main_menu_before',
				'wpex_hook_main_menu_top',
				'wpex_hook_main_menu_bottom',
				'wpex_hook_main_menu_after',
			),
		),
		'main' => array(
			'label' => __( 'Main', 'total' ),
			'hooks' => array(
				'wpex_hook_main_before',
				'wpex_hook_main_top',
				'wpex_hook_main_bottom',
				'wpex_hook_main_after',
			),
		),
		'primary' => array(
			'label' => __( 'Primary Wrap', 'total' ),
			'hooks' => array(
				'wpex_hook_primary_before',
				'wpex_hook_primary_after',
			),
		),
		'content' => array(
			'label' => __( 'Content Wrap', 'total' ),
			'hooks' => array(
				'wpex_hook_content_before',
				'wpex_hook_content_top',
				'wpex_hook_content_bottom',
				'wpex_hook_content_after',
			),
		),
		'sidebar' => array(
			'label' => __( 'Sidebar', 'total' ),
			'hooks' => array(
				'wpex_hook_sidebar_before',
				'wpex_hook_sidebar_top',
				'wpex_hook_sidebar_inner',
				'wpex_hook_sidebar_bottom',
				'wpex_hook_sidebar_after',
			),
		),
		'footer' => array(
			'label' => __( 'Footer', 'total' ),
			'hooks' => array(
				'wpex_hook_footer_before',
				'wpex_hook_footer_top',
				'wpex_hook_footer_inner',
				'wpex_hook_footer_bottom',
				'wpex_hook_footer_after',
			),
		),
		'footer_bottom' => array(
			'label' => __( 'Footer Bottom', 'total' ),
			'hooks' => array(
				'wpex_hook_footer_bottom_before',
				'wpex_hook_footer_bottom_inner',
				'wpex_hook_footer_bottom_after',
			),
		),
		'page_header' => array(
			'label' => __( 'Page Header', 'total' ),
			'hooks' => array(
				'wpex_hook_page_header_before',
				'wpex_hook_page_header_top',
				'wpex_hook_page_header_inner',
				'wpex_hook_page_header_bottom',
				'wpex_hook_page_header_after',
			),
		),
		'social_share' => array(
			'label' => __( 'Social Share', 'total' ),
			'hooks' => array(
				'wpex_hook_social_share_before',
				'wpex_hook_social_share_inner',
				'wpex_hook_social_share_after',
			),
		),
	);
}

/**
 * Outer Wrap Hooks
 *
 * @since 2.0.0
 */
function wpex_outer_wrap_before() {
	do_action( 'wpex_outer_wrap_before' );
}
function wpex_outer_wrap_after() {
	do_action( 'wpex_outer_wrap_after' );
}


/**
 * Topbar Hooks
 *
 * @since 2.0.0
 */
function wpex_hook_topbar_before() {
	do_action( 'wpex_hook_topbar_before' );
}
function wpex_hook_topbar_inner() {
	do_action( 'wpex_hook_topbar_inner' );
}
function wpex_hook_topbar_after() {
	do_action( 'wpex_hook_topbar_after' );
}


/**
 * Main Header Hooks
 *
 * @since 1.0
 */
function wpex_hook_header_before() {
	do_action( 'wpex_hook_header_before' );
}
function wpex_hook_header_top() {
	do_action( 'wpex_hook_header_top' );
}
function wpex_hook_header_inner() {
	do_action( 'wpex_hook_header_inner' );
}
function wpex_hook_header_bottom() {
	do_action( 'wpex_hook_header_bottom' );
}
function wpex_hook_header_after() {
	do_action( 'wpex_hook_header_after' );
}

/**
 * Logo
 *
 * @since 3.5.1
 */
function wpex_hook_site_logo_inner() {
	do_action( 'wpex_hook_site_logo_inner' );
}


/**
 * Wrap Hooks
 *
 * @since 1.0
 */
function wpex_hook_wrap_before() {
	do_action( 'wpex_hook_wrap_before' );
}
function wpex_hook_wrap_top() {
	do_action( 'wpex_hook_wrap_top' );
}
function wpex_hook_wrap_bottom() {
	do_action( 'wpex_hook_wrap_bottom' );
}
function wpex_hook_wrap_after() {
	do_action( 'wpex_hook_wrap_after' );
}


/**
 * Main Hooks
 *
 * @since 1.0
 */
function wpex_hook_main_before() {
	do_action( 'wpex_hook_main_before' );
}
function wpex_hook_main_top() {
	do_action( 'wpex_hook_main_top' );
}
function wpex_hook_main_bottom() {
	do_action( 'wpex_hook_main_bottom' );
}
function wpex_hook_main_after() {
	do_action( 'wpex_hook_main_after' );
}


/**
 * Primary Hooks
 *
 * @since 2.0.0
 */
function wpex_hook_primary_before() {
	do_action( 'wpex_hook_primary_before' );
}
function wpex_hook_primary_after() {
	do_action( 'wpex_hook_primary_after' );
}


/**
 * Content Hooks
 *
 * @since 1.0
 */
function wpex_hook_content_before() {
	do_action( 'wpex_hook_content_before' );
}
function wpex_hook_content_top() {
	do_action( 'wpex_hook_content_top' );
}
function wpex_hook_content_bottom() {
	do_action( 'wpex_hook_content_bottom' );
}
function wpex_hook_content_after() {
	do_action( 'wpex_hook_content_after' );
}


/**
 * Sidebar Hooks
 *
 * @since 1.0
 */
function wpex_hook_sidebar_before() {
	do_action( 'wpex_hook_sidebar_before' );
}
function wpex_hook_sidebar_after() {
	do_action( 'wpex_hook_sidebar_after' );
}
function wpex_hook_sidebar_top() {
	do_action( 'wpex_hook_sidebar_top' );
}
function wpex_hook_sidebar_bottom() {
	do_action( 'wpex_hook_sidebar_bottom' );
}
function wpex_hook_sidebar_inner() {
	do_action( 'wpex_hook_sidebar_inner' );
}


/**
 * Footer Hooks
 *
 * @since 1.0
 */
function wpex_hook_footer_before() {
	do_action( 'wpex_hook_footer_before' );
}
function wpex_hook_footer_top() {
	do_action( 'wpex_hook_footer_top' );
}
function wpex_hook_footer_inner() {
	do_action( 'wpex_hook_footer_inner' );
}
function wpex_hook_footer_bottom() {
	do_action( 'wpex_hook_footer_bottom' );
}
function wpex_hook_footer_after() {
	do_action( 'wpex_hook_footer_after' );
}

/**
 * Footer Bottom Hooks
 *
 * @since 4.0
 */
function wpex_hook_footer_bottom_before() {
	do_action( 'wpex_hook_footer_bottom_before' );
}
function wpex_hook_footer_bottom_inner() {
	do_action( 'wpex_hook_footer_bottom_inner' );
}
function wpex_hook_footer_bottom_after() {
	do_action( 'wpex_hook_footer_bottom_after' );
}


/**
 * Main Menu Hooks
 *
 * @since 1.0
 */
function wpex_hook_main_menu_before() {
	do_action( 'wpex_hook_main_menu_before' );
}
function wpex_hook_main_menu_top() {
	do_action( 'wpex_hook_main_menu_top' );
}
function wpex_hook_main_menu_bottom() {
	do_action( 'wpex_hook_main_menu_bottom' );
}
function wpex_hook_main_menu_after() {
	do_action( 'wpex_hook_main_menu_after' );
}


/**
 * Page Header Hooks
 *
 * @since 1.0
 */
function wpex_hook_page_header_before() {
	do_action( 'wpex_hook_page_header_before' );
}
function wpex_hook_page_header_top() {
	do_action( 'wpex_hook_page_header_top' );
}
function wpex_hook_page_header_inner() {
	do_action( 'wpex_hook_page_header_inner' );
}
function wpex_hook_page_header_bottom() {
	do_action( 'wpex_hook_page_header_bottom' );
}
function wpex_hook_page_header_after() {
	do_action( 'wpex_hook_page_header_after' );
}

/**
 * Visual Composer Rows
 *
 * @since 4.0
 */
function wpex_hook_vc_row_top( $atts = '' ) {
	return apply_filters( 'wpex_hook_vc_row_top', '', $atts ); // Must be filter since do_action returns content
}
function wpex_hook_vc_row_bottom( $atts = '' ) {
	return apply_filters( 'wpex_hook_vc_row_bottom', '', $atts ); // Must be filter since do_action returns content
}
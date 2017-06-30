<?php
/**
 * Customizer Partial Refresh Support
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Abort if selective refresh is not available.
if ( ! isset( $wp_customize->selective_refresh ) ) {
	return;
}

// Social Sharing
$wp_customize->selective_refresh->add_partial( 'social_share', array(
	'selector'            => '.wpex-social-share-wrap',
	'settings'            => array(
		'social_share_sites',
		'social_share_position',
		'social_share_style',
		'social_share_shortcode',
		'social_share_heading',
	),
	'primarySetting'      => 'social_share_sites',
	'container_inclusive' => true,
	'render_callback'     => function() {
		wpex_get_template_part( 'social_share' );
	},
) );

// Breadcrumbs
$wp_customize->selective_refresh->add_partial( 'breadcrumbs', array(
	'selector'            => '.site-breadcrumbs',
	'settings'            => array(
		'breadcrumbs',
		'breadcrumbs_position',
		'breadcrumbs_home_title',
		'breadcrumbs_title_trim',
		'blog_page',
		'breadcrumbs_first_cat_only',
	),
	'primarySetting'      => 'breadcrumbs',
	'container_inclusive' => true,
	'render_callback'     => function() {
		wpex_display_breadcrumbs(); // Must use this function because of custom position.
	},
) );

// Topbar Style
$wp_customize->selective_refresh->add_partial( 'top_bar_style', array(
	'selector'            => '#top-bar-wrap',
	'settings'            => array( 'top_bar_style' ),
	'primarySetting'      => false,
	'container_inclusive' => true,
	'render_callback'     => function() {
		wpex_get_template_part( 'topbar' );
	},
) );

// Topbar Content
$wp_customize->selective_refresh->add_partial( 'top_bar_content', array(
	'id'                  => 'top_bar_content',
	'selector'            => '#top-bar-content',
	'settings'            => array( 'top_bar_content' ),
	'primarySetting'      => 'top_bar_content',
	'container_inclusive' => true,
	'render_callback'     => function() {

		// Add inline style for VC added content
		if ( function_exists( 'wpex_get_vc_meta_inline_style' ) ) {
			if ( $topbar_content = get_theme_mod( 'top_bar_content' ) ) {
				if ( is_numeric( $topbar_content ) ) {
					$post = get_post( $topbar_content );
					if ( $post && ! is_wp_error( $post ) ) {
						echo wpex_get_vc_meta_inline_style( $topbar_content );
					}
				}
			}
		}

		// Load template
		wpex_get_template_part( 'topbar_content' );

	},
) );

// Topbar Social Alt
$wp_customize->selective_refresh->add_partial( 'top_bar_social_alt', array(
	'selector'            => '#top-bar-social-alt',
	'settings'            => 'top_bar_social_alt',
	'primarySetting'      => 'top_bar_social_alt',
	'container_inclusive' => true,
	'render_callback'     => function() {

		// Add inline style for VC added content
		if ( function_exists( 'wpex_get_vc_meta_inline_style' ) ) {
			if ( $social_alt_content = get_theme_mod( 'top_bar_social_alt' ) ) {
				if ( is_numeric( $social_alt_content ) ) {
					$post = get_post( $social_alt_content );
					if ( $post && ! is_wp_error( $post ) ) {
						echo wpex_get_vc_meta_inline_style( $social_alt_content );
					}
				}
			}
		}

		// Get template file
		wpex_get_template_part( 'topbar_social' );

	},
) );

// Topbar Social
$social_settings = array(
	'top_bar_social_style',
);
$social_options = wpex_topbar_social_options();
foreach ( $social_options as $key => $val ) {
	$social_settings[] = 'top_bar_social_profiles[' . $key .']';
}
$wp_customize->selective_refresh->add_partial( 'top_bar_social', array(
	'selector'            => '#top-bar-social',
	'settings'            => $social_settings,
	'primarySetting'      => 'top_bar_social',
	'container_inclusive' => true,
	'render_callback'     => function() {
		wpex_get_template_part( 'topbar_social' );
	},
) );

// Post Series
$wp_customize->selective_refresh->add_partial( 'post_series', array(
	'id'                  => 'post_series_heading',
	'selector'            => '#post-series',
	'settings'            => array( 'post_series_heading' ),
	'primarySetting'      => 'post_series_heading',
	'container_inclusive' => true,
	'render_callback'     => function() {
		wpex_get_template_part( 'post_series' );
	},
) );

// Header Aside Content
$wp_customize->selective_refresh->add_partial( 'header_aside', array(
	'id'                  => 'header_aside',
	'selector'            => '#header-aside',
	'settings'            => array( 'header_aside', 'header_aside_search' ),
	'primarySetting'      => 'header_aside',
	'container_inclusive' => true,
	'render_callback'     => function() {
		wpex_get_template_part( 'header_aside' );
	},
) );

// Callout
$wp_customize->selective_refresh->add_partial( 'callout_text', array(
	'selector'            => '#footer-callout-wrap',
	'settings'            => array(
		'callout',
		'callout_text',
		'callout_link',
		'callout_button_icon',
		'callout_link_txt',
		'callout_visibility'
	),
	'primarySetting'      => 'callout_text',
	'container_inclusive' => true,
	'render_callback'     => function() {

		// Add inline style for VC added content
		if ( function_exists( 'wpex_get_vc_meta_inline_style' ) ) {
			if ( $callout_content = get_theme_mod( 'callout_text' ) ) {
				if ( is_numeric( $callout_content ) ) {
					$post = get_post( $callout_content );
					if ( $post && ! is_wp_error( $post ) ) {
						echo wpex_get_vc_meta_inline_style( $callout_content );
					}
				}
			}
		}

		// Get callout content
		wpex_get_template_part( 'footer_callout' );

	},
) );

// Footer Bottom
$wp_customize->selective_refresh->add_partial( 'footer_bottom', array(
	'selector'            => '#footer-bottom',
	'settings'            => array( 'footer_bottom', 'bottom_footer_text_align', 'footer_copyright_text' ),
	'primarySetting'      => 'footer_bottom',
	'container_inclusive' => true,
	'render_callback'     => function() {
		wpex_get_template_part( 'footer_bottom' );
	},
) );

/* Blog Post Settings
$wp_customize->selective_refresh->add_partial( 'blog_single_composer', array(
	'selector'            => '.single-post #single-blocks',
	'settings'            => array( 'blog_single_composer', ),
	'primarySetting'      => 'blog_single_composer',
	'container_inclusive' => false,
	'render_callback'     => function() {
		wpex_get_template_part( 'blog_single_blocks' );
	},
	'fallback_refresh'    => false,
) );*/

// Accent
if ( class_exists( 'WPEX_Accent_Color' ) ) {
	$wp_customize->selective_refresh->add_partial( 'wpex_accent_color', array(
		'selector'            => '#wpex-accent-css',
		'settings'            => 'accent_color',
		'primarySetting'      => 'accent_color',
		'container_inclusive' => true,
		'render_callback'     => function() {
			$accent_css = WPEX_Accent_Color::customizer_css();
			echo $accent_css;
		},
	) );
}

// Borders
if ( class_exists( 'WPEX_Theme_Border_Color' ) ) {
	$wp_customize->selective_refresh->add_partial( 'wpex_main_border_color', array(
		'selector'            => '#wpex-borders-css',
		'settings'            => 'main_border_color',
		'primarySetting'      => 'main_border_color',
		'container_inclusive' => true,
		'render_callback'     => function() {
			$borders_css = WPEX_Theme_Border_Color::customizer_css();
			echo $borders_css;
		},
	) );
}
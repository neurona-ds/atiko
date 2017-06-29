<?php
/**
 * Deprecated functions
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Global Object Class => Deprecated since 4.0
require_once WPEX_FRAMEWORK_DIR . 'deprecated/global-object.php';

// Skins => Deprecated since 3.0.0
require_once WPEX_FRAMEWORK_DIR .'deprecated/skins/skins.php';

/*-----------------------------------------------------------------------------------*/
/*  - Rename old functions for better consistancy
/*-----------------------------------------------------------------------------------*/
function wpex_display_callout() {
	return wpex_has_callout();
}

function wpex_display_page_header() {
	wpex_page_header();
}

function wpex_display_page_header_title() {
	wpex_page_header_title();
}

function wpex_header_layout() {
	// Do nothing, new function wpex_site_header is added via hook
}

function wpex_toggle_bar_active() {
	return wpex_has_togglebar();
}

function wpex_toggle_bar_btn() {
	return wpex_toggle_bar_button();
}

function wpex_post_layout() {
	echo wpex_content_area_layout();
}

function wpex_get_post_layout_class() {
	return wpex_content_area_layout();
}

function wpex_overlay_classname() {
	return wpex_overlay_classes();
}

function wpex_img_animation_classes() {
	return wpex_entry_image_animation_classes();
}

function wpex_post_entry_author_avatar_enabled() {
	return wpex_get_mod( 'blog_entry_author_avatar' );
}

function wpex_has_menu_search() {
	return true;
}

function wpex_single_meta_blocks() {
	return wpex_meta_blocks();
}

function gds_get_star_rating( $rating = '', $post_id = '' ) {
	return wpex_get_star_rating( $rating, $post_id );
}

/*-----------------------------------------------------------------------------------*/
/*  - Prevent error notices in template parts for child theme edits
/*  - Do not remove!
/*-----------------------------------------------------------------------------------*/
function wpex_get_the_id() {
	return wpex_get_current_post_id();
}

function wpex_get_header_logo_width() {
	return wpex_header_logo_img_width();
}

function wpex_get_header_logo_height() {
	return wpex_header_logo_img_height();
}

function wpex_get_post_layout() {
	return wpex_content_area_layout();
}

function wpex_get_page_subheading() {
	return wpex_page_header_subheading_content();
}

function wpex_is_front_end_composer() {
	return wpex_vc_is_inline();
}

function wpex_breadcrumbs( $post_id = '' ) {
	$breadcrumbs = new WPEX_Breadcrumbs();
	$breadcrumbs->display();
}

function wpex_has_footer_widgets() {
	return wpex_footer_has_widgets();
}

function wpex_display_footer_widgets() {
	return wpex_footer_has_widgets();
}

function wpex_topbar_output() {
	return wpex_topbar_content();
}

function wpex_top_bar_classes() {
	return wpex_topbar_content_classes();
}

function wpex_footer_reveal_enabled( $post_id = '' ) {
	return wpex_footer_has_reveal( $post_id );
}

function wpex_page_header_background_image_meta() {
	return wpex_page_header_background_image();
}

function wpex_toggle_bar_classes() {
	return wpex_togglebar_classes();
}

function wpex_portfolio_post_blocks() {
	return wpex_portfolio_single_blocks();
}

function wpex_staff_post_blocks() {
	return wpex_staff_single_blocks();
}

/*-----------------------------------------------------------------------------------*/
/*  - Display Deprecated notices
/*-----------------------------------------------------------------------------------*/
function wpex_top_bar_content() {
	wpex_topbar_content();
}

function wpex_header_search_placeholder() {
	_deprecated_function( 'wpex_header_search_placeholder', '3.0.0', false );
}

function wpex_option() {
	_deprecated_function( 'wpex_option', '1.6.0', 'wpex_get_mod' );
}

function wpex_image() {
	_deprecated_function( 'wpex_image', '2.0.0', 'wpex_get_post_thumbnail' );
}

function wpex_mobile_menu() {
	_deprecated_function( 'wpex_mobile_menu', '2.0.0', 'wpex_mobile_menu_icons' );
}

function wpex_post_has_composer() {
	_deprecated_function( 'wpex_post_has_composer', '2.0.0', 'wpex_has_composer' );
}

function wpex_display_header() {
	_deprecated_function( 'wpex_display_header', '2.0.0', 'wpex_has_header' );
}

function wpex_display_footer() {
	_deprecated_function( 'wpex_display_footer', '2.0.0', 'wpex_has_footer' );
}

function wpex_page_title() {
	_deprecated_function( 'wpex_page_title', '2.0.0', 'wpex_title' );
}

function wpex_post_subheading() {
	_deprecated_function( 'wpex_post_subheading', '2.0.0', 'wpex_page_header_subheading' );
}

function wpex_hook_header_before_default() {
	_deprecated_function( 'wpex_hook_header_before_default', '2.0.0' );
}

function wpex_hook_header_inner_default() {
	_deprecated_function( 'wpex_hook_header_inner_default', '2.0.0' );
}

function wpex_hook_header_bottom_default() {
	_deprecated_function( 'wpex_hook_header_bottom_default', '2.0.0' );
}

function wpex_hook_main_top_default() {
	_deprecated_function( 'wpex_hook_main_top_default', '2.0.0' );
}

function wpex_hook_sidebar_inner_default() {
	_deprecated_function( 'wpex_hook_sidebar_inner_default', '2.0.0' );
}

function wpex_hook_footer_before_default() {
	_deprecated_function( 'wpex_hook_footer_before_default', '2.0.0' );
}

function wpex_hook_footer_inner_default() {
	_deprecated_function( 'wpex_hook_footer_inner', '2.0.0' );
}

function wpex_hook_footer_after_default() {
	_deprecated_function( 'wpex_hook_footer_after', '2.0.0' );
}

function wpex_hook_wrap_after_default() {
	_deprecated_function( 'wpex_hook_wrap_after_default', '2.0.0' );
}

function wpex_theme_setup() {
	_deprecated_function( 'wpex_theme_setup', '1.6.0' );
}

function wpex_active_post_types() {
	_deprecated_function( 'wpex_active_post_types', '1.6.0' );
}

function wpex_jpeg_quality() {
	_deprecated_function( 'wpex_jpeg_quality', '1.6.0' );
}

function wpex_favicons() {
	_deprecated_function( 'wpex_favicons', '1.6.0' );
}

function wpex_get_woo_product_first_cat() {
	_deprecated_function( 'wpex_get_woo_product_first_cat', '1.6.0' );
}

function wpex_global_config() {
	_deprecated_function( 'wpex_global_config', '1.6.0' );
}

function wpex_ie8_css() {
	_deprecated_function( 'wpex_ie8_css', '1.6.0' );
}

function wpex_html5() {
	_deprecated_function( 'wpex_html5', '1.6.0' );
}

function wpex_load_scripts() {
	_deprecated_function( 'wpex_load_scripts', '1.6.0' );
}

function wpex_remove_wp_ver_css_js() {
	_deprecated_function( 'wpex_remove_wp_ver_css_js', '1.6.0' );
}

function wpex_output_css() {
	_deprecated_function( 'wpex_output_css', '1.6.0' );
}

function wpex_header_output() {
	_deprecated_function( 'wpex_header_output', '1.6.0', 'wpex_header_layout' );
}

function wpex_footer_copyright() {
	_deprecated_function( 'wpex_footer_copyright', '1.6.0', 'get_template_part' );
}

function wpex_top_bar_social() {
	_deprecated_function( 'wpex_top_bar_social', '1.6.0', 'get_template_part' );
}

function wpex_portfolio_single_media() {
	_deprecated_function( 'wpex_portfolio_single_media', '1.6.0', 'get_template_part' );
}

function wpex_portfolio_related() {
	_deprecated_function( 'wpex_portfolio_related', '1.6.0', 'get_template_part' );
}

function wpex_staff_entry_media() {
	_deprecated_function( 'wpex_staff_entry_media', '1.6.0', 'get_template_part' );
}

function wpex_staff_related() {
	_deprecated_function( 'wpex_staff_related', '1.6.0', 'get_template_part' );
}

function wpex_blog_related() {
	_deprecated_function( 'wpex_blog_related', '1.6.0', 'get_template_part' );
}

function wpex_blog_entry_display() {
	_deprecated_function( 'wpex_blog_entry_display', '1.6.0', 'get_template_part' );
}

function wpex_blog_entry_image() {
	_deprecated_function( 'wpex_blog_entry_image', '1.6.0', 'get_template_part' );
}

function wpex_post_entry_author_avatar() {
	_deprecated_function( 'wpex_post_entry_author_avatar', '1.6.0', 'get_template_part' );
}

function wpex_blog_entry_title() {
	_deprecated_function( 'wpex_blog_entry_title', '1.6.0', 'get_template_part' );
}

function wpex_blog_entry_header() {
	_deprecated_function( 'wpex_blog_entry_header', '1.6.0', 'get_template_part' );
}

function wpex_blog_entry_content() {
	_deprecated_function( 'wpex_blog_entry_content', '1.6.0', 'get_template_part' );
}

function wpex_blog_entry_media() {
	_deprecated_function( 'wpex_blog_entry_media', '1.6.0', 'get_template_part' );
}

function wpex_blog_entry_link_format_image() {
	_deprecated_function( 'wpex_blog_entry_link_format_image', '1.6.0', 'get_template_part' );
}

function wpex_post_readmore_link() {
	_deprecated_function( 'wpex_post_readmore_link', '1.6.0', 'get_template_part' );
}

function wpex_blog_entry_video() {
	_deprecated_function( 'wpex_blog_entry_video', '1.6.0', 'get_template_part' );
}

function wpex_blog_entry_audio() {
	_deprecated_function( 'wpex_blog_entry_audio', '1.6.0', 'get_template_part' );
}

function wpex_post_meta() {
	_deprecated_function( 'wpex_post_meta', '1.6.0', 'get_template_part' );
}

function wpex_post_entry_classes() {
	_deprecated_function( 'wpex_post_entry_classes', '1.6.0' );
}

function vcex_advanced_parallax() {
	_deprecated_function( 'vcex_advanced_parallax', '2.0.2', 'vcex_parallax_bg' );
}

function vcex_front_end_carousel_js() {
	_deprecated_function( 'vcex_front_end_carousel_js', '2.0.0', 'vcex_inline_js' );
}

function wpex_breadcrumbs_get_parents() {
	_deprecated_function( 'wpex_breadcrumbs_get_parents', '3.0.9', false );
}

function wpex_breadcrumbs_get_term_parents() {
	_deprecated_function( 'wpex_breadcrumbs_get_term_parents', '3.0.9', false );
}
function wpex_vc_column_inner_extra_attributes() {
	_deprecated_function( 'wpex_vc_column_inner_extra_attributes', '4.1', false );
}
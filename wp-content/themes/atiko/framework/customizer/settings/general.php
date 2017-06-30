<?php
/**
 * Customizer => General Panel
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Accent Colors
$this->sections['wpex_accent_colors'] = array(
	'title' => __( 'Accent Colors', 'total' ),
	'panel' => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'accent_color',
			'default' => '#3b86b0',
			'transport' => 'partialRefresh',
			'control' => array(
				'label' => __( 'Accent Color', 'total' ),
				'type' => 'color',
			),
		),
		array(
			'id' => 'main_border_color',
			'default' => '#eee',
			'transport' => 'partialRefresh',
			'control' => array(
				'label' => __( 'Border Accent Color', 'total' ),
				'type' => 'color',
			),
		),
	)
);

// Background
$this->sections['wpex_background'] = array(
	'title'  => __( 'Site Background', 'total' ),
	'panel'  => 'wpex_general',
	'desc' => __( 'Here you can alter the global site background. It is highly recommended that you first set the site layout to "Boxed" under the Layout options.', 'total' ),
	'settings' => array(
		array(
			'id' => 'background_color',
			'control' => array(
				'label' => __( 'Background Color', 'total' ),
				'type' => 'color',
			),
		),
		array(
			'id' => 'background_image',
			'control' => array(
				'label' => __( 'Custom Background Image', 'total' ),
				'type' => 'image',
				'active_callback' => 'wpex_cac_hasnt_background_pattern',
			),
		),
		array(
			'id' => 'background_style',
			'default' => 'stretched',
			'control' => array(
				'label' => __( 'Background Image Style', 'total' ),
				'type'  => 'image',
				'type'  => 'select',
				'active_callback' => 'wpex_cac_has_background_image',
				'choices' => $bg_styles,
			),
		),
		array(
			'id' => 'background_pattern',
			'control' => array(
				'label' => __( 'Background Pattern', 'total' ),
				'type'  => 'image',
				'type'  => 'wpex-bg-patterns',
				'active_callback' => 'wpex_cac_hasnt_background_image',
			),
		),
	),
);

// Social Sharing Section
$this->sections['wpex_social_sharing'] = array(
	'title'  => __( 'Social Sharing', 'total' ),
	'panel'  => 'wpex_general',
	'settings' => array(
		array(
			'id'  => 'social_share_sites',
			'transport' => 'partialRefresh',
			'default' => array( 'twitter', 'facebook', 'google_plus', 'pinterest' ),
			'control' => array(
				'label'  => __( 'Sites', 'total' ),
				'desc' => __( 'Click and drag and drop elements to re-order them.', 'total' ),
				'type' => 'wpex-sortable',
				'object' => 'WPEX_Customize_Control_Sorter',
				'choices' => array(
					'twitter'  => 'Twitter',
					'facebook' => 'Facebook',
					'google_plus' => 'Google Plus',
					'pinterest' => 'Pinterest',
					'linkedin' => 'LinkedIn',
				),
				'active_callback' => 'wpex_cac_hasnt_custom_social_share',
			),
		),
		array(
			'id' => 'social_share_position',
			'transport' => 'partialRefresh',
			'control' => array(
				'label' => __( 'Position', 'total' ),
				'type' => 'select',
				'choices' => array(
					'' => __( 'Default', 'total' ),
					'horizontal' => __( 'Horizontal', 'total' ),
					'vertical' => __( 'Vertical', 'total' ),
				),
				'active_callback' => 'wpex_cac_has_theme_social_share_sites',
			),
		),
		array(
			'id' => 'social_share_style',
			'transport' => 'partialRefresh',
			'default' => 'flat',
			'control' => array(
				'label' => __( 'Style', 'total' ),
				'type'  => 'select',
				'choices' => array(
					'flat' => __( 'Flat', 'total' ),
					'minimal' => __( 'Minimal', 'total' ),
					'three-d' => __( '3D', 'total' ),
					'rounded' => __( 'Rounded', 'total' ),
				),
				'active_callback' => 'wpex_cac_has_theme_social_share_sites',
			),
		),
		array(
			'id' => 'social_share_heading',
			'transport' => 'partialRefresh',
			'default' => __( 'Please Share This', 'total' ),
			'control' => array(
				'label' => __( 'Heading on Posts', 'total' ),
				'type'  => 'text',
				'active_callback' => 'wpex_cac_has_theme_social_share_sites',
				'description' => $leave_blank_desc,
			),
		),
		array(
			'id' => 'social_share_twitter_handle',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Twitter Handle', 'total' ),
				'type' => 'text',
				'active_callback' => 'wpex_cac_has_theme_social_share_sites',
			),
		),
		array(
			'id' => 'social_share_shortcode',
			'transport' => 'partialRefresh',
			'control' => array(
				'label' => __( 'Alternative Shortcode', 'total' ),
				'type' => 'text',
				'description' => __( 'Override the theme default social share with your custom social sharing shortcode.', 'total' ),
			),
		),
	)
);

// Breadcrumbs
$this->sections['wpex_breadcrumbs'] = array(
	'title' => __( 'Breadcrumbs', 'total' ),
	'panel' => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'breadcrumbs',
			'transport' => 'partialRefresh',
			'default' => true,
			'control' => array(
				'label' => __( 'Enable', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'breadcrumbs_position',
			'transport' => 'partialRefresh',
			'default' => 'absolute',
			'control' => array(
				'label' => __( 'Position', 'total' ),
				'type'  => 'select',
				'choices' => array(
					'absolute'  => __( 'Absolute Right', 'total' ),
					'under-title' => __( 'Under Title', 'total' ),
					'custom' => __( 'Custom', 'total' ),
				),
				'active_callback' => 'wpex_cac_has_breadcrumbs',
			),
		),
		array(
			'id' => 'breadcrumbs_home_title',
			'transport' => 'partialRefresh',
			'control' => array(
				'label' => __( 'Custom Home Title', 'total' ),
				'type'  => 'text',
			),
		),
		array(
			'id' => 'breadcrumbs_first_cat_only',
			'transport' => 'partialRefresh',
			'default' => false,
			'control' => array(
				'label' => __( 'First Category Only', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'breadcrumbs_title_trim',
			'transport' => 'partialRefresh',
			'control' => array(
				'label' => __( 'Title Trim Length', 'total' ),
				'type'  => 'text',
				'desc'  => __( 'Enter the max number of words to display for your breadcrumbs post title', 'total' ),
			),
		),
		array(
			'id' => 'breadcrumbs_text_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Text Color', 'total' ),
				'active_callback' => 'wpex_cac_has_breadcrumbs',
			),
			'inline_css' => array(
				'target' => '.site-breadcrumbs',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'breadcrumbs_seperator_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Separator Color', 'total' ),
				'active_callback' => 'wpex_cac_has_breadcrumbs',
			),
			'inline_css' => array(
				'target' => '.site-breadcrumbs .sep',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'breadcrumbs_link_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Color', 'total' ),
				'active_callback' => 'wpex_cac_has_breadcrumbs',
			),
			'inline_css' => array(
				'target' => '.site-breadcrumbs a',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'breadcrumbs_link_color_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Color: Hover', 'total' ),
				'active_callback' => 'wpex_cac_has_breadcrumbs',
			),
			'inline_css' => array(
				'target' => '.site-breadcrumbs a:hover',
				'alter' => 'color',
			),
		),
	),
);

// Page Title
$this->sections['wpex_page_header'] = array(
	'title' => __( 'Page Header Title', 'total' ),
	'panel' => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'page_header_style',
			'transport' => 'refresh',
			'default' => '',
			'control' => array(
				'label'  => __( 'Style', 'total' ),
				'type' => 'select',
				'choices' => array(
					'' => __( 'Default','total' ),
					'centered' => __( 'Centered', 'total' ),
					'centered-minimal' => __( 'Centered Minimal', 'total' ),
					'hidden' => __( 'Hidden', 'total' ),
				),
			),
		),
		array(
			'id' => 'page_header_top_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Top Padding', 'total' ),
				'description' => $pixel_desc,
				'active_callback' => 'wpex_cac_has_page_header',
			),
			'inline_css' => array(
				'target' => '.page-header.wpex-supports-mods',
				'alter' => 'padding-top',
			),
		),
		array(
			'id' => 'page_header_bottom_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Bottom Padding', 'total' ),
				'description' => $pixel_desc,
				'active_callback' => 'wpex_cac_has_page_header',
			),
			'inline_css' => array(
				'target' => '.page-header.wpex-supports-mods',
				'alter' => 'padding-bottom',
			),
		),
		array(
			'id' => 'page_header_bottom_margin',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Bottom Margin', 'total' ),
				'description' => $pixel_desc,
				'active_callback' => 'wpex_cac_has_page_header',
			),
			'inline_css' => array(
				'target' => '.page-header',
				'alter' => 'margin-bottom',
			),
		),
		array(
			'id' => 'page_header_background',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Background', 'total' ),
				'active_callback' => 'wpex_cac_has_page_header',
			),
			'inline_css' => array(
				'target' => '.page-header.wpex-supports-mods',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'page_header_title_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Text Color', 'total' ),
				'active_callback' => 'wpex_cac_has_page_header',
			),
			'inline_css' => array(
				'target' => '.page-header.wpex-supports-mods .page-header-title',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'page_header_top_border',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Top Border Color', 'total' ),
				'active_callback' => 'wpex_cac_has_page_header',
			),
			'inline_css' => array(
				'target' => '.page-header.wpex-supports-mods',
				'alter' => 'border-top-color',
			),
		),
		array(
			'id' => 'page_header_bottom_border',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Bottom Border Color', 'total' ),
				'active_callback' => 'wpex_cac_has_page_header',
			),
			'inline_css' => array(
				'target' => '.page-header.wpex-supports-mods',
				'alter' => 'border-bottom-color',
			),
		),
		array(
			'id' => 'page_header_border_width',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Border Width', 'total' ),
				'description' => $pixel_desc,
				'active_callback' => 'wpex_cac_has_page_header',
			),
			'inline_css' => array(
				'target' => '.page-header.wpex-supports-mods',
				'alter' => array( 'border-width' ),
			),
		),
		array(
			'id' => 'page_header_background_img',
			'transport' => 'refresh',
			'control' => array(
				'type' => 'image',
				'label' => __( 'Background Image', 'total' ),
				'active_callback' => 'wpex_cac_has_page_header',
			),
		),
		array(
			'id' => 'page_header_background_img_style',
			'default' => 'fixed',
			'control' => array(
				'label' => __( 'Background Image Style', 'total' ),
				'type'  => 'image',
				'type'  => 'select',
				'active_callback' => 'wpex_cac_supports_page_header_background_img_style',
				'choices' => $bg_styles,
			),
		),
		array(
			'id' => 'page_header_hidden_main_top_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Hidden Page Header Title Spacing', 'total' ),
				'active_callback' => 'wpex_cac_hasnt_page_header',
				'desc' => __( 'When the page header title is set to hidden there will not be any space between the header and the main content. If you want to add a default space between the header and your main content you can enter the px value here.', 'total' ) .'<br /><br />'. $pixel_desc,
			),
			'inline_css' => array(
				'target' => 'body.page-header-disabled #content-wrap',
				'alter' => 'padding-top',
				'sanitize' => 'px',
			),
		),
	),
);

// Pages
$blocks = apply_filters( 'wpex_page_single_blocks', array(
	'title'    => __( 'Title', 'total' ),
	'media'    => __( 'Media', 'total' ),
	'content'  => __( 'Content', 'total' ),
	'share'    => __( 'Social Share', 'total' ),
	'comments' => __( 'Comments', 'total' ),
) );
$this->sections['wpex_pages'] = array(
	'title'  => __( 'Pages', 'total' ),
	'panel'  => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'page_single_layout',
			'default' => '',
			'control' => array(
				'label' => __( 'Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'pages_custom_sidebar',
			'default' => true,
			'control' => array(
				'label' => __( 'Custom Sidebar', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'page_composer',
			'default' => 'content',
			'control' => array(
				'label' => __( 'Post Layout Elements', 'total' ),
				'type' => 'wpex-sortable',
				'choices' => $blocks,
				'desc' => __( 'Click and drag and drop elements to re-order them.', 'total' ),
			),
		),
	),
);

// Search
$this->sections['wpex_search'] = array(
	'title'  => __( 'Search', 'total' ),
	'panel'  => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'search_style',
			'default' => 'default',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Style', 'total' ),
				'type' => 'select',
				'choices' => array(
					'default' => __( 'Left Thumbnail', 'total' ),
					'blog' => __( 'Inherit From Blog','total' ),
				),
			),
		),
		array(
			'id' => 'search_layout',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'search_posts_per_page',
			'default' => '10',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Posts Per Page', 'total' ),
				'type' => 'text',
			),
		),
		array(
			'id' => 'search_custom_sidebar',
			'default' => true,
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Custom Sidebar', 'total' ),
				'type' => 'checkbox',
			),
		),
	),
);

// Scroll to top
$this->sections['wpex_scroll_top'] = array(
	'title' => __( 'Scroll To Top', 'total' ),
	'panel' => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'scroll_top',
			'default' => true,
			'transport' => 'refresh',
			'control' => array(
				'label' => __( 'Scroll Up Button', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'scroll_top_arrow',
			'default' => 'chevron-up',
			'transport' => 'postMessage',
			'active_callback' => 'wpex_cac_has_scrolltop',
			'control' => array(
				'label' => __( 'Arrow', 'total' ),
				'type' => 'select',
				'choices' => wpex_get_awesome_icons( 'up_arrows' ),
			),
		),
		array(
			'id' => 'scroll_top_border_width',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Border Width', 'total' ),
				'description' => $pixel_desc,
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'sanitize' => 'px',
				'alter' => 'border-width',
			),
		),
		array(
			'id' => 'scroll_top_size',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Button Size', 'total' ),
				'description' => $pixel_desc,
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'sanitize' => 'px',
				'alter' => array(
					'width',
					'height',
					'line-height',
				),
			),
		),
		array(
			'id' => 'scroll_top_icon_size',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Icon Size', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'font-size',
			),
		),
		array(
			'id' => 'scroll_top_border_radius',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Border Radius', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'border-radius',
			),
		),
		array(
			'id' => 'scroll_top_right_position',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Right Position', 'total' ),
				'description' => __( 'Default:', 'total' ) .' 40px',
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'right',
			),
		),
		array(
			'id' => 'scroll_top_bottom_position',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Bottom Position', 'total' ),
				'description' => __( 'Default:', 'total' ) .' 40px',
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'bottom',
			),
		),
		array(
			'id' => 'scroll_top_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Color', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'scroll_top_color_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Color: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top:hover',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'scroll_top_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'scroll_top_bg_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Background: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top:hover',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'scroll_top_border',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Border', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'border-color',
			),
		),
		array(
			'id' => 'scroll_top_border_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Border: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top:hover',
				'alter' => 'border-color',
			),
		),
	),
);

// Pagination
$this->sections['wpex_pagination'] = array(
	'title' => __( 'Pagination', 'total' ),
	'panel' => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'pagination_align',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'select',
				'default' => 'left',
				'label' => __( 'Align', 'total' ),
				'choices' => array(
					'left' => __( 'Left', 'total' ),
					'center' => __( 'Center', 'total' ),
					'right' => __( 'Right', 'total' ),
				),
			),
		),
		array(
			'id' => 'pagination_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Padding', 'total' ),
				'description' => $padding_desc,
			),
			'inline_css' => array(
				'target' => 'ul .page-numbers a, a.page-numbers, span.page-numbers, .page-links span, .page-links a > span',
				'alter' => 'padding',
			),
		),
		array(
			'id' => 'pagination_font_size',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Font Size', 'total' ),
			),
			'inline_css' => array(
				'target' => 'ul.page-numbers, .page-links',
				'alter' => 'font-size',
			),
		),
		array(
			'id' => 'pagination_border_width',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Border Width', 'total' ),
			),
			'inline_css' => array(
				'target' => 'ul .page-numbers a, a.page-numbers, span.page-numbers, .page-links span, .page-links a > span',
				'alter' => 'border-width',
			),
		),
		array(
			'id' => 'pagination_border_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Border Color', 'total' ),
			),
			'inline_css' => array(
				'target' => 'ul .page-numbers a, a.page-numbers, span.page-numbers, .page-links span, .page-links a > span',
				'alter' => 'border-color',
			),
		),
		array(
			'id' => 'pagination_border_hover_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Border Color: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '.page-numbers a:hover, .page-numbers.current, .page-numbers.current:hover, .page-links span, .page-links a > span:hover',
				'alter' => 'border-color',
			),
		),
		array(
			'id' => 'pagination_border_active_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Border Color: Active', 'total' ),
			),
			'inline_css' => array(
				'target' => '.page-numbers.current, .page-numbers.current:hover',
				'alter' => 'border-color',
				'important' => true,
			),
		),
		array(
			'id' => 'pagination_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Color', 'total' ),
			),
			'inline_css' => array(
				'target' => 'ul .page-numbers a, a.page-numbers, span.page-numbers, .page-links span, .page-links a > span',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'pagination_hover_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Color: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '.page-numbers a:hover, .page-numbers.current, .page-numbers.current:hover, .page-links span, .page-links a > span:hover',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'pagination_hover_active',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Color: Active', 'total' ),
			),
			'inline_css' => array(
				'target' => '.page-numbers.current, .page-numbers.current:hover',
				'alter' => 'color',
				'important' => true,
			),
		),
		array(
			'id' => 'pagination_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Background', 'total' ),
			),
			'inline_css' => array(
				'target' => 'ul .page-numbers a, a.page-numbers, span.page-numbers, .page-links span, .page-links a > span',
				'alter' => 'background',
			),
		),
		array(
			'id' => 'pagination_hover_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Background: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '.page-numbers a:hover, .page-numbers.current, .page-numbers.current:hover, .page-links span, .page-links a > span:hover',
				'alter' => 'background',
			),
		),
		array(
			'id' => 'pagination_active_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Background: Active', 'total' ),
			),
			'inline_css' => array(
				'target' => '.page-numbers.current, .page-numbers.current:hover',
				'alter' => 'background',
				'important' => true,
			),
		),
	),
);

// Next/Prev
$this->sections['wpex_next_prev'] = array(
	'title' => __( 'Next & Previous Links', 'total' ),
	'panel' => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'next_prev_in_same_term',
			'default' => true,
			'control' => array(
				'type' => 'checkbox',
				'label' => __( 'From Same Category', 'total' ),
			),
		),
		array(
			'id' => 'next_prev_reverse_order',
			'default' => false,
			'control' => array(
				'type' => 'checkbox',
				'label' => __( 'Reverse Order', 'total' ),
			),
		),
		array(
			'id' => 'next_prev_link_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Color', 'total' ),
			),
			'inline_css' => array(
				'target' => '.post-pagination a',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'next_prev_link_font_size',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Font Size', 'total' ),
				'description' => __( 'Value in px or em.', 'total' ),
			),
			'inline_css' => array(
				'target' => '.post-pagination',
				'alter' => 'font-size',
				'sanitize' => 'font-size',
			),
		),
		array(
			'id' => 'next_prev_next_text',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Custom Next Text', 'total' ),
			),
		),
		array(
			'id' => 'next_prev_prev_text',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Custom Prev Text', 'total' ),
			),
		),
	),
);

// Theme Heading
$this->sections['wpex_theme_heading'] = array(
	'title' => __( 'Theme Heading', 'total' ),
	'panel' => 'wpex_general',
	'desc' => __( 'Heading used in various places such as the related and comments heading.', 'total' ),
	'settings' => array(
		array(
			'id' => 'theme_heading_style',
			'control' => array(
				'type' => 'select',
				'default' => '',
				'label' => __( 'Style', 'total' ),
				'choices' => array(
					'' => __( 'Default', 'total' ),
					'plain' => __( 'Plain', 'total' ),
					'border-w-color' => __( 'Bottom Border With Color', 'total' ),
				),
			),
		),
		array(
			'id' => 'theme_heading_tag',
			'default' => 'div',
			'control' => array(
				'label' => __( 'Tag', 'total' ),
				'type' => 'select',
				'choices' => array(
					'div' => 'div',
					'h2' => 'h2',
					'h3' => 'h3',
					'h4' => 'h4',
					'h5' => 'h5',
					'h6' => 'h6',
				),
			),
		),
	),
);

// Forms
$this->sections['wpex_general_forms'] = array(
	'title' => __( 'Forms', 'total' ),
	'panel' => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'label_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Label Color', 'total' ),
			),
			'inline_css' => array(
				'target' => 'label',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'forms_inputs_heading',
			'control' => array(
				'type' => 'wpex-heading',
				'label' => __( 'Inputs', 'total' ),
			),
		),
		array(
			'id' => 'input_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Padding', 'total' ),
				'description' => $padding_desc,
			),
			'inline_css' => array(
				'target' => array('.site-content input[type="date"],.site-content input[type="time"],.site-content input[type="datetime-local"],.site-content input[type="week"],.site-content input[type="month"],.site-content input[type="text"],.site-content input[type="email"],.site-content input[type="url"],.site-content input[type="password"],.site-content input[type="search"],.site-content input[type="tel"],.site-content input[type="number"],.site-content textarea' ),
				'alter' => 'padding',
			),
		),
		array(
			'id' => 'input_border_radius',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Border Radius', 'total' ),
			),
			'inline_css' => array(
				'target' => array('.site-content input[type="date"],.site-content input[type="time"],.site-content input[type="datetime-local"],.site-content input[type="week"],.site-content input[type="month"],.site-content input[type="text"],.site-content input[type="email"],.site-content input[type="url"],.site-content input[type="password"],.site-content input[type="search"],.site-content input[type="tel"],.site-content input[type="number"],.site-content textarea' ),
				'alter' => array( 'border-radius', '-webkit-border-radius' ),
			),
		),
		array(
			'id' => 'input_font_size',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Font-Size', 'total' ),
			),
			'inline_css' => array(
				'target' => array('.site-content input[type="date"],.site-content input[type="time"],.site-content input[type="datetime-local"],.site-content input[type="week"],.site-content input[type="month"],.site-content input[type="text"],.site-content input[type="email"],.site-content input[type="url"],.site-content input[type="password"],.site-content input[type="search"],.site-content input[type="tel"],.site-content input[type="number"],.site-content textarea' ),
				'alter' => 'font-size',
			),
		),
		array(
			'id' => 'input_background',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Background', 'total' ),
			),
			'inline_css' => array(
				'target' => array('.site-content input[type="date"],.site-content input[type="time"],.site-content input[type="datetime-local"],.site-content input[type="week"],.site-content input[type="month"],.site-content input[type="text"],.site-content input[type="email"],.site-content input[type="url"],.site-content input[type="password"],.site-content input[type="search"],.site-content input[type="tel"],.site-content input[type="number"],.site-content textarea' ),
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'input_border',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Border', 'total' ),
			),
			'inline_css' => array(
				'target' => array('.site-content input[type="date"],.site-content input[type="time"],.site-content input[type="datetime-local"],.site-content input[type="week"],.site-content input[type="month"],.site-content input[type="text"],.site-content input[type="email"],.site-content input[type="url"],.site-content input[type="password"],.site-content input[type="search"],.site-content input[type="tel"],.site-content input[type="number"],.site-content textarea' ),
				'alter' => 'border-color',
			),
		),
		array(
			'id' => 'input_border_width',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Border Width', 'total' ),
				'description' => $pixel_desc,
			),
			'inline_css' => array(
				'target' => array('.site-content input[type="date"],.site-content input[type="time"],.site-content input[type="datetime-local"],.site-content input[type="week"],.site-content input[type="month"],.site-content input[type="text"],.site-content input[type="email"],.site-content input[type="url"],.site-content input[type="password"],.site-content input[type="search"],.site-content input[type="tel"],.site-content input[type="number"],.site-content textarea' ),
				'alter' => 'border-width',
			),
		),
		array(
			'id' => 'input_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Color', 'total' ),
			),
			'inline_css' => array(
				'target' => array('.site-content input[type="date"],.site-content input[type="time"],.site-content input[type="datetime-local"],.site-content input[type="week"],.site-content input[type="month"],.site-content input[type="text"],.site-content input[type="email"],.site-content input[type="url"],.site-content input[type="password"],.site-content input[type="search"],.site-content input[type="tel"],.site-content input[type="number"],.site-content textarea' ),
				'alter' => 'color',
			),
		),
	),
);


// Links & Buttons
$this->sections['wpex_general_links_buttons'] = array(
	'title' => __( 'Links & Buttons', 'total' ),
	'panel' => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'link_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Links Color', 'total' ),
			),
			'inline_css' => array(
				'target' => 'a, h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover, .entry-title a:hover,.woocommerce .woocommerce-error a.button, .woocommerce .woocommerce-info a.button, .woocommerce .woocommerce-message a.button',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'link_color_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Links Color: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => 'a:hover',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'theme_button_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Theme Button Padding', 'total' ),
				'description' => $padding_desc,
			),
			'inline_css' => array(
				'target' => '.theme-button,input[type="submit"],button',
				'alter' => 'padding',
			),
		),
		array(
			'id' => 'theme_button_border_radius',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Theme Button Border Radius', 'total' ),
			),
			'inline_css' => array(
				'target' => '.theme-button,input[type="submit"],button',
				'alter' => 'border-radius',
			),
		),
		array(
			'id' => 'theme_button_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Theme Button Color', 'total' ),
			),
			'inline_css' => array(
				'target' => '.theme-button,input[type="submit"],button',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'theme_button_hover_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Theme Button Color: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '.theme-button:hover,input[type="submit"]:hover,button:hover',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'theme_button_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Theme Button Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '.theme-button,input[type="submit"],button',
				'alter' => 'background',
			),
		),
		array(
			'id' => 'theme_button_hover_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Theme Button Background: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '.theme-button:hover,input[type="submit"]:hover,button:hover',
				'alter' => 'background',
			),
		),
	),
);
<?php
/**
 * Visual Composer Row Configuration
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'VCEX_VC_Row_Config' ) ) {
	
	class VCEX_VC_Row_Config {

		/**
		 * Main constructor
		 *
		 * @since 2.0.0
		 */
		public function __construct() {
			
			// Modify some default params when editing to move things around
			add_action( 'wpex_vc_modify_params', array( 'VCEX_VC_Row_Config', 'modify_params' ) );
			
			// Remove some default params
			add_action( 'wpex_vc_add_params', array( 'VCEX_VC_Row_Config', 'add_params' ) );
			
			// Edit fields when opening editor window
			add_filter( 'vc_edit_form_fields_attributes_vc_row', array( 'VCEX_VC_Row_Config', 'edit_form_fields') );
			
			// Add custom classes
			add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, array( 'VCEX_VC_Row_Config', 'shortcode_classes' ), 10, 3 );

			// Parse attributes
			add_filter( 'shortcode_atts_vc_row', array( 'VCEX_VC_Row_Config', 'parse_attributes' ), 99 );

			// Add custom attributes to row
			add_filter( 'wpex_vc_row_wrap_atts', array( 'VCEX_VC_Row_Config', 'wrap_attributes' ), 10, 2 );

			// Hooks
			add_filter( 'wpex_hook_vc_row_top', array( 'VCEX_VC_Row_Config', 'center_row_open' ), 10, 2 );
			add_filter( 'wpex_hook_vc_row_bottom', array( 'VCEX_VC_Row_Config', 'center_row_close' ), 10, 2 );
			add_filter( 'wpex_hook_vc_row_bottom', array( 'VCEX_VC_Row_Config', 'vc_row_added_elements' ), 50, 2 );

			// Custom output
			add_filter( 'vc_shortcode_output', array( 'VCEX_VC_Row_Config', 'custom_output' ), 10, 3 );

		}

		/**
		 * Used to update default parms
		 *
		 * @since 3.0.0
		 */
		public static function modify_params( $params ) {

			// Save re-usable strings in var
			$parallax_tab = __( 'Parallax', 'total' );
			$s_video      = __( 'Video', 'total' );

			$params['vc_row'] = array(

				'el_id' => array(
					'weight' => 99
				),

				'el_class' => array(
					'weight' => 99,
				),

				'css_animation' => array(
					'weight' => 99,
				),

				'full_width' => array(
					'weight' => 99,
				),

				'content_placement' => array(
					'weight' => 99,
				),

				// Change gap description
				'gap' => array(
					'heading' => __( 'Outer Column Gap', 'total' ),
					'description' =>  __( 'Alters the outer column gap to be used when adding backgrounds to your columns. To increase the default space between the columns without backgrounds use the "Outer Column Gap" setting instead.', 'total' ),
					'weight' => 40,
				),

				// Move video parallax setting
				'video_bg_parallax' => array(
					'group' => $s_video,
					'dependency' => array(
						'element' => 'video_bg',
						'value' => 'youtube',
					),
				),

				// Move youtube url
				'video_bg_url' => array(
					'group' => $s_video,
					'dependency' => array(
						'element' => 'video_bg',
						'value' => 'youtube',
					),
				),

				// Move video parallax speed
				'parallax_speed_video' => array(
					'group' => $s_video,
					'dependency' => array(
						'element' => 'video_bg',
						'value' => 'youtube',
					),
				),

				// Alter Parallax dropdown
				'parallax' => array(
					'group' => $parallax_tab,
					'value' => array(
						__( 'Advanced Parallax', 'total' ) => 'vcex_parallax',
					),
				),

				// Alter Parallax image location
				'parallax_image' => array(
					'group' => $parallax_tab,
				),

				// Alter Parallax speed location
				'parallax_speed_bg' => array(
					'group' => $parallax_tab,
					'dependency' => array(
						'element' => 'parallax',
						'value' => array( 'content-moving', 'content-moving-fade' ),
					),
				),

				// Move design options
				'css' => array(
					'group' => __( 'Design Options', 'total' ),
					'weight' => -1,
				),

				/* Change items to use vcex_ofswitch
				// @todo
				'full_height' => array(
					'type' => 'vcex_ofswitch',
					'vcex' => array( 'off' => '', 'on' => 'yes', ),
					'std' => '',
				),*/

			);

			return $params;

		}

		/**
		 * Adds new params for the VC Rows
		 *
		 * @since 2.0.0
		 */
		public static function add_params( $params ) {

			// Save re-usable strings in var
			$s_video = __( 'Video', 'total' );
			$s_no    = __( 'No', 'total' );
			$s_yes   = __( 'Yes', 'total' );

			// Array of params to add
			$add_params = array();

			$add_params[] = array(
				'type' => 'dropdown',
				'heading' => __( 'User Access', 'total' ),
				'param_name' => 'vcex_user_access',
				'weight' => 99,
				'value' => array(
					__( 'All', 'total' ) => '',
					__( 'Logged in', 'total' ) => 'logged_in',
					__( 'Logged out', 'total' ) => 'logged_out',
					__( 'Custom', 'total' ) => 'custom',
				)
			);

			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Custom User Access', 'total' ),
				'param_name' => 'vcex_user_access_callback',
				'description' => __( 'Enter your callback function name here.', 'total' ),
				'weight' => 99,
				'dependency' => array( 'element' => 'vcex_user_access', 'value' => 'custom' ),
			);

			$add_params[] = array(
				'type' => 'vcex_visibility',
				'heading' => __( 'Visibility', 'total' ),
				'param_name' => 'visibility',
				'weight' => 99,
			);

			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Local Scroll ID', 'total' ),
				'param_name' => 'local_scroll_id',
				'description' => __( 'Unique identifier for local scrolling links.', 'total' ),
				'weight' => 99,
			);

			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Minimum Height', 'total' ),
				'description' => __( 'Adds a minimum height to the row so you can have a row without any content but still display it at a certain height. Such as a background with a video or image background but without any content.', 'total' ),
				'param_name' => 'min_height',
			);

			$add_params[] = array(
				'type' => 'dropdown',
				'heading' => __( 'Equal Column Heights', 'total' ),
				'param_name' => 'match_column_height',
				'value' => array(
					$s_no => '',
					$s_yes => 'yes',
				),
			);

			$add_params[] = array(
				'type' => 'dropdown',
				'heading' => __( 'Typography Style', 'total' ),
				'param_name' => 'typography_style',
				'value' => array_flip( wpex_typography_styles() ),
			);

			$add_params[] = array(
				'type' => 'dropdown',
				'heading' => __( 'Max Width', 'total' ),
				'param_name' => 'max_width',
				'value' => array(
					__( 'None', 'total' ) => '',
					'10%' => '10',
					'20%' => '20',
					'30%' => '30',
					'40%' => '40',
					'50%' => '50',
					'60%' => '60',
					'70%' => '70',
					'80%' => '80',
				),
				'description' => __( 'The max width is done by setting a percentage margin on the left and right of your row. You can visit the Design Options tab to enter custom percentage margins yourself if you prefer', 'total' ),
				'dependency' => array( 'element' => 'full_width', 'is_empty' => true ),
			);

			$add_params[] = array(
				'type' => 'dropdown',
				'heading' => __( 'Inner Column Gap', 'total' ),
				'param_name' => 'column_spacing',
				'value' => array(
					__( 'Default', 'total' ) => '',
					'0px' => '0px',
					'1px' => '1',
					'5px' => '5',
					'10px' => '10',
					'20px' => '20',
					'30px' => '30',
					'40px' => '40',
					'50px' => '50',
					'60px' => '60',
				),
				'description' => __( 'Alter the inner column spacing.', 'total' ),
				'weight' => 40,
			);
			$add_params[] = array(
				'type' => 'vcex_ofswitch',
				'heading' => __( 'Full-Width Columns On Tablets', 'total' ),
				'param_name' => 'tablet_fullwidth_cols',
				'vcex' => array( 'off' => 'no', 'on' => 'yes', ),
				'std' => 'no',
				'description' => __( 'Enable to make all columns inside this row full-width for tablets.', 'total' ),
			);
			$add_params[] = array(
				'type' => 'vcex_ofswitch',
				'heading' => __( 'Float Columns Right', 'total' ),
				'param_name' => 'columns_right',
				'vcex' => array( 'off' => 'no', 'on' => 'yes', ),
				'std' => 'no',
				'description' => __( 'Most useful when you want to alternate content such as an image to the right and content to the left but display the image at the top on mobile.', 'total' ),
			);

			$add_params[] = array(
				'type' => 'vcex_ofswitch',
				'heading' => __( 'Offset Overlay Header', 'total' ),
				'param_name' => 'offset_overlay_header',
				'vcex' => array( 'off' => 'no', 'on' => 'yes', ),
				'std' => 'no',
				'description' => __( 'Check this box to add an offset spacing before this row equal to the height of your header to prevent issues with the header Overlay when enabled.', 'total' ),
			);

			// Parallax
			$add_params[] = array(
				'type' => 'vcex_ofswitch',
				'heading' => __( 'Enable parallax for mobile devices', 'total' ),
				'param_name' => 'parallax_mobile',
				'vcex' => array( 'off' => 'no', 'on' => 'yes', ),
				'std' => 'no',
				'description' => __( 'Parallax effects would most probably cause slowdowns when your site is viewed in mobile devices. By default it is disabled.', 'total' ),
				'group' => __( 'Parallax', 'total' ),
				'dependency' => array(
					'element' => 'parallax',
					'value' => 'vcex_parallax',
				),
			);
			$add_params[] = array(
				'type' => 'dropdown',
				'heading' => __( 'Parallax Style', 'total' ),
				'param_name' => 'parallax_style',
				'group' => __( 'Parallax', 'total' ),
				'value' => array(
					__( 'Cover', 'total' ) => '',
					__( 'Fixed and Repeat', 'total' ) => 'fixed-repeat',
					__( 'Fixed and No-Repeat', 'total' ) => 'fixed-no-repeat',
				),
				'dependency' => array(
					'element' => 'parallax',
					'value' => 'vcex_parallax',
				),
			);
			$add_params[] = array(
				'type' => 'dropdown',
				'heading' => __( 'Parallax Direction', 'total' ),
				'param_name' => 'parallax_direction',
				'value' => array(
					__( 'Up', 'total' ) => '',
					__( 'Down', 'total' ) => 'down',
					__( 'Left', 'total' ) => 'left',
					__( 'Right', 'total' ) => 'right',
				),
				'group' => __( 'Parallax', 'total' ),
				'dependency' => array(
					'element' => 'parallax',
					'value' => 'vcex_parallax',
				),
			);
			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Parallax Speed', 'total' ),
				'param_name' => 'parallax_speed',
				'description' => __( 'The movement speed, value should be between 0.1 and 1.0. A lower number means slower scrolling speed. Be mindful of the background size and the dimensions of your background image when setting this value. Faster scrolling means that the image will move faster, make sure that your background image has enough width or height for the offset.', 'total' ),
				'group' => __( 'Parallax', 'total' ),
				'dependency' => array(
					'element' => 'parallax',
					'value' => 'vcex_parallax',
				),
			);

			// Video
			$add_params[] = array(
				'type' => 'dropdown',
				'heading' => __( 'Video Background', 'total' ),
				'param_name' => 'video_bg',
				'value' => array(
					__( 'None', 'total' ) => '',
					__( 'Youtube', 'total' ) => 'youtube',
					__( 'Self Hosted', 'total' ) => 'self_hosted',
				),
				'group' => $s_video,
				'description' => __( 'Video backgrounds do not display on mobile because mobile devices do not allow the auto playing of videos it is recommended to apply a standard background image or color as a fallback for mobile devices.', 'total' ),
			);
			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Video URL: MP4 URL', 'total' ),
				'param_name' => 'video_bg_mp4',
				'dependency' => array(
					'element' => 'video_bg',
					'value' => 'self_hosted',
				),
				'group' => $s_video,
			);
			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Video URL: WEBM URL', 'total' ),
				'param_name' => 'video_bg_webm',
				'dependency' => array(
					'element' => 'video_bg',
					'value' => 'self_hosted',
				),
				'group' => $s_video,
			);
			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Video URL: OGV URL', 'total' ),
				'param_name' => 'video_bg_ogv',
				'dependency' => array(
					'element' => 'video_bg',
					'value' => 'self_hosted',
				),
				'group' => $s_video,
			);
			$add_params[] = array(
				'type' => 'dropdown',
				'heading' => __( 'Background Overlay', 'total' ),
				'param_name' => 'wpex_bg_overlay',
				'group' => __( 'Overlay', 'total' ),
				'value' => array(
					__( 'None', 'total' ) => '',
					__( 'Color', 'total' ) => 'color',
					__( 'Dark', 'total' ) => 'dark',
					__( 'Dotted', 'total' ) => 'dotted',
					__( 'Diagonal Lines', 'total' ) => 'dashed',
				),
			);

			$add_params[] = array(
				'type' => 'colorpicker',
				'heading' => __( 'Background Overlay Color', 'total' ),
				'param_name' => 'wpex_bg_overlay_color',
				'group' => __( 'Overlay', 'total' ),
				'dependency' => array( 'element' => 'wpex_bg_overlay', 'value' => array( 'color', 'dark', 'dotted', 'dashed' ) ),
			);

			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Background Overlay Opacity', 'total' ),
				'param_name' => 'wpex_bg_overlay_opacity',
				'dependency' => array( 'element' => 'wpex_bg_overlay', 'value' => array( 'color', 'dark', 'dotted', 'dashed' ) ),
				'group' => __( 'Overlay', 'total' ),
				'description' => '0.65',
			);

			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Background Position', 'total' ),
				'param_name' => 'wpex_bg_position',
				'group' => __( 'Design Options', 'total' ),
				'description' => __( 'Enter your custom background position. Example: "center center"', 'total' ),
				'weight' => -2,
				'dependency' => array( 'element' => 'parallax', 'is_empty' => true ),
			);

			$add_params[] = array(
				'type' => 'vcex_ofswitch',
				'heading' => __( 'Center Row Content (deprecated)', 'total' ),
				'param_name' => 'center_row',
				'vcex' => array( 'off' => 'no', 'on' => 'yes', ),
				'std' => 'no',
				'dependency' => array( 'element' => 'full_width', 'is_empty' => true ),
				'description' => __( 'Use this option is used to center the inner content horizontally in your row when using the "Full Screen" layout for your post/page. This was added prior to the stretch row setting it is now best to use the no-sidebar layout and the stretch row function to achive full-screen sections. If enable certain settings such as the "Content position" may not work correctly.', 'total' ),
			);

			// Hidden fields = Deprecated params, these should be removed on save
			$deprecated_params = array(
				'id',
				'style',
				'bg_color',
				'bg_image',
				'bg_style',
				'border_style',
				'border_color',
				'border_width',
				'margin_top',
				'margin_bottom',
				'margin_left',
				'padding_top',
				'padding_bottom',
				'padding_left',
				'padding_right',
				'no_margins',
				'video_bg_overlay',
				'match_column_height', // converted to equal_height in 4.0
			);

			foreach ( $deprecated_params as $param ) {

				$add_params[] = array(
					'type' => 'hidden',
					'param_name' => $param,
				);

			}

			// Apply filters for child theming
			// @deprecated in Total 4.0 since it's not really needed
			// $add_params = apply_filters( 'wpex_vc_row_custom_params', $add_params );

			$params['vc_row'] = $add_params;

			return $params;

		}

		/**
		 * Tweaks row attributes on edit
		 *
		 * @since 2.0.2
		 */
		public static function edit_form_fields( $atts ) {

			// Parse ID
			if ( empty( $atts['el_id'] ) && ! empty( $atts['id'] ) ) {
				$atts['el_id'] = $atts['id'];
				unset( $atts['id'] );
			}

			// Convert match_column_height to equal_height
			if ( ! empty( $atts['match_column_height'] ) ) {
				$atts['equal_height'] = 'yes';
				unset( $atts['match_column_height'] );
			}

			// Parse $style into $typography_style
			if ( empty( $atts['typography_style'] ) && ! empty( $atts['style'] ) ) {
				if ( in_array( $atts['style'], array_flip( wpex_typography_styles() ) ) ) {
					$atts['typography_style'] = $atts['style'];
					unset( $atts['style'] );
				}
			}

			// Parse parallax
			if ( ! empty( $atts['parallax'] ) ) {
				if ( in_array( $atts['parallax'], array( 'simple', 'advanced', 'true' ) ) ) {
					$atts['parallax'] = 'vcex_parallax';
				}
			} elseif ( empty( $atts['parallax'] ) && ! empty( $atts['bg_style'] ) ) {
				if ( 'parallax' == $atts['bg_style'] || 'parallax-advanced' == $atts['bg_style'] ) {
					$atts['parallax'] = 'vcex_parallax';
					unset( $atts['bg_style'] );
				}
			}

			// Parse video background
			if ( ! empty( $atts['video_bg'] ) && 'yes' == $atts['video_bg'] ) {
				$atts['video_bg'] = 'self_hosted';
			}

			// Convert 'no-margins' to '0px' column_spacing
			if ( empty( $atts['column_spacing'] ) && ! empty( $atts['no_margins'] ) && 'true' == $atts['no_margins'] ) {
				$atts['column_spacing'] = '0px';
				unset( $atts['no_margins'] );
			}

			// Convert video overlay to just overlay
			if ( ! empty( $atts['video_bg_overlay'] ) && 'none' != $atts['video_bg_overlay'] ) {
				$atts['wpex_bg_overlay'] = $atts['video_bg_overlay'];
				unset( $atts['video_bg_overlay'] );
			}

			// Parse css
			if ( empty( $atts['css'] ) ) {

				// Convert deprecated fields to css field
				$atts['css'] = vcex_parse_deprecated_row_css( $atts );

				// Unset deprecated vars
				unset( $atts['bg_image'] );
				unset( $atts['bg_color'] );

				unset( $atts['margin_top'] );
				unset( $atts['margin_bottom'] );
				unset( $atts['margin_right'] );
				unset( $atts['margin_left'] );

				unset( $atts['padding_top'] );
				unset( $atts['padding_bottom'] );
				unset( $atts['padding_right'] );
				unset( $atts['padding_left'] );

				unset( $atts['border_width'] );
				unset( $atts['border_style'] );
				unset( $atts['border_color'] );

			}

			// Return $atts
			return $atts;

		}

		/**
		 * Tweak shortcode classes
		 *
		 * @since 4.0
		 */
		public static function shortcode_classes( $class_string, $tag, $atts ) {

			// Edits only for columns
			if ( 'vc_row' != $tag && 'vc_row_inner' != $tag ) {
				return $class_string;
			}

			$add_classes = array();

			// Tweak some classes
			if ( strpos( $class_string, 'vc_row-has-fill' ) !== false ) {
				$class_string = str_replace( 'vc_row-has-fill', '', $class_string );
				$add_classes['wpex-vc_row-has-fill'] = 'wpex-vc_row-has-fill';
			}

			// Visibility
			if ( ! empty( $atts['visibility'] ) ) {
				$add_classes[] = $atts['visibility'];
			}

			// Typography
			if ( ! empty( $atts['typography_style'] ) ) {
				$add_classes[] = $atts['typography_style'];
			}

			// Full width
			if ( ! empty( $atts['full_width'] ) ) {
				$add_classes[] = 'wpex-vc-row-stretched';
			}
			if ( ! empty( $atts['full_width_boxed_layout'] ) ) {
				$add_classes[] = 'wpex-vc-row-boxed-layout-stretched';
				if ( isset( $atts['full_width_style'] ) && 'stretch_row_content_no_spaces' == $atts['full_width_style'] ) {
					$add_classes[] = 'vc_row-no-padding';
				}
			}

			// Max width
			if ( empty( $atts['full_width'] ) && ! empty( $atts['max_width'] ) ) {
				$add_classes[] = 'vc-has-max-width vc-max-width-'. $atts['max_width'];
			}

			// Centered row
			if ( isset( $atts['center_row'] ) && 'yes' == $atts['center_row'] ) {
				$add_classes[] = 'wpex-vc-row-centered';
			}

			// Column spacing
			if ( ! empty( $atts['column_spacing'] ) ) {
				$add_classes[] = 'wpex-vc-has-custom-column-spacing';
				$add_classes[] = 'wpex-vc-column-spacing-'. $atts['column_spacing'];
			}

			// Tablet
			if ( isset( $atts['tablet_fullwidth_cols'] ) && 'yes' == $atts['tablet_fullwidth_cols'] ) {
				$add_classes[] = 'tablet-fullwidth-columns';
			}

			// Right hand columns
			if ( isset( $atts['columns_right'] ) && 'yes' == $atts['columns_right'] ) {
				$add_classes[] = 'wpex-cols-right';
			}

			// Parallax
			if ( ! empty( $atts['vcex_parallax'] ) ) {
				$add_classes[] = 'wpex-parallax-bg-wrap';
				$add_classes['wpex-vc_row-has-fill'] = 'wpex-vc_row-has-fill';
			}

			// Video bg
			if ( ! empty( $atts['wpex_self_hosted_video_bg'] ) ) {
				$add_classes[] = 'wpex-has-video-bg';
				$add_classes['wpex-vc_row-has-fill'] = 'wpex-vc_row-has-fill';
			}

			// Overlay
			if ( vcex_row_overlay( $atts ) ) {
				$add_classes[] = 'wpex-has-overlay';
			}

			// BG class
			// @deprecated fallback
			if ( ! empty( $atts['bg_style_class'] ) ) {
				$add_classes[] = $atts['bg_style_class'];
			}

			// Header overlay offset
			if ( isset( $atts['offset_overlay_header'] ) && 'yes' == $atts['offset_overlay_header'] ) {
				$add_classes[] = 'add-overlay-header-offset';
			}

			/*
			// Deprecated in 4.0
			if ( ! empty( $atts['full_width'] ) ) {
				$add_classes[] = 'wpex-vc-row-'. $atts['full_width'];
			}*/

			// Add custom classes
			if ( $add_classes ) {
				$add_classes = implode( ' ', array_filter( $add_classes, 'trim' ) );
				$class_string .= ' '. $add_classes;
			}

			// Return class string
			return $class_string;

		}

		/**
		 * Parse VC row attributes on front-end
		 *
		 * @since 4.0
		 */
		public static function parse_attributes( $atts ) {
			return vcex_parse_row_atts( $atts );
		}

		/**
		 * Add custom attributes to the row wrapper
		 *
		 * @since 4.0
		 */
		public static function wrap_attributes( $wrapper_attributes, $atts ) {
			$inline_style = '';

			// Local scroll ID
			if ( ! empty( $atts['local_scroll_id'] ) ) {
				$wrapper_attributes[] = 'data-ls_id="#' . esc_attr( $atts['local_scroll_id'] ) . '"';
			}

			// Min Height
			if ( ! empty( $atts['min_height'] ) ) {

				// Sanitize
				$min_height = esc_attr( $atts['min_height'] );
				if ( ! preg_match('/[A-z]/', $min_height ) && strpos( $min_height, '%' ) === false ) {
					$min_height = intval( $min_height ) . 'px;';
				}

				// Add inline style
				if ( $min_height ) {
					$inline_style .= 'min-height:'. $min_height .';';
				}

			}

			// Background position
			if ( ! empty( $atts['wpex_bg_position'] ) ) {
				$inline_style .= 'background-position:'. $atts['wpex_bg_position'] .' !important;';
			}

			// Inline css styles
			// Fallback For OLD Total Params
			if ( empty( $atts['css'] ) && function_exists( 'vcex_parse_deprecated_row_css' ) ) {
				$inline_style .= vcex_parse_deprecated_row_css( $atts, 'inline_css' );
			}

			// Add inline style to wrapper attributes
			if ( $inline_style ) {
				$wrapper_attributes[] = 'style="'. $inline_style .'"';
			}

			// Return attributes
			return $wrapper_attributes;

		}

		/**
		 * Open center row
		 *
		 * Priority: 10
		 *
		 * @since 4.0
		 */
		public static function center_row_open( $content, $atts ) {

			if ( ! empty( $atts['center_row'] ) ) {
				$content .= '<div class="center-row container"><div class="center-row-inner clr">';
			}

			return $content;

		}

		/**
		 * Close center row
		 *
		 * Priority: 10
		 *
		 * @since 4.0
		 */
		public static function center_row_close( $content, $atts ) {

			if ( ! empty( $atts['center_row'] ) ) {
				$content .= '</div></div><!-- center-row -->';
			}

			return $content;

		}

		/**
		 * Insert custom elements into rows as needed
		 *
		 * Priority: 1
		 *
		 * @since 4.0
		 */
		public static function vc_row_added_elements( $content, $atts ) {
			$content .= vcex_row_overlay( $atts );
			$content .= vcex_parallax_bg( $atts );
			$content .= vcex_row_video( $atts );
			return $content;

		}

		/**
		 * Custom HTML output
		 *
		 * @since 4.1
		 */
		public static function custom_output( $output, $obj, $atts ) {

			// Check needed settings
			if ( ! $obj->settings( 'base' ) ) {
				return $output;
			}

			// Tweaks neeed for vc_row only
			if ( 'vc_row' != $obj->settings( 'base' ) ) {
				return $output;
			}

			// Check user settings
			if ( isset( $atts['vcex_user_access'] )
				&& ! is_admin()
				&& ! vc_is_inline()
			) {
				$callback = ( 'custom' == $atts['vcex_user_access'] && isset( $atts['vcex_user_access_callback'] ) ) ? isset( $atts['vcex_user_access_callback'] ) : '';
				if ( ! wpex_user_can_access( $atts['vcex_user_access'], $callback ) ) {
					return;
				}
			}

			// Return output
			return $output;

		}

	}

}
new VCEX_VC_Row_Config();
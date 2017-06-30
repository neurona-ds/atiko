<?php
/**
 * Registers the button shortcode and adds it to the Visual Composer
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.2
 */

if ( ! class_exists( 'VCEX_Button_Shortcode' ) ) {

	class VCEX_Button_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_button', array( 'VCEX_Button_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_button', array( 'VCEX_Button_Shortcode', 'map' ) );
			}

			// Parse attributes
			if ( is_admin() ) {
				add_filter( 'vc_edit_form_fields_attributes_vcex_button', array( 'VCEX_Button_Shortcode', 'edit_form_fields' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_button.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			// Same re-usable strings
			$s_typo   = __( 'Typography', 'total' );
			$s_design = __( 'Design', 'total' );
			$s_icons  = __( 'Icons', 'total' );
			$s_link   = __( 'Link', 'total' );

			// Return array of settings
			return array(
				'name' => __( 'Total Button', 'total' ),
				'description' => __( 'Eye catching button', 'total' ),
				'base' => 'vcex_button',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-total-button vcex-icon fa fa-external-link-square',
				'params' => array(

					// General
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_hover_animations',
						'heading' => __( 'Hover Animation', 'total'),
						'param_name' => 'hover_animation',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Text', 'total' ),
						'param_name' => 'content',
						'admin_label' => true,
						'std' => 'Button Text',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'classes',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),

					// Link
					array(
						'type' => 'dropdown',
						'heading' => __( 'On click action', 'total' ),
						'param_name' => 'onclick',
						'value' => array(
							__( 'Open custom link', 'total' ) => 'custom_link',
							__( 'Open image', 'total' ) => 'image',
							__( 'Open lightbox', 'total' ) => 'lightbox',
						),
						'group' => $s_link,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'URL', 'total' ),
						'param_name' => 'url',
						'value' => 'https://www.google.com/',
						'dependency' => array( 'element' => 'onclick', 'value' => array( 'custom_link', 'lightbox' ) ),
						'group' => $s_link,
					),
					array(
						'type' => 'attach_image',
						'heading' => __( 'Image', 'total' ),
						'param_name' => 'image_attachment',
						'dependency' => array( 'element' => 'onclick', 'value' => array( 'image', 'lightbox' ) ),
						'group' => $s_link,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Title Attribute', 'total' ),
						'param_name' => 'title',
						'group' => $s_link,
						'description' => __( 'By default the button will use the button text for the title tag.', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Target', 'total' ),
						'param_name' => 'target',
						'value' => array(
							__( 'Self', 'total' ) => '',
							__( 'Blank', 'total' ) => 'blank',
							__( 'Local', 'total' ) => 'local',
						),
						'group' => $s_link,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Rel', 'total' ),
						'param_name' => 'rel',
						'value' => array(
							__( 'None', 'total' ) => '',
							__( 'Nofollow', 'total' ) => 'nofollow',
						),
						'group' => $s_link,
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Include Download Attribute', 'total' ),
						'param_name' => 'download_attribute',
						'group' => $s_link,
					),

					// Lightbox
					array(
						'type' => 'dropdown',
						'heading' => __( 'Type', 'total' ),
						'param_name' => 'lightbox_type',
						'value' => array(
							__( 'Auto Detect - slow', 'total' ) => '',
							__( 'iFrame', 'total' ) => 'iframe',
							__( 'Image', 'total' ) => 'image',
							__( 'Video', 'total' ) => 'video_embed',
							__( 'HTML5', 'total' ) => 'html5',
							__( 'Quicktime', 'total' ) => 'quicktime',
						),
						'description' => __( 'Auto detect depends on the iLightbox API, so by choosing your type it speeds things up and you also allows for HTTPS support.', 'total' ),
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'onclick', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'HTML5 Webm URL', 'total' ),
						'param_name' => 'lightbox_video_html5_webm',
						'description' => __( 'Enter the URL to a video, SWF file, flash file or a website URL to open in lightbox.', 'total' ),
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'lightbox_type', 'value' => 'html5' ),
					),
					array(
						'type' => 'attach_image',
						'heading' => __( 'Lightbox HTML5 Poster Image', 'total' ),
						'param_name' => 'lightbox_poster_image',
						'dependency' => array( 'element' => 'lightbox_type', 'value' => 'html5' ),
						'group' => __( 'Lightbox', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Lightbox Dimensions', 'total' ),
						'param_name' => 'lightbox_dimensions',
						'description' => __( 'Enter a custom width and height for your lightbox pop-up window. Use format widthxheight. Example: 900x600.', 'total' ),
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'onclick', 'value' => 'lightbox' ),
					),

					// Design
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'group' => $s_design,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Layout', 'total' ),
						'param_name' => 'layout',
						'value' => array(
							__( 'Inline', 'total' ) => '',
							__( 'Block', 'total' ) => 'block',
							__( 'Expanded (fit container)', 'total' ) => 'expanded',
						),
						'group' => $s_design,
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'align',
						'group' => $s_design,
						'description' => __( 'Any alignment besides "Default" will add a wrapper around the button to position it so it will no longer be inline.', 'total' )
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Size', 'total' ),
						'param_name' => 'size',
						'std' => '',
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'Small', 'total' ) => 'small',
							__( 'Medium', 'total' ) => 'medium',
							__( 'Large', 'total' ) => 'large',
						),
						'group' => $s_design,
						'dependency' => array( 'element' => 'font_size', 'is_empty' => true )
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'group' => $s_design,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'custom_background',
						'group' => $s_design,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'custom_hover_background',
						'group' => $s_design,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'custom_color',
						'group' => $s_design,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'custom_hover_color',
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Width', 'total' ),
						'param_name' => 'width',
						'description' => __( 'Please use a pixel or percentage value.', 'total' ),
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'border_radius',
						'description' => __( 'Please enter a px value.', 'total' ),
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'font_padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'margin',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_design,
					),

					// Typography
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'font_family',
						'group' => $s_typo,
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'group' => $s_typo,
						'target' => 'font-size',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'letter_spacing',
						'group' => $s_typo,
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'text_transform',
						'group' => $s_typo,
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'font_weight',
						'group' => $s_typo,
					),
					
					//Icons
					array(
						'type' => 'dropdown',
						'heading' => __( 'Icon library', 'total' ),
						'param_name' => 'icon_type',
						'description' => __( 'Select icon library.', 'total' ),
						'std' => 'fontawesome',
						'value' => array(
							__( 'Font Awesome', 'total' ) => 'fontawesome',
							__( 'Open Iconic', 'total' ) => 'openiconic',
							__( 'Typicons', 'total' ) => 'typicons',
							__( 'Entypo', 'total' ) => 'entypo',
							__( 'Linecons', 'total' ) => 'linecons',
							__( 'Pixel', 'total' ) => 'pixelicons',
						),
						'group' => $s_icons,
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'icon_left',
						'settings' => array(
							'emptyIcon' => true,
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
						'group' => $s_icons,
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'icon_left_openiconic',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'openiconic',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
						'group' => $s_icons,
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'icon_left_typicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'typicons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
						'group' => $s_icons,
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'icon_left_entypo',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'entypo',
							'iconsPerPage' => 300,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
						'group' => $s_icons,
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'icon_left_linecons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'linecons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
						'group' => $s_icons,
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'icon_left_pixelicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'pixelicons',
							'source' => vcex_pixel_icons(),
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
						'group' => $s_icons,
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'icon_right',
						'settings' => array(
							'emptyIcon' => true,
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
						'group' => $s_icons,
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'icon_right_openiconic',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'openiconic',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
						'group' => $s_icons,
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'icon_right_typicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'typicons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
						'group' => $s_icons,
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'icon_right_entypo',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'entypo',
							'iconsPerPage' => 300,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
						'group' => $s_icons,
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'icon_right_linecons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'linecons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
						'group' => $s_icons,
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'icon_right_pixelicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'pixelicons',
							'source' => vcex_pixel_icons(),
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
						'group' => $s_icons,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Left Icon: Right Padding', 'total' ),
						'param_name' => 'icon_left_padding',
						'group' => $s_icons,
					),

					array(
						'type' => 'textfield',
						'heading' => __( 'Right Icon: Left Padding', 'total' ),
						'param_name' => 'icon_right_padding',
						'group' => $s_icons,
					),
					// Design options
					array(
						'type' => 'css_editor',
						'heading' => __( 'Container Design', 'total' ),
						'param_name' => 'css_wrap',
						'group' => __( 'Container Design', 'total' ),
					),
					// Deprecated
					array( 'type' => 'hidden', 'param_name' => 'lightbox' ),
					array( 'type' => 'hidden', 'param_name' => 'lightbox_image' ),
				)
			);
		}

		/**
		 * Update fields on edit
		 *
		 * @since 3.5.0
		 */
		public function edit_form_fields( $atts ) {
			if ( ! empty( $atts['lightbox_image'] ) ) {
				$atts['image_attachment'] = $atts['lightbox_image'];
				unset( $atts['lightbox_image'] );
			}
			if ( isset( $atts['lightbox'] ) && 'true' == $atts['lightbox'] ) {
				$atts['onclick'] = 'lightbox';
			}
			return $atts;
		}


	}

}
new VCEX_Button_Shortcode;
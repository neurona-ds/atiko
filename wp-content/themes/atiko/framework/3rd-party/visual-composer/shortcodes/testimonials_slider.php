<?php
/**
 * Registers the testimonials slider shortcode and adds it to the Visual Composer
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Testimonials_Slider_Shortcode' ) ) {

	class VCEX_Testimonials_Slider_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_testimonials_slider', array( 'VCEX_Testimonials_Slider_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_testimonials_slider', array( 'VCEX_Testimonials_Slider_Shortcode', 'map' ) );
			}

			// Admin filters
			if ( is_admin() ) {

				// Alter fields on edit
				add_filter( 'vc_edit_form_fields_attributes_vcex_testimonials_slider', array( 'VCEX_Testimonials_Slider_Shortcode', 'edit_form_fields' ) );

				// Get autocomplete suggestion
				add_filter( 'vc_autocomplete_vcex_testimonials_slider_include_categories_callback', 'vcex_suggest_testimonials_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_testimonials_slider_exclude_categories_callback', 'vcex_suggest_testimonials_categories', 10, 1 );

				// Render autocomplete suggestions
				add_filter( 'vc_autocomplete_vcex_testimonials_slider_include_categories_render', 'vcex_render_testimonials_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_testimonials_slider_exclude_categories_render', 'vcex_render_testimonials_categories', 10, 1 );

			}

		}

		/**
		 * Parse old shortcode attributes
		 *
		 * @since 2.0.0
		 */
		public static function edit_form_fields( $atts ) {
			if ( ! empty( $atts['animation'] ) && 'fade' == $atts['animation'] ) {
				$atts['animation'] = 'fade_slides';
			}
			return $atts;
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_testimonials_slider.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {
			return array(
				'name' => __( 'Testimonials Slider', 'total' ),
				'description' => __( 'Recent testimonials slider', 'total' ),
				'base' => 'vcex_testimonials_slider',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-testimonials-slider vcex-icon fa fa-comments-o',
				'params' => array(
					// General
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
						'admin_label' => true,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Classes', 'total' ),
						'param_name' => 'classes',
						'admin_label' => true,
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'yes',
						'heading' => __( 'Author', 'total' ),
						'param_name' => 'display_author_name',
						'vcex' => array( 'on' => 'yes', 'off' => 'no' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'no',
						'heading' => __( 'Company', 'total' ),
						'param_name' => 'display_author_company',
						'vcex' => array( 'on' => 'yes', 'off' => 'no' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Rating', 'total' ),
						'param_name' => 'rating',
					),
					// Slider Settings
					array(
						'type' => 'dropdown',
						'heading' => __( 'Animation', 'total' ),
						'param_name' => 'animation',
						'std' => 'fade_slides',
						'value' => array(
							__( 'Fade', 'total' ) => 'fade_slides',
							__( 'Slide', 'total' ) => 'slide',
						),
						'group' => __( 'Slider Settings', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Auto Play', 'total' ),
						'param_name' => 'slideshow',
						'description' => __( 'Enable automatic slideshow? Disabled in front-end composer to prevent page "jumping".', 'total' ),
						'group' => __( 'Slider Settings', 'total' ),
						'admin_label' => true,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Auto Play Delay', 'total' ),
						'param_name' => 'slideshow_speed',
						'std' => 5000,
						'description' => __( 'Enter a value in milliseconds.', 'total' ),
						'group' => __( 'Slider Settings', 'total' ),
						'dependency' => array( 'element' => 'slideshow', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Loop', 'total' ),
						'param_name' => 'loop',
						'group' => __( 'Slider Settings', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Dot Navigation', 'total' ),
						'param_name' => 'control_nav',
						'group' => __( 'Slider Settings', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Arrows', 'total' ),
						'param_name' => 'direction_nav',
						'group' => __( 'Slider Settings', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Auto Height Animation', 'total' ),
						'std' => 400,
						'param_name' => 'height_animation',
						'group' => __( 'Slider Settings', 'total' ),
						'description' => __( 'You can enter "0.0" to disable the animation completely.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Animation Speed', 'total' ),
						'param_name' => 'animation_speed',
						'std' => 600,
						'description' => __( 'Enter a value in milliseconds.', 'total' ),
						'group' => __( 'Slider Settings', 'total' ),
					),
					// Query
					array(
						'type' => 'textfield',
						'heading' => __( 'Posts Count', 'total' ),
						'param_name' => 'count',
						'value' => 3,
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Offset', 'total' ),
						'param_name' => 'offset',
						'group' => __( 'Query', 'total' ),
						'description' => __( 'Number of post to displace or pass over. Warning: Setting the offset parameter overrides/ignores the paged parameter and breaks pagination. The offset parameter is ignored when posts per page is set to -1.', 'total' ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Include Categories', 'total' ),
						'param_name' => 'include_categories',
						'param_holder_class' => 'vc_not-for-custom',
						'admin_label' => true,
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => false,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Exclude Categories', 'total' ),
						'param_name' => 'exclude_categories',
						'param_holder_class' => 'vc_not-for-custom',
						'admin_label' => true,
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => false,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order', 'total' ),
						'param_name' => 'order',
						'group' => __( 'Query', 'total' ),
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'DESC', 'total' ) => 'DESC',
							__( 'ASC', 'total' ) => 'ASC',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order By', 'total' ),
						'param_name' => 'orderby',
						'value' => vcex_orderby_array(),
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Orderby: Meta Key', 'total' ),
						'param_name' => 'orderby_meta_key',
						'group' => __( 'Query', 'total' ),
						'dependency' => array(
							'element' => 'orderby',
							'value' => array( 'meta_value_num', 'meta_value' ),
						),
					),
					// Image
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'yes',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'display_author_avatar',
						'group' => __( 'Image', 'total' ),
						'vcex' => array( 'on' => 'yes', 'off' => 'no', ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'img_border_radius',
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'display_author_avatar', 'value' => 'yes' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Size', 'total' ),
						'param_name' => 'img_size',
						'std' => 'wpex_custom',
						'value' => vcex_image_sizes(),
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'display_author_avatar', 'value' => 'yes' ),
					),
					array(
						'type' => 'vcex_image_crop_locations',
						'heading' => __( 'Image Crop Location', 'total' ),
						'param_name' => 'img_crop',
						'std' => 'center-center',
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Width', 'total' ),
						'param_name' => 'img_width',
						'description' => __( 'Enter a width in pixels.', 'total' ),
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_height',
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					// Thumbnails
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'no',
						'heading' => __( 'Thumbnails', 'total' ),
						'param_name' => 'control_thumbs',
						'group' => __( 'Thumbnails', 'total' ),
						'vcex' => array( 'off' => 'no', 'on' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Navigation Thumbnails Height', 'total' ),
						'param_name' => 'control_thumbs_height',
						'std' => 50,
						'group' => __( 'Thumbnails', 'total' ),
						'dependency' => array( 'element' => 'control_thumbs', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Navigation Thumbnails Width', 'total' ),
						'param_name' => 'control_thumbs_width',
						'std' => 50,
						'group' => __( 'Thumbnails', 'total' ),
						'dependency' => array( 'element' => 'control_thumbs', 'value' => 'true' ),
					),
					// Excerpts
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'no',
						'heading' => __( 'Excerpt', 'total' ),
						'param_name' => 'excerpt',
						'group' => __( 'Excerpt', 'total' ),
						'vcex' => array( 'off' => 'no', 'on' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Excerpt Length', 'total' ),
						'param_name' => 'excerpt_length',
						'value' => 20,
						'description' => __( 'Enter a custom excerpt length. Will trim the excerpt by this number of words. Enter "-1" to display the_content instead of the auto excerpt.', 'total' ),
						'group' => __( 'Excerpt', 'total' ),
						'dependency' => array( 'element' => 'excerpt', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Read More', 'total' ),
						'param_name' => 'read_more',
						'group' => __( 'Excerpt', 'total' ),
						'dependency' => array( 'element' => 'excerpt', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Read More Text', 'total' ),
						'param_name' => 'read_more_text',
						'group' => __( 'Excerpt', 'total' ),
						'value' => __( 'read more', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					// CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Skin', 'total' ),
						'param_name' => 'skin',
						'group' => __( 'Design', 'total' ),
						'value' => array(
							__( 'Dark Text', 'total' ) => 'dark',
							__( 'Light Text', 'total' ) => 'light',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'font_weight',
						'group' => __( 'Design', 'total' ),
					),

				),
			);
		}

	}
}
new VCEX_Testimonials_Slider_Shortcode;
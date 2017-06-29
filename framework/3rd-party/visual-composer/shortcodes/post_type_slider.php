<?php
/**
 * Visual Composer Post Type Slider
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Post_Type_Flexslider_Shortcode' ) ) {

	class VCEX_Post_Type_Flexslider_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_post_type_flexslider', array( 'VCEX_Post_Type_Flexslider_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_post_type_flexslider', array( 'VCEX_Post_Type_Flexslider_Shortcode', 'map' ) );
			}

			// Admin filters
			if ( is_admin() ) {

				// Get autocomplete suggestion
				add_filter( 'vc_autocomplete_vcex_post_type_flexslider_tax_query_taxonomy_callback', 'vcex_suggest_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_flexslider_tax_query_terms_callback', 'vcex_suggest_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_flexslider_author_in_callback', 'vcex_suggest_users', 10, 1 );

				// Render autocomplete suggestions
				add_filter( 'vc_autocomplete_vcex_post_type_flexslider_tax_query_taxonomy_render', 'vcex_render_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_flexslider_tax_query_terms_render', 'vcex_render_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_flexslider_author_in_render', 'vcex_render_users', 10, 1 );
				
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_post_type_flexslider.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {
			return array(
				'name' => __( 'Post Types Slider', 'total' ),
				'description' => __( 'Posts slider', 'total' ),
				'base' => 'vcex_post_type_flexslider',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-post-type-slider vcex-icon fa fa-files-o',
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
					// Slider Settings
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Randomize', 'total' ),
						'param_name' => 'randomize',
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Animation', 'total' ),
						'param_name' => 'animation',
						'value' => array(
							__( 'Fade', 'total' ) => 'fade_slides',
							__( 'Slide', 'total' ) => 'slide',
						),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Loop', 'total' ),
						'param_name' => 'loop',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Auto Height Animation', 'total' ),
						'std' => '500',
						'param_name' => 'height_animation',
						'description' => __( 'You can enter "0.0" to disable the animation completely.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Animation Speed', 'total' ),
						'param_name' => 'animation_speed',
						'std' => '600',
						'description' => __( 'Enter a value in milliseconds.', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Auto Play', 'total' ),
						'param_name' => 'slideshow',
						'description' => __( 'Enable automatic slideshow? Disabled in front-end composer to prevent page "jumping".', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Auto Play Delay', 'total' ),
						'param_name' => 'slideshow_speed',
						'std' => '5000',
						'description' => __( 'Enter a value in milliseconds.', 'total' ),
						'dependency' => array( 'element' => 'slideshow', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Arrows', 'total' ),
						'param_name' => 'direction_nav',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Arrows on Hover', 'total' ),
						'param_name' => 'direction_nav_hover',
						'dependency' => array( 'element' => 'direction_nav', 'value' => 'true' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Dot Navigation', 'total' ),
						'param_name' => 'control_nav',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Thumbnails', 'total' ),
						'param_name' => 'control_thumbs',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Thumbnails Pointer', 'total' ),
						'param_name' => 'control_thumbs_pointer',
						'dependency' => array( 'element' => 'control_thumbs', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Navigation Thumbnails Height', 'total' ),
						'param_name' => 'control_thumbs_height',
						'std' => '70',
						'dependency' => array( 'element' => 'control_thumbs', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Navigation Thumbnails Width', 'total' ),
						'param_name' => 'control_thumbs_width',
						'std' => '70',
						'dependency' => array( 'element' => 'control_thumbs', 'value' => 'true' ),
					),
					// Query
					array(
						'type' => 'posttypes',
						'heading' => __( 'Post types', 'total' ),
						'param_name' => 'post_types',
						'group' => __( 'Query', 'total' ),
						'std' => 'post',
						'admin_label' => true,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Count', 'total' ),
						'param_name' => 'posts_per_page',
						'value' => '4',
						'description' => __( 'You can enter "-1" to display all posts.', 'total' ),
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Limit By Post ID\'s', 'total' ),
						'param_name' => 'posts_in',
						'group' => __( 'Query', 'total' ),
						'description' => __( 'Seperate by a comma.', 'total' ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Limit By Author', 'total' ),
						'param_name' => 'author_in',
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => false,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
							//'values' => vcex_get_users(),
						),
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Query by Taxonomy', 'total' ),
						'param_name' => 'tax_query',
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Taxonomy Name', 'total' ),
						'param_name' => 'tax_query_taxonomy',
						'dependency' => array( 'element' => 'tax_query', 'value' => 'true' ),
						'settings' => array(
							'multiple' => false,
							'min_length' => 1,
							'groups' => false,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 500,
							'auto_focus' => true,
							//'values' => vcex_get_taxonomies(),
						),
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Terms', 'total' ),
						'param_name' => 'tax_query_terms',
						'dependency' => array( 'element' => 'tax_query', 'value' => 'true' ),
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => true,
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
						'type' => 'dropdown',
						'heading' => __( 'Image Size', 'total' ),
						'param_name' => 'img_size',
						'std' => 'wpex_custom',
						'value' => vcex_image_sizes(),
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'vcex_image_crop_locations',
						'heading' => __( 'Image Crop Location', 'total' ),
						'param_name' => 'img_crop',
						'std' => 'center-center',
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Width', 'total' ),
						'param_name' => 'img_width',
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_height',
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
						'group' => __( 'Image', 'total' )
					),
					// Caption
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Caption', 'total' ),
						'param_name' => 'caption',
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Caption Visibility', 'total' ),
						'param_name' => 'caption_visibility',
						'group' => __( 'Caption', 'total' ),
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Caption Location', 'total' ),
						'param_name' => 'caption_location',
						'value' => array(
							__( 'Over Image', 'total' ) => 'over-image',
							__( 'Under Image', 'total' ) => 'under-image',
						),
						'group' => __( 'Caption', 'total' ),
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Title', 'total' ),
						'param_name' => 'title',
						'group' => __( 'Caption', 'total' ),
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Meta', 'total' ),
						'param_name' => 'meta',
						'group' => __( 'Caption', 'total' ),
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Excerpt', 'total' ),
						'param_name' => 'excerpt',
						'group' => __( 'Caption', 'total' ),
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Excerpt Length', 'total' ),
						'param_name' => 'excerpt_length',
						'value' => '40',
						'group' => __( 'Caption', 'total' ),
						'dependency' => array( 'element' => 'excerpt', 'value' => 'true' ),
					),
					// Design
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design', 'total' ),
					),

				),
				
			);
		}

	}
}
new VCEX_Post_Type_Flexslider_Shortcode;
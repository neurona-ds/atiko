<?php
/**
 * Visual Composer Post Excerpt
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.2
 *
 * @todo Turn into single class
 */

/**
 * Create shortcode for Post Grid
 *
 * @since 4.0
 */
function vcex_post_excerpt_gitem_shortcode( $atts ) {
   return '{{ vcex_post_excerpt:' . http_build_query( (array) $atts ) . ' }}';
}
add_shortcode( 'vcex_gitem_post_excerpt', 'vcex_post_excerpt_gitem_shortcode' );

/**
 * Map Shortcode to grid items
 *
 * @since 4.0
 */
if ( ! function_exists( 'vcex_gitem_post_excerpt_add_grid_shortcodes' ) ) {
	function vcex_gitem_post_excerpt_add_grid_shortcodes( $shortcodes ) {
		$shortcodes['vcex_gitem_post_excerpt'] = array(
			'name'        => __( 'Post Excerpt', 'total' ),
			'base'        => 'vcex_gitem_post_excerpt',
			'icon'        => 'vcex-gitem-post-video vcex-icon fa fa-film',
			'category'    => WPEX_THEME_BRANDING,
			'description' => __( 'Featured post video.', 'total' ),
			'post_type'   => Vc_Grid_Item_Editor::postType(),
			'params'      => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Length', 'total' ),
					'param_name' => 'length',
					'value' => 30,
				),
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Color', 'total' ),
					'param_name' => 'color',
				),
				array(
					'type' => 'vcex_font_family_select',
					'heading' => __( 'Font Family', 'total' ),
					'param_name' => 'font_family',
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Font Size', 'total' ),
					'param_name' => 'font_size',
					'description' => __( 'You can enter a px or em value. Example 13px or 1em.', 'total' ),
					'target' => 'font-size',
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Line Height', 'total' ),
					'param_name' => 'line_height',
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Letter Spacing', 'total' ),
					'param_name' => 'letter_spacing',
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Italic', 'total' ),
					'param_name' => 'italic',
					'value' => array( __( 'No', 'total' ) => 'false', __( 'Yes', 'total' ) => 'true' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => __( 'Font Weight', 'total' ),
					'param_name' => 'font_weight',
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => __( 'Text Align', 'total' ),
					'param_name' => 'text_align',
				),
				array(
					'type' => 'css_editor',
					'heading' => __( 'CSS', 'total' ),
					'param_name' => 'css',
					'group' => __( 'CSS', 'total' ),
				),
			)
		);
		return $shortcodes;
	}
}
add_filter( 'vc_grid_item_shortcodes', 'vcex_gitem_post_excerpt_add_grid_shortcodes' );

/**
 * Add data to the vcex_gitem_post_excerpt shortcode
 *
 * @since 4.0
 */
function vc_gitem_template_attribute_vcex_post_excerpt( $value, $data ) {

	// Extract data
	extract( array_merge( array(
		'output' => '',
		'post'   => null,
		'data'   => '',
	), $data ) );

	$atts = array();
	parse_str( $data, $atts );

	$atts = vc_map_get_attributes( 'vcex_gitem_post_excerpt', $atts );

	// Get video
	$excerpt = wpex_get_excerpt( array(
		'post_id' => $post->ID,
		'length'  => isset( $atts['length'] ) ? $atts['length'] : '30',
	) );

	if ( ! $excerpt && 'vc_grid_item' == get_post_type( $post->ID ) ) {
		$excerpt = __( 'Sample text for item preview.', 'total' );
	}

	if ( ! $excerpt ) {
		return;
	}

	$attrs = array(
		'class' => 'vcex-gitem-post-excerpt wpex-clr',
	);

	$attrs['style'] = vcex_inline_style( array(
		'color'          => isset( $atts['color'] ) ? $atts['color'] : '',
		'font_family'    => isset( $atts['font_family'] ) ? $atts['font_family'] : '',
		'font_size'      => isset( $atts['font_size'] ) ? $atts['font_size'] : '',
		'letter_spacing' => isset( $atts['letter_spacing'] ) ? $atts['letter_spacing'] : '',
		'font_weight'    => isset( $atts['font_weight'] ) ? $atts['font_weight'] : '',
		'text_align'     => isset( $atts['text_align'] ) ? $atts['text_align'] : '',
		'line_height'    => isset( $atts['line_height'] ) ? $atts['line_height'] : '',
		'width'          => isset( $atts['width'] ) ? $atts['width'] : '',
		'font_style'     => ( isset( $atts['italic'] ) && 'true' == $atts['italic'] ) ? 'italic' : '',
	), false );

	if ( ! empty( $atts['css'] ) ) {
		$attrs['class'] .= ' '. vc_shortcode_custom_css_class( $atts['css'] );
	}

	$output .= '<div '. wpex_parse_attrs( $attrs ) .'>';

		$output .= wp_kses_post( $excerpt );
		
	$output .= '</div>';

	return $output;

}
add_filter( 'vc_gitem_template_attribute_vcex_post_excerpt', 'vc_gitem_template_attribute_vcex_post_excerpt', 10, 2 );
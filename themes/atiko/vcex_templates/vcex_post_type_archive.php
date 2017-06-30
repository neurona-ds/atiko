<?php
/**
 * Visual Composer Post Type Archive
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Not needed in admin ever
if ( is_admin() ) {
    return;
}

// Required VC functions
if ( ! function_exists( 'vc_map_get_attributes' ) ) {
	vcex_function_needed_notice();
	return;
}

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_post_type_archive', $atts );

// Make sure post type param is not empty
$atts['post_type'] = ! empty( $atts['post_type'] ) ? $atts['post_type'] : 'post';

// Build the WordPress query
$my_query = vcex_build_wp_query( $atts );

// Set post to blog
$post_type = $atts['post_type'];
$post_type = ( 'post' == $post_type ) ? 'blog' : $post_type;

// Output posts
if ( $my_query->have_posts() ) :

	// Wrapper classes
	$wrap_classes = array( 'vcex-module', 'vcex-post-type-archive', 'clr' );
	if ( $atts['css_animation'] && 'none' != $atts['css_animation'] ) {
   		$wrap_classes[] = vcex_get_css_animation( $atts['css_animation'] );
	}
	if ( $atts['classes'] ) {
	    $wrap_classes[] = vcex_get_extra_class( $atts['classes'] );
	}
	if ( $atts['visibility'] ) {
	    $wrap_classes[] = $atts['visibility'];
	}
	$wrap_classes = implode( ' ', $wrap_classes );
	$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_post_type_archive', $atts ); ?>
	
	<div class="<?php echo esc_attr( $wrap_classes ); ?>"<?php vcex_unique_id( $atts['unique_id'] ); ?>>

		<?php
		// Get loop top
		get_template_part( 'partials/loop/loop-top', $post_type );

			// Define counter var to clear floats
			$wpex_count='';

			// Loop through posts
			while ( $my_query->have_posts() ) :

				// Get post from query
				$my_query->the_post();

				// Create new post object.
				$post = new stdClass();

					// Get content template part
					get_template_part( 'partials/loop/loop', $post_type );

			// End loop
			endwhile;

		// Get loop bottom
		get_template_part( 'partials/loop/loop-bottom', $post_type );
		
		// Display pagination if enabled
		if ( 'true' == $atts['pagination'] ) :

			wpex_pagination( $my_query );

		endif; ?>

	</div>
	
	<?php
	// Reset the post data to prevent conflicts with WP globals
	wp_reset_postdata(); ?>

<?php
// If no posts are found display message
else : ?>

	<?php
	// Display no posts found error if function exists
	echo vcex_no_posts_found_message( $atts ); ?>

<?php
// End post check
endif; ?>
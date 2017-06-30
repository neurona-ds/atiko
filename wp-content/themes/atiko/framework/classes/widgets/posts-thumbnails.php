<?php
/**
 * Recent posts with Thumbnails custom widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start class
if ( ! class_exists( 'WPEX_Recent_Posts_Thumbnails_Widget' ) ) {

	class WPEX_Recent_Posts_Thumbnails_Widget extends WP_Widget {
		private $defaults;

		/**
		 * Register widget with WordPress.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// Defaults
			$this->defaults = array(
				'title'             => '',
				'number'            => '3',
				'style'             => 'default',
				'post_type'         => 'post',
				'taxonomy'          => '',
				'terms'             => '',
				'terms_exclude'     => '',
				'order'             => 'DESC',
				'orderby'           => 'date',
				'columns'           => '3',
				'img_size'          => 'wpex_custom',
				'img_hover'         => '',
				'img_width'         => '',
				'img_height'        => '',
				'date'              => '',
				'excerpt_length'    => '0',
				'thumbnail_query'   => true,
				'img_crop_location' => '',
			);

			// Construtor
			$branding = wpex_get_theme_branding();
			$branding = $branding ? $branding . ' - ' : '';
			parent::__construct(
				'wpex_recent_posts_thumb',
				$branding . esc_html__( 'Posts With Thumbnails', 'total' ),
				array(
					'customize_selective_refresh' => true,
				)
			);

		}

		/**
		 * Front-end display of widget.
		 *
		 * @since 1.0.0
		 */
		public function widget( $args, $instance ) {

			// Parse instance
			extract( wp_parse_args( $instance, $this->defaults ) );

			// Define output
			$output = '';

			// Apply filters to the title
			$title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';

			// Before widget WP hook
			$output .= $args['before_widget'];

			// Display title if defined
			if ( $title ) {

				$output .= $args['before_title'];

					$output .= esc_html( $title );

				$output .= $args['after_title'];
			}

			// Query posts
			$query_args = array(
				'post_type'      => $post_type,
				'posts_per_page' => $number,
				'no_found_rows'  => true,
				'tax_query'      => array(
					'relation' => 'AND',
				),
			);

			// Query by thumbnail meta_key
			if ( $thumbnail_query ) {
				$query_args['meta_key'] = '_thumbnail_id';
			}

			// Order params - needs FALLBACK don't ever edit!
			if ( ! empty( $orderby ) ) {
				$query_args['order']   = $order;
				$query_args['orderby'] = $orderby;
			} else {
				$query_args['orderby'] = $order; // THIS IS THE FALLBACK
			}

			// Tax Query
			if ( ! empty( $taxonomy ) ) {

				// Include Terms
				if (  ! empty( $terms ) ) {

					// Sanitize terms and convert to array
					$terms = str_replace( ', ', ',', $terms );
					$terms = explode( ',', $terms );

					// Add to query arg
					$query_args['tax_query'][] = array(
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $terms,
						'operator' => 'IN',
					);

				}

				// Exclude Terms
				if ( ! empty( $terms_exclude ) ) {

					// Sanitize terms and convert to array
					$terms_exclude = str_replace( ', ', ',', $terms_exclude );
					$terms_exclude = explode( ',', $terms_exclude );

					// Add to query arg
					$query_args['tax_query'][] = array(
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $terms_exclude,
						'operator' => 'NOT IN',
					);

				}

			}

			// Exclude current post
			if ( is_singular() ) {
				$query_args['post__not_in'] = array( get_the_ID() );
			}

			// Query posts
			$wpex_query = new WP_Query( $query_args );

			// If there are posts loop through them
			if ( $wpex_query->have_posts() ) :

				// Begin entries output
				$output .= '<ul class="wpex-widget-recent-posts wpex-clr style-' . esc_attr( $style ) . '">';

						// Loop through posts
						while ( $wpex_query->have_posts() ) : $wpex_query->the_post();

							// Check thumb
							$has_thumb = has_post_thumbnail();

							// Li classes
							$li_classes = 'wpex-widget-recent-posts-li clr';
							if ( ! $has_thumb ) {
								$li_classes .= ' wpex-no-thumb';
							}

							// Output entry
							$output .= '<li class="' . $li_classes . '">';

								// Get post data
								$permalink = wpex_get_permalink();
								$esc_title = wpex_get_esc_title();

								// Display thumbnail
								if ( $has_thumb ) :

									// Thumb chasses
									$thumb_classes = 'wpex-widget-recent-posts-thumbnail';
									if ( $img_hover = esc_attr( $img_hover ) ) {
										$thumb_classes .= ' ' . wpex_image_hover_classes( $img_hover );
									}

									$output .= '<a href="'. $permalink .'" title="'. $esc_title .'" class="'. $thumb_classes .'">';

										$output .= wpex_get_post_thumbnail( array(
											'size'   => $img_size,
											'width'  => $img_width,
											'height' => $img_height,
											'crop'   => $img_crop_location,
											'alt'    => wpex_get_esc_title(),
										) );

									$output .= '</a>';

								endif;

								$output .= '<div class="details clr">';

									$output .= '<a href="' . $permalink . '" title="' . $esc_title . '" class="wpex-widget-recent-posts-title">' . get_the_title() . '</a>';

									// Display date if enabled
									if ( '1' != $date ) :

										$output .= '<div class="wpex-widget-recent-posts-date">' . get_the_date() . '</div>';

									endif;

									// Display excerpt
									if ( intval( $excerpt_length ) && 0 !== $excerpt_length ) {

										$excerpt = wpex_get_excerpt( array(
											'length'          => $excerpt_length,
											'context'         => 'wpex_recent_posts_thumb_widget',
											'custom_excerpts' => false,
										) );

										if ( $excerpt ) {

											$output .= '<div class="wpex-widget-recent-posts-excerpt">' . $excerpt . '</div>';

										}

									}

								$output .= '</div>';

							$output .= '</li>';

						endwhile;

				$output .= '</ul>';

				// Reset post data
				wp_reset_postdata();

			endif;

			// After widget WordPress hook
			$output .= $args['after_widget'];

			// Echo output
			echo $output;

		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @since 1.0.0
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['post_type'] = ! empty( $new_instance['post_type'] ) ? strip_tags( $new_instance['post_type'] ) : '';
			$instance['taxonomy'] = ! empty( $new_instance['taxonomy'] ) ? strip_tags( $new_instance['taxonomy'] ) : '';
			$instance['terms'] = ! empty( $new_instance['terms'] ) ? strip_tags( $new_instance['terms'] ) : '';
			$instance['terms_exclude'] = ! empty( $new_instance['terms_exclude'] ) ? strip_tags( $new_instance['terms_exclude'] ) : '';
			$instance['number'] = ! empty( $new_instance['number'] ) ? strip_tags( $new_instance['number'] ) : '';
			$instance['excerpt_length'] = ! empty( $new_instance['excerpt_length'] ) ? intval( $new_instance['excerpt_length'] ) : '';
			$instance['order'] = ! empty( $new_instance['order'] ) ? strip_tags( $new_instance['order'] ) : '';
			$instance['orderby'] = ! empty( $new_instance['orderby'] ) ? strip_tags( $new_instance['orderby'] ) : '';
			$instance['style'] = ! empty( $new_instance['style'] ) ? strip_tags( $new_instance['style'] ) : '';
			$instance['img_hover'] = ! empty( $new_instance['img_hover'] ) ? strip_tags( $new_instance['img_hover'] ) : '';
			$instance['img_size'] = ! empty( $new_instance['img_size'] ) ? strip_tags( $new_instance['img_size'] ) : 'wpex_custom';
			$instance['img_height'] = ! empty( $new_instance['img_height'] ) ? intval( $new_instance['img_height'] ) : '';
			$instance['img_width'] = ! empty( $new_instance['img_width'] ) ? intval( $new_instance['img_width'] ) : '';
			$instance['date'] = isset( $new_instance['date'] ) ? true : false;
			$instance['thumbnail_query'] = isset( $new_instance['thumbnail_query'] ) ? true : false;
			$instance['img_crop_location'] = ! empty( $new_instance['img_crop_location'] ) ? strip_tags( $new_instance['img_crop_location'] ) : '';
			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @since 1.0.0
		 */
		public function form( $instance ) {

			extract( wp_parse_args( ( array ) $instance, $this->defaults ) ); ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'total' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Style', 'total' ); ?></label>
				<br />
				<select class='wpex-select' name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
					<option value="default" <?php selected( $style, 'default' ); ?>><?php esc_html_e( 'Small Image', 'total' ); ?></option>
					<option value="fullimg" <?php selected( $style, 'fullimg' ); ?>><?php esc_html_e( 'Full Image', 'total' ); ?></option>
				</select>
			</p>

			<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>"><?php esc_html_e( 'Post Type', 'total' ); ?></label>
			<br />
			<select class='wpex-select' name="<?php echo esc_attr( $this->get_field_name( 'post_type' ) ); ?>" style="width:100%;">
				<option value="post" <?php selected( $post_type, 'post' ); ?>><?php esc_html_e( 'Post', 'total' ); ?></option>
				<?php
				// Get Post Types and loop through them to create dropdown
				$get_post_types = wpex_get_post_types();
				foreach ( $get_post_types as $key => $val ) : ?>
					<?php if ( $key != 'post' ) { ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $post_type, $key ); ?>><?php echo  $val; ?></option>
					<?php } ?>
				<?php endforeach; ?>
			</select>
			</p>

			<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>"><?php esc_html_e( 'Query By Taxonomy', 'total' ); ?></label>
			<br />
			<select class='wpex-select' name="<?php echo esc_attr( $this->get_field_name( 'taxonomy' ) ); ?>" style="width:100%;">
				<option value="" <?php if ( ! $taxonomy ) { ?>selected="selected"<?php } ?>><?php esc_html_e( 'No', 'total' ); ?></option>
				<?php
				// Get Taxonomies
				$get_taxonomies = get_taxonomies( array(
					'public' => true,
				), 'objects' ); ?>
				<?php foreach ( $get_taxonomies as $get_taxonomy ) : ?>
					<option value="<?php echo esc_attr( $get_taxonomy->name ); ?>" <?php selected( $taxonomy, $get_taxonomy->name ); ?>><?php echo ucfirst( $get_taxonomy->labels->singular_name ); ?></option>
				<?php endforeach; ?>
			</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'terms' ) ); ?>"><?php esc_html_e( 'Include Terms', 'total' ); ?></label>
				<br />
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'terms' ) ); ?>" type="text" value="<?php echo esc_attr( $terms ); ?>" />
				<small><?php esc_html_e( 'Enter the term slugs to query by seperated by a "comma"', 'total' ); ?></small>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'terms_exclude' ) ); ?>"><?php esc_html_e( 'Exclude Terms', 'total' ); ?></label>
				<br />
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'terms_exclude' ) ); ?>" type="text" value="<?php echo esc_attr( $terms_exclude ); ?>" />
				<small><?php esc_html_e( 'Enter the term slugs to query by seperated by a "comma"', 'total' ); ?></small>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order', 'total' ); ?></label>
				<br />
				<select class='wpex-select' name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
					<option value="DESC" <?php selected( $order, 'DESC', true ); ?>><?php esc_html_e( 'Descending', 'total' ); ?></option>
					<option value="ASC" <?php selected( $order, 'ASC', true ); ?>><?php esc_html_e( 'Ascending', 'total' ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order By', 'total' ); ?>:</label>
				<br />
				<select class='wpex-select' name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>">
				<?php
				// Orderby options
				$orderby_array = array(
					'date'          => __( 'Date', 'total' ),
					'title'         => __( 'Title', 'total' ),
					'modified'      => __( 'Modified', 'total' ),
					'author'        => __( 'Author', 'total' ),
					'rand'          => __( 'Random', 'total' ),
					'comment_count' => __( 'Comment Count', 'total' ),
				);
				foreach ( $orderby_array as $key => $value ) { ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $orderby, $key ); ?>>
						<?php echo strip_tags( $value ); ?>
					</option>
				<?php } ?>
				</select>
			</p>

			<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number', 'total' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" />
			</p>

			<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>"><?php esc_html_e( 'Excerpt Length', 'total' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_length' ) ); ?>" type="text" value="<?php echo esc_attr( $excerpt_length ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'img_hover' ) ); ?>"><?php esc_html_e( 'Image Hover', 'total' ); ?></label>
				<br />
				<select class='wpex-select' name="<?php echo esc_attr( $this->get_field_name( 'img_hover' ) ); ?>" style="width:100%;">
					<?php
					// Get image sizes
					$hovers = wpex_image_hovers();
					// Loop through hovers and add options
					foreach ( $hovers as $key => $val ) { ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $img_hover, $key ); ?>>
							<?php echo strip_tags( $val ); ?>
						</option>
					<?php } ?>

				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'img_size' ) ); ?>"><?php esc_html_e( 'Image Size', 'total' ); ?></label>
				<br />
				<select class='wpex-select' name="<?php echo esc_attr( $this->get_field_name( 'img_size' ) ); ?>" style="width:100%;">
				<option value="wpex_custom" <?php selected( $img_size, 'wpex_custom' ); ?>><?php esc_html_e( 'Custom', 'total' ); ?></option>
					<?php
					// Get image sizes
					$get_img_sizes = wpex_get_thumbnail_sizes();
					// Loop through image sizes
					foreach ( $get_img_sizes as $key => $val ) { ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $img_size, $key ); ?>><?php echo strip_tags( $key ); ?></option>
					<?php } ?>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'img_width' ) ); ?>"><?php esc_html_e( 'Image Crop Width', 'total' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'img_width' ) ); ?>" type="text" value="<?php echo esc_attr( $img_width ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'img_height' ) ); ?>"><?php esc_html_e( 'Image Crop Height', 'total' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'img_height' ) ); ?>" type="text" value="<?php echo esc_attr( $img_height ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'img_crop_location' ) ); ?>"><?php esc_html_e( 'Image Crop Location', 'total' ); ?></label>
				<br />
				<select class='wpex-select' name="<?php echo esc_attr( $this->get_field_name( 'img_crop_location' ) ); ?>" style="width:100%;">
					<?php
					// Get crop locations
					$crop_locations = wpex_image_crop_locations(); ?>

					<?php foreach ( $crop_locations as $key => $val ) { ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $img_crop_location, $key ); ?>><?php echo strip_tags( $key ); ?></option>
					<?php } ?>
				</select>
			</p>

			<p>
				<input name="<?php echo esc_attr( $this->get_field_name( 'date' ) ); ?>" type="checkbox" value="1" <?php checked( $date, '1', true ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"><?php esc_html_e( 'Disable Date?', 'total' ); ?></label>
			</p>

			<p>
				<input name="<?php echo esc_attr( $this->get_field_name( 'thumbnail_query' ) ); ?>" type="checkbox" value="1" <?php checked( $thumbnail_query, '1', true ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'thumbnail_query' ) ); ?>"><?php esc_html_e( 'Post With Thumbnails Only', 'total' ); ?></label>
			</p>

		<?php
		}
		
	}

}
register_widget( 'WPEX_Recent_Posts_Thumbnails_Widget' );
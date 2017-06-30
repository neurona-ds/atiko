<?php
/**
 * Instagram Slider Widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 4.0
 */

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

// Start widget class
if ( ! class_exists( 'WPEX_Instagram_Grid_Widget' ) ) {

	class WPEX_Instagram_Grid_Widget extends WP_Widget {
		
		/**
		 * Register widget with WordPress.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$branding = wpex_get_theme_branding();
			$branding = $branding ? $branding . ' - ' : '';
			parent::__construct(
				'wpex_insagram_slider',
				$branding . __( 'Instagram Grid', 'total' ),
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

			// Define vars
			$output     = '';
			$title      = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
			$username   = empty( $instance['username'] ) ? '' : $instance['username'];
			$number     = empty( $instance['number'] ) ? 9 : $instance['number'];
			$columns    = empty( $instance['columns'] ) ? '3' : $instance['columns'];
			$gap        = empty( $instance['gap'] ) ? '10' : $instance['gap'];
			$target     = empty( $instance['target'] ) ? ' target="_blank"' : $instance['target'];
			$size       = empty( $instance['size'] ) ? 'thumbnail' : $instance['size'];
			$responsive = empty( $instance['responsive'] ) ? true : false;

			// Prevent size issues
			if ( ! in_array( $size, array( 'thumbnail', 'small', 'large', 'original' ) ) ) {
				$size = 'thumbnail';
			}

			// Before widget hook
			$output .= $args['before_widget'];

			// Display widget title
			if ( $title ) {
				$output .= $args['before_title'] . $title . $args['after_title'];
			}

			// Display notice for username not added
			if ( ! $username ) {

				$output .= '<p>'. esc_html__( 'Please enter an instagram username for your widget.', 'total' ) .'</p>';

			} else {

				// Get instagram images
				$media_array = wpex_fetch_instagram_feed( $username, $number );

				// Display error message
				if ( is_wp_error( $media_array ) ) {

					$output .= strip_tags( $media_array->get_error_message() );

				}

				// Display instagram feed
				elseif ( is_array( $media_array ) ) {
					
					// Set correct gap class
					$gap = 'gap-'. $gap;

					$output .= '<div class="wpex-instagram-grid-widget wpex-clr">';

						$output .= '<ul class="wpex-clr wpex-row '. esc_attr( $gap ) .'">';

						$count = 0;

						foreach ( $media_array as $item ) {

							$image = isset( $item['display_src'] ) ? $item['display_src'] : '';

							if ( 'thumbnail' == $size ) {
								$image = ! empty( $item['thumbnail_src'] ) ? $item['thumbnail_src'] : $image;
								$image = ! empty( $item['thumbnail'] ) ? $item['thumbnail'] : $image;
							} elseif ( 'small' == $size ) {
								$image = ! empty( $item['small'] ) ? $item['small'] : $image;
							} elseif ( 'large' == $size ) {
								$image = ! empty( $item['large'] ) ? $item['large'] : $image;
							} elseif ( 'original' == $size ) {
								$image = ! empty( $item['original'] ) ? $item['original'] : $image;
							}

							if ( $image ) {

								$count++;
								
								if ( strpos( $item['link'], 'http' ) === false ) {
									$item['link'] = str_replace( '//', 'https://', $item['link'] );
								}
								
								$classes = wpex_grid_class( $columns ) .' clr count-'. esc_attr( $count );
								
								if ( 'false' == $responsive ) {
									$classes .= ' nr-col';
								} else {
									$classes .= ' col';
								}

								$output .= '<li class="'. $classes .'">';

									$output .= '<a href="'. esc_url( $item['link'] ) .'" title="'. esc_attr( $item['description'] ) .'"'. esc_attr( $target ) .'>';

											$output .= '<img src="'. esc_url( $image ) .'"  alt="'. esc_attr( $item['description'] ) .'" />';

										$output .= '</a>';

									$output .= '</li>';

								if ( $columns == $count ) {
									$count = 0;
								}

							}
						}

						$output .= '</ul>';
						
					$output .= '</div>';

				}

			}

			$output .= $args['after_widget'];

			echo $output;
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @since 1.0.0
		 */
		public function update( $new_instance, $old_instance ) {
			$instance               = $old_instance;
			$instance['title']      = strip_tags( $new_instance['title'] );
			$instance['size']       = isset( $new_instance['size'] ) ? strip_tags( $new_instance['size'] ) : 'thumbnail';
			$instance['username']   = isset( $new_instance['username'] ) ? trim( strip_tags( $new_instance['username'] ) ) : '';
			$instance['number']     = ! empty( $new_instance['number'] ) ? intval( $new_instance['number'] ) : 9;
			$instance['target']     = $new_instance['target'] == 'blank' ? $new_instance['target'] : '';
			$instance['columns']    = isset( $new_instance['columns'] ) ? intval( $new_instance['columns'] ) : '';
			$instance['gap']        = isset( $new_instance['gap'] ) ? strip_tags( $new_instance['gap'] ) : '';
			$instance['responsive'] = isset( $new_instance['responsive'] ) ? true : false;
			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @since 1.0.0
		 */
		public function form( $instance ) {

			extract( wp_parse_args( ( array ) $instance, array(
				'title'      => '',
				'username'   => '',
				'number'     => '9',
				'columns'    => '3',
				'target'     => '_self',
				'size'       => 'thumbnail',
				'responsive' => '',
				'gap'        => '',
			) ) ); ?>
			
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'total' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php esc_html_e( 'Username', 'total' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" type="text" value="<?php echo esc_attr( $username ); ?>" /></label></p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Size', 'total' ); ?>:</label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>" class="widefat">
					<option value="thumbnail" <?php selected( 'thumbnail', $size ) ?>><?php esc_html_e( 'Thumbnail', 'total' ); ?></option>
					<option value="small" <?php selected( 'small', $size ) ?>><?php esc_html_e( 'Small', 'total' ); ?></option>
					<option value="large" <?php selected( 'large', $size ) ?>><?php esc_html_e( 'Large', 'total' ); ?></option>
					<option value="original" <?php selected( 'original', $size ) ?>><?php esc_html_e( 'Original', 'total' ); ?></option>
				</select>
			</p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php esc_html_e( 'Columns', 'total' ); ?>:</label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" class="widefat">
					<?php
					$wpex_columns = wpex_grid_columns();
					foreach ( $wpex_columns as $key => $label ) { ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $columns ) ?>><?php echo esc_html( $label ); ?></option>
					<?php } ?>
				</select>
			</p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'gap' ) ); ?>"><?php esc_html_e( 'Gap', 'total' ); ?>:</label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'gap' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'gap' ) ); ?>" class="widefat">
					<?php
					$gaps = wpex_column_gaps();
					foreach ( $gaps as $key => $label ) { ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $gap ) ?>><?php echo esc_html( $label ); ?></option>
					<?php } ?>
				</select>
			</p>

			<p><input name="<?php echo esc_attr( $this->get_field_name( 'responsive' ) ); ?>" type="checkbox" <?php checked( $responsive, true, true ); ?> /><label for="<?php echo esc_attr( $this->get_field_id( 'responsive' ) ); ?>"><?php esc_html_e( 'Responsive', 'total' ); ?></label></p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of photos', 'total' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" /></label><small><?php esc_html_e( 'Max 12 items.', 'total' ); ?></small></p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>"><?php esc_html_e( 'Open links in', 'total' ); ?>:</label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" class="widefat">
					<option value="_self" <?php selected( '_self', $target ) ?>><?php esc_html_e( 'Current window', 'total' ); ?></option>
					<option value="_blank" <?php selected( '_blank', $target ) ?>><?php esc_html_e( 'New window', 'total' ); ?></option>
				</select>
			</p>

			<p style="background:#f9f9f9;padding:10px;border:1px solid #ededed;"><strong><?php esc_html_e( 'Cache Notice', 'total' ); ?></strong>: <?php esc_html_e( 'The Instagram feed is refreshed every 2 hours.', 'total' ); ?></p>

			<?php
		}

	}
	
}
register_widget( 'WPEX_Instagram_Grid_Widget' );
<?php
/**
 * Users Grid Widget
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
if ( ! class_exists( 'WPEX_Users_Grid_Widget' ) ) {

	class WPEX_Users_Grid_Widget extends WP_Widget {

		private $settings;

		/**
		 * Register widget with WordPress.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// Get branding
			$branding = wpex_get_theme_branding();
			$branding = $branding ? $branding . ' - ' : '';

			// Main constructor
			parent::__construct(
				'wpex_users_grid',
				$branding . __( 'Users', 'total' ),
				array(
					'customize_selective_refresh' => true,
				)
			);

			// Settings array
			$this->settings = array(
				'title'         => '',
				'class'         => '',
				'columns'       => '4',
				'columns_gap'   => '5',
				'offset'        => '',
				'order'         => 'ASC',
				'orderby'       => 'login',
				'show_name'     => 'off',
				'admins'        => 'on',
				'editors'       => 'on',
				'authors'       => 'on',
				'contributors'  => 'on',
				'subscribers'   => 'off',
				'include'       => '',
				'img_size'      => '70',
				'img_hover'     => 'opacity',
				'link_to_posts' => 'on',
				'show_name'     => '',
			);

			// Checkbox settings
			$this->checkbox_settings = array( 'admins', 'authors', 'editors', 'contributors', 'subscribers', 'link_to_posts', 'show_name' );

		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 * @since 1.0.0
		 *
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {

			// Loop through settings
			$settings = $this->settings;
			$sanitized_settings = array();
			foreach ( $settings as $setting_id => $default ) {
				$val = ! empty( $instance[$setting_id] ) ? $instance[$setting_id] : $default;
				if ( 'on' == $val ) {
					$val = true;
				} elseif ( 'off' == $val ) {
					$val = false;
				}
				$sanitized_settings[$setting_id] = $val;
			}
			$sanitized_settings = apply_filters( 'wpex_users_grid_widget_settings', $sanitized_settings, $sanitized_settings['class'] );
			extract( $sanitized_settings );

			// Output var
			$output = '';

			// Before widget WP hook
			$output .= $args['before_widget'];

			// Display title
			if ( $title = apply_filters( 'widget_title', $title ) ) {

				$output .= $args['before_title'];

					$output .= esc_html( $title );

				$output .= $args['after_title'];
			}

			// Query users
			$query_args = array(
				'orderby' => $orderby,
				'order'   => $order,
			);
			$role_in = array();
			if ( $admins ) {
				$role_in[] = 'administrator';
			}
			if ( $authors ) {
				$role_in[] = 'author';
			}
			if ( $contributors ) {
				$role_in[] = 'contributor';
			}
			if ( $role_in ) {
				$query_args['role__in'] = $role_in;
			}
			$get_users = get_users( $query_args );

			if ( $get_users ) :

				$output .= '<ul class="wpex-users-widget wpex-row '. wpex_gap_class( $columns_gap ) .' clr">';

					$count=0;

					foreach ( $get_users as $user ) :

						$count++;
						$classes = 'nr-col clr';
						$classes .= ' '. wpex_grid_class( $columns );
						$classes .= ' col-'. $count;

						$output .= '<li class="'. $classes .'">';

							// Open link tag
							if ( $link_to_posts ) {

								$output .= '<a href="'. esc_url( get_author_posts_url( $user->ID, $user->user_nicename ) ) .'" title="'. esc_attr( $user->display_name ) .' '. esc_html__( 'Archive', 'total' ) .'">';

							}

							// Display avatar
							$output .= '<div class="wpex-users-widget-avatar '. wpex_image_hover_classes( $img_hover ) .'">';

								$output .= get_avatar( $user->ID, $img_size, '', $user->display_name );

							$output .= '</div>';

							// Display name
							if ( $show_name ) {

								$output .= '<div class="wpex-users-widget-name entry-title">';

									$output .= esc_html( $user->display_name );

								$output .= '</div>';

							}

							// Close link
							if ( $link_to_posts ) {
								$output .= '</a>';
							}

						$output .= '</li>';

					// Clear columns
					if ( $columns == $count ) {
						$count = 0;
					}

					// End loop
					endforeach;

				// Close ul wrap
				$output .= '</ul>';

			endif;

			// After widget hook
			$output .= $args['after_widget'];

			// Echo output
			echo $output;
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 * @since 1.0.0
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance   = $old_instance;
			$settings   = $this->settings;
			$checkboxes = $this->checkbox_settings;
			foreach ( $settings as $setting_id => $default ) {
				if ( in_array( $setting_id, $checkboxes ) ) {
					$instance[$setting_id] = isset( $new_instance[$setting_id] ) ? 'on' : 'off';
				} else {
					$instance[$setting_id] = ! empty( $new_instance[$setting_id] ) ? strip_tags( $new_instance[$setting_id] ) : $default;
				}
			}
			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 * @since 1.0.0
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {

			// Parseand extract instance/settings
			extract( wp_parse_args( ( array ) $instance, $this->settings ) );

			// Store arrays
			$get_gaps    = wpex_column_gaps();
			$get_columns = wpex_grid_columns(); ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'total' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title', 'total' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"><?php esc_html_e( 'Custom Class', 'total' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'class', 'total' ) ); ?>" type="text" value="<?php echo esc_attr( $class ); ?>" />
				<small><?php esc_html_e( 'Optional classname for styling purposes.', 'total' ); ?></small>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order', 'total' ); ?>:</label>
				<br />
				<select class='wpex-select' name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>">
				<option value="ASC" <?php selected( $order, 'ASC' ) ?>><?php esc_html_e( 'Ascending', 'total' ); ?></option>
				<option value="DESC" <?php selected( $order, 'DESC' ); ?>><?php esc_html_e( 'Descending', 'total' ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order By', 'total' ); ?>:</label>
				<br />
				<select class='wpex-select' name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>">
					<?php $orderby_array = array(
						'ID'           => esc_html__( 'ID', 'total' ),
						'login'        => esc_html__( 'Login', 'total' ),
						'nicename'     => esc_html__( 'Nicename', 'total' ),
						'email'        => esc_html__( 'Email', 'total' ),
						'url'          => esc_html__( 'Url', 'total' ),
						'registered'   => esc_html__( 'Registered', 'total' ),
						'display_name' => esc_html__( 'Display Name', 'total' ),
						'post_count'   => esc_html__( 'Post Count', 'total' ),
					);
					foreach ( $orderby_array as $key => $value ) { ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $orderby, $key ); ?>>
							<?php echo esc_attr( $value ); ?>
						</option>
					<?php } ?>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php esc_html_e( 'Columns', 'total' ); ?>:</label>
				<br />
				<select class='wpex-select' name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>">
					<?php foreach ( $get_columns as $key ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $columns, $key ); ?>><?php echo esc_html( $key ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'columns_gap' ) ); ?>"><?php esc_html_e( 'Column Gap', 'total' ); ?>:</label>
				<br />
				<select class='wpex-select' name="<?php echo esc_attr( $this->get_field_name( 'columns_gap' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'columns_gap' ) ); ?>">
					option value="5" <?php selected( $columns_gap, 1 ); ?>>1</option>
					<?php foreach ( $get_gaps as $key => $val ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $columns_gap, $key ); ?>><?php echo esc_html( $val ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'img_size' ) ); ?>"><?php esc_html_e( 'Image Size', 'total' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'img_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'img_size' ) ); ?>" type="text" value="<?php echo esc_attr( intval( $img_size ) ); ?>" />
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
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $img_hover, $key ); ?>><?php echo esc_html( $val ); ?></option>
					<?php } ?>
				</select>
			</p>

			<p>
				<input name="<?php echo esc_attr( $this->get_field_name( 'admins' ) ); ?>" type="checkbox" value="on" <?php checked( $admins, 'on', true ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'admins' ) ); ?>"><?php esc_html_e( 'Included Administrators?', 'total' ); ?></label>
			</p>

			<p>
				<input name="<?php echo esc_attr( $this->get_field_name( 'editors' ) ); ?>" type="checkbox" value="on" <?php checked( $editors, 'on', true ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'editors' ) ); ?>"><?php esc_html_e( 'Included Editors?', 'total' ); ?></label>
			</p>

			<p>
				<input name="<?php echo esc_attr( $this->get_field_name( 'authors' ) ); ?>" type="checkbox" value="on" <?php checked( $authors, 'on', true ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'authors' ) ); ?>"><?php esc_html_e( 'Included Authors?', 'total' ); ?></label>
			</p>

			<p>
				<input name="<?php echo esc_attr( $this->get_field_name( 'contributors' ) ); ?>" type="checkbox" value="on" <?php checked( $contributors, 'on', true ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'contributors' ) ); ?>"><?php esc_html_e( 'Included Contributors?', 'total' ); ?></label>
			</p>

			<p>
				<input name="<?php echo esc_attr( $this->get_field_name( 'subscribers' ) ); ?>" type="checkbox" value="on" <?php checked( $subscribers, 'on', true ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'subscribers' ) ); ?>"><?php esc_html_e( 'Included Subscribers?', 'total' ); ?></label>
			</p>

			<p>
				<input name="<?php echo esc_attr( $this->get_field_name( 'link_to_posts' ) ); ?>" type="checkbox" value="on" <?php checked( $link_to_posts, 'on', true ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'link_to_posts' ) ); ?>"><?php esc_html_e( 'Link to user posts page?', 'total' ); ?></label>
			</p>

			<p>
				<input name="<?php echo esc_attr( $this->get_field_name( 'show_name' ) ); ?>" type="checkbox" value="on" <?php checked( $show_name, 'on', true ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_name' ) ); ?>"><?php esc_html_e( 'Display Name?', 'total' ); ?></label>
			</p>

			<?php
		}

	}

}
register_widget( 'WPEX_Users_Grid_Widget' );
<?php
/**
 * bbPress Stats Widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 3.6.0
 */

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

// Start widget class
if ( ! class_exists( 'WPEX_bbPress_Forum_Info_Widget' ) ) {

	class WPEX_bbPress_Forum_Info_Widget extends WP_Widget {
		
		/**
		 * Register widget with WordPress.
		 *
		 * @since 3.2.0
		 */
		public function __construct() {
			$branding = wpex_get_theme_branding();
			$branding = $branding ? $branding . ' - ' : '';
			parent::__construct(
				'wpex_bbpress_forum_info',
				'(bbPress) '. $branding . esc_html__( 'Forum Info', 'total' ),
				array(
					'customize_selective_refresh' => true,
				)
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 * @since 3.2.0
		 *
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {

			// Only needed for single forums
			if ( ! bbp_is_single_forum() ) {
				return;
			}

			// Define output variable
			$output = '';

			// Widget options
			$title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';

			// Before widget hook
			echo $args['before_widget'];

				// Display widget title
				if ( $title ) :

					$output .= $args['before_title'];

						$output .= $title;

					$output .= $args['after_title'];

				endif;

				// Wrap classes
				$output .= '<ul class="wpex-bbpress-forum-info wpex-clr">';

					// Topics
					$output .= '<li class="topic-count"><span class="fa fa-folder-open"></span>'. bbp_get_forum_topic_count() .' '. esc_html__( 'topics', 'total' ) .'</li>';

					// Replies
					$output .= '<li class="reply-count"><span class="fa fa-comments"></span>'. bbp_get_forum_post_count() .' '. esc_html__( 'replies', 'total' ) .'</li>';

					// Freshness
					$output .= '<li class="forum-freshness-time"><span class="fa fa-clock-o"></span>'. esc_html__( 'Last activity', 'total' ) .': '. bbp_get_forum_freshness_link() .'</li>';

				// Close widget wrap
				$output .= '</ul>';

			// After widget hook
			$output .= $args['after_widget'];

			// Echo output
			echo $output;

		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 * @since 3.2.0
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance          = $old_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );
			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 * @since 3.2.0
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			$instance = wp_parse_args( ( array ) $instance, array(
				'title' => '',
			) );
			extract( $instance ); ?>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'total' ); ?>:</label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<?php
		}
	}
}
register_widget( 'WPEX_bbPress_Forum_Info_Widget' );
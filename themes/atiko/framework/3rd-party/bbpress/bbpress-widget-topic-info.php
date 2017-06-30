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
if ( ! class_exists( 'WPEX_bbPress_Topic_Info_Widget' ) ) {

	class WPEX_bbPress_Topic_Info_Widget extends WP_Widget {
		
		/**
		 * Register widget with WordPress.
		 *
		 * @since 3.2.0
		 */
		public function __construct() {
			$branding = wpex_get_theme_branding();
			$branding = $branding ? $branding . ' - ' : '';
			parent::__construct(
				'wpex_bbpress_topic_info',
				'(bbPress) '. $branding . esc_html__( 'Topic Info', 'total' ),
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
			if ( ! bbp_is_single_topic() ) {
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

					// Parent
					if ( $forum = wp_get_post_parent_id( get_the_ID() ) ) {

						$parent_name = get_the_title( $forum );

						$output .= '<li class="forum-topic-count"><span class="fa fa-folder-open"></span>'. esc_html__( 'In', 'total' ) .': ';
							
							$output .= '<a href="'. get_permalink( $forum ) .'" title="'. $parent_name .'">'. $parent_name .'</a>';
						
						$output .= '</li>';
					}

					// Replies
					$count = bbp_show_lead_topic() ? bbp_get_topic_reply_count() : bbp_get_topic_post_count();
					$output .= '<li class="forum-reply-count"><span class="fa fa-comments"></span>'. $count .' '. esc_html__( 'replies', 'total' ) .'</li>';

					// Participants
					$output .= '<li class="forum-participants"><span class="fa fa-users"></span>'. esc_html__( 'Participants', 'total' ) .': '. bbp_get_topic_voice_count() .'</li>';

					// Last Reply
					$output .= '<li class="last-user"><span class="fa fa-user"></span>'. esc_html__( 'Last reply from', 'total' ) .': ';

						$last_post_id = bbp_get_topic_last_active_id();
						$last_user_id = bbp_get_topic_author_id( $last_post_id );
						$user         = get_user_by( 'id', $last_user_id );
						$name         = $user->display_name;

						$output .= '<a href="'. bbp_get_user_profile_url( $last_user_id ) .'" title="'. $name .'">'. $name .'</a>';

					$output .= '</li>';

					// Freshness
					$output .= '<li class="forum-freshness-time"><span class="fa fa-clock-o"></span>'. esc_html__( 'Last activity', 'total' ) .': '. bbp_get_topic_freshness_link() .'</li>';

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
register_widget( 'WPEX_bbPress_Topic_Info_Widget' );
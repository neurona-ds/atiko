<?php
/**
 * Useful functions that return arrays
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns array of custom widgets
 *
 * @since 3.6.0
 */
function wpex_custom_widgets_list() {

	// Return empty array if disabled
	if ( ! wpex_get_mod( 'custom_widgets_enable', true ) ) {
		return array();
	}

	// Define widgets
	$widgets = array(
		'about',
		'newsletter',
		'info',
		'social-fontawesome',
		'social',
		'simple-menu',
		'modern-menu',
		'facebook-page',
		'google-map',
		'flickr',
		'video',
		'posts-thumbnails',
		'posts-grid',
		'posts-icons',
		'instagram-grid',
		'users-grid',
		'comments-avatar',
	);

	// Add templatera widget
	if ( function_exists( 'templatera_init' ) ) {
		$widgets['templatera'] = 'templatera';
	}

	// Apply filters and return widgets array
	return apply_filters( 'wpex_custom_widgets', $widgets );

}

/**
 * Returns array of image background styles
 *
 * @since 3.5.0
 */
function wpex_get_bg_img_styles() {
	return array(
		''             => __( 'Default', 'total' ),
		'cover'        => __( 'Cover', 'total' ),
		'stretched'    => __( 'Fixed Cover', 'total' ),
		'repeat'       => __( 'Repeat', 'total' ),
		'fixed-top'    => __( 'Fixed Top', 'total' ),
		'fixed'        => __( 'Fixed Center', 'total' ),
		'fixed-bottom' => __( 'Fixed Bottom', 'total' ),
		'repeat-x'     => __( 'Repeat-x', 'total' ),
		'repeat-y'     => __( 'Repeat-y', 'total' ),
		'repeat-y'     => __( 'Repeat-y', 'total' ),
	);
}

/**
 * Returns array of dropdown styles
 *
 * @since 3.4.0
 */
function wpex_get_menu_dropdown_styles() {
	return apply_filters( 'wpex_get_header_menu_dropdown_styles', array(
		'default'    => __( 'Default', 'total' ),
		'minimal-sq' => __( 'Minimal', 'total' ),
		'minimal'    => __( 'Minimal - Rounded', 'total' ),
		'black'      => __( 'Black', 'total' ),
	) );
}

/**
 * Returns array of form styles
 *
 * @since 3.6.0
 */
function wpex_get_form_styles() {
	return apply_filters( 'wpex_get_form_styles', array(
		''        => __( 'Default', 'total' ),
		'min'     => __( 'Minimal', 'total' ),
		'modern'  => __( 'Modern', 'total' ),
		'white'   => __( 'White', 'total' ),
		'black'   => __( 'Black', 'total' ),
		'white-o' => __( 'White Outline', 'total' ),
		'black-o' => __( 'Black Outline', 'total' ),
	) );
}

/**
 * Array of carousel arrow positions
 *
 * @since 3.5.3
 */
function wpex_carousel_arrow_positions() {
	return apply_filters( 'wpex_carousel_arrow_positions', array(
		'default' => __( 'Default', 'total' ),
		'left'    => __( 'Left', 'total' ) ,
	 	'center'  => __( 'Center', 'total' ),
		'right'   => __( 'Right', 'total' ),
		'abs'     => __( 'Absolute', 'total' ),
	) );
}

/**
 * Array of carousel arrow styles
 *
 * @since 3.5.3
 */
function wpex_carousel_arrow_styles() {
	return apply_filters( 'wpex_carousel_arrow_styles', array(
		''       => __( 'Default', 'total' ),
		'min'    => __( 'Minimal', 'total' ),
		'border' => __( 'Border', 'total' ),
		'circle' => __( 'Circle', 'total' ),
	) );
}

/**
 * Returns array of page layouts
 *
 * @since 3.3.3
 */
function wpex_get_post_layouts() {
	return array(
		''              => __( 'Default', 'total' ),
		'right-sidebar' => __( 'Right Sidebar', 'total' ),
		'left-sidebar'  => __( 'Left Sidebar', 'total' ),
		'full-width'    => __( 'No Sidebar', 'total' ),
		'full-screen'   => __( 'Full Screen', 'total' ),
	);
}

/**
 * Returns array of Header Overlay Styles
 *
 * @since 3.3.0
 */
function wpex_header_overlay_styles() {
	return apply_filters( 'wpex_header_overlay_styles', array(
		'white' => __( 'White Text', 'total' ),
		'light' => __( 'Light Text', 'total' ),
		'dark'  => __( 'Black Text', 'total' ),
		'core'  => __( 'Default Styles', 'total' ),
	) );
}

/**
 * Returns array of available post types
 *
 * @since 3.3.0
 */
function wpex_get_post_types( $instance = '' ) {
	$types = array();
	$get_types = get_post_types( array(
		'public'   => true,
		'_builtin' => false,
	), 'objects', 'and' );
	foreach ( $get_types as $key => $val ) {
		$types[$key] = $val->labels->name;
	}
	return apply_filters( 'wpex_get_post_types', $types, $instance );
}

/**
 * Returns array of Social Options for the Top Bar
 *
 * Added here because it's needed in backend and front-end
 *
 * @since 1.6.0
 */
function wpex_topbar_social_options() {
	return apply_filters ( 'wpex_topbar_social_options', array(
		'twitter' => array(
			'label' => 'Twitter',
			'icon_class' => 'fa fa-twitter',
		),
		'facebook' => array(
			'label' => 'Facebook',
			'icon_class' => 'fa fa-facebook',
		),
		'googleplus' => array(
			'label' => 'Google Plus',
			'icon_class' => 'fa fa-google-plus',
		),
		'pinterest'  => array(
			'label' => 'Pinterest',
			'icon_class' => 'fa fa-pinterest',
		),
		'dribbble' => array(
			'label' => 'Dribbble',
			'icon_class' => 'fa fa-dribbble',
		),
		'vk' => array(
			'label' => 'VK',
			'icon_class' => 'fa fa-vk',
		),
		'instagram'  => array(
			'label' => 'Instagram',
			'icon_class' => 'fa fa-instagram',
		),
		'linkedin' => array(
			'label' => 'LinkedIn',
			'icon_class' => 'fa fa-linkedin',
		),
		'tumblr'  => array(
			'label' => 'Tumblr',
			'icon_class' => 'fa fa-tumblr',
		),
		'github'  => array(
			'label' => 'Github',
			'icon_class' => 'fa fa-github-alt',
		),
		'flickr'  => array(
			'label' => 'Flickr',
			'icon_class' => 'fa fa-flickr',
		),
		'skype' => array(
			'label' => 'Skype',
			'icon_class' => 'fa fa-skype',
		),
		'youtube' => array(
			'label' => 'Youtube',
			'icon_class' => 'fa fa-youtube',
		),
		'vimeo' => array(
			'label' => 'Vimeo',
			'icon_class' => 'fa fa-vimeo-square',
		),
		'vine' => array(
			'label' => 'Vine',
			'icon_class' => 'fa fa-vine',
		),
		'xing' => array(
			'label' => 'Xing',
			'icon_class' => 'fa fa-xing',
		),
		'yelp' => array(
			'label' => 'Yelp',
			'icon_class' => 'fa fa-yelp',
		),
		'tripadvisor' => array(
			'label' => 'Tripadvisor',
			'icon_class' => 'fa fa-tripadvisor',
		),
		'twitch' => array(
			'label' => 'Twitch',
			'icon_class' => 'fa fa-twitch',
		),
		'rss'  => array(
			'label' => __( 'RSS', 'total' ),
			'icon_class' => 'fa fa-rss',
		),
		'email' => array(
			'label' => __( 'Email', 'total' ),
			'icon_class' => 'fa fa-envelope',
		),
		'phone' => array(
			'label' => __( 'Phone', 'total' ),
			'icon_class' => 'fa fa-phone',
		),
	) );
}

/**
 * Returns array of WP dashicons
 *
 * @since 3.3.0
 */
function wpex_get_dashicons_array() {
	return array('admin-appearance' => 'f100', 'admin-collapse' => 'f148', 'admin-comments' => 'f117', 'admin-generic' => 'f111', 'admin-home' => 'f102', 'admin-media' => 'f104', 'admin-network' => 'f112', 'admin-page' => 'f133', 'admin-plugins' => 'f106', 'admin-settings' => 'f108', 'admin-site' => 'f319', 'admin-tools' => 'f107', 'admin-users' => 'f110', 'align-center' => 'f134', 'align-left' => 'f135', 'align-none' => 'f138', 'align-right' => 'f136', 'analytics' => 'f183', 'arrow-down' => 'f140', 'arrow-down-alt' => 'f346', 'arrow-down-alt2' => 'f347', 'arrow-left' => 'f141', 'arrow-left-alt' => 'f340', 'arrow-left-alt2' => 'f341', 'arrow-right' => 'f139', 'arrow-right-alt' => 'f344', 'arrow-right-alt2' => 'f345', 'arrow-up' => 'f142', 'arrow-up-alt' => 'f342', 'arrow-up-alt2' => 'f343', 'art' => 'f309', 'awards' => 'f313', 'backup' => 'f321', 'book' => 'f330', 'book-alt' => 'f331', 'businessman' => 'f338', 'calendar' => 'f145', 'camera' => 'f306', 'cart' => 'f174', 'category' => 'f318', 'chart-area' => 'f239', 'chart-bar' => 'f185', 'chart-line' => 'f238', 'chart-pie' => 'f184', 'clock' => 'f469', 'cloud' => 'f176', 'dashboard' => 'f226', 'desktop' => 'f472', 'dismiss' => 'f153', 'download' => 'f316', 'edit' => 'f464', 'editor-aligncenter' => 'f207', 'editor-alignleft' => 'f206', 'editor-alignright' => 'f208', 'editor-bold' => 'f200', 'editor-customchar' => 'f220', 'editor-distractionfree' => 'f211', 'editor-help' => 'f223', 'editor-indent' => 'f222', 'editor-insertmore' => 'f209', 'editor-italic' => 'f201', 'editor-justify' => 'f214', 'editor-kitchensink' => 'f212', 'editor-ol' => 'f204', 'editor-outdent' => 'f221', 'editor-paste-text' => 'f217', 'editor-paste-word' => 'f216', 'editor-quote' => 'f205', 'editor-removeformatting' => 'f218', 'editor-rtl' => 'f320', 'editor-spellcheck' => 'f210', 'editor-strikethrough' => 'f224', 'editor-textcolor' => 'f215', 'editor-ul' => 'f203', 'editor-underline' => 'f213', 'editor-unlink' => 'f225', 'editor-video' => 'f219', 'email' => 'f465', 'email-alt' => 'f466', 'exerpt-view' => 'f164', 'facebook' => 'f304', 'facebook-alt' => 'f305', 'feedback' => 'f175', 'flag' => 'f227', 'format-aside' => 'f123', 'format-audio' => 'f127', 'format-chat' => 'f125', 'format-gallery' => 'f161', 'format-image' => 'f128', 'format-links' => 'f103', 'format-quote' => 'f122', 'format-standard' => 'f109', 'format-status' => 'f130', 'format-video' => 'f126', 'forms' => 'f314', 'googleplus' => 'f462', 'groups' => 'f307', 'hammer' => 'f308', 'id' => 'f336', 'id-alt' => 'f337', 'image-crop' => 'f165', 'image-flip-horizontal' => 'f169', 'image-flip-vertical' => 'f168', 'image-rotate-left' => 'f166', 'image-rotate-right' => 'f167', 'images-alt' => 'f232', 'images-alt2' => 'f233', 'info' => 'f348', 'leftright' => 'f229', 'lightbulb' => 'f339', 'list-view' => 'f163', 'location' => 'f230', 'location-alt' => 'f231', 'lock' => 'f160', 'marker' => 'f159', 'menu' => 'f333', 'migrate' => 'f310', 'minus' => 'f460', 'networking' => 'f325', 'no' => 'f158', 'no-alt' => 'f335', 'performance' => 'f311', 'plus' => 'f132', 'portfolio' => 'f322', 'post-status' => 'f173', 'pressthis' => 'f157', 'products' => 'f312', 'redo' => 'f172', 'rss' => 'f303', 'screenoptions' => 'f180', 'search' => 'f179', 'share' => 'f237', 'share-alt' => 'f240', 'share-alt2' => 'f242', 'shield' => 'f332', 'shield-alt' => 'f334', 'slides' => 'f181', 'smartphone' => 'f470', 'smiley' => 'f328', 'sort' => 'f156', 'sos' => 'f468', 'star-empty' => 'f154', 'star-filled' => 'f155', 'star-half' => 'f459', 'tablet' => 'f471', 'tag' => 'f323', 'testimonial' => 'f473', 'translation' => 'f326', 'trash' => 'f182', 'twitter' => 'f301', 'undo' => 'f171', 'update' => 'f463', 'upload' => 'f317', 'vault' => 'f178', 'video-alt' => 'f234', 'video-alt2' => 'f235', 'video-alt3' => 'f236', 'visibility' => 'f177', 'welcome-add-page' => 'f133', 'welcome-comments' => 'f117', 'welcome-edit-page' => 'f119', 'welcome-learn-more' => 'f118', 'welcome-view-site' => 'f115', 'welcome-widgets-menus' => 'f116', 'wordpress' => 'f120', 'wordpress-alt' => 'f324', 'yes' => 'f147');
}

/**
 * Array of social profiles for staff members
 *
 * @since 1.5.4
 */
function wpex_staff_social_array() {
	return apply_filters( 'wpex_staff_social_array', array(
		'twitter'        => array(
			'key'        => 'twitter',
			'meta'       => 'wpex_staff_twitter',
			'icon_class' => 'fa fa-twitter',
			'label'      => 'Twitter',
		),
		'facebook'        => array(
			'key'        => 'facebook',
			'meta'       => 'wpex_staff_facebook',
			'icon_class' => 'fa fa-facebook',
			'label'      => 'Facebook',
		),
		'instagram'      => array(
			'key'        => 'instagram',
			'meta'       => 'wpex_staff_instagram',
			'icon_class' => 'fa fa-instagram',
			'label'      => 'Instagram',
		),
		'google-plus'    => array(
			'key'        => 'google-plus',
			'meta'       => 'wpex_staff_google-plus',
			'icon_class' => 'fa fa-google-plus',
			'label'      => 'Google Plus',
		),
		'linkedin'       => array(
			'key'        => 'linkedin',
			'meta'       => 'wpex_staff_linkedin',
			'icon_class' => 'fa fa-linkedin',
			'label'      => 'Linkedin',
		),
		'dribbble'       => array(
			'key'        => 'dribbble',
			'meta'       => 'wpex_staff_dribbble',
			'icon_class' => 'fa fa-dribbble',
			'label'      => 'Dribbble',
		),
		'vk'             => array(
			'key'        => 'vk',
			'meta'       => 'wpex_staff_vk',
			'icon_class' => 'fa fa-vk',
			'label'      => 'VK',
		),
		'skype'          => array(
			'key'        => 'skype',
			'meta'       => 'wpex_staff_skype',
			'icon_class' => 'fa fa-skype',
			'label'      => 'Skype',
		),
		'phone_number'   => array(
			'key'        => 'phone_number',
			'meta'       => 'wpex_staff_phone_number',
			'icon_class' => 'fa fa-phone',
			'label'      => __( 'Phone Number', 'total' ),
		),
		'email'          => array(
			'key'        => 'email',
			'meta'       => 'wpex_staff_email',
			'icon_class' => 'fa fa-envelope',
			'label'      => __( 'Email', 'total' ),
		),
		'website'        => array(
			'key'        => 'website',
			'meta'       => 'wpex_staff_website',
			'icon_class' => 'fa fa-external-link-square',
			'label'      => __( 'Website', 'total' ),
		),
	) );
}

/**
 * Creates an array for adding the staff social options to the metaboxes
 *
 * @since 1.5.4
 */
function wpex_staff_social_meta_array() {
	$profiles = wpex_staff_social_array();
	$array = array();
	foreach ( $profiles as $profile ) {
		$array[] = array(
			'title' => '<span class="'. $profile['icon_class'] .'"></span>' . $profile['label'],
			'id'    => $profile['meta'],
			'type'  => 'text',
			'std'   => '',
		);
	}
	return $array;
}

/**
 * Grid Columns
 *
 * @since 2.0.0
 */
function wpex_grid_columns() {
	return apply_filters( 'wpex_grid_columns', array(
		'1' => '1',
		'2' => '2',
		'3' => '3',
		'4' => '4',
		'5' => '5',
		'6' => '6',
		'7' => '7',
	) );
}

/**
 * Grid Column Gaps
 *
 * @since 2.0.0
 */
function wpex_column_gaps() {
	return apply_filters( 'wpex_column_gaps', array(
		''     => __( 'Default', 'total' ),
		'none' => '0px',
		'1'    => '1px',
		'5'    => '5px',
		'10'   => '10px',
		'15'   => '15px',
		'20'   => '20px',
		'25'   => '25px',
		'30'   => '30px',
		'35'   => '35px',
		'40'   => '40px',
		'50'   => '50px',
		'60'   => '60px',
	) );
}

/**
 * Typography Styles
 *
 * @since 2.0.0
 */
function wpex_typography_styles() {
	return apply_filters( 'wpex_typography_styles', array(
		''             => __( 'Default', 'total' ),
		'light'        => __( 'Light', 'total' ),
		'white'        => __( 'White', 'total' ),
		'white-shadow' => __( 'White with Shadow', 'total' ),
		'black'        => __( 'Black', 'total' ),
		'none'         => __( 'None', 'total' ),
	) );
}

/**
 * Button styles
 *
 * @since 1.6.2
 */
function wpex_button_styles() {
	return apply_filters( 'wpex_button_styles', array(
		''               => __( 'Default', 'total' ),
		'flat'           => __( 'Flat', 'total' ),
		'graphical'      => __( 'Graphical', 'total' ),
		'clean'          => __( 'Clean', 'total' ),
		'three-d'        => __( '3D', 'total' ),
		'outline'        => __( 'Outline', 'total' ),
		'minimal-border' => __( 'Minimal Border', 'total' ),
		'plain-text'     => __( 'Plain Text', 'total' ),
	) );
}

/**
 * Button colors
 *
 * @since 1.6.2
 */
function wpex_button_colors() {
	return apply_filters( 'wpex_button_colors', array(
		''       => __( 'Default', 'total' ),
		'black'  => __( 'Black', 'total' ),
		'blue'   => __( 'Blue', 'total' ),
		'brown'  => __( 'Brown', 'total' ),
		'grey'   => __( 'Grey', 'total' ),
		'green'  => __( 'Green', 'total' ),
		'gold'   => __( 'Gold', 'total' ),
		'orange' => __( 'Orange', 'total' ),
		'pink'   => __( 'Pink', 'total' ),
		'purple' => __( 'Purple', 'total' ),
		'red'    => __( 'Red', 'total' ),
		'rosy'   => __( 'Rosy', 'total' ),
		'teal'   => __( 'Teal', 'total' ),
		'white'  => __( 'White', 'total' ),
	) );
}

/**
 * Array of image crop locations
 *
 * @link 2.0.0
 */
function wpex_image_crop_locations() {
	return array(
		''              => __( 'Default', 'total' ),
		'left-top'      => __( 'Top Left', 'total' ),
		'right-top'     => __( 'Top Right', 'total' ),
		'center-top'    => __( 'Top Center', 'total' ),
		'left-center'   => __( 'Center Left', 'total' ),
		'right-center'  => __( 'Center Right', 'total' ),
		'center-center' => __( 'Center Center', 'total' ),
		'left-bottom'   => __( 'Bottom Left', 'total' ),
		'right-bottom'  => __( 'Bottom Right', 'total' ),
		'center-bottom' => __( 'Bottom Center', 'total' ),
	);
}

/**
 * Image Hovers
 *
 * @since 1.6.2
 */
function wpex_image_hovers() {
	return apply_filters( 'wpex_image_hovers', array(
		''             => __( 'Default', 'total' ),
		'opacity'      => __( 'Opacity', 'total' ),
		'grow'         => __( 'Grow', 'total' ),
		'shrink'       => __( 'Shrink', 'total' ),
		'side-pan'     => __( 'Side Pan', 'total' ),
		'vertical-pan' => __( 'Vertical Pan', 'total' ),
		'tilt'         => __( 'Tilt', 'total' ),
		'blurr'        => __( 'Normal - Blurr', 'total' ),
		'blurr-invert' => __( 'Blurr - Normal', 'total' ),
		'sepia'        => __( 'Sepia', 'total' ),
		'fade-out'     => __( 'Fade Out', 'total' ),
		'fade-in'      => __( 'Fade In', 'total' ),
	) );
}

/**
 * Text decorations
 *
 * @since 1.6.2
 */
function wpex_text_decorations() {
	return apply_filters( 'wpex_text_decorations', array(
		''             => __( 'Default', 'total' ),
		'underline'    => __( 'Underline', 'total' ),
		'overline'     => __( 'Overline','total' ),
		'line-through' => __( 'Line-through', 'total' ),
	) );
}

/**
 * Font Weights
 *
 * @since 1.6.2
 */
function wpex_font_weights() {
	return apply_filters( 'wpex_font_weights', array(
		''         => __( 'Default', 'total' ),
		'normal'   => __( 'Normal', 'total' ),
		'semibold' => __( 'Semibold','total' ),
		'bold'     => __( 'Bold', 'total' ),
		'bolder'   => __( 'Bolder', 'total' ),
		'100'      => '100',
		'200'      => '200',
		'300'      => '300',
		'400'      => '400',
		'500'      => '500',
		'600'      => '600',
		'700'      => '700',
		'800'      => '800',
		'900'      => '900',
	) );
}

/**
 * Font Style
 *
 * @since 1.6.2
 */
function wpex_font_styles() {
	return apply_filters( 'wpex_font_styles', array(
		''        => __( 'Default', 'total' ),
		'normal'  => __( 'Normal', 'total' ),
		'italic'  => __( 'Italic', 'total' ),
		'oblique' => __( 'Oblique', 'total' ),
	) );
}

/**
 * Text Transform
 *
 * @since 1.6.2
 */
function wpex_text_transforms() {
	return array(
		''           => __( 'Default', 'total' ),
		'none'       => __( 'None', 'total' ) ,
		'capitalize' => __( 'Capitalize', 'total' ),
		'uppercase'  => __( 'Uppercase', 'total' ),
		'lowercase'  => __( 'Lowercase', 'total' ),
	);
}

/**
 * Border Styles
 *
 * @since 1.6.0
 */
function wpex_border_styles() {
	return array(
		''       => __( 'Default', 'total' ),
		'solid'  => __( 'Solid', 'total' ),
		'dotted' => __( 'Dotted', 'total' ),
		'dashed' => __( 'Dashed', 'total' ),
	);
}

/**
 * Alignments
 *
 * @since 1.6.0
 */
function wpex_alignments() {
	return array(
		''       => __( 'Default', 'total' ),
		'left'   => __( 'Left', 'total' ),
		'right'  => __( 'Right', 'total' ),
		'center' => __( 'Center', 'total' ),
	);
}

/**
 * Visibility
 *
 * @since 1.6.0
 */
function wpex_visibility() {
	return apply_filters( 'wpex_visibility', array(
		''                         => __( 'Always Visible', 'total' ),
		'hidden-phone'             => __( 'Hidden on Phones', 'total' ),
		'hidden-tablet'            => __( 'Hidden on Tablets', 'total' ),
		'hidden-tablet-landscape'  => __( 'Hidden on Tablets: Landscape', 'total' ),
		'hidden-tablet-portrait'   => __( 'Hidden on Tablets: Portrait', 'total' ),
		'hidden-desktop'           => __( 'Hidden on Desktop', 'total' ),
		'visible-desktop'          => __( 'Visible on Desktop Only', 'total' ),
		'visible-phone'            => __( 'Visible on Phones Only', 'total' ),
		'visible-tablet'           => __( 'Visible on Tablets Only', 'total' ),
		'visible-tablet-landscape' => __( 'Visible on Tablets: Landscape Only', 'total' ),
		'visible-tablet-portrait'  => __( 'Visible on Tablets: Portrait Only', 'total' ),
	) );
}

/**
 * CSS Animations
 *
 * @since 1.6.0
 */
function wpex_css_animations() {
	return apply_filters( 'wpex_css_animations', array(
		''              => __( 'None', 'total') ,
		'top-to-bottom' => __( 'Top to bottom', 'total' ),
		'bottom-to-top' => __( 'Bottom to top', 'total' ),
		'left-to-right' => __( 'Left to right', 'total' ),
		'right-to-left' => __( 'Right to left', 'total' ),
		'appear'        => __( 'Appear from center', 'total' ),
	) );
}

/**
 * Array of Hover CSS animations
 *
 * @since 2.0.0
 */
function wpex_hover_css_animations() {
	return apply_filters( 'wpex_hover_css_animations', array(
		''                       => __( 'Default', 'total' ),
		'shadow'                 => __( 'Shadow', 'total' ),
		'grow-shadow'            => __( 'Grow Shadow', 'total' ),
		'float-shadow'           => __( 'Float Shadow', 'total' ),
		'grow'                   => __( 'Grow', 'total' ),
		'shrink'                 => __( 'Shrink', 'total' ),
		'pulse'                  => __( 'Pulse', 'total' ),
		'pulse-grow'             => __( 'Pulse Grow', 'total' ),
		'pulse-shrink'           => __( 'Pulse Shrink', 'total' ),
		'push'                   => __( 'Push', 'total' ),
		'pop'                    => __( 'Pop', 'total' ),
		'bounce-in'              => __( 'Bounce In', 'total' ),
		'bounce-out'             => __( 'Bounce Out', 'total' ),
		'rotate'                 => __( 'Rotate', 'total' ),
		'grow-rotate'            => __( 'Grow Rotate', 'total' ),
		'float'                  => __( 'Float', 'total' ),
		'sink'                   => __( 'Sink', 'total' ),
		'bob'                    => __( 'Bob', 'total' ),
		'hang'                   => __( 'Hang', 'total' ),
		'skew'                   => __( 'Skew', 'total' ),
		'skew-backward'          => __( 'Skew Backward', 'total' ),
		'wobble-horizontal'      => __( 'Wobble Horizontal', 'total' ),
		'wobble-vertical'        => __( 'Wobble Vertical', 'total' ),
		'wobble-to-bottom-right' => __( 'Wobble To Bottom Right', 'total' ),
		'wobble-to-top-right'    => __( 'Wobble To Top Right', 'total' ),
		'wobble-top'             => __( 'Wobble Top', 'total' ),
		'wobble-bottom'          => __( 'Wobble Bottom', 'total' ),
		'wobble-skew'            => __( 'Wobble Skew', 'total' ),
		'buzz'                   => __( 'Buzz', 'total' ),
		'buzz-out'               => __( 'Buzz Out', 'total' ),
		'glow'                   => __( 'Glow', 'total' ),
		'shadow-radial'          => __( 'Shadow Radial', 'total' ),
		'box-shadow-outset'      => __( 'Box Shadow Outset', 'total' ),
		'box-shadow-inset'       => __( 'Box Shadow Inset', 'total' ),
	) );
}

/**
 * Image filter styles
 *
 * @since 1.4.0
 */
function wpex_image_filters() {
	return apply_filters( 'wpex_image_filters', array(
		''          => __( 'None', 'total' ),
		'grayscale' => __( 'Grayscale', 'total' ),
	) );
}

/**
 * Social Link styles
 *
 * @since 3.0.0
 */
function wpex_social_button_styles() {
	return apply_filters( 'wpex_social_button_styles', array(
		'default'            => __( 'Skin Default', 'total' ),
		'none'               => __( 'None', 'total' ),
		'minimal'            => __( 'Minimal', 'total' ),
		'minimal-rounded'    => __( 'Minimal Rounded', 'total' ),
		'minimal-round'      => __( 'Minimal Round', 'total' ),
		'flat'               => __( 'Flat', 'total' ),
		'flat-rounded'       => __( 'Flat Rounded', 'total' ),
		'flat-round'         => __( 'Flat Round', 'total' ),
		'flat-color'         => __( 'Flat Color', 'total' ),
		'flat-color-rounded' => __( 'Flat Color Rounded', 'total' ),
		'flat-color-round'   => __( 'Flat Color Round', 'total' ),
		'3d'                 => __( '3D', 'total' ),
		'3d-color'           => __( '3D Color', 'total' ),
		'black'              => __( 'Black', 'total' ),
		'black-rounded'      => __( 'Black Rounded', 'total' ),
		'black-round'        => __( 'Black Round', 'total' ),
		'black-ch'           => __( 'Black with Color Hover', 'total' ),
		'black-ch-rounded'   => __( 'Black with Color Hover Rounded', 'total' ),
		'black-ch-round'     => __( 'Black with Color Hover Round', 'total' ),
		'graphical'          => __( 'Graphical', 'total' ),
		'graphical-rounded'  => __( 'Graphical Rounded', 'total' ),
		'graphical-round'    => __( 'Graphical Round', 'total' ),
		'bordered'           => __( 'Bordered', 'total' ),
		'bordered-rounded'   => __( 'Bordered Rounded', 'total' ),
		'bordered-round'     => __( 'Bordered Round', 'total' ),
	) );
}

/**
 * Array of background patterns
 *
 * @since 4.0
 */
function wpex_get_background_patterns() {
	$url = wpex_asset_url( 'images/patterns/' );
	return apply_filters( 'wpex_get_background_patterns', array(
		'dark_wood' => array(
			'label' => __( 'Dark Wood', 'total' ),
			'url'   => $url . 'dark_wood.png',
		),
		'diagmonds' => array(
			'label' => __( 'Diamonds', 'total' ),
			'url'   => $url . 'diagmonds.png',
		),
		'grilled' => array(
			'label' => __( 'Grilled', 'total' ),
			'url'   => $url . 'grilled.png',
		),
		'lined_paper' => array(
			'label' => __( 'Lined Paper', 'total' ),
			'url'   => $url . 'lined_paper.png',
		),
		'old_wall' => array(
			'label' => __( 'Old Wall', 'total' ),
			'url'   => $url . 'old_wall.png',
		),
		'ricepaper' => array(
			'label' => __( 'Rice Paper', 'total' ),
			'url'   => $url . 'ricepaper.png',
		),
		'tree_bark' => array(
			'label' => __( 'Tree Bark', 'total' ),
			'url'   => $url . 'tree_bark.png',
		),
		'triangular' => array(
			'label' => __( 'Triangular', 'total' ),
			'url'   => $url . 'triangular.png',
		),
		'white_plaster' => array(
			'label' => __( 'White Plaster', 'total' ),
			'url'   => $url . 'white_plaster.png',
		),
		'wild_flowers' => array(
			'label' => __( 'Wild Flowers', 'total' ),
			'url'   => $url . 'wild_flowers.png',
		),
		'wood_pattern' => array(
			'label' => __( 'Wood Pattern', 'total' ),
			'url'   => $url . 'wood_pattern.png',
		),
	) );
}
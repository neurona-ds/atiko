<?php
/**
 * Main Theme Panel
 *
 * @package Total WordPress theme
 * @subpackage Framework
 * @version 4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'WPEX_Theme_Panel' ) ) {

	class WPEX_Theme_Panel {

		/**
		 * Start things up
		 *
		 * @since 1.6.0
		 */
		public function __construct() {

			// Add panel menu
			add_action( 'admin_menu', array( 'WPEX_Theme_Panel', 'add_menu_page' ), 0 );

			// Add panel submenu
			add_action( 'admin_menu', array( 'WPEX_Theme_Panel', 'add_menu_subpage' ) );

			// Add custom CSS for the theme panel
			add_action( 'admin_enqueue_scripts', array( 'WPEX_Theme_Panel', 'scripts' ) );

			// Register panel settings
			add_action( 'admin_init', array( 'WPEX_Theme_Panel', 'register_settings' ) );

			// Load addon files
			self::load_addons();

		}

		/**
		 * Return theme addons
		 * Can't be added in construct because translations won't work
		 *
		 * @since 3.3.3
		 */
		private static function get_addons() {

			// Categories
			$s_core  = __( 'Core', 'total' );
			$s_seo   = __( 'SEO', 'total' );
			$s_op    = __( 'Optimizations', 'total' );
			$s_dev   = __( 'Developers', 'total' );
			$s_types = __( 'Post Types', 'total' );
			$s_vc    = __( 'Visual Composer', 'total' );

			// Array of addons
			$addons = array(
				'demo_importer' => array(
					'label'    => __( 'Demo Importer', 'total' ),
					'icon'     => 'dashicons dashicons-download',
					'category' => $s_core,
				),
				'under_construction' => array(
					'label'    => __( 'Under Construction', 'total' ),
					'icon'     => 'dashicons dashicons-hammer',
					'category' => $s_core,
				),
				'recommend_plugins' => array(
					'label'    => __( 'Recommend Plugins', 'total' ),
					'icon'     => 'dashicons dashicons-admin-plugins',
					'category' => $s_core,
				),
				'schema_markup' => array(
					'label'     => __( 'Schema Markup', 'total' ),
					'icon'      => 'dashicons dashicons-feedback',
					'category'  => $s_seo,
				),
				'aria_landmarks' => array(
					'label'     => __( 'Aria Landmarks', 'total' ),
					'icon'      => 'dashicons dashicons-feedback',
					'category'  => __( 'Accessibility', 'total' ),
					'disabled'  => true,
				),
				'minify_js' => array(
					'label'    => __( 'Minify Javascript', 'total' ),
					'icon'     => 'dashicons dashicons-performance',
					'category' => $s_op,
				),
				'custom_css' => array(
					'label'    => __( 'Custom CSS', 'total' ),
					'icon'     => 'dashicons dashicons-admin-appearance',
					'category' => $s_dev,
				),
				'custom_actions' => array(
					'label'    => __( 'Custom Actions', 'total' ),
					'icon'     => 'dashicons dashicons-editor-code',
					'category' => $s_dev,
				),
				'favicons' => array(
					'label'    => __( 'Favicons', 'total' ),
					'icon'     => 'dashicons dashicons-nametag',
					'category' => $s_core,
				),
				'portfolio' => array(
					'label'    => wpex_get_portfolio_name(),
					'icon'     => 'dashicons dashicons-'. wpex_get_portfolio_menu_icon(),
					'category' => $s_types,
				),
				'staff' => array(
					'label'    => wpex_get_staff_name(),
					'icon'     => 'dashicons dashicons-'. wpex_get_staff_menu_icon(),
					'category' => $s_types,
				),
				'testimonials' => array(
					'label'    => wpex_get_testimonials_name(),
					'icon'     => 'dashicons dashicons-'. wpex_get_testimonials_menu_icon(),
					'category' => $s_types,
				),
				'post_series' => array(
					'label'    => __( 'Post Series', 'total' ),
					'icon'     => 'dashicons dashicons-edit',
					'category' => $s_core,
				),
				'header_builder' => array(
					'label'    => __( 'Header Builder', 'total' ),
					'icon'     => 'dashicons dashicons-editor-insertmore',
					'category' => $s_core,
				),
				'footer_builder' => array(
					'label'    => __( 'Footer Builder', 'total' ),
					'icon'     => 'dashicons dashicons-editor-insertmore',
					'category' => $s_core,
				),
				'custom_admin_login'  => array(
					'label'    => __( 'Custom Login Page', 'total' ),
					'icon'     => 'dashicons dashicons-lock',
					'category' => $s_core,
				),
				'custom_404' => array(
					'label'    => __( 'Custom 404 Page', 'total' ),
					'icon'     => 'dashicons dashicons-dismiss',
					'category' => $s_core,
				),
				'customizer_panel' => array(
					'label'    => __( 'Customizer Manager', 'total' ),
					'icon'     => 'dashicons dashicons-admin-settings',
					'category' => $s_op,
				),
				'custom_wp_gallery' => array(
					'label'    => __( 'Custom WordPress Gallery', 'total' ),
					'icon'     => 'dashicons dashicons-images-alt2',
					'category' => $s_core,
				),
				'widget_areas' => array(
					'label'    => __( 'Widget Areas', 'total' ),
					'icon'     => 'dashicons dashicons-welcome-widgets-menus',
					'category' => $s_core,
				),
				'custom_widgets' => array(
					'label'    => __( 'Custom Widgets', 'total' ),
					'icon'     => 'dashicons dashicons-list-view',
					'category' => $s_core,
				),
				'term_thumbnails' => array(
					'label'    => __( 'Category Thumbnails', 'total' ),
					'icon'     => 'dashicons dashicons-format-image',
					'category' => $s_core,
				),
				'editor_formats' => array(
					'label'    => __( 'Editor Formats', 'total' ),
					'icon'     => 'dashicons dashicons-editor-paste-word',
					'category' => $s_core,
				),
				'editor_shortcodes' => array(
					'label'    => __( 'Editor Shortcodes', 'total' ),
					'icon'     => 'dashicons dashicons-editor-paste-word',
					'category' => $s_core,
				),
				'remove_emoji_scripts' => array(
					'label'    => __( 'Remove Emoji Scripts', 'total' ),
					'icon'     => 'dashicons dashicons-smiley',
					'category' => $s_op,
				),
				'image_sizes' => array(
					'label'    => __( 'Image Sizes', 'total' ),
					'icon'     => 'dashicons dashicons-image-crop',
					'category' => $s_core,
				),
				'page_animations' => array(
					'label'    => __( 'Page Animations', 'total' ),
					'icon'     => 'dashicons dashicons-welcome-view-site',
					'category' => $s_core,
				),
				'typography' => array(
					'label'    => __( 'Typography Options', 'total' ),
					'icon'     => 'dashicons dashicons-editor-bold',
					'category' => $s_core,
				),
				'edit_post_link' => array(
					'label'    => __( 'Post Edit Links', 'total' ),
					'icon'     => 'dashicons dashicons-admin-tools',
					'category' => $s_core,
				),
				'header_image' => array(
					'label'    => __( 'Header Image', 'total' ),
					'disabled' => true,
					'icon'     => 'dashicons dashicons-format-image',
					'category' => $s_core,
				),
				'import_export' => array(
					'label'    => __( 'Import/Export Panel', 'total' ),
					'icon'     => 'dashicons dashicons-admin-settings',
					'category' => $s_core,
				),
				'visual_composer_theme_mode' => array(
					'label'     =>  __( 'Visual Composer Theme Mode', 'total' ),
					'icon'      => 'dashicons dashicons-admin-customizer',
					'custom_id' => true,
					'condition' => WPEX_VC_ACTIVE,
					'category'  => $s_vc,
				),
				'extend_visual_composer' => array(
					'label'     => WPEX_THEME_BRANDING . ' ' . __( 'Visual Composer Modules', 'total' ),
					'icon'      => 'dashicons dashicons-admin-customizer',
					'custom_id' => true,
					'condition' => WPEX_VC_ACTIVE,
					'category'  => $s_vc,
				),
				'deprecated_functions' => array(
					'label'     => __( 'Deprecated Fallbacks', 'total' ),
					'custom_id' => true,
					'icon'      => 'dashicons dashicons-undo',
					'category'  => $s_op,
					'desc'      => __( 'From time to time functions are removed in updates but the code stays in the theme as a fallback for child theme modifications. Disable this option to remove these fallbacks from your site.', 'total' ),
				),
				'disable_gs' => array(
					'disabled'  => true,
					'label'     => __( 'Remove Google Fonts', 'total' ),
					'custom_id' => true,
					'icon'      => 'dashicons dashicons-editor-strikethrough',
					'category'  => $s_op,
				),
				'remove_posttype_slugs' => array(
					'disabled'  => true,
					'label'     => __( 'Remove Post Type Slugs', 'total' ),
					'desc'      => __( 'Removes the slug from built-in custom post types. Slugs are important to prevent conflicts so use with caution (not recommented in most cases).', 'total' ),
					'custom_id' => true,
					'icon'      => 'dashicons dashicons-art',
					'category'  => $s_types,
				),
			);

			// Add custom js only if setting not empty
			if ( wpex_get_mod ( 'custom_js', null ) ) {
				$addons['custom_js'] = array(
					'label'    => __( 'Custom JS', 'total' ),
					'icon'     => 'dashicons dashicons-media-code',
					'category' => $s_dev,
					'disabled' => true,
				);
			}

			// Apply filters and return
			return apply_filters( 'wpex_theme_addons', $addons );

		}

		/**
		 * Registers a new menu page
		 *
		 * @since 1.6.0
		 */
		public static function add_menu_page() {
		  add_menu_page(
				__( 'Theme Panel', 'total' ),
				'Theme Panel', // menu title - can't be translated because it' used for the $hook prefix
				'manage_options',
				WPEX_THEME_PANEL_SLUG,
				'',
				'dashicons-admin-generic',
				null
			);
		}

		/**
		 * Registers a new submenu page
		 *
		 * @since 1.6.0
		 */
		public static function add_menu_subpage(){
			add_submenu_page(
				'wpex-general',
				__( 'General', 'total' ),
				__( 'General', 'total' ),
				'manage_options',
				WPEX_THEME_PANEL_SLUG,
				array( 'WPEX_Theme_Panel', 'create_admin_page' )
			);
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * @since 1.6.0
		 */
		public static function register_settings() {
			register_setting( 'wpex_tweaks', 'wpex_tweaks', array( 'WPEX_Theme_Panel', 'admin_sanitize' ) );
		}

		/**
		 * Main Sanitization callback
		 *
		 * @since 1.6.0
		 */
		public static function admin_sanitize( $options ) {

			// Check options first
			if ( ! is_array( $options ) || empty( $options ) || ( false === $options ) ) {
				return array();
			}

			// Get addons array
			$theme_addons = self::get_addons();

			// Save checkboxes
			$checkboxes = array();

			// Add theme parts to checkboxes
			foreach ( $theme_addons as $key => $val ) {

				// Get correct ID
				$id = isset( $val['custom_id'] ) ? $key : $key .'_enable';

				// No need to save items that are enabled by default unless they have been disabled
				$default = isset ( $val['disabled'] ) ? false : true;

				// If default is true
				if ( $default ) {
					if ( ! isset( $options[$id] ) ) {
						set_theme_mod( $id, 0 ); // Disable option that is enabled by default
					} else {
						remove_theme_mod( $id ); // Make sure its not in the theme_mods since it's already enabled
					}
				}

				// If default is false
				elseif ( ! $default ) {
					if ( isset( $options[$id] ) ) {
						set_theme_mod( $id, 1 ); // Enable option that is disabled by default
					} else {
						remove_theme_mod( $id ); // Remove theme mod because it's disabled by default
					}
				}


			}

			// Remove thememods for checkboxes not in array
			foreach ( $checkboxes as $checkbox ) {
				if ( isset( $options[$checkbox] ) ) {
					set_theme_mod( $checkbox, 1 );
				} else {
					set_theme_mod( $checkbox, 0 );
				}
			}

			// Save Branding
			$value = $options['theme_branding'];
			if ( empty( $value ) ) {
				set_theme_mod( 'theme_branding', 'disabled' );
			} else {
				set_theme_mod( 'theme_branding', strip_tags( $value ) );
			}

			// No need to save in options table
			$options = '';
			return $options;

		}

		/**
		 * Settings page output
		 *
		 * @since 1.6.0
		 */
		public static function create_admin_page() {

			// Delete option that isn't needed
			delete_option( 'wpex_tweaks' );

			// Get theme addons array
			$theme_addons = self::get_addons(); ?>

			<div class="wpex-theme-panel wpex-clr">

				<div class="wrap about-wrap">

					<?php if ( apply_filters( 'wpex_theme_panel_badge', true ) ) { ?>

						<div class="wpex-badge">
							<table>
								<tr>
									<th>
										<span class="<?php echo esc_attr( apply_filters( 'wpex_theme_panel_badge_icon', 'fa fa-wpexplorer' ) ); ?>"></span>
										<div class="wpex-spacer"></div>
										<?php echo esc_html__( 'Version', 'total' ) .' <span class="wpex-version">'. WPEX_THEME_VERSION . '</span>'; ?>
									</th>
								</tr>
							</table>
						</div>

					<?php } ?>

					<h1><?php esc_attr_e( 'Theme Options Panel', 'total' ); ?></h1>

					<p class="about-text"><?php esc_attr_e( 'In this theme panel you will be able to enable or disable the various features of the theme so only the functions you need will run keeping your site well optimized.', 'total' ); ?></p>

					<h2 class="nav-tab-wrapper">
						<a href="#" class="nav-tab nav-tab-active"><span class="fa fa-cogs"></span><?php esc_attr_e( 'Features', 'total' ); ?></a>
						<?php if ( wpex_get_mod( 'demo_importer_enable', true ) ) { ?>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=wpex-panel-demo-importer' ) ); ?>" class="nav-tab"><span class="fa fa-download"></span><?php esc_attr_e( 'Demo Import', 'total' ); ?></a>
						<?php } ?>
						<?php
						// Customizer url
						$customize_url = add_query_arg(
							array(
								'return' => urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ),
							),
							'customize.php'
						); ?>
						<a href="<?php echo esc_url( $customize_url ); ?>" class="nav-tab"><span class="fa fa-paint-brush"></span><?php esc_attr_e( 'Customize', 'total' ); ?></a>
					</h2>

					<div id="wpex-theme-panel-content" class="wpex-clr">

						<div class="wpex-theme-panel-updated">
							<p>
								<?php echo wpex_sanitize_data( __( 'Don\'t forget to <a href="#">save your changes</a>', 'total' ), 'html' ); ?>
							</p>
						</div>

						<form id="wpex-theme-panel-form" method="post" action="options.php">

							<?php settings_fields( 'wpex_tweaks' ); ?>

							<div class="manage-right">

								<!-- Branding -->
								<h4><?php esc_attr_e( 'Theme Branding', 'total' ); ?></h4>
								<?php
								// Get theme branding value
								$value = wpex_get_mod( 'theme_branding', 'Total' );
								$value = ( 'disabled' == $value || ! $value ) ? '' : $value; ?>
								<input type="text" name="wpex_tweaks[theme_branding]" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php esc_attr_e( 'Used in widgets and builder blocks...', 'total' ); ?>">

								<!-- View -->
								<h4><?php esc_attr_e( 'View', 'total' ); ?></h4>
								<div class="button-group wpex-filter-active">
									<button type="button" class="button active"><?php esc_attr_e( 'All', 'total' ); ?></button>
									<button type="button" class="button" data-filter-by="active"><?php esc_attr_e( 'Active', 'total' ); ?></button>
									<button type="button" class="button" data-filter-by="inactive"><?php esc_attr_e( 'Inactive', 'total' ); ?></button>
								</div>

								<!-- Sort -->
								<h4><?php esc_attr_e( 'Sort', 'total' ); ?></h4>
								<?php
								// Categories
								$categories = wp_list_pluck( $theme_addons, 'category' );
								$categories = array_unique( $categories );
								asort( $categories ); ?>
								<ul class="wpex-theme-panel-sort">
									<li><a href="#" data-category="all" class="wpex-active-category"><?php esc_attr_e( 'All', 'total' ); ?></a></li>
									<?php
									// Loop through cats
									foreach ( $categories as $key => $category ) :

										// Check condition
										$display = true;
										if ( isset( $theme_addons[$key]['condition'] ) ) {
											$display = $theme_addons[$key]['condition'];
										}

										// Show cat
										if ( $display ) {
											$sanitize_category = strtolower( str_replace( ' ', '_', $category ) ); ?>
											<li><a href="#" data-category="<?php echo esc_attr( $sanitize_category ); ?>" title="<?php echo esc_attr( $category ); ?>"><?php echo strip_tags( $category ); ?></a></li>
										<?php } ?>

									<?php endforeach; ?>
								</ul>

							</div><!-- manage-right -->

							<div class="manage-left">

								<table class="table table-bordered wp-list-table widefat fixed wpex-modules">

									<tbody id="the-list">

										<?php
										$count = 0;
										// Loop through theme addons and add checkboxes
										foreach ( $theme_addons as $key => $val ) :
											$count++;

											// Display setting?
											$display = true;
											if ( isset( $val['condition'] ) ) {
												$display = $val['condition'];
											}

											// Sanitize vars
											$default = isset ( $val['disabled'] ) ? false : true;
											$label   = isset ( $val['label'] ) ? $val['label'] : '';
											$icon    = isset ( $val['icon'] ) ? $val['icon'] : '';

											// Label
											if ( $icon ) {
												$label = '<i class="'. $icon .'"></i>'. $label;
											}

											// Set id
											if ( isset( $val['custom_id'] ) ) {
												$key = $key;
											} else {
												$key = $key .'_enable';
											}

											// Get theme option
											$theme_mod = wpex_get_mod( $key, $default );

											// Get category and sanitize
											$category = isset( $val['category'] ) ? $val['category'] : ' other';
											$category = strtolower( str_replace( ' ', '_', $category ) );

											// Sanitize category
											$category = strtolower( str_replace( ' ', '_', $category ) );

											// Classes
											$classes = 'wpex-module';
											$classes .= $theme_mod ? ' wpex-active' : ' wpex-disabled';
											$classes .= ! $display ? ' wpex-hidden' : '';
											$classes .= ' wpex-category-'. $category;
											if ( $count = 2 ) {
												$classes .= ' alternative';
												$count = 0;
											} ?>

											<tr id="<?php echo esc_attr( $key ); ?>" class="<?php echo esc_attr( $classes ); ?>">

												<th scope="row" class="check-column">
													<input type="checkbox" name="wpex_tweaks[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $theme_mod ); ?>" <?php checked( $theme_mod, true ); ?> class="wpex-checkbox">
												</th>

												<td class="name column-name">
													<span class="info"><a href="#<?php echo esc_attr( $key ); ?>" class="wpex-theme-panel-module-link"><?php echo wp_kses_post( $label, 'html' ); ?></a></span>
													<?php if ( isset( $val['desc'] ) ) { ?>
														<div class="wpex-module-description">
															<small><?php echo wp_kses_post( $val['desc'], 'html' ); ?></small>
														</div>
													<?php } ?>
												</td>

											</tr>

										<?php endforeach; ?>

									</tbody>

								</table>

								<?php submit_button(); ?>

							</div><!-- .manage-left -->

						</form>

						</div><!-- #wpex-theme-panel-content -->

				</div><!-- .wrap -->

			</div><!-- .wpex-theme-panel -->

		<?php
		}

		/**
		 * Load Theme Panel Scripts
		 *
		 * @since 3.6.0
		 */
		public static function scripts( $hook ) {

			if ( $hook != 'toplevel_page_wpex-panel' ) {
				return;
			}

			wp_enqueue_style(
				'font-awesome',
				wpex_asset_url( 'lib/font-awesome/css/font-awesome.min.css' ),
				array(),
				'4.6.3'
			);

			wp_enqueue_style(
				'wpex-theme-panel',
				wpex_asset_url( 'css/wpex-theme-panel.css' ),
				array(),
				WPEX_THEME_VERSION
			);

			wp_enqueue_script(
				'wpex-theme-panel',
				wpex_asset_url( 'js/dynamic/wpex-theme-panel.js' ),
				array( 'jquery' ),
				WPEX_THEME_VERSION,
				true
			);
		}

		/**
		 * Include addons
		 *
		 * @since 1.6.0
		 */
		private static function load_addons() {

			// Addons directory location
			$dir = WPEX_FRAMEWORK_DIR . 'addons/';

			// Demo importer
			if ( wpex_get_mod( 'demo_importer_enable', true ) ) {
				require_once $dir . '/demo-importer/demo-importer.php';
			}

			// Typography
			if ( wpex_get_mod( 'typography_enable', true ) ) {
				require_once $dir . 'typography.php';
			}

			// Under Construction
			if ( wpex_get_mod( 'under_construction_enable', true ) ) {
				require_once $dir . 'under-construction.php';
			}

			// Custom Favicons
			if ( wpex_get_mod( 'favicons_enable', true ) ) {
				require_once $dir . 'favicons.php';
			}

			// Custom 404
			if ( wpex_get_mod( 'custom_404_enable', true ) ) {
				require_once $dir . 'custom-404.php';
			}

			// Custom widget areas
			if ( wpex_get_mod( 'widget_areas_enable', true ) ) {
				require_once $dir . 'widget-areas.php';
			}

			// Custom Login
			if ( wpex_get_mod( 'custom_admin_login_enable', true ) ) {
				require_once $dir . 'custom-login.php';
			}

			// Header builder
			if ( wpex_get_mod( 'header_builder_enable', true ) ) {
				require_once $dir . 'header-builder.php';
			}

			// Footer builder
			if ( wpex_get_mod( 'footer_builder_enable', true ) ) {
				require_once $dir . 'footer-builder.php';
			}

			// Custom WordPress gallery output
			if ( apply_filters( 'wpex_custom_wp_gallery', wpex_get_mod( 'custom_wp_gallery_enable', true ) ) ) {
				require_once $dir . 'wp-gallery.php';
			}

			// Custom CSS
			if ( wpex_get_mod( 'custom_css_enable', true ) ) {
				require_once $dir . 'custom-css-panel.php';
			}

			// Custom JS / Only if not empty this - this is deprecated
				//Removed completely in Total 3.6.0
				//require_once $dir . 'custom-js.php';
			//

			// User Actions
			if ( wpex_get_mod( 'custom_actions_enable', true ) ) {
				require_once $dir . 'custom-actions.php';
			}

			// Page animations
			if ( wpex_get_mod( 'page_animations_enable', true ) ) {
				require_once $dir . 'page-animations.php';
			}

			/*** ADMIN ONLY ADDONS ***/
			if ( is_admin() ) {

				// Import Export Functions
				if ( wpex_get_mod( 'import_export_enable', true ) ) {
					require_once $dir . 'import-export.php';
				}

				// Editor formats
				if ( wpex_get_mod( 'editor_formats_enable', true ) ) {
					require_once $dir . 'editor-formats.php';
				}

			} // End is_admin()

		}

	}

}
new WPEX_Theme_Panel();
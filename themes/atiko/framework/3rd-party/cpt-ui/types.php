<?php
/**
 * Custom Post Types UI Post Types Config
 *
 * @package Total WordPress Theme
 * @subpackage Custom Post Types UI - Post Type Settings
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start class
if ( ! class_exists( 'WPEX_CPT_UI_Types' ) ) {

	class WPEX_CPT_UI_Types {
		private $types             = array();
		private $wpex_cpt_settings = array();

		/**
		 * Start things up
		 *
		 * @since 3.6.0
		 */
		public function __construct() {
			$cptui_post_types = get_option( 'cptui_post_types' );

			// Add new settings for the Custom Post Types UI plugin
			if ( is_admin() ) {

				// Add new settings
				add_action( 'cptui_post_type_after_fieldsets', array( $this, 'new_settings' ), 10, 1 );

				// Save settings
				add_action( 'cptui_after_update_post_type', array( $this, 'save_settings' ), 10, 1 );

			}

			// There are custom types so lets do things
			if ( ! empty( $cptui_post_types ) && is_array( $cptui_post_types ) ) {

				// Save types in Class var
				$this->types = $cptui_post_types;

				// Get Data
				$wpex_cpt_settings = get_option( 'wpex_cpt_settings' );

				// Make sure settings are not empty and are in fact an array
				if ( ! empty( $wpex_cpt_settings ) && is_array( $wpex_cpt_settings ) ) {

					// Set data to class var
					$this->wpex_cpt_settings = $wpex_cpt_settings;

					// Admin functions
					if ( is_admin() ) {

						// Enable metabox
						add_filter( 'wpex_main_metaboxes_post_types', array( $this, 'metabox_types' ) );

						// Enable metabox media tab
						add_filter( 'wpex_metabox_array', array( $this, 'enable_metabox_media' ) );

						// Add gallery metabox
						add_filter( 'wpex_gallery_metabox_post_types', array( $this, 'gallery_metabox' ) );

						// Add image sizes tabs
						add_filter( 'wpex_image_sizes_tabs', array( $this, 'image_sizes_tabs' ), 10 );
						

					}

					// Front-end functions
					else {

						// Posts per page
						add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

						// Alter layout
						add_filter( 'wpex_post_layout_class', array( $this, 'layouts' ) );

						// Alter page header title
						add_filter( 'wpex_page_header_title_args', array( $this, 'page_header_title_args' ) );

						// Breadcrumbs
						add_filter( 'wpex_breadcrumbs_trail', array( $this, 'filter_crumbs' ) );

						// Filter sidebar display
						add_filter( 'wpex_get_sidebar', array( $this, 'get_sidebar' ) );

						// Define main taxonomy for meta
						add_filter( 'wpex_meta_categories_taxonomy', array( $this, 'meta_tax' ) );

						// Filter grid entry columns
						add_filter( 'wpex_get_grid_entry_columns', array( $this, 'entry_columns' ) );

						// Filter next/prev
						add_filter( 'wpex_has_next_prev', array( $this, 'next_prev' ) );

						// Loop through types
						foreach ( $this->wpex_cpt_settings as $type => $settings ) {

							// Entry layout
							if ( ! empty( $settings['entry_blocks'] ) ) {
								add_filter( 'wpex_'. $type .'_entry_blocks', array( $this, 'entry_blocks' ), 5, 2 );
							}

							// Post Layout
							if ( ! empty( $settings['single_blocks'] ) ) {
								add_filter( 'wpex_'. $type .'_single_blocks', array( $this, 'single_blocks' ), 5, 2 );
							}

						}

						// Filter meta
						add_filter( 'wpex_meta_blocks', array( $this, 'meta_blocks' ), 5, 2 );

					} // End admin check

					// Add image size settings
					add_filter( 'wpex_image_sizes', array( $this, 'add_image_sizes' ), 99 );

					// Register sidebars
					add_filter( 'wpex_register_sidebars_array', array( $this, 'register_sidebars' ) );

				}

			}

		}

		/**
		 * Array of Total settings to add
		 *
		 * @since 3.6.0
		 */
		public function wpex_settings() {

			// Store layouts
			$layout_choices = array();
			$layouts = wpex_get_post_layouts();
			foreach ( $layouts as $key => $val ) {
				$layout_choices[] = array(
					'attr' => $key,
					'text' => $val,
				);
			}

			// Store grid columns
			$grid_columns_choices = array(
				array( 'attr' => '', 'text' => esc_html__( 'Default', 'total' ) )
			);
			$grid_columns = wpex_grid_columns();
			foreach ( $grid_columns as $key => $val ) {
				$grid_columns_choices[] = array(
					'attr' => $key,
					'text' => $val,
				);
			}

			// Store yes/no
			$no_yes = array(
				array( 'attr' => 'false', 'text' => esc_attr__( 'False', 'total' ) ),
				array( 'attr' => 'true', 'text' => esc_attr__( 'True', 'total' ) ),
			);
			$yes_no = array(
				array( 'attr' => 'true', 'text' => esc_attr__( 'True', 'total' ) ),
				array( 'attr' => 'false', 'text' => esc_attr__( 'False', 'total' ) ),
			);

			// Store description
			$string_desc = esc_html__( 'If you are using a translation plugin you should use theme filters instead to alter this text. Please refer to the online documentation and snippets.', 'total' );

			// Entry blocks
			$entry_blocks = apply_filters( 'wpex_cptui_entry_blocks', array(
				'media'    => esc_html__( 'Media', 'total' ),
				'title'    => esc_html__( 'Title', 'total' ),
				'meta'     => esc_html__( 'Meta', 'total' ),
				'content'  => esc_html__( 'Content', 'total' ),
				'readmore' => esc_html__( 'Readmore', 'total' ),
			) );

			// Single blocks
			$single_blocks = apply_filters( 'wpex_cptui_single_blocks', array(
				'media'       => esc_html__( 'Media', 'total' ),
				'title'       => esc_html__( 'Title', 'total' ),
				'meta'        => esc_html__( 'Meta', 'total' ),
				'post-series' => esc_html__( 'Post Series', 'total' ),
				'content'     => esc_html__( 'Content', 'total' ),
				'page-links'  => esc_html__( 'Page Links', 'total' ),
				'share'       => esc_html__( 'Social Share', 'total' ),
				'comments'    => esc_html__( 'Comments', 'total' ),
			) );

			// Meta blocks
			$meta_blocks = apply_filters( 'wpex_cptui_meta_sections', array(
				'date'       => esc_html__( 'Date', 'total' ),
				'author'     => esc_html__( 'Author', 'total' ),
				'categories' => esc_html__( 'Categories', 'total' ),
				'comments'   => esc_html__( 'Comments', 'total' ),
			) );

			// Return array
			return array(
				'add_to_total_array' => array(
					'label' => esc_html__( 'Total Settings', 'total' ),
					'type' => 'select',
					'choices' => $yes_no,
					'default' => 'true',
					'desc' => esc_html__( 'Enable Total settings for this post type.', 'total' ),
				),
				'enable_image_sizes' => array(
					'label' => esc_html__( 'Image Sizes', 'total' ),
					'type' => 'select',
					'choices' => $yes_no,
					'default' => 'true',
					'desc' => esc_html__( 'Enable image size settings for this post type.', 'total' ),
				),
				'custom_sidebar' => array(
					'label' => esc_html__( 'Custom Sidebar', 'total' ),
					'type' => 'textfield',
					'desc' => esc_html__( 'Enter a name to create a custom sidebar for the post type archive, single posts and attached taxonomies.', 'total' ),
				),
				'post_metabox' => array(
					'label' => esc_html__( 'Post Settings Metabox', 'total' ),
					'type' => 'select',
					'choices' => $no_yes,
					'default' => 'false',
				),
				'gallery_metabox' => array(
					'label' => esc_html__( 'Post Gallery', 'total' ),
					'type' => 'select',
					'choices' => $no_yes,
					'default' => 'false',
				),
				'single_layout' => array(
					'label' => esc_html__( 'Single Layout', 'total' ),
					'type' => 'select',
					'choices' => $layout_choices,
				),
				'single_page_header_title' => array(
					'label' => esc_html__( 'Single Page Header title', 'total' ),
					'type' => 'textfield',
					'desc' => $string_desc .'<br/><br/>'. esc_html__( 'Use {{title}} to display the current post title', 'total' ),
				),
				'next_prev' => array(
					'label' => esc_html__( 'Next/Prev', 'total' ),
					'type' => 'select',
					'choices' => $yes_no,
					'default' => 'true',
				),
				'main_page' => array(
					'label' => esc_html__( 'Main Page ID', 'total' ),
					'type' => 'textfield',
					'desc' => esc_html( 'Used for breadcrumbs.', 'total' ),
				),
				'main_taxonomy' => array(
					'label' => esc_html__( 'Main Taxonomy', 'total' ),
					'type' => 'taxonomy_select',
					'desc' => esc_html( 'Used for breadcrumbs and post meta categories.', 'total' ),
				),
				'archive_layout' => array(
					'label' => esc_html__( 'Archive Layout', 'total' ),
					'type' => 'select',
					'choices' => $layout_choices,
				),
				'archive_grid_columns' => array(
					'label' => esc_html__( 'Archive Grid Columns', 'total' ),
					'type' => 'textfield',
					'desc' => esc_html( 'Enter a number between 1 and 7. Default is 1.', 'total' ),
				),
				'archive_posts_per_page' => array(
					'label' => esc_html__( 'Archive Posts Per Page', 'total' ),
					'type' => 'textfield',
					'desc' => esc_html( 'Will apply to the main post type archive and any built-in taxonomies associated with the post type.', 'total' ),
				),
				'archive_page_header_title' => array(
					'label' => esc_html__( 'Archive Page Header Title', 'total' ),
					'type' => 'textfield',
					'desc' => $string_desc,
				),
				'entry_blocks' => array(
					'label' => esc_html__( 'Entry Blocks', 'total' ),
					'type' => 'builder',
					'choices' => $entry_blocks,
				),
				'entry_meta_blocks' => array(
					'label' => esc_html__( 'Entry Meta', 'total' ),
					'type' => 'builder',
					'choices' => $meta_blocks,
				),
				'single_blocks' => array(
					'label' => esc_html__( 'Single Blocks', 'total' ),
					'type' => 'builder',
					'choices' => $single_blocks,
				),
				'single_meta_blocks' => array(
					'label' => esc_html__( 'Single Meta', 'total' ),
					'type' => 'builder',
					'builder_instance' => 'single',
					'choices' => $meta_blocks,
				),
			);
		}

		/**
		 * Add new settings
		 *
		 * @since 3.6.0
		 */
		public function new_settings( $ui ) {

			// Define empty current var
			$current = '';

			// Get current tab
			$tab = ( ! empty( $_GET ) && ! empty( $_GET['action'] ) && 'edit' === $_GET['action'] ) ? 'edit' : 'new';

			// Get current type
			if ( 'edit' == $tab ) {

				$post_type_deleted = apply_filters( 'cptui_post_type_deleted', false );
				$selected_post_type = cptui_get_current_post_type( $post_type_deleted );

				// Get current settings
				if ( ! empty( $_POST['wpex_data'] ) ) {
					$current = array();
					$post_data = $_POST['wpex_data'];
					foreach ( $post_data as $key => $val ) {
						$key = str_replace( 'wpex_', '', $key );
						$current[$key] = $val;
					}
				} elseif ( $selected_post_type ) {
					$wpex_data = $this->wpex_cpt_settings;
					$wpex_data = isset( $wpex_data[$selected_post_type] ) ? $wpex_data[$selected_post_type] : '';
					$current = $wpex_data;
				}

				// Check if disabled
				if ( empty( $current )
					|| ( isset( $current['add_to_total_array'] ) && 'false' == $current['add_to_total_array'] )
				) {
					$current = 'disabled';
				}

			} ?>

			<div class="cptui-section postbox">

				<button type="button" class="handlediv button-link" aria-expanded="true">
					<span class="screen-reader-text"><?php esc_html_e( 'Toggle panel: Total Settings', 'total' ); ?></span>
					<span class="toggle-indicator" aria-hidden="true"></span>
				</button>

				<h2 class="hndle">
					<span><?php esc_html_e( 'Total Theme', 'total' ); ?></span>
				</h2>

				<div class="inside">
					<div class="main">
						<table class="form-table cptui-table">

							<?php
							// Get settings to loop through
							$settings = $this->wpex_settings();

							// Loop through settings
							foreach ( $settings as $key => $val ) {

								// Restore select
								$select = array();

								// Get new val
								$new_val = isset( $current[$key] ) ? $current[$key] : '';

								// Default value
								$default = isset( $val['default'] ) ? $val['default'] : '';

								// Check if enabled
								if ( 'disabled' == $current ) {
									if ( 'add_to_total_array' != $key ) {
										break;
									}
									$new_val = 'false';
								}

								// Text
								if ( 'textfield' == $val['type'] ) {

									// Sanitize columns
									if ( $new_val && 'archive_grid_columns' == $key ) {
										$new_val = intval( $new_val );
										$new_val = $new_val == 0 ? '' : $new_val;
										$new_val = $new_val > 7 ? 7 : $new_val;
									}

									// Display input
									echo $ui->get_text_input( array(
										'namearray' => 'wpex_data',
										'name'      => 'wpex_'. $key,
										'textvalue' => $new_val,
										'labeltext' => $val['label'],
										'helptext'  => isset( $val['desc'] ) ? $val['desc'] : '',
										'required'  => isset( $val['required'] ) ? true : false,
									) );

								}

								// Taxonomy select
								elseif ( 'taxonomy_select' == $val['type'] ) {

									$select['options'] = array(
										array( 'attr' => '', 'text' => esc_attr__( 'None', 'total' ) )
									);
									$add_taxes = get_taxonomies( array( 'public' => true ), 'objects' );
									unset( $add_taxes['nav_menu'] ); unset( $add_taxes['post_format'] );
									foreach ( $add_taxes as $add_tax ) {
										$select['options'][] = array( 'attr' => $add_tax->name, 'text' => $add_tax->labels->name );
									}
									$select['selected'] = $new_val;

									echo $ui->get_select_input( array(
										'namearray'  => 'wpex_data',
										'name'       => 'wpex_'. $key,
										'labeltext'  => $val['label'],
										'aftertext'   => isset( $val['desc'] ) ? $val['desc'] : '',
										'selections' => $select,
									) );

								}

								// Selects
								elseif ( 'select' == $val['type'] ) {

									$select['options']  = $val['choices'];
									$select['selected'] = $new_val ? $new_val : $default;

									echo $ui->get_select_input( array(
										'namearray'  => 'wpex_data',
										'name'       => 'wpex_'. $key,
										'labeltext'  => $val['label'],
										'aftertext'  => isset( $val['desc'] ) ? $val['desc'] : '',
										'selections' => $select,
									) );

								}

								// Entry builder
								if ( 'builder' == $val['type'] ) {

									// Add to current settings
									if ( ! empty( $_POST['wpex_'. $key] ) ) {
										$current[$key] = $_POST['wpex_'. $key];
									}

									// Add label
									echo '<tr valign="top" class="wpex-builder-field">';

									echo $ui->get_th_start() . $val['label'];

									// Open Fieldset
									echo $ui->get_th_end() . $ui->get_td_start() . $ui->get_fieldset_start();

									// Loop through blocks
									foreach ( $val['choices'] as $block => $label ) {

										// Check if enabled
										$checked = true;
										if ( ! empty( $current[$key] ) ) {
											if ( is_array( $current[$key] ) ) {
												if ( in_array( $block, $current[$key] ) ) {
													$checked = 'true';
												} else {
													$checked = 'false';
												}
											}
										}

										// Always true for new tab
										if ( 'new' == $tab ) {
											$checked = 'true';
										}

										// Add selects
										echo $ui->get_check_input( array(
											'checkvalue' => $block,
											'checked'    => $checked,
											'name'       => $block,
											'namearray'  => 'wpex_'. $key,
											'textvalue'  => $block,
											'labeltext'  => $label,
											'default'    => true,
											'wrap'       => false,
										) );

									}

									// Close fieldset
									$ui->get_fieldset_end();

								}

								// Close table
								echo $ui->get_td_end() . $ui->get_tr_end();

							} ?>

						</table>

					</div><!-- .main -->
				</div><!-- .inside -->

			</div><!-- .cptui-section -->
			
		<?php }

		/**
		 * Save Settings
		 *
		 * @since 3.6.0
		 */
		public function save_settings( $data ) {
	
			if ( empty( $data ) || empty( $data['cpt_custom_post_type']['name'] ) ) {
				return;
			}

			$wpex_data = get_option( 'wpex_cpt_settings' );
			$wpex_data = $wpex_data ? $wpex_data : array();

			$type = $data['cpt_custom_post_type']['name'];

			$post_data = $data['wpex_data'];

			// Get settings to loop through
			$settings = $this->wpex_settings();

			// Loop through settings to save them
			foreach ( $settings as $key => $val ) {

				// First check if disabled
				if ( 'add_to_total_array' == $key ) {
					if ( isset( $post_data['wpex_'. $key] ) && 'false' == $post_data['wpex_'. $key] ) {
						$wpex_data[$type] = array();
						break;
					}
				}

				// Get new val for builders (uses $data var seperate from post_data )
				if ( 'builder' == $val['type'] ) {
					if ( ! empty( $data['wpex_'. $key] ) && is_array( $data['wpex_'. $key] ) ) {
						$new_val = $data['wpex_'. $key];
					} else {
						$new_val = array_flip( $val['choices'] );
					}
				}

				// Val for other modules
				else {
					$new_val = ! empty( $post_data['wpex_'. $key] ) ? sanitize_text_field( $post_data['wpex_'. $key] ) : '';
				}

				// Get val
				$default = isset( $val['default'] ) ? $val['default'] : '';

				// Sanitize columns
				if ( $new_val && 'archive_grid_columns' == $key ) {
					$new_val = intval( $new_val );
					$new_val = $new_val > 7 ? 7 : $new_val;
				}

				// Sanitize Posts perpage
				if ( $new_val && 'archive_posts_per_page' == $key ) {
					$new_val = intval( $new_val );
				}

				// Set new option
				if ( $new_val ) {
					$wpex_data[$type][$key] = $new_val;
				} elseif ( $default ) {
					$wpex_data[$type][$key] = $default; // Defaults that can't be removed
				} else {
					unset( $wpex_data[$type][$key] );
				}

			}

			// Save data in new option
			$new_result = update_option( 'wpex_cpt_settings', $wpex_data );

		}

		/**
		 * Enable metabox for types
		 *
		 * @since 3.6.0
		 */
		public function metabox_types( $types ) {

			// Add metabox support to post types
			foreach ( $this->wpex_cpt_settings as $type => $settings ) {
				if ( ! empty( $settings['post_metabox'] )  && 'true' == $settings['post_metabox'] ) {
					$types[$type] = $type;
				}
			}

			// Return post types
			return $types;

		}

		/**
		 * Enable metabox for types
		 *
		 * @since 3.6.0
		 */
		public function enable_metabox_media( $array ) {

			// Add metabox support to post types
			foreach ( $this->wpex_cpt_settings as $type => $settings ) {
				if ( ! empty( $settings['post_metabox'] )  && 'true' == $settings['post_metabox'] ) {
					$array['media']['post_type'][] = $type;
				}
			}

			// Return post types
			return $array;

		}

		/**
		 * Register gallery metabox for your custom types
		 *
		 * @since 3.6.0
		 */
		public function gallery_metabox( $types ) {

			// Add post types support to the gallery metabox
			foreach ( $this->wpex_cpt_settings as $type => $settings ) {
				if ( ! empty( $settings['gallery_metabox'] ) && 'true' == $settings['gallery_metabox'] ) {
					$types[$type] = $type;
				}
			}

			// Return post types
			return $types;

		}

		/**
		 * Adds new image size tabs
		 *
		 * @since 3.6.0
		 */
		public function image_sizes_tabs( $tabs ) {
			foreach ( $this->wpex_cpt_settings as $type => $settings ) {
				if ( ! empty( $settings['enable_image_sizes'] ) && 'true' == $settings['enable_image_sizes'] ) {
					$obj = get_post_type_object( $type );
					if ( $obj && is_object( $obj ) ) {
						$name = $obj->labels->name;
					} else {
						$name = $type;
					}
					$tabs[$type] = $name;
				}
			}
			return $tabs;
		}


		/**
		 * Add image size option tabs for post types
		 *
		 * @since 3.6.0
		 */
		public function add_image_sizes( $sizes ) {

			// Loop through and add new image size options
			foreach ( $this->wpex_cpt_settings as $type => $settings ) {
				if ( ! empty( $settings['enable_image_sizes'] ) && 'true' == $settings['enable_image_sizes'] ) {
					$sizes[$type .'_archive'] = array(
						'label'   => esc_html__( 'Archive', 'total' ),
						'width'   => $type .'_archive_width',
						'height'  => $type .'_archive_height',
						'crop'    => $type .'_archive_image_crop',
						'section' => $type,
					);
					$sizes[$type .'_single'] = array(
						'label'   => esc_html__( 'Post', 'total' ),
						'width'   => $type .'_post_width',
						'height'  => $type .'_post_height',
						'crop'    => $type .'_post_image_crop',
						'section' => $type,
					);
				}
			}

			// Return sizes
			return $sizes;

		}

		/**
		 * Custom Layouts
		 *
		 * @since 3.6.0
		 */
		public function pre_get_posts( $query ) {

			// Not needed in admin
			if ( is_admin() ) {
				return;
			}

			// Make sure query is an object
			if ( ! is_object( $query ) ) {
				return;
			}

			// Only alter main query
			if ( ! $query->is_main_query() ) {
				return;
			}

			// Post type archive posts per page
			if ( $query->is_post_type_archive() ) {
				$type = $query->query['post_type'];
				if ( $type && array_key_exists( $type, $this->wpex_cpt_settings ) ) {
					$settings = $this->wpex_cpt_settings[$type];
					if ( ! empty( $settings['archive_posts_per_page'] ) ) {
						$query->set( 'posts_per_page', $settings['archive_posts_per_page'] );
					}
				}
			}

			// Taxonomy query
			if ( $query->is_tax() ) {
				$types = $this->types;
				foreach ( $this->wpex_cpt_settings as $type => $settings ) {
					$taxonomies = ! empty( $types[$type]['taxonomies'] ) ? $types[$type]['taxonomies'] : '';
					if ( $taxonomies ) {
						foreach ( $taxonomies as $tax ) {
							if ( $query->is_tax( $tax ) && ! empty( $settings['archive_posts_per_page'] ) ) {
								$query->set( 'posts_per_page', $settings['archive_posts_per_page'] );
							}
						}
					}
				}
			}

		}

		/**
		 * Custom Layouts
		 *
		 * @since 3.6.0
		 */
		public function layouts( $layout ) {

			// Single layout
			if ( is_singular() ) {
				$type = get_post_type();
				if ( $type && array_key_exists( $type, $this->wpex_cpt_settings ) ) {
					$settings = $this->wpex_cpt_settings[$type]; 
					if ( ! empty( $settings['single_layout'] ) ) {
						$layout = $settings['single_layout'];
					}
				}
			}

			// Post Type Archives
			elseif ( is_post_type_archive() ) {
				$type = get_post_type();
				if ( $type && array_key_exists( $type, $this->wpex_cpt_settings ) ) {
					$settings = $this->wpex_cpt_settings[$type]; 
					if ( ! empty( $settings['archive_layout'] ) ) {
						$layout = $settings['archive_layout'];
					}
				}
			}

			// Taxonomies
			elseif ( is_tax() ) {
				$types = $this->types;
				foreach ( $this->wpex_cpt_settings as $type => $settings ) {
					if ( ! empty( $types[$type] ) && $type == get_post_type() ) {
						$taxonomies = ! empty( $types[$type]['taxonomies'] ) ? $types[$type]['taxonomies'] : '';
						if ( $taxonomies ) {
							foreach ( $taxonomies as $tax ) {
								if ( is_tax( $tax ) && ! empty( $settings['archive_layout'] ) ) {
									$layout = $settings['archive_layout'];
								}
							}
						}
					}
				}
			}

			// Return layout
			return $layout;

		}

		/**
		 * Page Header Title Args
		 *
		 * @since 3.6.0
		 */
		public function page_header_title_args( $args ) {

			// Single layout
			if ( is_singular() ) {
				$type = get_post_type();
				if ( $type && array_key_exists( $type, $this->wpex_cpt_settings ) ) {
					$settings = $this->wpex_cpt_settings[$type];
					if ( ! empty( $settings['single_page_header_title'] ) ) {
						$title = $settings['single_page_header_title'];
						$title = ( '{{title}}' == $title ) ? get_the_title() : $title;
						$args['string'] = $title;
					}
				}
			}

			// Archives
			if ( is_post_type_archive() ) {
				$type = get_post_type();
				if ( $type && array_key_exists( $type, $this->wpex_cpt_settings ) ) {
					$settings = $this->wpex_cpt_settings[$type];
					if ( ! empty( $settings['archive_page_header_title'] ) ) {
						$args['string'] = $settings['archive_page_header_title'];
					}
				}
			}

			// Return args
			return $args;

		}

		/**
		 * Filter breadcrumbs
		 *
		 * @since 3.6.0
		 */
		public function filter_crumbs( $trail ) {

			// Add category and main type to breadcrumbs
			if ( is_singular() ) {

				$post_type = get_post_type();
				
				if ( $post_type && array_key_exists( $post_type, $this->wpex_cpt_settings ) ) {
					
					$settings = $this->wpex_cpt_settings[$post_type];
						
					// Add archive - should override any pre-defined archive or setting may confuse people
					$main_archive = ! empty( $settings['main_page'] ) ? $settings['main_page'] : '';
					if ( $main_archive && get_post_status( $main_archive ) ) {
						$label = get_the_title( $main_archive );
						$link  = get_permalink($main_archive );
						$trail['post_type_archive'] = WPEX_Breadcrumbs::get_crumb_html( $label, $link );
					}
					
					// Add category
					if ( empty( $trail['categories'] ) ) {
						$main_tax = ! empty( $settings['main_taxonomy'] ) ? $settings['main_taxonomy'] : '';
						if ( $main_tax ) {
							$terms = WPEX_Breadcrumbs::get_post_terms( $main_tax );
							if ( $terms ) {
								$trail['categories'] = '<span class="trail-cptui-terms">'. $terms .'</span>';
							}
						}
					}

				}

			}

			// Return breadcrumbs trail
			return $trail;

		}

		/**
		 * Register sidebars
		 *
		 * @since 3.6.0
		 */
		public function register_sidebars( $sidebars ) {

			// Loop through types and add sidebars
			foreach ( $this->wpex_cpt_settings as $type => $settings ) {
				if ( ! empty( $settings['custom_sidebar'] ) ) {
					$id = $this->sanitize_sidebar_id( $settings['custom_sidebar'] );
					$sidebars[$id] = $settings['custom_sidebar'];
				}
			}

			// Return sidebars
			return $sidebars;

		}

		/**
		 * Get sidebar
		 *
		 * @since 3.6.0
		 */
		public function get_sidebar( $sidebar ) {

			// Only needed here
			if ( ( is_singular() && ! is_page() && ! is_attachment() ) || is_post_type_archive() || is_tax() ) {

				// Save types
				$types = $this->types;

				// Loop through types and add sidebars
				foreach ( $this->wpex_cpt_settings as $type => $settings ) {

					// Get custom sidebar
					$custom_sidebar = ! empty( $settings['custom_sidebar'] ) ? $this->sanitize_sidebar_id( $settings['custom_sidebar'] ) : '';

					// If sidebar not empty lets set it
					if ( $custom_sidebar ) {
						
						// Set for singular
						if ( is_singular( $type ) ) {
							$sidebar = $custom_sidebar;
						}

						// Set for post type
						elseif ( is_post_type_archive( $type ) ) {
							$sidebar = $custom_sidebar;
						}

						// Set for taxes - we need to loop through taxonomies set for type
						elseif ( is_tax() && ! empty( $types[$type] ) && $type == get_post_type() ) {
							$taxonomies = ! empty( $types[$type]['taxonomies'] ) ? $types[$type]['taxonomies'] : '';
							if ( $taxonomies ) {
								foreach ( $taxonomies as $tax ) {
									if ( is_tax( $tax ) ) {
										$sidebar = $custom_sidebar;
									}
								}
							}
						}

					}

				}

			}

			// Return sidebar
			return $sidebar;

		}

		/**
		 * Taxonomy to use for post type meta
		 *
		 * @since 3.6.0
		 */
		public function meta_tax( $taxonomy ) {
			
			// Get current type
			if ( $type = get_post_type() ) {

				// Get settings
				$settings = $this->wpex_cpt_settings;
				$settings = ! empty( $settings[$type] ) ? $settings[$type] : '';

				// Check main taxonomy
				if ( $settings && ! empty( $settings['main_taxonomy'] ) ) {
					$taxonomy = $settings['main_taxonomy'];
				}

			}

			// Return taxonomy
			return $taxonomy;

		}

		/**
		 * Entry classes
		 *
		 * @since 3.6.0
		 */
		public function entry_columns( $columns ) {
			
			// Loop through types
			if ( $type = get_post_type() ) {

				// Get type settings
				$settings = $this->wpex_cpt_settings;
				$settings = ! empty( $settings[$type] ) ? $settings[$type] : '';

				// Set columns if defined
				if ( $settings && ! empty( $settings['archive_grid_columns'] ) ) {
					$columns = $settings['archive_grid_columns'];
				}

			}

			// Return columns
			return $columns;

		}

		/**
		 * Next Prev links
		 *
		 * @since 3.6.0
		 */
		public function next_prev( $bool ) {

			// Get current type
			if ( $type = get_post_type() ) {

				// Get settings
				$settings = $this->wpex_cpt_settings;
				$settings = ! empty( $settings[$type] ) ? $settings[$type] : '';

				// Check main taxonomy
				if ( $settings && ! empty( $settings['next_prev'] ) ) {
					if ( 'false' == $settings['next_prev'] ) {
						$bool = false;
					} elseif ( 'true' == $settings['next_prev'] ) {
						$bool = true;
					}
				}

			}

			// Return columns
			return $bool;

		}

		/**
		 * Filter entry blocks
		 *
		 * @since 3.6.0
		 */
		public function entry_blocks( $blocks, $type ) {

			// Type required
			if ( $type ) {

				// Get settings
				$settings = $this->wpex_cpt_settings;
				$settings = ! empty( $settings[$type] ) ? $settings[$type] : '';

				// Return entry blocks if defined
				if ( $settings && ! empty( $settings['entry_blocks'] ) ) {
					$blocks = $settings['entry_blocks'];
				}

			}

			// Return blocks
			return $blocks;

		}

		/**
		 * Filter single blocks
		 *
		 * @since 3.6.0
		 */
		public function single_blocks( $blocks, $type ) {

			// Type required
			if ( $type ) {

				// Get settings
				$settings = $this->wpex_cpt_settings;
				$settings = ! empty( $settings[$type] ) ? $settings[$type] : '';

				// Return single blocks if defined
				if ( $settings && ! empty( $settings['single_blocks'] ) ) {
					$blocks = $settings['single_blocks'];
				}

			}

			// Return blocks
			return $blocks;

		}

		/**
		 * Filter meta blocks
		 *
		 * @since 3.6.0
		 */
		public function meta_blocks( $blocks, $type ) {

			// Type required
			if ( $type ) {

				// Get settings
				$settings = $this->wpex_cpt_settings;
				$settings = ! empty( $settings[$type] ) ? $settings[$type] : '';

				// No settigns, return blocks
				if ( ! $settings ) {
					return $blocks;
				}

				// Return entry meta blocks
				if ( ! empty( $settings['entry_meta_blocks'] ) ) {
					if ( ! is_singular() || ( is_singular() && ! is_main_query() ) ) {
						$blocks = $settings['entry_meta_blocks'];
					}
				}

				// Return single meta blocks
				if ( ! empty( $settings['single_meta_blocks'] )
					&& is_singular()
					&& is_main_query()
				) {
					$blocks = $settings['single_meta_blocks'];
				}

			}

			// Return blocks
			return $blocks;

		}

		/**
		 * Sanitize sidebar ID
		 *
		 * @since 3.6.0
		 */
		public function sanitize_sidebar_id( $id ) {
			$id = wp_strip_all_tags( $id );
			$id = str_replace( ' ', '_', $id );
			$id = strtolower( $id );
			return $id;
		}

	}

	new WPEX_CPT_UI_Types();

}
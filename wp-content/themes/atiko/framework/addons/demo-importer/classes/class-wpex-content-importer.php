<?php

if ( ! class_exists( 'WPEX_Content_Importer' ) ) {

	class WPEX_Content_Importer {

		/**
		 * Contains the data for the demos.
		 *
		 * @since 1.1.0
		 * 
		 * @var array
		 */
		private $demos = array();
		
		public function __construct() {
			// Nothing needed here.
		}

		public function set_demos_data( $data ) {
			$this->demos = $data;
		}

		/**
		 * Import XML data
		 *
		 * @since 1.1.0
		 */
		public function process_xml( $demo, $import_images ) {
			
			$response = WPEX_Demo_Importer_Utils::remote_get( $this->demos[ $demo ][ 'xml' ] );

			// No sample data found
			if ( $response === false ) {
				return new WP_Error( 'xml_import_error', esc_html__( 'Can not retrieve sample data xml file. The hosting server may be down at the momment. Please try again later. If you still have issues contact the theme developer for assistance.', 'total' ) );
			}

			// Write sample data content to temp xml file
			$temp_xml = WPEX_DEMO_IMPORTER_DIR .'/temp.xml';
			file_put_contents( $temp_xml, $response );

			// Set temp xml to attachment url for use
			$attachment_url = $temp_xml;

			// If file exists lets import it
			if ( file_exists( $attachment_url ) ) {
				$this->import_xml( $attachment_url, $import_images );
			} else {
				// Import file can't be imported - we should die here since this is core for most people.
				return new WP_Error( 'xml_import_error', __( 'The xml import file could not be accessed. Please try again or contact the theme developer.', 'total' ) );
			}

		}
		
		private function import_xml( $file, $import_images ) {

			// Make sure importers constant is defined
			if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
				define( 'WP_LOAD_IMPORTERS', true );
			}

			// Import file location
			$import_file = ABSPATH . 'wp-admin/includes/import.php';

			// Include import file
			if ( ! file_exists( $import_file ) ) {
				return;
			}

			// Include import file
			require_once $import_file;

			// Define error var
			$importer_error = false;

			if ( ! class_exists( 'WP_Importer' ) ) {
				$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

				if ( file_exists( $class_wp_importer ) ) {
					require_once $class_wp_importer;
				} else {
					$importer_error = __( 'Can not retrieve class-wp-importer.php', 'total' );
				}
			}

			if ( ! class_exists( 'WP_Import' ) ) {
				$class_wp_import = WPEX_DEMO_IMPORTER_DIR .'/classes/wordpress-importer/wordpress-importer.php';

				if ( file_exists( $class_wp_import ) ) {
					require_once $class_wp_import;
				} else {
					$importer_error = __( 'Can not retrieve wordpress-importer.php', 'total' );
				}
			}

			// Display error
			if ( $importer_error ) {
				return new WP_Error( 'xml_import_error', $importer_error );
			} else {

				// No error, lets import things...
				if ( ! is_file( $file ) ) {
					$importer_error = __( 'Sample data file appears corrupt or can not be accessed.', 'total' );
					return new WP_Error( 'xml_import_error', $importer_error );
				} else {
					$wp_import = new WP_Import();
					$wp_import->fetch_attachments = $import_images;
					$wp_import->import( $file );
				}
			}
		}

		/**
		 * Process the theme mods json file
		 *
		 * @since 1.0.0
		 */
		public function process_theme_mods( $demo ) {

			// Save backup
			update_option( 'total_import_theme_mods_backup', get_theme_mods() );

			// Get file from github and save remotely
			$mods = WPEX_Demo_Importer_Utils::remote_get( $this->demos[ $demo ]['theme_mods'] );

			// Make sure mods aren't empty
			if ( empty( $mods ) ) {
				return new WP_Error( 'theme_mods_import_error', __( 'No customizer found or required for this demo.', 'total' ) );
			}

			// Extract json data
			$data = json_decode( $mods, true );

			// Import widgets and save results
			if ( '0' == json_last_error() ) {
				$this->import_theme_mods( $data );
			} else {
				return new WP_Error( 'theme_mods_import_error', __( 'There was an error parsing the theme mods json file.', 'total' ) );
			}
		}

		/**
		 * Removes & Imports theme mods
		 *
		 * @since 1.0.0
		 */
		private function import_theme_mods( $data ) {

			// Double check
			if ( empty( $data ) ) {
				return;
			}

			// Remove all mods
			remove_theme_mods();

			// Re-add the menus
			foreach ( $data as $key => $val ) {
				set_theme_mod( $key, $val );
			}
		}

		/**
		 * Import Revsliders
		 *
		 * @since 1.0.0
		 */
		public function process_sliders_import( $demo ) {
			
			// Return since no sliders are defined
			if ( empty( $this->demos[ $demo ]['sliders'] ) ) {
				return;
			}

			// Checks
			if ( ! function_exists( 'download_url' ) ) {
				return new WP_Error( 'revslider_import_error', __( 'Sliders could not be imported because the download_url function does not exist.', 'total' ) );
			}

			// Make sure rev is active
			if ( ! class_exists( 'RevSlider' ) ) {
				return new WP_Error( 'revslider_import_error', __( 'Sliders could not be imported because the Revolution slider plugin is disabled.', 'total' ) );
			}

			// Get sliders for this demo
			$sliders = $this->demos[ $demo ]['sliders'];

			$errors = array();

			// Loop through slider zips and upload to media library then import
			foreach( $sliders as $slider_url ) {

				// Download zip from github
				$temp = download_url( $slider_url );

				// Files array for use with media_handle_sideload()
				$file_array = array(
					'name'     => basename( $slider_url ),
					'tmp_name' => $temp
				);

				// Check for download errors
				if ( $error = is_wp_error( $temp ) ) {
					if ( is_object( $error ) ) {
						$errors[] = new WP_Error( 'revslider_import_error', __( 'Slider error:', 'total' ) .' '. $temp->get_error_message() );
					}
					unlink( $file_array[ 'tmp_name' ] );
					continue;
				}

				// Get media ID
				$id = media_handle_sideload( $file_array, 0 );

				// Check for handle sideload errors.
				if ( is_wp_error( $id ) ) {
					if ( is_object( $id ) ) {
						$errors[] = new WP_Error( 'revslider_import_error', __( 'Slider error:', 'total' ) .' '. $id->get_error_message() );
					}
					unlink( $file_array['tmp_name'] );
					continue;
				}

				// Get attachment url
				$attachment_url = get_attached_file( $id );

				// New Revslider Class
				$slider = new RevSlider();

				// Import slider
				$slider->importSliderFromPost( true, true, $attachment_url );
			}

			return $errors;
		}

		/**
		 * Process the widget import
		 *
		 * @since 1.0.0
		 */
		public function process_widget_import( $demo ) {

			// Get widgets json file
			$widgets = WPEX_Demo_Importer_Utils::remote_get( $this->demos[ $demo ][ 'widgets' ] );

			// Display warning and return
			if ( empty( $widgets ) ) {
				return new WP_Error( 'widgets_import_error', __( 'No widgets found or required for this demo.', 'total' ) );
			}

			// Extract json data
			$data = json_decode( $widgets, true );

			// Import widgets and save results
			if ( '0' == json_last_error() ) {

				// Import widgets
				$this->import_widgets( $data );
			}
		}

		/**
		 * Available widgets
		 *
		 * Save a list of the site's widgets
		 *
		 * @since 2.2.0
		 *
		 * @global array $wp_registered_widget_updates
		 * @return array Widget information
		 */
		private function available_widgets() {
			global $wp_registered_widget_controls;
			$widget_controls   = $wp_registered_widget_controls;
			$available_widgets = array();

			foreach ( $widget_controls as $widget ) {
				if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) {
					$available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
					$available_widgets[$widget['id_base']]['name']    = $widget['name'];
				}
			}

			return $available_widgets;
		}

		/**
		 * Imports the widgets
		 *
		 * @since 1.0.0
		 */
		private function import_widgets( $data ) {

			// Double check
			if ( empty( $data ) ) {
				return;
			}

			global $wp_registered_sidebars;

			// Hook before import
			$data = apply_filters( 'wpex_before_widgets_import', $data );

			// Get all available widgets site supports
			$available_widgets = $this->available_widgets();

			// Get all existing widget instances
			$widget_instances = array();

			foreach ( $available_widgets as $widget_data ) {
				$widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );
			}

			// Loop import data's sidebars
			foreach ( $data as $sidebar_id => $widgets ) {

			// Skip inactive widgets (should not be in export file)
			if ( 'wp_inactive_widgets' == $sidebar_id ) {
				continue;
			}

			// Check if sidebar is available on this site
			// Otherwise add widgets to inactive, and say so
			if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
			  $sidebar_available = true;
			  $use_sidebar_id = $sidebar_id;
			  $sidebar_message_type = 'success';
			  $sidebar_message = '';
			} else {
			  $sidebar_available = false;
			  $use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
			  $sidebar_message_type = 'error';
			  $sidebar_message = __( 'Sidebar does not exist in theme (using Inactive)', 'total' );
			}

			/* Result for sidebar
			$this->results[$sidebar_id]['name'] = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
			$this->results[$sidebar_id]['message_type'] = $sidebar_message_type;
			$this->results[$sidebar_id]['message'] = $sidebar_message;
			$this->results[$sidebar_id]['widgets'] = array();*/

			// Loop widgets
			foreach ( $widgets as $widget_instance_id => $widget ) {
				  $fail = false;

				  // Get id_base (remove -# from end) and instance ID number
				  $id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
				  $instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

				  // Does site support this widget?
				  if ( ! $fail && ! isset( $available_widgets[$id_base] ) ) {
					$fail = true;
					$widget_message_type = 'error';
					$widget_message = __( 'Site does not support widget', 'total' ); // explain why widget not imported
				  }

				  // Filter to modify settings before import
				  // Do before identical check because changes may make it identical to end result (such as URL replacements)
				  $widget = apply_filters( 'radium_theme_import_widget_settings', $widget );

				  // Does widget with identical settings already exist in same sidebar?
				  if ( ! $fail && isset( $widget_instances[$id_base] ) ) {

					// Get existing widgets in this sidebar
					$sidebars_widgets = get_option( 'sidebars_widgets' );
					$sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go
					
					// Loop widgets with ID base
					$single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
					
					foreach ( $single_widget_instances as $check_id => $check_widget ) {
					  
					  // Is widget in same sidebar and has identical settings?
					  if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {
						$fail = true;
						$widget_message_type = 'warning';
						$widget_message = __( 'Widget already exists', 'total' ); // explain why widget not imported
						break;
					  }

					}

				  }

				  // No failure
				  if ( ! $fail ) {

					// all instances for that widget ID base, get fresh every time
					$single_widget_instances = get_option( 'widget_' . $id_base );

					// start fresh if have to
					$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 );

					// add it
					$single_widget_instances[] = (array) $widget;

					// Get the key it was given
					end( $single_widget_instances );
					$new_instance_id_number = key( $single_widget_instances );

					// If key is 0, make it 1
					// When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
					if ( '0' === strval( $new_instance_id_number ) ) {
					  $new_instance_id_number = 1;
					  $single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
					  unset( $single_widget_instances[0] );
					}
					// Move _multiwidget to end of array for uniformity
					if ( isset( $single_widget_instances['_multiwidget'] ) ) {
					  $multiwidget = $single_widget_instances['_multiwidget'];
					  unset( $single_widget_instances['_multiwidget'] );
					  $single_widget_instances['_multiwidget'] = $multiwidget;
					}

					// Update option with new widget
					update_option( 'widget_' . $id_base, $single_widget_instances );

					// Assign widget instance to sidebar
					$sidebars_widgets = get_option( 'sidebars_widgets' );

					// use ID number from new widget instance
					$new_instance_id = $id_base . '-' . $new_instance_id_number;

					// add new instance to sidebar
					$sidebars_widgets[$use_sidebar_id][] = $new_instance_id;

					// save the amended data
					update_option( 'sidebars_widgets', $sidebars_widgets );

					// Success message
					if ( $sidebar_available ) {
					  $widget_message_type = 'success';
					  $widget_message      = __( 'Imported', 'total' );
					} else {
					  $widget_message_type = 'warning';
					  $widget_message      = __( 'Imported to Inactive', 'total' );
					}
				  }

				  /* Result for widget instance
				  $this->results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base;
				  $this->results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = $widget->title ? $widget->title : __( 'No Title', 'total' );
				  $this->results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
				  $this->results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message; */
				}
			}
		}

		/**
		 * Set menu locations
		 *
		 * @since 1.0.0
		 */
		public function set_menus( $demo ) {

			// Return since no menus are defined
			if ( empty( $this->demos[ $demo ]['nav_menu_locations'] ) ) {
				return;
			}

			// Get current locations
			$locations = get_theme_mod( 'nav_menu_locations' );

			// Add demo locations
			foreach ( $this->demos[$demo]['nav_menu_locations'] as $location => $name ) {
				$menu                 = get_term_by( 'name', $name, 'nav_menu');
				$locations[$location] = $menu->term_id;
			}

			// Set menu locations
			set_theme_mod( 'nav_menu_locations', $locations );
		}

		/**
		 * Set homepage
		 *
		 * @since 1.0.0
		 */
		public function set_homepage( $demo ) {

			if ( empty( $this->demos[$demo]['homepage_slug'] ) ) {
				return;
			}

			$page = get_page_by_path( $this->demos[$demo]['homepage_slug'] );

			update_option( 'page_on_front', $page->ID );
			update_option( 'show_on_front', 'page' );
		}

		/**
		 * Set posts page
		 *
		 * @since 1.0.0
		 */
		public function set_posts_page( $demo ) {

			if ( empty( $this->demos[$demo]['page_for_posts'] ) ) {
				return;
			}

			$posts_page = get_page_by_path( $this->demos[$demo]['page_for_posts'] );

			update_option( 'page_for_posts', $posts_page->ID );
			
		}

		/**
		 * Set shop page
		 *
		 * @since 1.0.0
		 */
		public function set_shop_page( $demo ) {

			if ( empty( $this->demos[$demo]['shop_slug'] ) ) {
				return;
			}

			$shop = get_page_by_path( $this->demos[$demo]['shop_slug'] );

			update_option( 'woocommerce_shop_page_id', $shop->ID );
			
		}

	}
}
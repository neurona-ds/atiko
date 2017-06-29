<?php
/**
 * Plugin Name: Total Theme Demo Importer
 * Plugin URI: http://wpexplorer-themes.com/total/
 * Description: Import your favorite Total theme demos
 * Author: AJ Clarke
 * Author URI: http://www.wpexplorer.com
 * Version: 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPEX_Demo_Importer' ) ) {

	class WPEX_Demo_Importer {

		/**
		 * The URL to the demos' data.
		 *
		 * @since 1.1.0
		 * 
		 * @var string
		 */
		private $demos_remote_url;

		/**
		 * Contains the data for the demos.
		 *
		 * @since 1.1.0
		 * 
		 * @var array
		 */
		private $demos;

		/**
		 * Contains the categories of demos.
		 *
		 * @since 1.1.0
		 * 
		 * @var array
		 */
		private $categories;

		/**
		 * Contains the plugins required by the demos.
		 *
		 * @since 1.1.0
		 * 
		 * @var array
		 */
		private $plugins;

		/**
		 * Instance of the WPEX_Plugin_Installer class which is used to
		 * activate and install plugins automatically.
		 *
		 * @since 1.1.0
		 * 
		 * @var Object
		 */
		private $plugin_installer;

		/**
		 * Instance of the WPEX_Content_Importer class, which is used to import
		 * the XML content, theme customizations, widgets, sliders and other
		 * available data.
		 *
		 * @since 1.1.0
		 * 
		 * @var Object
		 */
		private $content_importer;

		/**
		 * Start things up
		 */
		public function __construct() {

			// Not needed here...
			if ( ! is_admin() || is_customize_preview() ) {
				return;
			}

			// Disable Woo Wizard
			add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );
			add_filter( 'woocommerce_show_admin_notice', '__return_false' );
			add_filter( 'woocommerce_prevent_automatic_wizard_redirect', '__return_false' );

			// Disable bbPress redirect
			if ( WPEX_BBPRESS_ACTIVE ) {
				remove_action( 'bbp_admin_init', 'bbp_do_activation_redirect', 1 );
			}

			// Constants
			define( 'WPEX_DEMO_IMPORTER_DIR', WPEX_THEME_DIR . '/framework/addons/demo-importer' );
			define( 'WPEX_DEMO_IMPORTER_URI', WPEX_THEME_URI . '/framework/addons/demo-importer' );
			define( 'WPEX_DEMO_IMPORTER_PLUGINS_DIR', WPEX_FRAMEWORK_DIR_URI . 'plugins/' );

			// Include core classes
			require_once WPEX_DEMO_IMPORTER_DIR . '/classes/class-wpex-demo-importer-utils.php';
			require_once WPEX_DEMO_IMPORTER_DIR . '/classes/class-wpex-content-importer.php';
			require_once WPEX_DEMO_IMPORTER_DIR . '/classes/class-wpex-plugin-installer.php';

			// Start things up
			add_action( 'admin_menu', array( $this, 'add_page' ), 1 );
			add_action( 'admin_init', array( $this, 'init' ), 20 );
			add_filter( 'upload_mimes', array( $this, 'allow_xml_uploads' ) );

		}

		/**
		 * Make sure the demo importer can run on the server
		 *
		 * @since 1.0.0
		 */
		public function init_checks() {
			$errors = array();
			if ( ! function_exists( 'json_last_error' ) ) {
				$errors['json_last_error'] = esc_html__( 'Unfortuantely your server is using a version of PHP not supported by WordPress. Please make sure to update your PHP version to at least version 5.5 in order to proceed with the import.', 'total' );
			}
			if ( $errors ) {
				return $errors;
			} else {
				return 'passed';
			}
		}

		/**
		 * Initialize everything
		 *
		 * @since 1.0.0
		 */
		public function init() {

			// Enqueue the admin style and javascript
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

			// Register the AJAX methods
			add_action( 'wp_ajax_wpex_get_selected_demo_data', array( $this, 'ajax_get_selected_demo_data' ) );
			add_action( 'wp_ajax_wpex_post_content_to_import', array( $this, 'ajax_post_content_to_import' ) );
			add_action( 'wp_ajax_wpex_get_import_data', array( $this, 'ajax_get_import_data' ) );

			add_action( 'wp_ajax_wpex_post_import_xml_data', array( $this, 'ajax_post_import_xml_data' ) );
			add_action( 'wp_ajax_wpex_post_import_mods', array( $this, 'ajax_post_import_mods' ) );
			add_action( 'wp_ajax_wpex_post_import_widgets', array( $this, 'ajax_post_import_widgets' ) );
			add_action( 'wp_ajax_wpex_post_import_sliders', array( $this, 'ajax_post_import_sliders' ) );
			add_action( 'wp_ajax_wpex_post_import_complete', array( $this, 'ajax_post_import_complete' ) );

			add_action( 'wp_ajax_wpex_post_install_plugin', array( $this, 'ajax_post_install_plugin' ) );
			add_action( 'wp_ajax_wpex_post_activate_plugin', array( $this, 'ajax_post_activate_plugin' ) );
			
		}

		/**
		 * Add sub menu page
		 *
		 * @since 1.0.0
		 */
		public function add_page() {
			if ( ! defined( 'WPEX_THEME_PANEL_SLUG' ) ) {
				return;
			}

			// Add page
			add_submenu_page(
				WPEX_THEME_PANEL_SLUG,
				__( 'Demo Importer', 'total' ),
				__( 'Demo Importer', 'total' ),
				'administrator',
				WPEX_THEME_PANEL_SLUG .'-demo-importer',
				array( $this, 'demos_page' )
			);

		}

		/**
		 * Renders the main Demo Imported page
		 *
		 * @since 1.1.0
		 */
		public function demos_page() {
			//delete_transient( 'wpex_demos_data' );
			$this->init_checks = self::init_checks();

			if ( 'passed' != $this->init_checks ) {
				include_once( 'views/not-supported.php' );
			} else {
				$this->init_demos_data();
			}

			if ( ! $this->demos ) {
				delete_transient( 'wpex_demos_data' );
				include_once( 'views/no-demos.php' );
			} else {
				include_once( 'views/demos.php' );
			}
		}

		/**
		 * Enqueue admin stylesheets
		 *
		 * @since 1.1.0
		 */
		public function enqueue_admin_styles( $hook ) {
			if ( defined( 'WPEX_ADMIN_PANEL_HOOK_PREFIX' )
				&& WPEX_ADMIN_PANEL_HOOK_PREFIX . '-demo-importer' == $hook
			) {
				wp_enqueue_style( 'total-theme-demo-importer', WPEX_DEMO_IMPORTER_URI . '/assets/css/admin.css' );
			}
		}

		/**
		 * Enqueue admin JavaScript files
		 *
		 * @since 1.1.0
		 */
		public function enqueue_admin_scripts( $hook ) {
			if ( defined( 'WPEX_ADMIN_PANEL_HOOK_PREFIX' )
				&& WPEX_ADMIN_PANEL_HOOK_PREFIX . '-demo-importer' == $hook
			) {
				wp_enqueue_script( 'jquery' );

				wp_enqueue_script(
					'lazyload', WPEX_DEMO_IMPORTER_URI . '/assets/js/jquery.lazyload.min.js',
					array( 'jquery' ),
					false,
					true
				);

				wp_enqueue_script(
					'isotope', WPEX_DEMO_IMPORTER_URI . '/assets/js/isotope.pkgd.min.js',
					array( 'jquery' ),
					false,
					true
				);

				wp_enqueue_script(
					'total-theme-demo-importer', WPEX_DEMO_IMPORTER_URI . '/assets/js/admin.js',
					array( 'jquery' ),
					false,
					true
				);

				wp_localize_script( 'total-theme-demo-importer', 'wpex_strings', array(
					'installingPlugin' => esc_html__( 'Installing plugin', 'total' ),
					'activatingPlugin' => esc_html__( 'Activating plugin', 'total' ),
				) );

				// pass some PHP variables to the JavaScript file
				wp_localize_script( 'total-theme-demo-importer', 'wpex_js_vars', array(
					'ajaxurl'                         => admin_url( 'admin-ajax.php' ),
					'get_selected_demo_data_nonce'    => wp_create_nonce( 'get-selected-demo-data' ),
					'get_import_data_nonce'           => wp_create_nonce( 'get-import-data' ),
					'plugin_failed_activation'        => esc_html__( 'The plugin failed to activate.', 'total' ),
					'plugin_failed_activation_retry'  => esc_html__( 'The plugin failed to activate. Please try again.', 'total' ),
					'plugin_failed_activation_memory' => esc_html__( 'The plugin failed to activate. Please try to increase the memory_limit on your server.', 'total' ),
					'content_importing_error'         => esc_html__( 'There was a problem during the importing process resulting in the following error from your server:', 'total' ),
				) );
			}
		}

		/**
		 * Allows xml uploads so we can import from github
		 *
		 * @since 1.0.0
		 */
		public function allow_xml_uploads( $mimes ) {
			$mimes = array_merge( $mimes, array(
				'xml' => 'application/xml'
			) );
    		return $mimes;
		}

		/**
		 * Initializes the demos data
		 *
		 * @since 1.1.0
		 */
		protected function init_demos_data() {

			// the remote URL where the data is stored
			$this->demos_remote_url = apply_filters( 'wpex_get_demos_remote_url', 'https://totaltheme.wpengine.com/demos-json/' );
 
			// demo data
			$demos_data = $this->get_demos_data();
 
			// list of all the currently available demos
			$this->demos = $demos_data[ 'demos' ];

			//print_r( $this->demos );
 
			// list of all the currently available categories
			$this->categories = $demos_data[ 'categories' ];

			// list of all the currently available plugins
			$this->plugins = $demos_data[ 'plugins' ];

			// add the slug and source for each plugin
			foreach( $this->plugins as $key => $value ) {
				$this->plugins[ $key ]['slug'] = $key;

				if ( $value['location'] === 'bundled' ) {
					$this->plugins[ $key ]['source'] = WPEX_DEMO_IMPORTER_PLUGINS_DIR . $key . '.zip';
				}
			}
		}

		/**
		 * Gets the list of demos, demo categories and required plugins from the json file
		 *
		 * @since 1.0.0
		 */
		protected function get_demos_data() {
			
			// Try to retrieve the demos data from the transient option.
			// If it doesn't exist or it's expired, load it from the remote location
			// and then store it in the transient for later use.
			if ( ( $demos = get_transient( 'wpex_demos_data' ) ) === false ) {
	
				// Get list of demos
				$response = WPEX_Demo_Importer_Utils::remote_get( $this->demos_remote_url );

				if ( $response === false ) {
					return array(
						'demos' => array(),
						'categories' => array(),
						'plugins' => array()
					);
				} else {
					// Extract json data
					$data = json_decode( $response, true );

					if ( '0' == json_last_error() ) {
						$demos = $data;

						// Store the demos in a transient for 1 hour
						set_transient( 'wpex_demos_data', $demos, 60 * 60 );
					} else {
						return array(
							'demos' => array(),
							'categories' => array(),
							'plugins' => array()
						);
					}
				}
			}

			return array(
				'demos'      => $demos['demos'],
				'categories' => $demos['categories'],
				'plugins'    => $demos['plugins']
			);
		}		

		/**
		 * Gets the popup content associated with the selected demo
		 *
		 * @since 1.1.0
		 */
		public function ajax_get_selected_demo_data() {
			$demo = $_GET['demo_name'];

			if ( ! wp_verify_nonce( $_GET['get_selected_demo_data_nonce'], 'get-selected-demo-data' ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			$this->init_demos_data();

			$this->plugin_installer = new WPEX_Plugin_Installer();
			$this->plugin_installer->set_plugins_data( $this->plugins );

			// Extract demo data
			$demo_data = $this->demos[ $demo ];

			include( 'views/selected.php' );

			die();
		}

		/**
		 * Returns an array containing all the importable content with the corresponding data
		 * (the name of the input field, the name of the AJAX method) for each content type.
		 *
		 * @since 1.1.0
		 */
		public function ajax_get_import_data() {
			if ( ! wp_verify_nonce( $_GET['get_import_data_nonce'], 'get-import-data' ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			echo json_encode( 
				array(
					'xml_data' => array(
						'input_name' => 'wpex_import_xml',
						'action' => 'wpex_post_import_xml_data',
						'method' => 'ajax_post_import_xml_data',
						'preloader' => __( 'Importing XML Data', 'total' )
					),
	 
					'mods' => array(
						'input_name' => 'wpex_import_mods',
						'action' => 'wpex_post_import_mods',
						'method' => 'ajax_post_import_mods',
						'preloader' => __( 'Importing Customizer Settings', 'total' )
					),
	 
					'widgets' => array(
						'input_name' => 'wpex_import_widgets',
						'action' => 'wpex_post_import_widgets',
						'method' => 'ajax_post_import_widgets',
						'preloader' => __( 'Importing Widgets', 'total' )
					),
	 
					'sliders' => array(
						'input_name' => 'wpex_import_sliders',
						'action' => 'wpex_post_import_sliders',
						'method' => 'ajax_post_import_sliders',
						'preloader' => __( 'Importing Sliders', 'total' )
					)
				)
			);

			die();
		}

		/**
		 * Activates a plugin
		 *
		 * @since 1.1.0
		 */
		public function ajax_post_activate_plugin() {
			$nonce = $_POST['wpex_plugin_nonce'];
			$plugin = $_POST['wpex_plugin_slug'];

			if ( ! wp_verify_nonce( $nonce, 'activate-plugin_' . $plugin ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			$this->init_demos_data();
			
			$this->plugin_installer = new WPEX_Plugin_Installer();
			$this->plugin_installer->set_plugins_data( $this->plugins );

			// Check if the plugin is not already activated
			if ( $this->plugin_installer->is_plugin_activated( $plugin ) === false ) {
					
				$result = $this->plugin_installer->activate_plugin( $plugin );

				if ( $result === true ) {
					echo 'successful activation';
				} else {
					echo 'failed activation';
				}
			} else {
				echo 'successful activation';
			}

			die();
		}

		/**
		 * Installs a plugin and then activates it
		 *
		 * @since 1.1.0
		 */
		public function ajax_post_install_plugin() {
			$nonce = $_POST['wpex_plugin_nonce'];
			$plugin = $_POST['wpex_plugin_slug'];
			$plugin_installed = false;

			if ( ! wp_verify_nonce( $nonce, 'install-plugin_' . $plugin ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			$this->init_demos_data();

			$this->plugin_installer = new WPEX_Plugin_Installer();
			$this->plugin_installer->set_plugins_data( $this->plugins );

			// Check if the plugin is not already installed
			if ( $this->plugin_installer->is_plugin_installed( $plugin ) ) {
				$plugin_installed = true;
			} else {

				// Try to install the plugin.
				$plugin_installed = $this->plugin_installer->install_plugin( $plugin );
			}

			// If the plugins is installed
			if ( $plugin_installed === true ) {

				// Check if the plugin is not already activated
				if ( $this->plugin_installer->is_plugin_activated( $plugin ) === false ) {
					
					// Get the file path of the plugin in order to pass it to the activation method
					$plugin_file_path = $this->plugin_installer->get_plugin_file_path( $plugin );

					// Try to activate the plugin
					$activation_result = $this->plugin_installer->activate_plugin( $plugin_file_path );
					
					if ( $activation_result === true ) {
						echo 'successful installation';
					} else {
						echo 'failed activation';
					}
				} else {
					echo 'successful installation';
				}
			} else {
				$plugin_source = $this->plugin_installer->get_download_link( $plugin );

				echo sprintf( __( 'The plugin failed to install. Please check the permissions for the "plugins" directory or <a href="%s" target="_blank">download</a> the plugin and install it manually.', 'total' ), $plugin_source );
			}

			die();
		}

		/**
		 * Import XML Data and, optionally, the attachments
		 *
		 * @since 1.1.0
		 */
		public function ajax_post_import_xml_data() {
			if ( ! wp_verify_nonce( $_POST['wpex_import_demo_nonce'], 'wpex_import_demo_nonce' ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			$this->init_demos_data();

			$this->content_importer = new WPEX_Content_Importer();
			$this->content_importer->set_demos_data( $this->demos );

			// get the slug of the demo
			$demo = $_POST['wpex_import_demo'];

			// indicates if the images will be imported
			$import_images = ( isset( $_POST[ 'wpex_import_xml_attachments' ] ) && $_POST[ 'wpex_import_xml_attachments' ] === 'true' ) ? true : false;

			// delete the default post and page
			$sample_page = get_page_by_path( 'sample-page', OBJECT, 'page' );

			if ( ! is_null( $sample_page ) ) {
				wp_delete_post( $sample_page->ID, true );
			}

			$hello_world_post = get_page_by_path( 'hello-world', OBJECT, 'post' );

			if ( ! is_null( $hello_world_post ) ) {
				wp_delete_post( $hello_world_post->ID, true );
			}

			$result = $this->content_importer->process_xml( $demo, $import_images );
			
			if ( is_wp_error( $result ) ) {
				echo json_encode( $result->errors );
			} else {
				echo 'successful import';
			}

			die();
		}

		/**
		 * Import customizer settings
		 *
		 * @since 1.1.0
		 */
		public function ajax_post_import_mods() {
			if ( ! wp_verify_nonce( $_POST['wpex_import_demo_nonce'], 'wpex_import_demo_nonce' ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			$this->init_demos_data();

			$this->content_importer = new WPEX_Content_Importer();
			$this->content_importer->set_demos_data( $this->demos );

			// get the slug of the demo
			$demo = $_POST['wpex_import_demo'];

			$result = $this->content_importer->process_theme_mods( $demo );

			if ( is_wp_error( $result ) ) {
				echo json_encode( $result->errors );
			} else {
				echo 'successful import';
			}

			die();
		}

		/**
		 * Import widgets
		 *
		 * @since 1.1.0
		 */
		public function ajax_post_import_widgets() {
			if ( ! wp_verify_nonce( $_POST['wpex_import_demo_nonce'], 'wpex_import_demo_nonce' ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			$this->init_demos_data();
			
			$this->content_importer = new WPEX_Content_Importer();
			$this->content_importer->set_demos_data( $this->demos );

			// get the slug of the demo
			$demo = $_POST['wpex_import_demo'];

			$result = $this->content_importer->process_widget_import( $demo );

			if ( is_wp_error( $result ) ) {
				echo json_encode( $result->errors );
			} else {
				echo 'successful import';
			}

			die();
		}

		/**
		 * Import sliders
		 *
		 * @since 1.1.0
		 */
		public function ajax_post_import_sliders() {
			if ( ! wp_verify_nonce( $_POST['wpex_import_demo_nonce'], 'wpex_import_demo_nonce' ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			$this->init_demos_data();

			$this->content_importer = new WPEX_Content_Importer();
			$this->content_importer->set_demos_data( $this->demos );

			// get the name of the demo
			$demo = $_POST['wpex_import_demo'];

			$result = $this->content_importer->process_sliders_import( $demo );

			if ( is_wp_error( $result ) ) {
				echo json_encode( $result->errors );
			} else {
				echo 'successful import';
			}

			die();
		}

		/**
		 * Called when all the selected content has been imported
		 *
		 * @since 1.1.0
		 */
		public function ajax_post_import_complete() {
			if ( ! wp_verify_nonce( $_POST['wpex_import_demo_nonce'], 'wpex_import_demo_nonce' ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			if ( $_POST['wpex_import_is_xml'] === 'true' ) {
				$this->init_demos_data();

				$this->content_importer = new WPEX_Content_Importer();
				$this->content_importer->set_demos_data( $this->demos );

				$demo = $_POST['wpex_import_demo'];

				$this->content_importer->set_menus( $demo );
				$this->content_importer->set_homepage( $demo );
				$this->content_importer->set_posts_page( $demo );
				$this->content_importer->set_shop_page( $demo );

				do_action( 'wpex_demo_importer_ajax_post_import_complete' );
				
			}

			die();
		}

	}

}
new WPEX_Demo_Importer();

<?php
if ( ! class_exists( 'WPEX_Plugin_Installer' ) ) {

	class WPEX_Plugin_Installer {

		/**
		 * Contains the data for all the possible plugins that might be used by
		 * the theme. The data consists of the plugin's name, slug, the source of the 
		 * installable files and the file path of the plugin inside the 'plugins' directory.
		 *
		 * @since 1.1.0
		 * 
		 * @var array
		 */
		private $plugins_data = array();

		/**
		 * Contains data for all the plugins that are installed, whether they are activated or not.
		 *
		 * @since 1.1.0
		 * 
		 * @var array
		 */
		private $installed_plugins = array();

		/**
		 * Start things up
		 */
		public function __construct() {

			// this file contains the necessary methods for checking if a plugin is activated or installed
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		/**
		 * Set the data for the required plugins.
		 *
		 * @since 1.1.0
		 * 
		 * @param array The array of plugin data.
		 */
		public function set_plugins_data( $data ) {
			$this->plugins_data = $data;

			// set the file path for each required plugin
			foreach( $this->plugins_data as $plugin_slug => $plugin_data ) {
				$this->set_plugin_file_path( $plugin_slug );
			}
		}

		/**
		 * Return the complete data for the plugins required by the theme.
		 *
		 * @since 1.1.0
		 * 
		 * @return array The array of plugin data.
		 */
		public function get_plugins_data() {
			return $this->plugins_data;
		}

		/**
		 * Set the file path of the plugin. This should be used after a plugin was installed.
		 *
		 * @since 1.1.0
		 * 
		 * @param  string $slug The slug of the plugin.
		 * @return bool         True if the file path was set or false if the plugin wasn't found.
		 */
		public function set_plugin_file_path( $slug ) {
			$installed_plugins = $this->get_installed_plugins( true );

			foreach( $installed_plugins as $file_path => $plugin ) {
				if ( strpos( $file_path, $slug ) !== false ) {
					$this->plugins_data[ $slug ][ 'file_path' ] = $file_path;
					return true;
				}
			}

			return false;
		}

		/**
		 * Get the file path of the plugin.
		 *
		 * @since 1.1.0
		 * 
		 * @param  string      $slug The slug of the plugin.
		 * @return string|bool       Returns the file path if the plugin was found or false if plugin wasn't found.
		 */
		public function get_plugin_file_path( $slug ) {
			$plugins_data = $this->get_plugins_data();

			if ( isset( $plugins_data[ $slug ][ 'file_path' ] ) !== false ) {
				return $plugins_data[ $slug ][ 'file_path' ];
			}

			return false;
		}

		/**
		 * Return the list of installed plugins.
		 *
		 * @since 1.1.0
		 * 
		 * @param  bool  $refresh Indicates if the array of installed plugins will be refreshed.
		 * @return array          The array of installed plugins.
		 */
		protected function get_installed_plugins( $refresh = false ) {
			if ( empty ( $this->installed_plugins ) || $refresh === true ) {
				$this->installed_plugins = get_plugins();
			}

			return $this->installed_plugins;
		}

		/**
		 * Check if the given plugin is installed.
		 *
		 * @since 1.1.0
		 *
		 * @param  string $slug The slug of the plugin.
		 * @return bool         True if the plugins is installed and false if it's not.
		 */
		public function is_plugin_installed( $slug ) {
			$plugins_data = $this->get_plugins_data();

			// If a plugin doesn't have the 'file_path' information it means that it wasn't
			// in the list of installed plugins.
			if ( isset( $plugins_data[ $slug ]['file_path'] ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Check if the given plugin is activated.
		 *
		 * @since 1.1.0
		 * 
		 * @param  string $slug The slug of the plugin.
		 * @return bool         True if the plugins is activated and false if it's not.
		 */
		public function is_plugin_activated( $slug ) {
			$plugins_data = $this->get_plugins_data();

			// If a plugin doesn't have the 'file_path' information it means that it's not even installed
			// so return early.
			if ( isset( $plugins_data[ $slug ]['file_path'] ) === false ) {
				return false;
			}

			// If it is installed, check if it's active.
			if ( is_plugin_active( $plugins_data[ $slug ]['file_path'] ) === false ) {
				return false;
			}

			return true;
		}

		/**
		 * Attempt to activate a plugin.
		 *
		 * @since 1.1.0
		 * 
		 * @param  string $file_path The file path of the plugin.
		 * @return bool              True if the plugin was activated succesfully and false if it wasn't.
		 */
		public function activate_plugin( $file_path ) {
			$result = activate_plugin( $file_path );

			if ( is_null( $result) ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Attempt to install a plugin.
		 *
		 * @since 1.1.0
		 * 
		 * @param  string $slug The slug of the plugin.
		 * @return bool         True if the plugin was installed succesfully and false if it wasn't.
		 */
		public function install_plugin( $slug ) {

			// Get the download link of the plugin files (it can be local link or a WP repo link)
			$source = $this->get_download_link( $slug );

			// If the download link was found, create a new Plugin_Upgrader instance
			// and install the plugin
			if ( $source !== false ) {
				if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
					require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
				}

				if ( ! class_exists( 'Automatic_Upgrader_Skin', false ) ) {
					require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skins.php';
				}

				$upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
				$result = $upgrader->install( $source );

				if ( $result === true ) {

					// Since it's a newly installed plugin, its file path is not yet set in the plugins data,
					// so we need to set it.
					$this->set_plugin_file_path( $slug );
					
					return true;
				}
			}

			return false;
		}

		/**
		 * Return the download link for the plugin.
		 *
		 * @since 1.1.0
		 * 
		 * @param  string      $slug The slug of the plugin.
		 * @return string|bool       The download link if it was found or false if the download link wasn't found.
		 */
		public function get_download_link( $slug ) {
			$plugins_data = $this->get_plugins_data();

			// If a source was specified in the plugin data, return that
			if ( isset( $plugins_data[ $slug ][ 'source' ] ) ) {
				return $plugins_data[ $slug ][ 'source' ];
			} else {

				// Else, it means that the plugin is hosted on the WP repo and we need to fetch the download link from there
				if ( ! function_exists( 'plugins_api' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
				}

				$result = plugins_api( 'plugin_information', array( 'slug' => $slug ) );

				if ( ! is_wp_error( $result ) ) {
					return $result->download_link;
				}
			}

			return false;
		}

	}
}
<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpex-selected-notice">

	<h3><?php esc_html_e( 'Demo Selected:', 'total' ); ?> <span><?php echo esc_html( $demo_data['name'] ); ?></span></h3>

	<p class="wpex-selected-notice-warning">
		<?php
		$plugin_link;
		if ( is_plugin_active( 'wordpress-database-reset/wp-reset.php' ) ) {
			$plugin_link = admin_url( 'tools.php?page=database-reset' );
		} else {
			$plugin_link = 'http://www.wpexplorer.com/reset-wordpress-website/';
		}
		echo sprintf( __( 'For your site to look exactly like this demo, we recommend uploading sample data on a clean installation to prevent conflicts with current content. You can use this plugin to reset your site if needed: <a href="%s" target="_blank">Wordpress Database Reset</a>. Otherwise, select only the options you require on the next screen.', 'total' ), $plugin_link ); ?>
	</p>
	
	<?php
	if ( 'base' == $demo ) {
		echo '<p class="wpex-pre-import-error">';
		echo '<strong>' . esc_html__( 'Important:', 'total' ) . '</strong>';
		echo ' '. esc_html__( 'The Base demo is extremely large. We recommend importing the "Base Lite" demo instead which is a slimmed down version of Base with all the core pages, sliders, widgets, menus and media to get you started.', 'total' );
		echo '</p>';
	} ?>

	<?php

	// Get the data of all the plugins that might be required by the theme
	$plugins_data = $this->plugin_installer->get_plugins_data();

	// Contains the HTML output for the plugins that need to be installed or activated
	$plugins_output = '';

	// If the current demo requires some plugins
	if ( isset( $demo_data['plugins'] ) ) {

		// Iterate through the list of plugin data and display those plugins that are required
		foreach ( $plugins_data as $plugin_data ) {
			if ( in_array( $plugin_data['name'], $demo_data['plugins'] ) ) {
				$plugin_slug = $plugin_data['slug'];
				$user_action_url = '';
				$user_action_link = '';

				// If the plugin is not installed/activated provide the possibility to install/activate it
				if ( $this->plugin_installer->is_plugin_installed( $plugin_slug ) === false ) {
					
					$user_action_url = admin_url( 'update.php' ) . '?action=install-plugin&plugin=' . $plugin_slug . '&_wpnonce=' . wp_create_nonce( 'install-plugin_' . $plugin_slug );
					$user_action_link = '<a href="' . esc_url( $user_action_url ) . '" class="install-plugin">' . $plugin_data['name'] . '</a>';
				
				} else if ( $this->plugin_installer->is_plugin_activated( $plugin_slug ) === false ) {
					
					$user_action_url = admin_url( 'plugins.php' ) . '?action=activate&plugin=' . $plugin_data['file_path'] . '&_wpnonce=' . wp_create_nonce( 'activate-plugin_' . $plugin_data['file_path'] );
					$user_action_link = '<a href="' . esc_url( $user_action_url ) . '" class="activate-plugin">' . $plugin_data['name'] . '</a>';
				
				}

				if ( $user_action_link !== '' ) {
					$plugins_output .= '<tr class="wpex-required-plugin"><td class="wpex-plugin-name">' . $user_action_link . '</td><td class="wpex-plugin-action-result"></td></tr>';
				}

			}
		}

		if ( $plugins_output !== '' ) {
			echo '<p class="wpex-required-plugins-notice">' . __( 'This demo requires the plugins listed below. Please click on each plugin to install/activate it automatically.', 'total' ) . '</p>';
			echo '<table class="wpex-required-plugins"><tbody>' . $plugins_output . '</tbody></table>';
		}
	}
	?>

	<div class="wpex-popup-buttons wpex-clr">

		<a href="#" class="button-secondary wpex-popup-selected-next<?php echo $plugins_output !== '' ? ' disabled' : ''; ?>"><?php esc_html_e( 'Next', 'total' ); ?></a>

		<a href="#" class="button-secondary wpex-popup-selected-close"><?php esc_html_e( 'Close', 'total' ); ?></a>

	</div>

</div>


<form method="post" id="wpex-demo-import-form" class="wpex-selected-content-to-import">

	<input id="wpex_import_demo" type="hidden" name="wpex_import_demo" value="<?php echo esc_attr( $demo ); ?>" />

	<div class="wpex-demo-import-form-types wpex-clr">

		<h3><?php esc_html_e( 'Please select what content you want to import:', 'total' ); ?></h3>
		
		<ul>
			<li>
				<label for="wpex_import_xml">
					<input id="wpex_import_xml" type="checkbox" name="wpex_import_xml" checked="checked" />
					<strong><?php esc_html_e( 'Import XML Data', 'total' ); ?></strong> (<?php esc_html_e( 'pages, posts, meta data, terms, menus, etc', 'total' ); ?>)
				</label>
			</li>

			<li>
				<label for="wpex_import_xml_attachments">
					<input id="wpex_import_xml_attachments" type="checkbox" name="wpex_import_xml_attachments" checked="checked" />
					<strong><?php esc_html_e( 'Import Images', 'total' ); ?></strong>
				</label>
			</li>

			<li>
				<label for="wpex_import_mods">
					<input id="wpex_import_mods" type="checkbox" name="wpex_import_mods" checked="checked" />
					<strong><?php esc_html_e( 'Import Theme Customizer Settings', 'total' ); ?></strong> (<?php esc_html_e( 'Will reset your current settings', 'total' ); ?>)
				</label>
			</li>

			<li>
				<label for="wpex_import_widgets">
					<input id="wpex_import_widgets" type="checkbox" name="wpex_import_widgets" checked="checked" />
					<strong><?php esc_html_e( 'Import Widgets', 'total' ); ?></strong> (<?php esc_html_e( 'Imports new widgets, will not reset current widgets', 'total' ); ?>)
				</label>
			</li>

			<?php
			// Sliders
			if ( in_array( 'Slider Revolution', $demo_data['plugins'] ) || in_array( 'Revolution Slider', $demo_data['plugins'] ) ) :

				// Make sure zips can be uploaded
				$mimes              = get_allowed_mime_types();
				$allows_zip_uploads = ( is_array( $mimes ) && array_key_exists( 'zip', $mimes ) ) ?  true : false; ?>

				<li>
					<label for="wpex_import_sliders">
						<input id="wpex_import_sliders" type="checkbox" name="wpex_import_sliders" <?php checked( $allows_zip_uploads, true ); ?> <?php if ( ! $allows_zip_uploads ) echo ' disabled="disabled"'; ?> />
						<strong><?php esc_html_e( 'Import Sliders', 'total' ); ?></strong><?php if ( ! $allows_zip_uploads ) { echo ' - <span class="wpex-warning">'. esc_html__( 'You must first enable zip uploads for your WordPress install', 'total' ) .'</span>'; } ?>
					</label>
				</li>

			<?php endif; ?>

		</ul>

	</div>
	
	<div class="wpex-popup-buttons wpex-clr">
		<?php wp_nonce_field( 'wpex_import_demo_nonce', 'wpex_import_demo_nonce' ); ?>
		<input type="submit" name="submit" class="button button-primary wpex-submit-form" value="<?php esc_html_e( 'Confirm Import', 'total' ); ?>"  />
		<a href="#" class="button-secondary wpex-popup-selected-close"><?php esc_html_e( 'Close', 'total' ); ?></a>
	</div>

</form>

<div class="wpex-preloader">
	<h3><?php esc_html_e( 'The import process could take some time, so please be patient.', 'total' ); ?></h3>
	<div class="wpex-import-status"></div>
</div>

<div class="wpex-import-complete">
	<p class="wpex-import-complete-header"><?php esc_html_e( 'Import completed', 'total' ); ?></p>
	<p><?php echo sprintf( __( 'See the results at <a href="%s" target="_blank">%s</a>.', 'total' ), get_home_url(), get_bloginfo( 'name' ) ); ?></p>
</div>
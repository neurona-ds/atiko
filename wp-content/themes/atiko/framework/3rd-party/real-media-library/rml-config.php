<?php
/**
 * Real Media Library Configuration Class
 *
 * @package Total WordPress Theme
 * @subpackage 3rd Party
 * @version 4.2.1
 */

if ( ! class_exists( 'WPEX_Real_Media_Library_Config' ) ) {

	class WPEX_Real_Media_Library_Config {
		private static $vc_supported_modules = array();

		/**
		 * Start things up
		 *
		 * @version 4.0
		 */
		public function __construct() {

			if ( WPEX_VC_ACTIVE ) {

				self::$vc_supported_modules = array(
					'vcex_image_grid',
					'vcex_image_carousel',
					'vcex_image_flexslider',
					'vcex_image_galleryslider',
				);

				add_action( 'init', array( 'WPEX_Real_Media_Library_Config', 'add_vc_params' ) );

				if ( is_admin() ) {
					foreach ( self::$vc_supported_modules as $module ) {
						add_filter( 'vc_autocomplete_'. $module .'_rml_folder_callback', 'wpex_vc_rml_suggest_folders', 10, 1 );
						add_filter( 'vc_autocomplete_'. $module .'_rml_folder_render', 'wpex_vc_rml_render_folders', 10, 1 );
					}
				}

			}

		}

		/**
		 * Add new parameters to VC modules
		 *
		 * @version 4.0
		 */
		public static function add_vc_params() {

			$settings = array(
				'type' => 'autocomplete',
				'heading' => __( 'Real Media Library Folder', 'total' ),
				'param_name' => 'rml_folder',
				'group' => __( 'Gallery', 'total' ),
				'settings' => array(
					'multiple' => false,
					'min_length' => 1,
					'groups' => true,
					'unique_values' => true,
					'display_inline' => true,
					'delay' => 0,
					'auto_focus' => true,
				),
			);

			foreach ( self::$vc_supported_modules as $module ) {
				vc_add_param( $module, $settings );
				if ( 'vcex_image_grid' != $module ) {
					vc_add_param( $module, array(
						'type' => 'textfield',
						'heading' => __( 'Count', 'total' ),
						'param_name' => 'posts_per_page',
						'value' => '12',
						'description' => __( 'How many images to grab from this folder. Enter -1 to display all of them.', 'total' ),
					) );
				}
			}

		}

	}

}
new WPEX_Real_Media_Library_Config();


/**** HELPER FUNCTIONS ****/

/**
 * Suggest folders for VC auto complete
 *
 * @since 4.0
 */
function wpex_rml_folders_array( $include_empty = true, $rec_childs = null, &$folders = array() ) {
	if ( $include_empty ) {
		$folders[] = __( 'Select', 'total' );
	}
	$get_folders = is_array( $rec_childs ) ? $rec_childs : wp_rml_root_childs();
	if ( $get_folders ) {
		if ( version_compare( RML_VERSION, '2.8' ) <= 0 ) {
			foreach ( $get_folders as $parent_folder ) {
				$folders[$parent_folder->id] = $parent_folder->name;
				if ( ! empty( $parent_folder->children ) ) {
					wpex_rml_folders_array( false, $parent_folder->children, $folders );
				}
			}
		} else {
			foreach ( $get_folders as $parent_folder ) {
				$folders[$parent_folder->getId()] = $parent_folder->getName();
				$childs = $parent_folder->getChildrens();
				if ( ! empty( $childs ) ) {
					wpex_rml_folders_array( false, $childs, $folders );
				}
			}
		}
	}
	return $folders;
}

/**
 * Suggest folders for VC auto complete
 *
 * @since 4.0
 */
function wpex_vc_rml_suggest_folders() {
	$folders = array();
	$get_folders = wpex_rml_folders_array();
	if ( $get_folders ) {
		foreach ( $get_folders as $id => $name ) {
			$folders[] = array(
				'label' => $name,
				'value' => $id,
			);
		}
	}
	return $folders;
}

/**
 * Renders folders for vc autocomplete
 *
 * @since 4.0
 */
function wpex_vc_rml_render_folders( $data ) {
	$value = $data['value'];
	$get_folder = wp_rml_get_by_id( $value );
	if ( is_object( $get_folder ) ) {
		if ( version_compare( RML_VERSION, '2.8' ) > 0 ) {
			return array(
				'label' => $get_folder->getName(),
				'value' => $value,
			);
		} else {
			return array(
				'label' => $get_folder->name,
				'value' => $value,
			);
		}
	}
	return $data;
}
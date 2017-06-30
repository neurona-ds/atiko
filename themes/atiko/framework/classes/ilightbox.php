<?php
/**
 * iLightbox class
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'WPEX_iLightbox' ) ) {

	class WPEX_iLightbox {

		/**
		 * Main constructor
		 *
		 * @since 2.1.0
		 */
		public function __construct() {

			// Define lightbox stylesheets
			add_action( 'wp_enqueue_scripts', array( 'WPEX_iLightbox', 'register_style_sheets' ), 20 );

			// Load scripts
			if ( wpex_get_mod( 'lightbox_auto' ) || apply_filters( 'wpex_load_ilightbox_globally', false ) ) {
				add_action( 'wp_enqueue_scripts', array( 'WPEX_iLightbox', 'load_stylesheet_always' ), 40 );
			}

			// Add to localize array
			add_filter( 'wpex_localize_array', array( 'WPEX_iLightbox', 'localize' ) );

			// Add customizer settings
			add_filter( 'wpex_customizer_sections', array( 'WPEX_iLightbox', 'customizer_settings' ) );

		}

		/**
		 * Localize scripts
		 *
		 * @since 2.1.0
		 */
		public static function active_skin() {
			$skin = wpex_get_mod( 'lightbox_skin' );
			$skin = $skin ? $skin : 'minimal';
			return apply_filters( 'wpex_lightbox_skin', $skin );
		}

		/**
		 * Localize scripts
		 *
		 * @since 2.1.0
		 */
		public static function localize( $array ) {

			// Define lightbox type
			$array['lightboxType'] = 'iLightbox';

			// lightbox animations
			if ( wpex_get_mod( 'ilightbox_animated_effects', true ) ) {
				$effects = array(
					'reposition'      => true,
                    'repositionSpeed' => 200,
                    'switchSpeed'     => 300,
					'loadedFadeSpeed' => 50,
					'fadeSpeed'       => 500,
				);
				$display_speed = 200;
			} else {
				$effects = array(
					'reposition'      => false,
                    'repositionSpeed' => 0,
                    'switchSpeed'     => 0,
					'loadedFadeSpeed' => 0,
					'fadeSpeed'       => 0,
				);
				$display_speed = 0;
			}

			// Get maxwidth
			$maxwidth = intval( wpex_get_mod( 'lightbox_width' ) );

			// Add lightbox to localize array and apply filters for easier tweaking.
			$array['iLightbox'] = apply_filters( 'wpex_ilightbox_settings', array(
				'auto'        => wpex_get_mod( 'lightbox_auto', false ),
				'skin'        => wpex_ilightbox_skin(),
				'path'        => 'horizontal',
				'infinite'    => false,
				'maxScale'    => 1,
				'minScale'    => 0,
				'width'       => $maxwidth ? $maxwidth : 1400,
				'height'      => '',
				'videoWidth'  => 1280, // 720p
				'videoHeight' => 720,  // 720p
				'controls' => array(
					'arrows'     => wpex_get_mod( 'lightbox_arrows', true ),
					'thumbnail'  => wpex_get_mod( 'lightbox_thumbnails', true ),
					'fullscreen' => wpex_get_mod( 'lightbox_fullscreen', true ),
					'mousewheel' => wpex_get_mod( 'lightbox_mousewheel', false ),
					'slideshow'  => true,
				),
				'slideshow' => array(
					'pauseTime'    => 3000,
					'startPaused'  => true,
				),
				'effects' => $effects,
				'show' => array(
					'title' => true,
					'speed' => $display_speed,
				),
				'hide' => array(
					'speed' => $display_speed,
				),
				'overlay' => array(
					'blur'    => true,
					'opacity' => '0.9',
				),
				'social' => array(
					'start'   => true,
					'show'    => 'mouseenter',
					'hide'    => 'mouseleave',
					'buttons' => false,
				),
				'text' => array(
					'close'           => 'Press Esc to close',
					'enterFullscreen' => 'Enter Fullscreen (Shift+Enter)',
					'exitFullscreen'  => 'Exit Fullscreen (Shift+Enter)',
					'slideShow'       => 'Slideshow',
					'next'            => 'Next',
					'previous'        => 'Previous',
				),
				'thumbnails' => array(
					'maxWidth'  => 120,
					'maxHeight' => 80,
				),
			) );
			return $array;
		}

		/**
		 * Holds an array of lightbox skins
		 *
		 * @since 2.1.0
		 */
		public static function skins() {
			return apply_filters( 'wpex_ilightbox_skins', array(
				'minimal'     => __( 'Minimal', 'total' ),
				'white'       => __( 'White', 'total' ),
				'dark'        => __( 'Dark', 'total' ),
				'light'       => __( 'Light', 'total' ),
				'mac'         => __( 'Mac', 'total' ),
				'metro-black' => __( 'Metro Black', 'total' ),
				'metro-white' => __( 'Metro White', 'total' ),
				'parade'      => __( 'Parade', 'total' ),
				'smooth'      => __( 'Smooth', 'total' ),
			) );
		}

		/**
		 * Returns correct skin stylesheet
		 *
		 * @since 2.1.0
		 */
		public static function skin_style( $skin = null ) {

			// Sanitize skin
			$skin = $skin ? $skin : wpex_ilightbox_skin();

			// Loop through skins
			$stylesheet = wpex_asset_url( 'lib/ilightbox/'. $skin .'/ilightbox-'. $skin .'-skin.css' );

			// Apply filters and return
			return apply_filters( 'wpex_ilightbox_stylesheet', $stylesheet );

		}

		/**
		 * Enqueues iLightbox skin stylesheet
		 *
		 * @since 2.1.0
		 */
		public static function enqueue_style( $skin = null ) {

			// Get default skin if custom skin not defined
			$skin = ( $skin && 'default' != $skin ) ? $skin : wpex_ilightbox_skin();

			// Enqueue stylesheet
			wp_enqueue_style( 'wpex-ilightbox-'. $skin );

		}

		/**
		 * Registers all stylesheets for quick usage and enqueues default skin for the whole site
		 *
		 * @since 2.1.0
		 */
		public static function register_style_sheets() {
			foreach( self::skins() as $key => $val ) {
				wp_register_style( 'wpex-ilightbox-'. $key, self::skin_style( $key ), false, WPEX_THEME_VERSION );
			}
		}

		/**
		 * Will load the lightbox main stylesheet everywhere
		 *
		 * @since 2.1.0
		 */
		public static function load_stylesheet_always() {
			wp_enqueue_style( 'wpex-ilightbox-'. self::active_skin(), false, WPEX_THEME_VERSION );
		}

		/**
		 * Loads the stylesheet
		 *
		 * @since 2.1.0
		 */
		public static function load_css() {
			self::enqueue_style();
		}

		/**
		 * Adds lightbox customizer settings
		 *
		 * @return array
		 *
		 * @since 2.1.0
		 */
		public static function customizer_settings( $sections ) {
			$skins = array( '' => __( 'Default', 'total' ) );
			$skins = $skins + wpex_ilightbox_skins();
			$sections['wpex_lightbox'] = array(
				'title' => __( 'Lightbox', 'total' ),
				'panel' => 'wpex_general',
				'settings' => array(
					array(
						'id' => 'lightbox_skin',
						'transport' => 'postMessage',
						'default' => '',
						'control' => array(
							'label' => __( 'Skin', 'total' ),
							'type' => 'select',
							'choices' => $skins,
							'desc'  => __( 'You must save your options and refresh your live site to preview changes to this setting.', 'total' ),
						),
					),
					array(
						'id' => 'lightbox_width',
						'default' => 1500,
						'control' => array(
							'label' => __( 'Image Max Width', 'total' ),
							'type' => 'number',
						),
					),
					array(
						'id' => 'lightbox_auto',
						'control' => array(
							'label' => __( 'Auto Lightbox', 'total' ),
							'type' => 'checkbox',
							'desc' => __( 'Automatically add Lightbox to images inserted into the post editor.', 'total' ),
						),
					),
					array(
						'id' => 'ilightbox_animated_effects',
						'default' => true,
						'control' => array(
							'label' => __( 'Animated Effects', 'total' ),
							'type' => 'checkbox',
						),
					),
					array(
						'id' => 'lightbox_thumbnails',
						'default' => true,
						'control' => array(
							'label' => __( 'Gallery Thumbnails', 'total' ),
							'type' => 'checkbox',
						),
					),
					array(
						'id' => 'lightbox_arrows',
						'default' => true,
						'control' => array(
							'label' => __( 'Gallery Arrows', 'total' ),
							'type' => 'checkbox',
						),
					),
					array(
						'id' => 'lightbox_mousewheel',
						'default' => false,
						'control' => array(
							'label' => __( 'Gallery Mousewheel Scroll', 'total' ),
							'type' => 'checkbox',
						),
					),
					array(
						'id' => 'lightbox_titles',
						'default' => true,
						'control' => array(
							'label' => __( 'Titles', 'total' ),
							'type' => 'checkbox',
						),
					),
					array(
						'id' => 'lightbox_fullscreen',
						'default' => true,
						'control' => array(
							'label' => __( 'Fullscreen Button', 'total' ),
							'type' => 'checkbox',
						),
					),
				),
			);
			return $sections;
		}

	}

}
new WPEX_iLightbox();


/* Helper functions
-------------------------------------------------------------------------------*/

/**
 * Returns array of ilightbox Skins
 *
 * @since 2.0.0
 */
function wpex_ilightbox_skins() {
	return WPEX_iLightbox::skins();
}

/**
 * Returns lightbox skin
 *
 * @since 1.3.3
 */
function wpex_ilightbox_skin() {
	return WPEX_iLightbox::active_skin();
}

/**
 * Enqueues lightbox stylesheet
 *
 * @since 1.3.3
 */
function wpex_enqueue_ilightbox_skin( $skin = null ) {
	return WPEX_iLightbox::enqueue_style( $skin );
}

// Deprecated functions
function wpex_ilightbox_stylesheet( $skin = null ) {
	return; // This function is no longer needed and shouldn't be used
}
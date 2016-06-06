<?php
/**
 * Plugin Name:     Cooked Custom Labels
 * Plugin URI:      https://wordpress.org/plugins/cooked-custom-labels
 * Description:     Easily customize the labels used throughout the Cooked for WP recipe plugin.
 * Version:         1.0.0
 * Author:          Daniel Iser
 * Author URI:      https://danieliser.com
 * Text Domain:     cooked-custom-labels
 *
 * @author          Daniel Iser
 * @copyright       Copyright (c) 2015
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Cooked_Custom_Labels' ) ) {

	/**
	 * Main Cooked_Custom_Labels class
	 *
	 * @since       1.0.0
	 */
	class Cooked_Custom_Labels {

		/**
		 * @var         Cooked_Custom_Labels $instance The one true Cooked_Custom_Labels
		 * @since       1.0.0
		 */
		private static $instance;


		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      object self::$instance The one true Cooked_Custom_Labels
		 */
		public static function instance() {
			if ( ! self::$instance ) {
				self::$instance = new Cooked_Custom_Labels();
				self::$instance->setup_constants();
				self::$instance->includes();
				self::$instance->load_textdomain();
				self::$instance->hooks();
			}

			return self::$instance;
		}


		/**
		 * Setup plugin constants
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function setup_constants() {
			// Plugin version
			define( 'CKD_CUSTOM_LABELS_VER', '1.0.0' );

			// Plugin path
			define( 'CKD_CUSTOM_LABELS_DIR', plugin_dir_path( __FILE__ ) );

			// Plugin URL
			define( 'CKD_CUSTOM_LABELS_URL', plugin_dir_url( __FILE__ ) );
		}


		/**
		 * Include necessary files
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function includes() {
			// Include scripts
			require_once CKD_CUSTOM_LABELS_DIR . 'includes/filters.php';

		}


		/**
		 * Run action and filter hooks
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 *
		 */
		private function hooks() {
			// Register settings
			add_filter( 'cooked_settings_addons', array( $this, 'settings' ), 1 );
		}


		/**
		 * Internationalization
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 */
		public function load_textdomain() {
			// Set filter for language directory
			$lang_dir = CKD_CUSTOM_LABELS_DIR . '/languages/';
			$lang_dir = apply_filters( 'cooked_custom_labels_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'cooked-custom-labels' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'cooked-custom-labels', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/cooked-custom-labels/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/cooked-custom-labels/ folder
				load_textdomain( 'cooked-custom-labels', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/cooked-custom-labels/languages/ folder
				load_textdomain( 'cooked-custom-labels', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'cooked-custom-labels', false, $lang_dir );
			}
		}


		/**
		 * Add settings
		 *
		 * @access      public
		 * @since       1.0.0
		 *
		 * @param       array $settings The existing Cooked settings array
		 *
		 * @return      array The modified Cooked settings array
		 */
		public function settings( $settings ) {
			$new_settings = array(
				array(
					'id'   => 'cooked_custom_labels_settings',
					'name' => '<strong>' . __( 'Custom Labels', 'cooked-custom-labels' ) . '</strong>',
					'desc' => __( 'Configure Custom Labels', 'cooked-custom-labels' ),
					'type' => 'header',
				)
			);

			return array_merge( $settings, $new_settings );
		}
	}
} // End if class_exists check


/**
 * The main function responsible for returning the one true Cooked_Custom_Labels
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      Cooked_Custom_Labels The one true Cooked_Custom_Labels
 */
function cooked_custom_labels_load() {
	if ( ! class_exists( 'Cooked' ) ) {
		if ( ! class_exists( 'Cooked_Addon_Activation' ) ) {
			require_once 'includes/class.addon-activation.php';
		}

		$activation = new Cooked_Addon_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
		$activation->run();
	} else {
		Cooked_Custom_Labels::instance();
	}
}

add_action( 'plugins_loaded', 'cooked_custom_labels_load' );

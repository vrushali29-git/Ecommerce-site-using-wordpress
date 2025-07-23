<?php

/**
 * Backend Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Kata_Plus_Backend' ) ) {
	class Kata_Plus_Backend {

		/**
		 * The directory of the file.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $dir;

		/**
		 * The url of the file.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $url;

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Backend
		 */
		public static $instance;

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since   1.0.0
		 * @return  object
		 */
		public static function get_instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->definitions();
			$this->actions();
		}

		/**
		 * Global definitions.
		 *
		 * @since   1.0.0
		 */
		public function definitions() {
			self::$dir = Kata_Plus::$dir . 'includes/backend/';
			self::$url = Kata_Plus::$url . 'includes/backend/';
		}

		/**
		 * Add actions.
		 *
		 * @since   1.0.0
		 */
		public function actions() {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'admin_init', array( $this, 'enable_mega_menu' ) );
			add_action( 'kata_plus_init', array( $this, 'kata_plus_pro_activation_check' ) );
		}

		/**
		 * Enqueue Admin Scripts
		 *
		 * @since     1.0.0
		 */
		public function admin_enqueue_scripts() {
			wp_enqueue_script( 'kata-plus-sweet-alert', Kata_Plus::$assets . 'js/libraries/sweetalert.js', array( 'jquery' ), Kata_plus::$version, true );
			wp_enqueue_script( 'kata-plus-admin-js', Kata_Plus::$assets . 'js/backend/admin.js', array( 'jquery' ), Kata_plus::$version, true );
		}

		/**
		 * Reactive Kata Plus Pro when it doesn't find kata_plus_init hook
		 *
		 * @since     1.5.1
		 */
		public function kata_plus_pro_activation_check() {
			if ( ! class_exists( 'Kata_Plus_Pro' ) && is_plugin_active( 'kata-plus-pro/kata-plus-pro.php' ) ) {
				deactivate_plugins( 'kata-plus-pro/kata-plus-pro.php' );
				activate_plugin( 'kata-plus-pro/kata-plus-pro.php' );
			}
		}

		/**
		 * Enable Mega menu
		 *
		 * @since     1.3.0
		 */
		public function enable_mega_menu() {
			global $pagenow;

			if ( current_user_can( 'edit_theme_options' ) && $pagenow !== 'nav-menus.php' ) {
				return false;
			}

			$user            = get_current_user_id();
			$user_hidden_box = get_user_meta( $user, 'metaboxhidden_nav-menus', true );

			if ( is_array( $user_hidden_box ) ) {

				if ( in_array( 'add-post-type-kata_mega_menu', $user_hidden_box ) ) {

					foreach ( $user_hidden_box as $key => $value ) {
						if ( $value === 'add-post-type-kata_mega_menu' ) {
							unset( $user_hidden_box[ $key ] );
						}
					}
					update_user_meta( $user, 'metaboxhidden_nav-menus', $user_hidden_box );
				}
			}
		}
	}
	Kata_Plus_Backend::get_instance();
}

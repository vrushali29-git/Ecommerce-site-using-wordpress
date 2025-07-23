<?php

/**
 * Compatibility Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Compatibility' ) ) {
	class Kata_Plus_Compatibility {
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
		 * @var     Kata_Plus_Compatibility
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
			$this->dependencies();
			$this->actions();
		}

		/**
		 * Global definitions.
		 *
		 * @since   1.0.0
		 */
		public function definitions() {
			self::$dir = Kata_Plus::$dir . 'includes/compatiblity/';
			self::$url = Kata_Plus::$url . 'includes/compatiblity/';
		}

		/**
		 * Global definitions.
		 *
		 * @since   1.0.0
		 */
		public function actions() {
			if ( class_exists( 'Crypto_Currency_Price_Widget' ) ) {
				remove_action( 'admin_init', array( Crypto_Currency_Price_Widget::get_instance(), 'ccpw_do_activation_redirect' ) );
			}
		}

		/**
		 * Load dependencies.
		 *
		 * @since   1.0.0
		 */
		public function dependencies() {

			include_once ABSPATH . 'wp-admin/includes/plugin.php';

			/**
			 * LearnPress
			 */
			if ( is_plugin_active( 'learnpress/learnpress.php' ) ) {
				Kata_Plus_Autoloader::load( self::$dir, 'class-kata-plus-learnpress-compatibility' );
			}

			/**
			 * PolyLang
			 */
			if ( is_plugin_active( 'polylang/polylang.php' ) ) {
				Kata_Plus_Autoloader::load( self::$dir, 'class-kata-plus-pll-compatibility' );
			}

			/**
			 * WPML
			 */
			if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
				Kata_Plus_Autoloader::load( self::$dir, 'class-kata-plus-wpml-compatibility' );
			}

			/**
			 * ShopPress
			 */
			if ( is_plugin_active( 'shop-press/shop-press.php' ) ) {
				Kata_Plus_Autoloader::load( self::$dir, 'class-kata-plus-shoppress-compatibility' );
			}

			if ( is_plugin_active( 'mailpoet/mailpoet.php' ) && is_admin() ) {
				add_filter(
					'mailpoet_conflict_resolver_whitelist_style',
					function ( $whitelistedStyles ) {
						$whitelistedStyles[] = 'kata-plus';
						return $whitelistedStyles;
					}
				);

				add_filter(
					'mailpoet_conflict_resolver_whitelist_script',
					function ( $whitelistedScripts ) {
						$whitelistedScripts[] = 'kata-plus';
						return $whitelistedScripts;
					}
				);
			}
		}
	}
	Kata_Plus_Compatibility::get_instance();
}

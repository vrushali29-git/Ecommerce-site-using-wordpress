<?php
/**
 * Theme Options Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Theme_Options' ) ) {
	class Kata_Plus_Theme_Options {
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
		 * This is kata option name where all the Redux data is stored.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $opt_name;

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Theme_Options
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
			$this->config();
			$this->actions();
		}

		/**
		 * Global definitions.
		 *
		 * @since   1.0.0
		 */
		public function definitions() {
			self::$dir      = Kata_Plus::$dir . 'includes/theme-options/';
			self::$url      = Kata_Plus::$url . 'includes/theme-options/';
			self::$opt_name = 'Kata_theme_options';
		}

		/**
		 * Load dependencies.
		 *
		 * @since   1.0.0
		 */
		public function dependencies() {
			add_action( 'after_setup_theme', function() {
				// Options
				Kata_Plus_Autoloader::load(
					self::$dir . 'options',
					array(
						'blog',
						'styling',
						'container',
						'footer',
						'header',
						'page',
						'site-identity',
						'add-google-fonts',
						'woocommerce',
					),
					'',
					'options'
				);

				do_action( 'kata_plus_after_customizer_register' );

				// Functions
				Kata_Plus_Autoloader::load( self::$dir . 'functions', 'theme-options-functions' );

				/**
				 * customizer-export-import
				 */
				if ( ! class_exists( 'CEI_Core' ) ) {
					Kata_Plus_Autoloader::load( self::$dir . 'plugins/customizer-export-import', 'customizer-export-import' );
				}
				/**
				 * wps-menu-exporter
				 */
				if ( ! function_exists( 'plugins_loaded_wps_menu_exporter_plugin' ) ) {
					Kata_Plus_Autoloader::load( self::$dir . 'plugins/wps-menu-exporter', 'wps-menu-exporter' );
				}
			} );
		}

		/**
		 * Configuration redux framework.
		 *
		 * @since   1.0.0
		 */
		public function config() {
			Kirki::add_config(
				self::$opt_name,
				array(
					'capability'  => Kata_Plus_Helpers::capability(),
					'option_type' => 'theme_mod',
				)
			);
		}

		/**
		 * Add actions.
		 *
		 * @since   1.0.0
		 */
		public function actions() {
			add_action( 'customize_register', array( $this, 'change_wp_customizer' ), 100 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'current_screen', array( $this, 'current_screen' ) );
			add_action( 'kata_start_theme_options', array( $this, 'deactive_class_kata_theme_options' ) );
			add_action( 'after_setup_theme', array( $this, 'remove_custom_logo' ), 11 );
		}


		/**
		 * Remove Custom logo.
		 *
		 * @since   1.0.0
		 */
		public function remove_custom_logo() {
			remove_theme_support( 'custom-logo' );
		}

		/**
		 * Add actions.
		 *
		 * @since   1.0.0
		 */
		public function enqueue_styles() {
			if ( 'customize' === self::current_screen() ) {
				wp_enqueue_style( 'kata-customizer', Kata_Plus::$assets . '/css/backend/customizer.css', array(), Kata_Plus::$version );
				$this->add_kata_swatches_style();
			}
		}

		/**
		 * Add kata swatches style.
		 *
		 * @since   1.3.1
		 */
		public function add_kata_swatches_style() {
			$colorbase       = get_theme_mod( 'kata-color-primary', '' );
			$secondary_color = get_theme_mod( 'kata-color-secondary', '' );

			if ( ! empty( $colorbase ) || ! empty( $secondary_color ) || ! empty( $tertiary_color ) ) {
				echo '<style>';
					echo ':root {';
				if ( ! empty( $colorbase ) ) {
					echo '--ct-color-primary: ' . esc_html( $colorbase ) . ' !important;';
				}
				if ( ! empty( $secondary_color ) ) {
					echo '--ct-color-button-primary: ' . esc_html( $secondary_color ) . ' !important;';
				}
					echo '}';
					echo '[data-color*="var(--ct-"] {font-size: 0 !important;}';
				echo '</style>';
			}
		}

		/**
		 * Current Screen
		 *
		 * @since   1.0.0
		 */
		public static function current_screen() {
			return get_current_screen()->base;
		}

		/**
		 * Change Customizer Default panels order
		 *
		 * @since   1.0.0
		 */
		public function change_wp_customizer( $wp_customize ) {
			$site_identitiy_section = $wp_customize->get_section( 'title_tagline' );
			if ( $site_identitiy_section ) {
				$site_identitiy_section->priority = 0;
			}

			$static_front_page_section = $wp_customize->get_section( 'static_front_page' );
			if ( $static_front_page_section ) {
				$static_front_page_section->priority = 2;
			}

			$nav_panel = $wp_customize->get_panel( 'nav_menus' );
			if ( $nav_panel ) {
				$nav_panel->priority = 7;
			}

			$widgets_panel = $wp_customize->get_panel( 'widgets' );
			if ( $widgets_panel ) {
				$widgets_panel->priority = 8;
			}

			add_action( 'wp_footer', array( $this, 'customizer_inline_style' ) );
		}
		public function customizer_inline_style() {
			echo '
			<div class="kata-customizer-preloader">
				<img src="' . esc_url( Kata_Plus::$assets ) . 'images/admin/kata-icon.svg" class="kata-customizer-preloader-icon">
			</div>
			<style>
				body.wp-customizer-unloading {
					opacity: 1 !important;
				}
				.kata-customizer-preloader {
					position: fixed;
					top: 0;
					left: 0;
					opacity: 0;
					width: 100%;
					height: 100%;
					background: rgba(255, 255, 255, 0.60);
					opacity: 0;
					z-index: -9999;
					transition: all .3s ease;
					-webkit-transition: all .3s ease;
				}
				@-webkit-keyframes sk-scaleout {
					0% { -webkit-transform: scale(0) }
					100% {
						-webkit-transform: scale(1.0);
						opacity: 0;
					}
				}
				@keyframes sk-scaleout {
					0% {
						-webkit-transform: scale(0);
						transform: scale(0);
					}
					100% {
						-webkit-transform: scale(1.0);
						transform: scale(1.0);
						opacity: 0;
					}
				}
				.wp-customizer-unloading .kata-customizer-preloader {
					opacity: 1;
					z-index: 9999;
				}
				.wp-customizer-unloading .kata-customizer-preloader img {
					position: fixed;
					top: calc( 50% - 50px );
					left: calc( 50% - 50px );
					height: 100px;
					width: 100px;
					transition: all .3s ease;
					-webkit-transition: all .3s ease;
					-webkit-animation: sk-scaleout 1.0s infinite ease-in-out;
					animation: sk-scaleout 1.0s infinite ease-in-out;
				}
			</style>';
		}
	}

	Kata_Plus_Theme_Options::get_instance();
}

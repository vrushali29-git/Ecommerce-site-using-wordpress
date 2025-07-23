<?php
/**
 * Plugin Name:     Kata Plus
 * Plugin URI:      https://wordpress.org/plugins/kata-plus/
 * Description:     Kata Plus is one an all in one addon for Elementor page builder that is fully compatible with Kata WordPress theme. Kata Plus is an all-in-one plugin that has a header, footer, and blog builder inside Styler (the new advanced tool for styling widgets) and comes with 18 practical widgets for creating different websites.
 * Version:         1.5.5
 * Author:          Climax Themes
 * Author URI:      https://climaxthemes.com/
 * Elementor tested up to: 3.29.2
 * Elementor Pro tested up to: 3.29.2
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     kata-plus
 * Domain Path:     /languages
 *
 * The plugin bootstrap file
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Elementor\Core\Files\CSS\Base;

if ( ! class_exists( 'Kata_Plus' ) ) {
	class Kata_Plus {
		/**
		 * Maintains the current version of the plugin.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $version;

		/**
		 * Maintains the slug of the plugin.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $slug;

		/**
		 * Maintains the url of the plugin.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $plugin_url;

		/**
		 * Maintains the name of the plugin.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $plugin_name;

		/**
		 * Maintains the wp-include dir.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $kata_plus_wp_include_dir;

		/**
		 * Maintains the wp-admin dir.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $kata_plus_wp_admin_dir;

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
		 * The base name of the Kata Plus.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $name;

		/**
		 * Fonts table name.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $fonts_table_name;

		/**
		 * Upload dir.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $upload_dir;

		/**
		 * Upload dir.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $upload_dir_url;

		/**
		 * The url of assets file.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $assets;

		/**
		 * The url of assets file.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $assets_dir;

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus
		 */
		public static $instance;

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since   1.0.0
		 * @return  object
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Define the core functionality of the plugin.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {

			register_activation_hook( __FILE__, array( $this, 'essentials' ) );

			if ( ! class_exists( 'Elementor\Plugin' ) ) {
				return;
			}

			if ( get_option( 'kata_is_active' ) ) {
				add_action( 'admin_notices', array( $this, 'kata_not_active' ) );
				return;
			}
			$this->definitions();
			$this->upload_dir();
			$this->dependencies();
			$this->actions();
			$this->kata_plus_init();
		}

		/**
		 * Kata theme is not activate notice.
		 *
		 * @since   1.0.0
		 */
		public function kata_not_active() {

			echo '<div id="message" class="updated error is-dismissible"><p>' . esc_html__( 'Kata theme is not activated. Kata Plus needs Kata theme to work', 'kata-plus' ) . '</p></div>';
		}

		/**
		 * Kata Plus Init.
		 *
		 * @since   1.0.0
		 */
		public function kata_plus_init() {
			add_action( 'after_setup_theme', function() {
				do_action( 'kata_plus_init' );
			}, 50 );
		}

		/**
		 * Global definitions.
		 *
		 * @since   1.0.0
		 */
		public function definitions() {

			self::$version                  = '1.5.5';
			self::$slug                     = 'kata-plus';
			self::$plugin_url               = 'https://wordpress.org/plugins/kata-plus/';
			self::$plugin_name              = 'Kata Plus';
			self::$dir                      = plugin_dir_path( __FILE__ );
			self::$kata_plus_wp_include_dir = ABSPATH . WPINC;
			self::$kata_plus_wp_admin_dir   = ABSPATH . 'wp-admin';
			self::$url                      = plugin_dir_url( __FILE__ );
			self::$name                     = plugin_basename( __FILE__ );
			self::$assets                   = self::$url . 'assets/src/';
			self::$assets_dir               = self::$dir . 'assets/src/';
			self::$fonts_table_name         = 'kata_plus_fonts_manager';
			self::$upload_dir               = wp_get_upload_dir()['basedir'] . '/kata';
			self::$upload_dir_url           = is_ssl() ? str_replace( 'http://', 'https://', wp_get_upload_dir()['baseurl'] . '/kata' ) : wp_get_upload_dir()['baseurl'] . '/kata';
		}

		/**
		 * Add actions.
		 *
		 * @since   1.0.0
		 */
		public function actions() {
			add_action( 'after_switch_theme', array( $this, 'sync_theme_mods' ), 20 );
			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ), 10 );
			add_action( 'admin_init', array( $this, 'update_permalink' ), 9 );
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
			add_action( 'wp_print_scripts', array( $this, 'localize_fonts_list' ), 1 );
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'localize_fonts_list' ), -10 );
		}

		/**
		 * Load dependencies.
		 *
		 * @since   1.0.0
		 */
		public function dependencies() {

			// AutoLoader.
			require_once self::$dir . 'includes/autoloader/autoloader.php';

			// Compatiblity.
			Kata_Plus_Autoloader::load( self::$dir . 'includes/compatiblity', 'class-kata-plus-compatibility' );

			// Helpers.
			Kata_Plus_Autoloader::load( self::$dir . 'includes/helpers', 'helpers' );

			// Vendors.
			Kata_Plus_Autoloader::load( self::$dir . 'includes/vendors', 'zilla-likes' );

			// Convertors.
			Kata_Plus_Autoloader::load( self::$dir . 'includes/helpers', 'class-demos-convertor' );
			Kata_Plus_Autoloader::load( self::$dir . 'includes/helpers', 'class-styler-elementor-convertor' );
			Kata_Plus_Autoloader::load( self::$dir . 'includes/helpers', 'class-styler-kirki-convertor' );
			Kata_Plus_Autoloader::load( self::$dir . 'includes/helpers', 'class-styler-elementor-convertor' );

			if ( ! class_exists( 'Kirki' ) ) {

				Kata_Plus_Autoloader::load( self::$dir . 'includes/vendors/kirki', 'kirki' );
			}

			// Styler.
			Kata_Plus_Autoloader::load( self::$dir . 'includes/styler', 'styler' );

			// Theme Options.
			Kata_Plus_Autoloader::load( self::$dir . 'includes/theme-options', 'theme-options' );

			/**
			 * Grid Post Type
			 */
			Kata_Plus_Autoloader::load( self::$dir . 'includes/post-types/grid', 'grid' );

			// Meta Box.
			Kata_Plus_Autoloader::load( self::$dir . 'includes/meta-box', 'meta-box' );

			// Backend.
			Kata_Plus_Autoloader::load( self::$dir . 'includes/backend', 'backend' );

			// Export
			Kata_Plus_Autoloader::load( self::$dir . 'includes/backend', 'export' );

			// Frontend.
			Kata_Plus_Autoloader::load( self::$dir . 'includes/frontend', 'frontend' );

			// Register widgets
			Kata_Plus_Autoloader::load( self::$dir . 'includes/frontend', 'register-assets' );

			// Elementor.
			if ( class_exists( '\Elementor\Plugin' ) ) {

				Kata_Plus_Autoloader::load( self::$dir . 'includes/elementor', 'elementor' );
			}

			// Breadcrumbs.
			Kata_Plus_Autoloader::load( self::$dir . 'includes/vendors', 'breadcrumbs' );

			// Woocommerce.
			Kata_Plus_Autoloader::load( self::$dir . 'includes/woocommerce', 'woocommerce' );

			// Widgets.
			Kata_Plus_Autoloader::load( self::$dir . 'includes/widgets', 'widgets' );

			// Menu.
			Kata_Plus_Autoloader::load( self::$dir . 'includes/menu', 'menu' );

			// Admin Panel.
			Kata_Plus_Autoloader::load( self::$dir . 'includes/admin-panel', 'class-update' );

			if ( is_admin() ) {
				Kata_Plus_Autoloader::load( self::$dir . 'includes/admin-panel', 'admin-panel' );
				Kata_Plus_Autoloader::load( self::$dir . 'includes/admin-panel', 'class-fast-mode' );
				Kata_Plus_Autoloader::load( self::$dir . 'includes/install-plugins', 'install-plugins' );

				Kata_Plus_Autoloader::load( self::$dir . 'includes/importer', 'importer' );

				Kata_Plus_Autoloader::load( self::$dir . 'includes/admin-panel', 'class-system-status' );

				Kata_Plus_Autoloader::load( self::$dir . 'includes/admin-panel', 'class-rollback' );

				Kata_Plus_Autoloader::load( self::$dir . 'includes/helpers', 'class-kata-plus-notices' );

				if ( file_exists( self::$dir . 'rtl-version.php' ) ) {
					Kata_Plus_Autoloader::load( self::$dir,  'rtl-version' );
				}
			}
		}

		/**
		 * Upload dir.
		 *
		 * @since   1.0.0
		 */
		public function upload_dir() {

			if ( ! file_exists( self::$upload_dir ) ) {
				mkdir( self::$upload_dir, 0777 );
			}

			if ( ! file_exists( self::$upload_dir . '/css' ) ) {
				mkdir( self::$upload_dir . '/css', 0777 );
			}

			if ( ! file_exists( self::$upload_dir . '/instagram' ) ) {
				mkdir( self::$upload_dir . '/instagram', 0777 );
			}

			if ( ! file_exists( self::$upload_dir . '/fonts' ) ) {
				mkdir( self::$upload_dir . '/fonts', 0777 );
			}

			if ( ! file_exists( self::$upload_dir . '/importer' ) ) {
				mkdir( self::$upload_dir . '/importer', 0777 );
			}

			if ( ! file_exists( self::$upload_dir . '/localize' ) ) {
				mkdir( self::$upload_dir . '/localize', 0777 );
			}

			if ( ! file_exists( self::$upload_dir . '/css/admin-custom.css' ) ) {

				global $wp_filesystem;
				if ( empty( $wp_filesystem ) ) {
					require_once ABSPATH . '/wp-admin/includes/file.php';
					WP_Filesystem();
				}
				$wp_filesystem->put_contents(
					self::$upload_dir . '/css/admin-custom.css',
					'',
					FS_CHMOD_FILE
				);
			}

			if ( ! file_exists( self::$upload_dir . '/css/dynamic-styles.css' ) ) {

				global $wp_filesystem;
				if ( empty( $wp_filesystem ) ) {
					require_once ABSPATH . '/wp-admin/includes/file.php';
					WP_Filesystem();
				}

				$wp_filesystem->put_contents(
					self::$upload_dir . '/css/dynamic-styles.css',
					'',
					FS_CHMOD_FILE
				);
			}
		}

		/**
		 * Load the textdomain of the plugin.
		 *
		 * @since   1.0.0
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'kata-plus', false, basename( __DIR__ ) . '/languages' );
		}

		/**
		 * Deactivator.
		 *
		 * @since   1.0.0
		 */
		public function sync_theme_mods() {
			$kata_options       = get_option( 'theme_mods_kata' );
			$kata_child_options = get_option( 'theme_mods_kata-child' );
			if ( $kata_options === $kata_child_options ) {
				return;
			}
			$current_theme = class_exists( 'Kata' ) ? Kata::$theme->get( 'TextDomain' ) : false;
			if ( ! $current_theme ) {
				return;
			}
			$previous_theme = 'kata-child' === $current_theme ? 'kata' : 'kata-child';
			if ( 'kata' === $previous_theme ) {
				$options = get_option( 'theme_mods_kata' );
				foreach ( $options as $name => $value ) {
					set_theme_mod( $name, $value );
				}
			}
			if ( 'kata-child' === $previous_theme ) {
				$options = get_option( 'theme_mods_kata-child' );
				if ( $options ) {
					foreach ( $options as $name => $value ) {
						set_theme_mod( $name, $value );
					}
				}
			}
		}

		/**
		 * Create Database Tables
		 *
		 * @since     1.0.0
		 */
		public static function update_permalink() {
			$kata_options = get_option( 'kata_options' );
			if ( ! isset( $kata_options['update_permalinks'] ) ) {
				global $wp_rewrite;
				$structure = get_option( 'permalink_structure' );
				$wp_rewrite->set_permalink_structure( $structure );
				flush_rewrite_rules();
				$kata_options['update_permalinks'] = true;
				update_option( 'kata_options', $kata_options );
			}
		}

		/**
		 * Create Database Tables
		 *
		 * @since     1.0.0
		 */
		public static function essentials() {

			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();


			$sql = 'CREATE TABLE ' . $wpdb->prefix . self::$fonts_table_name . " (
                ID int(9) NOT NULL AUTO_INCREMENT,
                name text NOT NULL,
                source varchar(200) NOT NULL,
                selectors text NOT NULL,
                subsets text NOT NULL,
                variants text NOT NULL,
                url text DEFAULT '' NOT NULL,
                place varchar(50) NOT NULL DEFAULT 'before_head_end',
                status varchar(50) NOT NULL DEFAULT 'publish',
                created_at int(12) NOT NULL,
                updated_at int(12) NOT NULL,
                PRIMARY KEY  (ID)
            ) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );

			// esential options.
			if ( ! get_option( 'kata_options' ) ) {
				update_option(
					'kata_options',
					array(
						'container'           => 1600,
						'kata_container'      => 1600,
						'elementor_container' => 1600,
						'first_time_import'   => true,
						'images'              => '',
						'license'             => array(
							'purchase_code'   => '',
							'product_version' => '',
							'id'              => '',
						),
						'fast-mode'           => array(
							'websitetype'     => '',
							'blogname'        => '',
							'blogdescription' => '',
							'siteurl'         => '',
							'admin_email'     => '',
							'timezone_string' => '',
						),
						'updates'             => array(
							'builders' => array(
								'primary' => 'updated',
							),
							'styler'   => array(
								'redirection' => false,
								'have_backup' => false,
								'updated'     => false,
							),
						),
					)
				);
			}

			global $wp_rewrite;
			$wp_rewrite->set_permalink_structure( '/%postname%/' );
		}

		/**
		 * Plugin row meta.
		 *
		 * Adds row meta links to the plugin list table
		 *
		 * Fired by `plugin_row_meta` filter.
		 *
		 * @since 1.3.0
		 * @access public
		 *
		 * @param array  $plugin_meta An array of the plugin's metadata, including
		 *                            the version, author, author URI, and plugin URI.
		 * @param string $plugin_file Path to the plugin file, relative to the plugins
		 *                            directory.
		 *
		 * @return array An array of plugin row meta links.
		 */
		public function plugin_row_meta( $plugin_meta, $plugin_file ) {
			if ( self::$name === $plugin_file ) {
				$row_meta = array(
					'docs'      => '<a href="https://climaxthemes.com/kata/documentation/" aria-label="' . esc_attr( esc_html__( 'View Kata Documentation', 'kata-plus' ) ) . '" target="_blank">' . esc_html__( 'Docs & FAQs', 'kata-plus' ) . '</a>',
					'changes'   => '<a href="https://climaxthemes.com/kata/documentation/changelog/" aria-label="' . esc_attr( esc_html__( 'View Kata Changelog', 'kata-plus' ) ) . '" target="_blank">' . esc_html__( 'Changelog', 'kata-plus' ) . '</a>',
					'pro'       => '<a href="https://my.climaxthemes.com/buy/" aria-label="' . esc_attr( esc_html__( 'View Kata Changelog', 'kata-plus' ) ) . '" target="_blank" style="color: red;">' . esc_html__( 'Go Pro', 'kata-plus' ) . '</a>',
					'shoppress' => '<a href="https://climaxthemes.com/shoppress/" aria-label="' . esc_attr( esc_html__( 'ShopPress', 'kata-plus' ) ) . '" target="_blank" style="color: #837AF5;">' . esc_html__( 'ShopPress', 'kata-plus' ) . '</a>',
				);

				$plugin_meta = array_merge( $plugin_meta, $row_meta );
			}

			return $plugin_meta;
		}

		/**
		 * localize Fonts List.
		 *
		 * @since   1.3.0
		 */
		public function localize_fonts_list() {
			$fonts = array();

			if ( ! class_exists( 'Kata_Plus_Pro' ) ) {
				$fonts = Kata_Plus_Theme_Options_Functions::simple_font_manager_data_for_styler();
			}

			$fonts = apply_filters( 'kata_localize_fonts_list', $fonts );

			$list  = array();
			foreach ( $fonts as $font ) {
				if ( isset( $font['source'] ) ) {
					if( $font['source'] === 'upload-icon' ) {
						continue;
					}

					if ( $font['source'] === 'upload-font' ) {
						$font_names = json_decode( $font['name'] );
						$font_names_list = [];
						foreach ( $font_names as $ft => $fl ) {
							foreach ( $fl as $fh => $fn ) {
								if( isset( $font_names_list[$fn] ) ) {
									continue;
								}
								$font_names_list[$fn] = $fn;
								$list[] = trim( $fn );
							}
						}
						continue;
					}
				}

				if ( isset( $font['name'] ) ) {
					$font['name'] = explode( ',' ,$font['name'] );
					foreach ($font['name'] as  $fontname ) {
						$list[] = trim( $fontname );
					}
				}
			}

			echo '<script>var kata_plus_fonts_list=' . json_encode( $list, true ) . '</script>';
		}
	} // class
	Kata_Plus::get_instance();
}

<?php
/**
 * Install Plugins Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Install_Plugins' ) ) {
	class Kata_Plus_Install_Plugins {
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
		 * The directory of the plugins.
		 *
		 * @access  private
		 * @var     string
		 */
		public $plugins_dir;

		/**
		 * The url of the images.
		 *
		 * @access  private
		 * @var     string
		 */
		public $images_url;

		/**
		 * Required plugins data.
		 *
		 * @access  private
		 * @var     string
		 */
		public $plugins_data;

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Install_Plugins
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
		 * @access      public
		 * @return      void
		 */
		public function __construct() {
			$this->definitions();
			$this->actions();
			$this->dependencies();
		}

		/**
		 * Global definitions.
		 *
		 * @since   1.0.0
		 */
		public function definitions() {
			self::$dir         = Kata_Plus::$dir . 'includes/install-plugins/';
			self::$url         = Kata_Plus::$url . 'includes/install-plugins/';
			$this->images_url  = Kata_Plus::$assets . 'images/admin/plugins/';
			$this->plugins_dir = 'https://katademos.com/requirements/pp/';
		}

		/**
		 * Add actions.
		 *
		 * @since   1.0.0
		 */
		public function actions() {
			add_action( 'tgmpa_register', array( $this, 'register_plugins' ) );
			add_filter( 'tgmpa_load', array( $this, 'tgmpa_load' ), 10 );
			add_action( 'wp_ajax_kata_plus_plugin_actions', array( $this, 'plugin_actions' ) );
			if ( isset( $_GET['page'] ) && $_GET['page'] == 'kata-plus-plugins' ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			}
			if ( isset( $_GET['page'] ) && ( $_GET['page'] == 'kata-plus-plugins' || $_GET['page'] == 'kata-plus-demo-importer' ) ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			}
		}

		/**
		 * Load dependencies.
		 *
		 * @since   1.0.0
		 */
		public function dependencies() {
			Kata_Plus_Autoloader::load( self::$dir . 'core', 'class-tgm-plugin-activation' );
		}

		/**
		 * Register recommended plugins.
		 *
		 * @access  public
		 * @param   null
		 * @return  void
		 */
		public function register_plugins() {
			tgmpa(
				$this->configuration_plugins(),
				array(
					'id'           => 'kata-plus',                   // Unique ID for hashing notices for multiple instances of TGMPA.
					'default_path' => '',                       // Default absolute path to bundled plugins.
					'menu'         => 'kata-plus-plugins',      // Menu slug.
					'parent_slug'  => 'admin.php',              // Parent menu slug.
					'capability'   => 'edit_theme_options',     // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
					'has_notices'  => false,                    // Show admin notices or not.
					'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
					'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
					'is_automatic' => true,                   // Automatically activate plugins after installation or not.
					'message'      => '',                      // Message to output right before the plugins table.
				)
			);
		}

		/**
		 * Configuration recommended plugins.
		 *
		 * @access  private
		 * @param   null
		 * @return  array
		 */
		private function configuration_plugins() {
			$this->plugins_data = require_once self::$dir . 'config/install-plugins-data.php';
			$plugins = apply_filters( 'kata_plus_plugins', $this->plugins_data );
			return $plugins;
		}

		/**
		 * By default TGMPA only loads on the WP back-end and not in an Ajax call.
		 * Enable TGMP in AJAX call.
		 *
		 * @access  public
		 * @param   bool $load Whether or not TGMPA should load.
		 * @return  bool
		 */
		public function tgmpa_load( $load ) {
			return true;
		}

		/**
		 * AJAX callback method. Used to activate/deactivate/install plugin.
		 *
		 * @access  public
		 * @param   null
		 * @return  void
		 */
		public function plugin_actions() {
			if ( current_user_can( Kata_Plus_Helpers::capability() ) ) {
				check_admin_referer( 'tgmpa-' . $_GET['plugin-action'], 'tgmpa-nonce' );

				$tgmpa = TGM_Plugin_Activation::$instance;

				// active/deactive
				if ( $_GET['plugin-action'] == 'activate' || $_GET['plugin-action'] == 'deactivate' ) {
					$plugins = $tgmpa->plugins;

					foreach ( $plugins as $plugin ) {
						if ( isset( $_GET['plugin'] ) && ( $plugin['slug'] === $_GET['plugin'] ) ) {
							if ( $_GET['plugin-action'] == 'activate' ) {
								activate_plugin( $plugin['file_path'] );
								$convert_action = 'deactivate';
								$url            = wp_nonce_url(
									add_query_arg(
										array(
											'plugin' => urlencode( $plugin['slug'] ),
											'tgmpa-' . $convert_action => $convert_action . '-plugin',
										),
										$tgmpa->get_tgmpa_url()
									),
									'tgmpa-' . $convert_action,
									'tgmpa-nonce'
								);
								echo 'deactivate_href:' . htmlspecialchars_decode( $url );
							} else {
								deactivate_plugins( $plugin['file_path'] );
								$convert_action = 'activate';

								$url = wp_nonce_url(
									add_query_arg(
										array(
											'plugin' => urlencode( $plugin['slug'] ),
											'tgmpa-' . $convert_action => $convert_action . '-plugin',
										),
										$tgmpa->get_tgmpa_url()
									),
									'tgmpa-' . $convert_action,
									'tgmpa-nonce'
								);
								echo 'activate_href:' . htmlspecialchars_decode( $url );
							}
						}
					}

					// install
				} else {
					$tgmpa->install_plugins_page();

					$url = wp_nonce_url(
						add_query_arg(
							array(
								'plugin'         => urlencode( $_GET['plugin'] ),
								'tgmpa-activate' => 'activate-plugin',
							),
							$tgmpa->get_tgmpa_url()
						),
						'tgmpa-activate',
						'tgmpa-nonce'
					);

					echo 'activate_href:' . htmlspecialchars_decode( $url );
				}
			}

			// this is required to terminate immediately and return a proper response
			wp_die();
		}

		/**
		 * Enqueue Styles.
		 *
		 * @access public
		 * @return void
		 */
		public function enqueue_styles() {
			wp_enqueue_style( 'kata-plus-plugins', Kata_Plus::$assets . 'css/backend/install-plugins.css' );
			if ( is_rtl() ) {
				wp_enqueue_style( 'kata-plus-plugins-rtl', Kata_Plus::$assets . 'css/backend/install-plugins-rtl.css' );
			}
		}

		/**
		 * Enqueue JavaScripts.
		 *
		 * @since   1.0.0
		 */
		public function enqueue_scripts() {
		}
	} // class

	Kata_Plus_Install_Plugins::get_instance();
}

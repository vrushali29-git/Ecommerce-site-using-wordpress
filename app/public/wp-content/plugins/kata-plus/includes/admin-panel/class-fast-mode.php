<?php
/**
 * Fast Mode Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Plugin;

if ( ! class_exists( 'Kata_Plus_Fast_Mode' ) ) {

	/**
	 * Kata Plus.
	 *
	 * @author     Climaxthemes
	 * @package     Kata Plus
	 * @since     1.0.0
	 */
	class Kata_Plus_Fast_Mode extends Kata_Plus_Admin_Panel {

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Elementor
		 */
		public static $instance;

		/**
		 * Step holder.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var string
		 */
		private static $step = '';

		/**
		 * Step holder.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var string
		 */
		private static $kata_options = '';

		/**
		 * close fast mode URL.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var string
		 */
		private static $close_fast_mode = '';

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
			if ( ! class_exists( 'Elementor\Plugin' ) ) {
				return;
			}

			$this::$step           = isset( $_GET['step'] ) ? $_GET['step'] : '1';
			self::$close_fast_mode = get_option( 'cwb_redirect' ) ? 'https://climaxthemes.ca/' : admin_url();
			$this::$kata_options   = get_option( 'kata_options' );
			$this->actions();
		}

		/**
		 * Get server operating system.
		 *
		 * @since 1.0.0
		 */
		public function actions() {
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ), 999999 );
			add_action( 'wp_ajax_header_importer', array( $this, 'header_importer' ) );
			add_action( 'wp_ajax_footer_importer', array( $this, 'footer_importer' ) );
			add_action( 'wp_ajax_typography_importer', array( $this, 'typography_importer' ) );
		}

		/**
		 * Enqueue Scripts.
		 *
		 * @since 1.0.0
		 */
		public function scripts() {

			if ( parent::current_screen()['base_name'] == 'fast mode' && self::$step ) {

				wp_enqueue_style( 'kata-plus-fast-mode-header', Kata_Plus::$assets . 'css/backend/fast-mode-header.css', array(), Kata_Plus::$version );
				wp_enqueue_style( 'kata-plus-fast-mode', Kata_Plus::$assets . 'css/backend/fast-mode-step-' . self::$step . '.css', array(), Kata_Plus::$version );

				if ( is_rtl() ) {
					wp_enqueue_style( 'kata-plus-fast-mode-header-rtl', Kata_Plus::$assets . 'css/backend/fast-mode-header-rtl.css', array(), Kata_Plus::$version );
					wp_enqueue_style( 'kata-plus-fast-mode-rtl', Kata_Plus::$assets . 'css/backend/fast-mode-step-' . self::$step . '-rtl.css', array(), Kata_Plus::$version );
				}

				wp_enqueue_script( 'kata-plus-fast-mode', Kata_Plus::$assets . 'js/backend/fast-mode.js', array( 'jquery' ), Kata_Plus::$version, true );
				wp_localize_script(
					'kata-plus-fast-mode',
					'fast_mode_localize',
					array(
						'ajax' => array(
							'url'    => admin_url( 'admin-ajax.php' ),
							'nonce'  => wp_create_nonce( 'kata_fast_mode_nonce' ),
							'notice' => array(
								'importing' => __( 'Importing content, Please Wait!', 'kata-plus' ),
								'error'     => __( 'Import progress failed.', 'kata-plus' ),
								'success'   => __( 'Import progress completed.', 'kata-plus' ),
							),
						),
					)
				);

				if ( '3' === self::$step ) {

					wp_enqueue_media();
				}

				if ( '5' === self::$step ) {

					wp_enqueue_style( 'kata-plus-importer', Kata_Plus::$assets . 'css/backend/importer.css' );

					if ( is_rtl() ) {

						wp_enqueue_style( 'kata-plus-importer-rtl', Kata_Plus::$assets . 'css/backend/importer-rtl.css' );
					}

					wp_enqueue_style( 'nice-select', Kata_Plus::$assets . 'css/libraries/nice-select.css', array(), Kata_Plus::$version );
					wp_enqueue_script( 'lozad', Kata_Plus::$assets . 'js/libraries/lozad.min.js', array( 'jquery' ), Kata_Plus::$version, false );
					wp_enqueue_script( 'nice-select', Kata_Plus::$assets . 'js/libraries/jquery.nice-select.js', array( 'jquery' ), Kata_Plus::$version, true );
					wp_enqueue_script( 'kata-plus-importer', Kata_Plus::$assets . 'js/backend/importer.js', array( 'jquery', 'nice-select' ), Kata_plus::$version, true );
					wp_localize_script(
						'kata-plus-importer',
						'importer_localize',
						array(
							'ajax' => array(
								'url'   => admin_url( 'admin-ajax.php' ),
								'nonce' => wp_create_nonce( 'kata_importer_nonce' ),
							),
						)
					);

					wp_localize_script(
						'kata-plus-importer',
						'kata_install_plugins',
						array(
							'translation' => array(
								'activate'           => esc_html__( 'Activate', 'kata-plus' ),
								'deactivate'         => esc_html__( 'Deactivate', 'kata-plus' ),
								'fail-plugin-action' => esc_html__( 'There was a problem with your action. Please try again or reload the page.', 'kata-plus' ),
							),
						)
					);
				}

				if ( '7' === self::$step ) {

					wp_enqueue_script( 'kata-plus-fast-mode', Kata_Plus::$assets . 'js/backend/fast-mode.js', array( 'jquery' ), Kata_Plus::$version, true );
					wp_localize_script(
						'kata-plus-fast-mode',
						'fast_mode_localize',
						array(
							'ajax' => array(
								'url'    => admin_url( 'admin-ajax.php' ),
								'nonce'  => wp_create_nonce( 'kata_fast_mode_nonce' ),
								'notice' => array(
									'importing' => __( 'Importing content, Please Wait!', 'kata-plus' ),
									'error'     => __( 'Import progress failed.', 'kata-plus' ),
									'success'   => __( 'Import progress completed.', 'kata-plus' ),
								),
							),
						)
					);
					wp_enqueue_script( 'nice-select', Kata_Plus::$assets . 'js/libraries/jquery.nice-select.js', array( 'jquery' ), Kata_Plus::$version, true );
					wp_enqueue_script( 'kata-plus-importer', Kata_Plus::$assets . 'js/backend/importer.js', array( 'jquery', 'nice-select' ), Kata_plus::$version, true );
					wp_localize_script(
						'kata-plus-importer',
						'importer_localize',
						array(
							'ajax' => array(
								'url'   => admin_url( 'admin-ajax.php' ),
								'nonce' => wp_create_nonce( 'kata_importer_nonce' ),
							),
						)
					);
					wp_localize_script(
						'kata-plus-importer',
						'kata_install_plugins',
						array(
							'translation' => array(
								'activate'           => esc_html__( 'Activate', 'kata-plus' ),
								'deactivate'         => esc_html__( 'Deactivate', 'kata-plus' ),
								'fail-plugin-action' => esc_html__( 'There was a problem with your action. Please try again or reload the page.', 'kata-plus' ),
							),
						)
					);
				}
				wp_deregister_style( 'kata-elementor-admin-dark' );
				wp_dequeue_style( 'kata-elementor-admin-dark' );
			}
		}

		/**
		 * Return the close url based on website builder.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public static function close_url() {
			return self::$close_fast_mode;
		}

		/**
		 * Call and run the specefic step.
		 *
		 * @since 1.0.0
		 */
		public static function step_handler() {
			if ( self::$step == 1 ) {
				echo '<style type="text/css"> html { background: #f1f1f1; } body { background: #fff; border: 1px solid #ccd0d4; color: #444; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; margin: 2em auto; padding: 1em 2em; max-width: 700px; -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .04); box-shadow: 0 1px 1px rgba(0, 0, 0, .04); } h1 { border-bottom: 1px solid #dadada; clear: both; color: #666; font-size: 24px; margin: 30px 0 0 0; padding: 0; padding-bottom: 7px; } #error-page { margin-top: 50px; } #error-page p, #error-page .wp-die-message { font-size: 14px; line-height: 1.5; margin: 25px 0 20px; } #error-page code { font-family: Consolas, Monaco, monospace; } ul li { margin-bottom: 10px; font-size: 14px ; } a { color: #0073aa; } a:hover, a:active { color: #006799; } a:focus { color: #124964; -webkit-box-shadow: 0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, 0.8); box-shadow: 0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, 0.8); outline: none; } .button { background: #f3f5f6; border: 1px solid #016087; color: #016087; display: inline-block; text-decoration: none; font-size: 13px; line-height: 2; height: 28px; margin: 0; padding: 0 10px 1px; cursor: pointer; -webkit-border-radius: 3px; -webkit-appearance: none; border-radius: 3px; white-space: nowrap; -webkit-box-sizing: border-box; -moz-box-sizing:    border-box; box-sizing:         border-box; vertical-align: top; } .button.button-large { line-height: 2.30769231; min-height: 32px; padding: 0 12px; } .button:hover, .button:focus { background: #f1f1f1; } .button:focus { background: #f3f5f6; border-color: #007cba; -webkit-box-shadow: 0 0 0 1px #007cba; box-shadow: 0 0 0 1px #007cba; color: #016087; outline: 2px solid transparent; outline-offset: 0; } .button:active { background: #f3f5f6; border-color: #7e8993; -webkit-box-shadow: none; box-shadow: none; } </style> ';
				wp_die(
					'Unfortunately, there is nothing to display',
					'Unfortunately, there is nothing to display',
					array(
						'response'  => 500,
						'link_url'  => esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=business' ) ),
						'link_text' => esc_html__( 'Go to wizard' ),
					)
				);
			}
			Kata_Plus_Autoloader::load( self::$dir . 'fast-mode', 'heder.tpl' );
			Kata_Plus_Autoloader::load( self::$dir . 'fast-mode', 'mode.tpl.' . self::$step );
			self::store_options( $_GET );
			self::set_options();
			self::help_modual();
		}

		/**
		 * Store the fast mode user data entery.
		 *
		 * @param string $option set option for each step
		 * @since 1.0.0
		 */
		private static function store_options( $option ) {
			$kata_options = self::$kata_options;
			if ( ! isset( $kata_options['fast-mode'] ) ) {
				$kata_options['fast-mode'] = array();
				update_option( 'kata_options', $kata_options );
				$kata_options = get_option( 'kata_options' );
			}

			/**
			* Website Type
			*/
			if ( isset( $option['websitetype'] ) && ! empty( $option['websitetype'] ) ) {
				$kata_options['fast-mode']['websitetype'] = $option['websitetype'];
			}

			/**
			* Site Title
			*/
			if ( isset( $option['blogname'] ) && ! empty( $option['blogname'] ) ) {
				$kata_options['fast-mode']['blogname'] = $option['blogname'];
			}

			/**
			* Site Tagline
			*/
			if ( isset( $option['blogdescription'] ) && ! empty( $option['blogdescription'] ) ) {
				$kata_options['fast-mode']['blogdescription'] = $option['blogdescription'];
			}

			/**
			* Site URL
			*/
			if ( isset( $option['siteurl'] ) && ! empty( $option['siteurl'] ) ) {
				$kata_options['fast-mode']['siteurl'] = $option['siteurl'];
			}

			/**
			* Admin Email
			*/
			if ( isset( $option['admin_email'] ) && ! empty( $option['admin_email'] ) ) {
				$kata_options['fast-mode']['admin_email'] = $option['admin_email'];
			}

			/**
			* Site Language
			*/
			if ( isset( $option['WPLANG'] ) && ! empty( $option['WPLANG'] ) ) {
				$kata_options['fast-mode']['WPLANG'] = $option['WPLANG'];
			}

			/**
			* Site Timezone
			*/
			if ( isset( $option['timezone_string'] ) && ! empty( $option['timezone_string'] ) ) {
				$kata_options['fast-mode']['timezone_string'] = rtrim( $option['timezone_string'], '/' );
			}

			/**
			* Site Phone Number
			*/
			if ( isset( $option['site-phone'] ) && ! empty( $option['site-phone'] ) ) {
				$kata_options['fast-mode']['site-phone'] = rtrim( $option['site-phone'], '/' );
			}

			/**
			* Site Address
			*/
			if ( isset( $option['site-address'] ) && ! empty( $option['site-address'] ) ) {
				$kata_options['fast-mode']['site-address'] = $option['site-address'];
			}

			/**
			* Site Facebook
			*/
			if ( isset( $option['site-facebook'] ) && ! empty( $option['site-facebook'] ) ) {
				$kata_options['fast-mode']['site-facebook'] = $option['site-facebook'];
			}

			/**
			* Site Twitter
			*/
			if ( isset( $option['site-twitter'] ) && ! empty( $option['site-twitter'] ) ) {
				$kata_options['fast-mode']['site-twitter'] = $option['site-twitter'];
			}

			/**
			* Site linkedin
			*/
			if ( isset( $option['site-linkedin'] ) && ! empty( $option['site-linkedin'] ) ) {
				$kata_options['fast-mode']['site-linkedin'] = $option['site-linkedin'];
			}

			/**
			* Site instagram
			*/
			if ( isset( $option['site-instagram'] ) && ! empty( $option['site-instagram'] ) ) {
				$kata_options['fast-mode']['site-instagram'] = $option['site-instagram'];
			}

			/**
			* Site favicon
			*/
			if ( isset( $option['site-icon'] ) && ! empty( $option['site-icon'] ) ) {
				$kata_options['fast-mode']['site-icon'] = $option['site-icon'];
			}

			/**
			* Site Logo
			*/
			if ( isset( $option['site-logo'] ) && ! empty( $option['site-logo'] ) ) {
				$kata_options['fast-mode']['site-logo'] = rtrim( $option['site-logo'], '/' );
			}
			update_option( 'kata_options', $kata_options );
		}

		/**
		 * Fast Mode set option.
		 *
		 * @param string $option set option for each step
		 * @since 1.0.0
		 */
		private static function set_options() {
			$kata_options = get_option( 'kata_options' )['fast-mode'];

			if ( isset( $_GET['blogname'] ) ) {
				delete_option( 'blogname' );
				add_option( 'blogname', $_GET['blogname'] );
			}

			if ( function_exists( 'wp_can_install_language_pack' ) ) {
				if ( wp_can_install_language_pack() && ! empty( $_GET['WPLANG'] ) ) {
					$loaded_language = wp_download_language_pack( $_GET['WPLANG'] );
					if ( $loaded_language ) {
						$user_language_new = $loaded_language;
						$user_language_old = get_user_locale();
						if ( $user_language_old !== $user_language_new ) {
							load_default_textdomain( $user_language_new );
							delete_option( 'WPLANG' );
							update_option( 'WPLANG', $user_language_new );
							unset( $GLOBALS['locale'] );
							$GLOBALS['locale']    = $loaded_language;
							$GLOBALS['wp_locale'] = new WP_Locale();
						}
					}
				}
			}

			if ( isset( $_GET['blogdescription'] ) ) {
				delete_option( 'blogdescription' );
				add_option( 'blogdescription', $_GET['blogdescription'] );
			}

			if ( isset( $_GET['admin-email'] ) ) {
				delete_option( 'admin_email' );
				add_option( 'admin_email', $_GET['admin-email'] );
			}

			if ( isset( $_GET['timezone_string'] ) ) {
				delete_option( 'timezone_string' );
				add_option( 'timezone_string', rtrim( $_GET['timezone_string'], '/' ) );
			}

			if ( isset( $_GET['site-logo'] ) ) {
				set_theme_mod( 'custom_logo', rtrim( $_GET['site-logo'], '/' ) );
			}

			if ( isset( $_GET['site-icon'] ) ) {
				delete_option( 'site_icon' );
				add_option( 'site_icon', $_GET['site-icon'] );
			}

			if ( isset( $_GET['site-address'] ) && '6' == $_GET['step'] ) {
				$address = false;
				global $wpdb;
				$pages = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", 'page' ), ARRAY_A );
				foreach ( $pages as $page ) {
					@$meta = json_decode( get_post_meta( $page['ID'], '_elementor_data', false )[0] );
					if ( $meta ) {
						foreach ( $meta as $sections ) {
							// enter to sections
							if ( 'section' == $sections->elType ) {
								// enter to columns
								foreach ( $sections->elements as $key => $elements ) {
									if ( 'column' == $elements->elType ) {
										// enter to elements
										foreach ( $elements->elements as $key => $value ) {
											if ( 'widget' == $value->elType && 'kata-plus-address' == $value->widgetType ) {
												if ( $_GET['site-address'] != $value->settings->address ) {
													$value->settings->address = rtrim( $_GET['site-address'], '/' );
													$address                  = true;
												}
											}
										}
									}
								}
							}
						}
						if ( $address ) {
							$address = false;
							$meta    = wp_json_encode( $meta );
							update_post_meta( $page['ID'], '_elementor_data', $meta );
							Plugin::$instance->files_manager->clear_cache();
						}
					}
				}
			}

			if ( isset( $_GET['site-facebook'] ) && isset( $_GET['site-twitter'] ) && isset( $_GET['site-linkedin'] ) && isset( $_GET['site-instagram'] ) && '6' == $_GET['step'] ) {
				$socials = false;
				global $wpdb;
				$pages = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", 'page' ), ARRAY_A );
				foreach ( $pages as $page ) {
					@$meta = json_decode( get_post_meta( $page['ID'], '_elementor_data', false )[0] );
					if ( $meta ) {
						foreach ( $meta as $sections ) {
							// enter to sections
							if ( 'section' == $sections->elType ) {
								// enter to columns
								foreach ( $sections->elements as $key => $elements ) {
									if ( 'column' == $elements->elType ) {
										// enter to elements
										foreach ( $elements->elements as $key => $value ) {
											if ( 'widget' == $value->elType && 'kata-plus-socials' == $value->widgetType ) {
												$value->settings->icons = array(
													(object) array(
														'social_icon'   => 'font-awesome/facebook',
														'icon_name'     => 'Facebook',
														'icon_link'     => (object) array(
															'url'   => $_GET['site-facebook'],
														),
														'_id' => 'cwb3569',
													),
													(object) array(
														'social_icon'   => 'font-awesome/twitter',
														'icon_name'     => 'Twitter',
														'icon_link'     => (object) array(
															'url'   => $_GET['site-twitter'],
														),
														'_id' => 'cwb3570',
													),
													(object) array(
														'social_icon'   => 'font-awesome/linkedin',
														'icon_name'     => 'Linkedin',
														'icon_link'     => (object) array(
															'url'   => $_GET['site-linkedin'],
														),
														'_id' => 'cwb3571',
													),
													(object) array(
														'social_icon'   => 'font-awesome/instagram',
														'icon_name'     => 'Instagram',
														'icon_link'     => (object) array(
															'url'   => $_GET['site-instagram'],
														),
														'_id' => 'cwb3572',
													),
												);
												$socials                = true;
											}
										}
									}
								}
							}
						}
						if ( $socials ) {
							$socials = false;
							$meta    = wp_json_encode( $meta );
							update_post_meta( $page['ID'], '_elementor_data', $meta );
							Plugin::$instance->files_manager->clear_cache();
						}
					}
				}
			}

			if ( isset( $_GET['site-phone'] ) && '6' == $_GET['step'] ) {
				$phone = false;
				global $wpdb;
				$pages = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", 'page' ), ARRAY_A );
				foreach ( $pages as $page ) {
					@$meta = json_decode( get_post_meta( $page['ID'], '_elementor_data', false )[0] );
					if ( $meta ) {
						foreach ( $meta as $sections ) {
							// enter to sections
							if ( 'section' == $sections->elType ) {
								// enter to columns
								foreach ( $sections->elements as $key => $elements ) {
									if ( 'column' == $elements->elType ) {
										// enter to elements
										foreach ( $elements->elements as $key => $value ) {
											if ( 'widget' == $value->elType && 'kata-plus-phone' == $value->widgetType ) {
												if ( $_GET['site-phone'] != $value->settings->phonenumber ) {
													$value->settings->phonenumber = $_GET['site-phone'];
													$phone                        = true;
												}
											}
										}
									}
								}
							}
						}
						if ( $phone ) {
							$phone = false;
							$meta  = wp_json_encode( $meta );
							update_post_meta( $page['ID'], '_elementor_data', $meta );
							Plugin::$instance->files_manager->clear_cache();
						}
					}
				}
			}
		}

		/**
		 * Help modual.
		 *
		 * @since 1.0.0
		 */
		public static function help_modual() {
			$steps = self::$step;
			?>
			<div class="kt-fst-hlp-overlay" style="display:none;">
				<div class="kt-fst-hlp-wrapper">
					<i class="ti-close close-help"></i>
					<h3 class="kt-fst-hlp-title"><?php echo esc_html__( 'help', 'kata-plus' ); ?></h3>
					<div class="kt-fst-hlp-inner-alert"><?php echo esc_html__( 'The Fast Mode is a mode by which you can create a website as fast as possible. It does not have anything to do with the website load speed or performance.', 'kata-plus' ); ?></div>
					<div class="kt-fst-hlp-inner-content">
						<?php
						switch ( $steps ) {
							case '1':
								?>
								<h5 class="content-title"><?php echo esc_html__( 'Launch the website right now.', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'Choosing the type of website. In this step, you have to specify the type of website you want to run. Please make this choice carefully because it affects the next steps and some of them will be dependent on your choice on this step. It may cause some changes, adding, or removals in some of the options of the following steps.', 'kata-plus' ); ?></p>
								<?php
								break;
							case '2':
								?>
								<h5 class="content-title"><?php echo esc_html__( 'Site title', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'The Site Title appears across the top of every page of a site', 'kata-plus' ); ?></p>
								<h5 class="content-title"><?php echo esc_html__( 'Site tagline', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'The title is typically the name of your site', 'kata-plus' ); ?></p>
								<h5 class="content-title"><?php echo esc_html__( 'Admin Email', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'Your website\'s email address is used by WordPress to send important email notifications. For example, when a new user account is created, an auto update is installed, and for comment moderation notices. The admin user\'s email address is used to recover lost password and notifications about their account', 'kata-plus' ); ?></p>
								<?php
								break;
							case '3':
								?>
								<h5 class="content-title"><?php echo esc_html__( 'Site Icon', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'A favicon (universal term) or Site Icon (WordPress terminology) is short for favorites icon. It is an icon associated with a website to be displayed with bookmarks, in the URL bar, on tabs, and anywhere else where a website has to be identified visually.', 'kata-plus' ); ?></p>
								<h5 class="content-title"><?php echo esc_html__( 'Logo', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'By choosing the website\'s logo, it will be shown wherever logo widget has been used.', 'kata-plus' ); ?></p>
								<?php
								break;
							case '4':
								?>
								<h5 class="content-title"><?php echo esc_html__( 'Logo, Address and socials', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'By filling out the above form, your data regarding the phone, address, and socials will be saved in the widget and will be shown in your website. And you won\'t need to edit in widgets through your website.', 'kata-plus' ); ?></p>
								<?php
								break;
							case '5':
								?>
								<h5 class="content-title"><?php echo esc_html__( 'Demo Importer', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'The Demo Importer is a tool used to import Kata\'s pre-made demos. Using this tool, you can activate the plugins needed for every demo in a few simple clicks and import all the demo data to any WordPress website.', 'kata-plus' ); ?></p>
								<?php
								break;
							case '6':
								?>
								<h5 class="content-title"><?php echo esc_html__( 'Launch the website right now', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'By choosing Launch in the website, all settings will be saved and you will be redirected to the home page. ', 'kata-plus' ); ?></p>
								<h5 class="content-title"><?php echo esc_html__( 'Edit website\'s content and design.', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'By choosing Edit website\'s content, you move to the next step, enabling you with further options to start the customization.', 'kata-plus' ); ?></p>
								<?php
								break;
							case '7':
								?>
								<h5 class="content-title"><?php echo esc_html__( 'Homepage', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'Go to homepage customization area.', 'kata-plus' ); ?></p>
								<h5 class="content-title"><?php echo esc_html__( 'Menu', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'Go to menu navigation customization area.', 'kata-plus' ); ?></p>
								<h5 class="content-title"><?php echo esc_html__( 'Blog', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'Go to Blog Builder customization area.', 'kata-plus' ); ?></p>
								<h5 class="content-title"><?php echo esc_html__( 'Pages', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'Show list of pages to start customize them using elementor page builder.', 'kata-plus' ); ?></p>
								<h5 class="content-title"><?php echo esc_html__( 'Posts', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'Show list of posts to start customize them.', 'kata-plus' ); ?></p>
								<h5 class="content-title"><?php echo esc_html__( 'Header', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'Go to Header Builder customization area.', 'kata-plus' ); ?></p>
								<h5 class="content-title"><?php echo esc_html__( 'Footer', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'Go to Footer Builder customization area.', 'kata-plus' ); ?></p>
								<h5 class="content-title"><?php echo esc_html__( 'Typography', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'Import Preset typography.', 'kata-plus' ); ?></p>
								<h5 class="content-title"><?php echo esc_html__( 'Plugins', 'kata-plus' ); ?></h5>
								<p class="content-text"><?php echo esc_html__( 'Install & activate the recommended plugins.', 'kata-plus' ); ?></p>
								<?php
								break;

							default:
								// code...
								break;
						}
						?>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Pages Lists.
		 *
		 * @since 1.0.0
		 */
		public static function pages() {
			global $wpdb;
			$pages      = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", 'page' ), ARRAY_A );
			$pages_list = array();
			foreach ( $pages as $page ) {
				$pages_list[] = '
				<div class="kata-finder-category-item">
				<a href="' . esc_url( admin_url( 'post.php?post=' . $page['ID'] . '&action=elementor' ) ) . '" target="_blank">' . Kata_Plus_Helpers::get_icon( 'themify', 'write' ) . esc_html( $page['post_title'] ) . '</a>
				</div>';
			}

			return $pages_list;
		}

		/**
		 * Posts Lists.
		 *
		 * @since 1.0.0
		 */
		public static function posts() {
			global $wpdb;
			$posts      = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", 'post' ), ARRAY_A );
			$posts_list = array();
			foreach ( $posts as $post ) {
				$posts_list[] = '
				<div class="kata-finder-category-item">
					<a href="' . esc_url( admin_url( 'post.php?post=' . $post['ID'] . '&action=elementor' ) ) . '"> ' . Kata_Plus_Helpers::get_icon( 'themify', 'write' ) . esc_html( $post['post_title'] ) . '</a>
				</div>';
			}
			return $posts_list;
		}

		/**
		 * Headers template List.
		 *
		 * @since 1.0.0
		 */
		public static function headers() {
			$response = wp_safe_remote_get(
				'https://katademos.com/api/template-manager/v3/templates.json',
				array(
					'timeout'    => 30,
					'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36',
				)
			);

			$response_code = wp_remote_retrieve_response_code( $response );
			if ( $response_code !== 200 ) {
				return new \WP_Error( '_response_code_error', esc_html__( 'Ooops, The Request Code :', 'kata-plus' ) . $response_code );
			}

			$template_data = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( isset( $template_data['error'] ) ) {
				return new \WP_Error( '_response_error', esc_html( $response['message'] ) );
			}

			if ( empty( $template_data ) ) {
				return new \WP_Error( '_response_code_error', esc_html__( 'Ooops, Invalid Data', 'kata-plus' ) );
			}
			$counter = 1;
			foreach ( $template_data as $template ) {
				if ( 'header' == $template['subtype'] && 6 >= $counter ) {
					?>
					<div class="header-item" data-id="<?php echo esc_attr( $template['id'] ); ?>">
						<figure class="header-tpls-thumbnail-wrapper">
							<img src="<?php echo esc_url( $template['thumbnail'] ); ?>" alt="<?php echo esc_attr( $template['title'] ); ?>">
							<figcaption><?php echo esc_html( $template['title'] ); ?></figcaption>
						</figure>
					</div>
					<?php
					++$counter;
				}
			}
		}

		/**
		 * Typography template List.
		 *
		 * @since 1.0.0
		 */
		public static function typography() {
			$template = array(
				'1' => __( 'Open Sans', 'kata-plus' ),
				'2' => __( 'Lora', 'kata-plus' ),
				'3' => __( 'Poppins', 'kata-plus' ),
				'4' => __( 'Nunito', 'kata-plus' ),
				'5' => __( 'Sora', 'kata-plus' ),
				'6' => __( 'IBM Plex Sans', 'kata-plus' ),
				'7' => __( 'Roboto', 'kata-plus' ),
				'8' => __( 'Oswald', 'kata-plus' ),
			);
			foreach ( $template as $key => $value ) {
				?>
				<div class="typography-item" data-id="<?php echo esc_attr( $key ); ?>"> </div>
				<?php
			}
		}

		/**
		 * Typography Importer.
		 *
		 * @since 1.0.0
		 */
		public static function typography_importer() {
			global $wpdb;
			$json_file  = parent::$url . 'fast-mode/presets/fonts/' . $_POST['template_id'] . '.json';
			$response   = wp_remote_get( $json_file );

			if ( is_wp_error( $response ) ) {
				wp_send_json_error( esc_html__( 'can not get font data', 'kata-plus' ), 403 );
			}

			$importData = wp_remote_retrieve_body( $response );

			$data = json_decode( $importData );

			foreach ( $data as $font ) {
				if ( is_array( $font->url ) ) {
					$font->url = wp_json_encode( $font->url );
				}
				$font->url = str_replace( '{{siteUrl}}', site_url(), $font->url );

				$result = $wpdb->insert(
					$wpdb->prefix . Kata_Plus::$fonts_table_name,
					array(
						'name'       => $font->name,
						'source'     => $font->source,
						'selectors'  => $font->selectors,
						'subsets'    => $font->subsets,
						'variants'   => $font->variants,
						'url'        => $font->url,
						'place'      => $font->place,
						'status'     => $font->status,
						'created_at' => $font->created_at,
						'updated_at' => $font->updated_at,
					)
				);

				if ( ! $result ) {
					wp_send_json_error( 'can not import font.', 500 );
				}
			}

			wp_send_json_success( 'imported successfully.', 200 );

			update_option( 'kata-fonts-manager-last-update', time() );

			wp_die();
		}

		/**
		 * Footerss template List.
		 *
		 * @param string $type.
		 * @since 1.0.0
		 */
		public static function footers() {
			$response = wp_safe_remote_get(
				'https://katademos.com/api/template-manager/v3/templates.json',
				array(
					'timeout'    => 30,
					'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36',
				)
			);

			$response_code = wp_remote_retrieve_response_code( $response );
			if ( $response_code !== 200 ) {
				return new \WP_Error( '_response_code_error', esc_html__( 'Ooops, The Request Code :', 'kata-plus' ) . $response_code );
			}

			$template_data = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( isset( $template_data['error'] ) ) {
				return new \WP_Error( '_response_error', esc_html( $response['message'] ) );
			}

			if ( empty( $template_data ) ) {
				return new \WP_Error( '_response_code_error', esc_html__( 'Ooops, Invalid Data', 'kata-plus' ) );
			}
			$counter = 1;
			foreach ( $template_data as $template ) {
				if ( 'footer' == $template['subtype'] && 6 >= $counter ) {
					?>
					<div class="footer-item" data-id="<?php echo esc_attr( $template['id'] ); ?>">
						<figure class="footer-tpls-thumbnail-wrapper">
							<img src="<?php echo esc_url( $template['thumbnail'] ); ?>" alt="<?php echo esc_attr( $template['title'] ); ?>">
							<figcaption><?php echo esc_html( $template['title'] ); ?></figcaption>
						</figure>
					</div>
					<?php
					++$counter;
				}
			}
		}

		/**
		 * Header Importer.
		 *
		 * @since 1.0.0
		 */
		public function header_importer() {
			check_ajax_referer( 'kata_fast_mode_nonce', 'nonce' );
			$builder_id    = Kata_Plus_Header_Builder::get_instance()->get_builder_id();
			$response      = wp_safe_remote_get(
				'https://katademos.com/api/template-manager/v3/template/template-' . $_POST['template_id'] . '.json',
				array(
					'timeout'    => 30,
					'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36',
				)
			);
			$response_code = wp_remote_retrieve_response_code( $response );

			if ( $response_code !== 200 ) {
				return new \WP_Error( '_response_code_error', esc_html__( 'Ooops, The Request Code :', 'kata-plus' ) . $response_code );
			}

			$template_data = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( isset( $template_data['error'] ) ) {
				return new \WP_Error( '_response_error', esc_html( $response['message'] ) );
			}
			$template_data = $template_data['content'];
			if ( empty( $template_data ) ) {
				return new \WP_Error( '_response_code_error', esc_html__( 'Ooops, Invalid Data', 'kata-plus' ) );
			}
			update_post_meta( $builder_id, '_elementor_edit_mode', 'builder' );
			update_post_meta( $builder_id, '_elementor_template_type', 'post' );
			update_post_meta( $builder_id, '_wp_page_template', 'default' );
			update_post_meta( $builder_id, '_edit_lock', time() . ':1' );
			update_post_meta( $builder_id, '_elementor_version', '0.4' );
			update_post_meta( $builder_id, '_elementor_data', $template_data );
			Plugin::$instance->files_manager->clear_cache();

			wp_die();
		}

		/**
		 * Footer Importer.
		 *
		 * @since 1.0.0
		 */
		public function footer_importer() {
			check_ajax_referer( 'kata_fast_mode_nonce', 'nonce' );
			$builder_id    = Kata_Plus_Footer_Builder::get_instance()->get_builder_id();
			$response      = wp_safe_remote_get(
				'https://katademos.com/api/template-manager/v3/template/template-' . $_POST['template_id'] . '.json',
				array(
					'timeout'    => 30,
					'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36',
				)
			);
			$response_code = wp_remote_retrieve_response_code( $response );

			if ( $response_code !== 200 ) {
				return new \WP_Error( '_response_code_error', esc_html__( 'Ooops, The Request Code :', 'kata-plus' ) . $response_code );
			}

			$template_data = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( isset( $template_data['error'] ) ) {
				return new \WP_Error( '_response_error', esc_html( $response['message'] ) );
			}
			$template_data = $template_data['content'];
			if ( empty( $template_data ) ) {
				return new \WP_Error( '_response_code_error', esc_html__( 'Ooops, Invalid Data', 'kata-plus' ) );
			}
			update_post_meta( $builder_id, '_elementor_edit_mode', 'builder' );
			update_post_meta( $builder_id, '_elementor_template_type', 'post' );
			update_post_meta( $builder_id, '_wp_page_template', 'default' );
			update_post_meta( $builder_id, '_edit_lock', time() . ':1' );
			update_post_meta( $builder_id, '_elementor_version', '0.4' );
			update_post_meta( $builder_id, '_elementor_data', $template_data );
			Plugin::$instance->files_manager->clear_cache();

			wp_die();
		}

		/**
		 * plugins list.
		 *
		 * @since 1.0.0
		 */
		public static function plugins() {
			$plugins          = TGM_Plugin_Activation::$instance->plugins;
			$tgmpa_list_table = new TGMPA_List_Table();
			$required_plugins = array();
			foreach ( $plugins as $plugin ) {
				if ( isset( $plugin['fast-mode'] ) && $plugin['fast-mode'] ) {
					$required_plugins[ $plugin['slug'] ] = $plugin;
				}
			}
			foreach ( $required_plugins as $plugin ) {
				$plugin['type']             = isset( $plugin['type'] ) ? $plugin['type'] : 'recommended';
				$plugin['sanitized_plugin'] = $plugin['name'];
				$plugin_action              = $tgmpa_list_table->kata_plus_actions_plugin( $plugin );
				$is_plugin_active_class     = TGM_Plugin_Activation::$instance->is_plugin_active( $plugin['slug'] ) ? ' active' : '';
				?>
				<div class="kata-required-plugin<?php echo esc_attr( $is_plugin_active_class ); ?>" data-plugin-name="<?php echo esc_attr( $plugin['name'] ); ?>">
					<h4><?php echo esc_html( $plugin['name'] ); ?></h4>
					<?php if ( isset( $plugin['description'] ) ) { ?>
						<span><?php echo esc_html( $plugin['description'] ); ?></span>
					<?php } ?>
					<span class="kata-required-plugin-line"></span>
					<?php echo wp_kses_post( $plugin_action ); ?>
				</div>
				<?php
			}
		}
	}

	Kata_Plus_Fast_Mode::get_instance();
}

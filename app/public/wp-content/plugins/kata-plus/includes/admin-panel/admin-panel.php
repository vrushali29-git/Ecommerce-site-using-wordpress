<?php

/**
 * Admin Panel Class.
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
use Elementor\Utils;
use Elementor\Core\Editor\Editor;
use Elementor\Core\Settings\Base\Manager;
use Elementor\Core\Common\Modules\Connect\Admin;

if ( ! class_exists( 'Kata_Plus_Admin_Panel' ) ) {

	class Kata_Plus_Admin_Panel {
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
		 * The pages_url of the file.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $pages_url;

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Admin_Panel
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

		public $action_name = 'admin_bar_init';

		/**
		 * Constructor.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {
			add_action( 'wp_ajax_prefers_color_scheme', array( $this, 'prefers_color_scheme' ) );

			if ( true == get_theme_mod( 'kata_control_panel', true ) ) {
				add_action( 'customize_controls_print_footer_scripts', array( $this, 'wp_menu' ) );
				add_action( 'elementor/editor/footer', array( $this, 'wp_menu' ) );
			}

			add_action(
				'elementor/editor/footer',
				function () {
					wp_enqueue_style( 'wp-admin-menu', Site_url() . '/wp-admin/css/admin-menu.min.css', array(), Kata_Plus::$version );
					if ( is_rtl() ) {
						echo '
						<style>
							#adminmenumain {
								transition: right .3s ease;
								-webkit-transition: right .3s ease;
								position: fixed;
								right: -200px;
								z-index: 9999;
								top: 0;
							}
							#adminmenu li img {
								width: 17px;
							}
							#adminmenumain.active {
								right: 50;
							}
							#wpcontent,
							#wpfooter {
								margin-right: 50px;
								padding-right: 20px;
							}
							.screen-reader-shortcut {
								position: absolute;
								top: -1000em;
							}
						</style>';
					} else {
						echo '
						<style>
							#adminmenumain {
								transition: left .3s ease;
								-webkit-transition: left .3s ease;
								position: fixed;
								left: -200px;
								z-index: 9999;
								top: 0;
							}
							#adminmenu li img {
								width: 17px;
							}
							#adminmenumain.active {
								left: 50;
							}
							#wpcontent,
							#wpfooter {
								margin-left: 50px;
								padding-left: 20px;
							}
							.screen-reader-shortcut {
								position: absolute;
								top: -1000em;
							}
						</style>';
					}
				}
			);
			$this->definitions();
			add_action(
				'init',
				function () {
					$this->actions();
					include_once ABSPATH . 'wp-admin/includes/plugin.php';
					self::$pages_url = array(
						'theme_activation_url'      => admin_url( 'admin.php?page=kata-plus-theme-activation' ),
						'demo_importer_url'         => admin_url( 'admin.php?page=kata-plus-demo-importer' ),
						'plugin_manager_url'        => admin_url( 'admin.php?page=kata-plus-plugins' ),
						'plugin_manager_url:static' => admin_url( 'admin.php?page=kata-plus-plugins' ),
						'plugin_manager_func'       => null,
						'fonts_manager_url'         => 'kata-fonts-manager',
						'fonts_manager_url:static'  => 'kata-fonts-manager',
						'fonts_manager_func'        => null,
						'customizer_url'            => admin_url( 'customize.php' ),
						'header_url'                => did_action( 'elementor/loaded' ) && class_exists( 'Kata_Plus_Builders_Base' ) ? Kata_Plus_Builders_Base::get_instance()->builder_url( 'kata_header' ) : '',
						'header_sticky_url'         => 'sticky-header',
						'footer_url'                => did_action( 'elementor/loaded' ) && class_exists( 'Kata_Plus_Builders_Base' ) ? Kata_Plus_Builders_Base::get_instance()->builder_url( 'kata_footer' ) : '',
						'blog_url'                  => did_action( 'elementor/loaded' ) && class_exists( 'Kata_Plus_Builders_Base' ) ? Kata_Plus_Builders_Base::get_instance()->builder_url( 'kata_blog' ) : '',
						'single_url'                => 'single-post-builder',
						'single_portfolio_url'      => 'single-portfolio-builder',
						'archive_portfolio_url'     => 'archive-portfolio-builder',
						'archive_url'               => 'archive-builder',
						'_404_url'                  => '404-builder',
						'profile_url'               => admin_url( 'profile.php' ),
						'author_url'                => 'author-builder',
						'search_url'                => 'search-builder',
						'mega_menu_url'             => 'kata-plus-mega-menu',
						'single_course_url'         => 'single-course-builder',
						'help_url'                  => admin_url( 'admin.php?page=kata-plus-help' ),
						'fast_mode'                 => 'kata-plus-fast-mode',
						'fast_mode:static'          => admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=business' ),
						'fast_mode_func'            => array( 'Kata_Plus_Fast_Mode', 'step_handler' ),
						'add_new_kata_grid'         => 'pro-post-types',
						'add_new_kata_mega_menu'    => 'pro-post-types',
						'add_new_kata_testimonial'  => 'pro-post-types',
						'add_new_kata_team_member'  => 'pro-post-types',
						'add_new_kata_recipe'       => 'pro-post-types',
						'kata_blog'                 => admin_url( 'edit.php?post_type=kata_plus_builder&builder=kata_blog' ),
						'kata_footer'               => admin_url( 'edit.php?post_type=kata_plus_builder&builder=kata_footer' ),
						'kata_header'               => admin_url( 'edit.php?post_type=kata_plus_builder&builder=kata_header' ),
						'kata_404'                  => 'pro-post-types',
						'kata_archive'              => 'pro-post-types',
						'kata_author'               => 'pro-post-types',
						'kata_single_course'        => 'pro-post-types',
						'kata_archive_portfolio'    => 'pro-post-types',
						'kata_search'               => 'pro-post-types',
						'kata_single_post'          => 'pro-post-types',
						'kata_single_portfolio'     => 'pro-post-types',
						'kata_sticky_header'        => 'pro-post-types',
					);
					apply_filters( 'kata_plus_submenu_url', self::$pages_url );
				},
				10
			);
		}

		public function wp_menu() {
			global $menu, $submenu; ?>
			<div id="adminmenumain" role="navigation" aria-label="<?php esc_attr_e( 'Main menu', 'kata-plus' ); ?>">
				<a href="#wpbody-content" class="screen-reader-shortcut"><?php esc_html_e( 'Skip to main content', 'kata-plus' ); ?></a>
				<a href="#wp-toolbar" class="screen-reader-shortcut"><?php esc_html_e( 'Skip to toolbar', 'kata-plus' ); ?></a>
				<div id="adminmenuback"></div>
				<div id="adminmenuwrap" type="kata">
					<ul id="adminmenu">
						<?php self::_wp_menu_output( $menu, $submenu ); ?>
					</ul>
				</div>
			</div>
			<?php
			if ( is_rtl() ) {
				?>
				<style>
					/* Elementor fixes
					-------------------------------------------------- */
					.elementor-device-desktop #elementor-preview-responsive-wrapper {
						width: 100% !important;
						height: 100% !important;
					}

					.elementor-editor-active .elementor-panel,
					body.elementor-editor-preview #elementor-preview {
						right: 50px;
					}

					.elementor-editor-preview .elementor-panel {
						right: 50px;
					}

					@media (max-width: 1439px) {
						.elementor-editor-preview .elementor-panel {
							right: -220px;
						}
					}

					.elementor-editor-active #elementor-preview {
						margin-right: 50px;
						width: calc( 100% - var(--e-editor-panel-width ) - 50px);
					}

					@media screen and (min-width: 1025px) {
						.wp-customizer .wp-full-overlay.expanded {
							margin-right: 396px;
						}

						.expanded #customize-controls.wp-full-overlay-sidebar,
						#customize-footer-actions {
							width: 346px
						}
					}

					/* Customizer fixes
					-------------------------------------------------- */
					.wp-full-overlay-sidebar,
					.expanded .wp-full-overlay-footer,
					.wp-core-ui .wp-full-overlay .collapse-sidebar {
						right: 50px;
					}

					.wp-full-overlay.collapsed {
						margin-right: 50px !important;
					}

					.wp-full-overlay.expanded {
						margin-right: 350px;
					}

					.wp-customizer #adminmenumain {
						transition: left .3s ease;
						-webkit-transition: left .3s ease;
						position: fixed;
						right: -200px;
						z-index: 9999;
					}

					.wp-customizer #adminmenumain.active {
						right: 50px;
					}
				</style>
				<?php
			} else {
				?>
				<style>
					/* Elementor fixes
					-------------------------------------------------- */
					.elementor-device-desktop #elementor-preview-responsive-wrapper {
						width: 100% !important;
						height: 100% !important;
					}

					.elementor-editor-active .elementor-panel,
					body.elementor-editor-preview #elementor-preview {
						left: 50px;
					}

					@media (min-width: 1440px) {
						.elementor-editor-preview .elementor-panel {
							left: 50px;
						}
					}

					@media (max-width: 1439px) {
						.elementor-editor-preview .elementor-panel {
							left: -220px;
						}
					}

					#elementor-panel {
						z-index: 999;
					}

					.elementor-editor-active #elementor-preview {
						margin-left: 50px;
						width: calc( 100% - var(--e-editor-panel-width ) - 50px);
					}

					@media screen and (min-width: 1025px) {
						.wp-customizer .wp-full-overlay.expanded {
							margin-left: 396px;
						}

						.expanded #customize-controls.wp-full-overlay-sidebar,
						#customize-footer-actions {
							width: 346px
						}
					}

					/* Customizer fixes
					-------------------------------------------------- */
					.wp-full-overlay-sidebar,
					.expanded .wp-full-overlay-footer,
					.wp-core-ui .wp-full-overlay .collapse-sidebar {
						left: 50px;
					}

					.wp-full-overlay.collapsed {
						margin-left: 50px !important;
					}

					.wp-full-overlay.expanded {
						margin-left: 350px;
					}

					.wp-customizer #adminmenumain {
						transition: left .3s ease;
						-webkit-transition: left .3s ease;
						position: fixed;
						left: -200px;
						z-index: 9999;
					}

					.wp-customizer #adminmenumain.active {
						left: 50px;
					}
				</style>
				<?php
			}
		}

		/**
		 * Global definitions.
		 *
		 * @since   1.0.0
		 */
		public function definitions() {
			self::$dir = Kata_Plus::$dir . 'includes/admin-panel/';
			self::$url = Kata_Plus::$url . 'includes/admin-panel/';
		}

		/**
		 * Add actions.
		 *
		 * @since   1.0.0
		 */
		public function actions() {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_menu', array( $this, 'create_dashboard_submenu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue_styles' ) );
			add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			if ( true == get_theme_mod( 'kata_control_panel', true ) ) {
				add_action( 'elementor/editor/footer', array( $this, 'kata_control_panel' ) );
			}
			add_action( 'elementor/editor/footer', array( $this, 'finder' ) );
			add_action( 'admin_footer', array( $this, 'finder' ) );
			add_action(
				'current_screen',
				function () {
					$current_screen = get_current_screen();
					$post_type      = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : '';
					if ( $current_screen->base == 'customize' ) {
						if ( true == get_theme_mod( 'kata_control_panel', true ) ) {
							add_action( 'admin_print_footer_scripts', array( $this, 'kata_control_panel' ), 1 );
						}
						add_action( 'admin_print_footer_scripts', array( $this, 'finder' ) );
					} elseif ( true == get_theme_mod( 'kata_control_panel', true ) ) {
							add_action( 'kata_plus_control_panel', array( $this, 'kata_control_panel' ) );
					}
				}
			);
			add_action( 'customize_controls_print_styles', array( $this, 'customizer_responsive_sizes' ) );
			add_filter( 'customize_previewable_devices', array( $this, 'customize_previewable_devices' ) );
			add_action( 'admin_menu', array( $this, 'GoPro' ) );
		}

		/**
		 * Responsive Sizes.
		 *
		 * @since   1.0.0
		 */
		public function customizer_responsive_sizes() {

			$laptop_width  = '1366px';
			$laptop_height = '640px';

			$smallmobile_margin_left = '50%'; // Half of -$mobile_width
			$smallmobile_width       = '320px';
			$smallmobile_height      = '640px';

			$mobile_margin_left = '50%'; // Half of -$mobile_width
			$mobile_width       = '420px';
			$mobile_height      = '640px';

			$tablet_landscape_width  = '1024px';
			$tablet_landscape_height = '640px';

			?>
			<style>
				#customize-preview {
					transition: all 0.5s ease;
					-webkit-transition: all 0.5s ease;
				}

				.wp-customizer .preview-smallmobile .wp-full-overlay-main {
					left: calc(50% - <?php echo esc_html( str_replace( 'px', '', $smallmobile_width ) ) / 2; ?>px);
					width: <?php echo esc_html( $smallmobile_width ); ?>;
					height: <?php echo esc_html( $smallmobile_height ); ?>;
					margin: unset !important;
				}

				.wp-customizer .preview-mobile .wp-full-overlay-main {
					left: calc(50% - <?php echo esc_html( str_replace( 'px', '', $mobile_width ) ) / 2; ?>px);
					width: <?php echo esc_html( $mobile_width ); ?>;
					height: <?php echo esc_html( $mobile_height ); ?>;
					margin: unset !important;
				}

				.wp-customizer .preview-laptop .wp-full-overlay-main {
					width: <?php echo esc_html( $laptop_width ); ?>;
					height: <?php echo esc_html( $laptop_height ); ?>;
					left: calc(50% - <?php echo esc_html( str_replace( 'px', '', $laptop_width ) ) / 2; ?>px);
					margin: unset !important;
				}

				.wp-customizer .preview-tabletlandscape .wp-full-overlay-main {
					width: <?php echo esc_html( $tablet_landscape_width ); ?>;
					height: <?php echo esc_html( $tablet_landscape_height ); ?>;
					left: calc(50% - <?php echo esc_html( str_replace( 'px', '', $tablet_landscape_width ) ) / 2; ?>px);
					margin: unset !important;
				}

				.wp-full-overlay-footer .devices>* {
					font: unset;
					font-family: eicons;
				}

				.wp-full-overlay-footer .devices>*:before {
					font: unset !important;
				}

				.wp-full-overlay-footer .devices .preview-tabletlandscape:before {
					content: '\e886';
					transform: rotate(-90deg);
				}

				.wp-full-overlay-footer .devices .preview-laptop:before {
					content: '\e8ee';
				}

				.wp-full-overlay-footer .devices .preview-tablet:before {
					content: '\e886';
				}

				.wp-full-overlay-footer .devices .preview-desktop:before {
					content: '\e885';
				}

				.wp-full-overlay-footer .devices .preview-smallmobile:before {
					content: '\e887';
					font-size: 12px !important;
					padding: 5px 8px;
				}

				.wp-full-overlay-footer .devices .preview-mobile:before {
					content: '\e887';
				}

				.wp-full-overlay .collapse-sidebar-label {
					font-size: 8px;
					margin-top: 3px;
					margin-left: -3px;
				}
			</style>
			<?php
		}

		public function customize_previewable_devices( $devices ) {
			$devices['desktop']                = array(
				'label'   => esc_html__( 'Desktop', 'kata-plus' ),
				'default' => true,
			);
			$devices['tablet']                 = array(
				'label'   => esc_html__( 'Tablet', 'kata-plus' ),
				'default' => false,
			);
			$custom_devices['desktop']         = $devices['desktop'];
			$custom_devices['laptop']          = array(
				'label'   => esc_html__( 'Laptop', 'kata-plus' ),
				'default' => false,
			);
			$custom_devices['tablet']          = $devices['tablet'];
			$custom_devices['tabletlandscape'] = array(
				'icon'    => 'eicon-device-desktop',
				'label'   => esc_html__( 'Tablet landscape', 'kata-plus' ),
				'default' => false,
			);
			$custom_devices['mobile']          = array(
				'label'   => esc_html__( 'Mobile', 'kata-plus' ),
				'default' => false,
			);
			$custom_devices['smallmobile']     = array(
				'label'   => esc_html__( 'Small Mobile', 'kata-plus' ),
				'default' => false,
			);

			foreach ( $devices as $device => $settings ) {
				if ( ! isset( $custom_devices[ $device ] ) ) {
					$custom_devices[ $device ] = $settings;
				}
			}

			return $custom_devices;
		}

		public static function kata_control_panel() {
			$protocol = ( ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) || $_SERVER['SERVER_PORT'] == 443 ) ? 'https://' : 'http://';
			$url      = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			// Profile
			$is_profile_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['profile_url'] == $url ? true : false;
			// Header
			$is_header_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['header_url'] == $url ? true : false;
			// Sticky Header
			$is_header_sticky_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['header_sticky_url'] == $url ? true : false;
			// Footer
			$is_footer_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['footer_url'] == $url ? true : false;
			// Mega Menu
			$is_mega_menu_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['mega_menu_url'] == $url ? true : false;
			// Blog
			$is_blog_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['blog_url'] == $url ? true : false;
			// Single
			$is_single_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['single_url'] == $url ? true : false;
			// Single Portfolio
			$is_single_portfolio_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['single_portfolio_url'] == $url ? true : false;
			// Archive Portfolio
			$is_archive_portfolio_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['archive_portfolio_url'] == $url ? true : false;
			// Single Course
			$is_single_course_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['single_course_url'] == $url ? true : false;
			// Archive
			$is_archive_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['archive_url'] == $url ? true : false;
			// Author
			$is_author_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['author_url'] == $url ? true : false;
			// Search
			$is_search_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['search_url'] == $url ? true : false;
			// 404
			$is_404_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['_404_url'] == $url ? true : false;
			// Theme Activation
			$is_theme_activation_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['theme_activation_url'] == $url ? true : false;
			// Demo Importer
			$is_demo_importer_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['demo_importer_url'] == $url ? true : false;
			// Plugin Manager
			$is_plugin_manager_page = strpos( $url, 'kata-plus-plugins' ) != false ? true : false;
			// Font Manager
			$is_fonts_manager_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['fonts_manager_url'] == $url ? true : false;
			// Help Page
			$is_help_page = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['help_url'] == $url ? true : false;
			// Theme Options
			$is_customizer_page = strpos( $url, 'customize.php' ) != false ? true : false;

			$settings_active_class      = $is_header_page || $is_header_sticky_page || $is_footer_page || $is_mega_menu_page || $is_blog_page || $is_single_page || $is_single_portfolio_page || $is_archive_portfolio_page || $is_archive_page || $is_author_page || $is_search_page || $is_404_page || $is_theme_activation_page || $is_demo_importer_page || $is_plugin_manager_page || $is_fonts_manager_page ? ' active' : '';
			$help_active_class          = isset( $_GET['page'] ) && $_GET['page'] === 'kata-plus-help' ? 'active' : '';
			$fonts_manager_active_class = isset( $_GET['page'] ) && $_GET['page'] === 'kata-plus-fonts-manager' ? 'active' : '';
			?>
			<?php if ( is_rtl() ) { ?>
				<style>
					#adminmenumain {
						transition: right .3s ease;
						-webkit-transition: right .3s ease;
						position: fixed;
						right: -200px;
						z-index: 9999;
					}

					#adminmenumain.active {
						right: 50px;
					}

					#wpcontent,
					#wpfooter {
						margin-right: 50px;
						padding-right: 20px;
					}
				</style>
			<?php } else { ?>
				<style>
					#adminmenumain {
						transition: left .3s ease;
						-webkit-transition: left .3s ease;
						position: fixed;
						left: -200px;
						z-index: 9999;
					}

					#adminmenumain.active {
						left: 50px;
					}

					#wpcontent,
					#wpfooter {
						margin-left: 50px;
						padding-left: 20px;
					}
				</style>
			<?php } ?>

			<?php
			$current_color = get_user_option( 'admin_color' );
			?>
			<div id="kata-admin-bar" class="kata-admin-bar <?php echo esc_attr( $current_color ); ?>">
				<ul id="kata-admin-bar-nav" class="kata-admin-bar-nav">
					<!-- Profile -->
					<li class="kata-admin-bar-nav-item<?php echo esc_attr( $is_profile_page ) ? ' active' : ''; ?>">
						<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['profile_url'] ); ?>">
							<div class="kata-admin-bar-avatar"><?php echo get_avatar( get_current_user_id(), 30 ); ?></div>
						</a>
						<div class="kata-admin-bar-contents">
							<ul>
								<li class="kata-admin-bar-item kata-admin-bar-curren-user-name">
									<?php echo esc_html( wp_get_current_user()->display_name ); ?>
								</li>
								<li class="kata-admin-bar-item kata-admin-bar-curren-user">
									<a href="<?php echo esc_url( home_url() ); ?>" target="_blank"><span></span><?php esc_html_e( 'Visit Site', 'kata-plus' ); ?></a>
								</li>
								<?php if ( get_post_type() ) : ?>
									<li class="kata-admin-bar-item kata-admin-bar-curren-user">
										<a href="<?php echo esc_url( get_the_permalink( get_the_ID() ) ); ?>" target="_blank"><span></span><?php esc_html_e( 'View Page', 'kata-plus' ); ?></a>
									</li>
								<?php endif; ?>
								<li class="kata-admin-bar-item kata-admin-bar-curren-user<?php echo esc_attr( $is_profile_page ) ? ' active' : ''; ?>">
									<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['profile_url'] ); ?>"><span></span><?php esc_html_e( 'Edit Profile', 'kata-plus' ); ?></a>
								</li>
								<li class="kata-admin-bar-item kata-admin-bar-curren-user">
									<a href="<?php echo esc_url( wp_logout_url() ); ?>"><span></span><?php esc_html_e( 'Log Out', 'kata-plus' ); ?></a>
								</li>
							</ul>
						</div> <!-- .kata-admin-bar-contents -->

					</li>

					<!-- Return to WordPress -->
					<li id="kata-admin-bar-return-to-wordpress" class="kata-admin-bar-nav-item">
						<i class="ti-wordpress"></i>
					</li>
					<?php
					// Get menu request from white labeling
					$white_label_manage_activation       = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->activation ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->activation ? 'hide' : 'show';
					$white_label_manage_demo_importer    = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->demo_importer ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->demo_importer ? 'hide' : 'show';
					$white_label_manage_plugin_manager   = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->plugin_manager ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->plugin_manager ? 'hide' : 'show';
					$white_label_manage_font_manager     = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->font_manager ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->font_manager ? 'hide' : 'show';
					$white_label_manage_customizer       = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->customizer ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->customizer ? 'hide' : 'show';
					$white_label_manage_template_library = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->template_library ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->template_library ? 'hide' : 'show';
					$white_label_manage_add_new          = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->add_new ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->add_new ? 'hide' : 'show';
					$white_label_manage_finder           = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->finder ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->finder ? 'hide' : 'show';
					$white_label_manage_help_menu        = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->help_menu ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->help_menu ? 'hide' : 'show';
					$white_label_manage_header_menu           = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->header ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->header ? 'hide' : 'show';
					$white_label_manage_heaeder_sticky_menu   = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->heaeder_sticky ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->heaeder_sticky ? 'hide' : 'show';
					$white_label_manage_footer_menu           = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->footer ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->footer ? 'hide' : 'show';
					$white_label_manage_blog_menu             = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->blog ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->blog ? 'hide' : 'show';
					$white_label_manage_single_menu           = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->single ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->single ? 'hide' : 'show';
					$white_label_manage_single_porfolio_menu  = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->portfolio ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->portfolio ? 'hide' : 'show';
					$white_label_manage_archive_porfolio_menu = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->archive_portfolio ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->archive_portfolio ? 'hide' : 'show';
					$white_label_manage_fast_mode_menu        = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->fast_mode ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->fast_mode ? 'hide' : 'show';
					$white_label_manage_archive_menu          = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->archive ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->archive ? 'hide' : 'show';
					$white_label_manage_author_menu           = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->author ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->author ? 'hide' : 'show';
					$white_label_manage_search_menu           = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->search ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->search ? 'hide' : 'show';
					$white_label_manage_error_404_menu        = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->error_404 ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->error_404 ? 'hide' : 'show';
					$white_label_manage_mega_menu_menu        = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->mega_menu ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->mega_menu ? 'hide' : 'show';
					?>

					<?php
					if (
						$white_label_manage_activation == 'show' ||
						$white_label_manage_demo_importer == 'show' ||
						$white_label_manage_plugin_manager == 'show' ||
						$white_label_manage_font_manager == 'show' ||
						$white_label_manage_customizer == 'show' ||
						$white_label_manage_template_library == 'show' ||
						$white_label_manage_add_new == 'show' ||
						$white_label_manage_finder == 'show' ||
						$white_label_manage_help_menu == 'show' ||
						$white_label_manage_header_menu == 'show' ||
						$white_label_manage_heaeder_sticky_menu == 'show' ||
						$white_label_manage_footer_menu == 'show' ||
						$white_label_manage_blog_menu == 'show' ||
						$white_label_manage_single_menu == 'show' ||
						$white_label_manage_archive_menu == 'show' ||
						$white_label_manage_author_menu == 'show' ||
						$white_label_manage_search_menu == 'show' ||
						$white_label_manage_error_404_menu == 'show' ||
						$white_label_manage_mega_menu_menu == 'show' ) {
						?>
						<!-- Settings -->
						<li class="kata-admin-bar-nav-item<?php echo esc_attr( $settings_active_class . ' ' . $fonts_manager_active_class ); ?>">
							<i class="ti-settings"></i>
							<div id="kata-admin-bar-settings" class="kata-admin-bar-contents">
								<?php if ( $white_label_manage_finder == 0 ) : ?>
									<div class="kata-admin-bar-finder">
										<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'search' ) ); ?>
										<span><?php esc_html_e( 'Finder', 'kata-plus' ); ?></span>
									</div>
								<?php endif; ?>
								<div class="kata-flex-grid">
									<div class="kata-col">
										<ul>
											<?php if ( $white_label_manage_header_menu == 'show' ) { ?>
												<!-- Header -->
												<li class="kata-admin-bar-item<?php echo esc_attr( $is_header_page ) ? ' active' : ''; ?>">
													<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['header_url'] ); ?>"><span></span><?php esc_html_e( 'Header', 'kata-plus' ); ?></a>
												</li>
											<?php } ?>
											<?php if ( $white_label_manage_heaeder_sticky_menu == 'show' ) { ?>
												<!-- Sticky Header -->
												<li class="kata-admin-bar-item<?php echo esc_attr( $is_header_sticky_page ) ? ' active' : ''; ?>">
													<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['header_sticky_url'] ); ?>"><span></span><?php esc_html_e( 'Sticky Header', 'kata-plus' ); ?></a>
												</li>
											<?php } ?>
											<?php if ( $white_label_manage_footer_menu == 'show' ) { ?>
												<!-- Footer -->
												<li class="kata-admin-bar-item<?php echo esc_attr( $is_footer_page ) ? ' active' : ''; ?>">
													<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['footer_url'] ); ?>"><span></span><?php esc_html_e( 'Footer', 'kata-plus' ); ?></a>
												</li>
											<?php } ?>
											<?php if ( $white_label_manage_error_404_menu == 'show' ) { ?>
												<!-- 404 -->
												<li class="kata-admin-bar-item<?php echo esc_attr( $is_404_page ) ? ' active' : ''; ?>">
													<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['_404_url'] ); ?>"><span></span><?php esc_html_e( '404', 'kata-plus' ); ?></a>
												</li>
											<?php } ?>
											<?php if ( $white_label_manage_mega_menu_menu == 'show' ) { ?>
												<!-- Mega Menu -->
												<li class="kata-admin-bar-item<?php echo esc_attr( $is_mega_menu_page ) ? ' active' : ''; ?>">
													<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['mega_menu_url'] ); ?>"><span></span><?php esc_html_e( 'Mega Menu', 'kata-plus' ); ?></a>
												</li>
											<?php } ?>
											<?php if ( $white_label_manage_single_porfolio_menu == 'show' ) { ?>
												<!-- Single Portfolio -->
												<li class="kata-admin-bar-item<?php echo esc_attr( $is_single_portfolio_page ) ? ' active' : ''; ?>">
													<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['single_portfolio_url'] ); ?>"><span></span><?php esc_html_e( 'Portfolio Single', 'kata-plus' ); ?></a>
												</li>
											<?php } ?>
										</ul>
									</div> <!-- .kata-col -->
									<div class="kata-col">
										<ul>
											<?php if ( $white_label_manage_blog_menu == 'show' ) { ?>
												<!-- Blog -->
												<li class="kata-admin-bar-item<?php echo esc_attr( $is_blog_page ) ? ' active' : ''; ?>">
													<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['blog_url'] ); ?>"><span></span><?php esc_html_e( 'Blog', 'kata-plus' ); ?></a>
												</li>
											<?php } ?>
											<?php if ( $white_label_manage_single_menu == 'show' ) { ?>
												<!-- Single -->
												<li class="kata-admin-bar-item<?php echo esc_attr( $is_single_page ) ? ' active' : ''; ?>">
													<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['single_url'] ); ?>"><span></span><?php esc_html_e( 'Single', 'kata-plus' ); ?></a>
												</li>
											<?php } ?>
											<?php if ( $white_label_manage_archive_menu == 'show' ) { ?>
												<!-- Archive -->
												<li class="kata-admin-bar-item<?php echo esc_attr( $is_archive_page ) ? ' active' : ''; ?>">
													<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['archive_url'] ); ?>"><span></span><?php esc_html_e( 'Archive', 'kata-plus' ); ?></a>
												</li>
											<?php } ?>
											<?php if ( $white_label_manage_author_menu == 'show' ) { ?>
												<!-- Author -->
												<li class="kata-admin-bar-item<?php echo esc_attr( $is_author_page ) ? ' active' : ''; ?>">
													<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['author_url'] ); ?>"><span></span><?php esc_html_e( 'Author', 'kata-plus' ); ?></a>
												</li>
											<?php } ?>
											<?php if ( $white_label_manage_search_menu == 'show' ) { ?>
												<!-- Search -->
												<li class="kata-admin-bar-item<?php echo esc_attr( $is_search_page ) ? ' active' : ''; ?>">
													<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['search_url'] ); ?>"><span></span><?php esc_html_e( 'Search', 'kata-plus' ); ?></a>
												</li>
											<?php } ?>
											<?php if ( $white_label_manage_archive_porfolio_menu == 'show' ) { ?>
												<!-- Single Portfolio -->
												<li class="kata-admin-bar-item<?php echo esc_attr( $is_archive_portfolio_page ) ? ' active' : ''; ?>">
													<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['archive_portfolio_url'] ); ?>"><span></span><?php esc_html_e( 'Portfolio Archive', 'kata-plus' ); ?></a>
												</li>
											<?php } ?>
											<?php if ( did_action( 'elementor/loaded' ) && is_plugin_active( 'learnpress/learnpress.php' ) ) { ?>
												<!-- Single Course -->
												<li class="course-builder kata-admin-bar-item<?php echo esc_attr( $is_single_portfolio_page ) ? ' active' : ''; ?>">
													<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['single_course_url'] ); ?>"><span></span><?php esc_html_e( 'Single Course', 'kata-plus' ); ?></a>
												</li>
											<?php } ?>
										</ul>
									</div> <!-- .kata-col -->
									<?php
									if (
										$white_label_manage_activation == 'show' ||
										$white_label_manage_demo_importer == 'show' ||
										$white_label_manage_plugin_manager == 'show' ||
										$white_label_manage_font_manager == 'show' ||
										$white_label_manage_customizer == 'show' ) :
										?>
										<div class="kata-col">
											<ul>
												<!-- Theme Activation -->
												<?php if ( $white_label_manage_activation == 'show' ) : ?>
													<li class="kata-admin-bar-item<?php echo esc_attr( $is_theme_activation_page ) ? ' active' : ''; ?>">
														<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['theme_activation_url'] ); ?>">
															<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'unlock' ) ); ?><?php esc_html_e( 'Activation', 'kata-plus' ); ?>
														</a>
													</li>
												<?php endif; ?>
												<?php if ( $white_label_manage_demo_importer == 'show' ) : ?>
													<!-- Demo Importer -->
													<li class="kata-admin-bar-item<?php echo esc_attr( $is_demo_importer_page ) ? ' active' : ''; ?>">
														<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['demo_importer_url'] ); ?>">
															<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'cloud-down' ) ); ?><?php esc_html_e( 'Demo Importer', 'kata-plus' ); ?>
														</a>
													</li>
												<?php endif; ?>
												<?php if ( $white_label_manage_plugin_manager == 'show' ) : ?>
													<!-- Plugin Manager -->
													<li class="kata-admin-bar-item<?php echo esc_attr( $is_plugin_manager_page ) ? ' active' : ''; ?>">
														<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['plugin_manager_url:static'] ); ?>">
															<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'plug' ) ); ?><?php esc_html_e( 'Plugin Manager', 'kata-plus' ); ?>
														</a>
													</li>
												<?php endif; ?>
												<?php if ( $white_label_manage_font_manager == 'show' ) : ?>
													<!-- Font Manager -->
													<li class="kata-admin-bar-item <?php echo esc_attr( $fonts_manager_active_class ); ?>">
														<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['fonts_manager_url:static'] ); ?>">
															<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'ink-pen' ) ); ?><?php esc_html_e( 'Font Manager', 'kata-plus' ); ?>
														</a>
													</li>
												<?php endif; ?>
												<?php if ( $white_label_manage_customizer == 'show' ) : ?>
													<!-- Options Page -->
													<li class="kata-admin-bar-item<?php echo esc_attr( $is_customizer_page ) ? ' active' : ''; ?>">
														<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['customizer_url'] ); ?>">
															<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'panel' ) ); ?><?php esc_html_e( 'Options', 'kata-plus' ); ?>
														</a>
													</li>
												<?php endif; ?>
												<?php if ( $white_label_manage_fast_mode_menu == 'show' ) { ?>
													<!-- Fast Mode -->
													<li class="kata-admin-bar-item">
														<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['fast_mode:static'] ); ?>">
															<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'time' ) ); ?><?php esc_html_e( 'Fast Mode', 'kata-plus' ); ?>
														</a>
													</li>
												<?php } ?>
											</ul>
										</div> <!-- .kata-col -->
									<?php endif ?>
								</div> <!-- .kata-flex-grid -->
							</div> <!-- .kata-admin-bar-contents -->
						</li>
					<?php } ?>

					<!-- Options (Customizer) -->
					<?php if ( $white_label_manage_customizer == 'show' ) : ?>
						<li class="kata-admin-bar-nav-item<?php echo esc_attr( $is_customizer_page ) ? ' active' : ''; ?>">
							<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['customizer_url'] ); ?>">
								<i class="ti-panel"></i>
							</a>
							<div class="kata-admin-bar-contents">
								<ul>
									<li class="kata-admin-bar-item<?php echo esc_attr( $is_customizer_page ) ? ' active' : ''; ?>">
										<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['customizer_url'] ); ?>"><span></span><?php esc_html_e( 'Options (Customizer)', 'kata-plus' ); ?></a>
									</li>
								</ul>
							</div> <!-- .kata-admin-bar-contents -->
						</li>
					<?php endif; ?>

					<!-- Templates -->
					<?php if ( did_action( 'elementor/loaded' ) && $white_label_manage_template_library == 'show' ) : ?>
						<?php if ( Plugin::$instance->editor->is_edit_mode() ) { ?>
							<li class="kata-admin-bar-nav-item kata-admin-bar-templates-btn kata-admin-bar-template-btn">
								<i class="ti-folder"></i>
								<div class="kata-admin-bar-contents">
									<ul>
										<li class="kata-admin-bar-item">
											<a href="#"><span></span><?php esc_html_e( 'Template Library', 'kata-plus' ); ?></a>
										</li>
									</ul>
								</div> <!-- .kata-admin-bar-contents -->
							</li>
						<?php } ?>
					<?php endif; ?>

					<!-- Add New -->
					<?php if ( $white_label_manage_add_new == 'show' ) : ?>
						<li class="kata-admin-bar-nav-item kata-admin-bar-nav-item-add-new">
							<i class="ti-plus"></i>
							<div class="kata-admin-bar-contents">
								<ul>
									<li class="kata-admin-bar-item">
										<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=page' ) ); ?>"><span></span><?php esc_html_e( 'New Page', 'kata-plus' ); ?></a>
									</li>
									<li class="kata-admin-bar-item">
										<a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><span></span><?php esc_html_e( 'New Post', 'kata-plus' ); ?></a>
									</li>
									<li class="kata-admin-bar-item">
										<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['add_new_kata_grid'] ); ?>"><span></span><?php esc_html_e( 'New Portfolio Item', 'kata-plus' ); ?></a>
									</li>
									<li class="kata-admin-bar-item">
										<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['add_new_kata_mega_menu'] ); ?>"><span></span><?php esc_html_e( 'New Mega Menu', 'kata-plus' ); ?></a>
									</li>
									<li class="kata-admin-bar-item">
										<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['add_new_kata_testimonial'] ); ?>"><span></span><?php esc_html_e( 'New Testimonial', 'kata-plus' ); ?></a>
									</li>
									<li class="kata-admin-bar-item">
										<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['add_new_kata_team_member'] ); ?>"><span></span><?php esc_html_e( 'New Team Member', 'kata-plus' ); ?></a>
									</li>
									<li class="kata-admin-bar-item">
										<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['add_new_kata_recipe'] ); ?>"><span></span><?php esc_html_e( 'New Recipe', 'kata-plus' ); ?></a>
									</li>
									<li class="kata-admin-bar-item">
										<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=elementor_library#add_new' ) ); ?>"><span></span><?php esc_html_e( 'New Template', 'kata-plus' ); ?></a>
									</li>
								</ul>
							</div> <!-- .kata-admin-bar-contents -->
						</li>
					<?php endif; ?>

					<!-- Finder -->
					<?php if ( $white_label_manage_finder == 'show' ) : ?>
						<li class="kata-admin-bar-nav-item kata-admin-bar-finder-btn">
							<i class="ti-search"></i>
						</li>
					<?php endif; ?>

					<!-- Help -->
					<?php if ( $white_label_manage_help_menu == 'show' ) : ?>
						<li class="kata-admin-bar-nav-item <?php echo esc_attr( $help_active_class ); ?>">
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-help' ) ); ?>">
								<i class="ti-help-alt"></i>
							</a>
							<div class="kata-admin-bar-contents">
								<ul>
									<li class="kata-admin-bar-item">
										<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-help' ) ); ?>"><span></span><?php esc_html_e( 'Help', 'kata-plus' ); ?> </a>
									</li>
								</ul>
							</div> <!-- .kata-admin-bar-contents -->
						</li>
					<?php endif; ?>
					<li class="kata-admin-bar-nav-item collapsmenu">
						<i class="ti-angle-left"></i>
					</li>

				</ul> <!-- #kata-admin-bar-nav -->
			</div> <!-- #kata-admin-bar -->
			<?php
		}

		public static function finder() {
			if ( ! class_exists( 'Kata_Plus_Helpers' ) ) {
				return;
			}
			$header_url        = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['header_url'];
			$header_sticky_url = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['header_sticky_url'];
			$footer_url        = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['footer_url'];
			$blog_url          = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['blog_url'];
			$single_url        = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['single_url'];
			$archive_url       = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['archive_url'];
			$author_url        = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['author_url'];
			$search_url        = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['search_url'];
			$mega_menu_url     = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['mega_menu_url'];
			$_404_url          = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['_404_url'];
			$help_url          = apply_filters( 'kata_plus_submenu_url', self::$pages_url )['help_url'];
			?>

			<div id="kata-finder" class="kata-dialog kata-finder">
				<h5 class="kata-dialog-header">
					<span><?php esc_html_e( 'Finder', 'kata-plus' ); ?></span>
					<span class="kata-dialog-close-btn"><i class="ti-close"></i></span>
				</h5> <!-- .kata-dialog-header -->
				<div class="kata-dialog-body">
					<div class="kata-finder-search">
						<i class="eicon-search"></i>
						<input type="text" id="kata-finder-search-input" placeholder="<?php esc_html_e( 'Type to find anything', 'kata-plus' ); ?>">
					</div>

					<!-- Pages -->
					<?php
					$pages = self::pages();
					if ( $pages ) :
						?>
						<h6 class="kata-finder-category-title"><?php esc_html_e( 'Edit Pages', 'kata-plus' ); ?></h6>
					<?php endif; ?>
					<div class="kata-finder-category-items">
						<?php
						foreach ( $pages as $page ) {
							echo wp_kses_post( $page );
						}
						?>
					</div> <!-- .kata-finder-category-items -->

					<!-- Templates -->
					<?php
					$templates = self::templates();
					if ( $templates ) :
						?>
						<h6 class="kata-finder-category-title"><?php esc_html_e( 'Edit Templates', 'kata-plus' ); ?></h6>
					<?php endif; ?>
					<div class="kata-finder-category-items">
						<?php

						foreach ( $templates as $template ) {
							echo wp_kses_post( $template );
						}
						?>
					</div> <!-- .kata-finder-category-items -->

					<!-- Edit Builders -->
					<h6 class="kata-finder-category-title"><?php esc_html_e( 'Builders', 'kata-plus' ); ?></h6>
					<div class="kata-finder-category-items">
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( $header_url ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'layout-tab-window' ) ) . esc_html__( 'Header', 'kata-plus' ); ?>
							</a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( $header_sticky_url ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'layout-tab-window' ) ) . esc_html__( 'Sticky Header', 'kata-plus' ); ?>
							</a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( $footer_url ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'layout-accordion-merged' ) ) . esc_html__( 'Footer', 'kata-plus' ); ?>
							</a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( $_404_url ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'close' ) ) . esc_html__( '404', 'kata-plus' ); ?>
							</a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( $mega_menu_url ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'eicons', 'nav-menu' ) ) . esc_html__( 'Mega Menu', 'kata-plus' ); ?>
							</a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( $blog_url ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'eicons', 'posts-grid' ) ) . esc_html__( 'Blog', 'kata-plus' ); ?>
							</a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( $single_url ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'eicons', 'post-excerpt' ) ) . esc_html__( 'Single', 'kata-plus' ); ?>
							</a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( $archive_url ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'eicons', 'archive-posts' ) ) . esc_html__( 'Archive', 'kata-plus' ); ?>
							</a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( $author_url ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'pencil' ) ) . esc_html__( 'Author', 'kata-plus' ); ?>
							</a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( $search_url ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'eicons', 'site-search' ) ) . esc_html__( 'Search', 'kata-plus' ); ?>
							</a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['single_portfolio_url'] ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'eicons', 'posts-justified' ) ) . esc_html__( 'Portfolio', 'kata-plus' ); ?>
							</a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['archive_portfolio_url'] ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'eicons', 'posts-justified' ) ) . esc_html__( 'Portfolio Archive', 'kata-plus' ); ?>
							</a>
						</div>
						<?php if ( did_action( 'elementor/loaded' ) && is_plugin_active( 'learnpress/learnpress.php' ) ) { ?>
							<div class="kata-finder-category-item">
								<a href="<?php echo esc_url( $single_course_url ); ?>">
									<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'pencil' ) ) . esc_html__( 'Course', 'kata-plus' ); ?>
								</a>
							</div>
						<?php } ?>
					</div> <!-- .kata-finder-category-items -->

					<!-- Posts -->
					<?php
					$posts = self::posts();
					if ( $posts ) :
						?>
						<h6 class="kata-finder-category-title"><?php esc_html_e( 'Edit Posts', 'kata-plus' ); ?></h6>
					<?php endif; ?>
					<div class="kata-finder-category-items">
						<?php
						foreach ( $posts as $post ) {
							echo wp_kses_post( $post );
						}
						?>
					</div> <!-- .kata-finder-category-items -->

					<!-- Add New -->
					<h6 class="kata-finder-category-title"><?php esc_html_e( 'Add New', 'kata-plus' ); ?></h6>
					<div class="kata-finder-category-items">
						<?php
						$new_list = self::new_list();
						foreach ( $new_list as $new ) {
							echo wp_kses_post( $new );
						}
						?>
					</div> <!-- .kata-finder-category-items -->

					<!-- Dashboard -->
					<h6 class="kata-finder-category-title"><?php esc_html_e( 'Dashboard', 'kata-plus' ); ?></h6>
					<div class="kata-finder-category-items">
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-theme-activation' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'unlock' ) ); ?><?php esc_html_e( 'Activation', 'kata-plus' ); ?>
							</a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-demo-importer' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'cloud-down' ) ); ?><?php esc_html_e( 'Demo Importer', 'kata-plus' ); ?>
							</a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['plugin_manager_url'] ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'plug' ) ); ?><?php esc_html_e( 'Plugin Manager', 'kata-plus' ); ?>
							</a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( apply_filters( 'kata_plus_submenu_url', self::$pages_url )['fonts_manager_url'] ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'ink-pen' ) ); ?><?php esc_html_e( 'Font Manager', 'kata-plus' ); ?>
							</a>
						</div>
					</div> <!-- .kata-finder-category-items -->

					<!-- Options -->
					<h6 class="kata-finder-category-title"><?php esc_html_e( 'Options', 'kata-plus' ); ?></h6>
					<div class="kata-finder-category-items">
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'panel' ) ) . esc_html__( 'Options (Customizer)', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bsection%5D=static_front_page' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'home' ) ) . esc_html__( 'Homepage Settings', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bsection%5D=kata_preloader_section' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'reload' ) ) . esc_html__( 'Preloader', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bsection%5D=kata_container_section' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'layout' ) ) . esc_html__( 'Container', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bpanel%5D=kata_page_panel' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'write' ) ) . esc_html__( 'Page', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bsection%5D=kata_page_title_section' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'write' ) ) . esc_html__( 'Page Title', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bsection%5D=kata_page_sidebar_section' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'write' ) ) . esc_html__( 'Sidebar', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bsection%5D=styling_typography_section' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'palette' ) ) . esc_html__( 'Styling & Typography', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bpanel%5D=nav_menus' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'layout-menu-v' ) ) . esc_html__( 'Menus', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bpanel%5D=widgets' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'widget' ) ) . esc_html__( 'Widgets', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bpanel%5D=kata_scroll_panel' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'mouse' ) ) . esc_html__( 'Scroll', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bsection%5D=kata_back_to_top_button_section' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'mouse' ) ) . esc_html__( 'Back To Top', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bsection%5D=kata_smooth_scroll_section' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'mouse' ) ) . esc_html__( 'Smooth Scroll', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bsection%5D=kata_scroll_bar_section' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'mouse' ) ) . esc_html__( 'Scroll Bar', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bpanel%5D=kata_api_panels' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'shield' ) ) . esc_html__( 'API', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bsection%5D=kata_gdpr_section' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'lock' ) ) . esc_html__( 'GDPR', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bsection%5D=kata_white_label_section' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'bookmark-alt' ) ) . esc_html__( 'White Label', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bsection%5D=kata_custom_codes_options' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'shortcode' ) ) . esc_html__( 'Custom Codes', 'kata-plus' ); ?></a>
						</div>
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus%5Bsection%5D=custom_css' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'brush-alt' ) ) . esc_html__( 'Additional CSS', 'kata-plus' ); ?></a>
						</div>
					</div> <!-- .kata-finder-category-items -->

					<!-- Help -->
					<h6 class="kata-finder-category-title"><?php esc_html_e( 'Help', 'kata-plus' ); ?></h6>
					<div class="kata-finder-category-items">
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( $help_url ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'help-alt' ) ) . esc_html__( 'Help', 'kata-plus' ); ?></a>
						</div>
					</div> <!-- .kata-finder-category-items -->

					<!-- Profile -->
					<h6 class="kata-finder-category-title"><?php esc_html_e( 'Profile', 'kata-plus' ); ?></h6>
					<div class="kata-finder-category-items">
						<div class="kata-finder-category-item">
							<a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>">
								<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( 'themify', 'user' ) ) . esc_html__( 'Edit My Profile', 'kata-plus' ); ?></a>
						</div>
					</div> <!-- .kata-finder-category-items -->

				</div> <!-- .kata-dialog-body -->
			</div> <!-- .kata-dialog -->
			<?php
		}

		public static function new_list() {
			$new_list                = array();
			$new_list['page']        = '
			<div class="kata-finder-category-item">
				<a href="' . esc_url( admin_url( 'post-new.php?post_type=page' ) ) . '">' . Kata_Plus_Helpers::get_icon( 'themify', 'plus' ) . esc_html__( 'Add New Page', 'kata-plus' ) . '</a>
			</div>';
			$new_list['post']        = '
			<div class="kata-finder-category-item">
				<a href="' . esc_url( admin_url( 'post-new.php' ) ) . '">' . Kata_Plus_Helpers::get_icon( 'themify', 'plus' ) . esc_html__( 'Add New Post', 'kata-plus' ) . '</a>
			</div>';
			$new_list['grid']        = '
			<div class="kata-finder-category-item">
				<a href="' . esc_url( admin_url( 'post-new.php?post_type=kata_grid' ) ) . '">' . Kata_Plus_Helpers::get_icon( 'themify', 'plus' ) . esc_html__( 'New Gallery Item', 'kata-plus' ) . '</a>
			</div>';
			$new_list['testimonial'] = '
			<div class="kata-finder-category-item">
				<a href="' . esc_url( admin_url( 'post-new.php?post_type=kata_testimonial' ) ) . '">' . Kata_Plus_Helpers::get_icon( 'themify', 'plus' ) . esc_html__( 'New Testimonial', 'kata-plus' ) . '</a>
			</div>';
			$new_list['team_member'] = '
			<div class="kata-finder-category-item">
				<a href="' . esc_url( admin_url( 'post-new.php?post_type=kata_team_member' ) ) . '">' . Kata_Plus_Helpers::get_icon( 'themify', 'plus' ) . esc_html__( 'New Team Member', 'kata-plus' ) . '</a>
			</div>';
			$new_list['recipe']      = '
			<div class="kata-finder-category-item">
				<a href="' . esc_url( admin_url( 'post-new.php?post_type=kata_recipe' ) ) . '">' . Kata_Plus_Helpers::get_icon( 'themify', 'plus' ) . esc_html__( 'New Recipe', 'kata-plus' ) . '</a>
			</div>';

			return $new_list;
		}

		public static function pages() {
			global $wpdb;
			$pages      = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", 'page' ), ARRAY_A );
			$pages_list = array();
			foreach ( $pages as $page ) {
				$pages_list[] = '
				<div class="kata-finder-category-item">
				<a href="' . esc_url( admin_url( 'post.php?post=' . $page['ID'] . '&action=elementor' ) ) . '">' . Kata_Plus_Helpers::get_icon( 'themify', 'write' ) . esc_html( $page['post_title'] ) . '</a>
				</div>';
			}

			return $pages_list;
		}

		public static function templates() {
			global $wpdb;
			$templates      = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", 'elementor_library' ), ARRAY_A );
			$templates_list = array();
			foreach ( $templates as $template ) {
				$templates_list[] = '
				<div class="kata-finder-category-item">
				<a href="' . esc_url( admin_url( 'post.php?post=' . $template['ID'] . '&action=elementor' ) ) . '">' . Kata_Plus_Helpers::get_icon( 'themify', 'write' ) . esc_html( $template['post_title'] ) . '</a>
				</div>';
			}

			return $templates_list;
		}

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
		 * Register admin panel menu.
		 *
		 * @since   1.0.0
		 */
		public function admin_menu() {

			// Get Name & Logo From White Labeling Customizer
			$white_label_wp_name = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) ? Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->name : '';
			$white_label_url     = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) ? Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->url : '';
			// Get Options for showing the sub menu on dashboard
			$white_label_demo_importer     = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->demo_importer ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->demo_importer ? 'hide' : 0;
			$white_label_plugins           = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->plugins ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->plugins ? 'hide' : 0;
			$white_label_header            = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->header ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->header ? 'hide' : 0;
			$white_label_heaeder_sticky    = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->heaeder_sticky ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->heaeder_sticky ? 'hide' : 0;
			$white_label_footer            = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->footer ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->footer ? 'hide' : 0;
			$white_label_single            = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->single ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->single ? 'hide' : 0;
			$white_label_single_portfolio  = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->portfolio ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->portfolio ? 'hide' : 0;
			$white_label_archive_portfolio = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->archive_portfolio ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->archive_portfolio ? 'hide' : 0;
			$white_label_blog              = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->blog ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->blog ? 'hide' : 0;
			$white_label_archive           = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->archive ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->archive ? 'hide' : 0;
			$white_label_error_404         = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->error_404 ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->error_404 ? 'hide' : 0;
			$white_label_author            = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->author ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->author ? 'hide' : 0;
			$white_label_search            = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->search ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->search ? 'hide' : 0;
			$white_label_mega_menu         = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->mega_menu ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->mega_menu ? 'hide' : 0;
			$white_label_help              = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->help ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->help ? 'hide' : 0;
			$white_label_fast_mode         = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->fast_mode ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->fast_mode ? 'hide' : 0;
			$white_label_font_manager      = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->font_manager ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->font_manager ? 'hide' : 0;
			$white_label_customizer        = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && isset( Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->customizer ) && '1' == Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->customizer ? 'hide' : 0;
			// If Set Image return new image address
			if ( $white_label_url ) :
				$admin_logo = $white_label_url;
			else :
				$admin_logo = Kata_Plus::$assets . 'images/admin/kata-icon.svg';
			endif;
			if ( $white_label_wp_name ) :
				$admin_name = $white_label_wp_name;
			else :
				$admin_name = class_exists( 'Kata_Plus_Helpers' ) ? Kata_Plus_Helpers::get_theme()->name : __( 'Kata', 'kata-plus' );
			endif;
			add_menu_page(
				esc_html( $admin_name ),
				esc_html( 'Kata' ),
				$this->capability(),
				'kata-plus-theme-activation',
				array( $this, 'dashboard_page' ),
				$admin_logo,
				'2'
			);
			// Demo Importer page
			if ( $white_label_demo_importer == 0 ) :
				add_submenu_page(
					'kata-plus-theme-activation',
					esc_html__( 'Demo Importer', 'kata-plus' ),
					esc_html__( 'Demo Importer', 'kata-plus' ),
					$this->capability(),
					'kata-plus-demo-importer',
					array( $this, 'demo_importer_page' )
				);
			endif;
			// Install Plugins page
			if ( $white_label_plugins == 0 ) :
				add_submenu_page(
					'kata-plus-theme-activation',
					esc_html__( 'Plugin Manager', 'kata-plus' ),
					esc_html__( 'Plugin Manager', 'kata-plus' ),
					$this->capability(),
					apply_filters( 'kata_plus_submenu_url', self::$pages_url )['plugin_manager_url'],
					apply_filters( 'kata_plus_submenu_url', self::$pages_url )['plugin_manager_func']
				);
			endif;
			if ( $white_label_font_manager == 0 ) :
				add_submenu_page(
					'kata-plus-theme-activation',
					esc_html__( 'Font Manager', 'kata-plus' ),
					esc_html__( 'Font Manager', 'kata-plus' ),
					$this->capability(),
					apply_filters( 'kata_plus_submenu_url', self::$pages_url )['fonts_manager_url'],
					apply_filters( 'kata_plus_submenu_url', self::$pages_url )['fonts_manager_func']
				);
				do_action( 'fonts_manager_current_controller' );
			endif;
			if ( $white_label_customizer == 0 ) :
				add_submenu_page(
					'kata-plus-theme-activation',
					esc_html__( 'Options', 'kata-plus' ),
					esc_html__( 'Options', 'kata-plus' ),
					$this->capability(),
					'customize.php'
				);
			endif;
			if ( did_action( 'elementor/loaded' ) ) {
				// Header
				if ( $white_label_header == 0 ) :
					add_submenu_page(
						'kata-plus-theme-activation',
						esc_html__( 'Header', 'kata-plus' ),
						esc_html__( 'Header', 'kata-plus' ),
						'edit_others_posts',
						self::$pages_url['kata_header']
					);
				endif;
				// Sticky Header
				if ( $white_label_heaeder_sticky == 0 ) :
					add_submenu_page(
						'kata-plus-theme-activation',
						esc_html__( 'Sticky Header', 'kata-plus' ),
						esc_html__( 'Sticky Header', 'kata-plus' ),
						'edit_others_posts',
						apply_filters( 'kata_plus_submenu_url', self::$pages_url )['kata_sticky_header']
					);
				endif;
				// Footer
				if ( $white_label_footer == 0 ) :
					add_submenu_page(
						'kata-plus-theme-activation',
						esc_html__( 'Footer', 'kata-plus' ),
						esc_html__( 'Footer', 'kata-plus' ),
						'edit_others_posts',
						self::$pages_url['kata_footer']
					);
				endif;
				// Blog
				if ( $white_label_blog == 0 ) :
					add_submenu_page(
						'kata-plus-theme-activation',
						esc_html__( 'Blog', 'kata-plus' ),
						esc_html__( 'Blog', 'kata-plus' ),
						'edit_others_posts',
						self::$pages_url['kata_blog']
					);
				endif;
				// Single
				if ( $white_label_single == 0 ) :
					add_submenu_page(
						'kata-plus-theme-activation',
						esc_html__( 'Single Post', 'kata-plus' ),
						esc_html__( 'Single Post', 'kata-plus' ),
						'edit_others_posts',
						apply_filters( 'kata_plus_submenu_url', self::$pages_url )['kata_single_post']
					);
				endif;
				// Single Portfolio
				if ( $white_label_single_portfolio == 0 ) :
					add_submenu_page(
						'kata-plus-theme-activation',
						esc_html__( 'Single Portfolio', 'kata-plus' ),
						esc_html__( 'Single Portfolio', 'kata-plus' ),
						'edit_others_posts',
						apply_filters( 'kata_plus_submenu_url', self::$pages_url )['kata_single_portfolio']
					);
				endif;
				// Portfolio Archive
				if ( $white_label_archive_portfolio == 0 ) :
					add_submenu_page(
						'kata-plus-theme-activation',
						esc_html__( 'Portfolio Archive', 'kata-plus' ),
						esc_html__( 'Portfolio Archive', 'kata-plus' ),
						'edit_others_posts',
						apply_filters( 'kata_plus_submenu_url', self::$pages_url )['kata_archive_portfolio']
					);
				endif;
				// Single Course
				if ( did_action( 'elementor/loaded' ) && is_plugin_active( 'learnpress/learnpress.php' ) ) :
					add_submenu_page(
						'kata-plus-theme-activation',
						esc_html__( 'Single Course', 'kata-plus' ),
						esc_html__( 'Single Course', 'kata-plus' ),
						'edit_others_posts',
						apply_filters( 'kata_plus_submenu_url', self::$pages_url )['kata_single_course']
					);
				endif;
				// Archive
				if ( $white_label_archive == 0 ) :
					add_submenu_page(
						'kata-plus-theme-activation',
						esc_html__( 'Archive', 'kata-plus' ),
						esc_html__( 'Archive', 'kata-plus' ),
						'edit_others_posts',
						apply_filters( 'kata_plus_submenu_url', self::$pages_url )['kata_archive']
					);
				endif;
				// 404
				if ( $white_label_error_404 == 0 ) :
					add_submenu_page(
						'kata-plus-theme-activation',
						esc_html__( '404', 'kata-plus' ),
						esc_html__( '404', 'kata-plus' ),
						'edit_others_posts',
						apply_filters( 'kata_plus_submenu_url', self::$pages_url )['kata_404']
					);
				endif;
				// Author
				if ( $white_label_author == 0 ) :
					add_submenu_page(
						'kata-plus-theme-activation',
						esc_html__( 'Author', 'kata-plus' ),
						esc_html__( 'Author', 'kata-plus' ),
						'edit_others_posts',
						apply_filters( 'kata_plus_submenu_url', self::$pages_url )['kata_author']
					);
				endif;
				// Search
				if ( $white_label_search == 0 ) :
					add_submenu_page(
						'kata-plus-theme-activation',
						esc_html__( 'Search', 'kata-plus' ),
						esc_html__( 'Search', 'kata-plus' ),
						'edit_others_posts',
						apply_filters( 'kata_plus_submenu_url', self::$pages_url )['kata_search']
					);
				endif;
				// Mega Menu
				if ( $white_label_mega_menu == 0 ) :
					add_submenu_page(
						'kata-plus-theme-activation',
						esc_html__( 'Mega Menu', 'kata-plus' ),
						esc_html__( 'Mega Menu', 'kata-plus' ),
						'edit_others_posts',
						apply_filters( 'kata_plus_submenu_url', self::$pages_url )['mega_menu_url']
					);
				endif;
			}

			if ( $white_label_help == 0 ) :
				add_submenu_page(
					'kata-plus-theme-activation',
					esc_html__( 'Help', 'kata-plus' ),
					esc_html__( 'Help', 'kata-plus' ),
					$this->capability(),
					'kata-plus-help',
					array( $this, 'help_page' )
				);
			endif;

			add_submenu_page(
				'kata-plus-theme-activation',
				esc_html__( 'Fast Mode', 'kata-plus' ),
				esc_html__( 'Fast Mode', 'kata-plus' ),
				$this->capability(),
				apply_filters( 'kata_plus_submenu_url', self::$pages_url )['fast_mode'],
				apply_filters( 'kata_plus_submenu_url', self::$pages_url )['fast_mode_func']
			);

			add_submenu_page(
				'kata-plus-theme-activation',
				esc_html__( 'Rollback', 'kata-plus' ),
				esc_html__( 'Rollback', 'kata-plus' ),
				$this->capability(),
				'kata-plus-rollback',
				array( $this, 'rollback_page' )
			);

			if ( ! isset( $_GET['step'] ) && isset( $_GET['page'] ) && $_GET['page'] == apply_filters( 'kata_plus_submenu_url', self::$pages_url )['fast_mode'] ) {
				if ( wp_safe_redirect( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=business' ) ) ) {
					exit;
				}
			}

			do_action( 'kata_plus_add_submenu_page' );
		}

		/**
		 * Create intro submenu.
		 *
		 * @since   1.0.0
		 */
		public function create_dashboard_submenu() {
			global $submenu;
			if ( current_user_can( $this->capability() ) ) {
				$submenu['kata-plus-theme-activation'][0][0] = esc_html__( 'Activation', 'kata-plus' );
			}
		}

		/**
		 * Hide admin notices.
		 *
		 * @since   1.0.0
		 */
		public function hide_admin_notices() {
			if ( ! isset( $_GET['page'] ) ) {
				return;
			}
			$page = self::current_screen();
			if ( is_super_admin() ) {
				if ( $page['base_name'] === 'theme activation' ||
				$page['base_name'] === 'demo importer' ||
				$page['base_name'] === 'plugins' ||
				$page['base_name'] === 'fast mode' ||
				$page['base_name'] === 'fonts manager' ) {
					remove_all_actions( 'admin_notices' );
				}
			}
		}

		/**
		 * Enqueue Styles.
		 *
		 * @since   1.0.0
		 */
		public function enqueue_styles() {
			$this->hide_admin_notices();

			wp_enqueue_style( 'kata-plus-admin', Kata_Plus::$assets . 'css/backend/admin.css', array(), Kata_Plus::$version );

			if ( is_rtl() ) {
				wp_enqueue_style( 'kata-plus-admin-rtl', Kata_Plus::$assets . 'css/backend/admin-rtl.css', array(), Kata_Plus::$version );
			}
			if ( self::current_screen()['base_name'] == 'install plugins' ) {
				wp_enqueue_style( 'kata-plus-plugin-manager', Kata_Plus::$assets . 'css/backend/plugins.css', array(), Kata_Plus::$version );
			}
			wp_enqueue_style( 'kata-plus-admin-custom', Kata_Plus::$upload_dir_url . '/css/admin-custom.css', array(), Kata_Plus::$version );
			wp_enqueue_style( 'kata-plus-admin-bar', Kata_Plus::$assets . 'css/backend/admin-bar.css', array(), Kata_Plus::$version );
			if ( is_rtl() ) {
				wp_enqueue_style( 'kata-plus-admin-bar-rtl', Kata_Plus::$assets . 'css/backend/admin-bar-rtl.css', array(), Kata_Plus::$version );
			}
			wp_enqueue_style( 'themify', Kata_Plus::$assets . 'css/libraries/themify-icons.css', array(), Kata_Plus::$version );
			wp_enqueue_style( 'kata-plus-icon-control', Kata_Plus::$assets . 'css/backend/elementor-icon-control.css', array(), Kata_Plus::$version );
		}

		/**
		 * Enqueue Scripts.
		 *
		 * @since   1.0.0
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( 'kata-plus-admin-nicescroll', Kata_Plus::$assets . 'js/libraries/nicescroll.js', array( 'jquery' ), Kata_Plus::$version, true );
			wp_enqueue_script( 'kata-plus-admin-bar', Kata_Plus::$assets . 'js/backend/admin-bar.js', array( 'jquery' ), Kata_Plus::$version, true );
			ob_start();

			if ( self::current_screen()['base_name'] == 'help' || 'theme activation' == self::current_screen()['base_name'] ) {
				?>
					var APP_ID = 'v6xp5fdh';
					window.intercomSettings = { app_id: APP_ID };
					(function(){var w=window;var ic=w.Intercom;if(typeof ic==='function'){ic('reattach_activator');ic('update',w.intercomSettings);}else{var d=document;var i=function(){i.c(arguments);};i.q=[];i.c=function(args){i.q.push(args);};w.Intercom=i;var l=function(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/' + APP_ID;var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);};if(document.readyState==='complete'){l();}else if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
					jQuery('.kata-help-intro').find('a.chat-link').on('click', function (e) { e.preventDefault(); var $this = jQuery(this); if ($this.hasClass('is-open')) { $this.removeClass('is-open'); Intercom('hide'); } else { $this.addClass('is-open'); Intercom('showMessages'); } });
				<?php
			}
			$inline_script = ob_get_clean();

			wp_add_inline_script( 'kata-plus-admin-bar', $inline_script );
			wp_enqueue_script( 'kata-plus-custom-msg', Kata_Plus::$assets . 'js/backend/custom-msg.js', array( 'jquery' ), Kata_Plus::$version, true );
			wp_localize_script(
				'kata-plus-admin-bar',
				'kata_plus_admin_localize',
				array(
					'ajax'      => array(
						'url'   => admin_url( 'admin-ajax.php' ),
						'nonce' => wp_create_nonce( 'kata_plus_admin_nonce' ),
					),
					'translate' => array(
						'activation' => array(
							'empty_input' => esc_html__( 'Please enter the purchase code.', 'kata-plus' ),
							'back'        => esc_html__( 'Back', 'kata-plus' ),
							'well'        => esc_html__( 'Well Done', 'kata-plus' ),
							'failed'      => esc_html__( 'There is a problem, please try again.', 'kata-plus' ),
							'not_valid'   => esc_html__( 'This license is not valid.', 'kata-plus' ),
							'success'     => esc_html__( 'Thank you for choosing Kata. Your theme was activated successfully.', 'kata-plus' ),
						),
					),
				)
			);
		}

		/**
		 * prefers color scheme.
		 *
		 * @since   1.0.0
		 */
		public function prefers_color_scheme() {
			check_ajax_referer( 'kata_plus_admin_nonce', 'nonce' );
			$kata_options = get_option( 'kata_options' );
			$color_mode   = sanitize_text_field( $_POST['color_mode'] );
			if ( $color_mode ) {
				$option                         = get_option( 'kata_options' );
				$option['prefers_color_scheme'] = sanitize_text_field( $color_mode );
				update_option( 'kata_options', $option );
			}
			wp_die();
		}

		/**
		 * Dashboard page.
		 *
		 * @since   1.0.0
		 */
		public function dashboard_page() {
			require_once self::$dir . 'views/dashboard-page.php';
		}

		/**
		 * Demo Importer page.
		 *
		 * @since   1.0.0
		 */
		public function demo_importer_page() {
			require_once self::$dir . 'views/demo-importer-page.php';
		}

		/**
		 * Install Plugins page.
		 *
		 * @since   1.0.0
		 */
		public function help_page() {
			require_once self::$dir . 'views/help-page.php';
		}

		/**
		 * Update styler data page.
		 *
		 * @since   1.3.0
		 */
		public function data_update() {
			require_once self::$dir . 'views/data-update.php';
		}

		/**
		 * Rollback page.
		 *
		 * @since   1.3.0
		 */
		public function rollback_page() {
			require_once self::$dir . 'views/rollback-page.php';
		}

		/**
		 * Current Screen
		 *
		 * @since   1.0.0
		 */
		public static function current_screen() {
			$current_screen    = get_current_screen();
			$data              = array();

			$data['base_name'] = str_replace(
				array( 'toplevel_page_kata-plus-', 'kata_page_kata-plus-', 'Toplevel_page_kata Plus', '-' ),
				array( '', '', '', ' ' ),
				get_current_screen()->base
			);

			if ( isset( $_GET['page'] ) ) {
				switch( $_GET['page'] ) {
					case 'kata-plus-theme-activation':
						$data['title'] = __( 'theme activation', 'kata-plus' );
						break;

					case 'kata-plus-demo-importer':
						$data['title'] = __( 'demo importer', 'kata-plus' );
						break;

					case 'kata-plus-plugins':
						$data['title'] = __( 'plugin manager', 'kata-plus' );
						break;

					case 'kata-plus-fonts-manager':
						$data['title'] = __( 'font manager', 'kata-plus' );
						break;

				}
			}
			return $data;
		}

		/**
		 * Go pro Submenu.
		 *
		 * @since   1.0.0
		 */
		public function GoPro() {
			global $submenu;
			add_submenu_page(
				'kata-plus-theme-activation',
				esc_html__( 'Go Pro', 'kata-plus' ),
				esc_html__( 'Go Pro', 'kata-plus' ),
				'edit_others_posts',
				esc_url( 'https://my.climaxthemes.com/buy/' )
			);
		}

		/**
		 * Header of admin panel pages.
		 *
		 * @since   1.0.0
		 */
		public function header() {
			$selected            = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : 'kata-plus-theme-activation';
			$white_label         = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) ? Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->name : '';
			$white_label_version = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) ? Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->version : '';
			$theme_logo_label    = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) ? Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->url : '';
			if ( $theme_logo_label ) {
				$theme_logo_label_style = 'style=background-image:url(' . $theme_logo_label . ');';
			} else {
				$theme_logo_label_style = '';
			}
			?>
			<div class="kata-container">
				<div class="kata-row">
					<div class="kata-col-sm-12">
						<?php $page_title = isset( self::current_screen()['title'] ) ? self::current_screen()['title'] : ''; ?>
						<h1>
							<?php
							if ( ! $white_label ) :
								echo esc_html( $page_title );
							else :
								echo esc_html( $white_label ) . ' ' . esc_html( $page_title );
							endif;
							?>
						</h1>
						<?php if ( 'kata-plus-theme-activation' == sanitize_text_field( $_GET['page'] ) ) { ?>
							<div class="about-text"><?php echo esc_html__( 'Kata WordPress theme activation.', 'kata-plus' ); ?></div>
						<?php } ?>
						<?php if ( 'kata-plus-demo-importer' == sanitize_text_field( $_GET['page'] ) ) { ?>
							<div class="about-text"><?php echo esc_html__( 'Just import one of the following demos and your website will be ready.', 'kata-plus' ); ?></div>
						<?php } ?>
						<?php if ( 'kata-plus-plugins' == sanitize_text_field( $_GET['page'] ) ) { ?>
							<div class="about-text"><?php echo esc_html__( 'Install recommended plugins.', 'kata-plus' ); ?></div>
						<?php } ?>
						<?php if ( 'kata-plus-fonts-manager' == sanitize_text_field( $_GET['page'] ) ) { ?>
							<div class="about-text"><?php echo esc_html__( 'Add & Manage fonts from all different sources like Google Fonts.', 'kata-plus' ); ?></div>
						<?php } ?>
						<?php if ( 'kata-plus-help' == sanitize_text_field( $_GET['page'] ) ) { ?>
							<div class="about-text"><?php echo esc_html__( 'Create your awesome website, fast and easy.', 'kata-plus' ); ?></div>
						<?php } ?>
						<?php if ( 'kata-plus-update' == sanitize_text_field( $_GET['page'] ) ) { ?>
							<div class="about-text"><?php echo esc_html__( 'Convert Old Data Format to New Version.', 'kata-plus' ); ?></div>
						<?php } ?>
						<div class="wp-badge" <?php echo esc_attr( $theme_logo_label_style ); ?>>
							<?php
							if ( ! $white_label_version ) :
								/* translators: %s is replaced with Kata_Plus::$version */
								echo( esc_html__( 'v ', 'kata-plus' ) . esc_html( Kata_Plus::$version ) );
							else :
								/* translators: %s is replaced with $white_label_version */
								echo( esc_html__( 'v ', 'kata-plus' ) . esc_html( $white_label_version ) );
							endif;
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		static function _wp_menu_output( $menu, $submenu, $submenu_as_parent = true ) {
			global $self, $parent_file, $submenu_file, $plugin_page, $typenow;

			$first = true;
			// 0 = menu_title, 1 = capability, 2 = menu_slug, 3 = page_title, 4 = classes, 5 = hookname, 6 = icon_url
			if ( is_array( $menu ) ) {
				foreach ( $menu as $key => $item ) {
					$admin_is_parent = false;
					$class           = array();
					$aria_attributes = '';
					$aria_hidden     = '';
					$is_separator    = false;

					if ( $first ) {
						$class[] = 'wp-first-item';
						$first   = false;
					}

					$submenu_items = array();
					if ( ! empty( $submenu[ $item[2] ] ) ) {
						$class[]       = 'wp-has-submenu';
						$submenu_items = $submenu[ $item[2] ];
					}

					if ( ( $parent_file && $item[2] == $parent_file ) || ( empty( $typenow ) && $self == $item[2] ) ) {
						if ( ! empty( $submenu_items ) ) {
							$class[] = 'wp-has-current-submenu wp-menu-open';
						} else {
							$class[]          = 'current';
							$aria_attributes .= 'aria-current="page"';
						}
					} else {
						$class[] = 'wp-not-current-submenu';
						if ( ! empty( $submenu_items ) ) {
							$aria_attributes .= 'aria-haspopup="true"';
						}
					}

					if ( ! empty( $item[4] ) ) {
						$class[] = esc_attr( $item[4] );
					}

					$class     = $class ? ' class="' . join( ' ', $class ) . '"' : '';
					$id        = ! empty( $item[5] ) ? ' id="' . preg_replace( '|[^a-zA-Z0-9_:.]|', '-', $item[5] ) . '"' : '';
					$img       = $img_style = '';
					$img_class = ' dashicons-before';

					if ( false !== strpos( $class, 'wp-menu-separator' ) ) {
						$is_separator = true;
					}

					/*
					* If the string 'none' (previously 'div') is passed instead of a URL, don't output
					* the default menu image so an icon can be added to div.wp-menu-image as background
					* with CSS. Dashicons and base64-encoded data:image/svg_xml URIs are also handled
					* as special cases.
					*/
					if ( ! empty( $item[6] ) ) {
						$img = '<img src="' . $item[6] . '" alt="" />';

						if ( 'none' === $item[6] || 'div' === $item[6] ) {
							$img = '<br />';
						} elseif ( 0 === strpos( $item[6], 'data:image/svg+xml;base64,' ) ) {
							$img       = '<br />';
							$img_style = ' style="background-image:url(\'' . esc_attr( $item[6] ) . '\')"';
							$img_class = ' svg';
						} elseif ( 0 === strpos( $item[6], 'dashicons-' ) ) {
							$img       = '<br />';
							$img_class = ' dashicons-before ' . sanitize_html_class( $item[6] );
						}
					}
					$arrow = '<div class="wp-menu-arrow"><div></div></div>';

					$title = wptexturize( $item[0] );

					// Hide separators from screen readers.
					if ( $is_separator ) {
						$aria_hidden = ' aria-hidden="true"';
					}

					echo wp_kses_post( "\n\t<li$class$id$aria_hidden>" );

					if ( $is_separator ) {
						echo '<div class="separator"></div>';
					} elseif ( $submenu_as_parent && ! empty( $submenu_items ) ) {
						$submenu_items = array_values( $submenu_items );  // Re-index.
						$menu_hook     = get_plugin_page_hook( $submenu_items[0][2], $item[2] );
						$menu_file     = $submenu_items[0][2];
						if ( false !== ( $pos = strpos( $menu_file, '?' ) ) ) {
							$menu_file = substr( $menu_file, 0, $pos );
						}
						if ( ! empty( $menu_hook ) || ( ( 'index.php' != $submenu_items[0][2] ) && file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! file_exists( ABSPATH . "/wp-admin/$menu_file" ) ) ) {
							$admin_is_parent = true;
							echo wp_kses_post( "<a href='admin.php?page={$submenu_items[0][2]}'$class $aria_attributes>$arrow<div class='wp-menu-image$img_class'$img_style>$img</div><div class='wp-menu-name'>$title</div></a>" );
						} else {
							echo wp_kses_post( "\n\t<a href='{$submenu_items[0][2]}'$class $aria_attributes>$arrow<div class='wp-menu-image$img_class'$img_style>$img</div><div class='wp-menu-name'>$title</div></a>" );
						}
					} elseif ( ! empty( $item[2] ) && current_user_can( $item[1] ) ) {
						$menu_hook = get_plugin_page_hook( $item[2], 'admin.php' );
						$menu_file = $item[2];
						if ( false !== ( $pos = strpos( $menu_file, '?' ) ) ) {
							$menu_file = substr( $menu_file, 0, $pos );
						}
						if ( ! empty( $menu_hook ) || ( ( 'index.php' != $item[2] ) && file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! file_exists( ABSPATH . "/wp-admin/$menu_file" ) ) ) {
							$admin_is_parent = true;
							echo wp_kses_post( "\n\t<a href='admin.php?page={$item[2]}'$class $aria_attributes>$arrow<div class='wp-menu-image$img_class'$img_style>$img</div><div class='wp-menu-name'>{$item[0]}</div></a>" );
						} else {
							echo wp_kses_post( "\n\t<a href='{$item[2]}'$class $aria_attributes>$arrow<div class='wp-menu-image$img_class'$img_style>$img</div><div class='wp-menu-name'>{$item[0]}</div></a>" );
						}
					}

					if ( ! empty( $submenu_items ) ) {
						echo "\n\t<ul class='wp-submenu wp-submenu-wrap'>";
						echo wp_kses_post( "<li class='wp-submenu-head' aria-hidden='true'>{$item[0]}</li>" );

						$first = true;

						// 0 = menu_title, 1 = capability, 2 = menu_slug, 3 = page_title, 4 = classes
						foreach ( $submenu_items as $sub_key => $sub_item ) {
							if ( ! current_user_can( $sub_item[1] ) ) {
								continue;
							}

							$class           = array();
							$aria_attributes = '';
							if ( $first ) {
								$class[] = 'wp-first-item';
								$first   = false;
							}

							$menu_file = $item[2];

							if ( false !== ( $pos = strpos( $menu_file, '?' ) ) ) {
								$menu_file = substr( $menu_file, 0, $pos );
							}

							// Handle current for post_type=post|page|foo pages, which won't match $self.
							$self_type = ! empty( $typenow ) ? $self . '?post_type=' . $typenow : 'nothing';

							if ( isset( $submenu_file ) ) {
								if ( $submenu_file == $sub_item[2] ) {
									$class[]          = 'current';
									$aria_attributes .= ' aria-current="page"';
								}
								// If plugin_page is set the parent must either match the current page or not physically exist.
								// This allows plugin pages with the same hook to exist under different parents.
							} elseif (
								( ! isset( $plugin_page ) && $self == $sub_item[2] ) ||
								( isset( $plugin_page ) && $plugin_page == $sub_item[2] && ( $item[2] == $self_type || $item[2] == $self || file_exists( $menu_file ) === false ) )
							) {
								$class[]          = 'current';
								$aria_attributes .= ' aria-current="page"';
							}

							if ( ! empty( $sub_item[4] ) ) {
								$class[] = esc_attr( $sub_item[4] );
							}

							$class = $class ? ' class="' . join( ' ', $class ) . '"' : '';

							$menu_hook = get_plugin_page_hook( $sub_item[2], $item[2] );
							$sub_file  = $sub_item[2];
							if ( false !== ( $pos = strpos( $sub_file, '?' ) ) ) {
								$sub_file = substr( $sub_file, 0, $pos );
							}

							$title = wptexturize( $sub_item[0] );

							if ( ! empty( $menu_hook ) || ( ( 'index.php' != $sub_item[2] ) && file_exists( WP_PLUGIN_DIR . "/$sub_file" ) && ! file_exists( ABSPATH . "/wp-admin/$sub_file" ) ) ) {
								// If admin.php is the current page or if the parent exists as a file in the plugins or admin dir
								if ( ( ! $admin_is_parent && file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! is_dir( WP_PLUGIN_DIR . "/{$item[2]}" ) ) || file_exists( $menu_file ) ) {
									$sub_item_url = add_query_arg( array( 'page' => $sub_item[2] ), $item[2] );
								} else {
									$sub_item_url = add_query_arg( array( 'page' => $sub_item[2] ), 'admin.php' );
								}

								$sub_item_url = esc_url( $sub_item_url );
								echo wp_kses_post( "<li$class><a href='$sub_item_url'$class$aria_attributes>$title</a></li>" );
							} else {
								echo wp_kses_post( "<li$class><a href='{$sub_item[2]}'$class$aria_attributes>$title</a></li>" );
							}
						}
						echo '</ul>';
					}
					echo '</li>';
				}
			}

			echo '<li id="collapse-menu" class="hide-if-no-js">' .
				'<button type="button" id="collapse-button" aria-label="' . esc_attr__( 'Collapse Main menu' ) . '" aria-expanded="true">' .
				'<span class="collapse-button-icon" aria-hidden="true"></span>' .
				'<span class="collapse-button-label">' . esc_html__( 'Collapse menu' ) . '</span>' .
				'</button></li>';
		}

		/**
		 * Minimum capability.
		 *
		 * @since   1.0.0
		 */
		public function capability() {
			return 'manage_options';
		}
	}

	Kata_Plus_Admin_Panel::get_instance();
}

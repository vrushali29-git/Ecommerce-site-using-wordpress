<?php

/**
 * Kata Plus Theme Options Helpers.
 *
 * @since   1.0.0
 */

if ( ! class_exists( 'Kata_Plus_Theme_Options_Functions' ) ) {
	class Kata_Plus_Theme_Options_Functions {
		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Theme_Options_Functions
		 */
		public static $instance;

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since   1.0.0
		 * @return   object
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
		 * @since    1.0.0
		 */
		public function __construct() {
			$this->actions();
		}

		/**
		 * Add actions.
		 *
		 * @since    1.0.0
		 */
		public function actions() {
			// Responsive
			add_filter( 'wp_head', array( $this, 'option_responsive' ), 100 );
			// Load google fonts
			add_action( 'wp_enqueue_scripts', array( $this, 'load_google_fonts' ) );
			// breadcrumbs
			if ( get_theme_mod( 'kata_show_breadcrumbs' ) == true ) {
				add_action( 'kata_header', array( $this, 'breadcrumbs' ), 99999 );
			}
			// Option Dynamic Styles
			// customize_preview_init
			add_action( 'customize_save_after', array( $this, 'option_dynamic_styles' ), 999999 );
			// header transparent
			// add_action( 'customize_save_after', array( $this, 'header_transparent' ), 999999 );
			// Enqueue Scripts
			add_action( 'elementor/frontend/after_register_scripts', array( $this, 'kata_plus_inline_scripts' ), 999999 );
			// Page Title
			add_action( 'kata_page_before_loop', array( $this, 'page_title' ), 10 );
			// Sidebars
			add_action( 'kata_page_before_loop', array( $this, 'left_sidebar' ), 30 );
			add_action( 'kata_page_before_the_content', array( $this, 'start_page_with_sidebar' ) );
			add_action( 'kata_page_after_the_content', array( $this, 'end_page_with_sidebar' ) );
			add_action( 'kata_page_after_loop', array( $this, 'right_sidebar' ) );
			// Body Classes
			add_filter( 'body_class', array( $this, 'body_classes' ) );
			// Backup customizer
			add_action( 'wp_head', array( $this, 'backup_customizer' ) );
			add_action( 'wp_head', array( $this, 'restore_backup_customizer' ) );
			// add_action( 'wp_body_open', [$this, 'builders_page_options'] );
		}

		/**
		 * Enqueue dynamic inline scripts.
		 *
		 * @since   1.0.0
		 */
		public function kata_plus_inline_scripts() {
			wp_enqueue_script( 'kata-plus-inline-script', Kata_Plus::$assets . 'js/frontend/kata-plus-inline.js', array( 'jquery' ), Kata_Plus::$version, true );
			$scripts  = '(function ($) { jQuery(document).ready(function () {';
			$scripts  = apply_filters( 'kata_plus_inline_scripts', $scripts );
			$scripts .= '}); })(jQuery);';
			wp_add_inline_script( 'kata-plus-inline-script', Kata_Plus_Helpers::jsminifier( $scripts ), 'after' );
		}

		/**
		 * Option Dynamic Styles.
		 *
		 * @since   1.0.0
		 */
		public function load_google_fonts( $classes ) {
			$fonts = get_theme_mod( 'kata_add_google_font_repeater' );
			if ( class_exists( 'Kata_Plus_Pro' ) ) {
				if ( $fonts ) {
					foreach ( $fonts as $key => $font ) {
						$varients = explode( ',', $font['varients'] );
						$var = "[";
						foreach( $varients as $varient ) {
							$var .= '"' . $varient . '",';
						}

						$varients = rtrim( $var, ',' );
						$varients .= ']';

						$arr[] = array(
							'ID'         => rand( '99', '9999' ),
							'name'       => $font['fonts'],
							'source'     => 'google',
							'selectors'  => '[]',
							'subsets'    => '[]',
							'variants'   => $varients,
							'url'        => '',
							'place'      => 'head',
							'status'     => 'published',
							'created_at' => '1613900176',
							'updated_at' => '161450209',
						);
					}

					$free_fonts = json_encode( $arr, true );

					if ( ! get_option( 'migrate_to_fonts_manger_pro' ) ) {
						global $wpdb;

						$importData = json_decode( $free_fonts, true );
						if ( ! $importData ) {
							return;
						}

						$result = array();

						foreach ( $importData as $font ) {
							$result[] = $wpdb->insert(
								$wpdb->prefix . Kata_Plus_Pro::$fonts_table_name,
								array(
									'name'       => $font['name'],
									'source'     => $font['source'],
									'selectors'  => $font['selectors'],
									'subsets'    => $font['subsets'],
									'variants'   => $font['variants'],
									'url'        => $font['url'],
									'place'      => $font['place'],
									'status'     => $font['status'],
									'created_at' => $font['created_at'],
									'updated_at' => $font['updated_at'],
								)
							);
						}

						if ( ! in_array( 0, $result ) ) {
							update_option( 'migrate_to_fonts_manger_pro', $free_fonts );
						} else {
							update_option( 'migrate_to_fonts_manger_pro', 'error' );
						}
					}
				}
			}
			$voiced_fonts = '';
			if ( $fonts ) {
				foreach ( $fonts as $key => $font ) {
					$voiced_fonts .= $font['fonts'] . ':' . $font['varients'] . '|';
				}
			}
			$src = 'https://fonts.googleapis.com/css?family=' . $voiced_fonts;
			if ( $voiced_fonts ) {
				wp_enqueue_style( 'kata-plus-basic-google-fonts', $src, array(), Kata_Plus::$version );
			}
		}

		/**
		 * Retrieves the font data for the Simple Font Manager styler.
		 *
		 * This function retrieves the font data from the 'kata_add_google_font_repeater' theme modifier.
		 * It iterates over each font in the array and assigns the 'fonts' value to the 'name' key in the $data array.
		 * Finally, it returns the $data array containing the font data.
		 *
		 * @return array The font data for the Simple Font Manager styler.
		 */
		public static function simple_font_manager_data_for_styler() {
			$fonts = get_theme_mod( 'kata_add_google_font_repeater' );

			$data = array();

			if ( is_array( $fonts ) ) {
				foreach( $fonts as $font ) {
					$data[]['name'] = $font['fonts'];
				}
			}

			return $data;
		}

		/**
		 * Option Dynamic Styles.
		 *
		 * @since   1.0.0
		 */
		public function body_classes( $classes ) {
			$colorbase = get_theme_mod( 'kata_base_color', '' );
			if ( ! empty( $colorbase ) ) {
				$classes[] = 'kata-color-base';
			}

			return $classes;
		}

		/**
		 * Breadcrumbs.
		 *
		 * @param $seperator
		 * @since   1.0.0
		 */
		public static function breadcrumbs( $start = '', $seperator = '' ) {
			// Breadcrumbs
			if ( get_theme_mod( 'kata_show_breadcrumbs' ) == true ) {
				if ( ! is_front_page() && get_post_type() != 'kata_plus_builder' ) { ?>
					<div id="kata-breadcrumbs" class="kata-breadcrumbs">
						<div class="container">
							<div class="col-sm-12">
								<?php
								if ( function_exists( 'kata_plus_breadcrumbs' ) ) {
									kata_plus_breadcrumbs( $start, $seperator );
								}
								?>
							</div>
						</div>
					</div> <!-- #kata-breadcrumbs -->
					<?php
				}
			}
		}

		/**
		 * Customizer styler css.
		 *
		 * @since   1.0.0
		 */
		public function builders_page_options() {
			if ( is_archive() ) {
				remove_action( 'kata_header', array( Kata_Plus_Builders_Base::get_instance(), 'builder_render' ) );
			}
			if ( is_tag() ) {
			}
			if ( is_author() ) {
			}
			if ( is_404() ) {
			}
			if ( Kata_Plus_helpers::is_blog_pages() ) {
			}
			if ( Kata_Plus_helpers::is_blog() ) {
			}
			if ( is_search() ) {
			}
		}

		/**
		 * Option Dynamic Styles.
		 *
		 * @since   1.0.0
		 */
		public function option_dynamic_styles() {
			$css = '';

			// Body Typography
			$body_typo_status     = get_theme_mod( 'kata_body_typography_status', 'disable' );
			$body_font_family     = get_theme_mod( 'kata_body_font_family', 'select-font' );
			$body_font_properties = get_theme_mod(
				'kata_body_font_properties',
				array(
					'font-size'      => '15px',
					'font-weight'    => '400',
					'letter-spacing' => '0px',
					'line-height'    => '1.5',
				)
			);
			$body_font_color      = get_theme_mod( 'kata_body_font_color' );
			if ( 'enabel' == $body_typo_status ) {
				$css .= 'body{';
				$css .= $body_font_family ? 'font-family:' . $body_font_family . ';' : '';
				$css .= $body_font_properties['font-size'] ? 'font-size:' . $body_font_properties['font-size'] . ';' : '';
				$css .= $body_font_properties['font-weight'] ? 'font-weight:' . $body_font_properties['font-weight'] . ';' : '';
				$css .= $body_font_properties['letter-spacing'] ? 'letter-spacing:' . $body_font_properties['letter-spacing'] . ';' : '';
				$css .= $body_font_properties['line-height'] ? 'line-height:' . $body_font_properties['line-height'] . ';' : '';
				$css .= $body_font_color ? 'color:' . $body_font_color . ';' : '';
				$css .= '}';
			}

			// Heading Typography
			$headings_typo_status     = get_theme_mod( 'kata_headings_typography_status', 'disable' );
			$headings_font_family     = get_theme_mod( 'kata_headings_font_family', 'select-font' );
			$headings_font_properties = get_theme_mod(
				'kata_headings_font_properties',
				array(
					'font-size'      => '15px',
					'font-weight'    => '400',
					'letter-spacing' => '0px',
					'line-height'    => '1.5',
				)
			);
			$headings_font_color      = get_theme_mod( 'kata_headings_font_color' );
			if ( 'enabel' == $headings_typo_status ) {
				$css .= 'h1,h2,h3,h4,h5,h6{';
				$css .= $headings_font_family ? 'font-family:' . $headings_font_family . ';' : '';
				$css .= $headings_font_properties['font-size'] ? 'font-size:' . $headings_font_properties['font-size'] . ';' : '';
				$css .= $headings_font_properties['font-weight'] ? 'font-weight:' . $headings_font_properties['font-weight'] . ';' : '';
				$css .= $headings_font_properties['letter-spacing'] ? 'letter-spacing:' . $headings_font_properties['letter-spacing'] . ';' : '';
				$css .= $headings_font_properties['line-height'] ? 'line-height:' . $headings_font_properties['line-height'] . ';' : '';
				$css .= $headings_font_color ? 'color:' . $headings_font_color . ';' : '';
				$css .= '}';
			}

			// Menu Navigation Typography
			$nav_menu_typo_status     = get_theme_mod( 'kata_nav_menu_typography_status', 'disable' );
			$nav_menu_font_family     = get_theme_mod( 'kata_nav_menu_font_family', 'select-font' );
			$nav_menu_font_properties = get_theme_mod(
				'kata_nav_menu_font_properties',
				array(
					'font-size'      => '15px',
					'font-weight'    => '400',
					'letter-spacing' => '0px',
					'line-height'    => '1.5',
				)
			);
			$nav_menu_font_color      = get_theme_mod( 'kata_nav_menu_font_color' );
			if ( 'enabel' == $nav_menu_typo_status ) {
				$css .= '.kata-menu-navigation li.menu-item a{';
				$css .= $nav_menu_font_family ? 'font-family:' . $nav_menu_font_family . ';' : '';
				$css .= $nav_menu_font_properties['font-size'] ? 'font-size:' . $nav_menu_font_properties['font-size'] . ';' : '';
				$css .= $nav_menu_font_properties['font-weight'] ? 'font-weight:' . $nav_menu_font_properties['font-weight'] . ';' : '';
				$css .= $nav_menu_font_properties['letter-spacing'] ? 'letter-spacing:' . $nav_menu_font_properties['letter-spacing'] . ';' : '';
				$css .= $nav_menu_font_properties['line-height'] ? 'line-height:' . $nav_menu_font_properties['line-height'] . ';' : '';
				$css .= $nav_menu_font_color ? 'color:' . $nav_menu_font_color . ';' : '';
				$css .= '}';
			}

			// Container Size
			$kata_grid_size_desktop         = get_theme_mod( 'kata_grid_size_desktop', '1280' );
			$kata_grid_size_laptop          = get_theme_mod( 'kata_grid_size_laptop', '1024' );
			$kata_grid_size_tabletlandscape = get_theme_mod( 'kata_grid_size_tabletlandscape', '100' );
			$kata_grid_size_tablet          = get_theme_mod( 'kata_grid_size_tablet', '100' );
			$kata_grid_size_mobile          = get_theme_mod( 'kata_grid_size_mobile', '100' );
			$kata_grid_size_small_mobile    = get_theme_mod( 'kata_grid_size_small_mobile', '100' );
			$wide_container                 = get_theme_mod( 'kata_wide_container', '0' );

			if ( $kata_grid_size_desktop ) {
				$css .= '.container, .elementor-section.elementor-section-boxed>.elementor-container{ max-width: ' . $kata_grid_size_desktop . 'px;}';
				$css .= class_exists( 'WooCommerce') ? '.woocommerce-notices-wrapper { padding: 0 var(--ct-elementor-column-gap); max-width: ' . $kata_grid_size_desktop . 'px; margin-left: auto; margin-right: auto;}' : '';
				$css .= '.e-con { --container-max-width: ' . $kata_grid_size_desktop . 'px; }';
			}
			if ( $kata_grid_size_laptop ) {
				$css .= '@media(max-width:1366px){';
				$css .= '.container, .elementor-section.elementor-section-boxed>.elementor-container{ max-width: ' . $kata_grid_size_laptop . 'px;}';
				$css .= class_exists( 'WooCommerce') ? '.woocommerce-notices-wrapper { padding: 0 var(--ct-elementor-column-gap); max-width: ' . $kata_grid_size_laptop . 'px;}' : '';
				$css .= '.e-con { --container-max-width: ' . $kata_grid_size_laptop . 'px; }';
				$css .=  '}';
			}
			if ( $kata_grid_size_tabletlandscape ) {
				$css .= '@media(max-width:1024px){';
				$css .= '.container, .elementor-section.elementor-section-boxed>.elementor-container { max-width: ' . $kata_grid_size_tabletlandscape . '% !important;}';
				$css .= class_exists( 'WooCommerce') ? '.woocommerce-notices-wrapper { padding: 0 var(--ct-elementor-column-gap); max-width: ' . $kata_grid_size_tabletlandscape . '%;}' : '';
				$css .= '.e-con { --container-max-width: ' . $kata_grid_size_tabletlandscape . '% !important; }';
				$css .= '}';
			}
			if ( $kata_grid_size_tablet ) {
				$css .= '@media(max-width:768px){';
				$css .= '.container, .elementor-section.elementor-section-boxed>.elementor-container { max-width: ' . $kata_grid_size_tablet . '% !important; margin-left:auto; margin-right:auto;}';
				$css .= class_exists( 'WooCommerce') ? '.woocommerce-notices-wrapper { padding: 0 var(--ct-elementor-column-gap); max-width: ' . $kata_grid_size_tablet . '%;}' : '';
				$css .= '.e-con { --container-max-width: ' . $kata_grid_size_tablet . '% !important; }';
				$css .= '}';
			}
			if ( $kata_grid_size_mobile ) {
				$css .= '@media(max-width:480px){';
				$css .= '.container, .elementor-section.elementor-section-boxed>.elementor-container { max-width: ' . $kata_grid_size_mobile . '% !important; margin-left:auto; margin-right:auto;}';
				$css .= class_exists( 'WooCommerce') ? '.woocommerce-notices-wrapper { padding: 0 var(--ct-elementor-column-gap); max-width: ' . $kata_grid_size_mobile . '%;}' : '';
				$css .= '.e-con { --container-max-width: ' . $kata_grid_size_mobile . '% !important; }';
				$css .= '}';
			}
			if ( $kata_grid_size_small_mobile ) {
				$css .= '@media(max-width:320px){';
				$css .= '.container, .elementor-section.elementor-section-boxed>.elementor-container { max-width: ' . $kata_grid_size_small_mobile . '% !important; margin-left:auto; margin-right:auto;}';
				$css .= class_exists( 'WooCommerce') ? '.woocommerce-notices-wrapper { padding: 0 var(--ct-elementor-column-gap); max-width: ' . $kata_grid_size_small_mobile . '%;}' : '';
				$css .= '.e-con { --container-max-width: ' . $kata_grid_size_small_mobile . '% !important; }';
				$css .= '}';
			}

			$css .= '.elementor-section.elementor-section-boxed>.elementor-container .elementor-container, .elementor-section.elementor-section-boxed>.elementor-container .container { max-width: 100%; }';
			$css .= '.single .kata-content .container .elementor-container { max-width: 100%; }';

			if ( $wide_container ) {
				$css .= '.e-con { --container-max-width: 100% !important; }';
				$css .= '.container, .elementor-section.elementor-section-boxed>.elementor-container{max-width: 100%;}';
			}

			$colorbase = get_theme_mod( 'kata_base_color', '#877ff3' );
			$kata_color_primary = get_theme_mod( 'kata-color-primary', '#837af5' );
			$kata_color_secondary = get_theme_mod( 'kata-color-secondary', '#1d2834' );
			$kata_color_button_primary = get_theme_mod( 'kata-color-button-primary', '#f7f6ff' );
			$kata_color_button_secondary = get_theme_mod( 'kata-color-button-secondary', '#2acf6c' );
			$kata_color_border_button_primary = get_theme_mod( 'kata-color-border-button-primary', '#e0deff' );
			$kata_color_text_primary = get_theme_mod( 'kata-color-text-primary', '#737d8b' );
			$kata_color_text_secondary = get_theme_mod( 'kata-color-text-secondary', '#959ca7' );
			$kata_color_text_tertiary = get_theme_mod( 'kata-color-text-tertiary', '#b7bec9' );
			$kata_color_heading_primary = get_theme_mod( 'kata-color-heading-primary', '#4c5765' );
			$kata_color_heading_secondary = get_theme_mod( 'kata-color-heading-secondary', '#202122' );
			$kata_color_onsale_primary = get_theme_mod( 'kata-color-onsale-primary', '#f37f9b' );
			$kata_color_border_primary = get_theme_mod( 'kata-color-border-primary', '#e3e5e7' );
			$kata_color_border_secondary = get_theme_mod( 'kata-color-border-secondary', '#f0f3f6' );
			$kata_color_border_tertiary = get_theme_mod( 'kata-color-border-tertiary', '#cbc7fb' );
			$kata_color_pagetitle_bg_primary = get_theme_mod( 'kata-color-pagetitle-bg-primary', '#f6f7f8' );
			$kata_border_radius_primary = get_theme_mod( 'kata-border-radius-primary', '7' );
			$kata_border_radius_secondary = get_theme_mod( 'kata-border-radius-secondary', '14' );
			$kata_font_size_primary = get_theme_mod( 'kata-font-size-primary', '15' );
			$kata_font_size_secondary = get_theme_mod( 'kata-font-size-secondary', '17' );
			$kata_font_size_tertiary = get_theme_mod( 'kata-font-size-tertiary', '18' );

			$css .= ':root {';
			$css .= '--ct-base-color: ' . $colorbase . ';';
			$css .= '--ct-color-primary: ' . $kata_color_primary . ';';
			$css .= '--ct-color-secondary: ' . $kata_color_secondary . ';';
			$css .= '--ct-color-button-primary: ' . $kata_color_button_primary . ';';
			$css .= '--ct-color-button-secondary: ' . $kata_color_button_secondary . ';';
			$css .= '--ct-color-border-button-primary: ' . $kata_color_border_button_primary . ';';
			$css .= '--ct-color-text-primary: ' . $kata_color_text_primary . ';';
			$css .= '--ct-color-text-secondary: ' . $kata_color_text_secondary . ';';
			$css .= '--ct-color-text-tertiary: ' . $kata_color_text_tertiary . ';';
			$css .= '--ct-color-heading-primary: ' . $kata_color_heading_primary . ';';
			$css .= '--ct-color-heading-secondary: ' . $kata_color_heading_secondary . ';';
			$css .= '--ct-color-onsale-primary: ' . $kata_color_onsale_primary . ';';
			$css .= '--ct-color-border-primary: ' . $kata_color_border_primary . ';';
			$css .= '--ct-color-border-secondary: ' . $kata_color_border_secondary . ';';
			$css .= '--ct-color-border-tertiary: ' . $kata_color_border_tertiary . ';';
			$css .= '--ct-color-pagetitle-bg-primary: ' . $kata_color_pagetitle_bg_primary . ';';
			$css .= '--ct-border-radius-primary:' . $kata_border_radius_primary . 'px;';
			$css .= '--ct-border-radius-secondary:' . $kata_border_radius_secondary . 'px;';
			$css .= '--ct-font-size-primary:' . $kata_font_size_primary . 'px;';
			$css .= '--ct-font-size-secondary:' . $kata_font_size_secondary . 'px;';
			$css .= '--ct-font-size-tertiary:' . $kata_font_size_tertiary . 'px;';
			$css .= '}';

			$css = apply_filters( 'option_dynamic_styles', $css );

			$uploaddir = wp_get_upload_dir();
			global $wp_filesystem;
			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}
			$wp_filesystem->put_contents(
				$uploaddir['basedir'] . '/kata/css/dynamic-styles.css',
				Kata_Plus_Helpers::cssminifier( $css ),
				FS_CHMOD_FILE
			);
		}

		/**
		 * Start Section Slider Page.
		 *
		 * @since   1.0.0
		 */
		public function header_transparent() {
			$header_transparent      = get_theme_mod( 'kata_make_header_transparent' );
			$header_transparent_dark = get_theme_mod( 'kata_header_transparent_white_color' );

			if ( ! get_option( 'kata_make_header_transparent_status' ) ) {
				update_option( 'kata_make_header_transparent_status', $header_transparent );
			}

			if ( ! get_option( 'kata_header_transparent_white_color_status' ) ) {
				update_option( 'kata_header_transparent_white_color_status', $header_transparent_dark );
			}

			// if ( get_option( 'kata_make_header_transparent_status' ) !== $header_transparent ) {
			switch ( $header_transparent ) {
				case 'posts':
					$posts = get_posts( 'post_type=post&numberposts=-1&post_status=any' );
					foreach ( $posts as $post ) {
						if ( 'default' == get_post_meta( $post->ID, 'kata_make_header_transparent', true ) ) {
							update_post_meta( $post->ID, 'kata_make_header_transparent', '1' );
						}
					}
					break;
				case 'pages':
					$pages = get_posts( 'post_type=page&numberposts=-1&post_status=any' );
					foreach ( $pages as $page ) {
						if ( 'default' == get_post_meta( $page->ID, 'kata_make_header_transparent', true ) ) {
							update_post_meta( $page->ID, 'kata_make_header_transparent', '1' );
						}
					}
					break;
				case 'both':
					$posts = get_posts( 'post_type=post&numberposts=-1&post_status=any' );
					foreach ( $posts as $post ) {
						if ( 'default' == get_post_meta( $post->ID, 'kata_make_header_transparent', true ) ) {
							update_post_meta( $post->ID, 'kata_make_header_transparent', '1' );
						}
					}
					$pages = get_posts( 'post_type=page&numberposts=-1&post_status=any' );
					foreach ( $pages as $page ) {
						if ( 'default' == get_post_meta( $page->ID, 'kata_make_header_transparent', true ) ) {
							update_post_meta( $page->ID, 'kata_make_header_transparent', '1' );
						}
					}
					break;
				case 'default':
					$posts = get_posts( 'post_type=post&numberposts=-1&post_status=any' );
					foreach ( $posts as $post ) {
						if ( 'default' !== get_post_meta( $post->ID, 'kata_make_header_transparent', true ) ) {
							update_post_meta( $post->ID, 'kata_make_header_transparent', 'default' );
						}
					}
					$pages = get_posts( 'post_type=page&numberposts=-1&post_status=any' );
					foreach ( $pages as $page ) {
						if ( 'default' !== get_post_meta( $page->ID, 'kata_make_header_transparent', true ) ) {
							update_post_meta( $page->ID, 'kata_make_header_transparent', 'default' );
						}
					}
					break;
			}
			// }

			// if ( get_option( 'kata_header_transparent_white_color_status', $header_transparent_dark ) !== $header_transparent_dark ) {
			switch ( $header_transparent_dark ) {
				case 'posts':
					$posts = get_posts( 'post_type=post&numberposts=-1&post_status=any' );
					foreach ( $posts as $post ) {
						if ( 'default' == get_post_meta( $post->ID, 'kata_header_transparent_white_color', true ) ) {
							update_post_meta( $post->ID, 'kata_header_transparent_white_color', '1' );
						}
					}
					$pages = get_posts( 'post_type=page&numberposts=-1&post_status=any' );
					foreach ( $pages as $page ) {
						if ( 'default' == get_post_meta( $page->ID, 'kata_header_transparent_white_color', true ) ) {
							update_post_meta( $page->ID, 'kata_header_transparent_white_color', '0' );
						}
					}
					break;
				case 'pages':
					$pages = get_posts( 'post_type=page&numberposts=-1&post_status=any' );
					foreach ( $pages as $page ) {
						if ( 'default' == get_post_meta( $page->ID, 'kata_header_transparent_white_color', true ) ) {
							update_post_meta( $page->ID, 'kata_header_transparent_white_color', '1' );
						}
					}
					$posts = get_posts( 'post_type=post&numberposts=-1&post_status=any' );
					foreach ( $posts as $post ) {
						if ( 'default' == get_post_meta( $post->ID, 'kata_header_transparent_white_color', true ) ) {
							update_post_meta( $post->ID, 'kata_header_transparent_white_color', '0' );
						}
					}
					break;
				case 'both':
					$posts = get_posts( 'post_type=post&numberposts=-1&post_status=any' );
					foreach ( $posts as $post ) {
						if ( 'default' == get_post_meta( $post->ID, 'kata_header_transparent_white_color', true ) ) {
							update_post_meta( $post->ID, 'kata_header_transparent_white_color', '1' );
						}
					}
					$pages = get_posts( 'post_type=page&numberposts=-1&post_status=any' );
					foreach ( $pages as $page ) {
						if ( 'default' == get_post_meta( $page->ID, 'kata_header_transparent_white_color', true ) ) {
							update_post_meta( $page->ID, 'kata_header_transparent_white_color', '1' );
						}
					}
					break;
				case 'default':
					$posts = get_posts( 'post_type=post&numberposts=-1&post_status=any' );
					foreach ( $posts as $post ) {
						if ( 'default' !== get_post_meta( $post->ID, 'kata_header_transparent_white_color', true ) ) {
							update_post_meta( $post->ID, 'kata_header_transparent_white_color', 'default' );
						}
					}
					$pages = get_posts( 'post_type=page&numberposts=-1&post_status=any' );
					foreach ( $pages as $page ) {
						if ( 'default' !== get_post_meta( $page->ID, 'kata_header_transparent_white_color', true ) ) {
							update_post_meta( $page->ID, 'kata_header_transparent_white_color', 'default' );
						}
					}
					break;
			}
			// }
		}

		/**
		 * Left Sidebar.
		 *
		 * @since   1.0.0
		 */
		public function left_sidebar() {
			$sidebar_position_meta = Kata_Helpers::get_meta_box( 'sidebar_position' );
			$sidebar_position      = $sidebar_position_meta != 'inherit_from_customizer' && ! empty( $sidebar_position_meta ) ? $sidebar_position_meta : get_theme_mod( 'kata_page_sidebar_position', 'none' );

			if ( $sidebar_position != 'none' ) {
				echo '<div class="row">';
			}

			// Left sidebar
			if ( $sidebar_position == 'left' || $sidebar_position == 'both' ) {
				echo '<div class="col-md-3 kata-sidebar kata-left-sidebar">';
				if ( is_active_sidebar( 'kata-left-sidebar' ) ) {
					dynamic_sidebar( 'kata-left-sidebar' );
				}
				echo '</div>';
			}
		}

		/**
		 * Start Page with Sidebar.
		 *
		 * @since   1.0.0
		 */
		public function start_page_with_sidebar() {
			$sidebar_position_meta = Kata_Helpers::get_meta_box( 'sidebar_position' );
			$sidebar_position      = $sidebar_position_meta != 'inherit_from_customizer' && ! empty( $sidebar_position_meta ) ? $sidebar_position_meta : get_theme_mod( 'kata_page_sidebar_position', 'none' );

			if ( $sidebar_position == 'both' ) {
				echo '<div class="col-md-6 kata-page-content">';
			} elseif ( $sidebar_position == 'right' || $sidebar_position == 'left' ) {
				echo '<div class="col-md-9 kata-page-content">';
			}
		}

		/**
		 * End Page with Sidebar.
		 *
		 * @since   1.0.0
		 */
		public function end_page_with_sidebar() {
			$sidebar_position_meta = Kata_Helpers::get_meta_box( 'sidebar_position' );
			$sidebar_position      = $sidebar_position_meta != 'inherit_from_customizer' && ! empty( $sidebar_position_meta ) ? $sidebar_position_meta : get_theme_mod( 'kata_page_sidebar_position', 'none' );

			if ( $sidebar_position != 'none' ) {
				echo '</div>';
			}
		}

		/**
		 * Right Sidebar.
		 *
		 * @since   1.0.0
		 */
		public function right_sidebar() {
			$sidebar_position_meta = Kata_Helpers::get_meta_box( 'sidebar_position' );
			$sidebar_position      = $sidebar_position_meta != 'inherit_from_customizer' && ! empty( $sidebar_position_meta ) ? $sidebar_position_meta : get_theme_mod( 'kata_page_sidebar_position', 'none' );

			// Right sidebar
			if ( $sidebar_position == 'right' || $sidebar_position == 'both' ) {
				echo '<div class="col-md-3 kata-sidebar kata-right-sidebar">';
				if ( is_active_sidebar( 'kata-right-sidebar' ) ) {
					dynamic_sidebar( 'kata-right-sidebar' );
				}
				echo '</div>';
			}

			if ( $sidebar_position != 'none' ) {
				echo '</div>';
			}
		}

		/**
		 * Page Title.
		 *
		 * @since   1.0.0
		 */
		public function page_title() {
			$page_title_meta         = Kata_Helpers::get_meta_box( 'kata_show_page_title' );
			$page_title_text_meta    = Kata_Helpers::get_meta_box( 'kata_page_title_text' );
			$theme_option_page_title = get_theme_mod( 'kata_show_page_title' ) ? '1' : '0';
			$page_title              = $page_title_meta === 'inherit_from_customizer' ? $theme_option_page_title : $page_title_meta;
			$page_title_text         = $page_title_text_meta ? $page_title_text_meta : get_the_title();

			if ( '1' === $page_title ) {
				echo '
				<div id="kata-page-title" class="kata-page-title">
					<div class="container">
						<div class="col-sm-12">
							<h1>' . wp_kses_post( $page_title_text ) . '</h1>
						</div>
					</div>
				</div>';
			}
		}

		/**
		 * Responsive.
		 *
		 * @since   1.0.0
		 */
		public function option_responsive() {
			$kata_grid_size_desktop = get_theme_mod( 'kata_grid_size_desktop', '1280' );
			$scalable = get_theme_mod( 'kata_responsive_scalable', '1' ) == true ? 'yes': 'no';

			if ( get_theme_mod( 'kata_responsive', '1' ) == '1' ) {
				echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2 user-scalable=' . esc_attr( $scalable ) . '">';
			} else {
				echo '<meta name="viewport" content="width=' . esc_attr( $kata_grid_size_desktop ) . ', initial-scale=1, maximum-scale=1, user-scalable=' . esc_attr( $scalable ) . '">';
			}
		}

		/**
		 * Backup Customizer.
		 *
		 * @since   1.0.0
		 */
		public function backup_customizer() {
			if ( strlen( json_encode( get_option( 'theme_mods_kata' ) ) ) < 86 ) {
				return;
			}
			if ( ! get_option( 'customizer_backup' ) ) {
				add_option( 'customizer_backup', get_option( 'theme_mods_kata' ) );
				add_option( 'customizer_backup_date', date( 'Ymd' ) );
			}
			if ( get_option( 'customizer_backup_date' ) <= date( 'Ymd' ) && '-' !== get_option( 'theme_mods_kata' ) ) {
				update_option( 'customizer_backup', get_option( 'theme_mods_kata' ) );
				update_option( 'customizer_backup_date', date( 'Ymd' ) );
			}
		}

		/**
		 * Restore Backup Customizer.
		 *
		 * @since   1.0.0
		 */
		public function restore_backup_customizer() {
			if ( get_option( 'customizer_backup' ) && get_option( 'customizer_backup_date' ) ) {
				if ( '-' == get_option( 'theme_mods_kata' ) ) {
					$user          = wp_get_current_user();
					$allowed_roles = array( 'editor', 'administrator', 'author' );
					if ( array_intersect( $allowed_roles, $user->roles ) ) {
						echo '<div class="kata-plus-customizer-problem" style="background:#ffb0b0;padding:10px 10px 7px;"><h3 style="line-height:1.2;font-size:21px;font-weight:normal;color:#ad0000;">' . __( 'There is a problem with customizer (theme options) data\'s please refresh the page to resolve the problem', 'kata-plus' ) . '</h3></div>';
					}
					update_option( 'theme_mods_kata', get_option( 'customizer_backup' ) );
				}
			}
		}
	} // Class

	Kata_Plus_Theme_Options_Functions::get_instance();
}

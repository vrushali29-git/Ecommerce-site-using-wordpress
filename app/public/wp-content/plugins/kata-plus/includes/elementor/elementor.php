<?php

/**
 * Elementor Class.
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
use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Dynamic_CSS;
use Elementor\Utils as ElementorUtils;
use Elementor\Settings;
use Elementor\Group_Control_Css_Filter;

if ( ! class_exists( 'Kata_Plus_Elementor' ) ) {
	class Kata_Plus_Elementor {

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
		 * @var     Kata_Plus_Elementor
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
			if ( ! class_exists( 'Elementor\Plugin' ) ) {
				return;
			}
			$this->definitions();
			$this->actions();
			$this->config();
		}

		/**
		 * Global definitions.
		 *
		 * @since   1.0.0
		 */
		public function definitions() {
			self::$dir = Kata_Plus::$dir . 'includes/elementor/';
			self::$url = Kata_Plus::$url . 'includes/elementor/';
		}

		/**
		 * Add actions.
		 *
		 * @since   1.0.0
		 */
		public function actions() {
			add_action( 'elementor/init', array( $this, 'dependencies' ) );
			add_action( 'elementor/init', array( $this, 'register_widgets' ) );
			add_action( 'elementor/init', array( $this, 'register_options' ) );
			add_action( 'elementor/init', array( $this, 'elementorinit' ), 0 );
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ), 9999999 );
			add_action( 'admin_enqueue_scripts', array( $this, 'editor_styles' ), 9999999 );
			add_action( 'elementor/frontend/after_register_scripts', array( $this, 'elementor_frontend_scripts' ), 999 );

			add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'editor_scripts' ) );
			add_action( 'elementor/preview/enqueue_styles', array( $this, 'preview_styles' ) );
			add_action( 'elementor/element/parse_css', array( $this, 'module_custom_css_frontend' ), 10, 2 );
			add_action( 'elementor/controls/register', array( $this, 'controls' ) );
			add_action( 'elementor/element/column/layout/before_section_end', array( $this, 'column_layout_options' ), 10, 3 );
			add_action( 'elementor/element/after_section_start', array( $this, 'elementor_section' ), 10, 3 );
			add_action( 'elementor/element/column/section_typo/after_section_end', array( $this, 'column_style_options' ), 10, 3 );
			add_action( 'elementor/element/section/section_typo/after_section_end', array( $this, 'section_style_options' ), 10, 3 );
			add_action( 'elementor/element/container/section_shape_divider/after_section_end', array( $this, 'container_style_options' ), 10, 3 );
			add_action( 'elementor/element/wp-page/document_settings/before_section_start', array( $this, 'kata_page_options' ), 10, 2 );
			add_action( 'elementor/element/wp-post/document_settings/before_section_start', array( $this, 'kata_post_options' ), 10, 2 );
			add_action( 'kata_plus_common_controls', array( $this, 'common_controls' ), 10, 1 );

			add_filter( 'styler-pickr-swatches', array( $this, 'add_color_to_styler_swatches' ), 1, 9 );
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'add_kata_swatches_style' ) );

			if ( isset( $_GET['action'] ) && 'elementor' !== sanitize_text_field( $_GET['action'] ) ) {
				add_action( 'save_post', array( $this, 'sync_post_options' ), 10, 3 );
			}

			/**
			 * Elementor Default Columns Gap
			 */
			add_action(
				'elementor/element/section/section_layout/before_section_end',
				function ( $element ) {
					$element->remove_control( 'gap' );
				},
				10,
				1
			);

			add_action(
				'elementor/element/section/section_layout/before_section_end',
				function ( $element ) {
					$element->start_injection(
						array(
							'of' => 'height',
							'at' => 'before',
						)
					);
					$element->add_control(
						'gap',
						array(
							'label'   => __( 'Columns Gap', 'kata-plus' ),
							'type'    => Controls_Manager::SELECT,
							'default' => get_theme_mod( 'kata_elementor_default_columns_gap', 'default' ),
							'options' => array(
								'default'  => __( 'Default', 'kata-plus' ),
								'no'       => __( 'No Gap', 'kata-plus' ),
								'narrow'   => __( 'Narrow', 'kata-plus' ),
								'extended' => __( 'Extended', 'kata-plus' ),
								'wide'     => __( 'Wide', 'kata-plus' ),
								'wider'    => __( 'Wider', 'kata-plus' ),
								'custom'   => __( 'Custom', 'kata-plus' ),
							),
						)
					);
					$element->end_injection();
				},
				99,
				1
			);
		}

		public function elementor_frontend_scripts() {
			wp_register_script( 'kata-jquery-enllax', Kata_Plus::$assets . 'js/frontend/parallax-motion.js', array( 'jquery' ), Kata_Plus::$version, true );
			wp_enqueue_script( 'kata-plus-theme-scripts', Kata_Plus::$assets . 'js/frontend/theme-scripts.js', array( 'jquery' ), Kata_Plus::$version, true );
		}

		/**
		 * Register kata plus widgets.
		 *
		 * @since   1.3.0
		 */
		public function register_widgets() {
			Kata_Plus_Autoloader::load( self::$dir, 'register-widgets' );

			if ( class_exists( 'Kata_Plus_Register_Widgets' ) ) {
				Kata_Plus_Register_Widgets::init();
			}
		}

		/**
		 * Register kata plus widgets.
		 *
		 * @since   1.3.0
		 */
		public function register_options() {
			$dir = self::$dir . 'options/';
			Kata_Plus_Autoloader::load( $dir . 'sticky', 'sticky' );
		}

		/**
		 * Sync post options.
		 *
		 * @since   1.0.0
		 */
		public function sync_post_options( $post_id, $post, $update ) {
			$elementor_page_settings = get_post_meta( $post_id, '_elementor_page_settings', true );

			if ( $elementor_page_settings ) {
				$elementor_page_settings['kata_show_header']                    = get_post_meta( $post_id, 'kata_show_header', true );
				$elementor_page_settings['kata_make_header_transparent']        = get_post_meta( $post_id, 'kata_make_header_transparent', true );
				$elementor_page_settings['kata_header_transparent_white_color'] = get_post_meta( $post_id, 'kata_header_transparent_white_color', true );
				$elementor_page_settings['post_time_to_read']                   = get_post_meta( $post_id, 'post_time_to_read', true );
				$elementor_page_settings['kata_post_video']                     = get_post_meta( $post_id, 'kata_post_video', true );
				update_post_meta( $post_id, '_elementor_page_settings', $elementor_page_settings );
			}
		}

		/**
		 * Post Options.
		 *
		 * @since   1.0.0
		 */
		public function kata_post_options( $page ) {
			if ( isset( $page ) && $page->get_id() > '' ) {
				$page_options_post_type = false;
				$page_options_post_type = get_post_type( $page->get_id() );
				if ( $page_options_post_type == 'post' ) {
					/**
					 * Header options
					 */
					$page->start_controls_section(
						'kata_page_options_header',
						array(
							'label' => esc_html__( 'Header Options', 'kata-plus' ),
							'tab'   => Controls_Manager::TAB_SETTINGS,
						)
					);
					$page->add_control(
						'kata_show_header',
						array(
							'label'        => __( 'Show Header:', 'kata-plus' ),
							'type'         => Controls_Manager::SWITCHER,
							'label_on'     => __( 'Show', 'kata-plus' ),
							'label_off'    => __( 'Hide', 'kata-plus' ),
							'return_value' => '1',
							'default'      => get_post_meta( get_the_ID(), 'kata_show_header', true ) ? get_post_meta( get_the_ID(), 'kata_show_header', true ) : '1',
						)
					);
					$page->add_control(
						'kata_make_header_transparent',
						array(
							'label'   => __( 'Header Transparent', 'kata-plus' ),
							'type'    => Controls_Manager::SELECT,
							'default' => get_post_meta( get_the_ID(), 'kata_make_header_transparent', true ) ? get_post_meta( get_the_ID(), 'kata_make_header_transparent', true ) : 'default',
							'options' => array(
								'default' => __( 'Default', 'kata-plus' ),
								'0'       => __( 'Disable', 'kata-plus' ),
								'1'       => __( 'Enable', 'kata-plus' ),
							),
						)
					);
					$page->add_control(
						'kata_header_transparent_white_color',
						array(
							'label'     => __( 'Dark Header Transparent', 'kata-plus' ),
							'type'      => Controls_Manager::SELECT,
							'default'   => get_post_meta( get_the_ID(), 'kata_header_transparent_white_color', true ) ? get_post_meta( get_the_ID(), 'kata_header_transparent_white_color', true ) : 'default',
							'options'   => array(
								'default' => __( 'Default', 'kata-plus' ),
								'0'       => __( 'Disable', 'kata-plus' ),
								'1'       => __( 'Enable', 'kata-plus' ),
							),
							'condition' => array(
								'kata_make_header_transparent' => '1',
							),
						)
					);
					$page->end_controls_section();
					/**
					 * Post Options
					 */
					$page->start_controls_section(
						'kata_post_options',
						array(
							'label' => esc_html__( 'Post Options', 'kata-plus' ),
							'tab'   => Controls_Manager::TAB_SETTINGS,
						)
					);
					$page->add_control(
						'post_time_to_read',
						array(
							'label'   => __( 'Post\'s time to read:', 'kata-plus' ),
							'type'    => Controls_Manager::TEXT,
							'default' => get_post_meta( get_the_ID(), 'post_time_to_read', true ),
						)
					);
					$page->add_control(
						'kata_post_video',
						array(
							'label'       => __( 'Video URL:', 'kata-plus' ),
							'description' => __( 'Youtube, Vimeo or Hosted video, Works when post format is video.', 'kata-plus' ),
							'type'        => Controls_Manager::TEXT,
							'default'     => get_post_meta( get_the_ID(), 'kata_post_video', true ),
						)
					);
					$page->end_controls_section();
					$settings                                        = $page->get_settings();
					$settings['post_time_to_read']                   = $settings['post_time_to_read'] ? $settings['post_time_to_read'] : '';
					$settings['kata_post_video']                     = $settings['kata_post_video'] ? $settings['kata_post_video'] : '';
					$settings['kata_show_header']                    = isset( $settings['kata_show_header'] ) && '1' == $settings['kata_show_header'] ? $settings['kata_show_header'] : '1';
					$settings['kata_make_header_transparent']        = isset( $settings['kata_make_header_transparent'] ) ? $settings['kata_make_header_transparent'] : '0';
					$settings['kata_header_transparent_white_color'] = isset( $settings['kata_header_transparent_white_color'] ) ? $settings['kata_header_transparent_white_color'] : '0';
					update_post_meta( get_the_ID(), 'kata_show_header', $settings['kata_show_header'] );
					update_post_meta( get_the_ID(), 'kata_make_header_transparent', $settings['kata_make_header_transparent'] );
					update_post_meta( get_the_ID(), 'kata_header_transparent_white_color', $settings['kata_header_transparent_white_color'] );
					update_post_meta( get_the_ID(), 'post_time_to_read', $settings['post_time_to_read'] );
					update_post_meta( get_the_ID(), 'kata_post_video', $settings['kata_post_video'] );
				}
			}
		}

		/**
		 * Page Options.
		 *
		 * @since   1.0.0
		 */
		public function kata_page_options( $page ) {
			if ( isset( $page ) && $page->get_id() > '' ) {
				$page_options_post_type = false;
				$page_options_post_type = get_post_type( $page->get_id() );
				if ( $page_options_post_type == 'page' ) {

					$page->start_controls_section(
						'kata_page_options_header',
						array(
							'label' => esc_html__( 'Header Options', 'kata-plus' ),
							'tab'   => Controls_Manager::TAB_SETTINGS,
						)
					);
					$page->add_control(
						'kata_show_header',
						array(
							'label'        => __( 'Show Header:', 'kata-plus' ),
							'type'         => Controls_Manager::SWITCHER,
							'label_on'     => __( 'Show', 'kata-plus' ),
							'label_off'    => __( 'Hide', 'kata-plus' ),
							'return_value' => '1',
							'default'      => get_post_meta( get_the_ID(), 'kata_show_header', true ) ? get_post_meta( get_the_ID(), 'kata_show_header', true ) : '1',
						)
					);
					$page->add_control(
						'kata_make_header_transparent',
						array(
							'label'   => __( 'Header Transparent', 'kata-plus' ),
							'type'    => Controls_Manager::SELECT,
							'default' => get_post_meta( get_the_ID(), 'kata_make_header_transparent', true ) ? get_post_meta( get_the_ID(), 'kata_make_header_transparent', true ) : 'default',
							'options' => array(
								'default' => __( 'Default', 'kata-plus' ),
								'0'       => __( 'Disable', 'kata-plus' ),
								'1'       => __( 'Enable', 'kata-plus' ),
							),
						)
					);
					$page->add_control(
						'kata_header_transparent_white_color',
						array(
							'label'     => __( 'Dark Header Transparent', 'kata-plus' ),
							'type'      => Controls_Manager::SELECT,
							'default'   => get_post_meta( get_the_ID(), 'kata_header_transparent_white_color', true ) ? get_post_meta( get_the_ID(), 'kata_header_transparent_white_color', true ) : 'default',
							'options'   => array(
								'default' => __( 'Default', 'kata-plus' ),
								'0'       => __( 'Disable', 'kata-plus' ),
								'1'       => __( 'Enable', 'kata-plus' ),
							),
							'condition' => array(
								'kata_make_header_transparent' => '1',
							),
						)
					);
					$page->add_control(
						'applychanges1',
						array(
							'label'       => __( 'Apply', 'kata-plus' ),
							'type'        => Controls_Manager::BUTTON,
							'separator'   => 'before',
							'button_type' => 'success',
							'text'        => __( 'Apply', 'kata-plus' ),
							'description' => __( 'Click the Apply button to save your changes.', 'kata-plus' ),
							'event'       => 'applychanges',
						)
					);
					$page->end_controls_section();

					$page->start_controls_section(
						'kata_page_options_title',
						array(
							'label' => esc_html__( 'Page Title', 'kata-plus' ),
							'tab'   => Controls_Manager::TAB_SETTINGS,
						)
					);
					$page->add_control(
						'kata_show_page_title',
						array(
							'label'   => __( 'Page Title:', 'kata-plus' ),
							'type'    => Controls_Manager::CHOOSE,
							'toggle'  => false,
							'options' => array(
								'inherit_from_customizer' => array(
									'title' => __( 'Inherit', 'kata-plus' ),
									'icon'  => 'eicon-click',
								),
								'1'                       => array(
									'title' => __( 'Show', 'kata-plus' ),
									'icon'  => 'eicon-preview-medium',
								),
								'0'                       => array(
									'title' => __( 'Hide', 'kata-plus' ),
									'icon'  => 'eicon-preview-medium hido',
								),
							),
							'default' => 'inherit_from_customizer',
						)
					);
					$page->add_control(
						'kata_page_title_text',
						array(
							'label'       => __( 'Custom Page Title:', 'kata-plus' ),
							'type'        => Controls_Manager::TEXT,
							'placeholder' => __( 'Type your title here', 'kata-plus' ),
						)
					);
					$page->add_control(
						'kata_styler_page_title_wrapper',
						array(
							'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
							'type'     => 'styler',
							'selector' => '#kata-page-title',
							'isSVG'    => false,
							'isInput'  => false,
							'wrapper'  => 'body',
						)
					);
					$page->add_control(
						'kata_styler_page_title',
						array(
							'label'    => esc_html__( 'Title', 'kata-plus' ),
							'type'     => 'styler',
							'selector' => 'h1',
							'isSVG'    => false,
							'isInput'  => false,
							'wrapper'  => '#kata-page-title',
						)
					);
					$page->add_control(
						'applychanges2',
						array(
							'label'       => __( 'Apply', 'kata-plus' ),
							'type'        => Controls_Manager::BUTTON,
							'separator'   => 'before',
							'button_type' => 'success',
							'text'        => __( 'Apply', 'kata-plus' ),
							'description' => __( 'Click the Apply button to save your changes.', 'kata-plus' ),
							'event'       => 'applychanges',
						)
					);
					$page->end_controls_section();

					$page->start_controls_section(
						'kata_page_options_sidebar',
						array(
							'label' => esc_html__( 'Sidebar', 'kata-plus' ),
							'tab'   => Controls_Manager::TAB_SETTINGS,
						)
					);
					$page->add_control(
						'sidebar_position',
						array(
							'label'   => __( 'Position:', 'kata-plus' ),
							'type'    => Controls_Manager::CHOOSE,
							'toggle'  => false,
							'options' => array(
								'inherit_from_customizer' => array(
									'title' => __( 'Inherit', 'kata-plus' ),
									'icon'  => 'eicon-click',
								),
								'none'                    => array(
									'title' => __( 'None', 'kata-plus' ),
									'icon'  => 'ti-layout-sidebar-none',
								),
								'right'                   => array(
									'title' => __( 'Right', 'kata-plus' ),
									'icon'  => 'ti-layout-sidebar-right',
								),
								'left'                    => array(
									'title' => __( 'Left', 'kata-plus' ),
									'icon'  => 'ti-layout-sidebar-left',
								),
								'both'                    => array(
									'title' => __( 'Both', 'kata-plus' ),
									'icon'  => 'ti-layout-sidebar-2',
								),
							),
							'default' => 'inherit_from_customizer',
						)
					);
					$page->add_control(
						'applychanges3',
						array(
							'label'       => __( 'Apply', 'kata-plus' ),
							'type'        => Controls_Manager::BUTTON,
							'separator'   => 'before',
							'button_type' => 'success',
							'text'        => __( 'Apply', 'kata-plus' ),
							'description' => __( 'Click the Apply button to save your changes.', 'kata-plus' ),
							'event'       => 'applychanges',
						)
					);
					$page->end_controls_section();

					$page->start_controls_section(
						'kata_page_options_full_page_slider',
						array(
							'label' => esc_html__( 'Full Page Slider', 'kata-plus' ),
							'tab'   => Controls_Manager::TAB_SETTINGS,
						)
					);
					$page->add_control(
						'full_page_slider_pro',
						array(
							'label' => __( 'Full Page Slider', 'kata-plus' ),
							'type'  => Controls_Manager::RAW_HTML,
							'raw'   => '<div class="elementor-nerd-box">
								<img class="elementor-nerd-box-icon" src="' . esc_attr( ELEMENTOR_ASSETS_URL . 'images/go-pro.svg' ) . '">
								<div class="elementor-nerd-box-title">' . __( 'Full Page Slider', 'kata-plus' ) . '</div>
								<div class="elementor-nerd-box-message">' . __( 'Full Page Slider will turn each section into a slide and will display the page as a slider.', 'kata-plus' ) . '</div>
								<a class="elementor-nerd-box-link elementor-button elementor-button-default elementor-button-go-pro" href="https://my.climaxthemes.com/buy" target="_blank">' . __( 'Kata Pro', 'kata-plus' ) . '</a>
							</div>',
						)
					);
					do_action( 'kata_plus_full_page_slider', $page );
					$page->add_control(
						'applychanges4',
						array(
							'label'       => __( 'Apply', 'kata-plus' ),
							'type'        => Controls_Manager::BUTTON,
							'separator'   => 'before',
							'button_type' => 'success',
							'text'        => __( 'Apply', 'kata-plus' ),
							'description' => __( 'Click the Apply button to save your changes.', 'kata-plus' ),
							'event'       => 'applychanges',
						)
					);
					$page->end_controls_section();

					$page->start_controls_section(
						'kata_page_options_footer',
						array(
							'label' => esc_html__( 'Footer', 'kata-plus' ),
							'tab'   => Controls_Manager::TAB_SETTINGS,
						)
					);
					$page->add_control(
						'show_footer',
						array(
							'label'        => __( 'Footer:', 'kata-plus' ),
							'type'         => Controls_Manager::SWITCHER,
							'label_on'     => __( 'Show', 'kata-plus' ),
							'label_off'    => __( 'Hide', 'kata-plus' ),
							'return_value' => '1',
							'default'      => '1',
						)
					);
					$page->add_control(
						'applychanges5',
						array(
							'label'       => __( 'Apply', 'kata-plus' ),
							'type'        => Controls_Manager::BUTTON,
							'separator'   => 'before',
							'button_type' => 'success',
							'text'        => __( 'Apply', 'kata-plus' ),
							'description' => __( 'Click the Apply button to save your changes.', 'kata-plus' ),
							'event'       => 'applychanges',
						)
					);
					$page->end_controls_section();

					$page->start_controls_section(
						'kata_page_options_page_style',
						array(
							'label' => esc_html__( 'Page Stylers', 'kata-plus' ),
							'tab'   => Controls_Manager::TAB_SETTINGS,
						)
					);
					$page->add_control(
						'styler_body',
						array(
							'label'    => esc_html__( 'Body Styler', 'kata-plus' ),
							'type'     => 'styler',
							'selector' => 'body',
							'isSVG'    => false,
							'isInput'  => false,
							'wrapper'  => ' ',
						)
					);
					$page->add_control(
						'styler_content',
						array(
							'label'    => esc_html__( 'Content Styler', 'kata-plus' ),
							'type'     => 'styler',
							'selector' => '.kata-content',
							'isSVG'    => false,
							'isInput'  => false,
							'wrapper'  => 'body',
						)
					);
					$page->end_controls_section();

					$settings                                        = $page->get_settings();
					$settings['kata_show_header']                    = isset( $settings['kata_show_header'] ) && '1' == $settings['kata_show_header'] ? $settings['kata_show_header'] : '0';
					$settings['kata_make_header_transparent']        = isset( $settings['kata_make_header_transparent'] ) ? $settings['kata_make_header_transparent'] : '0';
					$settings['kata_header_transparent_white_color'] = isset( $settings['kata_header_transparent_white_color'] ) ? $settings['kata_header_transparent_white_color'] : '0';
					$settings['full_page_slider']                    = isset( $settings['full_page_slider'] ) ? $settings['full_page_slider'] : '0';
					$settings['full_page_slider_navigation']         = isset( $settings['full_page_slider_navigation'] ) ? $settings['full_page_slider_navigation'] : '0';
					$settings['full_page_slider_loop_bottom']        = isset( $settings['full_page_slider_loop_bottom'] ) ? $settings['full_page_slider_loop_bottom'] : '0';
					$settings['full_page_slider_loop_top']           = isset( $settings['full_page_slider_loop_top'] ) ? $settings['full_page_slider_loop_top'] : '0';
					$settings['show_footer']                         = isset( $settings['show_footer'] ) && $settings['show_footer'] == '1' ? '1' : '0';
					$settings['kata_show_page_title']                = isset( $settings['kata_show_page_title'] ) ? $settings['kata_show_page_title'] : '0';
					$settings['full_page_slider']                    = isset( $settings['full_page_slider'] ) ? $settings['full_page_slider'] : '0';

					update_post_meta( get_the_ID(), 'kata_show_header', $settings['kata_show_header'] );
					update_post_meta( get_the_ID(), 'kata_make_header_transparent', $settings['kata_make_header_transparent'] );
					update_post_meta( get_the_ID(), 'kata_header_transparent_white_color', $settings['kata_header_transparent_white_color'] );
					update_post_meta( get_the_ID(), 'kata_show_page_title', $settings['kata_show_page_title'] );
					update_post_meta( get_the_ID(), 'kata_page_title_text', $settings['kata_page_title_text'] );
					update_post_meta( get_the_ID(), 'kata_styler_page_title', $settings['kata_styler_page_title'] );
					update_post_meta( get_the_ID(), 'sidebar_position', $settings['sidebar_position'] );
					if ( isset( $settings['full_page_slider'] ) ) {
						update_post_meta( get_the_ID(), 'full_page_slider', $settings['full_page_slider'] );
					}
					if ( isset( $settings['full_page_slider_navigation'] ) ) {
						update_post_meta( get_the_ID(), 'full_page_slider_navigation', $settings['full_page_slider_navigation'] );
					}
					if ( isset( $settings['full_page_slider_navigation_position'] ) ) {
						update_post_meta( get_the_ID(), 'full_page_slider_navigation_position', $settings['full_page_slider_navigation_position'] );
					}
					if ( isset( $settings['full_page_slider_loop_bottom'] ) ) {
						update_post_meta( get_the_ID(), 'full_page_slider_loop_bottom', $settings['full_page_slider_loop_bottom'] );
					}
					if ( isset( $settings['full_page_slider_loop_top'] ) ) {
						update_post_meta( get_the_ID(), 'full_page_slider_loop_top', $settings['full_page_slider_loop_top'] );
					}
					if ( isset( $settings['full_page_slider_scrolling_speed'] ) ) {
						update_post_meta( get_the_ID(), 'full_page_slider_scrolling_speed', $settings['full_page_slider_scrolling_speed'] );
					}
					update_post_meta( get_the_ID(), 'show_footer', $settings['show_footer'] );
					update_post_meta( get_the_ID(), 'styler_body', $settings['styler_body'] );
					update_post_meta( get_the_ID(), 'styler_content', $settings['styler_content'] );
				}
			}
		}

		/**
		 * Get Theme Name or White Label Name
		 *
		 * @since   1.0.0
		 */
		public function the_theme_name() {
			$white_label = class_exists( 'Kata_Plus_Pro_Theme_Options_Functions' ) && Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->name ? Kata_Plus_Pro_Theme_Options_Functions::get_instance()->white_label()->name : '';
			if ( ! $white_label ) :
				return Kata_Plus_Helpers::get_theme()->name . ' ';
			else :
				return $white_label . ' ';
			endif;
		}

		/**
		 * Load dependencies.
		 *
		 * @since   1.0.0
		 */
		public function dependencies() {
			// Builders
			foreach ( glob( self::$dir . 'builders/*' ) as $file ) {
				if ( basename( $file, '.php' ) !== 'content-template' ) {
					Kata_Plus_Autoloader::load( dirname( $file ), basename( $file, '.php' ) );
				}
			}
		}

		/**
		 * Configuration.
		 *
		 * @since   1.0.0
		 */
		public function config() {
			// Add Post types support
			add_post_type_support( 'mega_menu', 'elementor' );
		}

		/**
		 * Elementor Compatiblities.
		 *
		 * @since   1.0.0
		 */
		public function elementorinit() {

			$page = isset( $_REQUEST['post'] ) ? get_the_title( $_REQUEST['post'] ) : '';

			switch ( $page ) {
				case 'Kata Blog':
				case 'Kata Archive':
				case 'Kata Search':
				case 'Kata Single':
				case 'Kata Author':
					// Blog & Post
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor_blog_and_post',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Blog & Posts', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					// Usefull
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor_most_usefull',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Essentials', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					// Normals
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Pro', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					// Header
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor_header',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Header', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					// Courses
					if ( is_plugin_active( 'learnpress/learnpress.php' ) ) {
						// LearnPress Course
						Plugin::instance()->elements_manager->add_category(
							'kata_plus_elementor_learnpress_course',
							array(
								'title' => self::get_instance()->the_theme_name() . esc_html__( 'Courses', 'kata-plus' ),
								'icon'  => 'eicon-font',
							),
							1
						);
					}
					break;
				case 'Kata Header':
				case 'Kata Sticky Header':
					// Header
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor_header',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Header', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					// Usefull
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor_most_usefull',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Essentials', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					// Normals
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Pro', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					// Blog & Post
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor_blog_and_post',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Blog & Posts', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					// Courses
					if ( is_plugin_active( 'learnpress/learnpress.php' ) ) {
						// LearnPress Course
						Plugin::instance()->elements_manager->add_category(
							'kata_plus_elementor_learnpress_course',
							array(
								'title' => self::get_instance()->the_theme_name() . esc_html__( 'Courses', 'kata-plus' ),
								'icon'  => 'eicon-font',
							),
							1
						);
					}
					break;
				case 'Single Course':
					if ( is_plugin_active( 'learnpress/learnpress.php' ) ) {
						// LearnPress Course
						Plugin::instance()->elements_manager->add_category(
							'kata_plus_elementor_learnpress_course',
							array(
								'title' => self::get_instance()->the_theme_name() . esc_html__( 'Courses', 'kata-plus' ),
								'icon'  => 'eicon-font',
							),
							1
						);
					}
					// Blog & Post
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor_blog_and_post',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Blog & Posts', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					// Usefull
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor_most_usefull',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Essentials', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					// Normals
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Pro', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					// Header
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor_header',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Header', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					break;
				case 'Kata Footer':
				case 'Kata 404':
				default:
					// Usefull
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor_most_usefull',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Essentials', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					// Normals
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Pro', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					// Blog & Post
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor_blog_and_post',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Blog & Posts', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					// Header
					Plugin::instance()->elements_manager->add_category(
						'kata_plus_elementor_header',
						array(
							'title' => self::get_instance()->the_theme_name() . esc_html__( 'Header', 'kata-plus' ),
							'icon'  => 'eicon-font',
						),
						1
					);
					// Courses
					if ( is_plugin_active( 'learnpress/learnpress.php' ) ) {
						// LearnPress Course
						Plugin::instance()->elements_manager->add_category(
							'kata_plus_elementor_learnpress_course',
							array(
								'title' => self::get_instance()->the_theme_name() . esc_html__( 'Courses', 'kata-plus' ),
								'icon'  => 'eicon-font',
							),
							1
						);
					}
					break;
			}
		}

		/**
		 * Elementor editor styles.
		 *
		 * @since   1.0.0
		 */
		public function editor_styles() {
			$kata_options = get_option( 'kata_options' );
			$ui_theme     = Elementor\Core\Settings\Manager::get_settings_managers( 'editorPreferences' )->get_model()->get_settings( 'ui_theme' );
			if ( $ui_theme === 'auto' && isset( $kata_options['prefers_color_scheme'] ) ) {
				$ui_theme = $kata_options['prefers_color_scheme'];
			}
			$file_name = 'elementor-editor.css';
			$type      = 'light';
			if ( $ui_theme == 'dark' ) {
				$file_name = 'elementor-editor-dark.css';
				$type      = 'dark';
			}
			$kata_options['prefers_color_scheme'] = $type;
			update_option( 'kata_options', $kata_options );
			if ( Plugin::$instance->editor->is_edit_mode() || get_current_screen()->id == 'customize' || Kata_Plus_Helpers::string_is_contain( get_current_screen()->id, 'toplevel_page_kata-plus' ) || Kata_Plus_Helpers::string_is_contain( get_current_screen()->id, 'kata_page_kata-plus' ) || Kata_Plus_Helpers::string_is_contain( get_current_screen()->id, 'appearance_page_kata' ) ) {
				wp_enqueue_style( 'kata-elementor-admin-' . $type, Kata_Plus::$assets . 'css/backend/' . $file_name, array(), Kata_Plus::$version );
			}
		}

		/**
		 * Elementor preview styles.
		 *
		 * @since   1.0.0
		 */
		public function preview_styles() {
			wp_enqueue_style( 'kata-elementor-admin', Kata_Plus::$assets . 'css/backend/elementor-preview.css', array(), Kata_Plus::$version );
			static::render_elementor_breakpoints_script( 'jquery' );
		}

		/**
		 * Elementor editor scripts.
		 *
		 * @since   1.0.0
		 */
		public function editor_scripts() {
			wp_enqueue_script( 'kata-elementor-admin', Kata_Plus::$assets . 'js/backend/elementor-editor.js', array( 'jquery', 'elementor-editor' ), Kata_Plus::$version, true );
			static::render_elementor_breakpoints_script( 'kata-elementor-admin' );
			static::render_kata_widgets_script( 'kata-elementor-admin' );
			/*
			$breakpoints = static::get_breakpoints();
			echo '<style id="kata-plus-elementor-breakpoints">';
			foreach ( $breakpoints as $b => $size ) {
				if ( $b == 'desktop' ) {
					continue;
				}
				$size = $size == 1025 ? $size - 1 : $size;
				$size = $size == 769 ? $size - 2 : $size;
				echo 'body.elementor-device-' . $b . ' #elementor-preview-responsive-wrapper {
						width: ' . ( $size ) . 'px ! important;
					}';
				echo "\n";
			}
			echo '</style>'; */
		}

		/**
		 * Render Elementor Breakpoints Var Script
		 *
		 * @param $handel string
		 * @since     1.0.0
		 */
		public function render_elementor_breakpoints_script( $handel ) {
			$breakpoints = static::get_breakpoints();
			wp_add_inline_script(
				$handel,
				'
					var ElementorBreakPoints = ' . json_encode( $breakpoints ) . ';
				'
			);
		}

		/**
		 * Render Kata Plus Widgets
		 *
		 * @param $handel inline script id
		 * @since 1.0.0
		 */
		public function render_kata_widgets_script( $handel ) {
			$widgets = array(
				'kata-plus-archive-posts'         => array(
					'name'               => 'kata-plus-archive-posts',
					'elType'             => 'widget',
					'title'              => 'Archive Posts',
					'icon'               => 'kata-widget kata-eicon-archive-posts',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-archive-posts',
					'categories'         => array( 'kata_plus_elementor_blog_and_post' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-archive-posts',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-author-page'           => array(
					'name'               => 'kata-plus-author-page',
					'elType'             => 'widget',
					'title'              => 'Author Page',
					'icon'               => 'kata-widget kata-eicon-call-to-action-page',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-author-page',
					'categories'         => array( 'kata_plus_elementor_blog_and_post' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-author-page',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-category-lists'        => array(
					'name'               => 'kata-plus-category-lists',
					'elType'             => 'widget',
					'title'              => 'Categories List',
					'icon'               => 'kata-widget kata-eicon-editor-list-ul',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-category-lists',
					'categories'         => array( 'kata_plus_elementor_blog_and_post' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-category-lists',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-next-prev-post'        => array(
					'name'               => 'kata-plus-next-prev-post',
					'elType'             => 'widget',
					'title'              => 'Next & Previous Post',
					'icon'               => 'kata-widget kata-eicon-post-navigation',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-next-prev-post',
					'categories'         => array( 'kata_plus_elementor_blog_and_post' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-next-prev-post',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-related-posts'         => array(
					'name'               => 'kata-plus-related-posts',
					'elType'             => 'widget',
					'title'              => 'Related Posts',
					'icon'               => 'kata-widget kata-eicon-post-list',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-related-posts',
					'categories'         => array( 'kata_plus_elementor_blog_and_post' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-related-posts',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-search-page'           => array(
					'name'               => 'kata-plus-search-page',
					'elType'             => 'widget',
					'title'              => 'Search Page',
					'icon'               => 'kata-widget kata-eicon-site-search',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-search-page',
					'categories'         => array( 'kata_plus_elementor_blog_and_post' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-search-page',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-social-share'          => array(
					'name'               => 'kata-plus-social-share',
					'elType'             => 'widget',
					'title'              => 'Social Share',
					'icon'               => 'kata-widget kata-eicon-social-icons',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-social-share',
					'categories'         => array( 'kata_plus_elementor_blog_and_post' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-social-share',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-cart'                  => array(
					'name'               => 'kata-plus-cart',
					'elType'             => 'widget',
					'title'              => 'Cart',
					'icon'               => 'kata-widget kata-eicon-cart',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-cart',
					'categories'         => array( 'kata_plus_elementor_header' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-cart',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-date'                  => array(
					'name'               => 'kata-plus-date',
					'elType'             => 'widget',
					'title'              => 'Date',
					'icon'               => 'kata-widget kata-eicon-date',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-date',
					'categories'         => array( 'kata_plus_elementor_header' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-date',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-language-switcher'     => array(
					'name'               => 'kata-plus-language-switcher',
					'elType'             => 'widget',
					'title'              => 'Language Switcher',
					'icon'               => 'kata-widget kata-eicon-text-area',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-language-switcher',
					'categories'         => array( 'kata_plus_elementor_header' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-language-switcher',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-login'                 => array(
					'name'               => 'kata-plus-login',
					'elType'             => 'widget',
					'title'              => 'Login',
					'icon'               => 'kata-widget kata-eicon-lock-user',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-login',
					'categories'         => array( 'kata_plus_elementor_header' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-login',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-hamburger-menu'        => array(
					'name'               => 'kata-plus-hamburger-menu',
					'elType'             => 'widget',
					'title'              => 'Hamburger Menu',
					'icon'               => 'kata-widget kata-eicon-menu-toggle',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-hamburger-menu',
					'categories'         => array( 'kata_plus_elementor_header' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-hamburger-menu',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-reservation'           => array(
					'name'               => 'kata-plus-reservation',
					'elType'             => 'widget',
					'title'              => 'Reservation',
					'icon'               => 'kata-widget kata-eicon-form-horizontal',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-reservation',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-reservation',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-breadcrumbs'           => array(
					'name'               => 'kata-plus-breadcrumbs',
					'elType'             => 'widget',
					'title'              => 'Breadcrumbs',
					'icon'               => 'kata-widget kata-eicon-breadcrumbs',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-breadcrumbs',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-breadcrumbs',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-portfolio-carousel'    => array(
					'name'               => 'kata-plus-portfolio-carousel',
					'elType'             => 'widget',
					'title'              => 'Portfolio Carousel',
					'icon'               => 'kata-widget kata-eicon-slider-push',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-portfolio-carousel',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-portfolio-carousel',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-comparison-slider'     => array(
					'name'               => 'kata-plus-comparison-slider',
					'elType'             => 'widget',
					'title'              => 'Comparison Slider',
					'icon'               => 'kata-widget kata-eicon-image-before-after',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-comparison-slider',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-comparison-slider',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-content-slider'        => array(
					'name'               => 'kata-plus-content-slider',
					'elType'             => 'widget',
					'title'              => 'Content Slider',
					'icon'               => 'kata-widget kata-eicon-post-slider',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-content-slider',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-content-slider',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-content-toggle'        => array(
					'name'               => 'kata-plus-content-toggle',
					'elType'             => 'widget',
					'title'              => 'Content Toggle',
					'icon'               => 'kata-widget kata-eicon-menu-toggle',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-content-toggle',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-content-toggle',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-countdown'             => array(
					'name'               => 'kata-plus-countdown',
					'elType'             => 'widget',
					'title'              => 'Countdown',
					'icon'               => 'kata-widget kata-eicon-countdown',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-countdown',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-countdown',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-divider'               => array(
					'name'               => 'kata-plus-divider',
					'elType'             => 'widget',
					'title'              => 'Divider',
					'icon'               => 'kata-widget kata-eicon-divider',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-divider',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-divider',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-domain-checker'        => array(
					'name'               => 'kata-plus-domain-checker',
					'elType'             => 'widget',
					'title'              => 'Domain Checker',
					'icon'               => 'kata-widget kata-eicon-site-search',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-domain-checker',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-domain-checker',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-employee-information'  => array(
					'name'               => 'kata-plus-employee-information',
					'elType'             => 'widget',
					'title'              => 'Employee Information',
					'icon'               => 'kata-widget kata-eicon-person',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-employee-information',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-employee-information',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-food-menu'             => array(
					'name'               => 'kata-plus-food-menu',
					'elType'             => 'widget',
					'title'              => 'Food Menu',
					'icon'               => 'kata-widget kata-eicon-menu-toggle',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-food-menu',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-food-menu',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-food-menu-toggle'      => array(
					'name'               => 'kata-plus-food-menu-toggle',
					'elType'             => 'widget',
					'title'              => 'Food Menu Toggle',
					'icon'               => 'kata-widget kata-eicon-menu-toggle',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-food-menu-toggle',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-food-menu-toggle',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-gift-cards'            => array(
					'name'               => 'kata-plus-gift-cards',
					'elType'             => 'widget',
					'title'              => 'Gift Cards',
					'icon'               => 'kata-widget kata-eicon-welcome',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-gift-cards',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-gift-cards',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-google-map'            => array(
					'name'               => 'kata-plus-google-map',
					'elType'             => 'widget',
					'title'              => 'Google Map',
					'icon'               => 'kata-widget kata-eicon-google-maps',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-google-map',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-google-map',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-image-carousel'        => array(
					'name'               => 'kata-plus-image-carousel',
					'elType'             => 'widget',
					'title'              => 'Image Carousel',
					'icon'               => 'kata-widget kata-eicon-slider-push',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-image-carousel',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-image-carousel',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-image-hover-zoom'      => array(
					'name'               => 'kata-plus-image-hover-zoom',
					'elType'             => 'widget',
					'title'              => 'Image Hover Zoom',
					'icon'               => 'kata-widget kata-eicon-zoom-in',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-image-hover-zoom',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-image-hover-zoom',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-instagram'             => array(
					'name'               => 'kata-plus-instagram',
					'elType'             => 'widget',
					'title'              => 'Instagram',
					'icon'               => 'kata-widget kata-eicon-gallery-grid',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-instagram',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-instagram',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-portfolio-masonry'     => array(
					'name'               => 'kata-plus-portfolio-masonry',
					'elType'             => 'widget',
					'title'              => 'Portfolio Masonry',
					'icon'               => 'kata-widget kata-eicon-posts-masonry',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-portfolio-masonry',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-portfolio-masonry',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-pricing-plan'          => array(
					'name'               => 'kata-plus-pricing-plan',
					'elType'             => 'widget',
					'title'              => 'Pricing Plan',
					'icon'               => 'kata-widget kata-eicon-price-table',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-pricing-plan',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-pricing-plan',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-pricing-table'         => array(
					'name'               => 'kata-plus-pricing-table',
					'elType'             => 'widget',
					'title'              => 'Pricing Table',
					'icon'               => 'kata-widget kata-eicon-price-table',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-pricing-table',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-pricing-table',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-progress-bar'          => array(
					'name'               => 'kata-plus-progress-bar',
					'elType'             => 'widget',
					'title'              => 'Progress Bar',
					'icon'               => 'kata-widget kata-eicon-skill-bar',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-progress-bar',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-progress-bar',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-recipes'               => array(
					'name'               => 'kata-plus-recipes',
					'elType'             => 'widget',
					'title'              => 'Recipes',
					'icon'               => 'kata-widget kata-eicon-call-to-action',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-recipes',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-recipes',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-seo-analytic'          => array(
					'name'               => 'kata-plus-seo-analytic',
					'elType'             => 'widget',
					'title'              => 'SEO Analytic',
					'icon'               => 'kata-widget kata-eicon-dashboard',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-seo-analytic',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-seo-analytic',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-table'                 => array(
					'name'               => 'kata-plus-table',
					'elType'             => 'widget',
					'title'              => 'Table',
					'icon'               => 'kata-widget kata-eicon-date',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-table',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-table',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-tabs'                  => array(
					'name'               => 'kata-plus-tabs',
					'elType'             => 'widget',
					'title'              => 'Tabs',
					'icon'               => 'kata-widget kata-eicon-tabs',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-tabs',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-tabs',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-task-process'          => array(
					'name'               => 'kata-plus-task-process',
					'elType'             => 'widget',
					'title'              => 'Task Process',
					'icon'               => 'kata-widget kata-eicon-form-vertical',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-task-process',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-task-process',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-team'                  => array(
					'name'               => 'kata-plus-team',
					'elType'             => 'widget',
					'title'              => 'Team',
					'icon'               => 'kata-widget kata-eicon-person',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-team',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-team',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-testimonials-vertical' => array(
					'name'               => 'kata-plus-testimonials-vertical',
					'elType'             => 'widget',
					'title'              => 'Testimonials Vertical',
					'icon'               => 'kata-widget kata-eicon-testimonial-carousel',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-testimonials-vertical',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-testimonials-vertical',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-timeline'              => array(
					'name'               => 'kata-plus-timeline',
					'elType'             => 'widget',
					'title'              => 'Timeline',
					'icon'               => 'kata-widget kata-eicon-time-line',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-timeline',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-timeline',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-toggle-sidebox'        => array(
					'name'               => 'kata-plus-toggle-sidebox',
					'elType'             => 'widget',
					'title'              => 'Toggle Sidebox',
					'icon'               => 'kata-widget kata-eicon-h-align-left',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-toggle-sidebox',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-toggle-sidebox',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-template-loader'       => array(
					'name'               => 'kata-plus-template-loader',
					'elType'             => 'widget',
					'title'              => 'Template Loader',
					'icon'               => 'kata-widget kata-eicon-template-loader',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-template-loader',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-template-loader',
					'show_in_panel'      => true,
					'editable'           => false,
				),
				'kata-plus-audio-player'          => array(
					'name'               => 'kata-plus-audio-player',
					'elType'             => 'widget',
					'title'              => 'Template Loader',
					'icon'               => 'kata-widget kata-eicon-audio-player',
					'reload_preview'     => false,
					'help_url'           => '',
					'widget_type'        => 'kata-plus-audio-player',
					'categories'         => array( 'kata_plus_elementor' ),
					'html_wrapper_class' => 'elementor-widget-kata-plus-audio-player',
					'show_in_panel'      => true,
					'editable'           => false,
				),
			);
			wp_add_inline_script(
				$handel,
				apply_filters(
					'kata_plus_pro_widgets',
					'var KataPlusPro = false; var KataProWidgets = ' . json_encode( $widgets ) . ';'
				)
			);
		}

		/**
		 * Elementor Breakpoints
		 *
		 * @since     1.0.0
		 */
		public static function get_breakpoints() {

			if ( ! get_option( 'elementor_viewport_md' ) ) {
				update_option( 'elementor_viewport_md', 481 );
			}

			if ( ! get_option( 'elementor_viewport_lg' ) ) {
				update_option( 'elementor_viewport_lg', 769 );
			}

			// 'mobile'          => get_option( 'elementor_viewport_md' ) ? get_option( 'elementor_viewport_md' ) : 480,
			// 'tablet'          => get_option( 'elementor_viewport_lg' ) ? get_option( 'elementor_viewport_lg' ) : 768,
			$breakpoints = array(
				'smallmobile'     => 320,
				'mobile'          => 481,
				'tablet'          => 769,
				'tabletlandscape' => 1024,
				'laptop'          => 1366,
				'desktop'         => 1439,
			);
			return $breakpoints;
		}

		/**
		 * Module custom css frontned.
		 *
		 * @since   1.0.0
		 */
		public function module_custom_css_frontend( $post_css, $element ) {
			if ( $post_css instanceof Dynamic_CSS ) {
				return;
			}
			$element_settings = $element->get_settings();
			$custom_css       = ! empty( $element_settings['custom_css'] ) ? trim( $element_settings['custom_css'] ) : '';
			if ( ! empty( $custom_css ) ) {
				$post_css->get_stylesheet()->add_raw_css( $custom_css );
			} else {
				return;
			}
		}

		/**
		 * Module custom css backend.
		 *
		 * @since   1.0.0
		 */
		public static function module_custom_css_editor( $custom_css ) {
			if ( ! empty( $custom_css ) && Plugin::$instance->editor->is_edit_mode() ) {
				echo '<style>' . wp_strip_all_tags( $custom_css ) . '</style>';
			}
		}

		/**
		 * is Edit mode.
		 *
		 * @since   1.0.0
		 */
		public static function is_edit_mode() {
			return Plugin::$instance->editor->is_edit_mode();
		}

		/**
		 * Controls.
		 *
		 * @since   1.0.0
		 */
		public function controls() {
			foreach ( glob( self::$dir . 'controls/*' ) as $file ) {
				Kata_Plus_Autoloader::load( $file, 'control' );
			}
		}

		/**
		 * After Section Start.
		 *
		 * @since   1.0.0
		 */
		public function elementor_section( $element, $section_id, $args ) {
			if ( 'common' === $element->get_name() && '_section_style' === $section_id ) {
				$element->add_control(
					'common_styler',
					array(
						'label'    => esc_html__( 'Widget Wrapper', 'kata-plus' ),
						'type'     => 'styler',
						'selector' => '',
					)
				);
			}
		}

		/**
		 * After Section Start.
		 *
		 * @since   1.0.0
		 */
		public function column_layout_options( $element, $args ) {
			$element->add_responsive_control(
				'_max_inline_size',
				array(
					'label'     => __( 'Column Max Width ( px )', 'kata-plus' ),
					'type'      => Controls_Manager::NUMBER,
					'min'       => 0,
					'step'      => 1,
					'selectors' => array(
						'{{WRAPPER}}' => 'max-width: {{SIZE}}px;',
					),
				)
			);
			$element->add_responsive_control(
				'_min_inline_size',
				array(
					'label'     => __( 'Column Min Width ( px )', 'kata-plus' ),
					'type'      => Controls_Manager::NUMBER,
					'min'       => 0,
					'step'      => 1,
					'selectors' => array(
						'{{WRAPPER}}' => 'min-width: {{SIZE}}px;',
					),
				)
			);
			$element->add_responsive_control(
				'_max_height_inline_size',
				array(
					'label'     => __( 'Column Max Height ( px )', 'kata-plus' ),
					'type'      => Controls_Manager::NUMBER,
					'min'       => 0,
					'step'      => 1,
					'selectors' => array(
						'{{WRAPPER}}' => 'max-height: {{SIZE}}px;',
					),
				)
			);
			$element->add_responsive_control(
				'_min_height_inline_size',
				array(
					'label'     => __( 'Column Min Height ( px )', 'kata-plus' ),
					'type'      => Controls_Manager::NUMBER,
					'min'       => 0,
					'step'      => 1,
					'selectors' => array(
						'{{WRAPPER}}' => 'min-height: {{SIZE}}px;',
					),
				)
			);
		}

		/**
		 * Column Styling Options.
		 *
		 * @since   1.0.0
		 */
		public function column_style_options( $element, $args ) {
			$element->start_controls_section(
				'column_advanced',
				array(
					'label' => esc_html__( 'Styler', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
			$element->add_control(
				'column_styler',
				array(
					'label'    => esc_html__( 'Column Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '',
				)
			);
			$element->add_control(
				'column_inner_wrapper_styler',
				array(
					'label'    => esc_html__( 'Column Inner', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.elementor-element-populated',
				)
			);
			$element->end_controls_section();
		}

		/**
		 * Column Styling Options.
		 *
		 * @since   1.0.0
		 */
		public function section_style_options( $element, $args ) {
			$element->start_controls_section(
				'section_style_options',
				array(
					'label' => esc_html__( 'Styler', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
			$element->add_control(
				'section_styler',
				array(
					'label'    => esc_html__( 'Section Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '',
				)
			);
			$element->end_controls_section();
		}

		/**
		 * Container Styling Options.
		 *
		 * @since   1.0.0
		 */
		public function container_style_options( $element, $args ) {
			$element->start_controls_section(
				'container_style_options',
				array(
					'label' => esc_html__( 'Styler', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
			$element->add_control(
				'container_style',
				array(
					'label'    => esc_html__( 'Container Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '',
				)
			);
			$element->end_controls_section();
		}

		/**
		 * Common controls.
		 *
		 * @since   1.0.0
		 */
		public function common_controls( $self ) {
			// Custom CSS section
			$self->start_controls_section(
				'custom_css_section',
				array(
					'label' => esc_html__( 'Custom CSS', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
			$self->add_control(
				'custom_css_gopro',
				array(
					'label' => __( 'Custom CSS', 'kata-plus' ),
					'type'  => Controls_Manager::RAW_HTML,
					'raw'   => '<div class="elementor-nerd-box">
						<img class="elementor-nerd-box-icon" src="' . esc_attr( ELEMENTOR_ASSETS_URL . 'images/go-pro.svg' ) . '">
						<div class="elementor-nerd-box-title">' . __( 'Meet Our Custom CSS', 'kata-plus' ) . '</div>
						<div class="elementor-nerd-box-message">' . __( 'Custom CSS lets you add CSS code to any widget, and see it render live right in the editor.', 'kata-plus' ) . '</div>
						<a class="elementor-nerd-box-link elementor-button elementor-button-default elementor-button-go-pro" href="https://my.climaxthemes.com/buy" target="_blank">' . __( 'Kata Pro', 'kata-plus' ) . '</a>
					</div>',
				)
			);
			$self->end_controls_section();

			// Parallax Motion section
			$self->start_controls_section(
				'section_box_parallax',
				array(
					'label' => esc_html__( 'Parallax Motion', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
			$self->add_control(
				'prallax_motion_gopro',
				array(
					'label' => __( 'Motion Effects', 'kata-plus' ),
					'type'  => Controls_Manager::RAW_HTML,
					'raw'   => '<div class="elementor-nerd-box">
						<img class="elementor-nerd-box-icon" src="' . esc_attr( ELEMENTOR_ASSETS_URL . 'images/go-pro.svg' ) . '">
						<div class="elementor-nerd-box-title">' . __( 'Meet Our Custom CSS', 'kata-plus' ) . '</div>
						<div class="elementor-nerd-box-message">' . __( 'Custom CSS lets you add CSS code to any widget, and see it render live right in the editor.', 'kata-plus' ) . '</div>
						<a class="elementor-nerd-box-link elementor-button elementor-button-default elementor-button-go-pro" href="https://my.climaxthemes.com/buy" target="_blank">' . __( 'Kata Pro', 'kata-plus' ) . '</a>
					</div>',
				)
			);
			$self->end_controls_section();

			// Presets
			$self->start_controls_section(
				'section_kata_plus_presets',
				array(
					'label' => __( 'Presets', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
			$self->add_control(
				'presets_gopro',
				array(
					'label' => __( 'Motion Effects', 'kata-plus' ),
					'type'  => Controls_Manager::RAW_HTML,
					'raw'   => '<div class="elementor-nerd-box">
						<img class="elementor-nerd-box-icon" src="' . esc_attr( ELEMENTOR_ASSETS_URL . 'images/go-pro.svg' ) . '">
						<div class="elementor-nerd-box-title">' . __( 'Meet Our Custom CSS', 'kata-plus' ) . '</div>
						<div class="elementor-nerd-box-message">' . __( 'Custom CSS lets you add CSS code to any widget, and see it render live right in the editor.', 'kata-plus' ) . '</div>
						<a class="elementor-nerd-box-link elementor-button elementor-button-default elementor-button-go-pro" href="https://my.climaxthemes.com/buy" target="_blank">' . __( 'Kata Pro', 'kata-plus' ) . '</a>
					</div>',
				)
			);
			$self->end_controls_section();
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
					if ( ! empty( $colorbase ) )
						echo '--ct-color-primary: ' . esc_html( $colorbase ) . ' !important;';
					if ( ! empty( $secondary_color ) )
						echo '--ct-color-button-primary: ' . esc_html( $secondary_color ) . ' !important;';
					echo '}';
					echo '[data-color*="var(--ct-"] {font-size: 0 !important;}';
				echo '</style>';
			}
		}

		/**
		 * Add kata swatches to styler.
		 *
		 * @since   1.3.1
		 */
		public function add_color_to_styler_swatches( $swatches ) {
			$kata_base_color     = get_theme_mod( 'kata_base_color', '' );
			$kata_color_primary  = get_theme_mod( 'kata-color-primary', '' );
			$secondary_color     = get_theme_mod( 'kata-color-secondary', '' );

			if ( ! empty( $kata_base_color ) ) $swatches[] = 'var(--ct-base-color)';

			if ( ! empty( $kata_color_primary ) ) $swatches[] = 'var(--ct-color-primary)';

			if ( ! empty( $secondary_color ) ) $swatches[] = 'var(--ct-color-button-primary)';

			return $swatches;
		}
	} // class

	Kata_Plus_Elementor::get_instance();
}

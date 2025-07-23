<?php
/**
 * Search module config.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;

class Kata_Plus_Search extends Widget_Base {
	public function get_name() {
		return 'kata-plus-search';
	}

	public function get_title() {
		return esc_html__( 'Search', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-search';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_header' );
	}

	public function get_script_depends() {
		return array( 'kata-plus-search' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-search' );
	}

	protected function register_controls() {
		// Content options Start
		$this->start_controls_section(
			'section_text_content',
			array(
				'label' => esc_html__( 'Search Settings', 'kata-plus' ),
			)
		);
		$this->add_control(
			'livesearch',
			array(
				'label'        => __( 'Live Search', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'kata-plus' ),
				'label_off'    => __( 'Hide', 'kata-plus' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'simple',
				'options' => array(
					'simple' => __( 'Simple', 'kata-plus' ),
					'modal'  => __( 'Modal', 'kata-plus' ),
					'toggle' => __( 'Toggle', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'text',
			array(
				'label'     => esc_html__( 'Modal Placeholder', 'kata-plus' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Search', 'kata-plus' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'layout!' => 'simple',
				),
			)
		);
		$this->add_control(
			'toggleplaceholder',
			array(
				'label'     => __( 'Icon Type', 'kata-plus' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'placeholder_icon',
				'toggle'    => true,
				'options'   => array(
					'image'            => array(
						'title' => __( 'Image', 'kata-plus' ),
						'icon'  => 'eicon-image',
					),
					'placeholder_icon' => array(
						'title' => __( 'Icon', 'kata-plus' ),
						'icon'  => 'eicon-info-circle-o',
					),
				),
				'condition' => array(
					'layout!' => 'simple',
				),
			)
		);
		$this->add_control(
			'symbol',
			array(
				'label'     => __( 'Image/SVG', 'kata-plus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'imagei',
				'options'   => array(
					'imagei' => __( 'Image', 'kata-plus' ),
					'svg'    => __( 'SVG', 'kata-plus' ),
				),
				'condition' => array(
					'toggleplaceholder' => array(
						'image',
					),
					'layout!'           => 'simple',
				),
			)
		);
		$this->add_control(
			'image',
			array(
				'label'     => __( 'Choose Image', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'toggleplaceholder' => array(
						'image',
						'svg',
					),
					'layout!'           => 'simple',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'large',
				'separator' => 'none',
				'condition' => array(
					'toggleplaceholder' => array(
						'image',
					),
					'layout!'           => 'simple',
				),
			)
		);
		$this->add_control(
			'placeholder_icon',
			array(
				'label'     => esc_html__( 'Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/search',
				'condition' => array(
					'toggleplaceholder' => array(
						'placeholder_icon',
					),
					'layout!'           => 'simple',
				),
			)
		);
		$posttypes = get_post_types( array( 'public' => true ) );
		array_push( $posttypes, 'all' );
		$this->add_control(
			'posttype',
			array(
				'label'     => __( 'Post Type', 'kata-plus' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => '0',
				'options'   => str_replace( '_', ' ', $posttypes ),
				'condition' => array(
					'livesearch' => 'yes',
				),
			)
		);
		$taxonomies = get_taxonomies( array( 'public' => true ), 'names', 'and' );
		$taxonomies['disable'] = __( 'Disable', 'kata-plus' );
		unset( $taxonomies['post_format'] );
		$this->add_control(
			'taxonomy',
			array(
				'label'       => __( 'Categories', 'kata-plus' ),
				'description' => __( 'It will add a dropdown and make you able to select a category', 'kata-plus' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'disable',
				'options'   => str_replace( '_', ' ', $taxonomies ),
				'condition' => array(
					'livesearch' => 'yes',
				),
			)
		);
		$this->add_control(
			'placeholder',
			array(
				'label'   => __( 'Placeholder', 'kata-plus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Search…', 'kata-plus' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'button',
			array(
				'label'   => __( 'Button', 'kata-plus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Search', 'kata-plus' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'icon',
			array(
				'label' => esc_html__( 'Button Icon', 'kata-plus' ),
				'type'  => 'kata_plus_icons',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_placeholder',
			array(
				'label' => esc_html__( 'Modal Placeholder', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_place_holder_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kt-search-open-as',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_place_holder_icon',
			array(
				'label'     => esc_html__( 'Icon', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kt-toggle-wrapper .kata-icon',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'toggleplaceholder' => 'placeholder_icon',
				),
			)
		);
		$this->add_control(
			'styler_place_holder_icon_uploaded',
			array(
				'label'     => esc_html__( 'Icon', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kt-toggle-wrapper .kata-svg-icon',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'toggleplaceholder' => 'image',
					'symbol'            => 'svg',
				),
			)
		);
		$this->add_control(
			'styler_place_holder_image',
			array(
				'label'     => esc_html__( 'Image', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kt-toggle-wrapper img',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'toggleplaceholder' => 'image',
					'symbol'            => 'imagei',
				),
			)
		);
		$this->add_control(
			'styler_place_holder_text',
			array(
				'label'    => esc_html__( 'Text', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kt-search-open-as .kt-search-toggle-text',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			array(
				'label' => esc_html__( 'Modal Content', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_wrap',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-search-form',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_input_search',
			array(
				'label'    => esc_html__( 'Search Field', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-search-form input[type="search"]',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_input_search_placeholder',
			array(
				'label'    => esc_html__( 'Search Placeholder', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-search-form input[type="search"]::placeholder',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_input_button',
			array(
				'label'    => esc_html__( 'Button', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-search-form input[type="submit"]',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_modal_overlay',
			array(
				'label'     => esc_html__( 'Overlay', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kt-search-overlay',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'layout' => 'modal',
				),
			)
		);
		$this->add_control(
			'icon_style_error',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'In order to change the color of icons, Open Styler and navigate to the SVG tab (all Kata icons is SVG)', 'kata-plus' ),
				'content_classes' => 'kata-plus-elementor-error',
			)
		);
		$this->add_control(
			'styler_button_icon',
			array(
				'label'    => esc_html__( 'Button Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-search-form .kata-search-icon i.kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_close_icon',
			array(
				'label'    => esc_html__( 'Close Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kt-close-search-modal .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_result',
			array(
				'label'     => esc_html__( 'Search Result', 'kata-plus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'livesearch' => 'yes',
				),
			),
		);
		$this->add_control(
			'styler_result',
			array(
				'label'     => esc_html__( 'Result wrap', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-search-ajax-result',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_result_for',
			array(
				'label'     => esc_html__( 'Result For', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-search-ajax-result-wrap .search-result-is',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_result_items',
			array(
				'label'     => esc_html__( 'Item', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-search-ajax-result li a',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_result_item',
			array(
				'label'     => esc_html__( 'Thumbnail', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-search-ajax-result li img',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_result_title',
			array(
				'label'     => esc_html__( 'Title', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-search-ajax-result li h4',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'livesearch' => array(
						'yes',
					),
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_search_dropdown',
			array(
				'label'     => esc_html__( 'Search Dropdown', 'kata-plus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'livesearch' => 'yes',
				),
			),
		);
		$this->add_control(
			'styler_dropdown',
			array(
				'label'     => esc_html__( 'Dropdown', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-search-icon select.kt-search-term',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		// Common controls
		do_action( 'kata_plus_common_controls', $this );
	}

	protected function render() {
		require __DIR__ . '/view.php';
	}
}

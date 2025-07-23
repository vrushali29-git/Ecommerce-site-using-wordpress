<?php
/**
 * Counter module config.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Kata_Plus_Counter extends Widget_Base {
	public function get_name() {
		return 'kata-plus-counter';
	}

	public function get_title() {
		return __( 'Counter', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-counter';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-counter' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_counter',
			array(
				'label' => __( 'Counter', 'kata-plus' ),
			)
		);
		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => __( 'Vertical', 'kata-plus' ),
					'vertical'   => __( 'Horizontal', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'aligne',
			array(
				'label'   => __( 'Alignment', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'dfalt',
				'options' => array(
					'dfalt' => __( 'Center', 'kata-plus' ),
					'left'  => __( 'Left', 'kata-plus' ),
					'right' => __( 'Right', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'starting_number',
			array(
				'label'   => __( 'Starting Number', 'kata-plus' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
			)
		);
		$this->add_control(
			'ending_number',
			array(
				'label'   => __( 'Ending Number', 'kata-plus' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 20000,
			)
		);
		$this->add_control(
			'prefix',
			array(
				'label'       => __( 'Number Prefix', 'kata-plus' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '$',
				'placeholder' => '$',
			)
		);
		$this->add_control(
			'suffix',
			array(
				'label'       => __( 'Number Suffix', 'kata-plus' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Plus', 'kata-plus' ),
			)
		);
		$this->add_control(
			'duration',
			array(
				'label'   => __( 'Animation Duration', 'kata-plus' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2000,
				'min'     => 100,
				'step'    => 100,
			)
		);
		$this->add_control(
			'thousand_separator',
			array(
				'label'     => __( 'Thousand Separator', 'kata-plus' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Show', 'kata-plus' ),
				'label_off' => __( 'Hide', 'kata-plus' ),
			)
		);
		$this->add_control(
			'thousand_separator_char',
			array(
				'label'     => __( 'Separator', 'kata-plus' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => array(
					'thousand_separator' => 'yes',
				),
				'options'   => array(
					''  => 'Default',
					'.' => 'Dot',
					' ' => 'Space',
				),
			)
		);
		$this->add_control(
			'symbol',
			array(
				'label'   => __( 'Icon Source', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => array(
					'icon'   => __( 'Kata Icon', 'kata-plus' ),
					'imagei' => __( 'Image', 'kata-plus' ),
					'svg'    => __( 'Svg', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'icon',
			array(
				'label'     => esc_html__( 'Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/linux',
				'condition' => array(
					'symbol' => array(
						'icon',
					),
				),
			)
		);
		$this->add_control(
			'image',
			array(
				'label'     => __( 'Choose Image', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'symbol' => array(
						'imagei',
						'svg',
					),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image',
				'default'   => 'full',
				'separator' => 'none',
				'condition' => array(
					'symbol' => array(
						'imagei',
						'svg',
					),
				),
			)
		);
		$this->add_control(
			'description',
			array(
				'label'   => __( 'Description', 'kata-plus' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'view',
			array(
				'label'   => __( 'View', 'kata-plus' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Styler', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-counter',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_content_wrapper',
			array(
				'label'    => esc_html__( 'Content Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-counter .content-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_number',
			array(
				'label'    => esc_html__( 'Number', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-counter-number-wrapper .elementor-counter-number',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_prefix',
			array(
				'label'    => esc_html__( 'Prefix', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-counter-number-prefix',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_suffix',
			array(
				'label'    => esc_html__( 'Suffix', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-counter-number-suffix',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_icon_wrap',
			array(
				'label'    => esc_html__( 'Icon Wrap', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-counter-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_icon',
			array(
				'label'     => esc_html__( 'Icon', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-counter-icon .kata-icon',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'icon',
					),
				),
			)
		);
		$this->add_control(
			'svg_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'To style the SVGs please go to Style SVG tab.', 'kata-plus' ),
				'content_classes' => 'kata-plus-elementor-error',
			)
		);
		$this->add_control(
			'styler_image_st',
			array(
				'label'     => esc_html__( 'Image', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-counter-icon img, {{WRAPPER}} .kata-plus-counter-icon .kata-svg-icon',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'imagei',
					),
				),
			)
		);
		$this->add_control(
			'styler_description',
			array(
				'label'    => esc_html__( 'Description', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-counter-text-wrap p',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_svg',
			array(
				'label' => esc_html__( 'SVG', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'symbol' => array(
						'svg',
					),
				),
			)
		);
		$this->add_control(
			'important_note2',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Because certain SVGs use different tags for styling, you need to use the options below to style the uploaded SVG. They SVG tab in the Styler is there to do this.', 'kata-plus' ),
				'content_classes' => 'kata-plus-elementor-error',
				'condition' => array(
					'symbol' => array(
						'svg',
					),
				),
			)
		);
		$this->add_control(
			'styler_icon_path',
			array(
				'label'    => esc_html__( 'Path', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-svg-icon path',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'svg',
					),
				),
			)
		);
		$this->add_control(
			'styler_icon_rect',
			array(
				'label'    => esc_html__( 'Rect', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-svg-icon rect',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'svg',
					),
				),
			)
		);
		$this->add_control(
			'styler_icon_line',
			array(
				'label'    => esc_html__( 'Line', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-svg-icon line',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'svg',
					),
				),
			)
		);
		$this->add_control(
			'styler_icon_circel',
			array(
				'label'    => esc_html__( 'Circel', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-svg-icon circle',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'svg',
					),
				),
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

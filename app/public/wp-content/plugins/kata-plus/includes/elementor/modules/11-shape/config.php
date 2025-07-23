<?php
/**
 * Shape module config.
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
use Elementor\Group_Control_Image_Size;

class Kata_Shape extends Widget_Base {
	public function get_name() {
		return 'kata-plus-shape';
	}

	public function get_title() {
		return esc_html__( 'Shape', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-circle';
	}

	public function get_script_depends() {
		return array( 'kata-jquery-enllax' );
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-shape' );
	}

	protected function register_controls() {
		// Content options Start
		$this->start_controls_section(
			'section_text_content',
			array(
				'label' => esc_html__( 'Shape', 'kata-plus' ),
			)
		);

		$this->add_control(
			'shape',
			array(
				'label'       => __( 'Shape', 'kata-plus' ),
				'description' => __( 'Create circles, squares, and other shapes.', 'kata-plus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'circle',
				'options'     => array(
					'circle' => __( 'Circle', 'kata-plus' ),
					'square' => __( 'Square', 'kata-plus' ),
					'custom' => __( 'Custom Designing', 'kata-plus' ),
					'img'    => __( 'Image', 'kata-plus' ),
					'svg'    => __( 'Svg', 'kata-plus' ),
				),
			)
		);

		$this->add_control(
			'custom_dec',
			array(
				'label'     => __( 'If you choose this option, you have an empty DIV tag that via the styler in style tab you can syle your DIV.', 'kata-plus' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'shape' => 'custom',
				),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => __( 'Choose Image', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'shape' => array( 'img', 'svg' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'full',
				'separator' => 'none',
				'condition' => array(
					'shape' => array( 'img', 'svg' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'parent_shape',
			array(
				'label' => esc_html__( 'Wrapper', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_widget_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-shape-element',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'shape_styling',
			array(
				'label' => esc_html__( 'Shape', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'styler_circle',
			array(
				'label'     => esc_html__( 'Circle', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-shape-element .circle-shape',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'shape' => 'circle',
				),
			)
		);

		$this->add_control(
			'styler_square',
			array(
				'label'     => esc_html__( 'Square', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-shape-element .square-shape',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'shape' => 'square',
				),
			)
		);

		$this->add_control(
			'styler_custom_element',
			array(
				'label'     => esc_html__( 'Custom element', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-shape-element .custom-shape',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'shape' => 'custom',
				),
			)
		);

		$this->add_control(
			'styler_img',
			array(
				'label'     => esc_html__( 'Image', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-shape-element .img-shape',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'shape' => 'img',
				),
			)
		);
		$this->add_control(
			'styler_svg',
			array(
				'label'     => esc_html__( 'SVG', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.kata-plus-shape-element .kata-svg-icon',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'shape' => 'svg',
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

<?php
/**
 * Brands module config.
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

class Kata_Plus_Brands extends Widget_Base {
	public function get_name() {
		return 'kata-plus-brands';
	}

	public function get_title() {
		return esc_html__( 'Brands', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-accordion';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	public function get_script_depends() {
		return array( 'kata-plus-owl', 'kata-plus-owlcarousel', 'kata-plus-owl' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-brands', 'kata-plus-owlcarousel', 'kata-plus-owl' );
	}

	protected function register_controls() {
		// Content options Start
		$this->start_controls_section(
			'section_text_content',
			array(
				'label' => esc_html__( 'Brand Content Settings', 'kata-plus' ),
			)
		);
		$this->add_control(
			'yb_img',
			array(
				'label' => __( 'Select Your Images', 'kata-plus' ),
				'type'  => Controls_Manager::GALLERY,
			)
		);
		$this->add_responsive_control(
			'yb_num',
			array(
				'label'     => __( 'Item per row', 'kata-plus' ),
				'type'      => Controls_Manager::SELECT2,
				'options'   => array(
					'one'   => __( 'One', 'kata-plus' ),
					'two'   => __( 'Two', 'kata-plus' ),
					'three' => __( 'Three', 'kata-plus' ),
					'four'  => __( 'Four', 'kata-plus' ),
					'five'  => __( 'Five', 'kata-plus' ),
					'six'   => __( 'Six', 'kata-plus' ),
				),
				'default'   => 'four',
				'condition' => array(
					'tesp_carousel!' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'tesp_carousel',
			array(
				'label'        => __( 'Carousel', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'No', 'kata-plus' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$this->add_responsive_control(
			'inc_owl_item',
			array(
				'label'       => __( 'Items Per View', 'kata-plus' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 5,
				'step'        => 1,
				'default'     => 3,
				'description' => __( 'Varies between 1/5', 'kata-plus' ),
				'condition'   => array(
					'tesp_carousel' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_spd',
			array(
				'label'       => __( 'Slide Speed', 'kata-plus' ),
				'description' => __( 'Varies between 500/6000', 'kata-plus' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 500,
						'max'  => 6000,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 5000,
				),
				'condition'   => array(
					'tesp_carousel' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_smspd',
			array(
				'label'       => __( 'Smart Speed', 'kata-plus' ),
				'description' => __( 'Varies between 500/6000', 'kata-plus' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 500,
						'max'  => 6000,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 1000,
				),
				'condition'   => array(
					'tesp_carousel' => array(
						'yes',
					),
				),
			)
		);
		$this->add_responsive_control(
			'inc_owl_stgpad',
			array(
				'label'       => __( 'Stage Padding', 'kata-plus' ),
				'description' => __( 'Varies between 0/400', 'kata-plus' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 400,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 0,
				),
				'condition'   => array(
					'tesp_carousel' => array(
						'yes',
					),
				),
			)
		);
		$this->add_responsive_control(
			'inc_owl_margin',
			array(
				'label'       => __( 'Margin', 'kata-plus' ),
				'description' => __( 'Varies between 0/400', 'kata-plus' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 400,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 20,
				),
				'condition'   => array(
					'tesp_carousel' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_arrow',
			array(
				'label'        => __( 'Prev/Next Arrows', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'kata-plus' ),
				'label_off'    => __( 'Hide', 'kata-plus' ),
				'return_value' => 'true',
				'default'      => 'no',
				'condition'    => array(
					'tesp_carousel' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_prev',
			array(
				'label'     => __( 'Left Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'font-awesome/angle-left',
				'condition' => array(
					'tesp_carousel' => array(
						'yes',
					),
					'inc_owl_arrow' => array(
						'true',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_nxt',
			array(
				'label'     => __( 'Right Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'font-awesome/angle-right',
				'condition' => array(
					'tesp_carousel' => array(
						'yes',
					),
					'inc_owl_arrow' => array(
						'true',
					),
				),
			)
		);
		$this->add_responsive_control(
			'inc_owl_pag',
			array(
				'label'        => __( 'Pagination', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'kata-plus' ),
				'label_off'    => __( 'Hide', 'kata-plus' ),
				'return_value' => 'true',
				'default'      => 'no',
				'condition'    => array(
					'tesp_carousel' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_pag_num',
			array(
				'label'        => __( 'Change to Number', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'No', 'kata-plus' ),
				'return_value' => 'true',
				'default'      => 'no',
				'condition'    => array(
					'tesp_carousel' => array(
						'yes',
					),
					'inc_owl_pag'   => array(
						'true',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_loop',
			array(
				'label'        => __( 'Slider loop', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'No', 'kata-plus' ),
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => array(
					'tesp_carousel' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_autoplay',
			array(
				'label'        => __( 'Autoplay', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'No', 'kata-plus' ),
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => array(
					'tesp_carousel' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_center',
			array(
				'label'        => __( 'Center Item', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'No', 'kata-plus' ),
				'return_value' => 'false',
				'default'      => 'no',
				'condition'    => array(
					'tesp_carousel' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_rtl',
			array(
				'label'        => __( 'RTL', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'No', 'kata-plus' ),
				'return_value' => 'true',
				'default'      => 'no',
				'condition'    => array(
					'tesp_carousel' => array(
						'yes',
					),
				),
			)
		);
		$this->add_control(
			'inc_owl_vert',
			array(
				'label'        => __( 'Vertical Slider', 'kata-plus' ),
				'description'  => __( 'This option works only when "Items Per View" is set to 1.', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'No', 'kata-plus' ),
				'return_value' => 'true',
				'default'      => 'false',
				'condition'    => array(
					'tesp_carousel' => array(
						'yes',
					),
				),
			)
		);
		$this->end_controls_section();

		// Content options Start
		$this->start_controls_section(
			'section_styling',
			array(
				'label' => esc_html__( 'Styler', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-brands',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_item',
			array(
				'label'    => esc_html__( 'Brand item', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-brands .kata-brands-items',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_item_img',
			array(
				'label'    => esc_html__( 'Brand image', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-brands .kata-brands-items img',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_arrow',
			array(
				'label'    => esc_html__( 'Left & Right Arrows', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-brands .owl-nav i',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
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
			'styler_left_arrow',
			array(
				'label'    => esc_html__( 'Left Arrow', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-brands .owl-nav .owl-prev i',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_right_arrow',
			array(
				'label'    => esc_html__( 'Right Arrow', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-brands .owl-nav .owl-next i',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_bullets',
			array(
				'label'    => esc_html__( 'Bullets', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-owl .owl-dots .owl-dot',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_bullets_active',
			array(
				'label'    => esc_html__( 'Active Bullets', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-owl .owl-dots .owl-dot.active',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
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

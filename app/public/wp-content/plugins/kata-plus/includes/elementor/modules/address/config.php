<?php
/**
 * Address module config.
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

class Kata_Plus_Address extends Widget_Base {
	public function get_name() {
		return 'kata-plus-address';
	}

	public function get_title() {
		return esc_html__( 'Address', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-address';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_text_content',
			array(
				'label' => esc_html__( 'Address Settings', 'kata-plus' ),
			)
		);
		$this->add_control(
			'label',
			array(
				'label'       => __( 'Label', 'kata-plus' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Write a label', 'kata-plus' ),
				'default'     => __( 'Address', 'kata-plus' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'address',
			array(
				'label'       => __( 'Address', 'kata-plus' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Enter your Address', 'kata-plus' ),
				'default'     => __( 'California City Blvd California City CA 78912', 'kata-plus' ),
				'dynamic'     => array(
					'active' => true,
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
					'icon'   => __( 'Kata Icons', 'kata-plus' ),
					'imagei' => __( 'Image', 'kata-plus' ),
					'svg'    => __( 'Svg', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'address_icon',
			array(
				'label'     => esc_html__( 'Icon', 'kata-plus' ),
				'type'      => 'kata_plus_icons',
				'default'   => 'themify/location-pin',
				'condition' => array(
					'symbol' => array(
						'icon',
					),
				),
			)
		);
		$this->add_control(
			'address_image',
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
				'name'      => 'address_image',
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
		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			array(
				'label' => esc_html__( 'Styler', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_address_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-address-wrapper',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_address_label',
			array(
				'label'    => esc_html__( 'Label', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-address-wrapper .kata-plus-address-label',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_address',
			array(
				'label'    => esc_html__( 'Address', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-address-wrapper .kata-plus-address',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_address_icon_wrap',
			array(
				'label'    => esc_html__( 'Icon Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-address-wrapper .kata-plus-address-icon-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_address_icon',
			array(
				'label'    => esc_html__( 'Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-address-wrapper .kata-plus-address-icon-wrap i',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'icon',
						'svg',
					),
				),
			)
		);
		$this->add_control(
			'styler_address_icon_image',
			array(
				'label'    => esc_html__( 'Image', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-address-wrapper .kata-plus-address-icon-wrap img',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'condition' => array(
					'symbol' => array(
						'imagei',
					),
				),
			)
		);
		$this->end_controls_section();

		do_action( 'kata_plus_common_controls', $this );
	}

	protected function render() {
		require __DIR__ . '/view.php';
	}
}

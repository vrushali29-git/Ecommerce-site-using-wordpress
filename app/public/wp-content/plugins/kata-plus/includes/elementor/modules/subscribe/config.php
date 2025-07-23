<?php
/**
 * Subscribe module config.
 *
 * @author  ClimaxThemes
 * @package Kata Plus Pro
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Kata_Plus_Subscribe extends Widget_Base {
	public function get_name() {
		return 'kata-plus-subscribe';
	}

	public function get_title() {
		return esc_html__( 'Subscription Form', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-mail';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-subscribe' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'general_section',
			array(
				'label' => esc_html__( 'General', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'source',
			array(
				'label'       => esc_html__( 'Source URL', 'kata-plus' ),
				'description' => esc_html__( 'Mailchimp / Google feedburner URL', 'kata-plus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'feedburner',
				'options'     => array(
					'feedburner' => __( 'Feedburner', 'kata-plus' ),
					'mailchimp'  => __( 'Mailchimp', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'feedburner_uri',
			array(
				'label'         => esc_html__( 'Feedburner URI', 'kata-plus' ),
				'placeholder'   => 'climaxthemes/rbPw',
				'type'          => Controls_Manager::TEXT,
				'show_external' => false,
				'condition'     => array(
					'source' => 'feedburner',
				),
			)
		);
		$this->add_control(
			'action',
			array(
				'label'         => esc_html__( 'Mailchimp Signup form URL', 'kata-plus' ),
				'type'          => Controls_Manager::URL,
				'show_external' => false,
				'condition'     => array(
					'source' => 'mailchimp',
				),
			)
		);
		$this->add_control(
			'placeholder',
			array(
				'label'   => esc_html__( 'Placeholder', 'kata-plus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Email address', 'kata-plus' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'button',
			array(
				'label'   => esc_html__( 'Button', 'kata-plus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Subscribe', 'kata-plus' ),
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
		$this->add_control(
			'icon_pos',
			array(
				'label'   => __( 'Icon Position', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array(
					'right' => __( 'Right', 'kata-plus' ),
					'left'  => __( 'Left', 'kata-plus' ),
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
			'styler_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-subscribe',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_input',
			array(
				'label'    => esc_html__( 'Email Input', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-subscribe input[type="email"]',
				'isSVG'    => true,
				'isInput'  => true,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_input_placeholder',
			array(
				'label'    => esc_html__( 'Email Placeholder', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-subscribe input[type="email"]::placeholder',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_button',
			array(
				'label'    => esc_html__( 'Button', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-subscribe .kt-submit-sub',
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
			'styler_button_icon',
			array(
				'label'    => esc_html__( 'Button Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-subscribe .kata-icon',
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

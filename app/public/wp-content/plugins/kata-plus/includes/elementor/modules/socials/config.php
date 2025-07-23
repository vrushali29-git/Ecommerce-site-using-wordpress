<?php
/**
 * Socials module config.
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
use Elementor\Repeater;
class Kata_Plus_Socials extends Widget_Base {
	public function get_name() {
		return 'kata-plus-socials';
	}

	public function get_title() {
		return esc_html__( 'Socials', 'kata-plus-pro' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-social-icons';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-socials' );
	}

	protected function register_controls() {
		// Content Tab
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'General', 'kata-plus-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'social_icon',
			array(
				'label'   => __( 'Select Icon', 'kata-plus-pro' ),
				'type'    => 'kata_plus_icons',
				'default' => 'font-awesome/facebook',
			)
		);
		$repeater->add_control(
			'icon_name',
			array(
				'label'   => esc_html__( 'Name', 'kata-plus-pro' ), // heading
				'type'    => Controls_Manager::TEXT, // type
				'default' => __( 'Facebook', 'kata-plus-pro' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$repeater->add_control(
			'icon_link',
			array(
				'label'       => __( 'Link', 'kata-plus-pro' ),
				'type'        => Controls_Manager::URL,
				'default'     => array(
					'is_external' => 'true',
				),
				'placeholder' => __( 'https://your-link.com', 'kata-plus-pro' ),
			)
		);
		$repeater->add_control(
			'r_icon_style',
			array(
				'label'    => esc_html__( 'Icon', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '{{CURRENT_ITEM}} .social-wrapper .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$repeater->add_control(
			'r_name_style',
			array(
				'label'    => esc_html__( 'Name', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.social-wrapper .social-name',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);

		// Select Type Section
		$this->add_control(
			'icons', // param_name
			array(
				'label'       => esc_html__( 'Icons', 'kata-plus-pro' ), // heading
				'type'        => Controls_Manager::REPEATER, // type
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'social_icon' => 'font-awesome/instagram',
						'icon_name'   => 'Instagram',
						'icon_link'   => array(
							'url' => 'https://www.instagram.com/',
						),
					),
					array(
						'social_icon' => 'font-awesome/linkedin',
						'icon_name'   => 'Linkedin',
						'icon_link'   => array(
							'url' => 'https://www.linkedin.com/',
						),
					),
					array(
						'social_icon' => 'font-awesome/facebook',
						'icon_name'   => 'Facebook',
						'icon_link'   => array(
							'url' => 'https://www.facebook.com/',
						),
					),
				),
				'title_field' => '{{{ icon_name }}}',
			)
		);

		$this->add_control(
			'display_as',
			array(
				'label'        => __( 'Side by side icons', 'kata-plus-pro' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus-pro' ),
				'label_off'    => __( 'No', 'kata-plus-pro' ),
				'return_value' => 'side',
				'default'      => 'side',
			)
		);

		$this->add_control(
			'name_before_icon',
			array(
				'label'        => esc_html__( 'Display name before icon', 'kata-plus-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'kata-plus-pro' ),
				'label_off'    => esc_html__( 'No', 'kata-plus-pro' ),
				'return_value' => 'on',
				'default'      => 'off',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_styling',
			array(
				'label' => esc_html__( 'Styler', 'kata-plus-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.kata-social-icon-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_name',
			array(
				'label'    => esc_html__( 'Name', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => 'span',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-social-icon',
			)
		);
		$this->add_control(
			'styler_wrapper_link',
			array(
				'label'           => esc_html__( 'Link', 'kata-plus-pro' ),
				'type'            => 'styler',
				'selector'        => 'a',
				'isSVG'           => true,
				'isInput'         => false,
				'hover-selector'  => '.kata-social-icon',
				'wrapper'         => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'icon_style_error',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'In order to change the color of icons, Open Styler and navigate to the SVG tab (all Kata icons is SVG)', 'kata-plus-pro' ),
				'content_classes' => 'kata-plus-elementor-error',
			)
		);
		$this->add_control(
			'styler_icon',
			array(
				'label'    => esc_html__( 'Icons', 'kata-plus-pro' ),
				'type'     => 'styler',
				'selector' => '.kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-social-icon',
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

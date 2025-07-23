<?php
/**
 * Author Box module config.
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

class Kata_Plus_Author_Box extends Widget_Base {
	public function get_name() {
		return 'kata-plus-author-box';
	}

	public function get_title() {
		return esc_html__( 'Author Box', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-call-to-action';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_blog_and_post' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-author-page' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'General', 'kata-plus' ),
			)
		);
		$this->add_control(
			'show_avatar',
			array(
				'label'        => __( 'Show Avatar', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'kata-plus' ),
				'label_off'    => __( 'Hide', 'kata-plus' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->add_control(
			'avatar_size',
			array(
				'label'      => __( 'Avatar Size', 'kata-plus' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 70,
				),
				'condition'  => array(
					'show_avatar' => array( 'yes' ),
				),
			)
		);
		$this->add_control(
			'show_description',
			array(
				'label'        => __( 'Show Description', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'kata-plus' ),
				'label_off'    => __( 'Hide', 'kata-plus' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->add_control(
			'author_link_content',
			array(
				'label'         => __( 'Link Content', 'kata-plus' ),
				'type'          => Controls_Manager::TEXT,
				'show_external' => true,
			)
		);
		$this->add_control(
			'author_link',
			array(
				'label'         => __( 'Link', 'kata-plus' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://yourwebsite.com', 'kata-plus' ),
				'show_external' => true,
				'default'       => array(
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'styles_section_wrapper',
			array(
				'label' => esc_html__( 'Wrapper', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-author-box',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'styles_section',
			array(
				'label' => esc_html__( 'Styles', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_avatar_wrapper',
			array(
				'label'    => esc_html__( 'Avatar Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-author-thumbnail',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_avatar',
			array(
				'label'    => esc_html__( 'Avatar', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-author-thumbnail img',
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
				'selector' => '.kata-plus-author-content',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_name',
			array(
				'label'    => esc_html__( 'Name', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-author-name',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_description',
			array(
				'label'    => esc_html__( 'Bio', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-author-box-description',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_link',
			array(
				'label'     => esc_html__( 'link', 'kata-plus' ),
				'type'      => 'styler',
				'selector'  => '.author-link',
				'isSVG'     => true,
				'isInput'   => false,
				'wrapper'   => '{{WRAPPER}}',
				'condition' => array(
					'author_link_content!' => '',
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

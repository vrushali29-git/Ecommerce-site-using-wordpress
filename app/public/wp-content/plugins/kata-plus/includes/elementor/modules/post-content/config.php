<?php
/**
 * Post Content module config.
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

class Kata_Plus_Post_Content extends Widget_Base {
	public function get_name() {
		return 'kata-plus-post-content';
	}

	public function get_title() {
		return esc_html__( 'Post Content', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-post-content';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_blog_and_post' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-post-content' );
	}

	protected function register_controls() {
		// Style section
		$this->start_controls_section(
			'style_section',
			array(
				'label' => esc_html__( 'Styler', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-post-content',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_h1',
			array(
				'label'    => esc_html__( 'h1 tag', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-widget-container h1',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_h2',
			array(
				'label'    => esc_html__( 'h2 tag', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-widget-container h2',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_h3',
			array(
				'label'    => esc_html__( 'h3 tag', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-widget-container h3',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_h4',
			array(
				'label'    => esc_html__( 'h4 tag', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-widget-container h4',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_h5',
			array(
				'label'    => esc_html__( 'h5 tag', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-widget-container h5',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_h6',
			array(
				'label'    => esc_html__( 'h6 tag', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-widget-container h6',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_p',
			array(
				'label'    => esc_html__( 'p tag', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-widget-container p',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_ul',
			array(
				'label'    => esc_html__( 'ul tag', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-widget-container ul',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_ol',
			array(
				'label'    => esc_html__( 'ol tag', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-widget-container ol',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_li',
			array(
				'label'    => esc_html__( 'li tag', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-widget-container li',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_blockquote',
			array(
				'label'    => esc_html__( 'blockquote tag', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-widget-container blockquote',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_blockquote_cite',
			array(
				'label'    => esc_html__( 'blockquote cite tag', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-widget-container blockquote cite',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_cite',
			array(
				'label'    => esc_html__( 'cite tag', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-widget-container cite',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_a',
			array(
				'label'    => esc_html__( 'a tag', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-widget-container a',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_image',
			array(
				'label'    => esc_html__( 'image tag', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.elementor-widget-container img',
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

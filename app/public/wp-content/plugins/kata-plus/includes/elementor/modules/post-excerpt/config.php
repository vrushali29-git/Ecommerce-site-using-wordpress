<?php
/**
 * Post Excerpt module config.
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

class Kata_Plus_Post_Excerpt extends Widget_Base {
	public function get_name() {
		return 'kata-plus-post-excerpt';
	}

	public function get_title() {
		return esc_html__( 'Post Excerpt', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-post-list';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_blog_and_post' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'tag',
			array(
				'label'   => __( 'Excerpt Tag', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h4',
				'options' => array(
					'h1'   => __( 'H1', 'kata-plus' ),
					'h2'   => __( 'H2', 'kata-plus' ),
					'h3'   => __( 'H3', 'kata-plus' ),
					'h4'   => __( 'H4', 'kata-plus' ),
					'h5'   => __( 'H5', 'kata-plus' ),
					'h6'   => __( 'H6', 'kata-plus' ),
					'p'    => __( 'P', 'kata-plus' ),
					'span' => __( 'Span', 'kata-plus' ),
				),
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
			'styler_post_excerpt',
			array(
				'label'    => esc_html__( 'Post Excerpt', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-single-post-excerpt',
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

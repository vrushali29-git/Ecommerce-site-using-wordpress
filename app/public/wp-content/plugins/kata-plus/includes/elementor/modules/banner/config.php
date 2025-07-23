<?php

/**
 * Banner module config.
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
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;

class Kata_Plus_Banner extends Widget_Base {

	public function get_name() {
		return 'kata-plus-banner';
	}

	public function get_title() {
		return esc_html__( 'Banner', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-image-rollover';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	public function get_script_depends() {
		return array( 'jquery-visible' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-banner' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'banner',
			array(
				'label' => esc_html__( 'Banner', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'banner_tag',
			array(
				'label'   => __( 'Banner Tag', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'figure',
				'options' => array(
					'figure'  => __( 'FIGURE', 'kata-plus' ),
					'div'     => __( 'DIV', 'kata-plus' ),
					'article' => __( 'ARTICLE', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'banner_link',
			array(
				'label'         => __( 'Banner Link', 'kata-plus' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'kata-plus' ),
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
			'image',
			array(
				'label' => esc_html__( 'Image', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'image_tag',
			array(
				'label'   => __( 'Image Wrapper Tag', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'div',
				'options' => array(
					'figure' => __( 'FIGURE', 'kata-plus' ),
					'div'    => __( 'DIV', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'img',
			array(
				'label'     => __( 'Choose Image', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'img',
				'default'   => 'full',
				'separator' => 'none',
			)
		);
		$this->add_control(
			'retina_image',
			array(
				'label'     => __( 'Choose 2x Retina Image (Optional)', 'kata-plus' ),
				'type'      => Controls_Manager::MEDIA,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'description',
			array(
				'label' => esc_html__( 'Description', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'description_tag',
			array(
				'label'   => __( 'Description Tag', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'div',
				'options' => array(
					'figcaption' => __( 'Figcaption', 'kata-plus' ),
					'div'        => __( 'Div', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'banner_title',
			array(
				'label'       => __( 'Title', 'kata-plus' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Type your title here', 'kata-plus' ),
				'default'     => __( 'Title', 'kata-plus' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'banner_title_tag',
			array(
				'label'   => __( 'Title Tag', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h4',
				'options' => array(
					'h1'   => __( 'H1', 'kata-plus' ),
					'h2'   => __( 'H2', 'kata-plus' ),
					'h3'   => __( 'H3', 'kata-plus' ),
					'h4'   => __( 'H4', 'kata-plus' ),
					'h5'   => __( 'H5', 'kata-plus' ),
					'h6'   => __( 'H6', 'kata-plus' ),
					'h6'   => __( 'H6', 'kata-plus' ),
					'p'    => __( 'P', 'kata-plus' ),
					'span' => __( 'Span', 'kata-plus' ),
					'cite' => __( 'Cite', 'kata-plus' ),
					'div'  => __( 'Div', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'banner_subtitle',
			array(
				'label'       => __( 'Subtitle', 'kata-plus' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Type your subtitle here', 'kata-plus' ),
				'default'     => __( 'Subtitle', 'kata-plus' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'banner_subtitle_tag',
			array(
				'label'   => __( 'Subtitle Tag', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'p',
				'options' => array(
					'h1'   => __( 'H1', 'kata-plus' ),
					'h2'   => __( 'H2', 'kata-plus' ),
					'h3'   => __( 'H3', 'kata-plus' ),
					'h4'   => __( 'H4', 'kata-plus' ),
					'h5'   => __( 'H5', 'kata-plus' ),
					'h6'   => __( 'H6', 'kata-plus' ),
					'h6'   => __( 'H6', 'kata-plus' ),
					'p'    => __( 'P', 'kata-plus' ),
					'span' => __( 'Span', 'kata-plus' ),
					'cite' => __( 'Cite', 'kata-plus' ),
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'button',
			array(
				'label' => esc_html__( 'Button', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'banner_button_txt',
			array(
				'label'       => __( 'Button Text ', 'kata-plus' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Button name', 'kata-plus' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'button_link',
			array(
				'label'         => __( 'Button Link', 'kata-plus' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'kata-plus' ),
				'show_external' => true,
				'default'       => array(
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				),
			)
		);
		$this->add_control(
			'banner_button_icon',
			array(
				'label' => esc_html__( 'Button Icon', 'kata-plus' ),
				'type'  => 'kata_plus_icons',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'shape',
			array(
				'label' => esc_html__( 'Shape', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'shape_sk',
			array(
				'label'    => esc_html__( 'Shape style', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '{{CURRENT_ITEM}}',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
				'name'     => 'shape_sk',
			)
		);
		$this->add_control(
			'banner_shapes',
			array(
				'label'  => esc_html__( 'Shapes', 'kata-plus' ),
				'type'   => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
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
			'styler_banner_wrapper',
			array(
				'label'    => esc_html__( 'Banner Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-banner-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_image_box',
			array(
				'label'    => esc_html__( 'Image Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-banner-img',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_image',
			array(
				'label'    => esc_html__( 'Image', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-banner-img img',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}} .kata-banner-wrap',
			)
		);
		$this->add_control(
			'styler_description_wrap',
			array(
				'label'    => esc_html__( 'Description Box', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-banner-description',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_title',
			array(
				'label'    => esc_html__( 'Title', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-banner-title',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_subtitle',
			array(
				'label'    => esc_html__( 'Subtitle', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-banner-subtitle',
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
				'selector' => '.kata-banner-button',
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
				'selector' => '.kata-banner-button i',
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

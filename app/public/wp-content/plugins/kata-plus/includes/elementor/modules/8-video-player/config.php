<?php
/**
 * Video Player module config.
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

class Kata_Plus_Video_Player extends Widget_Base {

	public function get_name() {
		return 'kata-plus-video-player';
	}

	public function get_title() {
		return esc_html__( 'Video Player', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-youtube';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
	}

	public function get_script_depends() {
		return array( 'kata-plus-lightgallery', 'kata-plus-video-player', 'kata-jquery-enllax' );
	}

	public function get_style_depends() {
		return array( 'kata-plus-lightgallery', 'kata-plus-video-player' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_text_content',
			array(
				'label' => esc_html__( 'Text Settings', 'kata-plus' ),
			)
		);

		$this->add_control(
			'video_url',
			array(
				'label'       => __( 'Video URL', 'kata-plus' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'https://www.youtube.com/watch?v=Qap2xYcvF2g', 'kata-plus' ),
				'placeholder' => __( 'Paste your youtube video URL here ', 'kata-plus' ),
			)
		);

		$this->add_control(
			'button',
			array(
				'label'        => __( 'Background Image', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'No', 'kata-plus' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'image_placeholder',
			array(
				'label'     => __( 'Image Placeholder', 'kata-plus' ), // heading
				'type'      => Controls_Manager::MEDIA, // type
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'button' => 'yes',
				),
			)
		);

		$this->add_control(
			'lightbox',
			array(
				'label'        => __( 'Open in lightbox', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'kata-plus' ),
				'label_off'    => __( 'no', 'kata-plus' ),
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'title_and_icon',
			array(
				'label'     => __( 'Title And Icon', 'kata-plus' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'none',
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'kata-plus' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Type your title here', 'kata-plus' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'subtitle',
			array(
				'label'       => __( 'Subtitle', 'kata-plus' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Type your subtitle here', 'kata-plus' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'badge',
			array(
				'label'       => __( 'Video badge', 'kata-plus' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'New services', 'kata-plus' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'   => __( 'Choose Icon', 'kata-plus' ),
				'type'    => 'kata_plus_icons',
				'default' => 'font-awesome/play',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'parent_video',
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
				'selector' => '.kata-plus-video-player',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			array(
				'label' => esc_html__( 'Player', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_playerbtnwrapper',
			array(
				'label'    => esc_html__( 'Player Button', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-vp-conent',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_playerimg',
			array(
				'label'    => esc_html__( 'Image', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-video-player img',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_icon_wrapper',
			array(
				'label'    => esc_html__( 'Icon Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-video-player .iconwrap',
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
			'styler_icon',
			array(
				'label'    => esc_html__( 'Icon', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-video-player .iconwrap .kata-icon',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'title_subtitle_wrapper',
			array(
				'label'    => esc_html__( 'Title & Subtitle Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-video-btn-content-wrap',
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
				'selector' => '.kata-plus-video-player .kata-video-btn-content-wrap div',
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
				'selector' => '.kata-video-btn-content-wrap span',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_video_badge',
			array(
				'label'    => esc_html__( 'Video badge', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-plus-video-player .video-badge',
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

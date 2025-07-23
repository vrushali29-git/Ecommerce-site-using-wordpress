<?php
/**
 * Logo module config.
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
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

class Kata_Plus_Logo extends Widget_Base {
	public function get_name() {
		return 'kata-plus-logo';
	}

	public function get_title() {
		return esc_html__( 'Logo', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-logo';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_header' );
	}

	protected function register_controls() {
		// Content Tab
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'General', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'symbol_default_logo',
			array(
				'label'   => __( 'Image/SVG', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'imagei',
				'options' => array(
					'imagei' => __( 'Image', 'kata-plus' ),
					'svg'    => __( 'Svg', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'default-logo',
			array(
				'label'   => __( 'Default Logo', 'kata-plus' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => array(
					'url' => Kata_Plus::$assets . 'images/logo.png',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'default-logo', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'large',
				'separator' => 'none',
			)
		);
		$this->add_control(
			'symbol_transparent_logo',
			array(
				'label'   => __( 'Image/SVG', 'kata-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'imagei',
				'options' => array(
					'imagei' => __( 'Image', 'kata-plus' ),
					'svg'    => __( 'Svg', 'kata-plus' ),
				),
			)
		);
		$this->add_control(
			'transparent-logo',
			array(
				'label'   => __( 'Logo for Transparent Header', 'kata-plus' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'transparent-logo', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'large',
				'separator' => 'none',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'kata-plus' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'kata-plus' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'kata-plus' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'kata-plus' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => __( 'Advanced Styling', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_image_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-logo',
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
				'selector' => '.kata-logo img',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		// Common controls
		do_action( 'kata_plus_common_controls', $this );
	}

	private function has_caption( $settings ) {
		return ( ! empty( $settings['caption_source'] ) && 'none' !== $settings['caption_source'] );
	}

	private function get_caption( $settings ) {
		$caption = '';
		if ( ! empty( $settings['caption_source'] ) ) {
			switch ( $settings['caption_source'] ) {
				case 'attachment':
					$caption = wp_get_attachment_caption( $settings['image']['id'] );
					break;
				case 'custom':
					$caption = ! empty( $settings['caption'] ) ? $settings['caption'] : '';
			}
		}
		return $caption;
	}

	protected function render() {
		require __DIR__ . '/view.php';
	}

	private function get_link_url( $settings ) {
		if ( 'none' === $settings['link_to'] ) {
			return false;
		}

		if ( 'custom' === $settings['link_to'] ) {
			if ( empty( $settings['link']['url'] ) ) {
				return false;
			}
			return $settings['link'];
		}

		return array(
			'url' => $settings['image']['url'],
		);
	}
}

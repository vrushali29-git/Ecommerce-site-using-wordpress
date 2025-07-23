<?php
/**
 * Spacer module config.
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

class Kata_Plus_Spacer extends Widget_Base {
	public function get_name() {
		return 'kata-plus-gap';
	}

	public function get_title() {
		return esc_html__( 'Spacer', 'kata-plus' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-v-align-stretch';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_most_usefull' );
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

		// Select Type Section
		$this->add_responsive_control(
			'inner_scroll_height',
			array(
				'label'     => __( 'Space', 'kata-plus' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 600,
					),
					'em' => array(
						'min' => 0.1,
						'max' => 20,
					),
				),
				'default'   => array(
					'size' => 100,
				),
				'selectors' => array(
					'{{WRAPPER}} .kata-spacer-inner' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_widget_wrapper',
			array(
				'label' => esc_html__( 'Wrapper', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_wrap',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-spacer-inner',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		require __DIR__ . '/view.php';
	}

	protected function content_template() {
		?>
		<div class="kata-spacer"><div class="kata-spacer-inner"></div></div>
		<?php
	}
}

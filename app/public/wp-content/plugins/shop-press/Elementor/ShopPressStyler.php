<?php
/**
 * Styler.
 *
 * @package ShopPress
 */

namespace ShopPress\Elementor;

defined( 'ABSPATH' ) || exit;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

abstract class ShopPressStyler extends Widget_Base {
	/**
	 * Is Elementor Editor.
	 *
	 * @return boolean
	 */
	public function is_editor() {
		return Plugin::$instance->editor->is_edit_mode()
			||
			( in_array( get_post_type(), array( 'shoppress_pages', 'shoppress_loop' ) ) && isset( $_GET['preview'] ) );
	}

	/**
	 * Preview in editor
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function editor_preview() {

		if ( ! $this->is_editor() && 'product' !== get_post_type() ) {
			return false;
		}

		return true;
	}

	/**
	 * Preview in checkout editor
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function checkout_editor_preview() {

		if ( ! $this->is_editor() && ! is_checkout() ) {
			return false;
		}

		if ( $this->is_editor() ) {
			if ( empty( WC()->cart ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Register Grouped styler controls
	 *
	 * @since 1.0.0
	 *
	 * @param string $id
	 * @param string $sec_label
	 * @param array  $fields = array( 'icon_box_title' => array( 'label' => '', 'selector' => '', 'wrapper' => '', 'condition' => array() ), );
	 * @param array  $condition
	 *
	 * @return void
	 */
	public function register_group_styler( $sec_id, $sec_label, $fields, $condition = array() ) {

		$this->start_controls_section(
			$sec_id . '_styler_section',
			array(
				'label'     => $sec_label,
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => $condition,
			)
		);

		foreach ( $fields as $id => $item ) {

			$this->add_control(
				'styler_' . $id,
				array(
					'label'     => esc_html__( $item['label'], 'shop-press' ),
					'type'      => 'styler',
					'selector'  => isset( $item['selector'] ) ? $item['selector'] : '{{WRAPPER}}', // example '.styler-test-text',
					'wrapper'   => isset( $item['wrapper'] ) ? $item['wrapper'] : '{{WRAPPER}}', // example '{{WRAPPER}}',
					'condition' => isset( $item['condition'] ) ? $item['condition'] : array(), // array()
					'separator' => isset( $item['separator'] ) ? $item['separator'] : '', // array()
					'isSVG'     => true,
				)
			);
		}

		$this->end_controls_section();
	}

	/**
	 * Register a styler controls
	 *
	 * @since 1.0.0
	 *
	 * @param string $id
	 * @param string $label
	 * @param string $selector
	 * @param string $wrapper
	 * @param array  $condition
	 * @param string $hover
	 *
	 * @return void
	 */
	public function register_styler( $id, $label, $selector, $wrapper, $condition = array(), $hover = '' ) {

		$this->add_control(
			'styler_' . $id,
			array(
				'label'     => esc_html__( $label, 'shop-press' ),
				'type'      => 'styler',
				'selector'  => $selector, // example '.styler-test-text',
				'hover'     => isset( $hover ) ? $hover : $selector, // example '.styler-test-text',
				'wrapper'   => $wrapper,  // example '{{WRAPPER}}',
				'condition' => $condition, // array()
				'isSVG'     => true,
			)
		);
	}
}

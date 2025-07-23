<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Orders extends ShopPressWidgets {

	public function get_name() {
		return 'sp-dashboard-orders';
	}

	public function get_title() {
		return __( 'My Account Orders', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-orders';
	}

	public function get_categories() {
		return array( 'sp_woo_dashboard' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-orders-table',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'table',
			__( 'Table', 'shop-press' ),
			array(
				'table' => array(
					'label'    => esc_html__( 'table', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-orders-table',
					'wrapper'  => '{{WRAPPER}}',
				),
				'thead' => array(
					'label'    => esc_html__( 'thead', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'thead',
					'wrapper'  => '{{WRAPPER}} .woocommerce-orders-table',
				),
				'tr'    => array(
					'label'    => esc_html__( 'tr', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tr',
					'wrapper'  => '{{WRAPPER}} .woocommerce-orders-table',
				),
				'th'    => array(
					'label'    => esc_html__( 'th', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'th',
					'wrapper'  => '{{WRAPPER}} .woocommerce-orders-table',
				),
				'td'    => array(
					'label'    => esc_html__( 'td', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'td',
					'wrapper'  => '{{WRAPPER}} .woocommerce-orders-table',
				),
				'tbody' => array(
					'label'    => esc_html__( 'tbody', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tbody',
					'wrapper'  => '{{WRAPPER}} .woocommerce-orders-table',
				),
			)
		);

		$this->register_group_styler(
			'action_buttons',
			__( 'Action Buttons', 'shop-press' ),
			array(
				'buttons'       => array(
					'label'    => esc_html__( 'Buttons', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.button',
					'wrapper'  => '{{WRAPPER}} .woocommerce-orders-table__cell-order-actions',
				),
				'button_pay'    => array(
					'label'    => esc_html__( 'Pay Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.button.pay',
					'wrapper'  => '{{WRAPPER}} .woocommerce-orders-table__cell-order-actions',
				),
				'button_view'   => array(
					'label'    => esc_html__( 'View Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.button.view',
					'wrapper'  => '{{WRAPPER}} .woocommerce-orders-table__cell-order-actions',
				),
				'button_cancel' => array(
					'label'    => esc_html__( 'Cancel Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.button.cancel',
					'wrapper'  => '{{WRAPPER}} .woocommerce-orders-table__cell-order-actions',
				),
			)
		);
	}

	protected function register_controls() {
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		do_action( 'shoppress/widget/before_render', $this->get_settings_for_display() );

		sp_load_builder_template( 'my-account/orders' );
	}
}

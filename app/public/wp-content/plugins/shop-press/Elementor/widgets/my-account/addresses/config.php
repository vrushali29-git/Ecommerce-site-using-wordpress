<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Addresses extends ShopPressWidgets {

	public function get_name() {
		return 'sp-dashboard-addresses';
	}

	public function get_title() {
		return __( 'My Account Addresses', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-addresses';
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
					'selector' => '.sp-addresses',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'description',
			__( 'Description', 'shop-press' ),
			array(
				'description' => array(
					'label'    => esc_html__( 'Description', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.addresses-widget-description',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'billing_address',
			__( 'Billing Address', 'shop-press' ),
			array(
				'billing_address_title'     => array(
					'label'    => esc_html__( 'Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.u-column1 .title h3',
					'wrapper'  => '{{WRAPPER}}',
				),
				'billing_address_edit'      => array(
					'label'    => esc_html__( 'Edit Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.u-column1 .title a',
					'wrapper'  => '{{WRAPPER}}',
				),
				'billing_address_addresses' => array(
					'label'    => esc_html__( 'Addresses', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.u-column1 address',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'shipping_address',
			__( 'Shipping Address', 'shop-press' ),
			array(
				'shipping_address_title'     => array(
					'label'    => esc_html__( 'Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.u-column2 .title h3',
					'wrapper'  => '{{WRAPPER}}',
				),
				'shipping_address_edit'      => array(
					'label'    => esc_html__( 'Edit Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.u-column2 .title a',
					'wrapper'  => '{{WRAPPER}}',
				),
				'shipping_address_addresses' => array(
					'label'    => esc_html__( 'Addresses', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.u-column2 address',
					'wrapper'  => '{{WRAPPER}}',
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

		sp_load_builder_template( 'my-account/addresses' );
	}
}

<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

class OrderReview extends ShopPressWidgets {

	public function get_name() {
		return 'sp-checkout-order-review';
	}

	public function get_title() {
		return __( 'Order Review', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-order-review';
	}

	public function get_categories() {
		return array( 'sp_woo_checkout' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-order-review',
					'wrapper'  => '.woocommerce {{WRAPPER}}',
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
					'selector' => '.shop_table',
					'wrapper'  => '{{WRAPPER}}',
				),
				'thead' => array(
					'label'    => esc_html__( 'thead', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.shop_table thead',
					'wrapper'  => '{{WRAPPER}}',
				),
				'tr'    => array(
					'label'    => esc_html__( 'tr', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.shop_table tr',
					'wrapper'  => '{{WRAPPER}}',
				),
				'th'    => array(
					'label'    => esc_html__( 'th', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.shop_table th',
					'wrapper'  => '{{WRAPPER}}',
				),
				'td'    => array(
					'label'    => esc_html__( 'td', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.shop_table td',
					'wrapper'  => '{{WRAPPER}}',
				),
				'tbody' => array(
					'label'    => esc_html__( 'tbody', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.shop_table tbody',
					'wrapper'  => '{{WRAPPER}}',
				),
				'tfoot' => array(
					'label'    => esc_html__( 'tfoot', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.shop_table tfoot',
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
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		if ( $this->checkout_editor_preview() ) {
			sp_load_builder_template( 'checkout/checkout-order-review' );
		}
	}
}

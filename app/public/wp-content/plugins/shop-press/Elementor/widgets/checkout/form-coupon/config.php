<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

class FormCoupon extends ShopPressWidgets {

	public function get_name() {
		return 'sp-checkout-coupon-form';
	}

	public function get_title() {
		return __( 'Coupon Form', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-form-coupon';
	}

	public function get_categories() {
		return array( 'sp_woo_checkout' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'toggle',
			__( 'Toggle', 'shop-press' ),
			array(
				'toggle' => array(
					'label'    => esc_html__( 'Toggle', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-info',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'form',
			__( 'Form', 'shop-press' ),
			array(
				'form_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-checkout-form-coupon',
					'wrapper'  => '{{WRAPPER}}',
				),
				'form_title'   => array(
					'label'    => esc_html__( 'Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-checkout-form-coupon p',
					'wrapper'  => '{{WRAPPER}}',
				),
				'form_input'   => array(
					'label'    => esc_html__( 'Input', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'input#coupon_code',
					'wrapper'  => '{{WRAPPER}}',
				),
				'form_button'  => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-checkout-form-coupon button.button',
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

		if ( $this->checkout_editor_preview() ) {
			sp_load_builder_template( 'checkout/checkout-form-coupon' );
		}
	}
}

<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

class PaymentMethod extends ShopPressWidgets {

	public function get_name() {
		return 'sp-payment-method';
	}

	public function get_title() {
		return __( 'Payment', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-payment-method';
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
					'selector' => '#payment',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'payment_methods',
			__( 'Payment Methods', 'shop-press' ),
			array(
				'payment_methods_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.wc_payment_methods',
					'wrapper'  => '{{WRAPPER}}',
				),
				'payment_methods_items'   => array(
					'label'    => esc_html__( 'Payment Method', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.wc_payment_method',
					'wrapper'  => '{{WRAPPER}}',
				),
				'payment_methods_input'   => array(
					'label'    => esc_html__( 'Payment Method Input', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.wc_payment_method input',
					'wrapper'  => '{{WRAPPER}}',
				),
				'payment_methods_label'   => array(
					'label'    => esc_html__( 'Payment Method Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.wc_payment_method label',
					'wrapper'  => '{{WRAPPER}}',
				),
				'payment_methods_box'     => array(
					'label'    => esc_html__( 'Payment Box', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '#payment div.payment_box',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'place_order',
			__( 'Place Order', 'shop-press' ),
			array(
				'place_order_wrapper'    => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.form-row.place-order',
					'wrapper'  => '{{WRAPPER}}',
				),
				'privacy_policy_wrapper' => array(
					'label'    => esc_html__( 'Privacy Policy Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-privacy-policy-text',
					'wrapper'  => '{{WRAPPER}}',
				),
				'privacy_policy_text'    => array(
					'label'    => esc_html__( 'Privacy Policy Text', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-privacy-policy-text p',
					'wrapper'  => '{{WRAPPER}}',
				),
				'privacy_policy_link'    => array(
					'label'    => esc_html__( 'Privacy Policy Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-privacy-policy-text a',
					'wrapper'  => '{{WRAPPER}}',
				),
				'place_order_btn'        => array(
					'label'    => esc_html__( 'Place Order Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.place-order button#place_order',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'General', 'shop-press' ),
			)
		);

		$this->add_control(
			'hide_payment_title',
			array(
				'label' => __( 'Hide Title', 'shop-press' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'method_desc',
			array(
				'label'        => __( 'Payment method description', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'shop-press' ),
				'label_off'    => __( 'Hide', 'shop-press' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'policy_desc',
			array(
				'label'        => __( 'Policy description', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'shop-press' ),
				'label_off'    => __( 'Hide', 'shop-press' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array(
			'hide_title'  => $settings['hide_payment_title'],
			'policy_desc' => $settings['policy_desc'],
			'method_desc' => $settings['method_desc'],
		);

		if ( $this->checkout_editor_preview() ) {
			sp_load_builder_template( 'checkout/checkout-payment-method', $args );
		}
	}
}

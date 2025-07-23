<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

class CartTotals extends ShopPressWidgets {
	public function get_name() {
		return 'sp-cart-totals';
	}

	public function get_title() {
		return __( 'Cart Totals', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-cart-totals';
	}

	public function get_categories() {
		return array( 'sp_woo_cart' );
	}

	public function get_script_depends() {
		return array( 'sp-cart-totals' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.cart_totals',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'title',
			__( 'Title', 'shop-press' ),
			array(
				'title' => array(
					'label'    => esc_html__( 'Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => ' .cart_totals h2',
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
					'selector' => '.cart_totals .shop_table',
					'wrapper'  => '{{WRAPPER}}',
				),
				'tbody' => array(
					'label'    => esc_html__( 'tbody', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.cart_totals .shop_table tbody',
					'wrapper'  => '{{WRAPPER}}',
				),
				'tr'    => array(
					'label'    => esc_html__( 'tr', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.cart_totals .shop_table tr',
					'wrapper'  => '{{WRAPPER}}',
				),
				'th'    => array(
					'label'    => esc_html__( 'th', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.cart_totals .shop_table th',
					'wrapper'  => '{{WRAPPER}}',
				),
				'td'    => array(
					'label'    => esc_html__( 'td', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.cart_totals .shop_table td',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'shipping',
			__( 'Shipping', 'shop-press' ),
			array(
				'shipping_wrapper'                  => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tr.shipping',
					'wrapper'  => '{{WRAPPER}} .cart_totals .shop_table',
				),
				'shipping_title'                    => array(
					'label'    => esc_html__( 'Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'th',
					'wrapper'  => '{{WRAPPER}} .cart_totals .shop_table tr.shipping',
				),
				'shipping_content_wrapper'          => array(
					'label'    => esc_html__( 'Content Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'td',
					'wrapper'  => '{{WRAPPER}} .cart_totals .shop_table tr.shipping',
				),
				'shipping_methods'                  => array(
					'label'    => esc_html__( 'Shipping Methods', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'li',
					'wrapper'  => '{{WRAPPER}} .cart_totals .shop_table tr.shipping td #shipping_method',
				),
				'shipping_methods_label'            => array(
					'label'    => esc_html__( 'Shipping Methods Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'label',
					'wrapper'  => '{{WRAPPER}} .cart_totals .shop_table tr.shipping td #shipping_method li',
				),
				'shipping_methods_radio'            => array(
					'label'    => esc_html__( 'Shipping Methods Radio', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'input',
					'wrapper'  => '{{WRAPPER}} .cart_totals .shop_table tr.shipping td #shipping_method li',
				),
				'shipping_destination'              => array(
					'label'    => esc_html__( 'Shipping Destination', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-shipping-destination',
					'wrapper'  => '{{WRAPPER}} .cart_totals .shop_table tr.shipping td',
				),
				'shipping_calculator_wrapper'       => array(
					'label'    => esc_html__( 'Shipping Calculator Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-shipping-calculator',
					'wrapper'  => '{{WRAPPER}} .cart_totals .shop_table tr.shipping td',
				),
				'shipping_change_address_button'    => array(
					'label'    => esc_html__( 'Change Address Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a.shipping-calculator-button',
					'wrapper'  => '{{WRAPPER}} .cart_totals .shop_table tr.shipping td .woocommerce-shipping-calculator',
				),
				'shipping_calculator_form_wrapper'  => array(
					'label'    => esc_html__( 'Shipping Calculator Form Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.shipping-calculator-form',
					'wrapper'  => '{{WRAPPER}} .cart_totals .shop_table tr.shipping td .woocommerce-shipping-calculator',
				),
				'shipping_calculator_form_row'      => array(
					'label'    => esc_html__( 'Shipping Calculator Form Rows', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.form-row',
					'wrapper'  => '{{WRAPPER}} .cart_totals .shop_table tr.shipping td .woocommerce-shipping-calculator .shipping-calculator-form',
				),
				'shipping_calculator_selects'       => array(
					'label'    => esc_html__( 'Shipping Calculator Selects', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.select2-selection--single',
					'wrapper'  => '{{WRAPPER}} .cart_totals .shop_table tr.shipping td .woocommerce-shipping-calculator .shipping-calculator-form .form-row',
				),
				'shipping_calculator_text_input'    => array(
					'label'    => esc_html__( 'Shipping Calculator Text Inputs', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'input[type=text]',
					'wrapper'  => '{{WRAPPER}} .cart_totals .shop_table tr.shipping td .woocommerce-shipping-calculator .shipping-calculator-form .form-row',
				),
				'shipping_calculator_update_button_wrapper' => array(
					'label'    => esc_html__( 'Shipping Calculator Update Button Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'p:has(button)',
					'wrapper'  => '{{WRAPPER}} .cart_totals .shop_table tr.shipping td .woocommerce-shipping-calculator .shipping-calculator-form',
				),
				'shipping_calculator_update_button' => array(
					'label'    => esc_html__( 'Shipping Calculator Update Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'button',
					'wrapper'  => '{{WRAPPER}} .cart_totals .shop_table tr.shipping td .woocommerce-shipping-calculator .shipping-calculator-form p',
				),
			)
		);

		$this->register_group_styler(
			'button',
			__( 'Button', 'shop-press' ),
			array(
				'button' => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.cart_totals .wc-proceed-to-checkout a.checkout-button.button.alt',
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
			'header_text',
			array(
				'label'   => __( 'Header Text', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Summary', 'shop-press' ),
			)
		);

		$this->add_control(
			'is_open_shipping_box',
			array(
				'label'     => __( 'Shipping Details', 'shop-press' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'shop-press' ),
				'label_off' => __( 'Hide', 'shop-press' ),
				'default'   => 'yes',
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
			'header_text'          => $settings['header_text'],
			'is_open_shipping_box' => $settings['is_open_shipping_box'],
		);

		sp_load_builder_template( 'cart/cart-totals', $args );
	}
}

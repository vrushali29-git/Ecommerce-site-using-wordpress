<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

class CartCoupon extends ShopPressWidgets {

	public function get_name() {
		return 'sp-cart-coupon';
	}

	public function get_title() {
		return __( 'Cart Coupon', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-cart-coupon';
	}

	public function get_categories() {
		return array( 'sp_woo_cart' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-cart-coupon',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'form',
			__( 'Form', 'shop-press' ),
			array(
				'form_wrapper' => array(
					'label'    => esc_html__( 'Form Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-cart-coupon form.cart_coupon',
					'wrapper'  => '{{WRAPPER}}',
				),
				'form_input'   => array(
					'label'    => esc_html__( 'Input', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-cart-coupon #coupon_code',
					'wrapper'  => '{{WRAPPER}}',
				),
				'form_button'  => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-cart-coupon [name="apply_coupon"]',
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
			'button_style',
			array(
				'label'   => __( 'Button Icon or Text', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'text'        => __( 'Text', 'shop-press' ),
					'icon'        => __( 'Icon', 'shop-press' ),
					'button_icon' => __( 'Text Icon', 'shop-press' ),
					'icon_button' => __( 'Icon Text', 'shop-press' ),
				),
				'default' => 'text',
			)
		);

		$this->add_control(
			'btn_icon',
			array(
				'label'   => __( 'Button Icon', 'shop-press' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-arrow-right',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => __( 'Button Text', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Apply', 'shop-press' ),
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
			'button_style' => $settings['button_style'],
			'button_text'  => $settings['button_text'],
			'btn_icon'     => $settings['btn_icon'],
		);

		sp_load_builder_template( 'cart/cart-coupon', $args );
	}
}

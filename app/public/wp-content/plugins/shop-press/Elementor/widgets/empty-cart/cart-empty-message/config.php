<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

class CartEmptyMessage extends ShopPressWidgets {
	public function get_name() {
		return 'sp-cart-empty-message';
	}

	public function get_title() {
		return __( 'Cart Empty Message', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-cart-empty-message';
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
					'selector' => '.sp-cart-empty-message',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'text',
			__( 'Text', 'shop-press' ),
			array(
				'text' => array(
					'label'    => esc_html__( 'Text', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.cart-empty',
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
			'message',
			array(
				'label'   => __( 'Message', 'shop-press' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 5,
				'default' => __( 'Your cart is currently empty.', 'shop-press' ),
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
			'message' => $settings['message'],
		);

		sp_load_builder_template( 'cart/cart-empty-message', $args );
	}
}

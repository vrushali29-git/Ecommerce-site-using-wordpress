<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

class ThankyouOrderReview extends ShopPressWidgets {

	public function get_name() {
		return 'sp-thankyou-order-review';
	}

	public function get_title() {
		return __( 'Order Review', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-ty-order-review';
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
					'selector' => '.sp-thankyou-order-review',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
		);

		$this->register_group_styler(
			'notice',
			__( 'Notice', 'shop-press' ),
			array(
				'table' => array(
					'label'    => esc_html__( 'Message', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-notice',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'order_reviews',
			__( 'Order Reviews', 'shop-press' ),
			array(
				'wrapper'     => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-order-overview',
					'wrapper'  => '{{WRAPPER}}',
				),
				'items'       => array(
					'label'    => esc_html__( 'Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-order-overview li',
					'wrapper'  => '{{WRAPPER}}',
				),
				'items_value' => array(
					'label'    => esc_html__( 'Items', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-order-overview li strong',
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

		if ( is_checkout() ) {
			sp_load_builder_template( 'thankyou/order-review' );
		}

		if ( $this->is_editor() ) {
			require __DIR__ . '/dummy.php';
		}
	}
}

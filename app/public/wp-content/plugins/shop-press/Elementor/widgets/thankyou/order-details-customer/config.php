<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

class ThankyouOrderCustomerDetails extends ShopPressWidgets {

	public function get_name() {
		return 'sp-thankyou-order-customer-details';
	}

	public function get_title() {
		return __( 'Order Customer Details', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-order-customer-details';
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
					'selector' => '.woocommerce-customer-details',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
		);

		$this->register_group_styler(
			'title',
			__( 'Title', 'shop-press' ),
			array(
				'title' => array(
					'label'    => esc_html__( 'Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'h2',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'address',
			__( 'Address', 'shop-press' ),
			array(
				'address' => array(
					'label'    => esc_html__( 'Address', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'address',
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
			sp_load_builder_template( 'thankyou/order-details-customer' );
		}

		if ( $this->is_editor() ) {
			require __DIR__ . '/dummy.php';
		}
	}
}

<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class OrdersTracking extends ShopPressWidgets {

	public function get_name() {
		return 'sp-orders-tracking';
	}

	public function get_title() {
		return __( 'Orders Tracking', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-order-tracking';
	}

	public function get_categories() {
		return array( 'sp_general' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-orders-tracking',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'description',
			__( 'Description', 'shop-press' ),
			array(
				'icon' => array(
					'label'    => esc_html__( 'Description', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.order-tracking-description',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'order_id',
			__( 'Order ID', 'shop-press' ),
			array(
				'order_id_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.form-row-first',
					'wrapper'  => '{{WRAPPER}}',
				),
				'order_id_label'   => array(
					'label'    => esc_html__( 'Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.form-row-first label',
					'wrapper'  => '{{WRAPPER}}',
				),
				'order_id_input'   => array(
					'label'    => esc_html__( 'Input', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.form-row-first .input-text',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'billing_email',
			__( 'Billing email', 'shop-press' ),
			array(
				'billing_email_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.form-row-last',
					'wrapper'  => '{{WRAPPER}}',
				),
				'billing_email_label'   => array(
					'label'    => esc_html__( 'Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.form-row-last label',
					'wrapper'  => '{{WRAPPER}}',
				),
				'billing_email_input'   => array(
					'label'    => esc_html__( 'Input', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.form-row-last .input-text',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'button',
			__( 'Button', 'shop-press' ),
			array(
				'track_button' => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'button.track-button',
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
			'description',
			array(
				'label'   => __( 'Description', 'shop-press' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 5,
				'default' => __( 'To track your order please enter your Order ID in the box below and press the "Track" button. This was given to you on your receipt and in the confirmation email you should have received.', 'shop-press' ),
			)
		);

		$this->add_control(
			'description_hr',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'orderid_title',
			array(
				'label'   => __( 'Order ID Title', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Order ID', 'shop-press' ),
			)
		);

		$this->add_control(
			'order_placeholder',
			array(
				'label'   => __( 'Order ID Placeholder', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Found in your order confirmation email.', 'shop-press' ),
			)
		);

		$this->add_control(
			'order_hr',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'email_title',
			array(
				'label'   => __( 'Email Title', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Billing email', 'shop-press' ),
			)
		);

		$this->add_control(
			'email_placeholder',
			array(
				'label'   => __( 'Email Placeholder', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Email you used during checkout.', 'shop-press' ),
			)
		);

		$this->add_control(
			'email_hr',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => __( 'Button Text', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Track', 'shop-press' ),
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
			'description'       => $settings['description'],
			'orderid_title'     => $settings['orderid_title'],
			'order_placeholder' => $settings['order_placeholder'],
			'email_title'       => $settings['email_title'],
			'email_placeholder' => $settings['email_placeholder'],
			'button_text'       => $settings['button_text'],
		);

		sp_load_builder_template( 'general/orders-tracking', $args );
	}
}

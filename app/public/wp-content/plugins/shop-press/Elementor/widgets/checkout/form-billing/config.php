<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use ShopPress\Elementor\ShopPressWidgets;

class FormBilling extends ShopPressWidgets {
	public function get_name() {
		return 'sp-checkout-form-billing';
	}

	public function get_title() {
		return __( 'Form Billing', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-form-billing';
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
					'selector' => '.sp-checkout-billing-fields',
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
					'selector' => '.sp-checkout-billing-fields > h3',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'form',
			__( 'Form', 'shop-press' ),
			array(
				'form_label'    => array(
					'label'    => esc_html__( 'Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-checkout-billing-fields label',
					'wrapper'  => '{{WRAPPER}}',
				),
				'form_required' => array(
					'label'    => esc_html__( 'Required', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-checkout-billing-fields label > *',
					'wrapper'  => '.woocommerce {{WRAPPER}}',
				),
				'form_input'    => array(
					'label'    => esc_html__( 'Input', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-checkout-billing-fields input, .sp-checkout-billing-fields select, .sp-checkout-billing-fields .select2-selection',
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

		$this->register_checkout_form_billing_fields();
		$this->end_controls_section();
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	public function register_checkout_form_billing_fields() {

		$this->add_control(
			'hide_form_billing_title',
			array(
				'label' => __( 'Hide Title', 'shop-press' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array(
			'hide_title' => $settings['hide_form_billing_title'],
		);

		if ( $this->checkout_editor_preview() ) {
			sp_load_builder_template( 'checkout/checkout-form-billing', $args );
		}
	}
}

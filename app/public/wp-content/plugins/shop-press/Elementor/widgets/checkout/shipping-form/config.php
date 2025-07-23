<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

class FormShipping extends ShopPressWidgets {

	public function get_name() {
		return 'sp-checkout-form-shipping';
	}

	public function get_title() {
		return __( 'Form Shipping', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-shipping-form';
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
					'selector' => '.shipping_address',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'form',
			__( 'Form', 'shop-press' ),
			array(
				'form_label' => array(
					'label'    => esc_html__( 'Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.shipping_address label',
					'wrapper'  => '{{WRAPPER}}',
				),
				'form_input' => array(
					'label'    => esc_html__( 'Input', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.shipping_address input',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'ship_address',
			__( 'Ship to a different address', 'shop-press' ),
			array(
				'ship_checkbox' => array(
					'label'    => esc_html__( 'Checkbox', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'input#ship-to-different-address-checkbox',
					'wrapper'  => '{{WRAPPER}}',
				),
				'ship_title'    => array(
					'label'    => esc_html__( 'Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '#ship-to-different-address span',
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

		$this->register_checkout_form_shipping_fields();
		$this->end_controls_section();
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	public function register_checkout_form_shipping_fields() {

		$this->add_control(
			'hide_form_shipping_title',
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
			'hide_title' => $settings['hide_form_shipping_title'],
		);

		if ( $this->checkout_editor_preview() ) {
			sp_load_builder_template( 'checkout/checkout-shipping-form', $args );
		}
	}
}

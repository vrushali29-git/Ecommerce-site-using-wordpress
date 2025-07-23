<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class EditAccount extends ShopPressWidgets {

	public function get_name() {
		return 'sp-dashboard-edit-account';
	}

	public function get_title() {
		return __( 'My Account Edit Account', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-edit-account';
	}

	public function get_categories() {
		return array( 'sp_woo_dashboard' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-edit-account-details',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'form',
			__( 'Form', 'shop-press' ),
			array(
				'form_label'                   => array(
					'label'    => esc_html__( 'Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'label',
					'wrapper'  => '{{WRAPPER}}',
				),
				'form_text_input'              => array(
					'label'    => esc_html__( 'Text Input', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'input[type="text"]',
					'wrapper'  => '{{WRAPPER}}',
				),
				'form_email_input'             => array(
					'label'    => esc_html__( 'Email Input', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'input[type="email"]',
					'wrapper'  => '{{WRAPPER}}',
				),
				'form_password_input'          => array(
					'label'    => esc_html__( 'Password Input', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'input[type="password"]',
					'wrapper'  => '{{WRAPPER}}',
				),
				'form_password_fieldset'       => array(
					'label'    => esc_html__( 'Password Fieldset', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'fieldset',
					'wrapper'  => '{{WRAPPER}}',
				),
				'form_password_fieldset_title' => array(
					'label'    => esc_html__( 'Password Fieldset Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'legend',
					'wrapper'  => '{{WRAPPER}}',
				),
				'form_btn'                     => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'button.woocommerce-Button',
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

		sp_load_builder_template( 'my-account/edit-account' );
	}
}

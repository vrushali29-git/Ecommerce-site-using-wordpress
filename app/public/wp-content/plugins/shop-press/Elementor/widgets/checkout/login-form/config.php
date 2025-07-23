<?php
namespace ShopPress\Elementor\Widgets;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

defined( 'ABSPATH' ) || exit;

class LoginForm extends ShopPressWidgets {

	public function get_name() {
		return 'sp-checkout-login-form';
	}

	public function get_title() {
		return __( 'Login Form', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-login-form';
	}

	public function get_categories() {
		return array( 'sp_woo_checkout' );
	}

	protected function register_controls() {
		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		do_action( 'shoppress/widget/before_render', $this->get_settings_for_display() );

		if ( $this->checkout_editor_preview() ) {
			sp_load_builder_template( 'checkout/checkout-login-form' );
		}
	}
}

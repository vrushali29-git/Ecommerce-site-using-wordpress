<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class MyAccountLogin extends ShopPressWidgets {

	public function get_name() {
		return 'sp-dashboard-login-register';
	}

	public function get_title() {
		return __( 'My Account Login / Register', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-login';
	}

	public function get_categories() {
		return array( 'sp_woo_dashboard' );
	}

	protected function register_controls() {
		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		do_action( 'shoppress/widget/before_render', $this->get_settings_for_display() );

		sp_load_builder_template( 'my-account/form-login' );
	}
}

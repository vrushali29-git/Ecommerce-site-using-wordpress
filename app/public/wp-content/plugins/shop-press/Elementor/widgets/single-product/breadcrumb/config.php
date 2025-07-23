<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Breadcrumb extends ShopPressWidgets {

	public function get_name() {
		return 'sp-breadcrumb';
	}

	public function get_title() {
		return __( 'Product Breadcrumb', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-breadcrumbs';
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}

	public function setup_styling_options() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper'   => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-breadcrumbs-wrapper',
					'wrapper'  => '{{WRAPPER}}',
				),
				'container' => array(
					'label'    => esc_html__( 'Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-breadcrumbs',
					'wrapper'  => '{{WRAPPER}} .sp-breadcrumbs-wrapper',
				),
			)
		);

		$this->register_group_styler(
			'breadcrumb',
			__( 'Breadcrumb', 'shop-press' ),
			array(
				'breadcrumb'              => array(
					'label'    => esc_html__( 'Breadcrumb', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'nav.woocommerce-breadcrumb',
					'wrapper'  => '{{WRAPPER}} .sp-breadcrumbs',
				),
				'breadcrumb_link'         => array(
					'label'    => esc_html__( 'Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} .sp-breadcrumbs nav.woocommerce-breadcrumb',
				),
				'breadcrumb_product_name' => array(
					'label'    => esc_html__( 'Product Name', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span',
					'wrapper'  => '{{WRAPPER}} .sp-breadcrumbs nav.woocommerce-breadcrumb',
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

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/product-breadcrumb' );
		}
	}
}

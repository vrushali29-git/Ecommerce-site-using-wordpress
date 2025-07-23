<?php

namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use ShopPress\Elementor\ControlsWidgets;

class ProductsLoop extends ShopPressWidgets {
	public function get_name() {
		return 'sp-product-collection';
	}

	public function get_title() {
		return __( 'Product Collection', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-loop';
	}

	public function get_script_depends() {
		return array( 'sp-products-loop', 'sp-recent-products', 'slick' );
	}

	public function get_style_depends() {
		if ( is_rtl() ) {
			return array( 'sp-products-loop', 'slick', 'sp-products-loop-rtl' );
		} else {
			return array( 'sp-products-loop', 'slick' );
		}
	}

	public function get_categories() {
		return array( 'sp_general' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'General', 'shop-press' ),
			)
		);

		do_action( 'shoppress/elementor/widget/products_loop/section_content/start', $this );

		ControlsWidgets::add_query_controls( $this );

		do_action( 'shoppress/elementor/widget/products_loop/section_content/end', $this );

		$this->end_controls_section();

		$this->register_group_styler(
			'product_list',
			__( 'Products List', 'shop-press' ),
			array(
				'product_list' => array(
					'label'    => esc_html__( 'Products List', 'shop-press' ),
					'type'     => 'styler',
					'selector'     => 'ul.products',
					'wrapper' => '{{WRAPPER}}',
				),
			),
		);

		$this->register_group_styler(
			'products',
			__( 'Product Item', 'shop-press' ),
			array(
				'products' => array(
					'label'    => esc_html__( 'Product Item', 'shop-press' ),
					'type'     => 'styler',
					'selector'     => 'li.product',
					'wrapper' => '{{WRAPPER}} ul.products',
				),
			),
		);

		$this->carousel_options();

		$this->carousel_stylers();

		if ( 'sp-related-products' === $this->get_name() ) {

			$this->custom_heading_stylers();
		}

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	public function render() {
		$widget_name = $this->get_name();
		$element_id  = $this->get_id();
		$settings    = $this->get_settings_for_display();
		$widget_name = $settings['product_collection'] ?? $widget_name;

		do_action( 'shoppress/widget/before_render', $settings );

		if ( $this->is_editor() ) {
			echo '<script>
			jQuery(document).ready(function(){
				typeof sp_product_slider_init === "function" && sp_product_slider_init();
				typeof sp_slider_init === "function" && sp_slider_init();
			});
			</script>';
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo sp_render_product_collection( $widget_name, $settings );
	}
}

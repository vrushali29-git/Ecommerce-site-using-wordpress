<?php
namespace ShopPress\Elementor\Widgets\ProductsLoop;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\Widgets\ProductsLoop;

class UpSellProducts extends ProductsLoop {
	public function get_name() {
		return 'sp-up-sell-products';
	}

	public function get_title() {
		return __( 'Product Upsells', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-upsell-products';
	}

	public function get_script_depends() {
		return array( 'sp-products-loop' );
	}

	public function get_style_depends() {
		if ( is_rtl() ) {
			return array( 'sp-products-loop', 'sp-products-loop-rtl' );
		} else {
			return array( 'sp-products-loop' );
		}
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}
}

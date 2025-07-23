<?php
namespace ShopPress\Elementor\Widgets\ProductsLoop;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\Widgets\ProductsLoop;

class CrossSellProducts extends ProductsLoop {
	public function get_name() {
		return 'sp-cross-sell-products';
	}

	public function get_title() {
		return __( 'Cross Sell Products', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-cross-sell-products';
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
		return array( 'sp_woo_cart' );
	}
}

<?php

namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\Widgets\ProductsLoop;
class RelatedProducts extends ProductsLoop {
	public function get_name() {
		return 'sp-related-products';
	}

	public function get_title() {
		return __( 'Related Products', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-related-products';
	}

	public function get_script_depends() {
		return array( 'sp-recent-products' );
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

	public function render() {
		$settings = $this->get_settings_for_display();

		$custom_heading   = $settings['custom_heading'] ?? false;
		$products_heading = $settings['products_heading'] ?? '';
		$html_tag         = $settings['heading_tag'] ?? 'h4';

		$product_id       = get_the_ID();
		$related_products = wc_get_related_products( $product_id );

		if ( $this->is_editor() || $related_products ) {

			if ( $custom_heading ) {
				?>
				<<?php echo sp_whitelist_html_tags( $html_tag, 'h4' ); ?> class="sp-products-heading">
					<?php echo esc_html( $products_heading ); ?>
				</<?php echo sp_whitelist_html_tags( $html_tag, 'h4' ); ?>>
				<?php
			}

			parent::render();
		}
	}
}

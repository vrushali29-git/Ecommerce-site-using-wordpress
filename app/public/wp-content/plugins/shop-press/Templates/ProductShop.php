<?php
/**
 * Product Shop.
 *
 * @package ShopPress
 */

namespace ShopPress\Templates;

defined( 'ABSPATH' ) || exit;

use WP_Query;

class ProductShop {
	/**
	 * Block|Widget attributes.
	 *
	 * @since 1.4.0
	 *
	 * @var array
	 */
	protected $attrs;

	/**
	 * Constructor.
	 *
	 * @since 1.4.0
	 *
	 * @param array $attributes Attributes from block or widget.
	 */
	public function __construct( $attributes ) {
		$this->attrs = $attributes;

		$this->before_shop_loop();

		add_action( 'shoppress/builder/after_shop_product_render', array( $this, 'display_load_more' ), 9, 1 );
		add_filter( 'shoppress/builder/shop_product_classes', array( $this, 'shop_product_classes' ), 9, 2 );
	}

	/**
	 * Display load more.
	 *
	 * @since 1.4.0
	 *
	 * @return void
	 */
	public function display_load_more( $attrs ) {
		$load_more_button = $attrs['load_more_button'] ?? false;
		$infinite_scroll  = $attrs['infinite_scroll'] ?? false;

		if ( $load_more_button ) {

			$button_style = $attrs['loadmore_button_style'] ?? 'icon_text';
			$btn_text     = '<span class="sp-product-loadmore-text">' . ( $attrs['loadmore_text'] ?? '' ) . '</span>';

			// TODO: make it compatible with block editor
			$btn_icon = sp_render_icon( $attrs['loadmore_button_icon'] ?? '' );

			switch ( $button_style ) {
				case 'text':
					$btn_html = $btn_text;
					break;
				case 'icon_text':
					$btn_html = $btn_icon . $btn_text;
					break;
				case 'text_icon':
					$btn_html = $btn_text . $btn_icon;
					break;
				case 'icon':
				default:
					$btn_html = $btn_icon;
					break;
			}
			echo '<div class="sp-loadmore-wrapper">';
			echo '<a href="#" class="sp-loadmore">' . sp_kses_post( $btn_html ) . '</a>';
			echo '</div>';
		}

		if ( $infinite_scroll ) {
			echo '<div class="sp-infinite-scroll"></div>';
		}
	}

	/**
	 * Filters product classes.
	 *
	 * @since 1.4.0
	 *
	 * @return string
	 */
	public function shop_product_classes( $classes, $attrs ) {
		$load_more_button = $attrs['load_more_button'] ?? false;
		$infinite_scroll  = $attrs['infinite_scroll'] ?? false;

		if ( $load_more_button || $infinite_scroll ) {
			$classes .= ' hide-pagination';
		}

		return $classes;
	}

	/**
	 * Before shop hooks.
	 *
	 * @since 1.4.6
	 */
	public function before_shop_loop() {
		$result_count     = $this->attrs['result_count'] ?? false;
		$catalog_ordering = $this->attrs['catalog_ordering'] ?? false;

		if ( ! $result_count ) {
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		}

		if ( ! $catalog_ordering ) {
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		}
	}

	/**
	 * Check editor scrren.
	 *
	 * @since 1.4.0
	 *
	 * @return bool
	 */
	private function is_edit_screen() {
		$is_block_editor     = isset( $_GET['_locale'] ) && $_GET['_locale'] === 'user';
		$is_elementor_editor = did_action( 'elementor/loaded' ) && \Elementor\Plugin::$instance->editor->is_edit_mode();

		return $is_block_editor || $is_elementor_editor;
	}

	/**
	 * Render shop products.
	 *
	 * @since 1.4.0
	 *
	 * @return void
	 */
	public function render() {
		/**
		 * Filter classes.
		 *
		 * @since 1.4.0
		 *
		 * @param array $attrs
		 */
		$classes = apply_filters( 'shoppress/builder/shop_product_classes', '', $this->attrs );

		echo '<div class="sp-products-loop-wrapper ' . esc_attr( $classes ) . '">';

		/**
		 * Fires before shop product render.
		 *
		 * @since 1.4.0
		 *
		 * @param array $attrs
		 */
		do_action( 'shoppress/builder/before_shop_product_render', $this->attrs );

		if ( woocommerce_product_loop() || $this->is_edit_screen() ) {

			/**
			 * Hook: woocommerce_before_shop_loop.
			 *
			 * @hooked woocommerce_output_all_notices - 10
			 * @hooked woocommerce_result_count - 20
			 * @hooked woocommerce_catalog_ordering - 30
			 */
			do_action( 'woocommerce_before_shop_loop' );

			woocommerce_product_loop_start();

			if ( wc_get_loop_prop( 'total' ) ) {

				while ( have_posts() ) {
					the_post();

					/**
					 * Hook: woocommerce_shop_loop.
					 */
					do_action( 'woocommerce_shop_loop' );

					wc_get_template_part( 'content', 'product' );
				}
			} elseif ( $this->is_edit_screen() ) {

				$the_query = new WP_Query(
					array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'posts_per_page' => 10,
					)
				);

				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					global $product;
					/**
					 * Hook: woocommerce_shop_loop.
					 */
					do_action( 'woocommerce_shop_loop' );

					wc_get_template_part( 'content', 'product' );
				}
			}

			woocommerce_product_loop_end();

			wp_reset_postdata();
			wc_reset_loop();

			/**
			 * Hook: woocommerce_after_shop_loop.
			 *
			 * @hooked woocommerce_pagination - 10
			 */
			do_action( 'woocommerce_after_shop_loop' );
		} else {
			/**
			 * Hook: woocommerce_no_products_found.
			 *
			 * @hooked wc_no_products_found - 10
			 */
			do_action( 'woocommerce_no_products_found' );
		}

		/**
		 * Fires after shop product render.
		 *
		 * @since 1.4.0
		 *
		 * @param array $attrs
		 */
		do_action( 'shoppress/builder/after_shop_product_render', $this->attrs );

		echo '</div>';
	}
}

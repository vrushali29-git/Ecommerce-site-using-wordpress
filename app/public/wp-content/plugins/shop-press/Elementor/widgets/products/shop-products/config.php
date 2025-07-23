<?php

namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\Widgets\ProductsLoop;
use ShopPress\Elementor\ControlsWidgets;
use ShopPress\Templates;

class ShopProducts extends ProductsLoop {
	public function get_name() {
		return 'sp-products';
	}

	public function get_title() {
		return __( 'Shop Products', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-recent-products';
	}

	public function get_style_depends() {
		if ( is_rtl() ) {
			return array( 'sp-products-loop', 'slick', 'sp-products-loop-rtl' );
		} else {
			return array( 'sp-products-loop', 'slick' );
		}
	}

	public function get_script_depends() {
		return array( 'sp-products-loop', 'sp-nicescroll-script', 'slick' );
	}

	public function get_categories() {
		return array( 'sp_woo_shop' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'General', 'shop-press' ),
			)
		);

		$templates           = Templates\Utils::get_loop_builder_templates();
		$product_loop_status = sp_is_template_active( 'products_loop' );
		ControlsWidgets::select2(
			'template_id',
			__( 'Template', 'shop-press' ),
			array(
				'options' => $templates,
			),
			0,
			$this,
			false,
			! $product_loop_status ? sprintf( esc_html__( 'To display the custom template in the front, you need to activate the %s component', 'shop-press' ), '<a href="' . esc_url( admin_url( 'admin.php?page=shoppress&sub=templates' ) ) . '" target="_blank">' . __( 'products loop', 'shop-press' ) . '</a>' ) : ''
		);

		ControlsWidgets::switcher(
			'result_count',
			__( 'Result Count', 'shop-press' ),
			array(),
			'yes',
			$this
		);

		ControlsWidgets::switcher(
			'catalog_ordering',
			__( 'Catalog Ordering', 'shop-press' ),
			array(),
			'yes',
			$this
		);

		ControlsWidgets::switcher(
			'infinite_scroll',
			__( 'Infinite Scroll', 'shop-press' ),
			array(
				'condition' => array(
					'load_more_button!' => 'yes',
				),
			),
			'',
			$this
		);

		ControlsWidgets::switcher(
			'load_more_button',
			__( 'Load More Button', 'shop-press' ),
			array(
				'condition' => array(
					'infinite_scroll!' => 'yes',
				),
			),
			'',
			$this
		);

		ControlsWidgets::select(
			'loadmore_button_style',
			__( 'Button Icon or Text', 'shop-press' ),
			array(
				'options'   => array(
					'text'      => __( 'Text', 'shop-press' ),
					'icon'      => __( 'Icon', 'shop-press' ),
					'text_icon' => __( 'Text Icon', 'shop-press' ),
					'icon_text' => __( 'Icon Text', 'shop-press' ),
				),
				'condition' => array(
					'load_more_button' => 'yes',
					'infinite_scroll!' => 'yes',
				),
			),
			'icon_text',
			$this
		);

		ControlsWidgets::icons(
			'loadmore_button_icon',
			__( 'Button Icon', 'shop-press' ),
			array(
				'condition' => array(
					'load_more_button'       => 'yes',
					'loadmore_button_style!' => 'text',
				),
			),
			array(
				'value'   => 'fas fa-plus-circle',
				'library' => 'fa-solid',
			),
			$this
		);

		ControlsWidgets::text(
			'loadmore_text',
			__( 'Load More Button Text', 'shop-press' ),
			array(
				'condition' => array(
					'load_more_button'       => 'yes',
					'loadmore_button_style!' => 'icon',
				),
			),
			__( 'Load More', 'shop-press' ),
			$this
		);

		$this->end_controls_section();

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '{{WRAPPER}}',
				),
			),
		);

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

		$this->register_group_styler(
			'result_count',
			__( 'Result Count', 'shop-press' ),
			array(
				'result_count_p' => array(
					'label'    => esc_html__( 'Result Count', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-result-count',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
		);

		$this->register_group_styler(
			'order_by',
			__( 'Order By', 'shop-press' ),
			array(
				'order_by_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-ordering',
					'wrapper'  => '{{WRAPPER}}',
				),
				'order_by_select'  => array(
					'label'    => esc_html__( 'Select', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'select',
					'wrapper'  => '{{WRAPPER}} .woocommerce-ordering',
				),
			),
		);

		$this->register_group_styler(
			'load_more',
			__( 'Load More', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-loadmore-wrapper',
					'wrapper'  => '{{WRAPPER}}',
				),
				'text'    => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-loadmore-wrapper .sp-loadmore',
					'wrapper'  => '{{WRAPPER}}',
				),
				'icon'    => array(
					'label'    => esc_html__( 'Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-loadmore-wrapper .sp-loadmore .sp-icon',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
			array(
				'load_more_button' => 'yes',
			)
		);

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	public function render() {
		$settings = $this->get_settings_for_display();

		if ( $this->is_editor() ) {
			?>
			<?php if ( $settings['result_count'] ) : ?>
				<p class="woocommerce-result-count"><?php echo __( 'Showing all 12 results', 'shop-press' ) ?></p>
			<?php endif; ?>

			<?php if ( $settings['catalog_ordering'] ) : ?>
				<form class="woocommerce-ordering" method="get">
					<select name="orderby" class="orderby" aria-label="Shop order">
							<option value="menu_order" selected="selected"><?php echo __( 'Default sorting', 'shop-press' ) ?></option>
							<option value="popularity"><?php echo __( 'Sort by popularity', 'shop-press' ) ?></option>
							<option value="rating"><?php echo __( 'Sort by average rating', 'shop-press' ) ?></option>
							<option value="date"><?php echo __( 'Sort by latest', 'shop-press' ) ?></option>
							<option value="price"><?php echo __( 'Sort by price: low to high', 'shop-press' ) ?></option>
							<option value="price-desc"><?php echo __( 'Sort by price: high to low', 'shop-press' ) ?></option>
					</select>
					<input type="hidden" name="paged" value="1">
				</form>
			<?php endif; ?>
			<?php

			echo '<script>jQuery(document).ready(function($){if (typeof sp_slider_init === "function") {sp_slider_init();}});</script>';
		}

		$custom_type      = get_post_meta( get_the_ID(), 'custom_type', true );
		$is_products_loop = in_array( $custom_type, array( 'shop', 'archive' ) );

		if ( $is_products_loop || is_archive() || is_shop() ) {

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo sp_render_shop_products( $settings );
		}
	}
}

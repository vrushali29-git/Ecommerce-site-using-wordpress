<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Price extends ShopPressWidgets {

	public function get_name() {
		return 'sp-price';
	}

	public function get_title() {
		return __( 'Product Price', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-price';
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}

	public function setup_styling_options() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-price-wrapper',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'price',
			__( 'Price', 'shop-press' ),
			array(
				'container'            => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'p.price',
					'wrapper'  => '{{WRAPPER}} .sp-price-wrapper',
				),
				'single_regular_price' => array(
					'label'    => esc_html__( 'Regular Price', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '> span.amount',
					'wrapper'  => '{{WRAPPER}} .sp-price-wrapper p.price',
				),
				'regular_price'        => array(
					'label'    => esc_html__( 'Del Price (Line-through)', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'del',
					'wrapper'  => '{{WRAPPER}} .sp-price-wrapper p.price',
				),
				'sale_price'           => array(
					'label'    => esc_html__( 'Sale Price', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'ins',
					'wrapper'  => '{{WRAPPER}} .sp-price-wrapper p.price',
				),
				'symbol_price'         => array(
					'label'    => esc_html__( 'Symbol', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-Price-currencySymbol',
					'wrapper'  => '{{WRAPPER}} .sp-price-wrapper .woocommerce-Price-amount.amount',
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
			sp_load_builder_template( 'single-product/product-price' );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-price-wrapper">
			<p class="price">
				<del aria-hidden="true">
					<span class="woocommerce-Price-amount amount">
						<bdi>
							<span class="woocommerce-Price-currencySymbol">$</span>55.00
						</bdi>
					</span>
				</del>
				<ins>
					<span class="woocommerce-Price-amount amount">
						<bdi>
							<span class="woocommerce-Price-currencySymbol">$</span>15.00
						</bdi>
					</span>
				</ins>
			</p>
		</div>
		<?php
	}
}

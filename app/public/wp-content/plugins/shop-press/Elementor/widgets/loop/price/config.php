<?php
namespace ShopPress\Elementor\Widgets\LoopBuilder;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Price extends ShopPressWidgets {

	public function get_name() {
		return 'sp-item-price';
	}

	public function get_title() {
		return __( 'Product Price', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-price';
	}

	public function get_categories() {
		return array( 'sp_woo_loop' );
	}

	public function setup_styling_options() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-price',
				),
			)
		);

		$this->register_group_styler(
			'price',
			__( 'Price', 'shop-press' ),
			array(
				'price_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.price',
					'wrapper'  => '{{WRAPPER}} .sp-product-price',
				),
				'price'         => array(
					'label'    => esc_html__( 'Regular Price', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'del span.woocommerce-Price-amount.amount',
					'wrapper'  => '{{WRAPPER}} .sp-product-price .price',
				),
				'sale_price'    => array(
					'label'    => esc_html__( 'Sale Price', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span.woocommerce-Price-amount.amount',
					'wrapper'  => '{{WRAPPER}} .sp-product-price .price',
				),
				'symbol_price'  => array(
					'label'    => esc_html__( 'Symbol', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-Price-currencySymbol',
					'wrapper'  => '{{WRAPPER}} .sp-product-price .woocommerce-Price-amount.amount',
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
			sp_load_builder_template( 'loop/loop-price' );
		}
	}

	protected function content_template() {
		$classes = apply_filters( 'woocommerce_product_price_class', 'price' );
		?>
		<div class="sp-product-price">
			<p class="<?php echo esc_attr( $classes ); ?>">
				<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>20</span>
			</p>
		</div>
		<?php
	}
}

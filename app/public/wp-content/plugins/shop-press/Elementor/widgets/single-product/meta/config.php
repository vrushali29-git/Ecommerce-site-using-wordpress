<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

class Meta extends ShopPressWidgets {

	public function get_name() {
		return 'sp-product-meta';
	}

	public function get_title() {
		return __( 'Product Meta', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-categories';
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
					'selector' => '.sp-pr-meta',
					'wrapper'  => '{{WRAPPER}}',
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
			sp_load_builder_template( 'single-product/product-meta' );
		}
	}

	protected function content_template() {
		?>
			<div class="sp-pr-meta">
				<div class="product_meta">
					<span class="sku_wrapper">SKU: <span class="sku">woo-album</span></span>
					<span class="posted_in">Category: <a href="#" rel="tag">Clothing</a></span>
					<span class="tagged_as">Tag: <a href="#" rel="tag">Tshirts</a></span>
				</div>
			</div>
		<?php
	}
}

<?php
namespace ShopPress\Elementor\Widgets\LoopBuilder;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

defined( 'ABSPATH' ) || exit;

class SKU extends ShopPressWidgets {

	public function get_name() {
		return 'sp-item-item-sku';
	}

	public function get_title() {
		return __( 'Product SKU', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-loop-sku';
	}

	public function get_categories() {
		return array( 'sp_woo_loop' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'SKU', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'SKU', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-loop-product-sku',
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
			sp_load_builder_template( 'loop/loop-sku' );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-loop-product-sku">tshirt-logo</div>
		<?php
	}
}

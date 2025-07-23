<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Description extends ShopPressWidgets {

	public function get_name() {
		return 'sp-description';
	}

	public function get_title() {
		return __( 'Product Short Description', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-description';
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
					'selector' => '.sp-description-wrapper',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'description',
			__( 'Description', 'shop-press' ),
			array(
				'heading' => array(
					'label'    => esc_html__( 'Description', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-description',
					'wrapper'  => '{{WRAPPER}} .sp-description-wrapper',
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
			sp_load_builder_template( 'single-product/product-description' );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-description-wrapper">
			<p class="sp-description">
				This is a simple product.
			</p>
		</div>
		<?php
	}
}

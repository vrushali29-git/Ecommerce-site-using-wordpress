<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Content extends ShopPressWidgets {

	public function get_name() {
		return 'sp-content';
	}

	public function get_title() {
		return __( 'Product Description', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-content';
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
					'selector' => '.sp-product-content-wrapper',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'content',
			__( 'Content', 'shop-press' ),
			array(
				'content' => array(
					'label'    => esc_html__( 'Content', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-content',
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
			sp_load_builder_template( 'single-product/product-content' );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-product-content-wrapper">
			<p class="sp-product-content">
				Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.
			</p>
		</div>
		<?php
	}
}

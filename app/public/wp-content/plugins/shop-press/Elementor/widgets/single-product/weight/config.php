<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Weight extends ShopPressWidgets {

	public function get_name() {
		return 'sp-weight';
	}

	public function get_title() {
		return __( 'Product Weight', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-weight';
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
					'selector' => '.sp-weight-wrapper',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'weight',
			__( 'Weight', 'shop-press' ),
			array(
				'weight'      => array(
					'label'    => esc_html__( 'Weight', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.value',
					'wrapper'  => '{{WRAPPER}} .sp-weight-wrapper',
				),
				'weight_unit' => array(
					'label'    => esc_html__( 'unit', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.unit',
					'wrapper'  => '{{WRAPPER}} .sp-weight-wrapper',
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
			sp_load_builder_template( 'single-product/product-weight' );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-weight-wrapper">
			<span class="value">1</span>
			<sub class="unit">kg</sub>
		</div>
		<?php
	}
}

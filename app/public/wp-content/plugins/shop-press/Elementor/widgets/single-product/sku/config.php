<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

class SKU extends ShopPressWidgets {

	public function get_name() {
		return 'sp-sku';
	}

	public function get_title() {
		return __( 'Product SKU', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-sku';
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
					'selector' => '.sp-sku-wrapper',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'sku1',
			__( 'SKU', 'shop-press' ),
			array(
				'sku'       => array(
					'label'    => esc_html__( 'SKU', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-sku',
					'wrapper'  => '{{WRAPPER}} .sp-sku-wrapper',
				),
				'sku_label' => array(
					'label'    => esc_html__( 'Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-sku-label',
					'wrapper'  => '{{WRAPPER}} .sp-sku-wrapper',
				),
			)
		);
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'General', 'shop-press' ),
			)
		);

		$this->add_control(
			'sku_label',
			array(
				'label'   => __( 'SKU Label', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'SKU:', 'shop-press' ),

			)
		);

		$this->end_controls_section();
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array(
			'sku_label' => $settings['sku_label'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/product-sku', $args );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-sku-wrapper">
			<span class="sp-sku-label">{{{ settings.sku_label }}}</span>
			<span class="sp-sku sku">tshirt-logo</span>
		</div>
		<?php
	}
}

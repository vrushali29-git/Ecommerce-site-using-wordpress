<?php
namespace ShopPress\Elementor\Widgets\LoopBuilder;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Stock extends ShopPressWidgets {

	public function get_name() {
		return 'sp-item-stock';
	}

	public function get_title() {
		return __( 'Product Stock', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-stock';
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
					'selector' => '{{WRAPPER}} .sp-product-stock',
				),
			)
		);

		$this->register_group_styler(
			'stock',
			__( 'Stock', 'shop-press' ),
			array(
				'label'       => array(
					'label'    => esc_html__( 'Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '{{WRAPPER}} .sp-stock-label',
				),
				'stock_value' => array(
					'label'    => esc_html__( 'Availability', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '{{WRAPPER}} .sp-stock-availability',
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
			'label',
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Label', 'shop-press' ),
				'placeholder' => __( 'Availability:', 'shop-press' ),
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
			'label' => $settings['label'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'loop/loop-stock', $args );
		}
	}

	protected function content_template() {
		?>
		<p class="sp-product-stock">
			<span class="sp-stock-label">{{{ settings.label }}}</span>
			<span class="sp-stock-availability">Out of stock</span>
		</p>
		<?php
	}
}

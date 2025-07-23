<?php
namespace ShopPress\Elementor\Widgets\LoopBuilder;

use Elementor\Controls_Manager;

use ShopPress\Elementor\ShopPressWidgets;

defined( 'ABSPATH' ) || exit;

class Discount extends ShopPressWidgets {

	public function get_name() {
		return 'sp-item-discount';
	}

	public function get_title() {
		return __( 'Product Discount', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-loop-product-discount';
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
					'label'    => esc_html__( 'Discount', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-discount',
				),
				'label'   => array(
					'label'    => esc_html__( 'Discount Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-discount .sp-product-discount-label',
				),
				'price'   => array(
					'label'    => esc_html__( 'Discount Price', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-discount .sp-product-discount-price',
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
				'label'   => __( 'Label', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
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
			sp_load_builder_template( 'loop/loop-discount', $args );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-product-discount">
			<span class="sp-product-discount-label">{{{ settings.label }}}</span> <span class="sp-product-discount-price"> 10% </span>
		</div>
		<?php
	}
}

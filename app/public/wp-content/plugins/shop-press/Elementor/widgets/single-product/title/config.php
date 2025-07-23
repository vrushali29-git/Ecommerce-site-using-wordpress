<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Title extends ShopPressWidgets {

	public function get_name() {
		return 'sp-title';
	}

	public function get_title() {
		return __( 'Product Title', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-title';
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
					'selector' => '.sp-title-wrapper',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'heading',
			__( 'Heading', 'shop-press' ),
			array(
				'heading' => array(
					'label'    => esc_html__( 'Heading', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.product_title',
					'wrapper'  => '{{WRAPPER}} .sp-title-wrapper',
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
			'tag',
			array(
				'label'   => __( 'Title HTML Tag', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => array(
					'h1' => __( 'h1', 'shop-press' ),
					'h2' => __( 'h2', 'shop-press' ),
					'h3' => __( 'h3', 'shop-press' ),
					'h4' => __( 'h4', 'shop-press' ),
					'h5' => __( 'h5', 'shop-press' ),
					'h6' => __( 'h6', 'shop-press' ),
				),
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
			'tag' => $settings['tag'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/product-title', $args );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-title-wrapper">
			<{{{ settings.tag }}} class="product_title entry-title">
			Long Sleeve Tee
			</{{{ settings.tag }}}>
		</div>
		<?php
	}
}

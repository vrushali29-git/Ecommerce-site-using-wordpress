<?php
namespace ShopPress\Elementor\Widgets\LoopBuilder;

use Elementor\Controls_Manager;

use ShopPress\Elementor\ShopPressWidgets;

defined( 'ABSPATH' ) || exit;

class Title extends ShopPressWidgets {

	public function get_name() {
		return 'sp-item-title';
	}

	public function get_title() {
		return __( 'Product Title', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-title';
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
					'label'    => esc_html__( 'Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-title',
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
				'default' => 'h4',
				'options' => array(
					'h1'   => __( 'h1', 'shop-press' ),
					'h2'   => __( 'h2', 'shop-press' ),
					'h3'   => __( 'h3', 'shop-press' ),
					'h4'   => __( 'h4', 'shop-press' ),
					'h5'   => __( 'h5', 'shop-press' ),
					'h6'   => __( 'h6', 'shop-press' ),
					'p'    => __( 'p', 'shop-press' ),
					'span' => __( 'span', 'shop-press' ),
				),
			)
		);

		$this->add_control(
			'link_to_product',
			array(
				'label'        => __( 'Link to Product', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'link_target',
			array(
				'label'     => __( 'Open link in', 'shop-press' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '_self',
				'options'   => array(
					'_blank' => __( 'New Window', 'shop-press' ),
					'_self'  => __( 'Current Window', 'shop-press' ),
				),
				'condition' => array(
					'link_to_product' => 'yes',
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
			'link_to_product' => $settings['link_to_product'],
			'link_target'     => $settings['link_target'],
			'tag'             => $settings['tag'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'loop/loop-title', $args );
		}
	}

	protected function content_template() {
		?>
		<{{{ settings.tag }}} class="product_title entry-title sp-product-title">
		Long Sleeve Tee
		</{{{ settings.tag }}}>
		<?php
	}
}

<?php
namespace ShopPress\Elementor\Widgets\LoopBuilder;

use Elementor\Controls_Manager;

use ShopPress\Elementor\ShopPressWidgets;

defined( 'ABSPATH' ) || exit;

class Categories extends ShopPressWidgets {

	public function get_name() {
		return 'sp-item-categories';
	}

	public function get_title() {
		return __( 'Product Categories', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-loop-categories';
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
					'selector' => '.sp-product-cats',
				),
			)
		);

		$this->register_group_styler(
			'categories',
			__( 'Categories', 'shop-press' ),
			array(
				'item'      => array(
					'label'    => esc_html__( 'Category', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} .sp-product-cats',
				),
				'seperator' => array(
					'label'    => esc_html__( 'Seperator', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.separator',
					'wrapper'  => '{{WRAPPER}} .sp-product-cats',
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
			'separator',
			array(
				'label'   => __( 'Separator', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '|',
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
			'separator' => $settings['separator'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'loop/loop-categories', $args );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-product-cats">
			<a href="#" rel="tag">Tshirts</a>
		</div>
		<?php
	}
}

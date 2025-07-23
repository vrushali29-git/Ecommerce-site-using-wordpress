<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

class Categories extends ShopPressWidgets {

	public function get_name() {
		return 'sp-categories';
	}

	public function get_title() {
		return __( 'Product Categories', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-categories';
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
					'selector' => '.sp-categories-wrapper',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'categories',
			__( 'Categories', 'shop-press' ),
			array(
				'categories_container' => array(
					'label'    => esc_html__( 'Categories Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-categories',
					'wrapper'  => '{{WRAPPER}} .sp-categories-wrapper',
				),
				'cat_label'            => array(
					'label'    => esc_html__( 'Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-category-label',
					'wrapper'  => '{{WRAPPER}} .sp-categories-wrapper',
				),
				'category'             => array(
					'label'    => esc_html__( 'Category Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} .sp-product-categories',
				),
				'sep'                  => array(
					'label'    => esc_html__( 'Separator', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span.sp-cat-separator',
					'wrapper'  => '{{WRAPPER}} .sp-product-categories',
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
			'category_label',
			array(
				'label'   => __( 'Category Label', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Category:', 'shop-press' ),

			)
		);

		$this->add_control(
			'category_separator',
			array(
				'label'   => __( 'Separator', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => ', ',
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
			'category_label'     => $settings['category_label'],
			'category_separator' => $settings['category_separator'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/product-categories', $args );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-categories-wrapper">
			<span class="sp-category-label">{{{ settings.category_label }}}</span>
			<span class="sp-product-categories">
				<a href="#">Clothing</a><span class="sp-cat-separator">{{{ settings.category_separator }}}</span>
				<a href="#">Hoodies</a>
			</span>
		</div>
		<?php
	}
}

<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

class Tags extends ShopPressWidgets {

	public function get_name() {
		return 'sp-tags';
	}

	public function get_title() {
		return __( 'Product Tags', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-tags';
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
					'selector' => '.sp-tags-wrapper',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'tags',
			__( 'Tags', 'shop-press' ),
			array(
				'tags_container' => array(
					'label'    => esc_html__( 'Tags Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-tags',
					'wrapper'  => '{{WRAPPER}} .sp-tags-wrapper',
				),
				'tags_label'     => array(
					'label'    => esc_html__( 'Tags Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-tag-label',
					'wrapper'  => '{{WRAPPER}} .sp-tags-wrapper',
				),
				'tag'            => array(
					'label'    => esc_html__( 'Tag Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} .sp-product-tags',
				),
				'sep'            => array(
					'label'    => esc_html__( 'Separator', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span.sp-tags-separator',
					'wrapper'  => '{{WRAPPER}} .sp-tags-wrapper span.sp-product-tags',
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
			'tag_label',
			array(
				'label'   => __( 'Tag Label', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Tags:', 'shop-press' ),

			)
		);
		$this->add_control(
			'tag_separator',
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
			'tag_separator' => $settings['tag_separator'],
			'tag_label'     => $settings['tag_label'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/product-tags', $args );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-tags-wrapper">
			<span class="sp-tag-label">{{{ settings.tag_label }}}</span>
			<span class="sp-product-tags">
				<a href="#">T-shirt</a>
				<span class="sp-tags-separator">{{{ settings.tag_separator }}}</span>
				<a href="#">Men's T-shirt</a>
			</span>
		</div>
		<?php
	}
}

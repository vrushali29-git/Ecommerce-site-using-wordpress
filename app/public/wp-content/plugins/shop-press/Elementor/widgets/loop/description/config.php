<?php
namespace ShopPress\Elementor\Widgets\LoopBuilder;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Description extends ShopPressWidgets {

	public function get_name() {
		return 'sp-item-description';
	}

	public function get_title() {
		return __( 'Product Short Description', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-description';
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
					'selector' => '.sp-product-description',
				),
			)
		);

		$this->register_group_styler(
			'content',
			__( 'Content', 'shop-press' ),
			array(
				'div'       => array(
					'label'    => esc_html__( 'Div', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'div',
					'wrapper'  => '{{WRAPPER}} .sp-product-description',
				),
				'paragraph' => array(
					'label'    => esc_html__( 'Paragraph', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'p',
					'wrapper'  => '{{WRAPPER}} .sp-product-description',
				),
				'heading_1' => array(
					'label'    => esc_html__( 'Heading 1', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'h1',
					'wrapper'  => '{{WRAPPER}} .sp-product-description',
				),
				'heading_2' => array(
					'label'    => esc_html__( 'Heading 2', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'h2',
					'wrapper'  => '{{WRAPPER}} .sp-product-description',
				),
				'heading_3' => array(
					'label'    => esc_html__( 'Heading 3', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'h3',
					'wrapper'  => '{{WRAPPER}} .sp-product-description',
				),
				'heading_4' => array(
					'label'    => esc_html__( 'Heading 4', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'h4',
					'wrapper'  => '{{WRAPPER}} .sp-product-description',
				),
				'heading_5' => array(
					'label'    => esc_html__( 'Heading 5', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'h5',
					'wrapper'  => '{{WRAPPER}} .sp-product-description',
				),
				'heading_6' => array(
					'label'    => esc_html__( 'Heading 6', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'h6',
					'wrapper'  => '{{WRAPPER}} .sp-product-description',
				),
				'span'      => array(
					'label'    => esc_html__( 'Span', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span',
					'wrapper'  => '{{WRAPPER}} .sp-product-description',
				),
				'strong'    => array(
					'label'    => esc_html__( 'Strong', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'strong',
					'wrapper'  => '{{WRAPPER}} .sp-product-description',
				),
				'link'      => array(
					'label'    => esc_html__( 'Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} .sp-product-description',
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
			sp_load_builder_template( 'loop/loop-description' );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-product-description">
			<p>This is a simple product.</p>
		</div>
		<?php
	}
}

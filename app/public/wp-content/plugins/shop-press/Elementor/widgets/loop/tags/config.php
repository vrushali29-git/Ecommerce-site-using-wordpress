<?php

namespace ShopPress\Elementor\Widgets\LoopBuilder;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

defined( 'ABSPATH' ) || exit;

class Tags extends ShopPressWidgets {

	public function get_name() {
		return 'sp-item-tags';
	}

	public function get_title() {
		return __( 'Product Tags', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-loop-tags';
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
					'selector' => '.sp-product-tags',
				),
			)
		);

		$this->register_group_styler(
			'tags',
			__( 'Tags', 'shop-press' ),
			array(
				'item'      => array(
					'label'    => esc_html__( 'Tag', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} .sp-product-tags',
				),
				'seperator' => array(
					'label'    => esc_html__( 'Seperator', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.separator',
					'wrapper'  => '{{WRAPPER}} .sp-product-tags',
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
			sp_load_builder_template( 'loop/loop-tags', $args );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-product-tags">
			<a href="#" rel="tag">Clothing</a>
		</div>
		<?php
	}
}

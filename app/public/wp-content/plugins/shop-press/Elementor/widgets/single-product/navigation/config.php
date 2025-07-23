<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Navigation extends ShopPressWidgets {

	public function get_name() {
		return 'sp-navigation';
	}

	public function get_title() {
		return __( 'Product Navigation', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-navigation';
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
					'selector' => '.sp-navigation',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'item',
			__( 'Item', 'shop-press' ),
			array(
				'item_wrapper'    => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-navigation-wrap',
					'wrapper'  => '{{WRAPPER}} .sp-navigation',
				),
				'item_navigation' => array(
					'label'    => esc_html__( 'Navigation', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-navigation-link',
					'wrapper'  => '{{WRAPPER}} .sp-navigation .sp-navigation-wrap',
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
			'hover_details',
			array(
				'label'   => __( 'Product details on hover', 'shop-press' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'        => __( 'None', 'shop-press' ),
					'title'       => __( 'Title', 'shop-press' ),
					'thumb'       => __( 'Thumbnail', 'shop-press' ),
					'tthumb'      => __( 'Title + Thumbnail', 'shop-press' ),
					'tprice'      => __( 'Title + Price', 'shop-press' ),
					'tthumbprice' => __( 'Title + Thumbnail + Price', 'shop-press' ),
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
			'hover_details' => $settings['hover_details'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/product-navigation', $args );
		}
	}
}

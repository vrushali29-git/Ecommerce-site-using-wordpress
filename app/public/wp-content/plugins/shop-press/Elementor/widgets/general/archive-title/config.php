<?php

namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;

defined( 'ABSPATH' ) || exit;

class ArchiveTitle extends ShopPressWidgets {

	public function get_name() {
		return 'sp-archive-title';
	}

	public function get_title() {
		return esc_html__( 'Archive Title', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-archive-title';
	}

	public function get_categories() {
		return array( 'sp_woo_shop' );
	}

	protected function register_controls() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => __( 'Wrapper', 'shop-press' ),
					'selector' => '.sp-archive-title',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
		);

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array();
		sp_load_builder_template( 'general/archive-title', $args );
	}
}

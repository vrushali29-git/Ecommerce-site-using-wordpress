<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class CurrencySwitcher extends ShopPressWidgets {

	public function get_name() {
		return 'sp-currency-switcher';
	}

	public function get_title() {
		return __( 'Currency Switcher', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-currency-switcher';
	}

	public function get_style_depends() {
		return array( 'sp-currency-switcher' );
	}

	public function get_categories() {
		return array( 'sp_general' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'General', 'shop-press' ),
			)
		);

		$this->add_control(
			'format',
			array(
				'label'       => __( 'Format', 'shop-press' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '%name%',
				'description' => __( 'Default: %name% Available parameters: %code%, %symbol%, %name%', 'shop-press' ),
			)
		);

		$this->add_control(
			'switcher_style',
			array(
				'label'       => __( 'Currency switcher style', 'shop-press' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'wcml-dropdown',
				'options'     => array(
					'wcml-dropdown'        => __( 'Dropdown', 'shop-press' ),
					'wcml-dropdown-click'  => __( 'Dropdown click', 'shop-press' ),
					'wcml-horizontal-list' => __( 'Horizontal List', 'shop-press' ),
					'wcml-vertical-list'   => __( 'Vertical List', 'shop-press' ),
				),
			)
		);

		$this->end_controls_section();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array(
			'format'         => $settings['format'],
			'switcher_style' => $settings['switcher_style'],
		);

		sp_load_builder_template( 'general/currency-switcher', $args );
	}
}

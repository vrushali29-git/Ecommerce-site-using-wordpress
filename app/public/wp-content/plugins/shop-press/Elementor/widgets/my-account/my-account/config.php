<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class MyAccount extends ShopPressWidgets {

	public function get_name() {
		return 'sp-my-account';
	}

	public function get_title() {
		return __( 'My Account', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-dashboard';
	}

	public function get_categories() {
		return array( 'sp_woo_dashboard' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-my-account-wrapper',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'navigation',
			__( 'Navigation', 'shop-press' ),
			array(
				'nav_wrapper'   => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-MyAccount-navigation',
					'wrapper'  => '{{WRAPPER}}',
				),
				'nav_item'      => array(
					'label'    => esc_html__( 'List item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link',
					'wrapper'  => '{{WRAPPER}}',
				),
				'nav_item_link' => array(
					'label'    => esc_html__( 'List item link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link a',
					'wrapper'  => '{{WRAPPER}}',
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
			'menu_items',
			array(
				'label'       => esc_html__( 'Remove Items', 'shop-press' ),
				'description' => __( 'Selected items will be removed from the navigation.', 'shop-press' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => wc_get_account_menu_items(),
			)
		);

		// $this->register_nav_icons();

		$this->end_controls_section();
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	// protected function register_nav_icons() {

	// foreach ( wc_get_account_menu_items() as $endpoint => $label ) {
	// $this->add_control(
	// 'nav_icon_' . $endpoint . '',
	// array(
	// 'label'            => __( '' . $label . ' Icon', 'shop-press' ),
	// 'type'             => Controls_Manager::ICONS,
	// 'fa4compatibility' => 'icon',
	// )
	// );
	// }
	// }

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array(
			'menu_items' => $settings['menu_items'],
		);

		sp_load_builder_template( 'my-account/my-account', $args );
	}
}

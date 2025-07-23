<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

class ReturnToShop extends ShopPressWidgets {
	public function get_name() {
		return 'sp-return-to-shop';
	}

	public function get_title() {
		return __( 'Return To Shop', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-return-to-shop';
	}

	public function get_categories() {
		return array( 'sp_woo_cart' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-return-to-shop',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'icon',
			__( 'Icon', 'shop-press' ),
			array(
				'icon' => array(
					'label'    => esc_html__( 'Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-return-to-shop .sp-icon',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'button',
			__( 'Button', 'shop-press' ),
			array(
				'button' => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-return-to-shop .button',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'title',
			__( 'Title', 'shop-press' ),
			array(
				'title' => array(
					'label'    => esc_html__( 'Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-return-to-shop a',
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
			'button_style',
			array(
				'label'   => __( 'Button Style', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'text'        => __( 'Text', 'shop-press' ),
					'icon'        => __( 'Icon', 'shop-press' ),
					'button_icon' => __( 'Text Icon', 'shop-press' ),
					'icon_button' => __( 'Icon Text', 'shop-press' ),
				),
				'default' => 'button_icon',
			)
		);

		$this->add_control(
			'custom_text',
			array(
				'label'   => esc_html__( 'Custom Text', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Return To Shop', 'shop-press' ),
			)
		);

		$this->add_control(
			'custom_link',
			array(
				'label'         => esc_html__( 'Custom Link', 'shop-press' ),
				'type'          => Controls_Manager::TEXT,
				'placeholder'   => esc_html__( 'https://your-link.com', 'shop-press' ),
				'show_external' => false,
			)
		);

		$this->add_control(
			'btn_icon',
			array(
				'label' => __( 'Button Icon', 'shop-press' ),
				'type'  => Controls_Manager::ICONS,
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
			'custom_link'  => $settings['custom_link'],
			'button_style' => $settings['button_style'],
			'btn_icon'     => $settings['btn_icon'],
			'custom_text'  => $settings['custom_text'],
		);

		sp_load_builder_template( 'cart/cart-return-to-shop', $args );
	}
}

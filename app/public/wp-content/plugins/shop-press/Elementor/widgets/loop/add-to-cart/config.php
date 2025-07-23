<?php
namespace ShopPress\Elementor\Widgets\LoopBuilder;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;
use ShopPress\Modules;

class AddToCart extends ShopPressWidgets {

	public function get_name() {
		return 'sp-product-add-to-cart';
	}

	public function get_title() {
		return __( 'Add To Cart', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-add-to-cart';
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
					'selector' => '.sp-product-add-to-cart',
				),
			)
		);
		$this->register_group_styler(
			'button',
			__( 'Button', 'shop-press' ),
			array(
				'button'      => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a.button:not([disabled]):not(.disabled)',
					'wrapper'  => '{{WRAPPER}} .sp-product-add-to-cart',
				),
				'button_icon' => array(
					'label'    => esc_html__( 'Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-icon',
					'wrapper'  => '{{WRAPPER}} .sp-product-add-to-cart',
				),
			)
		);

		$this->register_group_styler(
			'tooltip_overlay',
			__( 'Tooltip Overlay', 'shop-press' ),
			array(
				'tooltip'       => array(
					'label'    => esc_html__( 'Tooltip', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-add-to-cart.overlay .sp-add-to-cart-label',
					'wrapper'  => '{{WRAPPER}}',
				),
				'tooltip_arrow' => array(
					'label'    => esc_html__( 'Tooltip Arrow', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-add-to-cart.overlay span:before',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
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
			'icon',
			array(
				'label' => __( 'Icon', 'shop-press' ),
				'type'  => Controls_Manager::ICONS,
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'   => __( 'Icon Position', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => array(
					''       => __( 'None', 'shop-press' ),
					'before' => __( 'Before', 'shop-press' ),
					'after'  => __( 'After', 'shop-press' ),
				),
			)
		);

		$this->add_control(
			'overlay',
			array(
				'type'        => Controls_Manager::SWITCHER,
				'label'       => __( 'Floating Position', 'shop-press' ),
				'label_on'    => __( 'Yes', 'shop-press' ),
				'label_off'   => __( 'No', 'shop-press' ),
				'description' => esc_html__( 'When you activate this option, the button will be positioned as an overlay on the parent section. Its position will be set to "absolute" and you can determine the exact location of this button in the style tab by using the styler associated with the Wrapper.', 'textdomain' ),
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
			'icon_position' => $settings['icon_position'],
			'icon'          => $settings['icon'],
			'overlay'       => $settings['overlay'],
		);

		if ( $this->editor_preview() ) {

			if ( ! Modules\CatalogMode::is_catalog_mode() ) {

				sp_load_builder_template( 'loop/loop-add-to-cart', $args );
			}
		}
	}
}

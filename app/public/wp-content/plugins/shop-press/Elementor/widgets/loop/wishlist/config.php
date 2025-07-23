<?php
namespace ShopPress\Elementor\Widgets\LoopBuilder;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

class Wishlist extends ShopPressWidgets {

	public function get_name() {
		return 'sp-product-wishlist';
	}

	public function get_title() {
		return __( 'Wishlist Button', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-wishlist';
	}

	public function get_categories() {
		return array( 'sp_woo_loop' );
	}

	public function get_script_depends() {
		return array( 'sp-wishlist' );
	}

	public function get_style_depends() {
		return array( 'sp-wishlist' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist-loop',
					'wrapper'  => '{{WRAPPER}}',
				),
				'button'  => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist-button',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist-loop',
				),
			)
		);

		$this->register_group_styler(
			'icon',
			__( 'Icon', 'shop-press' ),
			array(
				'icon_wrapper' => array(
					'label'    => esc_html__( 'Icon Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist-icon',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist-loop',
				),
				'icon'         => array(
					'label'    => esc_html__( 'Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'i.sp-icon',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist-loop .sp-wishlist-icon',
				),
				'active_icon'  => array(
					'label'    => esc_html__( 'Active Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'i.sp-icon',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist-loop[data-status="yes"] .sp-wishlist-icon',
				),
			),
		);

		$this->register_group_styler(
			'label',
			__( 'Label', 'shop-press' ),
			array(
				'label'        => array(
					'label'    => esc_html__( 'Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist-label',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist-loop',
				),
				'active_label' => array(
					'label'    => esc_html__( 'Active Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist-label',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist-loop[data-status="yes"]',
				),
			),
		);

		$this->register_group_styler(
			'tooltip_overlay',
			__( 'Tooltip Overlay', 'shop-press' ),
			array(
				'tooltip'       => array(
					'label'    => esc_html__( 'Tooltip', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist.overlay .sp-wishlist-label',
					'wrapper'  => '{{WRAPPER}}',
				),
				'tooltip_arrow' => array(
					'label'    => esc_html__( 'Tooltip Arrow', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist.overlay span:before',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
		);
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Settings', 'shop-press' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
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
			'icon_pos',
			array(
				'label'   => __( 'Icon Position', 'shop-press' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'toggle'  => false,
				'options' => array(
					'left'  => array(
						'title' => __( 'Left', 'shop-press' ),
						'icon'  => 'eicon-text-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'shop-press' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
			)
		);

		$this->add_control(
			'show_label',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => __( 'Show Label', 'shop-press' ),
				'label_on'     => __( 'Yes', 'shop-press' ),
				'label_off'    => __( 'No', 'shop-press' ),
				'return_value' => 'yes',
				'default'      => 'yes',
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
			'icon'       => $settings['icon'],
			'overlay'    => $settings['overlay'],
			'icon_pos'   => $settings['icon_pos'],
			'show_label' => $settings['show_label'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'loop/loop-wishlist', $args );
		}
	}
}

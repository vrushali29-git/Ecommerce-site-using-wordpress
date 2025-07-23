<?php

namespace ShopPress\Elementor\Widgets;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use ShopPress\Elementor\ShopPressWidgets;

defined( 'ABSPATH' ) || exit;

class MiniCart extends ShopPressWidgets {

	public function get_name() {
		return 'sp-mini-cart';
	}

	public function get_title() {
		return esc_html__( 'Mini Cart', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-mini-cart';
	}

	public function get_categories() {
		return array( 'sp_general' );
	}

	public function get_script_depends() {
		return array( 'sp-header-toggle' );
	}

	public function get_style_depends() {

		if ( is_rtl() ) {
			return array( 'sp-header-toggle', 'sp-mini-cart', 'sp-header-toggle-rtl', 'sp-mini-cart-rtl' );
		} else {
			return array( 'sp-header-toggle', 'sp-mini-cart' );
		}
	}

	protected function register_controls() {
		// Content options Start
		$this->start_controls_section(
			'section_text_content',
			array(
				'label' => esc_html__( 'Content Toggle Settings', 'shop-press' ),
			)
		);
		$this->add_control(
			'placeholder',
			array(
				'label'   => __( 'Placeholder', 'shop-press' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'icon',
				'toggle'  => true,
				'options' => array(
					'image' => array(
						'title' => __( 'Image', 'shop-press' ),
						'icon'  => 'eicon-image',
					),
					'icon'  => array(
						'title' => __( 'Icon', 'shop-press' ),
						'icon'  => 'eicon-icon-box',
					),
					'text'  => array(
						'title' => __( 'Text', 'shop-press' ),
						'icon'  => 'eicon-text',
					),
				),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => __( 'Choose Image', 'shop-press' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'placeholder' => array(
						'image',
						'svg',
					),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'large',
				'separator' => 'none',
				'condition' => array(
					'placeholder' => array(
						'image',
					),
				),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'     => esc_html__( 'Icon', 'shop-press' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'placeholder' => array(
						'icon',
					),
				),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'     => esc_html__( 'Text', 'shop-press' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Cart', 'shop-press' ),
				'condition' => array(
					'placeholder' => array(
						'text',
						'icon',
					),
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'open_in',
			array(
				'label'   => esc_html__( 'Opening type', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'dropdown',
				'options' => array(
					'dropdown' => 'Dropdown',
					'drawer'   => 'Drawer',
				),
			)
		);
		$this->end_controls_section();

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper'             => array(
					'label'    => __( 'Wrapper', 'shop-press' ),
					'selector' => '.sp-header-toggle',
					'wrapper'  => '{{WRAPPER}}',
				),
				'content_wrapper'     => array(
					'label'    => __( 'Content Wrapper', 'shop-press' ),
					'selector' => '.sp-header-toggle-content-wrap',
					'wrapper'  => '{{WRAPPER}} .sp-header-toggle',
				),

				'toggle_close_button' => array(
					'label'    => __( 'Toggle Close Button', 'shop-press' ),
					'selector' => '.sp-header-toggle-close',
					'wrapper'  => '{{WRAPPER}} .sp-header-toggle .sp-header-toggle-content-wrap',
				),
			),
		);

		$this->register_group_styler(
			'section_styling_placeholder',
			__( 'Placeholder', 'shop-press' ),
			array(
				'placeholder_wrapper'            => array(
					'label'    => __( 'Wrapper', 'shop-press' ),
					'selector' => '.sp-header-toggle-click',
					'wrapper'  => '{{WRAPPER}}',
				),
				'placeholder_image'              => array(
					'label'    => __( 'Image', 'shop-press' ),
					'selector' => '.sp-header-toggle-click img',
					'wrapper'  => '{{WRAPPER}}',
				),
				'placeholder_icon'               => array(
					'label'    => __( 'Icon', 'shop-press' ),
					'selector' => '.sp-header-toggle-click i',
					'wrapper'  => '{{WRAPPER}}',
				),
				'placeholder_text'               => array(
					'label'    => __( 'Text', 'shop-press' ),
					'selector' => '.sp-header-toggle-click .header-toggle-text',
					'wrapper'  => '{{WRAPPER}}',
				),
				'placeholder_total_items_number' => array(
					'label'    => __( 'Number', 'shop-press' ),
					'selector' => '.sp-cart-items-count',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
		);

		$this->register_group_styler(
			'section_styling_placeholder_active',
			__( 'Active Placeholder', 'shop-press' ),
			array(
				'placeholder_active_wrapper'            => array(
					'label'    => __( 'Wrapper', 'shop-press' ),
					'selector' => '.sp-header-toggle-click.active',
					'wrapper'  => '{{WRAPPER}}',
				),
				'placeholder_active_image'              => array(
					'label'    => __( 'Image', 'shop-press' ),
					'selector' => '.sp-header-toggle-click.active img',
					'wrapper'  => '{{WRAPPER}}',
				),
				'placeholder_active_icon'               => array(
					'label'    => __( 'Icon', 'shop-press' ),
					'selector' => '.sp-header-toggle-click.active i',
					'wrapper'  => '{{WRAPPER}}',
				),
				'placeholder_active_text'               => array(
					'label'    => __( 'Text', 'shop-press' ),
					'selector' => '.sp-header-toggle-click.active .header-toggle-text',
					'wrapper'  => '{{WRAPPER}}',
				),
				'placeholder_active_total_items_number' => array(
					'label'    => __( 'Number', 'shop-press' ),
					'selector' => '.sp-header-toggle-click.active .sp-cart-items-count',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
		);

		$this->register_group_styler(
			'section_styling_cart_title',
			__( 'Cart Title', 'shop-press' ),
			array(
				'cart_title' => array(
					'label'    => __( 'Title', 'shop-press' ),
					'selector' => '.sp-title',
					'wrapper'  => '{{WRAPPER}} .sp-mini-cart',
				),
			)
		);

		$this->register_group_styler(
			'section_styling_cart_items',
			__( 'Cart Items', 'shop-press' ),
			array(
				'cart_items_wrapper'          => array(
					'label'    => __( 'Wrapper', 'shop-press' ),
					'selector' => '.sp-cart-item-pr',
					'wrapper'  => '{{WRAPPER}} .sp-cart-items',
				),
				'cart_items_image'            => array(
					'label'    => __( 'Image', 'shop-press' ),
					'selector' => '.sp-cart-item-img img',
					'wrapper'  => '{{WRAPPER}} .sp-cart-items .sp-cart-item-pr',
				),
				'cart_items_remove_icon_wrap' => array(
					'label'    => __( 'Remove Icon Wrap', 'shop-press' ),
					'selector' => '.sp-cart-item-rm svg circle',
					'wrapper'  => '{{WRAPPER}} .sp-cart-items .sp-cart-item-pr',
				),
				'cart_items_remove_icon'      => array(
					'label'    => __( 'Remove Icon', 'shop-press' ),
					'selector' => '.sp-cart-item-rm svg path',
					'wrapper'  => '{{WRAPPER}} .sp-cart-items .sp-cart-item-pr',
				),
				'cart_items_title'            => array(
					'label'    => __( 'Title', 'shop-press' ),
					'selector' => '{{WRAPPER}} .sp-mmceta .sp-cart-item-pr-title-link, {{WRAPPER}} .sp-mmceta .sp-cart-item-pr-title-link .sp-cart-item-pr-title',
					'wrapper'  => '{{WRAPPER}} .sp-cart-items .sp-cart-item-pr',
				),
				'cart_items_prices'           => array(
					'label'    => __( 'Prices', 'shop-press' ),
					'selector' => '.sp-cart-item-prices span',
					'wrapper'  => '{{WRAPPER}} .sp-cart-items .sp-cart-item-pr .sp-mmceta',
				),
			),
		);

		$this->register_group_styler(
			'section_styling_details',
			__( 'Details and Buttons', 'shop-press' ),
			array(
				'details_wrapper'  => array(
					'label'    => __( 'Wrapper', 'shop-press' ),
					'selector' => '.sp-cart-details',
					'wrapper'  => '{{WRAPPER}}',
				),
				'details_label'    => array(
					'label'    => __( 'Label', 'shop-press' ),
					'selector' => 'p > strong',
					'wrapper'  => '{{WRAPPER}} .sp-cart-details',
				),
				'details_value'    => array(
					'label'    => __( 'Value', 'shop-press' ),
					'selector' => 'p span',
					'wrapper'  => '{{WRAPPER}} .sp-cart-details',
				),
				'view_cart_button' => array(
					'label'    => __( 'View Cart Button', 'shop-press' ),
					'selector' => '.buttons .button:first-of-type',
					'wrapper'  => '{{WRAPPER}} .sp-cart-details',
				),
				'checkout_button'  => array(
					'label'    => __( 'Checkout Button', 'shop-press' ),
					'selector' => '.buttons .button.checkout',
					'wrapper'  => '{{WRAPPER}} .sp-cart-details',
				),
			),
		);

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array(
			'open_in'                => $settings['open_in'],
			'placeholder'            => $settings['placeholder'],
			'image'                  => $settings['image'],
			'image_size'             => $settings['image_size'],
			'image_custom_dimension' => $settings['image_custom_dimension'],
			'text'                   => $settings['text'],
			'icon'                   => $settings['icon'],
		);

		sp_load_builder_template( 'general/mini-cart', $args );
	}
}

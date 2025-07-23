<?php

namespace ShopPress\Elementor\Widgets;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use ShopPress\Elementor\ShopPressWidgets;

defined( 'ABSPATH' ) || exit;

class MiniWishlist extends ShopPressWidgets {

	public function get_name() {
		return 'sp-mini-wishlist';
	}

	public function get_title() {
		return esc_html__( 'Mini Wishlist', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-mini-wishlist';
	}

	public function get_categories() {
		return array( 'sp_general' );
	}

	public function get_script_depends() {
		return array( 'sp-header-toggle', 'sp-wishlist' );
	}

	public function get_style_depends() {

		if ( is_rtl() ) {
			return array( 'sp-header-toggle', 'sp-menu-wishlist', 'sp-header-toggle-rtl', 'sp-menu-wishlist-rtl' );
		} else {
			return array( 'sp-header-toggle', 'sp-menu-wishlist' );
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
					'value'   => 'fas fa-heart',
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
				'default'   => __( 'Wishlist', 'shop-press' ),
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
					'selector' => '.sp-wishlist-items-count',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
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
					'selector' => '.sp-header-toggle-click.active .sp-wishlist-items-count',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'section_styling_wishlist_title',
			__( 'Wishlist Title', 'shop-press' ),
			array(
				'wishlist_title' => array(
					'label'    => __( 'Title', 'shop-press' ),
					'selector' => '.sp-wishlist-title',
					'wrapper'  => '{{WRAPPER}} .sp-mini-wishlist',
				),
			)
		);

		$this->register_group_styler(
			'section_styling_wishlist_items',
			__( 'Wishlist Items', 'shop-press' ),
			array(
				'wishlist_items_wrapper'          => array(
					'label'    => __( 'Wrapper', 'shop-press' ),
					'selector' => '.sp-wishlist-pr',
					'wrapper'  => '{{WRAPPER}}',
				),
				'wishlist_items_image'            => array(
					'label'    => __( 'Image', 'shop-press' ),
					'selector' => '.sp-wishlist-img img',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist-pr',
				),
				'wishlist_items_remove_icon_wrap' => array(
					'label'    => __( 'Remove Icon Wrap', 'shop-press' ),
					'selector' => '.sp-rmf-wishlist svg circle',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist-items .sp-wishlist-pr',
				),
				'wishlist_items_remove_icon'      => array(
					'label'    => __( 'Remove Icon', 'shop-press' ),
					'selector' => '.sp-rmf-wishlist svg path',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist-items .sp-wishlist-pr',
				),
				'wishlist_items_title'            => array(
					'label'    => __( 'Title', 'shop-press' ),
					'selector' => '.sp-wishlist-pr-title',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist-pr',
				),
				'wishlist_items_prices'           => array(
					'label'    => __( 'Prices', 'shop-press' ),
					'selector' => '.sp-wishlist-price span',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist-pr',
				),
			),
		);

		$this->register_group_styler(
			'section_styling_more',
			__( 'Button', 'shop-press' ),
			array(
				'more_wrapper'    => array(
					'label'    => __( 'Wrapper', 'shop-press' ),
					'selector' => '.sp-wishlist-link',
					'wrapper'  => '{{WRAPPER}}',
				),
				'details_buttons' => array(
					'label'    => __( 'Button', 'shop-press' ),
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist-link',
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

		sp_load_builder_template( 'general/mini-wishlist', $args );
	}
}

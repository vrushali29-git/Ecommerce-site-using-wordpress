<?php
namespace ShopPress\Elementor\Widgets;

use Elementor\Repeater;
use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;
use ShopPress\Elementor\ControlsWidgets;

defined( 'ABSPATH' ) || exit;

class Filters extends ShopPressWidgets {

	public function get_name() {
		return 'sp-product-filters';
	}

	public function get_title() {
		return __( 'Product Filters', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-products-filter';
	}

	public function get_categories() {
		return array( 'sp_woo_shop' );
	}

	public function get_script_depends() {
		return array( 'sp-product-filters' );
	}

	public function get_style_depends() {
		if ( is_rtl() ) {
			return array( 'sp-product-filters', 'sp-product-filters-rtl' );
		} else {
			return array( 'sp-product-filters' );
		}
	}

	public function get_attributes() {
		$attrs = array( '0' => __( 'Select a attribute' ) );
		foreach ( wc_get_attribute_taxonomies() as $value ) {
			$attrs[ strtolower( $value->attribute_name ) ] = $value->attribute_label;
		}
		return $attrs;
	}

	public function setup_styling_options() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-filters',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'open_filters',
			__( 'Open Drawer Filters', 'shop-press' ),
			array(
				'wrapper'                  => array(
					'label'    => esc_html__( 'Button Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-open-product-filters-drawer',
					'wrapper'  => '{{WRAPPER}}',
				),
				'text'                     => array(
					'label'    => esc_html__( 'Button Text', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-filters-label',
					'wrapper'  => '{{WRAPPER}} .sp-drawer-click',
				),
				'icon'                     => array(
					'label'    => esc_html__( 'Button Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-icon',
					'wrapper'  => '{{WRAPPER}} .sp-drawer-click',
				),
				'drawer_wrapper'           => array(
					'label'    => esc_html__( 'Drawer Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-drawer-content-wrap',
					'wrapper'  => '{{WRAPPER}}',
				),
				'drawer_title'             => array(
					'label'    => esc_html__( 'Drawer Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-drawer-content-title',
					'wrapper'  => '{{WRAPPER}}',
				),
				'drawer_close_button'      => array(
					'label'    => esc_html__( 'Close Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-drawer-close',
					'wrapper'  => '{{WRAPPER}} .sp-drawer-content-wrap',
				),
				'drawer_close_button_icon' => array(
					'label'    => esc_html__( 'Close Button icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'svg',
					'wrapper'  => '{{WRAPPER}} .sp-drawer-content-wrap .sp-drawer-close',
				),
			),
			array(
				'display_as' => 'drawer',
			)
		);

		$this->register_group_styler(
			'general',
			__( 'General', 'shop-press' ),
			array(
				'general_wrapper' => array(
					'label'    => esc_html__( 'Widgets Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.widget.woocommerce-widget-layered-nav',
					'wrapper'  => '{{WRAPPER}} .sp-product-filters',
				),
				'general_title'   => array(
					'label'    => esc_html__( 'Widgets Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.widgettitle',
					'wrapper'  => '{{WRAPPER}} .sp-product-filters',
				),
				'clear_all_btn'   => array(
					'label'    => esc_html__( 'Clear All Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-reset-filters',
					'wrapper'  => '{{WRAPPER}} .sp-product-filters',
				),
			)
		);

		$this->register_group_styler(
			'attribute',
			__( 'Attribute', 'shop-press' ),
			array(
				'list_wrapper'        => array(
					'label'    => esc_html__( 'Fliter Item Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-widget-layered-nav-list',
					'wrapper'  => '{{WRAPPER}} .woocommerce-widget-layered-nav',
				),
				'list_item'           => array(
					'label'    => esc_html__( 'Filter Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-widget-layered-nav-list__item',
					'wrapper'  => '{{WRAPPER}} .woocommerce-widget-layered-nav .woocommerce-widget-layered-nav-list',
					'separator' => 'after',
				),
				'list_item_attr_name' => array(
					'label'    => esc_html__( 'Attribute Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.attr-name',
					'wrapper'  => '{{WRAPPER}} .woocommerce-widget-layered-nav .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item',
					'separator' => 'after',
				),
				'list_item_color'     => array(
					'label'    => esc_html__( 'Color Type Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-nav-filter.color',
					'wrapper'  => '{{WRAPPER}} .woocommerce-widget-layered-nav .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item',
					'separator' => 'after',
				),
				'list_item_label'     => array(
					'label'    => esc_html__( 'Label Type Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-nav-filter.label',
					'wrapper'  => '{{WRAPPER}} .woocommerce-widget-layered-nav .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item',
					'separator' => 'after',
				),
				'list_item_image'     => array(
					'label'    => esc_html__( 'Image Type Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'img',
					'wrapper'  => '{{WRAPPER}} .woocommerce-widget-layered-nav .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item',
					'separator' => 'after',
				),
				'dropdown_wrapper'    => array(
					'label'    => esc_html__( 'Dropdown Type Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-widget-layered-nav-dropdown',
					'wrapper'  => '{{WRAPPER}} .woocommerce-widget-layered-nav',
				),
				'dropdown_select'     => array(
					'label'    => esc_html__( 'Dropdown Type Select', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'select.dropdown_layered_nav_grade',
					'wrapper'  => '{{WRAPPER}} .woocommerce-widget-layered-nav .woocommerce-widget-layered-nav-dropdown',
				),
				'active_filter_shop'     => array(
					'label'    => esc_html__( 'Active Filter Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'li.chosen a',
					'wrapper'  => '{{WRAPPER}} .widget_layered_nav_filters',
					'separator' => 'before',
				),
			)
		);

		$this->register_group_styler(
			'rating',
			__( 'Rating', 'shop-press' ),
			array(
				'rating_wrapper'      => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.widget_rating_filter',
					'wrapper'  => '{{WRAPPER}}',
				),
				'rating_list_wrapper' => array(
					'label'    => esc_html__( 'List Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'ul',
					'wrapper'  => '{{WRAPPER}} .widget_rating_filter',
				),
				'rating_items'        => array(
					'label'    => esc_html__( 'Items', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.wc-layered-nav-rating',
					'wrapper'  => '{{WRAPPER}} .widget_rating_filter ul',
				),
				'rating_stars'        => array(
					'label'    => esc_html__( 'Stars', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.star-rating:before',
					'wrapper'  => '{{WRAPPER}} .widget_rating_filter ul .wc-layered-nav-rating',
				),
				'rating_full_stars'   => array(
					'label'    => esc_html__( 'Full Stars', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span',
					'wrapper'  => '{{WRAPPER}} .widget_rating_filter ul .wc-layered-nav-rating .star-rating',
				),
			)
		);

		$this->register_group_styler(
			'price_filter',
			__( 'Price Filter', 'shop-press' ),
			array(
				'price_wrapper'           => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.widget_price_filter',
					'wrapper'  => '{{WRAPPER}}',
				),
				'price_inputs'            => array(
					'label'    => esc_html__( 'Inputs', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'input',
					'wrapper'  => '{{WRAPPER}} .widget_price_filter .price_slider_amount',
				),
				'filter_button'            => array(
					'label'    => esc_html__( 'Filter Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'button.button',
					'wrapper'  => '{{WRAPPER}} .widget_price_filter .price_slider_amount',
				),
				'price_slider_wrapper'    => array(
					'label'    => esc_html__( 'Slider Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.price_slider_wrapper',
					'wrapper'  => '{{WRAPPER}} .widget_price_filter',
				),
				'price_slider_horizontal' => array(
					'label'    => esc_html__( 'Slider Horizontal', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.ui-slider-horizontal',
					'wrapper'  => '{{WRAPPER}} .widget_price_filter .price_slider_wrapper',
				),
				'price_slider_ragne'      => array(
					'label'    => esc_html__( 'Slider Range', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.ui-slider-range',
					'wrapper'  => '{{WRAPPER}} .widget_price_filter .price_slider_wrapper',
				),
				'price_slider_handles'    => array(
					'label'    => esc_html__( 'Slider Handles', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.ui-slider-handle',
					'wrapper'  => '{{WRAPPER}} .widget_price_filter .price_slider_wrapper',
				),
			)
		);
	}

	protected function register_controls() {
		$display_conditions = array(
			'relation' => 'or',
			'terms'    => array(
				array(
					'name'     => 'display_as',
					'operator' => '==',
					'value'    => 'drawer',
				),
				array(
					'name'     => 'mobile_drawer',
					'operator' => '==',
					'value'    => 'true',
				),
			),
		);

		$this->start_controls_section(
			'section_display',
			array(
				'label' => __( 'Display', 'shop-press' ),
			)
		);

		$this->add_control(
			'display_as',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => __( 'Display as Drawer', 'shop-press' ),
				'default'      => '',
				'return_value' => 'drawer',
			)
		);

		$this->add_control(
			'mobile_drawer',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => __( 'Convert to drawer on mobile', 'shop-press' ),
				'return_value' => 'true',
				'condition'    => array(
					'display_as!' => 'drawer',
				),
			)
		);

		$this->add_control(
			'button_style',
			array(
				'label'      => __( 'Button style', 'shop-press' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => array(
					'text'      => __( 'Text', 'shop-press' ),
					'icon'      => __( 'Icon', 'shop-press' ),
					'text_icon' => __( 'Text Icon', 'shop-press' ),
					'icon_text' => __( 'Icon Text', 'shop-press' ),
				),
				'default'    => 'text_icon',
				'conditions' => $display_conditions,
			)
		);

		$this->add_control(
			'drawer_label',
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Button label', 'shop-press' ),
				'placeholder' => __( 'Filters', 'shop-press' ),
				'default'     => __( 'Filters', 'shop-press' ),
				'conditions'  => $display_conditions,
			)
		);

		$this->add_control(
			'drawer_icon',
			array(
				'type'       => Controls_Manager::ICONS,
				'label'      => __( 'Button icon', 'shop-press' ),
				'default'    => array(
					'value'   => 'fas fa-filter',
					'library' => 'fa-solid',
				),
				'conditions' => $display_conditions,
			)
		);

		$this->add_control(
			'drawer_position',
			array(
				'type'       => Controls_Manager::SELECT,
				'label'      => __( 'Drawer position', 'shop-press' ),
				'options'    => array(
					'left'  => __( 'Left', 'shop-press' ),
					'right' => __( 'Right', 'shop-press' ),
				),
				'default'    => 'left',
				'conditions' => $display_conditions,
			)
		);

		$this->add_control(
			'drawer_title',
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Drawer title', 'shop-press' ),
				'placeholder' => __( 'Filters', 'shop-press' ),
				'default'     => __( 'Filters', 'shop-press' ),
				'conditions'  => $display_conditions,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filters',
			array(
				'label' => __( 'Filters', 'shop-press' ),
			)
		);

		ControlsWidgets::switcher(
			'show_clear_all',
			__( 'Clear All Button', 'shop-press' ),
			array(),
			'',
			$this
		);

		$filters = new Repeater();

		ControlsWidgets::add_filter_controls(
			$filters,
			$this->get_attributes()
		);

		$this->add_control(
			'filter_repeaters',
			array(
				'label'       => __( 'Filters', 'shop-press' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $filters->get_controls(),
				'title_field' => '{{{ filter_repeater_select }}}',
				'default'     => array(
					array(
						'filter_repeater_select' => 'layered_nav_filters',
					),
					array(
						'filter_repeater_select' => 'layered_nav',
					),
					array(
						'filter_repeater_select' => 'rating_filter',
					),
					array(
						'filter_repeater_select' => 'price_filter',
					),
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
			'filter_repeaters' => $settings['filter_repeaters'],
			'show_clear_all'   => $settings['show_clear_all'],
			'display_as'       => $settings['display_as'] ?? '',
			'mobile_drawer'    => $settings['mobile_drawer'] ?? '',
			'drawer_label'     => $settings['drawer_label'] ?? __( 'Filters', 'shop-press' ),
			'drawer_icon'      => $settings['drawer_icon'] ?? '',
			'drawer_position'  => $settings['drawer_position'] ?? 'left',
			'drawer_title'     => $settings['drawer_title'] ?? __( 'Filters', 'shop-press' ),
			'button_style'     => $settings['button_style'] ?? 'text_icon',
		);

		if ( $this->editor_preview() && ! $this->is_editor() ) {
			sp_load_builder_template( 'shop/shop-filters', $args );
		}

		if ( $this->is_editor() ) {
			require __DIR__ . '/dummy.php';
		}
	}
}

<?php
namespace ShopPress\Elementor\Widgets;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

defined( 'ABSPATH' ) || exit;

class CategoriesGrid extends ShopPressWidgets {

	public function get_name() {
		return 'sp-products-categories';
	}

	public function get_title() {
		return __( 'Categories Grid', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-categories';
	}

	public function get_style_depends() {
		return array( 'sp-categories-grid' );
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

		$this->add_responsive_control(
			'columns',
			array(
				'label'     => __( 'Columns', 'shop-press' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'default'   => 4,
				'selectors' => array(
					'{{WRAPPER}} .sp-product-categories-items' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				),
			)
		);

		$this->add_responsive_control(
			'columns_gap',
			array(
				'label'     => __( 'Columns Gap', 'shop-press' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'default'   => 30,
				'selectors' => array(
					'{{WRAPPER}} .sp-product-categories-items' => 'column-gap: {{VALUE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'     => __( 'Row Gap', 'shop-press' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'default'   => 30,
				'selectors' => array(
					'{{WRAPPER}} .sp-product-categories-items' => 'row-gap: {{VALUE}}px;',
				),
			)
		);

		$this->add_control(
			'number',
			array(
				'label'   => __( 'Number of categories', 'shop-press' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 50,
				'step'    => 1,
				'default' => 9,
			)
		);

		$this->add_control(
			'hr',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'show_name',
			array(
				'label'        => __( 'Show category name', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'shop-press' ),
				'label_off'    => __( 'Hide', 'shop-press' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'cat_new_tab',
			array(
				'label'        => __( 'Open category link in new window', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'shop-press' ),
				'label_off'    => __( 'No', 'shop-press' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'hide_empty_cat',
			array(
				'label'        => __( 'Hide empty categories', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'shop-press' ),
				'label_off'    => __( 'Hide', 'shop-press' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'hide_subcat',
			array(
				'label'        => __( 'Hide subcategories', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'shop-press' ),
				'label_off'    => __( 'Hide', 'shop-press' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'custom_query',
			array(
				'label'        => __( 'Query by your desired categories', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'shop-press' ),
				'label_off'    => __( 'Hide', 'shop-press' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'desired_categories',
			array(
				'label'     => __( 'Select your desired categories', 'shop-press' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => $this->get_categories_for_select_option(),
				'condition' => array(
					'custom_query' => array(
						'yes',
					),
				),
			)
		);

		$this->add_control(
			'order_hr',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'order_by',
			array(
				'label'   => __( 'Order by', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => array(
					'name' => __( 'Name', 'shop-press' ),
					'id'   => __( 'IDs', 'shop-press' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'asc',
				'options' => array(
					'asc'  => __( 'ASC', 'shop-press' ),
					'desc' => __( 'DESC', 'shop-press' ),
				),
			)
		);

		$this->end_controls_section();

		$this->carousel_options();

		$this->setup_styling_options();

		$this->carousel_stylers();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	public function setup_styling_options() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-products-categories',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'categories',
			__( 'Categories', 'shop-press' ),
			array(
				'category_items'  => array(
					'label'    => esc_html__( 'Category Items', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-categories-items',
					'wrapper'  => '{{WRAPPER}} .sp-products-categories',
				),
				'container'       => array(
					'label'    => esc_html__( 'Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-product-cat',
					'wrapper'  => '{{WRAPPER}} .sp-product-categories-items',
				),
				'link'            => array(
					'label'    => esc_html__( 'Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a.sp-products-categories-link',
					'wrapper'  => '{{WRAPPER}} .sp-product-cat',
				),
				'image_container' => array(
					'label'    => esc_html__( 'Category Image Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-categories-image-container',
					'wrapper'  => '{{WRAPPER}} a.sp-products-categories-link',
				),
				'image'           => array(
					'label'          => esc_html__( 'Category Image', 'shop-press' ),
					'type'           => 'styler',
					'selector'       => '{{WRAPPER}} .sp-categories-image-container img, {{WRAPPER}} .sp-categories-image-container svg',
					'hover-selector' => '{{WRAPPER}} .sp-categories-image-container img:hover, {{WRAPPER}} .sp-categories-image-container:hover svg',
				),
				'Title'           => array(
					'label'    => esc_html__( 'Category Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'h5',
					'wrapper'  => '{{WRAPPER}} a',
				),
			)
		);
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array(
			'number'                        => $settings['number'],
			'columns'                       => $settings['columns'] ?? 4,
			'cat_new_tab'                   => $settings['cat_new_tab'],
			'order'                         => $settings['order'],
			'order_by'                      => $settings['order_by'],
			'show_name'                     => $settings['show_name'],
			'hide_subcat'                   => $settings['hide_subcat'],
			'hide_empty_cat'                => $settings['hide_empty_cat'],
			'desired_categories'            => $settings['desired_categories'],
			'carousel'                      => $settings['carousel'],
			'carousel_columns_mobile'       => $settings['carousel_columns_mobile'] ?? 1,
			'carousel_columns_tablet'       => $settings['carousel_columns_tablet'] ?? 2,
			'carousel_columns_tablet_extra' => $settings['carousel_columns_tablet_extra'] ?? 3,
			'carousel_columns_mobile_extra' => $settings['carousel_columns_mobile_extra'] ?? 2,
			'carousel_columns_laptop'       => $settings['carousel_columns_laptop'] ?? 3,
			'carousel_columns'              => $settings['carousel_columns'],
			'carousel_loop'                 => $settings['carousel_loop'],
			'slide_speed'                   => $settings['slide_speed'],
			'show_controls'                 => $settings['show_controls'],
			'show_controls_mobile'          => $settings['show_controls_mobile'] ?? false,
			'show_controls_tablet'          => $settings['show_controls_tablet'] ?? false,
			'show_controls_tablet_extra'    => $settings['show_controls_tablet_extra'] ?? false,
			'show_controls_mobile_extra'    => $settings['show_controls_mobile_extra'] ?? false,
			'show_controls_laptop'          => $settings['show_controls_laptop'] ?? false,
			'autoplay'                      => $settings['autoplay'] ?? false,
			'autoplay_speed'                => $settings['autoplay_speed'] ?? 1500,
			'slider_rows'                   => $settings['slider_rows'],

		);

		if ( $this->is_editor() ) {
			echo '<script>jQuery(document).ready(function($){if (typeof sp_slider_init === "function") {sp_slider_init();}});</script>';
		}

		sp_load_builder_template( 'general/categories-grid', $args );
	}
}

<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class AjaxSearch extends ShopPressWidgets {

	public function get_name() {
		return 'sp-ajax-search';
	}

	public function get_title() {
		return __( 'Ajax Search', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-ajax-search';
	}

	public function get_style_depends() {
		if ( is_rtl() ) {
			return array( 'sp-ajax-search', 'sp-ajax-search-rtl' );
		} else {
			return array( 'sp-ajax-search' );
		}
	}

	public function get_script_depends() {
		return array( 'sp-ajax-search' );
	}

	public function get_categories() {
		return array( 'sp_general' );
	}

	public function setup_styling_options() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '#sp-ajax-search-warp',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'input',
			__( 'Input', 'shop-press' ),
			array(
				'input'        => array(
					'label'    => esc_html__( 'Input', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-ajax-search-input',
					'wrapper'  => '{{WRAPPER}} #sp-ajax-search-warp',
				),
				'input_active' => array(
					'label'    => esc_html__( 'Active Input', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-ajax-search-input:focus-visible',
					'wrapper'  => '{{WRAPPER}} #sp-ajax-search-warp',
				),
				'placeholder'  => array(
					'label'    => esc_html__( 'Placeholder', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-ajax-search-input::placeholder',
					'wrapper'  => '{{WRAPPER}} #sp-ajax-search-warp',
				),
				'category'     => array(
					'label'    => esc_html__( 'Category', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.cat',
					'wrapper'  => '{{WRAPPER}} #sp-ajax-search-warp',
				),
			)
		);

		$this->register_group_styler(
			'search_result',
			__( 'Search Result', 'shop-press' ),
			array(
				'result_wrapper'              => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-ajax-search-result',
					'wrapper'  => '{{WRAPPER}} #sp-ajax-search-warp',
				),
				'result_list'                 => array(
					'label'    => esc_html__( 'List', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'ul.sp-products',
					'wrapper'  => '{{WRAPPER}} #sp-ajax-search-warp .sp-ajax-search-result',
				),
				'result_list_item'            => array(
					'label'    => esc_html__( 'Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'li',
					'wrapper'  => '{{WRAPPER}} #sp-ajax-search-warp .sp-ajax-search-result ul.sp-products',
				),
				'result_product'              => array(
					'label'    => esc_html__( 'Product', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-ajax-s-pr',
					'wrapper'  => '{{WRAPPER}} #sp-ajax-search-warp .sp-ajax-search-result ul.sp-products li',
				),
				'result_product_image'        => array(
					'label'    => esc_html__( 'Product Image', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'img',
					'wrapper'  => '{{WRAPPER}} #sp-ajax-search-warp .sp-ajax-search-result ul.sp-products li .sp-ajax-s-pr .sp-ajax-s-img',
				),
				'result_product_title'        => array(
					'label'    => esc_html__( 'Product Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} #sp-ajax-search-warp .sp-ajax-search-result ul.sp-products li .sp-ajax-s-pr .sp-ajax-meta .sp-ajax-s-pr-title',
				),
				'result_product_normal_price' => array(
					'label'    => esc_html__( 'Product Normal Price', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'del .woocommerce-Price-amount',
					'wrapper'  => '{{WRAPPER}} #sp-ajax-search-warp .sp-ajax-search-result ul.sp-products li .sp-ajax-s-pr .sp-ajax-meta .sp-ajax-s-p-wrap .sp-ajax-s-price',
				),
				'result_product_sale_price'   => array(
					'label'    => esc_html__( 'Product Sale Price', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'ins .woocommerce-Price-amount',
					'wrapper'  => '{{WRAPPER}} #sp-ajax-search-warp .sp-ajax-search-result ul.sp-products li .sp-ajax-s-pr .sp-ajax-meta .sp-ajax-s-p-wrap .sp-ajax-s-price',
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
			'limit',
			array(
				'label'   => __( 'Number of products', 'shop-press' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 9,
			)
		);

		$this->add_control(
			's_placeholder',
			array(
				'label'   => __( 'Search placeholder', 'shop-press' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Search Products', 'shop-press' ),
			)
		);

		$this->add_control(
			'cat',
			array(
				'label'   => __( 'Display categories', 'shop-press' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			)
		);

		$this->add_control(
			'c_text',
			array(
				'label'     => __( 'Category text', 'shop-press' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'All Categories', 'shop-press' ),
				'condition' => array(
					'cat' => 'yes',
				),
			),
		);

		$this->end_controls_section();
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array(
			'cat'           => $settings['cat'],
			'c_text'        => $settings['c_text'],
			's_placeholder' => $settings['s_placeholder'],
			'limit'         => $settings['limit'],
		);

		sp_load_builder_template( 'general/ajax-search', $args );
	}
}

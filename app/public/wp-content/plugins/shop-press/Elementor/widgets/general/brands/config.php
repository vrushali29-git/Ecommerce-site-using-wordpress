<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;
use ShopPress\Modules\VariationSwatches\Frontend;

class BrandsAttribute extends ShopPressWidgets {

	public function get_name() {
		return 'sp-brands-attribute';
	}

	public function get_title() {
		return __( 'Brand Attributes', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-brands';
	}

	public function get_style_depends() {
		if ( is_rtl() ) {
			return array( 'sp-brands', 'sp-brands-rtl' );
		} else {
			return array( 'sp-brands' );
		}
	}

	public function get_categories() {
		return array( 'sp_general' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'att_brand_wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-brands.sp-brands-attrs.sp-brands-grid',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);
		$this->register_group_styler(
			'att_brand',
			__( 'Brand', 'shop-press' ),
			array(
				'att_brand_items_wrapper' => array(
					'label'    => esc_html__( 'Items Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-brands-items',
					'wrapper'  => '{{WRAPPER}} .sp-brands.sp-brands-attrs.sp-brands-grid',
				),
				'att_brand_item' => array(
					'label'    => esc_html__( 'Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-brand-item',
					'wrapper'  => '{{WRAPPER}} .sp-brands.sp-brands-attrs.sp-brands-grid .sp-brands-items',
				),
				'att_brand_link' => array(
					'label'    => esc_html__( 'Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} .sp-brands.sp-brands-attrs.sp-brands-grid .sp-brands-items .sp-brand-item',
				),
				'att_brand_image_wrapper' => array(
					'label'    => esc_html__( 'Image Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-brand-img-wrapper',
					'wrapper'  => '{{WRAPPER}} .sp-brands.sp-brands-attrs.sp-brands-grid .sp-brands-items .sp-brand-item a',
				),
				'att_brand_image' => array(
					'label'    => esc_html__( 'Image', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'img',
					'wrapper'  => '{{WRAPPER}} .sp-brands.sp-brands-attrs.sp-brands-grid .sp-brands-items .sp-brand-item a .sp-brand-img-wrapper',
				),
				'att_brand_title' => array(
					'label'    => esc_html__( 'Brand Name', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.brand-name',
					'wrapper'  => '{{WRAPPER}} .sp-brands.sp-brands-attrs.sp-brands-grid .sp-brands-items .sp-brand-item a',
				),
			)
		);
	}

	protected function register_controls() {
		$brands     = array();
		$attributes = Frontend::get_brand_attributes( false );
		$terms      = $attributes['terms'] ?? array();

		foreach ( $terms as $key => $term ) {
			$brands[ $term->term_id ] = $term->name;
		}

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'General', 'shop-press' ),
			)
		);

		$this->add_control(
			'display_name',
			array(
				'label'        => __( 'Brand Name', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'false',
				'return_value' => 'true',
			)
		);

		$this->add_control(
			'display_logo',
			array(
				'label'        => __( 'Brand Logo', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'true',
				'return_value' => 'true',
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'        => __( 'Hide Empty', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'true',
				'return_value' => 'true',
			)
		);

		$this->add_control(
			'brands',
			array(
				'label'    => __( 'Brands', 'shop-press' ),
				'type'     => Controls_Manager::SELECT2,
				'default'  => '',
				'multiple' => true,
				'options'  => $brands,
			)
		);

		$this->end_controls_section();
		$this->carousel_options();
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		if ( $this->is_editor() ) {
			echo '<script>jQuery(document).ready(function($){if (typeof sp_slider_init === "function") {sp_slider_init();}});</script>';
		}

		$args = array(
			'display_name'     => $settings['display_name'],
			'display_logo'     => $settings['display_logo'],
			'hide_empty'       => $settings['hide_empty'],
			'brands'           => $settings['brands'],
			'carousel'         => $settings['carousel'],
			'carousel_columns' => $settings['carousel_columns'],
			'slide_speed'      => $settings['slide_speed'],
			'show_controls'    => $settings['show_controls'],
			'autoplay'         => $settings['autoplay'],
			'autoplay_speed'   => $settings['autoplay_speed'],
			'carousel_loop'    => $settings['carousel_loop'],
			'slider_rows'      => $settings['slider_rows'],
		);

		sp_load_builder_template( 'general/brands', $args );
	}
}

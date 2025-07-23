<?php
/**
 * Widgets Base.
 *
 * @package ShopPress
 */

namespace ShopPress\Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

abstract class ShopPressWidgets extends ShopPressStyler {
	public function carousel_options() {

		$this->start_controls_section(
			'carousel_options',
			array(
				'label' => __( 'Carousel', 'shop-press' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'carousel',
			array(
				'label'        => __( 'Carousel', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'carousel_columns',
			array(
				'label'          => __( 'Carousel Items', 'shop-press' ),
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 12,
				'step'           => 1,
				'default'        => 4,
				'tablet_default' => 3,
				'mobile_default' => 2,
				'condition'      => array(
					'carousel' => 'true',
				),
			)
		);

		$this->add_control(
			'slide_speed',
			array(
				'label'     => __( 'Slide Speed (ms)', 'shop-press' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 500,
				'min'       => 100,
				'max'       => 10000,
				'step'      => 100,
				'condition' => array(
					'carousel' => 'true',
				),
			)
		);

		$this->add_control(
			'show_controls',
			array(
				'label'        => __( 'Arrows', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'shop-press' ),
				'label_off'    => __( 'Hide', 'shop-press' ),
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => array(
					'carousel' => 'true',
				),
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => __( 'Autoplay', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'shop-press' ),
				'label_off'    => __( 'No', 'shop-press' ),
				'return_value' => 'true',
				'default'      => 'false',
				'condition'    => array(
					'carousel' => 'true',
				),
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => __( 'Autoplay Speed (ms)', 'shop-press' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3000,
				'min'       => 1000,
				'max'       => 10000,
				'step'      => 100,
				'condition' => array(
					'carousel' => 'true',
					'autoplay' => 'true',
				),
			)
		);

		$this->add_control(
			'carousel_loop',
			array(
				'label'        => __( 'Loop', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => array(
					'carousel' => 'true',
				),
			)
		);

		$this->add_control(
			'slider_rows',
			array(
				'label'     => __( 'Rows', 'shop-press' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 6,
				'step'      => 1,
				'condition' => array(
					'carousel' => 'true',
				),
			)
		);

		$this->end_controls_section();
	}

	public function carousel_stylers() {
		$this->register_group_styler(
			'owl_arrow',
			__( 'Carousel Arrows', 'shop-press' ),
			array(
				'prev_arrow' => array(
					'label'    => esc_html__( 'Prev Arrow', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'button.slick-prev',
					'wrapper'  => '{{WRAPPER}} .sp-slider-style',
				),
				'next_arrow' => array(
					'label'    => esc_html__( 'Next Arrow', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'button.slick-next',
					'wrapper'  => '{{WRAPPER}} .sp-slider-style',
				),
			),
			array(
				'carousel' => 'true' ?? array(),
			)
		);
	}

	public function custom_heading_stylers() {
		$this->register_group_styler(
			'reviews_custom_heading',
			__( 'Heading', 'shop-press' ),
			array(
				'reviews_custom_heading' => array(
					'label'    => esc_html__( 'Custom Heading', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-products-heading',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
		);
	}

	/**
	 * Returns Woo categories.
	 *
	 * @since 1.4.7
	 *
	 * @return array
	 */
	public function get_categories_for_select_option() {
		$categories = get_terms( 'product_cat' );

		$cats     = array();
		$cat_slug = array();
		$cat_name = array();
		if ( $categories ) {
			foreach ( $categories as $cat ) {
				$cat_slug[] = $cat->slug;
				$cat_name[] = $cat->name;
			}
		}

		if ( $cat_slug && $cat_name ) {
			$cats = array_combine( $cat_name, $cat_slug );
		}

		return $cats;
	}

	/**
	 * Returns all image sizes.
	 *
	 * @since 1.3.5
	 *
	 * @return array
	 */
	public static function get_all_image_sizes() {
		global $_wp_additional_image_sizes;

		$default_image_sizes = array( 'thumbnail', 'medium', 'medium_large', 'large' );

		$image_sizes = array();

		foreach ( $default_image_sizes as $size ) {
			$width                = (int) get_option( $size . '_size_w' );
			$height               = (int) get_option( $size . '_size_h' );
			$crop                 = (bool) get_option( $size . '_crop' );
			$image_sizes[ $size ] = array(
				'width'  => $width,
				'height' => $height,
				'crop'   => $crop,
			);
		}

		if ( $_wp_additional_image_sizes ) {
			$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
		}

		foreach ( $image_sizes as $size => $image_size ) {
			$size_text = "{$image_size['width']}x{$image_size['height']}";
			$title     = ucfirst( str_replace( '_', ' ', $size ) );
			if ( $size_text !== $title ) {
				$title .= ' ' . $size_text;
			}

			$image_sizes[ $size ]['title'] = $title;
		}

		return $image_sizes;
	}
}

<?php

/**
 * Shoppress Compatibility Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.3.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

if ( ! class_exists( 'Kata_Plus_Shoppress_Compatibility' ) ) {

	class Kata_Plus_Shoppress_Compatibility extends Kata_Plus_Compatibility {

		/**
		 * Instance of this class.
		 *
		 * @since   1.3.0
		 * @access  public
		 * @var     Kata_Plus_Shoppress_Compatibility
		 */
		public static $instance;

		/**
		 * widget settings.
		 *
		 * @since   1.3.0
		 * @access  public
		 * @var     Kata_Plus_Shoppress_Compatibility
		 */
		public $widget_settings = array();

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since   1.3.0
		 * @return  object
		 */
		public static function get_instance() {

			if ( self::$instance === null ) {

				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since   1.3.0
		 */
		public function __construct() {

			$this->definitions();
			$this->actions();
		}

		/**
		 * Add actions
		 *
		 * @since   1.3.0
		 */
		public function actions() {
			add_action( 'elementor/element/sp-products/section_content/before_section_end', array( $this, 'responsive_contorol_for_shop_products' ), 10, 2 );
			add_action( 'elementor/element/sp-cross-sell-products/section_content/before_section_end', array( $this, 'responsive_contorol_for_shop_products' ), 10, 2 );
			add_action( 'elementor/element/sp-product-collection/section_content/before_section_end', array( $this, 'responsive_contorol_for_shop_products' ), 10, 2 );
			add_action( 'elementor/element/sp-related-products/section_content/before_section_end', array( $this, 'responsive_contorol_for_shop_products' ), 10, 2 );
			add_action( 'elementor/element/sp-up-sell-products/section_content/before_section_end', array( $this, 'responsive_contorol_for_shop_products' ), 10, 2 );
			add_action( 'elementor/element/sp-review/wrapper_styler_section/after_section_end', array( $this, 'summary_stylers_for_review_widget' ), 10, 2 );
			add_action( 'elementor/element/sp-tabs/reviews_styler_section/before_section_start', array( $this, 'summary_stylers_for_review_widget' ), 10, 2 );
			add_action( 'elementor/element/sp-cart-table/coupon_styler_section/before_section_end', array( $this, 'cart_table_coupon_styler_inject' ), 10, 2 );
		}


		/**
		 * Adds responsive controls for the shop products.
		 *
		 * @param object $element The element object.
		 * @param array $args The arguments.
		 * @return void
		 *
		 * @since 1.3.0
		 */
		public function responsive_contorol_for_shop_products( $element, $args ) {

			$condition = $element->get_name() == 'sp-products' ?
			array(
				'label'        => esc_html__( 'Product in row', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'kata-plus' ),
				'label_off'    => esc_html__( 'Off', 'kata-plus' ),
				'return_value' => 'yes',
				'default'      => '',
			) : array(
				'label'        => esc_html__( 'Product in row', 'kata-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'kata-plus' ),
				'label_off'    => esc_html__( 'Off', 'kata-plus' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'carousel!' => 'true',
				),
			);

			$element->add_control(
				'enable_product_columns',
				$condition
			);
			$element->add_responsive_control(
				'product_columns',
				[
					'label'      => esc_html__( 'Product in row', 'kata-plus' ),
					'type'       => Controls_Manager::NUMBER,
					'min'        => 1,
					'max'        => 10,
					'step'       => 1,
					'default'    => 4,
					'selectors' => array(
						'{{WRAPPER}} .sp-products-loop-wrapper ul.products' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
					),
					'condition' => array(
						'enable_product_columns' => 'yes',
					),
				]
			);
			$element->add_responsive_control(
				'columns_gap',
				array(
					'label'   => __( 'Columns Gap', 'kata-plus' ),
					'type'       => Controls_Manager::NUMBER,
					'min'        => 0,
					'max'        => 100,
					'step'       => 1,
					'default'    => 30,
					'selectors' => array(
						'{{WRAPPER}} .products' => 'column-gap: {{VALUE}}px;',
					),
					'condition' => array(
						'enable_product_columns' => 'yes',
					),
				)
			);
			$element->add_responsive_control(
				'row_gap',
				array(
					'label'   => __( 'Row Gap', 'kata-plus' ),
					'type'       => Controls_Manager::NUMBER,
					'min'        => 0,
					'max'        => 100,
					'step'       => 1,
					'default'    => 30,
					'selectors' => array(
						'{{WRAPPER}} .products' => 'row-gap: {{VALUE}}px;',
					),
					'condition' => array(
						'enable_product_columns' => 'yes',
					),
				)
			);
		}

		/**
		 * Adds styling controls for the review widget summary elements.
		 *
		 * @param object $element The element object.
		 * @param array  $args    Additional arguments for the function.
		 * @return void
		 *
		 * @since 1.4.2
		 */
		public function summary_stylers_for_review_widget( $element, $args ) {
			$element->start_controls_section(
				'summary_styler_section',
				array(
					'label'     => __( 'Summary', 'kata-plus' ),
					'tab'  		=> Controls_Manager::TAB_STYLE,
				)
			);

			$element->add_control(
				'review_summary',
				array(
					'label'    => esc_html__( 'Comments Summary Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.kata-review-summery',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);

			$element->add_control(
				'overall_rating_wrap',
				array(
					'label'    => esc_html__( 'Overall Rating Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.overall-rating-wrap',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);

			$element->add_control(
				'average_rating',
				array(
					'label'    => esc_html__( 'Average Rating', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => 'div.average-rating',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .overall-rating-wrap',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);

			$element->add_control(
				'avg_stars_wrap',
				array(
					'label'    => esc_html__( 'Average Stars Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.average-rating-stars',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .overall-rating-wrap',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);

			$element->add_control(
				'avg_stars_empty',
				array(
					'label'    => esc_html__( 'Average Stars Empty Stars', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.star-rating:before',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .overall-rating-wrap .average-rating-stars',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);

			$element->add_control(
				'avg_star_full',
				array(
					'label'    => esc_html__( 'Average Star Full Stars', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => 'span',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .overall-rating-wrap .average-rating-stars .star-rating',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);

			$element->add_control(
				'reviews_count',
				array(
					'label'    => esc_html__( 'Reviews Count', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.reviews-count',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .overall-rating-wrap',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);

			$element->add_control(
				'comment_write_wrap',
				array(
					'label'    => esc_html__( 'Review Button Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.write-a-review',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .overall-rating-wrap',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);

			$element->add_control(
				'comment_write',
				array(
					'label'    => esc_html__( 'Review Button', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.button',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .overall-rating-wrap .write-a-review',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);

			$element->add_control(
				'rating_summary_wrap',
				array(
					'label'    => esc_html__( 'Rating Summary Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.rating-summary-wrap',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);

			$element->add_control(
				'rating_summary_item',
				array(
					'label'    => esc_html__( 'Rating Summary Item', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => 'div.item',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .rating-summary-wrap',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);

			$element->add_control(
				'rating_summary_item_stars_wrapper',
				array(
					'label'    => esc_html__( 'Rating Summary Item Stars Wrapper', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.star-rating',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .rating-summary-wrap div.item',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);

			$element->add_control(
				'rating_summary_item_empty',
				array(
					'label'    => esc_html__( 'Rating Summary Item Empty Stars', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.star-rating:before',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .rating-summary-wrap div.item',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);

			$element->add_control(
				'rating_summary_item_full',
				array(
					'label'    => esc_html__( 'Rating Summary Item Full Stars', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => 'span',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .rating-summary-wrap div.item .star-rating',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);

			$element->add_control(
				'item_rate_count',
				array(
					'label'    => esc_html__( 'Item Rate Count', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.rate-count',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .rating-summary-wrap div.item',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);

			$element->end_controls_section();
		}

		/**
		 * Adds styling controls for the review widget summary elements.
		 *
		 * @param object $element The element object.
		 * @param array  $args    Additional arguments for the function.
		 * @return void
		 *
		 * @since 1.4.2
		 */
		public function cart_table_coupon_styler_inject( $element, $args ) {
			$element->add_control(
				'styler_btn_coupon_icon',
				array(
					'label'    => esc_html__( 'Icon', 'kata-plus' ),
					'type'     => 'styler',
					'selector' => '.coupon-icon',
					'wrapper'  => '{{WRAPPER}}',
					'isSVG'    => true,
					'isInput'  => false,
				),
			);
		}

	}
	Kata_Plus_Shoppress_Compatibility::get_instance();
}

<?php
/**
 * Widgets Controls Base.
 *
 * @package ShopPress
 */

namespace ShopPress\Elementor;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ShopPress\Templates;

class ControlsWidgets {
	/**
	 * Instance of this class.
	 *
	 * @since   1.0.0
	 */
	public static $instance;

	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @since   1.0.0
	 *
	 * @return  object
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function select( $id, $label, $options, $default, &$class ) {

		$class->add_control(
			$id,
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => $label,
				'default'   => $default,
				'options'   => $options['options'] ?? array(),
				'condition' => $options['condition'] ?? '',
			)
		);
	}

	public static function select2( $id, $label, $options, $default, &$class, $multiple = false, $description = '', $other_options = array() ) {

		$class->add_control(
			$id,
			array(
				'type'        => Controls_Manager::SELECT2,
				'label'       => $label,
				'default'     => $default,
				'options'     => $options['options'] ?? array(),
				'multiple'    => $multiple,
				'description' => $description,
				'condition'   => $options['condition'] ?? '',
			)
		);
	}

	public static function number( $id, $label, $options, $default, &$class ) {

		$class->add_control(
			$id,
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => $label,
				'default'   => $default,
				'min'       => $options['min'] ?? '',
				'max'       => $options['max'] ?? '',
				'step'      => $options['step'] ?? '',
				'condition' => $options['condition'] ?? '',
			)
		);
	}

	public static function text( $id, $label, $options, $default, &$class ) {

		$class->add_control(
			$id,
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => $label,
				'default'     => $default,
				'placeholder' => $options['placeholder'] ?? '',
				'condition'   => $options['condition'] ?? '',
			)
		);
	}

	public static function switcher( $id, $label, $options, $default, &$class ) {

		$class->add_control(
			$id,
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => $label,
				'default'      => $default,
				'label_on'     => $options['label_on'] ?? __( 'Show', 'shop-press' ),
				'label_off'    => $options['label_off'] ?? __( 'Hide', 'shop-press' ),
				'return_value' => $options['return_value'] ?? 'yes',
				'selectors'    => $options['selectors'] ?? '',
				'condition'    => $options['condition'] ?? '',
			)
		);
	}

	public static function icons( $id, $label, $options, $default, &$class ) {

		$class->add_control(
			$id,
			array(
				'type'      => Controls_Manager::ICONS,
				'label'     => $label,
				'default'   => $default,
				'condition' => $options['condition'] ?? '',
			)
		);
	}

	/**
	 * Add Query Controls
	 *
	 * @param ShopPressWidgets $class
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function add_query_controls( &$class ) {
		$templates      = Templates\Utils::get_loop_builder_templates();
		$product_tags   = Templates\Utils::get_terms_for_select( 'product_tag' );
		$product_cats   = Templates\Utils::get_terms_for_select( 'product_cat' );
		$product_brands = sp_is_module_active( 'brands' ) ? Templates\Utils::get_terms_for_select( 'shoppress_brand' ) : array();

		if ( 'sp-product-collection' === $class->get_name() ) {

			$collections = array(
				'sp-recent-products'       => __( 'Recent Products', 'shop-press' ),
				'sp-featured-products'     => __( 'Featured Products', 'shop-press' ),
				'sp-best-selling-products' => __( 'Best Selling Products', 'shop-press' ),
				'sp-top-rated-products'    => __( 'Top Rated Products', 'shop-press' ),
				'sp-sales-products'        => __( 'Sale Products', 'shop-press' ),
			);
			$collections = apply_filters( 'shoppress/product-loop/collections', $collections, $class );

			self::select2(
				'product_collection',
				__( 'Product Collection', 'shop-press' ),
				array(
					'options' => $collections,
				),
				'sp-recent-products',
				$class,
				false
			);
		}

		$product_loop_status = sp_is_template_active( 'products_loop' );
		static::select2(
			'template_id',
			__( 'Template', 'shop-press' ),
			array(
				'options' => $templates,
			),
			0,
			$class,
			false,
			! $product_loop_status ? sprintf( esc_html__( 'To display the custom template in the front, you need to activate the %s component', 'shop-press' ), '<a href="' . esc_url( admin_url( 'admin.php?page=shoppress&sub=templates' ) ) . '" target="_blank">' . __( 'products loop', 'shop-press' ) . '</a>' ) : ''
		);

		static::number(
			'limit',
			__( 'Limit', 'shop-press' ),
			array(
				'min'  => 1,
				'max'  => 200,
				'step' => 1,
			),
			'',
			$class
		);

		static::number(
			'columns',
			__( 'Columns', 'shop-press' ),
			array(),
			'',
			$class
		);

		// static::switcher(
		// 'paginate',
		// __( 'Pagination', 'shop-press' ),
		// array(),
		// '',
		// $class,
		// array(
		// 'condition' => array(
		// 'display_as_slider!' => 'yes',
		// ),
		// )
		// );

		static::switcher(
			'infinite_scroll',
			__( 'Infinite scroll', 'shop-press' ),
			array(
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-pagination' => 'display:none;',
				),
				'condition' => array(
					'paginate'          => 'yes',
					'load_more_button!' => 'yes',
				),
			),
			'',
			$class
		);

		static::switcher(
			'load_more_button',
			__( 'Load More Button', 'shop-press' ),
			array(
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-pagination' => 'display:none;',
					'{{WRAPPER}} .sp-loadmore-wrapper'    => 'display:block;',
				),
				'condition' => array(
					'paginate'         => 'yes',
					'infinite_scroll!' => 'yes',
				),
			),
			'',
			$class
		);

		static::text(
			'loadmore_text',
			__( 'Load More Button Text', 'shop-press' ),
			array(
				'condition' => array(
					'paginate'         => 'yes',
					'load_more_button' => 'yes',
				),
			),
			__( 'Load More', 'shop-press' ),
			$class
		);

		static::switcher(
			'result_count',
			__( 'Result Count', 'shop-press' ),
			array(
				'condition' => array(
					'paginate' => 'yes',
				),
			),
			'',
			$class
		);

		static::switcher(
			'catalog_ordering',
			__( 'Order By Form', 'shop-press' ),
			array(
				'condition' => array(
					'paginate' => 'yes',
				),
			),
			'',
			$class
		);

		static::select(
			'orderby',
			__( 'Order By', 'shop-press' ),
			array(
				'options' => array(
					'id'         => __( 'ID', 'shop-press' ),
					'title'      => __( 'Title', 'shop-press' ),
					'date'       => __( 'Date', 'shop-press' ),
					'rand'       => __( 'Random', 'shop-press' ),
					'price'      => __( 'Price', 'shop-press' ),
					'popularity' => __( 'Popularity', 'shop-press' ),
					'rating'     => __( 'Rating', 'shop-press' ),
				),
			),
			'',
			$class
		);

		static::select(
			'order',
			__( 'Order', 'shop-press' ),
			array(
				'options' => array(
					'ASC'  => __( 'ASC', 'shop-press' ),
					'DESC' => __( 'DESC', 'shop-press' ),
				),
			),
			'',
			$class
		);

		static::text(
			'ids',
			__( 'Product IDs', 'shop-press' ),
			array(
				'placeholder' => __( 'ex: 1,2,3,4', 'shop-press' ),
				'condition'   => array(
					'product_collection' => array(
						'sp-products',
						'sp-recent-products',
						'sp-featured-products',
						'sp-best-selling-products',
						'sp-top-rated-products',
						'sp-sales-products',
					),
				),
			),
			'',
			$class
		);

		static::text(
			'skus',
			__( 'SKUs', 'shop-press' ),
			array(
				'placeholder' => __( 'ex: 1,2,3,4', 'shop-press' ),
				'condition'   => array(
					'product_collection' => array(
						'sp-products',
						'sp-recent-products',
						'sp-featured-products',
						'sp-best-selling-products',
						'sp-top-rated-products',
						'sp-sales-products',
					),
				),
			),
			'',
			$class
		);

		static::select(
			'cat_operator',
			__( 'Category Operator', 'shop-press' ),
			array(
				'options'   => array(
					'IN'     => __( 'Include', 'shop-press' ),
					'NOT IN' => __( 'Exclude', 'shop-press' ),
				),
				'condition' => array(
					'product_collection' => array(
						'sp-products',
						'sp-recent-products',
						'sp-featured-products',
						'sp-best-selling-products',
						'sp-top-rated-products',
						'sp-sales-products',
					),
				),
			),
			'IN',
			$class
		);

		static::select2(
			'category',
			__( 'Categories', 'shop-press' ),
			array(
				'options'   => $product_cats,
				'condition' => array(
					'product_collection' => array(
						'sp-products',
						'sp-recent-products',
						'sp-featured-products',
						'sp-best-selling-products',
						'sp-top-rated-products',
						'sp-sales-products',
					),
				),
			),
			array(),
			$class,
			true
		);

		static::select(
			'tag_operator',
			__( 'Tag Operator', 'shop-press' ),
			array(
				'options'   => array(
					'IN'     => __( 'Include', 'shop-press' ),
					'NOT IN' => __( 'Exclude', 'shop-press' ),
				),
				'condition' => array(
					'product_collection' => array(
						'sp-products',
						'sp-recent-products',
						'sp-featured-products',
						'sp-best-selling-products',
						'sp-top-rated-products',
						'sp-sales-products',
					),
				),
			),
			'IN',
			$class
		);

		static::select2(
			'tag',
			__( 'Tags', 'shop-press' ),
			array(
				'options'   => $product_tags,
				'condition' => array(
					'product_collection' => array(
						'sp-products',
						'sp-recent-products',
						'sp-featured-products',
						'sp-best-selling-products',
						'sp-top-rated-products',
						'sp-sales-products',
					),
				),
			),
			array(),
			$class,
			true
		);

		if ( sp_is_module_active( 'brands' ) ) {

			static::select(
				'brand_operator',
				__( 'Brand Operator', 'shop-press' ),
				array(
					'options'   => array(
						'IN'     => __( 'Include', 'shop-press' ),
						'NOT IN' => __( 'Exclude', 'shop-press' ),
					),
					'condition' => array(
						'product_collection' => array(
							'sp-products',
							'sp-recent-products',
							'sp-featured-products',
							'sp-best-selling-products',
							'sp-top-rated-products',
							'sp-sales-products',
						),
					),
				),
				'IN',
				$class
			);

			static::select2(
				'brand',
				__( 'Brands', 'shop-press' ),
				array(
					'options'   => $product_brands,
					'condition' => array(
						'product_collection' => array(
							'sp-products',
							'sp-recent-products',
							'sp-featured-products',
							'sp-best-selling-products',
							'sp-top-rated-products',
							'sp-sales-products',
						),
					),
				),
				array(),
				$class,
				true
			);
		}

		if ( 'sp-related-products' === $class->get_name() ) {

			static::switcher(
				'custom_heading',
				__( 'Custom Heading', 'shop-press' ),
				array(
					'return_value' => 'true',
				),
				'',
				$class,
			);

			static::text(
				'products_heading',
				__( 'Heading', 'shop-press' ),
				array(
					'condition' => array(
						'custom_heading' => 'true',
					),
				),
				'',
				$class
			);

			static::select(
				'heading_tag',
				__( 'Heading HTML Tag', 'shop-press' ),
				array(
					'options'   => array(
						'h1'   => __( 'h1', 'shop-press' ),
						'h2'   => __( 'h2', 'shop-press' ),
						'h3'   => __( 'h3', 'shop-press' ),
						'h4'   => __( 'h4', 'shop-press' ),
						'h5'   => __( 'h5', 'shop-press' ),
						'h6'   => __( 'h6', 'shop-press' ),
						'div'  => __( 'div', 'shop-press' ),
						'span' => __( 'span', 'shop-press' ),
					),
					'condition' => array(
						'custom_heading' => 'true',
					),
				),
				'h4',
				$class
			);
		}
	}

	/**
	 * Add Filter Controls
	 *
	 * @param ShopPressWidgets $class
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function add_filter_controls( &$class, $product_attributes = array() ) {

		$filter_by = apply_filters(
			'shoppress_filter_by_options',
			array(
				'layered_nav_filters' => __( 'Active Filters', 'shop-press' ),
				'layered_nav'         => __( 'Filter by Attribute', 'shop-press' ),
				'rating_filter'       => __( 'Filter by Rating', 'shop-press' ),
				'price_filter'        => __( 'Filter by Price', 'shop-press' ),
				// 'stock_filter'        => __( 'Filter by Stock', 'shop-press' ),
			)
		);

		static::select(
			'filter_repeater_select',
			__( 'Template', 'shop-press' ),
			array(
				'options' => $filter_by,
			),
			'cats',
			$class
		);

		static::text(
			'layered_nav_title',
			__( 'Title', 'shop-press' ),
			array(
				'condition' => array(
					'filter_repeater_select' => 'layered_nav',
				),
			),
			'Attribute',
			$class
		);

		static::select(
			'attribute',
			__( 'Attribute', 'shop-press' ),
			array(
				'options'   => $product_attributes,
				'condition' => array(
					'filter_repeater_select' => 'layered_nav',
				),
			),
			'0',
			$class
		);

		static::select(
			'attribute_display_type',
			__( 'Display type', 'shop-press' ),
			array(
				'options'   => array(
					'dropdown' => __( 'Dropdown', 'shop-press' ),
					'list'     => __( 'List', 'shop-press' ),
				),
				'condition' => array(
					'filter_repeater_select' => 'layered_nav',
				),
			),
			'list',
			$class
		);

		static::text(
			'price_filter_title',
			__( 'Title', 'shop-press' ),
			array(
				'condition' => array(
					'filter_repeater_select' => 'price_filter',
				),
			),
			'Price',
			$class
		);

		static::text(
			'rating_filter_title',
			__( 'Title', 'shop-press' ),
			array(
				'condition' => array(
					'filter_repeater_select' => 'rating_filter',
				),
			),
			'Rating',
			$class
		);

		static::text(
			'layered_nav_filters_title',
			__( 'Title', 'shop-press' ),
			array(
				'condition' => array(
					'filter_repeater_select' => 'layered_nav_filters',
				),
			),
			'Active Filters',
			$class
		);
	}
}

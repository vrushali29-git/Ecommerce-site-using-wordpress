<?php
/**
 * Product Collection.
 *
 * @package ShopPress
 */

namespace ShopPress\Templates;

defined( 'ABSPATH' ) || exit;

use WC_Shortcode_Products;
use ShopPress\Modules\FlashSalesCountdown;

class ProductCollection {
	/**
	 * Loop name.
	 *
	 * @since 1.4.0
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Block|Widget attributes.
	 *
	 * @since 1.4.0
	 *
	 * @var array
	 */
	protected $attrs;

	/**
	 * Constructor.
	 *
	 * @since 1.4.0
	 *
	 * @param string $name          Name of the loop.
	 * @param array  $attributes    Attributes from block or widget.
	 */
	public function __construct( $name, $attributes ) {
		$this->name  = $name;
		$this->attrs = $attributes;

		if ( $this->is_carousel_enabled() ) {
			wp_enqueue_style( 'slick' );
			wp_enqueue_script( 'slick' );
		}

		add_filter( 'woocommerce_shortcode_products_query', array( $this, 'prepare_query_args' ), 10, 3 );
	}

	/**
	 * Prepare Query Args.
	 *
	 * @param array  $query_args
	 * @param array  $attributes
	 * @param string $type
	 *
	 * @since 1.4.0
	 *
	 * @return array
	 */
	public function prepare_query_args( $query_args, $attributes, $type ) {

		if ( false === strpos( $type, 'sp-' ) ) {

			return $query_args;
		}

		$query_args['sp_products_loop'] = $type;

		$brands = $this->attrs['brand'] ?? false;
		if ( $brands ) {

			$query_args['tax_query']['brands'] = array(
				'taxonomy' => 'shoppress_brand',
				'terms'    => $brands,
				'compare'  => $this->attrs['brand_operator'],
			);
		}

		switch ( $type ) {

			case 'sp-best-selling-products':
				$query_args['meta_key'] = 'total_sales';

				$query_args['orderby'] = array(
					'total_sales' => 'DESC',
				);

				break;
			case 'sp-cross-sell-products':
				$p_id       = isset( $query_args['p'] ) ? $query_args['p'] : '';
				$q_post__in = isset( $query_args['post__in'] ) ? $query_args['post__in'] : '';
				$q_post__in = empty( $post__in ) && ! empty( $p_id ) ? array( $p_id ) : $q_post__in;

				if ( empty( $q_post__in ) ) {

					if ( is_checkout() || is_cart() ) {

						$post__in = WC()->cart->get_cross_sells();
					} else {

						global $product;
						$cross_sell_products = is_a( $product, '\WC_Product' ) ? $product->get_cross_sell_ids() : array();
						$post__in            = $cross_sell_products;
					}
				} else {

					$post__in = $q_post__in;
				}
				$query_args['p']        = '';
				$query_args['post__in'] = $post__in;

				break;
			case 'sp-featured-products':
				$query_args['tax_query']['featured'] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
					'operator' => 'IN',
				);

				break;
			case 'sp-sales-products':
				$p_id           = isset( $query_args['p'] ) ? $query_args['p'] : '';
				$q_post__in     = isset( $query_args['post__in'] ) ? $query_args['post__in'] : '';
				$q_post__in     = empty( $post__in ) && ! empty( $p_id ) ? array( $p_id ) : $q_post__in;
				$sales_products = wc_get_product_ids_on_sale();
				if ( is_array( $q_post__in ) && ! empty( $q_post__in ) ) {

					$post__in = array_intersect( $q_post__in, $sales_products );
					if ( empty( $post__in ) && ! empty( $q_post__in ) ) {

						$post__in                          = $q_post__in;
						$query_args['meta_query']['sales'] = array(
							'key'     => '_sale_price',
							'value'   => 0,
							'compare' => '>',
							'type'    => 'numeric',
						);
					}
				} else {

					$post__in = $sales_products;
				}
				$query_args['p']        = '';
				$query_args['post__in'] = $post__in;

				break;
			case 'sp-top-rated-products':
				$query_args['meta_key'] = '_wc_average_rating';

				$order                 = $query_args['order'];
				$orderby               = $query_args['orderby'];
				$query_args['orderby'] = array(
					'_wc_average_rating' => 'DESC',
					$orderby             => $order,
				);

				break;
			case 'sp-up-sell-products':
				$p_id       = isset( $query_args['p'] ) ? $query_args['p'] : '';
				$q_post__in = isset( $query_args['post__in'] ) ? $query_args['post__in'] : '';
				$q_post__in = empty( $post__in ) && ! empty( $p_id ) ? array( $p_id ) : $q_post__in;

				if ( empty( $q_post__in ) ) {

					global $product;
					$upsell_products = is_a( $product, '\WC_Product' ) ? $product->get_upsell_ids() : array();
					$post__in        = $upsell_products;
				} else {

					$post__in = $q_post__in;
				}
				$query_args['p']        = '';
				$query_args['post__in'] = $post__in;

				break;

			case 'sp-related-products':
				$p_id       = isset( $query_args['p'] ) ? $query_args['p'] : '';
				$q_post__in = isset( $query_args['post__in'] ) ? $query_args['post__in'] : '';
				$q_post__in = empty( $post__in ) && ! empty( $p_id ) ? array( $p_id ) : $q_post__in;

				if ( empty( $q_post__in ) ) {

					$product_id       = get_the_ID();
					$related_products = wc_get_related_products( $product_id );
					$post__in         = $related_products;
				} else {

					$post__in = $q_post__in;
				}
				$query_args['p']        = '';
				$query_args['post__in'] = $post__in;

				break;
			case 'sp-flash-sales-products':
				if ( false !== strpos( $attributes['ids'], '!!' ) ) {

					$query_args['post__not_in'] = explode( ',', str_replace( '!!', '', $attributes['ids'] ) );
					$query_args['post__in']     = '';
					$query_args['p']            = '';
				}
				break;

		}

		$orderby = isset( $_REQUEST['orderby'] ) && ! empty( $_REQUEST['orderby'] ) ? sanitize_text_field( $_REQUEST['orderby'] ) : '';
		if ( ! empty( $orderby ) ) {

			switch ( $orderby ) {

				case 'popularity':
					// $query_args['meta_key'] = 'total_sales';
					// $query_args['orderby'] = 'meta_value_num';
					// $query_args['order'] = 'DESC';
					// break;
				case 'rating':
					$query_args['meta_key'] = '_wc_average_rating';
					$query_args['orderby']  = 'meta_value_num';
					$query_args['order']    = 'DESC';

					break;
				case 'price':
					$query_args['meta_key'] = '_price';
					$query_args['orderby']  = 'meta_value_num';
					$query_args['order']    = 'ASC';

					break;
				case 'price-desc':
					$query_args['meta_key'] = '_price';
					$query_args['orderby']  = 'meta_value_num';
					$query_args['order']    = 'DESC';
					break;
			}
		}

		return $query_args;
	}

	/**
	 * Check if carousel enabled.
	 *
	 * @since 1.4.0
	 *
	 * @return bool
	 */
	private function is_carousel_enabled() {
		$carousel = $this->attrs['carousel'] ?? false;
		return $carousel == 'true';
	}

	/**
	 * Returns the default attributes.
	 *
	 * @since 1.4.0
	 *
	 * @return array
	 */
	private function get_default_attributes() {
		$current_obj = get_queried_object();
		$args        = array(
			'limit'          => '12',
			'columns'        => '3',
			'rows'           => '',
			'orderby'        => '',
			'order'          => '',
			'ids'            => '',
			'skus'           => '',
			'category'       => isset( $current_obj->term_id ) ? $current_obj->term_id : '',
			'cat_operator'   => 'IN',
			'attribute'      => '',
			'terms'          => '',
			'terms_operator' => 'IN',
			'tag'            => '',
			'tag_operator'   => 'IN',
			'visibility'     => 'visible',
			'class'          => '',
			'page'           => 1,
			'paginate'       => false,
			'cache'          => true,
		);

		switch ( $this->name ) {
			case 'sp-recent-products':
				$args['orderby'] = 'id';
				$args['order']   = 'DESC';

				break;
			case 'sp-related-products':
				$args['limit']   = 4;
				$args['columns'] = 4;

				break;
		}

		return $args;
	}

	/**
	 * Prepare tag.
	 *
	 * @since 1.4.0
	 *
	 * @param array $tag
	 *
	 * @return string
	 */
	private function prepare_tag( $tag ) {

		if ( is_string( $tag ) ) {
			return $tag;
		}

		$tag_slug     = array();
		$prepared_tag = array();

		if ( is_array( $tag ) ) {

			foreach ( $tag as $value ) {

				if ( is_array( $value ) && isset( $value['value'] ) ) {

					$prepared_tag[] = $value['value'];
				} else {

					$prepared_tag[] = $value;
				}
			}

			if ( $prepared_tag ) {

				foreach ( $prepared_tag as $value ) {

					$tag_item = get_term_by( 'id', $value, 'product_tag' );
					if ( isset( $tag_item->slug ) ) {

						$tag_slug[] = $tag_item->slug;
					}
				}

				return implode( ',', $tag_slug );
			}
		}

		return '';
	}

	/**
	 * Prepare category.
	 *
	 * @since 1.4.0
	 *
	 * @param array $cat
	 *
	 * @return string
	 */
	private function prepare_cat( $cat ) {

		if ( is_string( $cat ) ) {
			return $cat;
		}

		$cat_ids      = '';
		$prepared_cat = array();

		if ( is_array( $cat ) ) {

			foreach ( $cat as $value ) {

				if ( is_array( $value ) && isset( $value['value'] ) ) {

					$prepared_cat[] = $value['value'];
				} else {

					$prepared_cat[] = $value;
				}
			}

			if ( $prepared_cat ) {
				return implode( ',', $prepared_cat );
			}
		}

		return '';
	}

	/**
	 * Returns default query attributes.
	 *
	 * @since 1.4.0
	 *
	 * @return array
	 */
	private function get_attributes() {
		$attrs = $this->get_default_attributes();

		$attrs['limit']        = ! empty( $this->attrs['limit'] ?? '' ) ? (int) $this->attrs['limit'] : 4;
		$attrs['columns']      = ! empty( $this->attrs['columns'] ?? '' ) ? (int) $this->attrs['columns'] : 4;
		$attrs['orderby']      = isset( $this->attrs['orderby'] ) ? $this->attrs['orderby'] : '';
		$attrs['order']        = isset( $this->attrs['order'] ) ? $this->attrs['order'] : '';
		$attrs['ids']          = isset( $this->attrs['ids'] ) ? $this->attrs['ids'] : '';
		$attrs['skus']         = isset( $this->attrs['skus'] ) ? $this->attrs['skus'] : '';
		$attrs['category']     = isset( $this->attrs['category'] ) ? $this->prepare_cat( $this->attrs['category'] ) : '';
		$attrs['cat_operator'] = isset( $this->attrs['cat_operator'] ) ? $this->attrs['cat_operator'] : 'IN';
		$attrs['tag']          = isset( $this->attrs['tag'] ) ? $this->prepare_tag( $this->attrs['tag'] ) : '';
		$attrs['tag_operator'] = isset( $this->attrs['tag_operator'] ) ? $this->attrs['tag_operator'] : 'IN';

		switch ( $this->name ) {
			case 'sp-flash-sales-products':
				$event_sale_id = $this->attrs['flash_sale'] ?? '';
				if ( is_numeric( $event_sale_id ) ) {

					$sale_events = FlashSalesCountdown::get_available_sale_events();
					$sale_event  = $sale_events[ $event_sale_id ] ?? false;

					$product_categories_operator = $sale_event['event_product_categories_operator'] ?? 'all';
					$product_categories          = $sale_event['event_product_categories'] ?? array();

					$products_operator = $sale_event['event_products_operator'] ?? 'all';
					$products          = $sale_event['event_products'] ?? array();

					if ( ! empty( $product_categories ) && 'all' !== $product_categories_operator ) {

						$attrs['atts']['terms'] = ( 'exclude' === $product_categories_operator ? '!!' : '' ) . ( is_array( $product_categories ) ? implode( ',', $product_categories ) : $product_categories );
					}

					if ( ! empty( $products ) && 'all' !== $products_operator ) {

						$attrs['ids'] = ( 'exclude' === $products_operator ? '!!' : '' ) . ( is_array( $products ) ? implode( ',', $products ) : $products );
					}
				}

				break;
		}

		return $attrs;
	}

	/**
	 * Returns the slider attrs.
	 *
	 * @since 1.4.4
	 */
	private function get_slider_attrs() {

		if ( ! $this->is_carousel_enabled() ) {
			return false;
		}

		$columns    = $this->attrs['carousel_columns'] ?? 4;
		$speed      = $this->attrs['slide_speed'] ?? 500;
		$arrows     = $this->attrs['show_controls'] ?? true;
		$autoplay   = $this->attrs['autoplay'] ?? true;
		$play_speed = $this->attrs['autoplay_speed'] ?? 3000;
		$loop       = $this->attrs['carousel_loop'] ?? false;
		$rows       = $this->attrs['slider_rows'] ?? 1;

		$default_breakpoints = array(
			array(
				'breakpoint' => 767,
				'settings'   => array(
					'slidesToShow' => 2,
				),
			),
			array(
				'breakpoint' => 480,
				'settings'   => array(
					'slidesToShow' => 1,
				),
			),
		);

		$slider_attrs = array(
			'slidesToShow'  => $columns,
			'speed'         => ! empty( $speed ) ? $speed : 500,
			'rtl'           => is_rtl(),
			'arrows'        => $arrows == 'true',
			'autoplay'      => $autoplay == 'true',
			'autoplaySpeed' => ! empty( $play_speed ) ? $play_speed : 3000,
			'rows'          => ! empty( $rows ) ? $rows : 1,
			'infinite'      => $loop == 'true',
			'responsive'    => $default_breakpoints,
		);

		$attrs = ' data-spslider="' . esc_attr( json_encode( $slider_attrs ) ) . '"';

		return $attrs;
	}

	/**
	 * Returns the slider class.
	 *
	 * @since 1.4.4
	 */
	private function get_slider_class() {

		if ( ! $this->is_carousel_enabled() ) {
			return false;
		}

		return 'sp-products-slider sp-slider-style';
	}

	/**
	 * Render the loop.
	 *
	 * @since 1.4.0
	 *
	 * @return void
	 */
	public function render() {
		/**
		 * Filter data_attributes.
		 *
		 * @since 1.4.0
		 *
		 * @param string $name
		 * @param array  $attrs
		 */
		$data_attributes = apply_filters( 'shoppress/builder/products_loop_data_attributes', '', $this->name, $this->attrs );

		/**
		 * Filter classes.
		 *
		 * @since 1.4.0
		 *
		 * @param string $name
		 * @param array  $attrs
		 */
		$classes = apply_filters( 'shoppress/builder/products_loop_classes', '', $this->name, $this->attrs );

		echo '<div id="sp-products-loop-' . esc_attr( wp_rand() ) . '" class="sp-products-loop-wrapper ' . esc_attr( $classes ) . ' ' . $this->get_slider_class() . '" ' . $data_attributes . ' ' . $this->get_slider_attrs() . ' >';

		/**
		 * Fires before product collection render.
		 *
		 * @since 1.4.0
		 *
		 * @param array  $attrs
		 * @param string $name
		 */
		do_action( 'shoppress/builder/before_product_collection_render', $this->attrs, $this->name );

		$products_loop = new WC_Shortcode_Products( $this->get_attributes(), $this->name );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $products_loop->get_content();

		/**
		 * Fires after product collection render.
		 *
		 * @since 1.4.0
		 *
		 * @param array  $attrs
		 * @param string $name
		 */
		do_action( 'shoppress/builder/after_product_collection_render', $this->attrs, $this->name );

		echo '</div>';
	}
}

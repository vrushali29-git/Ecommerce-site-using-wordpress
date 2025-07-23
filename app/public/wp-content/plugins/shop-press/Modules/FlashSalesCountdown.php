<?php
/**
 * Flash Sale Countdown.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

class FlashSalesCountdown {
	/**
	 * Init.
	 *
	 * @since  1.2.0
	 */
	public static function init() {
		if ( ! sp_get_module_settings( 'flash_sale_countdown', 'status' ) ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 99 );
		$position = sp_get_module_settings( 'flash_sale_countdown', 'position' )['value'] ?? '';
		if ( ! empty( $position ) && false === sp_is_template_active( 'single' ) ) {
			add_action( $position, array( __CLASS__, 'fc_countdown_html' ) );
		}
		add_filter( 'woocommerce_get_price_html', array( __CLASS__, 'fs_label_price' ), 10, 2 );
		add_action( 'woocommerce_product_variation_get_price', array( __CLASS__, 'fsc_product_variation_get_price' ), 10, 2 );
		add_filter( 'woocommerce_product_variation_get_sale_price', array( __CLASS__, 'fsc_product_variation_get_price' ), 10, 2 );
		add_filter( 'woocommerce_variation_prices_price', array( __CLASS__, 'fsc_product_variation_get_price' ), 10, 2 );
		add_filter( 'woocommerce_variation_prices_sale_price', array( __CLASS__, 'fsc_product_variation_get_price' ), 10, 2 );
		add_filter( 'woocommerce_product_get_price', array( __CLASS__, 'fsc_product_get_price' ), 10, 2 );
		add_filter( 'woocommerce_product_get_sale_price', array( __CLASS__, 'fsc_product_get_price' ), 10, 2 );
		add_filter( 'shoppress/elementor/widgets', array( __CLASS__, 'add_flash_sale_widgets' ), 9 );

		add_filter( 'shoppress/product-loop/collections', array( __CLASS__, 'filter_products_loop_collections' ), 10, 2 );
		add_action( 'shoppress/elementor/widget/products_loop/section_content/end', array( __CLASS__, 'add_control_to_products_loop_widget' ) );
	}

	/**
	 * Adds the flash sale widgets to the existing list of widgets.
	 *
	 * @param array $widgets The array of widgets.
	 *
	 * @since  1.2.0
	 *
	 * @return array The updated array of widgets.
	 */
	public static function add_flash_sale_widgets( $widgets ) {

		$widgets['loop-flash-sale-countdown'] = array(
			'editor_type' => 'loop',
			'class_name'  => 'LoopBuilder\FlashSalesCountdown',
			'is_pro'      => false,
			'path_key'    => 'loop/flash-sale-countdown',
		);

		$widgets['single-flash-sale-countdown'] = array(
			'editor_type' => 'single',
			'class_name'  => 'FlashSalesCountdown',
			'is_pro'      => false,
			'path_key'    => 'single-product/flash-sale-countdown',
		);

		return $widgets;
	}

	/**
	 * Return sale events
	 *
	 * @param int  $product_id
	 * @param bool $check_user
	 *
	 * @since  1.2.0
	 *
	 * @return array
	 */
	public static function get_available_sale_events( $product_id = 0, $check_user = true ) {
		$_sale_events = sp_get_module_settings( 'flash_sale_countdown', 'sale_events' );
		$sale_events  = array();
		if ( ! is_array( $_sale_events ) ) {
			return $sale_events;
		}

		$user_id = get_current_user_id();
		if ( $user_id ) {
			$user       = get_userdata( $user_id );
			$user_roles = $user->roles;
		}

		if ( $product_id ) {

			$product    = wc_get_product( $product_id );
			$parent_id  = $product->get_parent_id();
			$product_id = $parent_id ? $parent_id : $product_id;
		}

		foreach ( $_sale_events as $key => $sale_event ) {

			$status = $sale_event['event_enable'] ?? false;
			if ( empty( $sale_event ) || ! $status ) {
				continue;
			}

			$product_categories_operator                     = $sale_event['event_product_categories_operator']['value'] ?? 'all';
			$sale_event['event_product_categories_operator'] = $product_categories_operator;
			$sale_event['event_product_categories']          = isset( $sale_event['event_product_categories'] ) && is_array( $sale_event['event_product_categories'] ) ? array_column( $sale_event['event_product_categories'], 'value' ) : array();

			$products_operator                     = $sale_event['event_products_operator']['value'] ?? 'all';
			$sale_event['event_products_operator'] = $products_operator;
			$sale_event['event_products']          = isset( $sale_event['event_products'] ) && is_array( $sale_event['event_products'] ) ? array_column( $sale_event['event_products'], 'value' ) : array();

			$event_apply_user_roles               = $sale_event['event_apply_user_roles'] ?? false;
			$sale_event['event_apply_user_roles'] = is_array( $event_apply_user_roles ) ? array_column( $event_apply_user_roles, 'value' ) : array();

			$discount_type                     = $sale_event['event_discount_type']['value'] ?? '';
			$sale_event['event_discount_type'] = $discount_type;
			$discount_value                    = $sale_event['event_discount_value'] ?? false;
			if ( ! $discount_type || ! $discount_value ) {
				continue;
			}

			$current_timestamp = current_time( 'timestamp' );
			$event_valid_from  = $sale_event['event_valid_from'] ?? false;
			if ( ! $event_valid_from || strtotime( $event_valid_from . ' 00:00' ) > $current_timestamp ) {
				continue;
			}

			$event_valid_to = $sale_event['event_valid_to'] ?? false;
			if ( ! $event_valid_to || strtotime( $event_valid_to . ' 23:59:59' ) < $current_timestamp ) {
				continue;
			}

			if ( $product_id ) {

				$product_category_ids         = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
				$product_categories_intersect = array_intersect( $product_category_ids, $sale_event['event_product_categories'] );

				if (
					! empty( $sale_event['event_product_categories'] )
					&&
					(
						( 'include' == $product_categories_operator && empty( $product_categories_intersect ) )
						||
						( 'exclude' == $product_categories_operator && ! empty( $product_categories_intersect ) )
					)
				) {
					continue;
				}

				if (
					! empty( $sale_event['event_products'] )
					&&
					(
						( 'include' == $products_operator && ! in_array( $product_id, $sale_event['event_products'] ) )
						||
						( 'exclude' == $products_operator && in_array( $product_id, $sale_event['event_products'] ) )
					)
				) {
					continue;
				}
			}

			if ( $check_user ) {

				$event_apply_discount_registered = $sale_event['event_apply_discount_registered'] ?? false;
				if ( $event_apply_discount_registered && ! is_user_logged_in() ) {
					continue;
				}

				if ( ! empty( $sale_event['event_apply_user_roles'] ) ) {

					if ( ! is_user_logged_in() ) {

						continue;
					}

					if ( empty( array_intersect( $sale_event['event_apply_user_roles'], $user_roles ) ) ) {

						continue;
					}
				}
			}

			$sale_events[ $key ] = $sale_event;
		}

		return $sale_events;
	}

	/**
	 * Return sale events
	 *
	 * @param int  $product_id
	 * @param bool $check_user
	 *
	 * @since  1.2.0
	 *
	 * @return array|false
	 */
	public static function get_available_sale_event( $product_id = 0, $check_user = true ) {

		$sale_events = static::get_available_sale_events( $product_id, $check_user );

		return is_array( $sale_events ) && count( $sale_events ) ? current( $sale_events ) : false;
	}

	/**
	 * Return flash sale product price
	 *
	 * @param \WC_Product $product
	 * @param array       $sale_event
	 *
	 * @since  1.2.0
	 *
	 * @return int|float
	 */
	public static function get_flash_sale_price( $product, $sale_event, $price = '', $price_type = 'regular' ) {

		$product_id = $product->get_id();

		return static::get_price(
			sp_get_module_settings( 'flash_sale_countdown', 'override_sale_price' ),
			$sale_event['event_discount_type'],
			$sale_event['event_discount_value'],
			$product_id,
			$price
		);
	}

	/**
	 * Change Label Price.
	 *
	 * @param $html
	 * @param $product
	 *
	 * @since  1.1.2
	 *
	 * @return string
	 */
	public static function fs_label_price( $html, $product ) {
		$product_id = $product->get_id();

		$sale_event = static::get_available_sale_event( $product_id );
		if ( empty( $sale_event ) ) {
			return $html;
		}

		$flash_sale_price = '';

		if ( ! $product->is_type( 'variable' ) && ! $product->is_type( 'grouped' ) ) {
			$flash_sale_price = wc_price( static::get_flash_sale_price( $product, $sale_event ) );
		}

		$manage_price_label = '<span class="sp-fsc-price-main">' . $html . '</span>';

		if ( is_admin() ) {
			return $flash_sale_price;
		} elseif ( empty( $manage_price_label ) ) {
			return $flash_sale_price;
		} else {
			return $manage_price_label;
		}
	}

	/**
	 * fsc Product Variation get Price.
	 *
	 * @param $price
	 * @param $product
	 *
	 * @since  1.1.2
	 *
	 * @return string
	 */
	public static function fsc_product_variation_get_price( $price, $product ) {
		$product_id = $product->get_id();

		$sale_event = static::get_available_sale_event( $product_id );
		if ( empty( $sale_event ) ) {
			return $price;
		}

		return static::get_flash_sale_price( $product, $sale_event, $price );
	}

	/**
	 * fc Countdown Html.
	 *
	 * @since  1.1.2
	 *
	 * @return void
	 */
	public static function fc_countdown_html() {

		if ( ! is_product() ) {
			return;
		}

		if ( ! sp_get_module_settings( 'flash_sale_countdown', 'show_product_page' ) ) {
			return;
		}

		global $shoppress_sticky_add_to_cart;
		if ( $shoppress_sticky_add_to_cart ) {
			return;
		}

		$atts = array(
			'title' => sp_get_module_settings( 'flash_sale_countdown', 'timer_title' ),
		);
		global $product;
		static::display_countdown( $product, $atts );
	}

	/**
	 * Return Countdown Html.
	 *
	 * @param \WC_Product $product
	 *
	 * @since  1.2.0
	 *
	 * @return void
	 */
	public static function display_countdown( $product = null, $atts = array() ) {

		$title      = $atts['title'] ?? '';
		$product    = ! is_null( $product ) ? $product : $GLOBALS['product'];
		$product_id = $product->get_id();

		$sale_event = static::get_available_sale_event( $product_id );
		if ( empty( $sale_event ) ) {
			return;
		}

		$time_end = $sale_event['event_valid_to'];
		if ( $time_end ) :
			?>
				<div class="fs-countdown-wrapper">
					<?php if ( $title ) : ?>
						<h6 class="fs-countdown-title"><?php echo wp_kses_post( $title ); ?></h6>
					<?php endif; ?>
					<div class="fs-countdown" data-timeend="<?php echo esc_attr( $time_end ); ?>"></div>
				</div>
			<?php
		endif;
	}

	/**
	 * fsc Product Get Price.
	 *
	 * @param $price
	 * @param $product
	 *
	 * @since  1.1.2
	 *
	 * @return string
	 */
	public static function fsc_product_get_price( $price, $product ) {
		$product_id = $product->get_id();

		$sale_event = static::get_available_sale_event( $product_id );
		if ( empty( $sale_event ) ) {
			return $price;
		}

		return static::get_flash_sale_price( $product, $sale_event, $price );
	}

	/**
	 * Get Variable Price.
	 *
	 * @param $product
	 * @param $override_price
	 * @param $discount_type
	 * @param $discount_value
	 * @param $price
	 *
	 * @since  1.1.2
	 *
	 * @return float|int
	 */
	public static function get_variable_price( $product, $override_price, $discount_type, $discount_value, $price ) {
		$base_price = (float) $product->get_regular_price();
		if ( $override_price ) {
			$base_price = (float) $price;
		}

		$flash_sale_price = 0;
		if ( $discount_type == 'percentage_discount' ) {
			$flash_sale_price = ( 1 - (float) $discount_value / 100 ) * $base_price;
		} elseif ( $discount_type == 'fixed_discount' ) {
			$flash_sale_price = $base_price - (float) $discount_value;
		} elseif ( $discount_type == 'fixed_price' ) {
			$flash_sale_price = (float) $discount_value;
		}
		return $flash_sale_price;
	}

	/**
	 * Calculate Price By Discount.
	 *
	 * @param string         $product_id
	 * @param $override_price
	 * @param $discount_type
	 * @param $discount_value
	 * @param string         $price
	 *
	 * @since  1.1.2
	 *
	 * @return float|int
	 */
	public static function get_price( $override_price, $discount_type, $discount_value, $product_id = '', $price = '' ) {
		$regular = get_post_meta( $product_id, '_regular_price', true );
		$sale    = get_post_meta( $product_id, '_sale_price', true );

		$base_price = (float) $regular;

		if ( $override_price && $sale ) {
			$base_price = (float) $sale;
		}

		if ( $price ) {
			$base_price = (float) $price;
		}

		$flash_sale_price = 0;
		if ( $discount_type == 'percentage_discount' ) {
			$flash_sale_price = ( 1 - (float) $discount_value / 100 ) * $base_price;
		} elseif ( $discount_type == 'fixed_discount' ) {
			$discount_value = (float) $discount_value;
			if ( $base_price > $discount_value ) {
				$flash_sale_price = $base_price - $discount_value;
			} else {
				$flash_sale_price = 0;
			}
		} elseif ( $discount_type == 'fixed_price' ) {
			$flash_sale_price = (float) $discount_value;
		}
		return $flash_sale_price;
	}

	/**
	 * enqueue scripts.
	 *
	 * @since  1.1.2
	 */
	public static function enqueue_scripts() {
		// if ( is_product() ) {
		wp_enqueue_script( 'sp-flash-sale-countdown' );
		wp_enqueue_style( 'sp-flash-sale-countdown' );

		if ( is_rtl() ) {
			return array( 'sp-flash-sale-countdown-rtl' );
		}
		// }
	}

	/**
	 * Return list sales events
	 *
	 * @since 1.3.5
	 *
	 * @return array
	 */
	public static function get_list_sale_events() {
		$sale_events         = self::get_available_sale_events( 0, false );
		$sale_events_options = array();

		foreach ( $sale_events as $id => $value ) {

			$sale_events_options[ $id ] = $value['event_name'];
		}

		return $sale_events_options;
	}

	/**
	 * Filter products loop collections
	 *
	 * @param array       $collections
	 * @param ProductLoop $class
	 *
	 * @since 1.3.5
	 *
	 * @return array
	 */
	public static function filter_products_loop_collections( $collections, $class ) {

		$collections['sp-flash-sales-products'] = __( 'Flash Sales', 'shop-press' );

		return $collections;
	}

	/**
	 * Add control to products loop widget
	 *
	 * @param ProductLoop $class
	 *
	 * @since 1.3.5
	 *
	 * @return void
	 */
	public static function add_control_to_products_loop_widget( $class ) {

		$sale_events = self::get_list_sale_events();
		$class->add_control(
			'flash_sale',
			array(
				'label'     => esc_html__( 'Select Flash Sale', 'shop-press' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => $sale_events,
				'default'   => key( $sale_events ),
				'condition' => array(
					'product_collection' => 'sp-flash-sales-products',
				),
			)
		);
	}
}

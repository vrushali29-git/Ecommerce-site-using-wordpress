<?php
/**
 * Catalog Mode.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

class CatalogMode {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {

		if ( ! self::is_catalog_mode() ) {
			return;
		}

		$hide_add_to_cart     = sp_get_module_settings( 'catalog_mode', 'hide_add_to_cart', false );
		$change_text_and_link = sp_get_module_settings( 'catalog_mode', 'change_text_and_link', false );

		if ( $hide_add_to_cart ) {
			add_action( 'woocommerce_init', array( __CLASS__, 'add_to_cart_button' ), 99 );
		}

		if ( $change_text_and_link ) {
			add_filter( 'woocommerce_product_add_to_cart_text', array( __CLASS__, 'custom_add_to_cart_text' ), 10, 2 );
			add_filter( 'woocommerce_product_single_add_to_cart_text', array( __CLASS__, 'custom_add_to_cart_text' ), 10, 2 );
			add_filter( 'woocommerce_product_add_to_cart_url', array( __CLASS__, 'custom_add_to_cart_link' ), 10, 2 );
			add_filter( 'woocommerce_add_to_cart_redirect', array( __CLASS__, 'custom_add_to_cart_link' ), 10, 2 );
			add_filter( 'woocommerce_is_sold_individually', array( __CLASS__, 'remove_quantity_fields' ), 10, 2 );
		}

		add_filter( 'woocommerce_get_price_html', array( __CLASS__, 'custom_price_html' ), 100, 2 );
		add_filter( 'woocommerce_product_tabs', array( __CLASS__, 'remove_product_tabs' ), 98 );
	}

	/**
	 * Check if catalog mode is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_catalog_mode() {

		if ( ! empty( sp_get_module_settings( 'catalog_mode', 'status' ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Add to cart button catalog mode.
	 *
	 * @since 1.0.0
	 */
	public static function add_to_cart_button() {

		if ( self::is_available_catalog_mode() === false ) {
			return;
		}

		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		remove_action( 'woocommerce_loop_add_to_cart_link', 'woocommerce_template_loop_add_to_cart', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
		remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
		remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
		remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
		add_filter( 'woocommerce_loop_add_to_cart_link', '__return_empty_string', 10 );
	}

	/**
	 * Remove product tabs catalog mode.
	 *
	 * @param array $tabs
	 *
	 * @since 1.4.0
	 *
	 * @return array
	 */
	public static function remove_product_tabs( $tabs ) {
		$hide_review_tab = sp_get_module_settings( 'catalog_mode', 'hide_review_tab', false );

		if ( $hide_review_tab && self::is_available_catalog_mode() === true ) {
			unset( $tabs['reviews'] );
		}

		return $tabs;
	}

	/**
	 * Add to cart button catalog mode.
	 *
	 * @param string $price
	 *
	 * @since 1.4.0
	 *
	 * @return string
	 */
	public static function custom_price_html( $price ) {
		$hide_price        = sp_get_module_settings( 'catalog_mode', 'hide_price', false );
		$price_placeholder = sp_get_module_settings( 'catalog_mode', 'price_placeholder', '' );
		if ( $hide_price && self::is_available_catalog_mode() === true ) {
			return $price_placeholder;
		}

		return $price;
	}

	/**
	 * Custom add to cart text catalog mode.
	 *
	 * @param string $add_to_cart_text
	 *
	 * @since 1.4.0
	 *
	 * @return string
	 */
	public static function custom_add_to_cart_text( $add_to_cart_text ) {

		if ( self::is_available_catalog_mode() === false ) {
			return $add_to_cart_text;
		}

		$custom_text = sp_get_module_settings( 'catalog_mode', 'custom_text' );

		return empty( ! $custom_text ) ? $custom_text : $add_to_cart_text;
	}

	/**
	 * Custom add to cart link catalog mode.
	 *
	 * @param string $url
	 *
	 * @since 1.4.0
	 *
	 * @return string
	 */
	public static function custom_add_to_cart_link( $url ) {

		if ( self::is_available_catalog_mode() === false ) {
			return $url;
		}

		$custom_link = sp_get_module_settings( 'catalog_mode', 'custom_link' ) ?? $url;

		return $custom_link;
	}

	/**
	 * Remove quantity fields catalog mode.
	 *
	 * @since 1.4.0
	 *
	 * @return bool
	 */
	public static function remove_quantity_fields() {
		return true;
	}

	/**
	 * Return prepared rules
	 *
	 * @param array $conditions
	 *
	 * @since 1.4.0
	 *
	 * @return array
	 */
	public static function prepare_rules( $conditions ) {

		foreach ( $conditions as $c_key => $rules ) {

			foreach ( $rules as $k => $rule ) {

				$rule['condition'] = $rule['condition']['value'] ?? false;
				$rule['operator']  = $rule['operator']['value'] ?? false;

				if ( isset( $rule['value']['min'] ) ) {

					$rule['value'] = $rule['value'] ?? array(
						'min' => 0,
						'max' => 5,
					);
				} elseif ( is_array( $rule['value'] ) && isset( current( $rule['value'] )['value'] ) ) {

					$rule['value'] = array_column( (array) ( $rule['value'] ?? array() ), 'value' );
				}

				$rules[ $k ] = $rule;
			}

			$conditions[ $c_key ] = $rules;
		}

		return $conditions;
	}

	/**
	 * Is available catalog mode.
	 *
	 * @since 1.4.0
	 *
	 * @return bool
	 */
	public static function is_available_catalog_mode() {
		// TODO: add rules option later!
		return true;

		$rules = sp_get_module_settings( 'catalog_mode', 'rules', array() );
		if ( empty( $rules ) ) {
			return true;
		}

		$rules      = self::prepare_rules( $rules );
		$user_id    = get_current_user_id();
		$user_roles = $user_id ? (array) wp_get_current_user()->roles : array();

		global $product;

		if ( ! is_a( $product, '\WC_Product' ) ) {
			return;
		}

		$product_id           = $product->get_id();
		$product_category_ids = $product->get_category_ids();

		$is_available = false;

		foreach ( $rules as $group_id => $group_data ) {

			$rule_group_validation = true;
			foreach ( $group_data as $rule_id => $rule ) {
				$type     = $rule['condition'] ?? false;
				$operator = $rule['operator'] ?? false;
				$value    = $rule['value'] ?? false;
				if ( ! $type || ! $operator || empty( $value ) ) {
					continue;
				}

				$validation = false;
				switch ( $type ) {
					case 'product_categories':
					case 'products':
						if ( 'product_categories' == $type ) {
							$values = $product_category_ids;
						} elseif ( 'products' == $type ) {
							$values = array( $product_id );
						}

						$intersect = array_intersect( (array) $values, (array) $value );
						if ( 'all' === $operator ) {
							$validation = true;
						} elseif ( 'include' == $operator ) {
							$validation = ! empty( $intersect );
						} else {
							$validation = empty( $intersect );
						}
						break;
					case 'user_role':
						$intersect = array_intersect( (array) $user_roles, (array) $value );
						if ( 'all' === $operator ) {
							$validation = true;
						} elseif ( 'include' == $operator ) {
							$validation = ! empty( $intersect );
						} else {
							$validation = empty( $intersect );
						}
						break;
					case 'login_status':
						if (
							( 'logged_in' == $value['value'] && $user_id )
							||
							( 'not_logged_in' == $value['value'] && ! $user_id )
							) {
							$validation = true;
						} else {
							$validation = false;
						}
						break;
				}

				if ( ! $validation ) {

					$rule_group_validation = false;
					break;
				}
			}

			if ( $rule_group_validation ) {
				$is_available = true;
				break;
			}
		}

		return $is_available;
	}
}

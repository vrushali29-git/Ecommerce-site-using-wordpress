<?php
/**
 * Shopify Checkout.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

class Shopify {
	/**
	 * Init.
	 *
	 * @since  1.2.0
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'wp_init' ), 99 );
		add_filter( 'wc_get_template', array( __CLASS__, 'checkout_templates' ), 9999, 5 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 99, 99 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'load_wp_media' ) );
		add_filter( 'template_include', array( __CLASS__, 'single_template_include' ), 9999 );
		add_filter( 'woocommerce_checkout_fields', array( __CLASS__, 'filter_checkout_fields' ), 9999 );
	}

	/**
	 * WP Init
	 *
	 * @since 1.3.1
	 *
	 * @return void
	 */
	public static function wp_init() {

		remove_action( 'woocommerce_before_checkout_form_cart_notices', 'woocommerce_output_all_notices' );
	}

	/**
	 * Load WP Media
	 *
	 * @since 1.1.2
	 */
	public static function load_wp_media() {
		wp_enqueue_media();
	}

	/**
	 * Checkout templates.
	 *
	 * @since 1.1.2
	 */
	public static function checkout_templates( $located, $template_name ) {
		if ( $template_name == 'global/form-login.php' && is_checkout() ) {
			$located = sp_get_template_path( 'form-login' );
		}

		if ( $template_name == 'checkout/form-checkout.php' ) {
			$located = sp_get_template_path( 'checkout/shopify-template/form-checkout' );
		}

		if ( $template_name == 'checkout/form-billing.php' ) {
			$located = sp_get_template_path( 'checkout/shopify-template/form-billing' );
		}

		if ( $template_name == 'checkout/form-shipping.php' ) {
			$located = sp_get_template_path( 'checkout/shopify-template/form-shipping' );
		}

		if ( $template_name == 'cart/cart-shipping.php' ) {
			$located = sp_get_template_path( 'checkout/shopify-template/cart-shipping' );
		}

		if ( $template_name == 'checkout/payment.php' ) {
			$located = sp_get_template_path( 'checkout/shopify-template/payment' );
		}

		if ( $template_name == 'checkout/review-order.php' ) {
			$located = sp_get_template_path( 'checkout/shopify-template/review-order' );
		}

		if ( $template_name == 'checkout/form-coupon.php' ) {
			$located = sp_get_template_path( 'checkout/shopify-template/form-coupon' );
		}

		return $located;
	}

	/**
	 * Single templates.
	 *
	 * @since 1.1.2
	 */
	public static function single_template_include( $located ) {
		if ( is_checkout() ) {
			$located = sp_get_template_path( 'checkout/shopify-checkout-canvas' );
		}

		return $located;
	}

	/**
	 * Filter checkout fields.
	 *
	 * @param array $fields
	 *
	 * @since 1.2.0
	 */
	public static function filter_checkout_fields( $fields ) {

		$is_phone_number_field = sp_get_module_settings( 'shopify_checkout', 'phone_number_field' );
		if ( ! $is_phone_number_field ) {

			unset( $fields['billing']['billing_phone'] );
			unset( $fields['shipping']['shipping_phone'] );
		}

		$billing_address_2 = sp_get_module_settings( 'shopify_checkout', 'billing_address_2' );
		if ( ! $billing_address_2 ) {

			unset( $fields['billing']['billing_address_2'] );
		}

		$shipping_address_2 = sp_get_module_settings( 'shopify_checkout', 'shipping_address_2' );
		if ( ! $shipping_address_2 ) {

			unset( $fields['shipping']['shipping_address_2'] );
		}

		return $fields;
	}

	/**
	 * Outputs a checkout/address form field.
	 *
	 * @param string $key Key.
	 * @param mixed  $args Arguments.
	 * @param string $value (default: null).
	 *
	 * @return string
	 *
	 * @since 1.1.2
	 */
	public static function woocommerce_form_field( $key, $args, $value = null ) {
		$defaults = array(
			'type'              => 'text',
			'label'             => '',
			'description'       => '',
			'placeholder'       => '',
			'maxlength'         => false,
			'required'          => false,
			'autocomplete'      => false,
			'id'                => $key,
			'class'             => array(),
			'label_class'       => array(),
			'input_class'       => array(),
			'return'            => false,
			'options'           => array(),
			'custom_attributes' => array(),
			'validate'          => array(),
			'default'           => '',
			'autofocus'         => '',
			'priority'          => '',
		);

		$args = wp_parse_args( $args, $defaults );
		$args = apply_filters( 'woocommerce_form_field_args', $args, $key, $value );

		if ( is_string( $args['class'] ) ) {
			$args['class'] = array( $args['class'] );
		}

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required        = '&nbsp;<abbr class="required" title="' . esc_attr__( 'required', 'shop-press' ) . '">*</abbr>';
		} else {
			$required = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'shop-press' ) . ')</span>';
		}

		if ( is_string( $args['label_class'] ) ) {
			$args['label_class'] = array( $args['label_class'] );
		}

		if ( is_null( $value ) ) {
			$value = $args['default'];
		}

		// Custom attribute handling.
		$custom_attributes         = array();
		$args['custom_attributes'] = array_filter( (array) $args['custom_attributes'], 'strlen' );

		if ( $args['maxlength'] ) {
			$args['custom_attributes']['maxlength'] = absint( $args['maxlength'] );
		}

		if ( ! empty( $args['autocomplete'] ) ) {
			$args['custom_attributes']['autocomplete'] = $args['autocomplete'];
		}

		if ( true === $args['autofocus'] ) {
			$args['custom_attributes']['autofocus'] = 'autofocus';
		}

		if ( $args['description'] ) {
			$args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
		}

		if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
			foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		if ( ! empty( $args['validate'] ) ) {
			foreach ( $args['validate'] as $validate ) {
				$args['class'][] = 'validate-' . $validate;
			}
		}

		$field           = '';
		$label_id        = $args['id'];
		$sort            = $args['priority'] ? $args['priority'] : '';
		$field_container = '<p class="sp-form-row %1$s" id="%2$s" data-priority="' . esc_attr( $sort ) . '">%3$s</p>';

		switch ( $args['type'] ) {
			case 'country':
				$countries = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();

				if ( 1 === count( $countries ) ) {

					$field .= '<strong>' . current( array_values( $countries ) ) . '</strong>';

					$field .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . current( array_keys( $countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' class="country_to_state" readonly="readonly" />';

				} else {
					$data_label = ! empty( $args['label'] ) ? 'data-label="' . esc_attr( $args['label'] ) . '"' : '';

					$field = '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="country_to_state country_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ? $args['placeholder'] : esc_attr__( 'Select a country / region&hellip;', 'shop-press' ) ) . '" ' . $data_label . '><option value="">' . esc_html__( 'Select a country / region&hellip;', 'shop-press' ) . '</option>';

					foreach ( $countries as $ckey => $cvalue ) {
						$field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
					}

					$field .= '</select>';

					$field .= '<noscript><button type="submit" name="woocommerce_checkout_update_totals" value="' . esc_attr__( 'Update country / region', 'shop-press' ) . '">' . esc_html__( 'Update country / region', 'shop-press' ) . '</button></noscript>';

				}
				break;
			case 'state':
				/* Get country this state field is representing */
				$for_country = isset( $args['country'] ) ? $args['country'] : WC()->checkout->get_value( 'billing_state' === $key ? 'billing_country' : 'shipping_country' );
				$states      = WC()->countries->get_states( $for_country );

				if ( is_array( $states ) && empty( $states ) ) {

					$field_container = '<p class="" id="%2$s" style="display: none">%3$s</p>';

					$field .= '<input type="hidden" class="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . esc_attr( $args['placeholder'] ) . '" readonly="readonly" data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '"/>';

				} elseif ( ! is_null( $for_country ) && is_array( $states ) ) {
					$data_label = ! empty( $args['label'] ) ? 'data-label="' . esc_attr( $args['label'] ) . '"' : '';

					$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="state_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ? $args['placeholder'] : esc_html__( 'Select an option&hellip;', 'shop-press' ) ) . '"  data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . $data_label . '>
						<option value="">' . esc_html__( 'Select an option&hellip;', 'shop-press' ) . '</option>';

					foreach ( $states as $ckey => $cvalue ) {
						$field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
					}

					$field .= '</select>';

				} else {

					$field .= '<input type="text" class="sp-input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $value ) . '"  placeholder="' . esc_attr( $args['placeholder'] ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" ' . implode( ' ', $custom_attributes ) . ' data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '"/>';

				}
				break;
			case 'textarea':
				$field .= '<textarea name="' . esc_attr( $key ) . '" class="sp-input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '' ) . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $value ) . '</textarea>';
				break;
			case 'checkbox':
				$field = '<label class="checkbox ' . implode( ' ', $args['label_class'] ) . '" ' . implode( ' ', $custom_attributes ) . '>
						<input type="' . esc_attr( $args['type'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="1" ' . checked( $value, 1, false ) . ' /> ' . $args['label'] . $required . '</label>';
				break;
			case 'text':
			case 'password':
			case 'datetime':
			case 'datetime-local':
			case 'date':
			case 'month':
			case 'time':
			case 'week':
			case 'number':
			case 'email':
			case 'url':
			case 'tel':
				$field .= '<input type="' . esc_attr( $args['type'] ) . '" class="sp-input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '"  value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';
				break;
			case 'hidden':
				$field .= '<input type="' . esc_attr( $args['type'] ) . '" class="input-hidden ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';
				break;
			case 'select':
				$field   = '';
				$options = '';

				if ( ! empty( $args['options'] ) ) {
					foreach ( $args['options'] as $option_key => $option_text ) {
						if ( '' === $option_key ) {
							// If we have a blank option, select2 needs a placeholder.
							if ( empty( $args['placeholder'] ) ) {
								$args['placeholder'] = $option_text ? $option_text : __( 'Choose an option', 'shop-press' );
							}
							$custom_attributes[] = 'data-allow_clear="true"';
						}
						$options .= '<option value="' . esc_attr( $option_key ) . '" ' . selected( $value, $option_key, false ) . '>' . esc_html( $option_text ) . '</option>';
					}

					$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '">
							' . $options . '
						</select>';
				}

				break;
			case 'radio':
				$label_id .= '_' . current( array_keys( $args['options'] ) );

				if ( ! empty( $args['options'] ) ) {
					foreach ( $args['options'] as $option_key => $option_text ) {
						$field .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ) . '" ' . implode( ' ', $custom_attributes ) . ' id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false ) . ' />';
						$field .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio ' . implode( ' ', $args['label_class'] ) . '">' . esc_html( $option_text ) . '</label>';
					}
				}
				break;
		}

		if ( ! empty( $field ) ) {
			$field_html = '';

			if ( $args['label'] && 'checkbox' !== $args['type'] ) {
				$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) . '">' . wp_kses_post( $args['label'] ) . $required . '</label>';
			}

			$field_html .= '<span class="woocommerce-input-wrapper">' . $field;

			if ( $args['description'] ) {
				$field_html .= '<span class="description" id="' . esc_attr( $args['id'] ) . '-description" aria-hidden="true">' . wp_kses_post( $args['description'] ) . '</span>';
			}

			$field_html .= '</span>';

			$container_class = esc_attr( implode( ' ', $args['class'] ) );
			$container_id    = esc_attr( $args['id'] ) . '_field';
			$field           = sprintf( $field_container, $container_class, $container_id, $field_html );
		}

		/**
		 * Filter by type.
		 */
		$field = apply_filters( 'woocommerce_form_field_' . $args['type'], $field, $key, $args, $value );

		/**
		 * General filter on form fields.
		 *
		 * @since 3.4.0
		 */
		$field = apply_filters( 'woocommerce_form_field', $field, $key, $args, $value );

		if ( $args['return'] ) {
			return $field;
		} else {
			echo $field; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * enqueue scripts.
	 *
	 * @since 1.1.2
	 */
	public static function enqueue_scripts() {

		if ( is_checkout() ) {

			wp_enqueue_script( 'sp-shopify-checkout' );
			wp_enqueue_style( 'sp-shopify-checkout' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-shopify-checkout-rtl' );
			}

			wp_enqueue_style( 'sp-checkout' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-checkout-rtl' );
			}
		}
	}
}

<?php
/**
 * Templates Utils.
 *
 * @package ShopPress
 */

namespace ShopPress\Templates;

defined( 'ABSPATH' ) || exit;

use ShopPress\Plugin;

class Utils {
	/**
	 * Returns sp_builder_type meta from post.
	 *
	 * @since 1.4.0
	 *
	 * @param int|string $builder_id
	 *
	 * @return string
	 */
	public static function get_builder_type( $builder_id ) {
		$builder_type = get_post_meta( $builder_id, 'sp_builder_type', true );

		return ! empty( $builder_type ) ? $builder_type : 'elementor';
	}

	/**
	 * Returns the loop builder templates.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_loop_builder_templates() {
		$args = array(
			'post_type'      => 'shoppress_loop',
			'posts_per_page' => 100,
		);

		$posts     = get_posts( $args );
		$templates = array(
			0 => __( 'Default', 'shop-press' ),
		);

		if ( ! sp_is_template_active( 'products_loop' ) ) {
			return $templates;
		}

		foreach ( $posts as $post ) {

			$templates[ $post->ID ] = $post->post_title;
		}

		return $templates;
	}

	/**
	 * Get post by ID.
	 *
	 * @param int $id
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Post
	 */
	public static function post( $id ) {

		global $shoppress_post;

		$shoppress_post = ( ! is_a( $shoppress_post, 'WP_Post' ) || $shoppress_post->ID !== $id ) ? get_post( $id ) : $shoppress_post;

		return $shoppress_post;
	}

	/**
	 * Get the latest product.
	 *
	 * @since 1.0.0
	 *
	 * @return false|\WC_Product
	 */
	public static function get_latest_product() {
		global $product;

		if ( ! empty( $product ) ) {

			return $product;
		}

		$arguments = array( 'limit' => 1 );
		$product   = wc_get_products( $arguments );

		if ( is_array( $product ) ) {
			$product = current( $product );
		}

		return $product;
	}

	/**
	 * Get the latest Product ID.
	 *
	 * @since 1.0.0
	 *
	 * @return int|false
	 */
	public static function get_latest_product_id() {
		return is_a( static::get_latest_product(), '\WC_product' ) ? static::get_latest_product()->get_id() : false;
	}

	/**
	 * Set the post data.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function setup_postdata() {
		global $post, $shoppress_old_post;

		$shoppress_old_post = $post;
		$post_id            = is_a( $post, '\WP_Post' ) ? $post->ID : 0;
		$product_id         = static::get_latest_product_id();

		if ( $post_id !== $product_id ) {

			$post = static::post( $product_id );
			setup_postdata( $post );
		}
	}

	/**
	 * Reset post data.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function reset_postdata() {
		global $post, $shoppress_old_post;

		$post = $shoppress_old_post;
	}

	/**
	 * Expand more for items.
	 *
	 * @since 1.4.0
	 */
	public static function expand_more( $visiblity ) {
		if ( $visiblity === 'yes' ) {
			return '
			<div class="more-less-view">
				<div class="show-more more-less-button">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="16"
						height="16" viewBox="0 0 30 30">
						<g></g>
						<path
							d="M15.233 19.175l0.754 0.754 6.035-6.035-0.754-0.754-5.281 5.281-5.256-5.256-0.754 0.754 3.013 3.013z"
							fill="#000000"></path>
					</svg>
					' . __( 'Show All', 'shop-press' ) . '</div>
				<div class="show-less more-less-button">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="16"
						height="16" viewBox="0 0 30 30">
						<g>
						</g>
						<path
							d="M16.767 12.809l-0.754-0.754-6.035 6.035 0.754 0.754 5.281-5.281 5.256 5.256 0.754-0.754-3.013-3.013z"
							fill="#000000"></path>
					</svg>
					' . __( 'Show Less', 'shop-press' ) . '</div>
			</div>';
		}
	}

	/**
	 * Returns builder type for creating the default templates.
	 *
	 * @since 1.4.0
	 */
	public static function get_builder_template_type() {
		$builder_type = Plugin::is_active_elementor() ? 'elementor' : 'block_editor';

		/**
		 * Fires before default templates creation.
		 *
		 * @since 1.4.0
		 *
		 * @param string $builder_type
		 */
		return apply_filters( 'shoppress\default_builder_type', $builder_type );
	}

	/**
	 * Render icon.
	 *
	 * @since 1.0.0
	 *
	 * @param string $icon
	 * @param array  $attrs
	 */
	public static function render_icon( $icon, $attrs = array() ) {
		ob_start();

		if ( isset( $attrs['class'] ) && strpos( $attrs['class'], 'sp-icon' ) == false ) {
				$attrs['class'] .= ' sp-icon';
		} elseif ( ! isset( $attrs['class'] ) ) {
			$attrs['class'] = 'sp-icon';
		}

		if ( isset( $icon['library'] ) && 'svg' === $icon['library'] ) {

			if ( class_exists( '\Elementor\Icons_Manager' ) ) {

				echo '<i class="sp-icon">';
					\Elementor\Icons_Manager::render_icon( $icon, $attrs );
				echo '</i>';
			}
		} elseif ( isset( $icon['type'] ) && isset( $icon['value'] ) ) {

			echo wp_get_attachment_image( $icon['value'] );
		} elseif ( ! empty( $icon['value'] ) ) {

			echo "<i aria-hidden='true' class='sp-icon {$icon['value']}'></i>";
		}

		$icon = ob_get_clean();

		return $icon;
	}

	/**
	 * Returns terms.
	 *
	 * @since 1.0.0
	 *
	 * @param string $taxonomy
	 */
	public static function get_terms_for_select( $taxonomy ) {
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);

		$options = array();

		if ( is_wp_error( $terms ) ) {
			return $options;
		}

		foreach ( $terms as $term ) {

			$key   = $term->term_taxonomy_id;
			$title = sprintf(
				'%s ( %d )',
				$term->name,
				$term->count
			);

			$options[ $key ] = $title;
		}

		return $options;
	}

	/**
	 * Add to cart button.
	 *
	 * @since 1.0.0
	 */
	public static function AddToCartButton( $product, $icon = '', $icon_position = 'after', $show_text = true, $custom_text = '' ) {
		$icon      = sp_render_icon( $icon, array( 'aria-hidden' => 'true' ) );
		$is_single = ( is_singular( 'product' ) || is_sp_quick_view_ajax() ) ? true : false;
		$before    = 'before' == $icon_position ? $icon : '';
		$after     = 'after' == $icon_position ? $icon : '';
		$text      = $show_text ? $product->single_add_to_cart_text() : '';
		$text      = ( '' !== $custom_text && $show_text ) ? $custom_text : $text;

		if ( $is_single ) {

			$is_ajax_add_to_cart = sp_get_module_settings( 'single_ajax_add_to_cart', 'status' );
			$classes             = $is_ajax_add_to_cart ? 'ajax_add_to_cart' : '';
			if ( $is_ajax_add_to_cart ) {
				echo '<input type="hidden" name="action" value="shoppress_single_add_to_cart_by_ajax">';
			}

			if ( 'variable' == $product->get_type() || 'grouped' == $product->get_type() ) {

				echo '<button type="submit" class="single_add_to_cart_button button alt' . ( esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) ) . ' ' . $classes . '">' . $before . esc_html( $text ) . $after . '</button>';
			} else {

				echo '<button type="submit" name="add-to-cart" value="' . esc_attr( $product->get_id() ) . '" class="single_add_to_cart_button button alt' . ( esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) ) . ' ' . $classes . '">' . $before . esc_html( $text ) . $after . '</button>';
			}
		}
	}

	/**
	 * Add to cart link.
	 *
	 * @since 1.4.3
	 */
	public static function AddToCartLink( $product, $icon = '', $icon_position = 'after', $show_text = true, $custom_text = '' ) {
		$icon   = sp_render_icon( $icon, array( 'aria-hidden' => 'true' ) );
		$before = 'before' == $icon_position ? $icon : '';
		$after  = 'after' == $icon_position ? $icon : '';
		$text   = $show_text ? $product->add_to_cart_text() : '';
		$text   = ( '' !== $custom_text && $show_text ) ? $custom_text : $text;

		if ( $product ) {

			$quantity = sanitize_text_field( $_POST['quantity'] ?? 1 );

			$args = array(
				'quantity'   => absint( $quantity ),
				'class'      => implode(
					' ',
					array_filter(
						array(
							'button',
							'product_type_' . $product->get_type(),
							$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
							$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
						)
					)
				),
				'attributes' => array(
					'data-product_id'  => $product->get_id(),
					'data-product_sku' => $product->get_sku(),
					'aria-label'       => $product->add_to_cart_description(),
					'rel'              => 'nofollow',
				),
			);

			if ( isset( $args['attributes']['aria-label'] ) ) {
				$args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
			}

			if ( 'simple' == $product->get_type() ) {
				echo apply_filters(
					'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
					sprintf(
						'<a href="%s" data-quantity="%s" class="%s" %s>%s %s %s</a>',
						esc_url( $product->add_to_cart_url() ),
						esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
						esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
						isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
						$before,
						esc_html( $text ),
						$after,
					),
					$product,
					$args
				);
			} elseif ( 'variable' == $product->get_type() ) {

				echo apply_filters(
					'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
					sprintf(
						'<a href="%s" data-quantity="%s" class="%s" %s>%s %s %s</a>',
						esc_url( $product->add_to_cart_url() ),
						esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
						esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
						isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
						$before,
						esc_html( $text ),
						$after,
					),
					$product,
					$args
				);
			} elseif ( 'grouped' == $product->get_type() ) {

				echo apply_filters(
					'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
					sprintf(
						'<a href="%s" data-quantity="%s" class="%s" %s>%s %s %s</a>',
						esc_url( $product->add_to_cart_url() ),
						esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
						esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
						isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
						$before,
						esc_html( $text ),
						$after,
					),
					$product,
					$args
				);
			}
		}
	}

	/**
	 * Whitelist HTML tags.
	 *
	 * @since 1.4.11
	 */
	public static function whitelist_html_tags( $tag, $default = 'div' ) {
		$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'figure', 'caption', 'div', 'article' );
		return in_array( $tag, $allowed_tags ) ? $tag : $default;
	}

}

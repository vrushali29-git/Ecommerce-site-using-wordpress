<?php
/**
 * Compare.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

class Compare {
	/**
	 * Init hooks.
	 *
	 * @since 1.0.0
	 */
	public static function init() {

		if ( self::is_compare_builder() || self::is_static_compare() ) {

			$compare_location_single  = sp_get_module_settings( 'compare', 'location_product_page' )['value'] ?? '';
			$compare_location_archive = sp_get_module_settings( 'compare', 'location_products_loop' )['value'] ?? '';

			self::setup_cookie();
			add_action( 'init', array( __CLASS__, 'add_rewrite_rules' ) );
			add_filter( 'query_vars', array( __CLASS__, 'add_query_vars' ) );
			add_filter( 'body_class', array( __CLASS__, 'filter_body_class' ) );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 99 );
			add_filter( 'shoppress_frontend_localize', array( __CLASS__, 'filter_localize' ), 10, 1 );
			add_shortcode( 'shoppress-compare-page', array( __CLASS__, 'render_compare_page' ) );
			add_filter( 'template_include', array( __CLASS__, 'full_template' ) );

			add_action( 'wp_ajax_AddCompare', array( __CLASS__, 'add_to_compare_by_ajax' ) );
			add_action( 'wp_ajax_nopriv_AddCompare', array( __CLASS__, 'add_to_compare_by_ajax' ) );

			if ( ! empty( $compare_location_single ) && false === sp_is_template_active( 'single' ) ) {
				add_action( $compare_location_single, array( __CLASS__, 'compare_output' ) );
			}

			if ( ! empty( $compare_location_archive ) ) {
				add_action( $compare_location_archive, array( __CLASS__, 'compare_output' ) );
			}
		}

		add_filter( 'shoppress/elementor/widgets', array( __CLASS__, 'add_compare_widgets' ), 9 );
	}

	/**
	 * Return compare page url.
	 *
	 * @since 1.2.0
	 *
	 * @return mixed
	 */
	public static function get_compare_page_url() {
		$page_id = sp_get_module_settings( 'compare', 'compare_page' )['value'] ?? '';

		return get_permalink( $page_id );
	}

	/**
	 * Add to the compare.
	 *
	 * @param int $product_id
	 *
	 * @since 1.2.0
	 */
	public static function add_to_compare( $product_id = null ) {

		$user_id = get_current_user_id();
		if ( $user_id ) {
			$compare = get_user_meta( $user_id, 'shoppress_woo_compare', true );
			if ( ! $compare ) {
				update_user_meta( $user_id, 'shoppress_woo_compare', array( $product_id ) );
			} else {
				if ( ! in_array( $product_id, $compare ) ) {
					$product = wc_get_product( $product_id );
					if ( is_a( $product, '\WC_Product' ) ) {
						$compare[] = $product_id;
					}
				}

				update_user_meta( $user_id, 'shoppress_woo_compare', $compare );
			}

			return $compare;
		}

		return false;
	}

	/**
	 * Remove from the compare.
	 *
	 * @param int $product_id
	 *
	 * @since 1.2.0
	 */
	public static function remove_from_compare( $product_id = null ) {

		$user_id = get_current_user_id();
		if ( $user_id ) {
			$compare = get_user_meta( $user_id, 'shoppress_woo_compare', true );
			if ( $compare ) {
				if ( in_array( $product_id, $compare ) ) {
					foreach ( $compare as $k => $v ) {
						if ( $product_id == $v ) {
							unset( $compare[ $k ] );
						}
					}
				}
				update_user_meta( $user_id, 'shoppress_woo_compare', $compare );
			}

			return $compare;
		}

		return false;
	}

	/**
	 * Add to the compare.
	 *
	 * @since 1.2.0
	 */
	public static function add_to_compare_by_ajax() {

		check_ajax_referer( 'shoppress_nonce', 'nonce' );
		$static_compare_title         = sp_get_module_settings( 'compare', 'add_label' );
		$static_compare_remove_title  = sp_get_module_settings( 'compare', 'remove_label' );
		$limit_the_number_of_products = sp_get_module_settings( 'compare', 'limit_the_number_of_products', 4 );
		$social_share                 = sp_get_module_settings( 'compare', 'social_share' );
		$social_media                 = sp_get_module_settings( 'compare', 'social_media' );
		$compare                      = self::get_compare_product_ids();
		$user_id                      = get_current_user_id();

		$Product = sanitize_text_field( $_POST['product_id'] );

		if ( 'clear_all' === $Product ) {
			if ( $user_id ) {
				update_user_meta( $user_id, 'shoppress_woo_compare', array() );
			}

			wp_send_json_success(
				array(
					'message'       => __( 'Products removed from compare.', 'shop-press' ),
					'message_type'  => 'success',
					'compare_label' => $static_compare_title,
					'status'        => 'no',
					'button_url'    => self::get_compare_page_url(),
					'button_text'   => __( 'View Compare', 'shop-press' ),
					'popup_html'    => '',
					'items_count'   => 0,
				),
				200
			);
		}

		$is_in_compare = in_array( $Product, $compare );

		if ( is_array( $compare ) && count( $compare ) >= $limit_the_number_of_products && ! $is_in_compare ) {
			wp_send_json_error(
				array(
					'status'       => 'no',
					'message'      => sprintf(
						__( 'You can add up to %s products', 'shop-press' ),
						$limit_the_number_of_products
					),
					'message_type' => 'error',
					'button_url'   => self::get_compare_page_url(),
					'button_text'  => __( 'View Compare', 'shop-press' ),
				),
				500
			);
		}

		if ( is_user_logged_in() ) {

			if ( ! $is_in_compare ) {
				$action = static::add_to_compare( $Product );
				$result = 'added';
			} elseif ( $is_in_compare ) {
				$action = static::remove_from_compare( $Product );
				$result = 'removed';
			}

			if ( is_wp_error( $action ) ) {
				wp_send_json_error(
					array(
						'message'      => $action->get_error_message(),
						'message_type' => 'error',
					),
					500
				);
			}
		} elseif ( ! $is_in_compare ) {

				$result = 'removed';
		} elseif ( $is_in_compare ) {
			$result = 'added';
		}

		$popup_html = '';
		if ( sp_get_module_settings( 'compare', 'display_popup_after_update' ) ) {
			$popup_html = self::compare_popup_output();
		}

		$updated_compare_items = self::get_compare_product_ids();

		if ( 'added' === $result ) {

			wp_send_json_success(
				array(
					'message'       => __( 'Product added to compare', 'shop-press' ),
					'message_type'  => 'success',
					'compare_label' => $static_compare_remove_title,
					'status'        => 'yes',
					'button_url'    => self::get_compare_page_url(),
					'button_text'   => __( 'View Compare', 'shop-press' ),
					'popup_html'    => $popup_html,
					'items_count'   => count( $updated_compare_items ),
					'share_link'    => $social_share && ! empty( $social_media ) ? self::get_compare_share_link( $updated_compare_items ) : '',
				),
				200
			);
		} elseif ( 'removed' === $result ) {

			wp_send_json_success(
				array(
					'message'       => __( 'Product removed from compare.', 'shop-press' ),
					'message_type'  => 'success',
					'compare_label' => $static_compare_title,
					'status'        => 'no',
					'button_url'    => self::get_compare_page_url(),
					'button_text'   => __( 'View Compare', 'shop-press' ),
					'popup_html'    => $popup_html,
					'items_count'   => count( $updated_compare_items ),
					'share_link'    => $social_share && ! empty( $social_media ) ? self::get_compare_share_link( $updated_compare_items ) : '',
				),
				200
			);
		}

		wp_die();
	}

	/**
	 * Adds the compare widgets to the existing list of widgets.
	 *
	 * @param array $widgets The array of widgets.
	 *
	 * @since  1.2.0
	 *
	 * @return array The updated array of widgets.
	 */
	public static function add_compare_widgets( $widgets ) {
		$widgets['single-compare'] = array(
			'editor_type' => 'single',
			'class_name'  => 'Compare',
			'is_pro'      => false,
			'path_key'    => 'single-product/compare',
		);

		$widgets['compare'] = array(
			'editor_type' => 'compare',
			'class_name'  => 'ProductsCompare',
			'is_pro'      => false,
			'path_key'    => 'compare/compare',
		);

		$widgets['loop-compare'] = array(
			'editor_type' => 'loop',
			'class_name'  => 'LoopBuilder\Compare',
			'is_pro'      => false,
			'path_key'    => 'loop/compare',
		);

		return $widgets;
	}

	/**
	 * Enqueue Scripts.
	 *
	 * @since 1.0.0
	 */
	public static function enqueue() {
		$builder_id = sp_get_template_settings( 'compare', 'page_builder' );
		if ( self::is_compare_page() && 'block_editor' === sp_get_builder_type( $builder_id ) ) {

			add_filter(
				'styler/block_editor/post_id',
				function () {
					return sp_get_template_settings( 'compare', 'page_builder' );
				}
			);
		}

		wp_enqueue_script( 'sp-compare' );
		wp_enqueue_style( 'sp-pr-general' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'sp-pr-general-rtl' );
		}

		$is_display_popup = sp_get_module_settings( 'compare', 'display_popup_after_update' );
		if ( self::is_compare_page() || $is_display_popup ) {
			wp_enqueue_style( 'sp-compare' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-compare-rtl' );
			}
		}
	}

	/**
	 * Check compare builder.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_compare_builder() {
		return sp_get_template_settings( 'compare', 'status' ) && sp_get_template_settings( 'compare', 'page_builder' ) ? true : false;
	}

	/**
	 * Check static compare.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_static_compare() {
		return sp_get_module_settings( 'compare', 'status' ) && ! self::is_compare_builder() ? true : false;
	}

	/**
	 * Return compare page id.
	 *
	 * @since 1.4.0
	 *
	 * @return int
	 */
	public static function get_compare_page_id() {

		return sp_get_module_settings( 'compare', 'compare_page', false )['value'] ?? null;
	}

	/**
	 * Return compare page slug.
	 *
	 * @since 1.4.0
	 *
	 * @return string
	 */
	public static function get_compare_page_slug() {

		$compare_page_id = self::get_compare_page_id();
		$compare         = $compare_page_id ? get_post( $compare_page_id ) : false;

		return is_a( $compare, '\WP_Post' ) ? $compare->post_name : 'compare';
	}

	/**
	 * Check compare page.
	 *
	 * @since 1.0.0
	 */
	public static function is_compare_page() {
		$compare_page_id = self::get_compare_page_id();
		return get_the_ID() === $compare_page_id;
	}

	/**
	 * body class.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function filter_body_class( $classes ) {

		if ( self::is_compare_page() ) {

			return array_merge( $classes, array( 'woocommerce woocommerce-page ' ) );
		}

		return $classes;
	}

	/**
	 * Set Cookie.
	 *
	 * @since 1.0.0
	 */
	public static function setup_cookie() {
		if ( ! isset( $_COOKIE['shoppress_woo_compare'] ) ) {
			@setcookie( 'shoppress_woo_compare', json_encode( array( 0 ) ), time() + ( 10 * 365 * 24 * 60 * 60 ), '/' );
		}
	}

	/**
	 * Update Localize.
	 *
	 * @since 1.0.0
	 */
	public static function filter_localize( $localize ) {

		$compare_title        = sp_get_module_settings( 'compare', 'add_label' );
		$compare_remove_title = sp_get_module_settings( 'compare', 'remove_label' );
		$localize['compare']  = array(
			'add'                        => array(
				'message'       => __( 'Product added to compare', 'shop-press' ),
				'compare_label' => $compare_remove_title,
				'button_text'   => __( 'View Compare', 'shop-press' ),
				'button_url'    => self::get_compare_page_url(),
				'status'        => 'yes',
			),
			'remove'                     => array(
				'message'       => __( 'Product removed from compare', 'shop-press' ),
				'compare_label' => $compare_title,
				'button_text'   => __( 'View Compare', 'shop-press' ),
				'button_url'    => self::get_compare_page_url(),
				'status'        => 'no',
			),
			'display_popup_after_update' => sp_get_module_settings( 'compare', 'display_popup_after_update' ),
		);

		return $localize;
	}

	/**
	 * Get the compare.
	 *
	 * @since 1.0.0
	 *
	 * @return array compare.
	 */
	public static function get_compare_product_ids( $limit = -1 ) {

		$user_id        = get_current_user_id();
		$compare_cookie = sanitize_text_field( $_COOKIE['shoppress_woo_compare'] ?? '' );

		if ( $user_id ) {
			$Compare = get_user_meta( $user_id, 'shoppress_woo_compare', true );
			if ( $Compare == '' ) {
				$Compare = array();
			}
		} else {
			$Compare = isset( $compare_cookie ) ? json_decode( $_COOKIE['shoppress_woo_compare'] ?? '', true ) : false;
		}

		if ( ! empty( $Compare ) && is_array( $Compare ) ) {

			// check if is product
			foreach ( $Compare as $key => $ID ) {
				$product = wc_get_product( $ID );
				if ( ! is_a( $product, '\WC_Product' ) ) {
					unset( $Compare[ $key ] );
				}
			}

			if ( is_numeric( $limit ) && -1 !== $limit ) {
				return array_slice( $Compare, 0, $limit );
			}
			return $Compare;
		}
		return array();
	}

	/**
	 * Get compare products.
	 *
	 * @since 1.3.7
	 *
	 * @return array
	 */
	public static function get_compare_products( $limit = -1 ) {

		$products    = array();
		$product_ids = self::get_compare_product_ids( $limit );
		if ( ! empty( $product_ids ) && is_array( $product_ids ) ) {

			foreach ( $product_ids as $product_id ) {

				$products[ $product_id ] = wc_get_product( $product_id );
			}
		}

		return $products;
	}

	/**
	 * Compare output.
	 *
	 * @since 1.0.0
	 */
	public static function compare_output() {
		global $shoppress_cross_sell_popup;
		// Do not display the variations in cross-sell popup
		if ( $shoppress_cross_sell_popup ) {
			return;
		}

		$product_id  = get_the_ID();
		$Comparelist = self::get_compare_product_ids();
		$Comparelist = is_array( $Comparelist ) && in_array( $product_id, $Comparelist ) ? 'yes' : 'no';
		$label       = 'yes' === $Comparelist ? sp_get_module_settings( 'compare', 'remove_label' ) : sp_get_module_settings( 'compare', 'add_label' );
		?>

		<div class="sp-single-compare sp-product-compare sp-compare-button sp-compare-button-wrapper" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-status="<?php echo esc_attr( $Comparelist ); ?>">
			<div class="sp-compare-button-loop">
				<span class="sp-compare-icon">
					<?php echo wp_kses( sp_get_svg_icon( 'compare' ), sp_allowd_svg_tags() ); ?>
				</span>
				<?php if ( ! empty( $label ) ) { ?>
					<span class="sp-compare-label"><?php echo wp_kses( $label, wp_kses_allowed_html( 'post' ) ); ?></span>
				<?php } ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Compare popup output.
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	public static function compare_popup_output() {
		ob_start();
			echo '<div class="sp-compare-popup shoppress-compare-popup-wrap">
					<div class="sp-popup-con"> <div class="sp-compare-close-popup"> <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" > <path d="M9.79.21a.717.717,0,0,1,0,1.014L5.956,5.057,9.674,8.776A.717.717,0,0,1,8.66,9.79L4.942,6.072l-3.6,3.6A.717.717,0,0,1,.326,8.66l3.6-3.6L.21,1.34A.717.717,0,0,1,1.224.326L4.942,4.043,8.776.21A.717.717,0,0,1,9.79.21Z" fill="#7f8da0" /> </svg> </div>';
				echo self::render_compare_page();

			echo '</div>
				</div>';
		return ob_get_clean();
	}

	/**
	 * Render compare page content.
	 *
	 * @param array $atts
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	public static function render_compare_page( $atts = array() ) {
		ob_start();

		wp_enqueue_style( 'sp-compare' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'sp-compare-rtl' );
		}

		wp_enqueue_script( 'sp-compare' );

		do_action( 'shoppress/before_compare' );

		echo '<div id="shoppress-wrap" class="shoppress-wrap">';
		if ( self::is_compare_builder() ) {

			$builder_id = sp_get_template_settings( 'compare', 'page_builder' );

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo sp_get_builder_content( $builder_id );
		} else {

			load_template( sp_get_template_path( 'compare/compare-template' ) );
		}
		echo '</div>';

		do_action( 'shoppress/after_compare' );

		return ob_get_clean();
	}

	/**
	 * Get full template.
	 *
	 * @since 1.2.0
	 */
	public static function full_template( $template ) {

		if ( self::is_compare_page() ) {
			$template = sp_get_template_path( 'full-template' );
		}

		return $template;
	}

	/**
	 * Add rewrite rules
	 *
	 * @since 1.4.0
	 *
	 * @return void
	 */
	public static function add_rewrite_rules() {

		$compare_page_slug = self::get_compare_page_slug();

		add_rewrite_rule( $compare_page_slug . '/view/(.*)/page/(.*)?', 'index.php?view=$matches[1]&paged=$matches[2]&pagename=' . $compare_page_slug, 'top' );
		add_rewrite_rule( $compare_page_slug . '/view/(.*)?', 'index.php?view=$matches[1]&pagename=' . $compare_page_slug, 'top' );
	}

	/**
	 * Add query vars
	 *
	 * @param array $vars
	 *
	 * @since 1.4.0
	 *
	 * @return array
	 */
	public static function add_query_vars( $vars ) {

		$vars['view'] = 'view';

		return $vars;
	}

	/**
	 * Return Compare Share Link
	 *
	 * @param int[] $product_ids
	 *
	 * @since 1.4.0
	 *
	 * @return string
	 */
	public static function get_compare_share_link( $product_ids ) {

		$url = self::get_compare_page_url();

		$compare_key = base64_encode( implode( ',', $product_ids ) );
		$url        .= 'view/' . $compare_key . '/';

		return $url;
	}
}

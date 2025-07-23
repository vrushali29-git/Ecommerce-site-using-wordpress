<?php
/**
 * Mobile Panel.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

use ShopPress\Helpers\ThumbnailGenerator;
use ShopPress\Modules\Compare;
use ShopPress\Modules\Wishlist\Main as Wishlist;

class MobilePanel {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {

		if ( ! sp_get_module_settings( 'mobile_panel', 'status' ) ) {
			return;
		}

		add_action( 'wp_footer', array( __CLASS__, 'mobile_panel_template' ), 99 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'body_class', array( __CLASS__, 'body_class' ) );
		add_filter( 'woocommerce_add_to_cart_fragments', array( __CLASS__, 'fragment' ), 99, 1 );
	}

	/**
	 * enqueue style.
	 *
	 * @since 1.0.0
	 */
	public static function enqueue() {
		wp_enqueue_style( 'sp-mobile-panel' );
	}

	/**
	 * Fragment.
	 *
	 * @since 1.2.0
	 */
	public static function fragment( $fragments ) {
		ob_start();
		woocommerce_mini_cart();
		$mini_cart = ob_get_clean();

		$fragments['div.widget_shopping_cart_content'] = $mini_cart;
		$fragments['cart_hash']                        = WC()->cart->get_cart_hash();
		return $fragments;
	}

	/**
	 * Mobile Panel Template.
	 *
	 * @since 1.0.0
	 */
	public static function mobile_panel_template() {

		$wishlist_page_url = Wishlist::get_wishlist_page_url();
		$compare_page_url  = Compare::get_compare_page_url();
		$items             = sp_get_module_settings( 'mobile_panel', 'items' );
		$defaults          = array(
			'shop'       => array(
				'icon'  => 'mobp-shop',
				'label' => __( 'Shop', 'shop-press' ),
			),
			'wishlist'   => array(
				'icon'  => 'mobp-wishlist',
				'label' => __( 'Wishlist', 'shop-press' ),
			),
			'compare'    => array(
				'icon'  => 'mobp-compare',
				'label' => __( 'Compare', 'shop-press' ),
			),
			'my_account' => array(
				'icon'  => 'mobp-account',
				'label' => __( 'Account', 'shop-press' ),
			),
			'cart'       => array(
				'icon'  => 'mobp-cart',
				'label' => __( 'Cart', 'shop-press' ),
			),
		);
		?>
		<div class="sp-mobile-panel">
			<?php
			if ( is_array( $items ) ) :
				foreach ( $items as $item ) :

					$type = $item['type']['value'] ?? false;
					if ( ! $type ) {
						continue;
					}
					$icon          = $item['icon'] ?? false;
					$label         = $item['label'] ?? '';
					$default_icon  = $defaults[ $type ]['icon'] ?? '';
					$default_label = $defaults[ $type ]['label'] ?? '';

					$icon_html = is_array( $icon ) && isset( $icon['id'] ) ? ThumbnailGenerator::instance()->image_resize_output( $icon['id'], array( 24, 24 ), '', '', true ) : sp_get_svg_icon( $default_icon );
					$label     = $label ? $label : $default_label;

					$classes      = '';
					$more_content = '';
					$link         = '';
					switch ( $type ) {
						case 'shop':
							$link = get_permalink( wc_get_page_id( 'shop' ) );
							break;
						case 'wishlist':
							if ( sp_is_module_active( 'wishlist' ) && $wishlist_page_url ) {

								$count        = Wishlist::get_total_wishlist_products();
								$link         = $wishlist_page_url;
								$more_content = '<span class="sp-wishlist-items-count">' . esc_html( $count ) . ' </span>';
								$classes     .= ' sp-mp-item-wishlist';
							}
							break;
						case 'compare':
							if ( sp_is_module_active( 'compare' ) && $compare_page_url ) {
								$link = $compare_page_url;
							}
							break;
						case 'my_account':
							$link = get_permalink( wc_get_page_id( 'myaccount' ) );
							break;
						case 'cart':
							if ( ! sp_get_module_settings( 'catalog_mode', 'status' ) ) {
								$link             = get_permalink( wc_get_page_id( 'cart' ) );
								$cart_items_count = ! empty( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
								$more_content     = '<span class="sp-cart-items-count">' . esc_html( $cart_items_count ) . ' </span>';
								$classes         .= ' sp-mp-item-cart';
							}
							break;
						case 'custom':
							$link = $item['link'] ?? '';
							break;
					}

					if ( empty( $link ) ) {

						continue;
					}
					?>
					<div class="sp-mp-item <?php echo esc_attr( $classes ); ?>">
						<a href="<?php echo esc_url( $link ); ?>">
							<?php echo sp_kses_post( $icon_html ); ?>
							<p><?php esc_html_e( $label, 'shop-press' ); ?></p>
							<?php echo $more_content . sp_kses_post( $more_content ); ?>
						</a>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Mobile Panel Template.
	 *
	 * @since 1.0.0
	 */
	public static function body_class( $classes ) {
		return array_merge( $classes, array( 'sp-active-mobile-panel' ) );
	}
}

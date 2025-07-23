<?php
/**
 * Menu Wishlist.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules\Wishlist;

defined( 'ABSPATH' ) || exit;

use ShopPress\Modules\Wishlist\Main;

class MenuWishlist {

	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {
		if ( ! sp_get_module_settings( 'wishlist_general_settings', 'wishlist_menu_module' ) ) {
			return;
		}

		$display_as        = sp_get_module_settings( 'wishlist_general_settings', 'display_wishlist_menu_as' )['value'] ?? 'dropdown';
		$display_as_drawer = 'drawer' === $display_as ? true : false;

		add_filter( 'wp_nav_menu_items', array( __CLASS__, 'add_to_menu' ), 999, 2 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 99 );

		if ( $display_as_drawer ) {
			add_action( 'wp_footer', array( __CLASS__, 'drawer' ) );
		}
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0.3
	 */
	public static function enqueue() {
		wp_enqueue_style( 'sp-menu-wishlist' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'sp-menu-wishlist-rtl' );
		}
	}

	/**
	 * Return icon.
	 *
	 * @since 1.2.0
	 *
	 * @return mixed
	 */
	public static function get_icon() {
		$icon_type = sp_get_module_settings( 'wishlist_general_settings', 'icon_type', 'icons_pack' );

		if ( 'icons_pack' === $icon_type ) {
			$icon = sp_get_module_settings( 'wishlist_general_settings', 'icons_pack' );

			return sp_render_icon_pack( $icon );
		} elseif ( 'custom_icon' === $icon_type ) {
			return sp_get_module_settings( 'wishlist_general_settings', 'custom_icon' )['id'] ?? false;
		}
	}

	/**
	 * html.
	 *
	 * @since 1.0.3
	 */
	public static function html() {
		$wishlist          = Main::get_wishlist_products();
		$count             = count( $wishlist );
		$button_icon       = self::get_icon();
		$display_as        = sp_get_module_settings( 'wishlist_general_settings', 'display_wishlist_menu_as' )['value'] ?? 'dropdown';
		$display_as_drawer = 'drawer' === $display_as ? true : false;

		?>
		<a href="#">
			<div class="sp-header-wishlist <?php echo $display_as_drawer ? 'sp-drawer-click' : ''; ?>" <?php echo $display_as_drawer ? 'data-drawer-target="sp-mc-drawer-menu_wishlist"' : ''; ?>>
				<?php echo ! is_numeric( $button_icon ) ? wp_kses( $button_icon, sp_allowd_svg_tags() ) : wp_get_attachment_image( $button_icon, array( 20, 20, true ) ); ?>
				<span class="sp-wishlist-items-count"><?php echo esc_html( $count ); ?></span>
			</div>
		</a>
		<?php
		if ( 'dropdown' === $display_as ) {
			echo self::dropdown( $wishlist ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Wishlist content.
	 *
	 * @since 1.0.3
	 */
	public static function wishlist_content( $wishlist = null ) {

		if ( is_null( $wishlist ) ) {

			$wishlist = Main::get_wishlist_products();
		}

		$content = '';
		if ( empty( $wishlist ) ) {
			$content .= '<div class="sp-wishlist-empty">';
			$content .= __( 'Your wishlist is currently empty', 'shop-press' );
			$content .= '</div>';
		} else {
			foreach ( $wishlist as $item => $wishlist_item_data ) {
				$product_id   = $wishlist_item_data['product_id'] ?? false;
				$wishlist_key = $wishlist_item_data['wishlist_key'] ?? false;
				$product      = wc_get_product( $item );
				$price        = $product->get_price_html();
				$image        = $product->get_image( array( 64, 64 ) );
				$title        = $product->get_title();
				$product_id   = $product->get_id();

				$content .= '<div class="sp-wishlist-pr">
							<span class="sp-wishlist-img">
								' . wp_kses_post( $image ) . '
							</span>
							<div class="sp-mmceta">
								<a class="sp-wishlist-pr-title" href="' . esc_url( get_permalink( $product_id ) ) . '">
									' . esc_html( $title ) . '
								</a>
								<div class="sp-wishlist-p-wrap">
									<span class="sp-wishlist-price">
										' . wp_kses_post( $price ) . '
									</span>
								</div>
							</div>
							<div class="sp-rmf-wishlist" data-product_id="' . esc_attr( $product_id ) . '" data-wishlist_key="' . esc_attr( $wishlist_key ) . '">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 36 36"> <g transform="translate(-830 -22)"> <circle cx="18" cy="18" r="18" transform="translate(830 22)" fill="#f1f2f3"/> <path d="M9.79.21a.717.717,0,0,1,0,1.014L5.956,5.057,9.674,8.776A.717.717,0,1,1,8.66,9.79L4.942,6.072l-3.6,3.6A.717.717,0,0,1,.326,8.66l3.6-3.6L.21,1.34A.717.717,0,1,1,1.224.326L4.942,4.043,8.776.21A.717.717,0,0,1,9.79.21Z" transform="translate(843 35)" fill="#959ca7"/> </g> </svg>
							</div>
						</div>';
			}
		}

		$content         .= '<div class="sp-wishlist-link">';
			$content     .= '<a href="' . esc_url( Main::get_wishlist_page_url() ) . '">';
				$content .= __( 'View Wishlist', 'shop-press' );
			$content     .= '</a>';
		$content         .= '</div>';

		return $content;
	}

	/**
	 * Dropdown.
	 *
	 * @since 1.2.0
	 */
	public static function drawer() {
		$wishlist        = Main::get_wishlist_products();
		$drawer_position = sp_get_module_settings( 'wishlist_general_settings', 'drawer_position' )['value'] ?? 'left';
		$close_icon      = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="17" height="17" viewBox="0 0 17 17"> <g> </g> <path d="M9.207 8.5l6.646 6.646-0.707 0.707-6.646-6.646-6.646 6.646-0.707-0.707 6.646-6.646-6.647-6.646 0.707-0.707 6.647 6.646 6.646-6.646 0.707 0.707-6.646 6.646z" fill="#000000" /> </svg>';
		?>
		<div id="sp-mc-drawer-menu_wishlist" class="sp-mini-wishlist sp-drawer sp-mini-wishlist-drawer <?php echo esc_attr( $drawer_position ? 'float-' . $drawer_position : 'float-left' ); ?>">
			<div class="sp-drawer-content-wrap">
				<div class="sp-drawer-close">
					<?php echo wp_kses( $close_icon, sp_allowd_svg_tags() ); ?>
				</div>
				<div class="sp-drawer-content-title"><?php esc_html_e( 'Wishlist', 'shop-press' ); ?></div>
				<div class="sp-wishlist-items">
					<?php echo self::wishlist_content( $wishlist ); ?>
				</div>

			</div>
		</div>
		<?php
	}

	/**
	 * Dropdown.
	 *
	 * @since 1.0.3
	 */
	public static function dropdown( $wishlist ) {

		$content      = '';
		$content     .= '<div class="sp-wishlist-dd" id="sp-wishlist-dd-menu_wishlist">';
			$content .= '<div class="sp-mini-wishlist"><div class="sp-wishlist-title">' . esc_html( 'Wishlist', 'shop-press' ) . '</div>';

			$content .= self::wishlist_content( $wishlist );
		$content     .= '</div></div>';

		return $content;
	}

	/**
	 * Add to menu
	 *
	 * @param array  $items
	 * @param object $args
	 *
	 * @since 1.0.3
	 *
	 * @return string
	 */
	public static function add_to_menu( $items, $args ) {
		$menu_wishlist_id = sp_get_module_settings( 'wishlist_general_settings', 'select_wishlist_menu' )['value'] ?? '';
		if ( isset( $args->menu ) ) {
			$menu_obj = wp_get_nav_menu_object ($args->menu);
			$menu_id = is_object( $args->menu ) ? $args->menu->term_id : $args->menu;
			if ( $menu_id == $menu_wishlist_id || $menu_obj->slug == $menu_id ) {

				ob_start();
				echo '<li class="sp-wishlist-items menu-item sp-open-dropdown">';
				self::html();
				echo '</li>';
				$items .= ob_get_clean();
			}
		}

		return $items;
	}
}

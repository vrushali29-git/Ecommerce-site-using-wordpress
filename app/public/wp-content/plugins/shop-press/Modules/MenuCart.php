<?php
/**
 * Menu Cart.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

class MenuCart {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {
		$menu_cart_id = sp_get_module_settings( 'menu_cart', 'cart_menu_id' )['value'] ?? false;
		if ( ! $menu_cart_id ) {
			return;
		}

		$display_as        = sp_get_module_settings( 'menu_cart', 'display_cart_as' )['value'] ?? 'dropdown';
		$display_as_drawer = 'drawer' === $display_as ? true : false;

		add_filter( 'wp_nav_menu_items', array( __CLASS__, 'add_to_menu' ), 999, 2 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 99 );
		add_filter( 'woocommerce_add_to_cart_fragments', array( __CLASS__, 'fragment' ), 99, 1 );

		if ( $display_as_drawer ) {
			add_action( 'wp_footer', array( __CLASS__, 'drawer' ) );
		}
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0.0
	 */
	public static function enqueue() {
		wp_enqueue_style( 'sp-mini-cart' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'sp-mini-cart-rtl' );
		}

		wp_enqueue_script( 'sp-menu-cart' );
	}

	/**
	 * Return icon.
	 *
	 * @since 1.2.0
	 *
	 * @return string|int
	 */
	public static function get_icon() {
		$icon_type = sp_get_module_settings( 'menu_cart', 'icon_type', 'icons_pack' );

		if ( 'icons_pack' === $icon_type ) {
			$icon = sp_get_module_settings( 'menu_cart', 'icons_pack' );

			return sp_render_icon_pack( $icon );
		} elseif ( 'custom_icon' === $icon_type ) {
			return sp_get_module_settings( 'menu_cart', 'custom_icon' )['id'] ?? false;
		}
	}

	/**
	 * Fragment.
	 *
	 * @since 1.0.0
	 */
	public static function fragment( $fragments ) {
		$cart_items_count  = ! empty( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
		$button_icon       = self::get_icon();
		$display_as        = sp_get_module_settings( 'menu_cart', 'display_cart_as' )['value'] ?? 'dropdown';
		$display_as_drawer = 'drawer' === $display_as ? true : false;
		ob_start();
		?>
		<a href="#">
			<div class="sp-header-cart <?php echo $display_as_drawer ? 'sp-drawer-click' : ''; ?>" <?php echo $display_as_drawer ? 'data-drawer-target="sp-mc-drawer-menu_cart"' : ''; ?>>
				<?php echo ! is_numeric( $button_icon ) ? wp_kses( $button_icon, sp_allowd_svg_tags() ) : wp_get_attachment_image( $button_icon, array( 20, 20, true ) ); ?>
				<span class="sp-cart-items-count"><?php esc_html_e( $cart_items_count ); ?></span>
			</div>
		</a>

		<?php
		$count = ob_get_clean();

		ob_start();
		if ( 'dropdown' === $display_as ) {
			self::dropdown();
		}
		$dropdown = ob_get_clean();

		ob_start();
		woocommerce_mini_cart();
		$mini_cart = ob_get_clean();

		$fragments['.sp-header-cart'] = $count;
		$fragments['.sp-mc-dd']       = $dropdown;
		$fragments['cart_hash']       = WC()->cart->get_cart_hash();
		if ( $display_as_drawer ) {

			ob_start();
			if ( $display_as_drawer ) {
				self::drawer();
			}
			$fragments['#sp-mc-drawer-menu_cart'] = ob_get_clean();
		}
		$fragments['div.widget_shopping_cart_content'] = $mini_cart;
		return $fragments;
	}

	/**
	 * Fragment.
	 *
	 * @since 1.0.0
	 */
	public static function cart_content() {
		include sp_get_template_path( 'mini-cart/style-1' );
	}

	/**
	 * display cart header.
	 *
	 * @since 1.2.0
	 */
	public static function display_header_cart() {
		$cart_items_count  = ! empty( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
		$display_as        = sp_get_module_settings( 'menu_cart', 'display_cart_as' )['value'] ?? 'dropdown';
		$display_as_drawer = 'drawer' === $display_as ? true : false;
		$button_icon       = self::get_icon();
		?>
		<a href="#">
			<div class="sp-header-cart <?php echo $display_as_drawer ? 'sp-drawer-click' : ''; ?>" <?php echo $display_as_drawer ? 'data-drawer-target="sp-mc-drawer-menu_cart"' : ''; ?>>
				<?php echo ! is_numeric( $button_icon ) ? wp_kses( $button_icon, sp_allowd_svg_tags() ) : wp_get_attachment_image( $button_icon, array( 20, 20, true ) ); ?>
				<span class="sp-cart-items-count"><?php esc_html_e( $cart_items_count ); ?></span>
			</div>
		</a>
		<?php
		if ( 'dropdown' === $display_as ) {
			self::dropdown();
		}
	}

	/**
	 * Dropdown.
	 *
	 * @since 1.2.0
	 */
	public static function drawer() {

		$drawer_position = sp_get_module_settings( 'menu_cart', 'drawer_position' )['value'] ?? 'left';
		$close_icon      = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="17" height="17" viewBox="0 0 17 17"> <g> </g> <path d="M9.207 8.5l6.646 6.646-0.707 0.707-6.646-6.646-6.646 6.646-0.707-0.707 6.646-6.646-6.647-6.646 0.707-0.707 6.647 6.646 6.646-6.646 0.707 0.707-6.646 6.646z" fill="#000000" /> </svg>';
		?>
		<div id="sp-mc-drawer-menu_cart" class="sp-drawer sp-mini-cart-drawer <?php echo esc_attr( $drawer_position ? 'float-' . $drawer_position : 'float-left' ); ?>">
			<div class="sp-drawer-content-wrap">
				<div class="sp-drawer-close">
					<?php echo wp_kses( $close_icon, sp_allowd_svg_tags() ); ?>
				</div>

				<?php self::cart_content(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Dropdown.
	 *
	 * @since 1.0.0
	 */
	public static function dropdown() {
		?>
			<div class="sp-mc-dd" id="sp-mc-dd-menu_cart">
				<?php self::cart_content(); ?>
			</div>
		<?php
	}

	/**
	 * Add to menu
	 *
	 * @param array  $items
	 * @param object $args
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function add_to_menu( $items, $args ) {
		$menu_cart_id = sp_get_module_settings( 'menu_cart', 'cart_menu_id' )['value'] ?? false;
		if ( isset( $args->menu ) ) {
			$menu_obj = wp_get_nav_menu_object ($args->menu);
			$menu_id = is_object( $args->menu ) ? $args->menu->term_id : $args->menu;
			if ( $menu_id == $menu_cart_id || $menu_obj->slug == $menu_id) {
				ob_start();
				echo '<li class="sp-mc-item menu-item sp-open-dropdown">';
					self::display_header_cart();
				echo '</li>';
				$items .= ob_get_clean();
			}
		}

		return $items;
	}
}

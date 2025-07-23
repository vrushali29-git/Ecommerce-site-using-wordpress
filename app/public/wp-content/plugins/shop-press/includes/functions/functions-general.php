<?php

defined( 'ABSPATH' ) || exit;

use ShopPress\Settings;
use ShopPress\Templates;
use ShopPress\Modules;

/**
 * Check if ShopPress Pro is active.
 *
 * @since 1.2.0
 *
 * @return bool
 */
function is_active_shoppress_pro() {

	if ( ! function_exists( 'is_plugin_active' ) ) {

		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	return function_exists( 'shoppress_pro' ) || is_plugin_active( 'shop-press-pro/shop-press-pro.php' );
}

/**
 * Retrieves the component settings for a specific component.
 *
 * @since 1.2.0
 *
 * @param string $template_id
 * @param string $setting_key
 * @param mixed  $default
 *
 * @return mixed The value of the component setting.
 */
function sp_get_template_settings( $template_id, $setting_key = null, $default = '' ) {
	return Settings::get_template_settings( $template_id, $setting_key, $default );
}

/**
 * Retrieves the module settings for a specific module.
 *
 * @since 1.2.0
 *
 * @param string $module_id
 * @param string $setting_key
 * @param mixed  $default
 *
 * @return mixed The value of the module setting.
 */
function sp_get_module_settings( $module_id, $setting_key = null, $default = '' ) {
	return Settings::get_module_settings( $module_id, $setting_key, $default );
}

/**
 * Checks if a component is active.
 *
 * @since 1.2.0
 *
 * @param string $template_id
 *
 * @return bool
 */
function sp_is_template_active( $template_id ) {
	return Settings::is_template_active( $template_id );
}

/**
 * Renders the product collection loop.
 *
 * @since 1.4.0
 *
 * @param string $loop_name
 * @param array  $attrs
 */
function sp_render_product_collection( $loop_name, $attrs = array() ) {
	$content = (
		new Templates\ProductCollection( $loop_name, $attrs )
	)->render();

	return $content;
}

/**
 * Renders the shop products.
 *
 * @since 1.4.0
 *
 * @param array $attrs
 */
function sp_render_shop_products( $attrs = array() ) {
	$content = (
		new Templates\ProductShop( $attrs )
	)->render();

	return $content;
}

/**
 * Get sp_builder_type meta from post.
 *
 * @since 1.4.0
 *
 * @param int|string $builder_id
 *
 * @return string
 */
function sp_get_builder_type( $builder_id ) {
	return Templates\Utils::get_builder_type( $builder_id );
}

/**
 * Renders the dynamic icon.
 *
 * @since 1.4.0
 *
 * @param string $icon
 * @param array  $attrs
 */
function sp_render_icon( $icon, $attrs = array() ) {
	return Templates\Utils::render_icon( $icon, $attrs );
}

/**
 * Renders add to cart button.
 *
 * @since 1.4.0
 */
function sp_add_to_cart_button( $product, $icon = '', $icon_position = 'after', $show_text = true, $custom_text = '' ) {
	return Templates\Utils::AddToCartButton( $product, $icon, $icon_position, $show_text, $custom_text );
}

/**
 * Renders add to cart link.
 *
 * @since 1.4.3
 */
function sp_add_to_cart_link( $product, $icon = '', $icon_position = 'after', $show_text = true, $custom_text = '' ) {
	return Templates\Utils::AddToCartLink( $product, $icon, $icon_position, $show_text, $custom_text );
}

/**
 * Returns the template custom type.
 *
 * @since 1.4.0
 *
 * @param int $post_id
 *
 * @return string
 */
function sp_get_template_type( $post_id ) {
	return get_post_meta( $post_id, 'custom_type', true );
}

/**
 * Checks if a module is active.
 *
 * @since 1.2.0
 *
 * @param string $module_id
 *
 * @return bool
 */
function sp_is_module_active( $module_id ) {
	return Settings::is_module_active( $module_id );
}

/**
 * Returns the URL path of the current page.
 *
 * @since 1.2.0
 *
 * @return string
 */
function sp_get_url_path() {
	return trim( parse_url( add_query_arg( array() ), PHP_URL_PATH ), '/' );
}

/**
 * Returns the last segment of a URL.
 *
 * @since 1.2.0
 *
 * @return string
 */
function sp_get_url_last_segment() {
	return basename( parse_url( sp_get_url_path(), PHP_URL_PATH ) );
}

/**
 * Checks if the current page is a wishlist page.
 *
 * @since 1.2.0
 *
 * @return bool
 */
function is_sp_wishlist_page() {
	return Modules\Wishlist\Main::is_wishlist_page();
}

/**
 * Checks if the current page is a compare page.
 *
 * @since 1.2.0
 *
 * @return bool
 */
function is_sp_compare_page() {
	return Modules\Compare::is_compare_page();
}

/**
 * Checks if the current request is an AJAX request for the quick view feature.
 *
 * @since 1.2.0
 *
 * @return bool
 */
function is_sp_quick_view_ajax() {
	return Modules\QuickView::is_quick_view_ajax();
}

/**
 * Returns an array of the allowd SVG tags.
 *
 * @since 1.2.0
 *
 * @return array
 */
function sp_allowd_svg_tags() {
	$allowed_tags = array(
		'svg'      => array(
			'version' => true,
			'xmlns'   => true,
			'width'   => true,
			'height'  => true,
			'viewbox' => true,
			'fill'    => true,
			'stroke'  => true,
		),
		'path'     => array(
			'fill'              => true,
			'd'                 => true,
			'data-name'         => true,
			'transform'         => true,
			'fill-rule'         => true,
			'stroke'            => true,
			'stroke-linecap'    => true,
			'stroke-linejoin'   => true,
			'stroke-width'      => true,
			'stroke-miterlimit' => true,
		),
		'rect'     => array(
			'height'            => true,
			'width'             => true,
			'data-name'         => true,
			'fill'              => true,
			'id'                => true,
			'stroke'            => true,
			'stroke-width'      => true,
			'stroke-linecap'    => true,
			'stroke-linejoin'   => true,
			'stroke-miterlimit' => true,
			'transform'         => true,
		),
		'clipPath' => array(
			'id' => true,
		),
		'g'        => array(
			'clip-path'    => true,
			'data-name'    => true,
			'id'           => true,
			'transform'    => true,
			'fill'         => true,
			'stroke-width' => true,
		),
		'defs'     => array(),
		'img'      => array(
			'src' => true,
		),
		'i'        => array(
			'class' => true,
		),
		'time'     => array(
			'class'    => true,
			'datetime' => true,
		),
	);

	return apply_filters( 'sp_allowd_svg_tags', $allowed_tags );
}

/**
 * Return template path
 *
 * @param string $template
 * @param bool   $is_pro
 *
 * @since 1.2.0
 *
 * @return string
 */
function sp_get_template_path( $template, $is_pro = false ) {

	$filename = SHOPPRESS_PATH . 'public/templates/' . $template . '.php';
	if ( $is_pro && defined( 'SHOPPRESS_PRO_PATH' ) ) {
		$filename = SHOPPRESS_PRO_PATH . 'public/templates/' . $template . '.php';
	}

	/**
	 * Filters the path of the template.
	 *
	 * @since 1.2.5
	 *
	 * @param string $filename
	 * @param string $template
	 * @param bool   $is_pro
	 */
	return apply_filters( 'shoppress/get_template_path', $filename, $template, $is_pro );
}

/**
 * Render the given icon pack.
 *
 * @since 1.2.0
 *
 * @param string $name
 *
 * @return void
 */
function sp_render_icon_pack( $name ) {

	$parts = explode( '-', $name );
	$pack  = $parts[0];

	$icons_path = SHOPPRESS_PATH . 'public/dist/icons-pack/' . $pack . '/' . $name . '.svg';

	if ( file_exists( $icons_path ) ) {

		$svg = file_get_contents( $icons_path );

		return $svg;
	}
}

/**
 * Render builder content.
 *
 * @since 1.2.5
 *
 * @param int $builder_id
 */
function sp_get_builder_content( $builder_id ) {
	return Templates\Render::get_builder_content( $builder_id );
}

/**
 * Load the builder template.
 *
 * @since 1.2.5
 *
 * @param string $template_file
 * @param array  $args
 * @param bool   $is_pro
 *
 * @return void
 */
function sp_load_builder_template( $template_file, $args = array(), $is_pro = false ) {
	return Templates\Render::load_builder_template( $template_file, $args, $is_pro );
}

/**
 * Retrieves the content of an SVG icon file.
 *
 * @since 1.2.6
 *
 * @param string $name The name of the icon file.
 *
 * @return string
 */
function sp_get_svg_icon( $name ) {
	$icons_folder = SHOPPRESS_PATH . 'public/images/icons/';

	/**
	 * Filters the svg folder url.
	 *
	 * @since 1.2.6
	 *
	 * @param string $icons_folder
	 */
	$icons_url = apply_filters( 'shoppress/svg_icons_folder_url', $icons_folder );

	$svg_url = $icons_url . $name . '.svg';

	/**
	 * Filters the svg file.
	 *
	 * @since 1.2.6
	 *
	 * @param string $svg_url
	 * @param string $name
	 */
	$svg = apply_filters( 'shoppress/icons/svg_content', $svg_url, $name );

	return file_exists( $svg ) ? file_get_contents( $svg ) : '';
}

/**
 * Product navigation render html
 *
 * @param int    $post_id
 * @param string $product_hover_details
 *
 * @since 1.2.6
 *
 * @return string
 */
function sp_navigation_render_html( $post_id = null, $product_hover_details = 'none' ) {

	$output = '';
	if ( ! $post_id || $product_hover_details == 'none' ) {
		return $output;
	}

	// Product Details
	$product = wc_get_product( $post_id );

	// Wrapper Width
	switch ( $product_hover_details ) {
		case 'thumb':
			$wrapper_width = 'width: 62px;';
			break;
		case 'title-thumb':
			$wrapper_width = 'width: 172px;';
			break;
		case 'title-thumb-price':
			$wrapper_width = 'width: 172px;';
			break;
		default:
			$wrapper_width = '';
	}

	// Thumbnail URL
	if ( get_the_post_thumbnail_url( $post_id ) ) {
		$thumbnail_url = get_the_post_thumbnail_url( $post_id, 'full' );
	} else {
		$thumbnail_url = '';
	}

	$output .= '<div class="sp-navigation-details" style=" opacity: 0;visibility: hidden;' . $wrapper_width . '">';

	// Title
	if ( $product_hover_details == 'title' ) {
		$output .= '<span class="sp-nav-det-title">' . get_the_title( $post_id ) . '</span>';
	}

	// Thumbnail
	if ( $product_hover_details == 'thumb' ) {
		$output .= '<img style="width: 100px;" src="' . esc_url( $thumbnail_url ) . '" alt="' . esc_attr( get_the_title( $post_id ) ) . '"/>';
	}

	// Title + Thumbnail
	if ( $product_hover_details == 'tthumb' ) {
		$output .= '<div class="sp-navigation-tthumb"><img src="' . esc_url( $thumbnail_url ) . '" alt="' . esc_attr( get_the_title( $post_id ) ) . '"/><span class="sp-nav-det-title">' . get_the_title( $post_id ) . '</span></div>';
	}

	// Title + Price
	if ( $product_hover_details == 'tprice' ) {
		$output .= '<div class="sp-navigation-tprice"><span class="sp-nav-det-title">' . get_the_title( $post_id ) . '</span><span>' . $product->get_price_html() . '</span></div>';
	}

	// Title + Thumbnail + Price
	if ( $product_hover_details == 'tthumbprice' ) {
		$output .= '<div class="sp-navigation-tthumbprice"><img src="' . esc_url( $thumbnail_url ) . '" alt="' . esc_attr( get_the_title( $post_id ) ) . '"/><div><span class="sp-nav-det-title">' . get_the_title( $post_id ) . '</span><span>' . $product->get_price_html() . '</span></div></div>';
	}

	$output .= '</div>';

	return $output;
}

/**
 * Return product discount string
 *
 * @param \WC_Product $product
 *
 * @since 1.3.1
 *
 * @return string
 */
function sp_get_product_discount( $product ) {

	$percentage = '';
	$product    = is_numeric( $product ) ? wc_get_product( $product ) : $product;
	if ( ! is_a( $product, '\WC_Product' ) ) {
		return $percentage;
	}

	if ( $product->is_type( 'variable' ) ) {
		$percentages = array();

		$prices = $product->get_variation_prices();

		foreach ( $prices['price'] as $key => $price ) {
			if ( $prices['regular_price'][ $key ] !== $price ) {
				$percentages[] = round( 100 - ( floatval( $prices['sale_price'][ $key ] ) / floatval( $prices['regular_price'][ $key ] ) * 100 ) );
			}
		}

		$percentage = count( $percentages ) ? max( $percentages ) . '%' : '';
	} elseif ( $product->is_type( 'grouped' ) ) {
		$percentages = array();

		$children_ids = $product->get_children();
		foreach ( $children_ids as $child_id ) {
			$child_product = wc_get_product( $child_id );

			$regular_price = (float) $child_product->get_regular_price();
			$sale_price    = (float) $child_product->get_sale_price();

			if ( $sale_price != 0 || ! empty( $sale_price ) ) {
				$percentages[] = round( 100 - ( $sale_price / $regular_price * 100 ) );
			}
		}
		$percentage = count( $percentages ) ? max( $percentages ) . '%' : '';
	} else {
		$regular_price = (float) $product->get_regular_price();
		$sale_price    = (float) $product->get_sale_price();

		if ( $sale_price != 0 || ! empty( $sale_price ) ) {
			$percentage = round( 100 - ( $sale_price / $regular_price * 100 ) ) . '%';
		}
	}

	return $percentage;
}

/**
 * Kses icon
 *
 * @param string $icon
 *
 * @since 1.3.1
 *
 * @return string
 */
function sp_kses_icon( $icon ) {
	return sp_kses_post( $icon );
}

/**
 * Kses post
 *
 * @param string $html
 *
 * @since 1.3.1
 *
 * @return string
 */
function sp_kses_post( $html ) {
	$allowed_tags = array_merge(
		wp_kses_allowed_html( 'post' ),
		sp_allowd_svg_tags()
	);

	return wp_kses( $html, $allowed_tags );
}

/**
 * Generate random string
 *
 * @param integer $length
 *
 * @since 1.3.3
 *
 * @return string
 */
function sp_generate_random_string( $length = 10 ) {
	$characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen( $characters );
	$randomString     = '';
	for ( $i = 0; $i < $length; $i++ ) {
		$randomString .= $characters[ rand( 0, $charactersLength - 1 ) ];
	}

	return $randomString;
}

/**
 * Returns image url.
 *
 * @param object $image
 *
 * @since 1.3.4
 *
 * @return string
 */
function sp_get_image_url( $image ) {
	$image     = sp_get_image_data( $image );
	$image_url = isset( $image['id'] ) ? wp_get_attachment_url( $image['id'] ) : '';

	return $image_url;
}

/**
 * Returns image url.
 *
 * @param object $image
 *
 * @since 1.3.4
 *
 * @return object
 */
function sp_get_image_data( $image ) {

	if ( is_array( $image ) ) {
		return $image;
	}

	$json_image = json_decode( $image ?? '', true );

	if ( $json_image !== null ) {
		$image = $json_image;
	}

	return $image;
}

/**
 * Returns social share links.
 *
 * @since 1.3.7
 *
 * @return array
 */
function sp_get_social_share_links() {

	$url  = '{url}';
	$text = '{text}';

	$share_url_patterns = array(
		'x'         => array(
			'title'  => __( 'X', 'shop-press' ),
			'social' => 'x',
			'url'    => 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $text,
			'icon'   => sp_get_svg_icon( 'wishlist_share_twitter' ),
		),
		'facebook'  => array(
			'title'  => __( 'Facebook', 'shop-press' ),
			'social' => 'facebook',
			'url'    => 'http://www.facebook.com/sharer.php?u=' . $url,
			'icon'   => sp_get_svg_icon( 'wishlist_share_facebook' ),
		),
		'email'     => array(
			'title'  => __( 'Email', 'shop-press' ),
			'social' => 'email',
			'url'    => 'mailto:""?subject=""',
			'icon'   => sp_get_svg_icon( 'wishlist_share_email' ),
		),
		'linkedin'  => array(
			'title'  => __( 'linkedin', 'shop-press' ),
			'social' => 'linkedin',
			'url'    => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url,
			'icon'   => sp_get_svg_icon( 'wishlist_share_linkedin' ),
		),
		'reddit'    => array(
			'title'  => __( 'Reddit', 'shop-press' ),
			'social' => 'reddit',
			'url'    => 'https://reddit.com/submit?url=' . $url . '&title=' . $text,
			'icon'   => sp_get_svg_icon( 'wishlist_share_reddit' ),
		),
		'pinterest' => array(
			'title'  => __( 'Pinterest', 'shop-press' ),
			'social' => 'pinterest',
			'url'    => 'http://pinterest.com/pin/create/button/?url=' . $url,
			'icon'   => sp_get_svg_icon( 'wishlist_share_pinterest' ),
		),
		'tumblr'    => array(
			'title'  => __( 'Tumblr', 'shop-press' ),
			'social' => 'tumblr',
			'url'    => 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=' . $url . '&title=' . $text,
			'icon'   => sp_get_svg_icon( 'wishlist_share_tumblr' ),
		),
		'whatsapp'  => array(
			'title'  => __( 'WhatsApp', 'shop-press' ),
			'social' => 'whatsapp',
			'url'    => 'https://api.whatsapp.com/send?text=' . $text . ' ' . $url,
			'icon'   => sp_get_svg_icon( 'wishlist_share_whatsapp' ),
		),
	);

	return $share_url_patterns;
}

/**
 * Checks for elementor editor.
 *
 * @since 1.4.3
 *
 * @return bool
 */
function sp_is_elementor_editor() {
	return did_action( 'elementor/loaded' ) && \Elementor\Plugin::$instance->editor->is_edit_mode();
}


/**
 * Whitelist HTML tags..
 *
 * @since 1.4.11
 */
function sp_whitelist_html_tags( $tag, $default ) {
	return Templates\Utils::whitelist_html_tags( $tag, $default );
}

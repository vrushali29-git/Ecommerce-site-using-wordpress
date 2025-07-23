<?php
/**
 * Return to shop.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

$shop_page_url  = wc_get_page_permalink( 'shop' );
$custom_link    = $args['custom_link'] ? $args['custom_link'] : $shop_page_url;
$link_text_html = '';
$button_style   = isset( $args['button_style'] ) ? $args['button_style'] : 'icon';
$btn_icon       = sp_render_icon( $args['btn_icon'] ?? '' );
$btn_text       = isset( $args['custom_text'] ) ? esc_html__( $args['custom_text'], 'shop-press' ) : esc_html__( 'Return To Shop', 'shop-press' );

switch ( $button_style ) {
	case 'text':
		$link_text_html = $btn_text;
		break;
	case 'icon_button':
		$link_text_html = $btn_icon . $btn_text;
		break;
	case 'button_icon':
		$link_text_html = $btn_text . $btn_icon;
		break;
	case 'icon':
	default:
		$link_text_html = $btn_icon;
		break;
}
?>
<div class="sp-return-to-shop">
	<a class="button" href="<?php echo esc_url( $custom_link ); ?>"><?php echo wp_kses_post( $link_text_html ); ?></a>
</div>

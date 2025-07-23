<?php
defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use ShopPress\Templates\Utils;

$settings = $this->get_settings_for_display();

$filters         = $settings['filter_repeaters'] ?? array();
$display_as      = $settings['display_as'] ?? '';
$drawer_label    = $settings['drawer_label'] ?? '';
$drawer_icon     = $settings['drawer_icon'] ?? '';
$drawer_title    = $settings['drawer_title'] ?? '';
$drawer_position = $settings['drawer_position'] ?? 'left';
$button_style    = $settings['button_style'] ?? 'text_icon';


if ( empty( $filters ) ) {
	return;
}

if ( is_shop() || is_archive() || $this->is_editor() ) {
	echo '<div class="sp-sidebar sp-product-filters ' . ( 'drawer' === $display_as ? 'sp-drawer sp-product-filter-drawer float-' . $drawer_position : '' ) . '">';

	if ( 'drawer' === $display_as ) {

		$close_icon  = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="17" height="17" viewBox="0 0 17 17"> <g> </g> <path d="M9.207 8.5l6.646 6.646-0.707 0.707-6.646-6.646-6.646 6.646-0.707-0.707 6.646-6.646-6.647-6.646 0.707-0.707 6.647 6.646 6.646-6.646 0.707 0.707-6.646 6.646z" fill="#000000" /></svg>';
		$drawer_icon = sp_render_icon(
			$drawer_icon,
			array(
				'aria-hidden' => 'true',
				'class'       => 'sp-product-filters-icon',
			)
		);

		$btn_text = '<span class="sp-product-filters-label">' . $drawer_label . '</span>';
		$btn_icon = ! is_numeric( $drawer_icon ) ? $drawer_icon : '';
		switch ( $button_style ) {
			case 'text':
				$btn_html = $btn_text;
				break;
			case 'icon_text':
				$btn_html = $btn_icon . $btn_text;
				break;
			case 'text_icon':
				$btn_html = $btn_text . $btn_icon;
				break;
			case 'icon':
			default:
				$btn_html = $btn_icon;
				break;
		}

		echo '<div class="sp-drawer-click sp-open-product-filters-drawer">'
			. sp_kses_post( $btn_html )
		. '</div>'
		. '<div class="sp-drawer-content-wrap">'
			. '<div class="sp-drawer-close">' . wp_kses( $close_icon, sp_allowd_svg_tags() ) . '</div>'
			. '<div class="sp-drawer-content-title">' . esc_html__( $drawer_title, 'shop-press' ) . '</div>';
	}
	$show_clear_all = 'yes' === ( $settings['show_clear_all'] ?? false );
	if ( $show_clear_all ) {

		echo '<button class="sp-reset-filters">' . __( 'Clear All', 'shop-press' ) . '</button>';
	}

	foreach ( $filters as $value ) {

		switch ( $value['filter_repeater_select'] ) {
			case 'layered_nav':
				require __DIR__ . '/layerd_nav_dummy.php';
				break;


			case 'rating_filter':
				require __DIR__ . '/rating_dummy.php';
				break;

			case 'price_filter':
				require __DIR__ . '/price_filter_dummy.php';
				break;

			case 'stock_filter':
				require __DIR__ . '/stock_filter_dummy.php';
				break;
		}
	}
	echo '</div>';

	if ( 'drawer' === $display_as ) {
		echo '</div>';
	}
}

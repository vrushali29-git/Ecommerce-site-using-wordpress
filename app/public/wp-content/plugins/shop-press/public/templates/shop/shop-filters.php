<?php
/**
 * Shop Filters.
 *
 * @package ShopPress
 */

use ShopPress\Templates\Utils;

defined( 'ABSPATH' ) || exit;

$filters         = $args['filter_repeaters'] ?? array();
$display_as      = $args['display_as'] ?? '';
$mobile_drawer   = $args['mobile_drawer'] ?? '';
$drawer_label    = $args['drawer_label'] ?? '';
$drawer_icon     = $args['drawer_icon'] ?? '';
$drawer_title    = $args['drawer_title'] ?? '';
$drawer_position = $args['drawer_position'] ?? 'left';
$button_style    = $args['button_style'] ?? 'text_icon';

$display_drawer                = ( 'drawer' === $display_as || 'true' === $mobile_drawer );
$mobile_drawer_classes         = 'true' === $mobile_drawer ? 'sp-mobile-drawer' : '';
$mobile_drawer_content_classes = 'true' === $mobile_drawer ? 'sp-mobile-drawer-content' : '';
$drawer_classes                = $display_drawer ? 'sp-drawer sp-product-filter-drawer ' . $mobile_drawer_classes . ' float-' . $drawer_position : '';

if ( empty( $filters ) ) {
	return;
}

if ( is_shop() || is_archive() ) {
	echo '<div class="sp-sidebar sp-product-filters ' . $drawer_classes . '">';

	if ( $display_drawer ) {

		$close_icon  = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="17" height="17" viewBox="0 0 17 17" fill="#000000"><path d="M9.207 8.5l6.646 6.646-0.707 0.707-6.646-6.646-6.646 6.646-0.707-0.707 6.646-6.646-6.647-6.646 0.707-0.707 6.647 6.646 6.646-6.646 0.707 0.707-6.646 6.646z"/></svg>';
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
			. '<div class="sp-drawer-content-wrap ' . $mobile_drawer_content_classes . '">'
				. '<div class="sp-drawer-close">' . wp_kses( $close_icon, sp_allowd_svg_tags() ) . '</div>'
				. '<div class="sp-drawer-content-title">' . esc_html__( $drawer_title, 'shop-press' ) . '</div>';
	}

	$show_clear_all = 'yes' === ( $args['show_clear_all'] ?? false );
	if ( $show_clear_all ) {

		echo '<button class="sp-reset-filters">' . __( 'Clear All', 'shop-press' ) . '</button>';
	}

	foreach ( $filters as $value ) {

		switch ( $value['filter_repeater_select'] ) {
			case 'layered_nav_filters':
				$show_reset = (bool) ( $value['show_reset'] ?? false );
				if ( $show_reset ) {
					echo '<div class="sp-product-filters--nav-filters-with-clear-button">';
				}
					the_widget(
						'WC_Widget_Layered_Nav_Filters',
						array(
							'title' => $value['layered_nav_filters_title'],
						)
					);
				if ( $show_reset ) {
					echo '</div>';
				}
				break;

			case 'layered_nav':
				the_widget(
					'ShopPress_Widget_Layered_Nav',
					array(
						'title'        => $value['layered_nav_title'],
						'attribute'    => $value['attribute'],
						'display_type' => $value['attribute_display_type'],
					)
				);
				break;

			case 'rating_filter':
				the_widget(
					'WC_Widget_Rating_Filter',
					array(
						'title' => $value['rating_filter_title'],
					)
				);
				break;

			case 'price_filter':
				the_widget(
					'WC_Widget_Price_Filter',
					array(
						'title' => $value['price_filter_title'],
					)
				);

				break;

			case 'stock_filter':
				break;
		}
	}
	echo '</div>';

	if ( $display_drawer ) {
		echo '</div>';
	}
}

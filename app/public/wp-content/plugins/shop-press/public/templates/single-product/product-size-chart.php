<?php
/**
 * Product Size Chart.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Modules\SizeChart;

global $product;

$label = isset( $args['label'] ) && ! empty( $args['label'] ) ? $args['label'] : '';
$icon  = sp_render_icon( $args['icon'] ?? '' );

SizeChart::display_size_chart_button(
	array(
		'label' => $label,
		'icon'  => $icon ? $icon : ' ',
	)
);

<?php
/**
 * Brand attributes list.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Modules\VariationSwatches\Frontend;

$classes         = array();
$slider_attrs    = '';
$display_name    = $args['display_name'] ?? false;
$display_logo    = $args['display_logo'] ?? true;
$hide_empty      = $args['hide_empty'] ?? true;
$selected_brands = $args['brands'] ?? array();
$carousel        = $args['carousel'] ?? false;
$attributes      = Frontend::get_brand_attributes( $hide_empty, $selected_brands );
$brands          = $attributes['terms'] ?? array();

if ( empty( $brands ) ) {
	return;
}

if ( $carousel == 'true' ) {

	$columns    = $args['carousel_columns'] ?? 4;
	$speed      = $args['slide_speed'] ?? 500;
	$arrows     = $args['show_controls'] ?? true;
	$autoplay   = $args['autoplay'] ?? true;
	$play_speed = $args['autoplay_speed'] ?? 3000;
	$loop       = $args['carousel_loop'] ?? false;
	$rows       = $args['slider_rows'] ?? 1;

	$default_breakpoints = array(
		array(
			'breakpoint' => 767,
			'settings'   => array(
				'slidesToShow' => 2,
			),
		),
		array(
			'breakpoint' => 480,
			'settings'   => array(
				'slidesToShow' => 1,
			),
		),
	);

	$slider_options = array(
		'slidesToShow'  => $columns,
		'rtl'           => is_rtl(),
		'speed'         => ! empty( $speed ) ? $speed : 500,
		'arrows'        => $arrows == 'true',
		'autoplay'      => $autoplay == 'true',
		'autoplaySpeed' => ! empty( $play_speed ) ? $play_speed : 3000,
		'rows'          => ! empty( $rows ) ? $rows : 1,
		'infinite'      => $loop == 'true',
		'responsive'    => $default_breakpoints,
	);

	$classes[]     = 'sp-slider sp-slider-style sp-brands-carousel';
	$slider_attrs .= ' data-slick="' . esc_attr( json_encode( $slider_options ) ) . '"';

	wp_enqueue_style( 'slick' );
	wp_enqueue_script( 'slick' );
}
?>

<div class="sp-brands sp-brands-attrs sp-brands-grid">
	<div class="sp-brands-items <?php echo implode( ' ', $classes ); ?>" <?php echo $slider_attrs; ?>>
		<?php
			echo Frontend::get_brand_attributes_output( $brands, $attributes['slug'], $display_name, $display_logo );
		?>
	</div>
</div>

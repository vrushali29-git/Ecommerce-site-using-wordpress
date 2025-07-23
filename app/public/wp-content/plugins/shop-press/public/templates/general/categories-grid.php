<?php
/**
 * Categories Grid.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

$number             = $args['number'] ?? '6';
$columns            = $args['columns'] ?? '4';
$new_tab            = $args['cat_new_tab'] ?? 'target=_blank';
$order              = $args['order'] ?? '';
$order_by           = $args['order_by'] ?? '';
$show_name          = $args['show_name'] ?? '';
$hide_subcat        = isset( $args['hide_subcat'] ) && $args['hide_subcat'] ? 0 : '';
$hide_empty_cat     = isset( $args['hide_empty_cat'] ) && $args['hide_empty_cat'] ? true : false;
$desired_categories = $args['desired_categories'] ?? '';

$terms = get_terms(
	'product_cat',
	array(
		'number'     => $number,
		'orderby'    => $order_by,
		'order'      => $order,
		'parent'     => $hide_subcat,
		'slug'       => $desired_categories,
		'hide_empty' => $hide_empty_cat,
	)
);

$attributes        = '';
$classes           = '';
$display_as_slider = 'true' === ( $args['carousel'] ?? 'no' ) ? true : false;
if ( $display_as_slider ) {

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

	$slider_attrs = array(
		'slidesToShow'  => $columns,
		'speed'         => ! empty( $speed ) ? $speed : 500,
		'rtl'           => is_rtl(),
		'arrows'        => $arrows == 'true',
		'autoplay'      => $autoplay == 'true',
		'autoplaySpeed' => ! empty( $play_speed ) ? $play_speed : 3000,
		'rows'          => ! empty( $rows ) ? $rows : 1,
		'infinite'      => $loop == 'true',
		'responsive'    => $default_breakpoints,
	);

	wp_enqueue_style( 'slick' );
	wp_enqueue_script( 'slick' );

	$attributes .= ' data-slick="' . esc_attr( json_encode( $slider_attrs ) ) . '"';
	$classes    .= ' sp-slider sp-slider-style';
} else {
	$classes .= " spcw-col-{$columns}";
}

?>
<div id="sp-products-categories-<?php echo esc_attr( wp_rand( 0, 999 ) ); ?>" class="sp-products-categories">
	<?php if ( $terms ) : ?>
		<div class="sp-product-categories-items <?php echo esc_attr( $classes ); ?>" <?php echo $attributes; ?>>
			<?php foreach ( $terms as $term ) : ?>
				<div class="sp-product-cat">
					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" <?php echo esc_attr( $new_tab ); ?> class="sp-products-categories-link">
						<div class="sp-categories-image-container">
							<?php
								$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
								$image        = wp_get_attachment_url( $thumbnail_id );
								$ext          = pathinfo( $image, PATHINFO_EXTENSION );

							if ( isset( $ext ) && 'svg' === $ext ) {
								$svg = file_get_contents( get_attached_file( $thumbnail_id ) );
								if ( $svg ) {
									echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput
								}
							} else {
								woocommerce_subcategory_thumbnail( $term );
							}
							?>
						</div>
						<?php if ( $show_name ) : ?>
							<h5><?php echo esc_html( $term->name ); ?></h5>
						<?php endif; ?>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

<?php
/**
 * Recently Viewed Products.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

$limit            = $args['limit'] ?? 4;
$columns          = $args['columns'] ?? 4;
$template_id      = $args['template_id'] ?? 0;
$carousel         = $args['carousel'] ?? false;
$custom_heading   = $args['custom_heading'] ?? false;
$products_heading = $args['products_heading'] ?? '';
$html_tag         = $args['heading_tag'] ?? 'h4';

$classes    = array( 'sp-products-loop-wrapper sp-recently-pr' );
$attributes = '';

$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array(); // @codingStandardsIgnoreLine
$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

if ( empty( $viewed_products ) ) {
	return;
}

if ( $template_id ) {

	global $sp_custom_loop_template_id;

	$sp_custom_loop_template_id = $template_id;

	add_filter( 'wc_get_template_part', array( 'ShopPress\Templates\ProductLoopContent', 'filter_loop_content' ), 10, 2 );
}

if ( $carousel ) {

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
		'rtl'           => is_rtl() ? true : false,
		'arrows'        => $arrows == 'true',
		'autoplay'      => $autoplay == 'true',
		'autoplaySpeed' => ! empty( $play_speed ) ? $play_speed : 3000,
		'rows'          => ! empty( $rows ) ? $rows : 1,
		'infinite'      => $loop == 'true',
		'responsive'    => $default_breakpoints,
	);

	$classes[]   = 'sp-products-slider sp-slider-style';
	$attributes .= ' data-spslider="' . esc_attr( json_encode( $slider_attrs ) ) . '"';

	wp_enqueue_style( 'slick' );
	wp_enqueue_script( 'slick' );
}

ob_start();

$query_args = array(
	'posts_per_page' => $limit,
	'no_found_rows'  => 1,
	'post_status'    => 'publish',
	'post_type'      => 'product',
	'post__in'       => $viewed_products,
	'orderby'        => 'post__in',
);

if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
	$query_args['tax_query'] = array(
		array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'outofstock',
			'operator' => 'NOT IN',
		),
	); // WPCS: slow query ok.
}

$r = new \WP_Query( apply_filters( 'woocommerce_recently_viewed_products_widget_query_args', $query_args ) );

if ( $custom_heading ) {
	?>
	<<?php echo sp_whitelist_html_tags( $html_tag, 'h4' ); ?> class="sp-products-heading">
		<?php echo esc_html( $products_heading ); ?>
	</<?php echo sp_whitelist_html_tags( $html_tag, 'h4' ); ?>>
	<?php
}

if ( $r->have_posts() ) {

	?>
	<div class="<?php echo implode( ' ', $classes ); ?>" <?php echo $attributes; ?>>
	<div class="woocommerce">
		<ul class="products columns-<?php esc_attr_e( $columns ); ?>">
		<?php
		while ( $r->have_posts() ) {
			$r->the_post();
			wc_get_template_part( 'content', 'product' );
		}
		?>
		</ul>
		</div>
	</div>
	<?php
} else {
	echo __( 'No products found', 'shop-press' );
}

wp_reset_postdata();

$content = ob_get_clean();

echo $content; // WPCS: XSS ok.

if ( $template_id ) {
	remove_filter( 'wc_get_template_part', array( 'ShopPress\Templates\ProductLoopContent', 'filter_loop_content' ), 10, 2 );
}

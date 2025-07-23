<?php
/**
 * Compare
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Modules\Compare;
use ShopPress\Elementor\ShopPressWidgets;
use ShopPress\Helpers\ThumbnailGenerator;

$compare_key = get_query_var( 'view' );
if ( $compare_key ) {

	$products    = array();
	$product_ids = explode( ',', base64_decode( $compare_key ) );
	foreach ( $product_ids as $product_id ) {

		$product = wc_get_product( $product_id );
		if ( ! is_a( $product, '\WC_Product' ) ) {
			continue;
		}
		$products[ $product_id ] = $product;
	}
} else {

	$products = Compare::get_compare_products();
}

if ( empty( $products ) ) {
	?>
		<div class="sp-compare-empty">
			<h2><?php echo __( 'Your compare list is empty', 'shop-press' ); ?></h2>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button return-to-shop"><?php echo __( 'Shop Now', 'shop-press' ); ?></a>
		</div>
	<?php
}

$product_ids = array_keys( $products );

$thumbnail_width  = sp_get_module_settings( 'compare', 'thumbnail_width' );
$thumbnail_height = sp_get_module_settings( 'compare', 'thumbnail_height' );
$fields_in_table  = sp_get_module_settings( 'compare', 'fields_in_table' );
$fields_in_table  = array_column( (array) $fields_in_table, 'value' );

$product = current( $products );
if ( $product ) {

	$attributes = array_filter( $product->get_attributes(), 'wc_attributes_array_filter_visible' );
	$r          = array();

	global $product;
	foreach ( $products as $product ) {

		if ( ! is_a( $product, '\WC_Product' ) ) {
			continue;
		}

		$product_id = $product->get_id();

		if ( ! $compare_key ) {

			$r[ $product_id ] = array(
				'c' => '<div class="sp-compare-page-button sp-compare-button-wrapper" data-status="yes" data-product_id="' . esc_attr( $product_id ) . '">
							<div class="sp-compare-button">
								<span class="compare-remove-item">
								<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="17" height="17" viewBox="0 0 17 17"> <g> </g> <path d="M9.207 8.5l6.646 6.646-0.707 0.707-6.646-6.646-6.646 6.646-0.707-0.707 6.646-6.646-6.647-6.646 0.707-0.707 6.647 6.646 6.646-6.646 0.707 0.707-6.646 6.646z" fill="#000000" /> </svg>
								</span>
							</div>
						</div>',
			);
		}

		if ( in_array( 'thumbnail', $fields_in_table ) ) {

			if ( $thumbnail_width && $thumbnail_height ) {

				$thumbnail_id = get_post_thumbnail_id( $product->get_id() );

				$r[ $product_id ]['thumbnail'] = ThumbnailGenerator::instance()->image_resize_output(
					$thumbnail_id,
					array(
						$thumbnail_width,
						$thumbnail_height,
					),
					'',
					'',
					true
				);
			} else {

				$r[ $product_id ]['thumbnail'] = $product->get_image();
			}
		}

		if ( in_array( 'title', $fields_in_table ) ) {
			$r[ $product_id ]['title'] = $product->get_title();
		}

		if ( in_array( 'categories', $fields_in_table ) ) {
			$r[ $product_id ]['categories'] = get_the_term_list( $product->get_id(), 'product_cat', '', ', ', '' );
		}

		if ( in_array( 'price', $fields_in_table ) ) {
			$r[ $product_id ]['price'] = wc_price( $product->get_price() );
		}

		if ( in_array( 'sku', $fields_in_table ) ) {
			$r[ $product_id ]['sku'] = $product->get_sku();
		}

		if ( in_array( 'ratings', $fields_in_table ) ) {

			$rating            = $product->get_average_rating();
			$count             = $product->get_rating_count();
			$rating_html       = wc_get_rating_html( $rating );
			$rating_html       = ! empty( $rating_html ) ? $rating_html : '<div class="star-rating" title="' . sprintf( __( 'Rated %s out of 5', 'shop-press' ), $rating ) . '">';
			$rr[ $product_id ] = array(
				__( 'Ratings', 'shop-press' )      => array(
					'h'     => $rating_html,
					'class' => 'ratings',
				),
				__( 'Expert Score', 'shop-press' ) => array(
					'h'     => $rating,
					'class' => 'ratings-average',
				),
			);
		}

		if ( in_array( 'add_to_cart', $fields_in_table ) ) {

			ob_start();
				sp_add_to_cart_link( $product );
			$add_to_cart = ob_get_clean();

			$rrr[ $product_id ] = array(
				'add_to_cart' => $add_to_cart,
			);
		}

		$product_attributes = array();
		foreach ( $attributes as $attribute ) {
			$values = array();

			if ( $attribute->is_taxonomy() ) {
				$attribute_taxonomy = $attribute->get_taxonomy_object();
				$attribute_values   = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

				foreach ( $attribute_values as $attribute_value ) {

					$value_name = esc_html( $attribute_value->name );
					$values[]   = $value_name;
				}
			} else {
				$values = $attribute->get_options();

				foreach ( $values as &$value ) {
					$value = make_clickable( esc_html( $value ) );
				}
			}

			$product_attributes[ 'attribute_' . sanitize_title_with_dashes( $attribute->get_name() ) ] = array(
				'label' => wc_attribute_label( $attribute->get_name() ),
				'value' => apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values ),
			);
		}

		$product_attributes = apply_filters( 'woocommerce_display_product_attributes', $product_attributes, $product );


		foreach ( $product_attributes as $attribute ) {
			$label = $attribute['label'];
			$value = $attribute['value'];

			$rr[ $product_id ][ $label ] = ! empty( $value ) ? $value : '-';
		}
	}

	if ( empty( $r ) ) {
		return;
	}

	$first_product = current( (array) $r );
	$rows          = array_keys( (array) $first_product );

	$rr_first_product = current( (array) $rr );
	$rr_rows          = array_keys( (array) $rr_first_product );

	$rrr_first_product = current( (array) $rrr );
	$rrr_rows          = array_keys( (array) $rrr_first_product );

	$class = '';

	?>
	<div class="sp-compare sp-compare-products-container">
		<table>
			<?php foreach ( $rows as $row_id ) : ?>
				<tr class="<?php echo esc_attr( $row_id ); ?>">
					<td>
						<?php do_action( 'shoppress/compare/table/tr/header', $row_id, $product_ids, ); ?>
					</td>
					<?php foreach ( $r as $product_id => $rows ) : ?>
						<td class="<?php echo esc_attr( "sp-c-pr-{$product_id}" ); ?>"><?php echo 'c' !== $row_id ? wp_kses_post( $rows[ $row_id ] ) : $rows[ $row_id ]; // PHPCS: XSS ok. ?></td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>

			<?php
			if ( $rr_rows ) {

				foreach ( $rr_rows as $row_id ) {
					?>
						<tr class="attribute <?php echo esc_attr( $class ); ?>">
							<td class="attribute-label"><?php echo esc_html( $row_id ); ?></td>
							<?php
							foreach ( $rr as $product_id => $rows ) {

								$class = is_array( $rows[ $row_id ] ) ? $rows[ $row_id ]['class'] : '';
								$html  = is_array( $rows[ $row_id ] ) ? $rows[ $row_id ]['h'] : $rows[ $row_id ];

								echo '<td class="attribute-value ' . esc_attr( $class . ' ' . "sp-c-pr-{$product_id}" ) . '">' . wp_kses_post( $html ) . '</td>';
							}
							?>
						</tr>
						<?php
				}
			}

			if ( $rrr_rows ) {

				foreach ( $rrr_rows as $rrr_rows_id ) :
					?>
					<tr class="<?php esc_attr_e( $rrr_rows_id ); ?>">
						<td></td>
						<?php foreach ( $rrr as $product_id => $rrr_rows ) : ?>
							<td class="<?php echo esc_attr( "sp-c-pr-{$product_id}" ); ?>"><?php echo wp_kses_post( $rrr_rows[ $rrr_rows_id ] ); ?></td>
						<?php endforeach; ?>
					</tr>
					<?php
				endforeach;
			}
			?>
		</table>
	</div>
	<?php
}

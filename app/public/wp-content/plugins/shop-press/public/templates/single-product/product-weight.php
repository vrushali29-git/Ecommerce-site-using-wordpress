<?php
/**
 * Product Weight.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product || empty( $product->get_weight() ) ) {
	return;
}
?>
<div class="sp-weight-wrapper">
	<?php
	if ( ! $product->is_type( 'variable' ) ) {
		echo wp_kses_post(
			wp_sprintf(
				'<span class="value">%s</span><sub class="unit">%s</sub>',
				$product->get_weight(),
				get_option( 'woocommerce_weight_unit' )
			)
		);
	} elseif ( $product->is_type( 'variable' ) ) {
		$variations = $product->get_available_variations();
		$weights    = array();

		// Loop through each variation
		foreach ( $variations as $variation ) {
			$variation_id  = $variation['variation_id'];
			$variation_obj = wc_get_product( $variation_id );

			// Check if the variation has weight
			if ( $variation_obj->has_weight() ) {
				$weights[] = $variation_obj->get_weight();
			}
		}

		$min = min( $weights );
		$max = max( $weights );

		if ( ! empty( $min ) && ! empty( $max ) ) {
			echo wp_kses_post(
				wp_sprintf(
					'<span class="value">%s - %s</span><sub class="unit">%s</sub>',
					$min,
					$max,
					get_option( 'woocommerce_weight_unit' )
				)
			);
		}
	}
	?>
</div>
<?php

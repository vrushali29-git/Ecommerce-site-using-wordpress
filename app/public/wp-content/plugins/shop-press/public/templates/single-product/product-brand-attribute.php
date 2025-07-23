<?php
/**
 * Product brand attribute.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Modules\VariationSwatches\Frontend;

global $product;

$attributes = $product->get_attributes();

if ( ! $attributes ) {
	return;
}

$display_name = $args['display_name'] ?? false;
$display_logo = $args['display_logo'] ?? true;
$brand_label  = $args['brand_label'] ?? '';
?>

<div class="sp-brands sp-brands-attrs sp-brands-grid sp-single-brands">
	<?php if ( $brand_label ) : ?>
		<div class="sp-pr-brand-label">
			<?php echo esc_html( $brand_label ); ?>
		</div>
	<?php endif; ?>

	<div class="sp-brands-items">
		<?php
		foreach ( $attributes as $attribute ) {
			$attribute_data = wc_get_attribute( $attribute->get_id() );

			if ( $attribute_data->type === 'brand' ) {

				$terms = wc_get_product_terms( $product->get_id(), $attribute_data->slug, array( 'fields' => 'all' ) );

				echo Frontend::get_brand_attributes_output( $terms, $attribute_data->slug, $display_name, $display_logo );

				break;
			}
		}
		?>
	</div>
</div>

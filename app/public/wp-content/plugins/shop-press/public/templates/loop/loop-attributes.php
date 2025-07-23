<?php
/**
 * Loop Attributes.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attribute            = $args['attribute_taxonomy'] ?? false;
$attribute_taxonomies = wc_get_attribute_taxonomies( $attribute );
$attribute_data       = $attribute_taxonomies[ "id:{$attribute}" ] ?? false;
if ( ! $attribute_data ) {
	return;
}

$taxonomy = "pa_{$attribute_data->attribute_name}";
if ( ! $attribute || ! taxonomy_exists( $taxonomy ) ) {
	return;
}

$product_attributes     = $product->get_attributes()[ $taxonomy ] ?? false;
$product_attributes_ids = is_a( $product_attributes, '\WC_Product_Attribute' ) ? $product_attributes->get_options() : array();
if ( empty( $product_attributes_ids ) ) {
	return;
}

$args = array(
	'id'        => 'attribute_' . sanitize_title( $attribute ) . '-' . $product->get_id(),
	'attribute' => $taxonomy,
	'product'   => $product,
	// 'name' => '',
);
?>
<div class="sp-product-attributes">
	<?php
	echo ( new ShopPress\Modules\VariationSwatches\Frontend() )->swatch_display_loop_html(
		'',
		$args,
		$attribute_data->attribute_type,
		'swatch_design_default',
		false
	);
	?>
</div>

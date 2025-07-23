<?php
/**
 * Product Title.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;
global $product;

$html_tag = $args['tag'] ?? 'h1';
?>

<div class="sp-title-wrapper">
	<<?php echo sp_whitelist_html_tags( $html_tag, 'h1' ); ?> class="product_title entry-title">
	<?php echo esc_html( $product->get_title() ); ?>
	</<?php echo sp_whitelist_html_tags( $html_tag, 'h1' ); ?>>
</div>

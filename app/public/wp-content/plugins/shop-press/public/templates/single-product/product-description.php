<?php
/**
 * Product Short Description.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

?>
<div class="sp-description-wrapper">
	<p class="sp-description">
		<?php echo wp_kses_post( $product->get_short_description() ); ?>
	</p>
</div>

<?php
/**
 * Product Breadcrumb.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="sp-breadcrumbs-wrapper">
	<div class="sp-breadcrumbs">
		<?php woocommerce_breadcrumb( array( 'delimiter' => ' &nbsp;/&nbsp; ' ) ); ?>
	</div>
</div>

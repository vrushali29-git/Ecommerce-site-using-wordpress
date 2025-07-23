<?php
/**
 * Call for price.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="sp-call-for-price">
	<a href="tel:<?php esc_attr_e( $args['btn_phone_number'] ); ?>" class="button"><?php esc_html_e( $args['btn_text'] ); ?></a>
</div>

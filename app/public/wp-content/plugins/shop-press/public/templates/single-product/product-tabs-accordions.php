<?php
/**
 * Single Product tabs accordions
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs   = apply_filters( 'woocommerce_product_tabs', array() );
$open_first_tab = $args['open_first_tab'] ?? true;
if ( ! empty( $product_tabs ) ) :
	$i = 0; ?>

	<div class="sp-accordions-container sp-product-tabs-accordions-container woocommerce-tabs wc-tabs-wrapper">
		<?php
		foreach ( $product_tabs as $key => $product_tab ) :
			++$i;
			?>
			<div class="sp-accordion-item sp-accordion-item-<?php echo esc_attr( $key ); ?> <?php echo $open_first_tab && 1 == $i ? 'open' : ''; ?>">
				<div class="sp-accordion-item-header">
					<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
					<div class="sp-accordion-item-header-icon"><?php echo wp_kses( sp_get_svg_icon( 'accordion_tabs_arrow' ), sp_allowd_svg_tags() ); ?></div>
				</div>
				<div class="sp-accordion-item-content">
					<?php
					if ( isset( $product_tab['callback'] ) ) {
						call_user_func( $product_tab['callback'], $key, $product_tab );
					}
					?>
				</div>
			</div>
		<?php endforeach; ?>

		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div>

<?php endif; ?>

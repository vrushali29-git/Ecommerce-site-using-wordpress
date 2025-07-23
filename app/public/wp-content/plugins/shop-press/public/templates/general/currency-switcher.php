<?php
/**
 * Currency Switcher.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;
$args['format'] = empty( $args['format'] ) ? '%name%' : $args['format'];
?>

<div id="sp-currency-swithcer">
	<?php echo do_shortcode( '[currency_switcher format="' . esc_html( $args['format'] ) . '" switcher_style="' . $args['switcher_style'] . '"]' ); ?>
</div>

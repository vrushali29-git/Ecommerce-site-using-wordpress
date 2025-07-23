<?php
/**
 * Flash Sales Countdown.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $post, $product;

$before_icon = '';
$after_icon  = '';

if ( isset( $args['label'] ) ) {
	$label = $args['label'];
} else {
	$label = __( 'ٔNew', 'shop-press' );
}

if ( isset( $args['show_up_to'] ) ) {
	$show_up_to = $args['show_up_to'];
} else {
	$show_up_to = '24';
}

$creation_timestamp = strtotime( $product->get_date_created() );
$current_timestamp  = strtotime( date_i18n( 'Y-m-d H:i:s' ) );
$show_up_to         = strtotime( '1970-01-01 +' . $show_up_to . ' hours' );
$i                  = $current_timestamp - $creation_timestamp;

if ( $i >= $show_up_to ) {
	return;
}

$icon = sp_render_icon( $args['icon'], array( 'aria-hidden' => 'true' ) );

if ( isset( $args['icon_pos'], $icon ) && 'before' == $args['icon_pos'] ) {
	$before_icon = $icon;
}

if ( isset( $args['icon_pos'], $icon ) && 'after' == $args['icon_pos'] ) {
	$after_icon = $icon;
}

?>

<div class="sp-new-badge">
	<?php echo wp_kses_post( $before_icon ); ?>
	<span class="label"><?php echo esc_html( $label ); ?></span>
	<?php echo wp_kses_post( $after_icon ); ?>
</div>

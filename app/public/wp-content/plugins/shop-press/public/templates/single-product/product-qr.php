<?php
/**
 * Product Qr Code.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product ) {
    return;
}

$qr_size = $arg['size'];

// Use actual QR code on frontend
$product_url = get_permalink( $product->get_id() );
$qr_code_url = 'https://api.qrserver.com/v1/create-qr-code/?size=' . $qr_size . 'x' . $qr_size . '&data=' . urlencode( $product_url );

?>
<div class="sp-qr-code">
    <img 
        src="<?php echo esc_url( $qr_code_url ); ?>" 
        alt="<?php echo esc_attr( $product->get_name() ); ?> QR Code" 
        width="<?php echo esc_attr( $qr_size ); ?>"
        height="<?php echo esc_attr( $qr_size ); ?>"
    />
</div>
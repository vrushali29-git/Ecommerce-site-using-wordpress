<?php
/**
 * Wishlist share popup
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div id="sp-wishlist-login-popup" class="sp-popup-overlay" style="<?php echo ! $open ? 'display:none;' : ''; ?>">
	<div class="sp-popup-content">
		<div class="sp-close-popup"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" > <path d="M9.79.21a.717.717,0,0,1,0,1.014L5.956,5.057,9.674,8.776A.717.717,0,0,1,8.66,9.79L4.942,6.072l-3.6,3.6A.717.717,0,0,1,.326,8.66l3.6-3.6L.21,1.34A.717.717,0,0,1,1.224.326L4.942,4.043,8.776.21A.717.717,0,0,1,9.79.21Z" fill="#7f8da0" /> </svg></div>
		<h3 class="sp-wishlist-popup-title"><?php esc_html_e( 'Add to Wishlist', 'shop-press' ); ?></h3>
		<p class="sp-wishlist-login-notice"><?php esc_html_e( 'Please log in to add an item to your Wishlist.', 'shop-press' ); ?></p>
		<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>" class="sp-wishlist-login-link"><?php esc_html_e( 'My Account', 'shop-press' ); ?></a>
	</div>
</div>

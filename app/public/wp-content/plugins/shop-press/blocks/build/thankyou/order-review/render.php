<?php
/**
 * Thank You Order Review.
 *
 * @package ShopPress
 */

if ( is_checkout() ) {
	sp_load_builder_template( 'thankyou/order-review' );
}

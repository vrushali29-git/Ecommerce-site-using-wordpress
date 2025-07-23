<?php

defined( 'ABSPATH' ) || exit;

global $product;

get_header();

if ( post_password_required() ) {

	echo get_the_password_form(); // WPCS: XSS ok.

	return;
}

if ( have_posts() ) {
	while ( have_posts() ) :
		the_post();
		do_action( 'woocommerce_before_single_product' );
		?>
			<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
				<?php do_action( 'shoppress_single' ); ?>
			</div>
		<?php
		do_action( 'woocommerce_after_single_product' );
	endwhile;
}

get_footer();

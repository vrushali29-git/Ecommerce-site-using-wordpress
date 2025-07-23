<?php
/**
 * Editor single product template
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $product;

echo '<div id="shoppress-wrap" class="shoppress-wrap container">';

if ( have_posts() ) {
	while ( have_posts() ) :
		the_post();
		do_action( 'woocommerce_before_single_product' );
		?>
			<div id="product-<?php the_ID(); ?>" class="product">
				<?php the_content(); ?>
			</div>
		<?php
		do_action( 'woocommerce_after_single_product' );
	endwhile;
}

echo '</div>';

get_footer();

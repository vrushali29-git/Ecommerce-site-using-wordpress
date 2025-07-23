<?php
/**
 * Full Template
 *
 * @package ShopPress\Templates
 */

get_header(); ?>

<div id="primary" class="sp-full-template">
	<?php
	while ( have_posts() ) :
		the_post();

		do_action( 'shoppress_full_page_before' );

		the_content();

		do_action( 'shoppress_full_page_after' );
	endwhile;
	?>
</div>

<?php
get_footer();

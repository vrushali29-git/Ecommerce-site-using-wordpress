<?php
/**
 * Editor single template
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

get_header();

echo '<div id="shoppress-wrap" class="shoppress-wrap container">';

if ( have_posts() ) {
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
}

echo '</div>';
get_footer();

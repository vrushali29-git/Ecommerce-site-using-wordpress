<?php
/**
 * Blog Posts widget view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$the_query = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $posts_per_page,
		'paged'          => get_query_var( 'paged' ) ? esc_html( get_query_var( 'paged' ) ) : 1,
	)
);

if ( ! empty( $title ) ) {
	echo wp_kses_post( $before_title ) . apply_filters( 'widget_title', $title ) . wp_kses_post( $after_title );
}
?>

<div class="kata-blog-posts-widget">
	<?php if ( $the_query->have_posts() ) : ?>
		<?php
		while ( $the_query->have_posts() ) :
			$the_query->the_post();
			?>
			<div class="kata-blog-post">
				<!-- Post thumbnail -->
				<div class="kata-post-thumbnail">
					<?php
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_thumbnail();
					}
					?>
				</div>
				<!-- Post content -->
				<div class="kata-post-content">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_title( '<h3 class="kata-post-title">', '</h3>', true ); ?>
					</a>
					<i class="kata-icon"><!--?xml version="1.0" encoding="utf-8"?--> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="17" height="17" viewBox="0 0 17 17"> <g> </g> <path d="M8.666 0c-4.687 0-8.5 3.813-8.5 8.5s3.813 8.5 8.5 8.5 8.5-3.813 8.5-8.5c0-4.687-3.813-8.5-8.5-8.5zM8.666 16c-4.136 0-7.5-3.364-7.5-7.5s3.364-7.5 7.5-7.5 7.5 3.364 7.5 7.5-3.364 7.5-7.5 7.5zM14 9v1h-6v-5h1v4h5z" fill="#000000"></path> </svg> </i>
					<?php
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_date();
					}
					?>
				</div>
			</div> <!-- end .kata-blog-post -->
		<?php endwhile; ?>
	<?php endif; ?>
</div>

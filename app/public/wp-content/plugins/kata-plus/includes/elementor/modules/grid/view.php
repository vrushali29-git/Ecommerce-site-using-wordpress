<?php

/**
 * Grid module view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Utils;

$settings = $this->get_settings();
$this->add_inline_editing_attributes( 'kata_plus_grid_load_more_text' );
$grid_mode                       = $settings['kata_plus_grid_mode'];
$items_count                     = $settings['kata_plus_grid_settings_items']['size'];
$items_count_tablet              = isset( $settings['kata_plus_grid_settings_items_tablet']['size'] ) ? $settings['kata_plus_grid_settings_items_tablet']['size'] : '2';
$items_count_mobile              = isset( $settings['kata_plus_grid_settings_items_mobile']['size'] ) ? $settings['kata_plus_grid_settings_items_mobile']['size'] : '1';
$settings['categories_all_item'] = isset( $settings['categories_all_item'] ) && '' != $settings['categories_all_item'] ? $settings['categories_all_item'] : 'All';
$tablet_items                    = $items_count_tablet ? 't-grid-col-' . $items_count_tablet . '' : null;
$mobile_items                    = $items_count_mobile ? 'm-grid-col-' . $items_count_mobile . '' : null;
$desktop_items                   = $items_count ? 'd-grid-col-' . $items_count . '' : null;
$thumbnail_width                 = $settings['thumbnail_size']['width'] ? $settings['thumbnail_size']['width'] : '150';
$thumbnail_height                = $settings['thumbnail_size']['height'] ? $settings['thumbnail_size']['height'] : '150';
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}
$posts_per_page = $settings['posts_per_page'] ? $settings['posts_per_page'] : -1;

if ( $grid_mode == 'all_posts' ) {
	// Posts
	$args  = array(
		'post_type'      => 'kata_grid',
		'post_status'    => 'publish',
		'order'          => 'DESC',
		'posts_per_page' => $posts_per_page,
	);
	$posts = new WP_Query( $args );
} else {
	$categories_mode = $settings['kata_plus_grid_categories_mode'];
	if ( $categories_mode == 'all' ) {
		if ( $settings['kata_plus_grid_show_posts'] == 'all' ) {
			$args  = array(
				'post_type'      => 'kata_grid',
				'post_status'    => 'publish',
				'order'          => 'DESC',
				'posts_per_page' => $posts_per_page,
			);
			$posts = new WP_Query( $args );
		} else {
			$args  = array(
				'post_type'      => 'kata_grid',
				'post_status'    => 'publish',
				'order'          => 'DESC',
				'post__in'       => $settings['kata_plus_grid_posts'],
				'posts_per_page' => $posts_per_page,
			);
			$posts = new WP_Query( $args );
		}
	} else {
		$cats  = $settings['kata_plus_grid_categories'];
		$args  = array(
			'post_type'      => 'kata_grid',
			'post_status'    => 'publish',
			'order'          => 'DESC',
			'grid_category'  => implode( ',', $cats ),
			'posts_per_page' => $posts_per_page,
		);
		$posts = new WP_Query( $args );
	}
}

if ( $grid_mode != 'all_posts' && $settings['kata_plus_grid_categories_mode'] == 'custom' ) {
	$cats  = $settings['kata_plus_grid_categories'];
	$terms = array();
	foreach ( $cats as $c ) {
		$terms[] = get_term_by( 'slug', $c, 'grid_category' );
	}
} else {
	$terms = get_terms( 'grid_category' );
}
if ( isset( $posts ) ) :
	if ( $items_count > $posts->post_count ) {
		$items_count = $posts->post_count;
	}
	?>
	<div class="kata-plus-grid-wrap">
		<?php if ( $settings['kata_plus_grid_show_item_categories'] == 'yes' ) : ?>
			<div class="filters masonry-category-filters">
				<span data-filter=".all" class="cat-item dbg-color-h active"><?php echo esc_html( $settings['categories_all_item'] ); ?></span>
				<?php foreach ( $terms as $term ) : ?>
					<span data-filter=".<?php echo $term->slug; ?>" class="cat-item dbg-color-h"><?php echo $term->name; ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif ?>
		<div class="kata-plus-grid <?php echo $settings['kata_plus_grid_show_modal'] == 'modal' ? ' grid-lightgallery' : ''; ?>">
			<?php
			if ( $posts->have_posts() ) :
				while ( $posts->have_posts() ) :

					$post            = $posts->the_post();
					$category_detail = wp_get_object_terms( get_the_ID(), 'grid_category' );
					$cats            = 'all';
					foreach ( $category_detail as $cd ) {
						$cats .= ' ';
						$cats .= $cd->slug;
					}
					?>
					<div class="kata-grid-item <?php echo esc_attr( $cats . ' ' . $desktop_items . ' ' . $tablet_items . ' ' . $mobile_items ); ?>">
						<a href="<?php the_permalink(); ?>">
							<div class="grid-image kata-lazyload" title="<?php the_title(); ?>" <?php echo esc_attr( $settings['kata_plus_grid_show_modal'] ) == 'modal' ? 'data-src="' . get_the_post_thumbnail_url() . '"' : ''; ?>>
								<div class="grid-overlay"></div>
								<?php
								Kata_Plus_Helpers::image_resize_output( get_post_thumbnail_id(), array( $thumbnail_width, $thumbnail_height ) );
								?>
							</div>
						</a>
						<div class="kata-grid-content-wrap<?php echo $settings['kata_plus_grid_show_modal'] == 'modal' ? ' open-modal' : ''; ?>">
							<?php if ( $settings['kata_plus_grid_show_title'] == 'yes' ) : ?>
								<h4 class="grid-title"><?php the_title( '<a href="' . get_the_permalink() . '">', '</a>', true ); ?></h4>
							<?php endif ?>
							<?php if ( $settings['kata_plus_grid_show_date'] == 'yes' ) : ?>
								<div class="grid-date"><?php echo get_the_date(); ?></div>
							<?php endif ?>
							<?php if ( $settings['kata_plus_grid_show_item_categories'] == 'yes' ) : ?>
								<div class="grid-item-category"><?php echo get_the_term_list( get_the_ID(), 'grid_category', '', '<span class="separator">' . $settings['categories_seperator'] . '</span>', '' ); ?></div>
							<?php endif ?>
							<?php if ( $settings['kata_plus_grid_show_excerpt'] == 'yes' ) : ?>
								<p class="grid-excerpt"><?php echo get_the_excerpt(); ?></p>
							<?php endif ?>
							<?php foreach ( $settings['kata_plus_grid_posts_icons'] as $icon ) : ?>
								<?php $url = Kata_Plus_Helpers::get_link_attr( $icon['kata_plus_grid_post_link'] ); ?>
								<?php if ( $icon['post_has_link'] ) : ?>
									<a <?php echo $url->src; ?> class="kata-plus-grid-post-icon elementor-repeater-item-<?php echo esc_attr( $icon['_id'] ); ?>" <?php echo $url->rel . $url->target; ?>>
										<?php $data_id = isset( $icon['styler_grid_nav_post_icon']['citem'] ) ? 'data-item-id="' . esc_attr( $icon['styler_grid_nav_post_icon']['citem'] ) . '"' : ''; ?>
										<?php echo Kata_Plus_Helpers::get_icon( '', $icon['kata_plus_grid_post_icon'], '', $data_id ); ?>
									</a>
								<?php else : ?>
									<?php $cname = isset( $icon['styler_grid_nav_post_icon']['citem'] ) ? $icon['styler_grid_nav_post_icon']['citem'] : ''; ?>
									<?php echo Kata_Plus_Helpers::get_icon( '', $icon['kata_plus_grid_post_icon'], 'kata-plus-grid-post-icon open-modal elementor-repeater-item-' . esc_attr( $icon['_id'] ), 'data-item-id="' . esc_attr( $cname ) . '"' ); ?>
								<?php endif ?>
							<?php endforeach ?>
							<?php foreach ( $settings['kata_plus_grid_posts_elements'] as $element ) : ?>
								<div class="open-modal elementor-repeater-item-<?php echo esc_attr( $element['_id'] ); ?>" data-item-id="<?php echo isset( $element['styler_grid_nav_post_element']['citem'] ) ? esc_attr( $element['styler_grid_nav_post_element']['citem'] ) : ''; ?>"></div>
							<?php endforeach ?>
						</div>
					</div>
					<?php
				endwhile;
			else :
				echo '<h5>' . __( 'You need portfolio item to use this widget.', 'kata-plus-pro' ) . '</h5>';
				echo '<p>' . __( 'Please create a new portfolio and also add the featured image to be previewed in the widget', 'kata-plus-pro' ) . '</p>';
			endif;
			wp_reset_postdata();
			?>
		</div>
		<?php
		if ( $settings['kata_plus_grid_load_more'] == 'yes' ) :
			$text = ! empty( $settings['kata_plus_grid_load_more_text'] ) ? $settings['kata_plus_grid_load_more_text'] : '';
			$url  = Kata_Plus_Helpers::get_link_attr( $settings['kata_plus_grid_load_more_link'] );
			?>
			<?php if ( $text ) : ?>
				<div class="kata-plus-grid-button-wrapper">
					<a href="<?php echo esc_url( $url->src, Kata_Plus_Helpers::ssl_url() ); ?>" class="kata-plus-grid-button" <?php echo $url->rel . $url->target; ?>>
						<?php if ( $settings['kata_plus_grid_load_more_icon_position'] == 'before' ) : ?>
							<?php if ( $settings['kata_plus_grid_load_more_icon'] ) : ?>
								<span class="kata-plus-button-icon kata-plus-align-icon-left">
									<?php echo Kata_Plus_Helpers::get_icon( '', $settings['kata_plus_grid_load_more_icon'], '', 'aria-hidden="true"' ); ?>
								</span>
							<?php endif ?>
						<?php endif ?>

						<span <?php echo $this->get_render_attribute_string( 'kata_plus_grid_load_more_text' ); ?>><?php echo $settings['kata_plus_grid_load_more_text']; ?></span>
						<?php if ( $settings['kata_plus_grid_load_more_icon_position'] == 'after' ) : ?>
							<?php if ( $settings['kata_plus_grid_load_more_icon'] ) : ?>
								<span class="kata-plus-button-icon kata-plus-align-icon-right">
									<?php echo Kata_Plus_Helpers::get_icon( '', $settings['kata_plus_grid_load_more_icon'], '', 'aria-hidden="true"' ); ?>
								</span>
							<?php endif ?>
						<?php endif ?>
					</a>
				</div>
			<?php endif; ?>
		<?php endif ?>
	</div>
	<?php
endif;
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}

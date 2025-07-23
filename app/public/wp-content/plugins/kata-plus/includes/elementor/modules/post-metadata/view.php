<?php

/**
 * Post Metadata module view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = $this->get_settings();
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}
$latest_post    = get_posts( 'post_type=post&numberposts=1' );
$latest_post_id = get_post_type() == 'kata_plus_builder' ? $latest_post[0]->ID : get_the_ID();
?>
<div class="kata-postmetadata">
	<?php
	// Post categories
	if ( $settings['categories'] ) {
		$output = array();
		// Defining taxonomy
		if ( get_post_type() == 'post' || get_post_type() == 'kata_plus_builder' ) {
			$taxonomy = 'category';
		} elseif ( get_post_type() == 'kata_grid' ) {
			$taxonomy = 'grid_category';
		}
		if ( isset( $taxonomy ) ) {
			$post_categories = wp_get_post_terms( $latest_post_id, $taxonomy );
		}

		if ( isset( $post_categories ) ) {
			foreach ( $post_categories as $post_category ) {
				/* translators: %s is replaced width $post_category->name */
				$output[] = '<a href="' . esc_url( get_category_link( $post_category ) ) . '" title="' . esc_attr( sprintf( __( 'View all posts in %s', 'kata-plus' ), $post_category->name ) ) . '">' . esc_html( $post_category->name ) . '</a>';
			}
			if ( $output ) {
				echo '<span class="kata-category-links">' . Kata_Plus_Helpers::get_icon( '', $settings['cat_icon'] ) . ' ' . implode( $settings['cat_seprator'], $output ) . '</span>';
			}
		}
	}

	// Post tags
	if ( $settings['tags'] ) {
		$tags_list = get_the_tag_list( '', ' ', '', $latest_post_id );
		if ( $tags_list ) {
			echo '<span class="kata-tags-links">
				' . Kata_Plus_Helpers::get_icon( '', $settings['tags_icon'] ) . ' ' . wp_kses_post( $tags_list ) . '
			</span>';
		}
	}

	// Post date
	if ( $settings['date'] ) {
		$time_string = sprintf(
			'<time class="kata-entry-date" datetime="%1$s">%2$s</time>',
			esc_attr( get_the_date( DATE_W3C, $latest_post_id ) ),
			esc_html( get_the_date( null, $latest_post_id ) )
		);

		$posted_on = is_single() ? $time_string : '<a href="' . esc_url( get_permalink( $latest_post_id ) ) . '" rel="bookmark">' . $time_string . '</a>';

		echo '<span class="kata-post-date">' . Kata_Plus_Helpers::get_icon( '', $settings['date_icon'], 'df-fill' ) . $posted_on . '</span>'; // WPCS: XSS OK.
	}

	// Post comments
	if ( $settings['comments'] ) {
		if ( comments_open( $latest_post_id ) || get_comments_number( $latest_post_id ) ) {
			echo '<span class="kata-comments-number">' . Kata_Plus_Helpers::get_icon( '', $settings['comments_icon'], 'df-fill' ) . '<span>' . get_comments_number( $latest_post_id ) . '</span></span>';
		}
	}

	// Post author
	if ( $settings['author'] ) {
		$post_author_id     = get_post_field( 'post_author', $latest_post_id );
		$post_author_avatar = $settings['author_avatar'] ? get_avatar( $post_author_id, $settings['author_avatar_size']['size'], '', esc_html( get_the_author_meta( 'display_name', $post_author_id ) ) ) : Kata_Plus_Helpers::get_icon( '', $settings['author_icon'], 'df-fill' );
		echo '<span class="kata-post-author">
				' . $post_author_avatar .
				'<a href="' . esc_url( get_author_posts_url( $post_author_id ) ) . '">
					' . esc_html( get_the_author_meta( 'display_name', $post_author_id ) ) . '
				</a>
			</span>';
	}

	// Post author
	if ( $settings['like'] ) {
		zilla_likes();
	}

	if ( $settings['post_share_count'] ) {
		if ( class_exists( 'Kata_Template_Tags' ) ) {
			Kata_Template_Tags::post_share_count( Kata_Plus_Helpers::get_icon( '', $settings['post_share_count_icon'], 'df-fill' ) );
		}
	}
	if ( $settings['post_view'] ) {
		if ( class_exists( 'Kata_Template_Tags' ) ) {
			Kata_Template_Tags::post_view( Kata_Plus_Helpers::get_icon( '', $settings['post_view_icon'], 'df-fill' ) );
		}
	}
	if ( $settings['post_time_to_read'] ) {
		if ( class_exists( 'Kata_Template_Tags' ) ) {
			$id = \Elementor\Plugin::$instance->editor->is_edit_mode() ? Kata_Plus_Helpers::get_latest_post_id() : get_the_ID();
			Kata_Template_Tags::post_time_to_read( Kata_Plus_Helpers::get_icon( '', $settings['post_time_to_read_icon'], 'df-fill' ), $id );
		}
	}
	?>
</div>
<?php
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}

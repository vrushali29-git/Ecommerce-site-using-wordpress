<?php
/**
 * Recent Posts module view.
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
$this->add_inline_editing_attributes( 'posts_read_more_text' );
$this->add_inline_editing_attributes( 'first_post_read_more_text' );
// Posts variables
$posts_columns             = $settings['posts_columns'] ? absint( $settings['posts_columns'] ) : 2;
$posts_columns_mobile      = isset( $settings['posts_columns_mobile'] ) ? absint( $settings['posts_columns_mobile'] ) : '';
$posts_columns_tablet      = isset( $settings['posts_columns_tablet'] ) ? absint( $settings['posts_columns_tablet'] ) : '';
$posts_per_page            = $settings['posts_per_page'];
$posts_excerpt_length      = $settings['posts_excerpt_length']['size'] ? $settings['posts_excerpt_length']['size'] + 1 : 25;
$posts_read_more_text      = $settings['posts_read_more_text'] ? $settings['posts_read_more_text'] : esc_html__( 'Read More', 'kata-plus' );
$left_thumbnail_class      = $settings['posts_thumbnail_position'] == 'before-title' && $settings['posts_thumbnail_layout_position'] == 'left' || $settings['posts_thumbnail_layout_position'] == 'right' ? ' col-sm-4' : '';
$left_thumbnail_post_class = $settings['posts_thumbnail_position'] == 'before-title' && $settings['posts_thumbnail_layout_position'] == 'left' || $settings['posts_thumbnail_layout_position'] == 'right' ? ' col-sm-8' : '';
$thumbnail_width           = $settings['posts_thumbnail_size'] == 'custom' && $settings['posts_thumbnail_custom_size'] ? $settings['posts_thumbnail_custom_size']['width'] : '';
$thumbnail_height          = $settings['posts_thumbnail_size'] == 'custom' && $settings['posts_thumbnail_custom_size'] ? $settings['posts_thumbnail_custom_size']['height'] : '';
// First Post variables
$first_post_left_thumbnail_class      = $settings['first_post_thumbnail_position'] == 'before-title' && $settings['first_post_thumbnail_layout_position'] == 'left' ? ' col-sm-4' : '';
$first_post_left_thumbnail_post_class = $settings['first_post_thumbnail_position'] == 'before-title' && $settings['first_post_thumbnail_layout_position'] == 'left' ? ' col-sm-8' : '';
$first_post_excerpt_length            = $settings['first_post_excerpt_length']['size'] ? $settings['first_post_excerpt_length']['size'] + 1 : 25;
$first_post_read_more_text            = $settings['first_post_read_more_text'] ? $settings['first_post_read_more_text'] : esc_html__( 'Read More', 'kata-plus' );
$x                                    = 0;
$first_post_thumbnail_width           = $settings['first_post_thumbnail_size'] == 'custom' && $settings['first_post_thumbnail_custom_size'] ? $settings['first_post_thumbnail_custom_size']['width'] : '';
$first_post_thumbnail_height          = $settings['first_post_thumbnail_size'] == 'custom' && $settings['first_post_thumbnail_custom_size'] ? $settings['first_post_thumbnail_custom_size']['height'] : '';
// Carousel Settings variables
$carousel_classes             = $settings['enable_carousel'] ? ' kata-owl owl-carousel owl-theme ' . $settings['inc_owl_pag_num'] : '';
$settings['inc_owl_arrow']    = $settings['inc_owl_arrow'] == 'true' ? 'true' : 'false';
$settings['inc_owl_pag']      = $settings['inc_owl_pag'] == 'true' && $settings['inc_owl_pag_num'] != 'dots-and-num' ? 'true' : 'false';
$settings['inc_owl_loop']     = $settings['inc_owl_loop'] == 'true' ? 'true' : 'false';
$settings['inc_owl_autoplay'] = $settings['inc_owl_autoplay'] == 'true' ? 'true' : 'false';
$settings['inc_owl_center']   = $settings['inc_owl_center'] == 'yes' ? 'true' : 'false';
$settings['inc_owl_vert']     = $settings['inc_owl_vert'] == 'true' ? 'true' : 'false';
$animateout                   = $settings['inc_owl_vert'] == 'true' ? 'fadeOutUp' : '';
$ignore_port_format           = $settings['ignore_port_format'] == 'yes' ? true : false;
$animatein                    = $settings['inc_owl_vert'] == 'true' ? 'fadeInUp' : '';
$settings['inc_owl_rtl']      = $settings['inc_owl_rtl'] == 'true' ? 'true' : 'false';

$settings['inc_owl_stgpad_tablet']['size'] = isset( $settings['inc_owl_stgpad_tablet']['size'] ) ? $settings['inc_owl_stgpad_tablet']['size'] : '0';
$settings['inc_owl_stgpad_mobile']['size'] = isset( $settings['inc_owl_stgpad_mobile']['size'] ) ? $settings['inc_owl_stgpad_mobile']['size'] : '0';
$settings['inc_owl_margin_tablet']['size'] = isset( $settings['inc_owl_margin_tablet']['size'] ) ? $settings['inc_owl_margin_tablet']['size'] : '0';
$settings['inc_owl_margin_mobile']['size'] = isset( $settings['inc_owl_margin_mobile']['size'] ) ? $settings['inc_owl_margin_mobile']['size'] : '0';
$slide_speed                               = $settings['inc_owl_spd']['size'];

$settings['date_format_1'] = 'yes' == $settings['custom_date_format'] && isset( $settings['date_format_1'] ) && ! empty( $settings['date_format_1'] ) ? $settings['date_format_1'] : '';
$settings['date_format_2'] = 'yes' == $settings['custom_date_format'] && isset( $settings['date_format_2'] ) && ! empty( $settings['date_format_2'] ) ? $settings['date_format_2'] : '';
$settings['date_format_3'] = 'yes' == $settings['custom_date_format'] && isset( $settings['date_format_3'] ) && ! empty( $settings['date_format_3'] ) ? $settings['date_format_3'] : '';

$settings['first_post_date_format_1'] = 'yes' == $settings['first_post_custom_date_format'] && isset( $settings['first_post_date_format_1'] ) && ! empty( $settings['first_post_date_format_1'] ) ? $settings['first_post_date_format_1'] : '';
$settings['first_post_date_format_2'] = 'yes' == $settings['first_post_custom_date_format'] && isset( $settings['first_post_date_format_2'] ) && ! empty( $settings['first_post_date_format_2'] ) ? $settings['first_post_date_format_2'] : '';
$settings['first_post_date_format_3'] = 'yes' == $settings['first_post_custom_date_format'] && isset( $settings['first_post_date_format_3'] ) && ! empty( $settings['first_post_date_format_3'] ) ? $settings['date_format_3'] : '';

if ( $settings['first_post'] && ! $settings['enable_carousel'] ) {
	++$posts_per_page;
}

$post_class_mobile = '';
$post_class_tablet = '';

if ( $settings['enable_carousel'] ) {
	$post_class = '';
} else {
	switch ( $posts_columns ) {
		case 1:
			$post_class = 'posts-columns-1 ';
			break;
		case 2:
			$post_class = 'posts-columns-2 ';
			break;
		case 3:
			$post_class = 'posts-columns-3 ';
			break;
		case 4:
			$post_class = 'posts-columns-4 ';
			break;
		case 5:
			$post_class = 'posts-columns-5 ';
			break;
		case 6:
			$post_class = 'posts-columns-6 ';
			break;
	}
	switch ( $posts_columns_tablet ) {
		case 1:
			$post_class_tablet = 'posts-columns-t-1 ';
			break;
		case 2:
			$post_class_tablet = 'posts-columns-t-2 ';
			break;
		case 3:
			$post_class_tablet = 'posts-columns-t-3 ';
			break;
		case 4:
			$post_class_tablet = 'posts-columns-t-4 ';
			break;
		case 5:
			$post_class_tablet = 'posts-columns-t-5 ';
			break;
		case 6:
			$post_class_tablet = 'posts-columns-t-6 ';
			break;
	}
	switch ( $posts_columns_mobile ) {
		case 1:
			$post_class_mobile = 'posts-columns-m-1 ';
			break;
		case 2:
			$post_class_mobile = 'posts-columns-m-2 ';
			break;
		case 3:
			$post_class_mobile = 'posts-columns-m-3 ';
			break;
		case 4:
			$post_class_mobile = 'posts-columns-m-4 ';
			break;
		case 5:
			$post_class_mobile = 'posts-columns-m-5 ';
			break;
		case 6:
			$post_class_mobile = 'posts-columns-m-6 ';
			break;
	}
}

// Query variables
$query_categories = $settings['query_categories'];
$last_category    = end( $query_categories );
$category_name    = '';
foreach ( $query_categories as $key => $value ) {
	$category_name .= $value;
	if ( $value != $last_category ) {
		$category_name .= ', ';
	}
}
$query_tags = $settings['query_tags'];
$last_tag   = end( $query_tags );
$tag_name   = '';
foreach ( $query_tags as $key => $value ) {
	$tag_name .= $value;
	if ( $value != $last_tag ) {
		$tag_name .= ', ';
	}
}

if ( get_query_var( 'paged' ) ) {
	$paged = esc_html( get_query_var( 'paged' ) );
} elseif ( get_query_var( 'page' ) ) {
	$paged = esc_html( get_query_var( 'page' ) );
} else {
	$paged = 1;
}
if ( $settings['posts_pagination'] && ! $settings['enable_carousel'] ) {
	$the_query = new \WP_Query(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $posts_per_page,
			'paged'          => $paged,
			'category_name'  => $category_name,
			'tag'            => $tag_name,
			'orderby'        => $settings['query_order_by'],
			'order'          => $settings['query_order'],
		)
	);
} else {
	$the_query = new \WP_Query(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $posts_per_page,
			'category_name'  => $category_name,
			'tag'            => $tag_name,
			'orderby'        => $settings['query_order_by'],
			'order'          => $settings['query_order'],
		)
	);
}

// start copy
if ( $settings['enable_carousel'] ) {
	$owl = array();
	$owl['inc_owl_item'] = ! empty( $settings['inc_owl_item'] ) ? $settings['inc_owl_item'] : '3';
	$owl['inc_owl_item_laptop'] = ! empty( $settings['inc_owl_item_laptop'] ) ? $settings['inc_owl_item_laptop'] : $owl['inc_owl_item'];
	$owl['inc_owl_item_tablet_extra'] = ! empty( $settings['inc_owl_item_tablet_extra'] ) ? $settings['inc_owl_item_tablet_extra'] : $owl['inc_owl_item_laptop'];
	$owl['inc_owl_item_tablet'] = ! empty( $settings['inc_owl_item_tablet'] ) ? $settings['inc_owl_item_tablet'] : $owl['inc_owl_item_tablet_extra'];
	$owl['inc_owl_item_mobile_extra'] = ! empty( $settings['inc_owl_item_mobile_extra'] ) ? $settings['inc_owl_item_mobile_extra'] : $owl['inc_owl_item_tablet'];
	$owl['inc_owl_item_mobile'] = ! empty( $settings['inc_owl_item_mobile'] ) ? $settings['inc_owl_item_mobile'] : $owl['inc_owl_item_mobile_extra'];
	?>
	<div class="kata-blog-posts row<?php echo esc_attr( $carousel_classes ); ?>"
	data-inc_owl_items="<?php echo esc_attr( json_encode( $owl ) ); ?>"
	data-inc_owl_spd="<?php echo esc_attr( $slide_speed ); ?>"
	data-inc_owl_smspd="<?php echo esc_attr( $settings['inc_owl_smspd']['size'] ); ?>"
	data-inc_owl_stgpad="<?php echo esc_attr( $settings['inc_owl_stgpad']['size'] ); ?>"
	data-inc_owl_stgpadtab="<?php echo esc_attr( $settings['inc_owl_stgpad_tablet']['size'] ); ?>"
	data-inc_owl_stgpadmob="<?php echo esc_attr( $settings['inc_owl_stgpad_mobile']['size'] ); ?>"
	data-inc_owl_margin="<?php echo esc_attr( $settings['inc_owl_margin']['size'] ); ?>"
	data-inc_owl_margintab="<?php echo esc_attr( $settings['inc_owl_margin_tablet']['size'] ); ?>"
	data-inc_owl_marginmob="<?php echo esc_attr( $settings['inc_owl_margin_mobile']['size'] ); ?>"
	data-inc_owl_arrow="<?php echo esc_attr( $settings['inc_owl_arrow'] ); ?>"
	data-inc_owl_pag="<?php echo esc_attr( $settings['inc_owl_pag'] ); ?>"
	data-inc_owl_loop="<?php echo esc_attr( $settings['inc_owl_loop'] ); ?>"
	data-inc_owl_autoplay="<?php echo esc_attr( $settings['inc_owl_autoplay'] ); ?>"
	data-inc_owl_center="<?php echo esc_attr( $settings['inc_owl_center'] ); ?>"
	data-animatein="<?php echo esc_attr( $animatein ); ?>"
	data-animateout="<?php echo esc_attr( $animateout ); ?>"
	data-inc_owl_prev="<?php echo base64_encode( Kata_Plus_Helpers::get_icon( '', $settings['inc_owl_prev'], '', '' ) ); ?>"
	data-inc_owl_nxt="<?php echo base64_encode( Kata_Plus_Helpers::get_icon( '', $settings['inc_owl_nxt'], '', '' ) ); ?>"
	data-inc_owl_rtl="<?php echo esc_attr( $settings['inc_owl_rtl'] ); ?>">
	<?php
} else {
	echo '<div class="kata-blog-posts row">';
}

if ( $the_query->have_posts() ) :
	while ( $the_query->have_posts() ) :
		$the_query->the_post();

		if ( $settings['first_post'] && $x == 0 && ! $settings['enable_carousel'] ) {
			++$x;
			?>
			<div <?php post_class( 'kata-blog-post kata-first-post' ); ?> >
			<?php

			if ( $settings['first_post_thumbnail_position'] == 'before-title' && $settings['first_post_thumbnail_layout_position'] == 'left' ) {
				echo '<div class="row">';
			}

			// First Post Thumbnail
			if ( $settings['first_post_thumbnail_position'] == 'before-title' ) {
				if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() && $settings['first_post_thumbnail'] ) {
					echo '<div class="kata-post-thumbnail kata-lazyload' . esc_attr( $first_post_left_thumbnail_class ) . '">';
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						if ( $settings['first_post_thumbnail_size'] == 'custom' ) {
							if ( $settings['first_post_thumbnail_custom_size']['width'] && $settings['first_post_thumbnail_custom_size']['height'] ) {
								$alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ? ' alt=' . get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) . '' : ' alt';
								echo '<a href="' . get_the_permalink() . '">';
								echo '<img src="' . Kata_Plus_Helpers::image_resize( get_post_thumbnail_id(), array( $first_post_thumbnail_width, $first_post_thumbnail_height ) ) . '"' . esc_attr( $alt ) . '>';
								echo '</a>';
							}
						} else {
							Kata_Template_Tags::post_thumbnail();
						}
					}
					echo '</div>';
				}
			}

			echo '<div class="kata-post-content' . esc_attr( $first_post_left_thumbnail_post_class ) . '">';

			// First Post Meta (before title)
			if ( $settings['first_post_post_format_position'] == 'before-title'
				|| $settings['first_post_categories_position'] == 'before-title'
				|| $settings['first_post_tags_position'] == 'before-title'
				|| $settings['first_post_date_position'] == 'before-title'
				|| $settings['first_post_comments_position'] == 'before-title'
				|| $settings['first_post_time_to_read_position'] == 'before-title'
				|| $settings['first_post_share_count_position'] == 'before-title'
				|| $settings['first_post_view_position'] == 'before-title'
				|| $settings['first_post_author_position'] == 'before-title' ) {
					echo '<div class="kata-post-metadata before-title">';
				if ( $settings['first_post_post_format'] && $settings['first_post_post_format_position'] == 'before-title' ) {
					if ( class_exists( 'Kata_Plus_Helpers' ) ) {
						Kata_Plus_Helpers::post_format_icon();
					}
				}
				if ( $settings['first_post_categories'] && $settings['first_post_categories_position'] == 'before-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_categories( ' ', Kata_Plus_Helpers::get_icon( '', $settings['first_post_category_icon'], 'df-fill' ) );
					}
				}
				if ( $settings['first_post_tags'] && $settings['first_post_tags_position'] == 'before-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_tags();
					}
				}
				if ( $settings['first_post_date'] && $settings['first_post_date_position'] == 'before-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_date( Kata_Plus_Helpers::get_icon( '', $settings['first_post_date_icon'], 'df-fill' ), $settings['first_post_date_format_1'], $settings['first_post_date_format_2'], $settings['first_post_date_format_3'] );
					}
				}
				if ( $settings['first_post_comments'] && $settings['first_post_comments_position'] == 'before-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_comments_number( Kata_Plus_Helpers::get_icon( '', $settings['first_post_comments_icon'], 'df-fill' ) );
					}
				}
				if ( $settings['first_post_time_to_read'] && $settings['first_post_time_to_read_position'] == 'before-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_time_to_read( Kata_Plus_Helpers::get_icon( '', $settings['first_post_time_to_read_icon'], 'df-fill' ) );
					}
				}
				if ( $settings['first_post_share_count'] && $settings['first_post_share_count_position'] == 'before-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_share_count( Kata_Plus_Helpers::get_icon( '', $settings['first_post_share_count_icon'], 'df-fill' ) );
					}
				}
				if ( $settings['first_post_view'] && $settings['first_post_view_position'] == 'before-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_view( Kata_Plus_Helpers::get_icon( '', $settings['first_post_view_icon'], 'df-fill' ) );
					}
				}
				if ( $settings['first_post_author'] && $settings['first_post_author_position'] == 'before-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_author( Kata_Plus_Helpers::get_icon( '', $settings['first_post_author_icon'], 'df-fill' ), $settings['first_post_author_symbol'], $settings['first_avatar_size'] );
					}
				}
					echo '</div>'; // end .kata-post-metadata (.kata-clearfix)
			}

			// First Post Title
			if ( $settings['first_post_title'] ) {
				?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<?php the_title( '<' . Kata_Plus_Helpers::whitelist_html_tags( $settings['first_post_title_tag'], 'h3' ) . ' class="kata-post-title">', '</' . Kata_Plus_Helpers::whitelist_html_tags( $settings['first_post_title_tag'], 'h3' ) . '>', true ); ?>
				</a>
				<?php
			}

			// First Post Thumbnail
			if ( $settings['first_post_thumbnail_position'] == 'after-title' ) {
				if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() && $settings['first_post_thumbnail'] ) {
					echo '<div class="kata-post-thumbnail kata-lazyload">';
					if ( $settings['first_post_thumbnail_size'] == 'custom' ) {
						$alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ? ' alt=' . get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) . '' : ' alt';
						echo '<a href="' . get_the_permalink() . '">';
						echo '<img src="' . Kata_Plus_Helpers::image_resize( get_post_thumbnail_id(), array( $first_post_thumbnail_width, $first_post_thumbnail_height ) ) . '"' . esc_attr( $alt ) . '>';
						echo '</a>';
					} else {
						if ( class_exists( 'Kata_Template_Tags' ) ) {
							Kata_Template_Tags::post_thumbnail();
						}
					}
					echo '</div>';
				}
			}

			// First Post Meta (after title)
			if ( $settings['first_post_post_format_position'] == 'after-title'
			|| $settings['first_post_categories_position'] == 'after-title'
			|| $settings['first_post_tags_position'] == 'after-title'
			|| $settings['first_post_date_position'] == 'after-title'
			|| $settings['first_post_comments_position'] == 'after-title'
			|| $settings['first_post_time_to_read_position'] == 'after-title'
			|| $settings['first_post_share_count_position'] == 'after-title'
			|| $settings['first_post_view_position'] == 'after-title'
			|| $settings['first_post_author_position'] == 'after-title' ) {
				echo '<div class="kata-post-metadata after-title">';
				if ( $settings['first_post_post_format'] && $settings['first_post_post_format_position'] == 'after-title' ) {
					if ( class_exists( 'Kata_Plus_Helpers' ) ) {
						Kata_Plus_Helpers::post_format_icon();
					}
				}
				if ( $settings['first_post_categories'] && $settings['first_post_categories_position'] == 'after-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_categories();
					}
				}
				if ( $settings['first_post_tags'] && $settings['first_post_tags_position'] == 'after-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_tags();
					}
				}
				if ( $settings['first_post_date'] && $settings['first_post_date_position'] == 'after-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_date( Kata_Plus_Helpers::get_icon( '', $settings['first_post_date_icon'], 'df-fill' ), $settings['first_post_date_format_1'], $settings['first_post_date_format_2'], $settings['first_post_date_format_3'] );
					}
				}
				if ( $settings['first_post_comments'] && $settings['first_post_comments_position'] == 'after-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_comments_number( Kata_Plus_Helpers::get_icon( '', $settings['first_post_comments_icon'], 'df-fill' ) );
					}
				}
				if ( $settings['first_post_time_to_read'] && $settings['first_post_time_to_read_position'] == 'after-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_time_to_read( Kata_Plus_Helpers::get_icon( '', $settings['first_post_time_to_read_icon'], 'df-fill' ) );
					}
				}
				if ( $settings['first_post_share_count'] && $settings['first_post_share_count_position'] == 'after-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_share_count( Kata_Plus_Helpers::get_icon( '', $settings['first_post_share_count_icon'], 'df-fill' ) );
					}
				}
				if ( $settings['first_post_view'] && $settings['first_post_view_position'] == 'after-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_view( Kata_Plus_Helpers::get_icon( '', $settings['first_post_view_icon'], 'df-fill' ) );
					}
				}
				if ( $settings['first_post_author'] && $settings['first_post_author_position'] == 'after-title' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_author( Kata_Plus_Helpers::get_icon( '', $settings['first_post_author_icon'], 'df-fill' ), $settings['first_post_author_symbol'], $settings['first_avatar_size'] );
					}
				}
				echo '</div>'; // end .kata-post-metadata (.kata-clearfix)
			}

			// First Post Excerpt(content)
			if ( $settings['first_post_excerpt'] ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					echo '<p class="kata-post-excerpt">' . Kata_Template_Tags::excerpt( $first_post_excerpt_length, $ignore_port_format ) . '</p>';
				}
			}

			// First Post Meta (after excerpt)
			if ( $settings['first_post_post_format_position'] == 'after-excerpt'
				|| $settings['first_post_categories_position'] == 'after-excerpt'
				|| $settings['first_post_tags_position'] == 'after-excerpt'
				|| $settings['first_post_date_position'] == 'after-excerpt'
				|| $settings['first_post_comments_position'] == 'after-excerpt'
				|| $settings['first_post_time_to_read_position'] == 'after-excerpt'
				|| $settings['first_post_share_count_position'] == 'after-excerpt'
				|| $settings['first_post_view_position'] == 'after-excerpt'
				|| $settings['first_post_author_position'] == 'after-excerpt' ) {
					echo '<div class="kata-post-metadata after-excerpt">';
				if ( $settings['first_post_post_format'] && $settings['first_post_post_format_position'] == 'after-excerpt' ) {
					if ( class_exists( 'Kata_Plus_Helpers' ) ) {
						Kata_Plus_Helpers::post_format_icon();
					}
				}
				if ( $settings['first_post_categories'] && $settings['first_post_categories_position'] == 'after-excerpt' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_categories();
					}
				}
				if ( $settings['first_post_tags'] && $settings['first_post_tags_position'] == 'after-excerpt' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_tags();
					}
				}
				if ( $settings['first_post_date'] && $settings['first_post_date_position'] == 'after-excerpt' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_date( Kata_Plus_Helpers::get_icon( '', $settings['first_post_date_icon'], 'df-fill' ), $settings['first_post_date_format_1'], $settings['first_post_date_format_2'], $settings['first_post_date_format_3'] );
					}
				}
				if ( $settings['first_post_comments'] && $settings['first_post_comments_position'] == 'after-excerpt' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_comments_number( Kata_Plus_Helpers::get_icon( '', $settings['first_post_comments_icon'], 'df-fill' ) );
					}
				}
				if ( $settings['first_post_time_to_read'] && $settings['first_post_time_to_read_position'] == 'after-excerpt' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_time_to_read( Kata_Plus_Helpers::get_icon( '', $settings['first_post_time_to_read_icon'], 'df-fill' ) );
					}
				}
				if ( $settings['first_post_share_count'] && $settings['first_post_share_count_position'] == 'after-excerpt' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_share_count( Kata_Plus_Helpers::get_icon( '', $settings['first_post_share_count_icon'], 'df-fill' ) );
					}
				}
				if ( $settings['first_post_view'] && $settings['first_post_view_position'] == 'after-excerpt' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_view( Kata_Plus_Helpers::get_icon( '', $settings['first_post_view_icon'], 'df-fill' ) );
					}
				}
				if ( $settings['first_post_author'] && $settings['first_post_author_position'] == 'after-excerpt' ) {
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						Kata_Template_Tags::post_author( Kata_Plus_Helpers::get_icon( '', $settings['first_post_author_icon'], 'df-fill' ), $settings['first_post_author_symbol'], $settings['first_avatar_size'] );
					}
				}
					echo '</div>'; // end .kata-post-metadata (.kata-clearfix)
			}
			// First Post Read More
			if ( $settings['first_post_read_more'] ) {
				echo '<a class="kata-post-readmore elementor-inline-editing" ' . $this->get_render_attribute_string( 'first_post_read_more_text' ) . ' href="' . esc_url( get_permalink() ) . '">' . esc_html( $first_post_read_more_text ) . '</a>';
			}
			echo '</div>'; // end .kata-blog-post

			if ( $settings['first_post_thumbnail_position'] == 'before-title' && $settings['first_post_thumbnail_layout_position'] == 'left' ) {
				echo '</div>';
			}

			echo '</div>';
			continue;
		}

		?>
		<div <?php post_class( 'kata-blog-post ' . $post_class . $post_class_tablet . $post_class_mobile ); ?>>
		<?php

		if ( $settings['posts_thumbnail_position'] == 'before-title' ) {
			if ( $settings['posts_thumbnail_layout_position'] == 'left' || $settings['posts_thumbnail_layout_position'] == 'right' ) {
				echo '<div class="row">';
			}
		}

		// Post Thumbnail
		if ( $settings['posts_thumbnail_position'] == 'before-title' ) {
			if ( $settings['posts_thumbnail_layout_position'] == 'left' || $settings['posts_thumbnail_layout_position'] == 'top' ) {
				if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() && $settings['posts_thumbnail'] ) {
					echo '<div class="kata-post-thumbnail kata-lazyload' . esc_attr( $left_thumbnail_class ) . '">';
					if ( class_exists( 'Kata_Template_Tags' ) ) {
						if ( $settings['posts_thumbnail_size'] == 'custom' ) {
							if ( ! empty( $thumbnail_width ) && ! empty( $thumbnail_height ) ) {
								$alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ? ' alt=' . get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) . '' : ' alt';
								echo '<a href="' . get_the_permalink() . '">';
								echo '<img src="' . Kata_Plus_Helpers::image_resize( get_post_thumbnail_id(), array( $thumbnail_width, $thumbnail_height ) ) . '"' . esc_attr( $alt ) . '>';
								echo '</a>';
							}
						} else {
							Kata_Template_Tags::post_thumbnail();
						}
					}
					echo '</div>';
				}
			}
		}

		echo '<div class="kata-post-content' . esc_attr( $left_thumbnail_post_class ) . '">';

			// Post Meta (before title)
		if ( $settings['post_format_position'] == 'before-title'
				|| $settings['posts_categories_position'] == 'before-title'
				|| $settings['posts_tags_position'] == 'before-title'
				|| $settings['posts_date_position'] == 'before-title'
				|| $settings['posts_comments_position'] == 'before-title'
				|| $settings['post_time_to_read_position'] == 'before-title'
				|| $settings['post_share_count_position'] == 'before-title'
				|| $settings['post_view_position'] == 'before-title'
				|| $settings['posts_author_position'] == 'before-title' ) {
				echo '<div class="kata-post-metadata before-title">';
			if ( $settings['post_format'] && $settings['post_format_position'] == 'before-title' ) {
				if ( class_exists( 'Kata_Plus_Helpers' ) ) {
							Kata_Plus_Helpers::post_format_icon();
				}
			}
			if ( $settings['posts_categories'] && $settings['posts_categories_position'] == 'before-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_categories( ' ', Kata_Plus_Helpers::get_icon( '', $settings['posts_category_icon'], 'df-fill' ) );
				}
			}
			if ( $settings['posts_tags'] && $settings['posts_tags_position'] == 'before-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_tags();
				}
			}
			if ( $settings['posts_date'] && $settings['posts_date_position'] == 'before-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_date( Kata_Plus_Helpers::get_icon( '', $settings['posts_date_icon'], 'df-fill' ), $settings['date_format_1'], $settings['date_format_2'], $settings['date_format_3'] );
				}
			}
			if ( $settings['posts_comments'] && $settings['posts_comments_position'] == 'before-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_comments_number( Kata_Plus_Helpers::get_icon( '', $settings['posts_comments_icon'], 'df-fill' ) );
				}
			}
			if ( $settings['post_time_to_read'] && $settings['post_time_to_read_position'] == 'before-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_time_to_read( Kata_Plus_Helpers::get_icon( '', $settings['post_time_to_read_icon'], 'df-fill' ) );
				}
			}
			if ( $settings['post_share_count'] && $settings['post_share_count_position'] == 'before-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_share_count( Kata_Plus_Helpers::get_icon( '', $settings['post_share_count_icon'], 'df-fill' ) );
				}
			}
			if ( $settings['post_view'] && $settings['post_view_position'] == 'before-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_view( Kata_Plus_Helpers::get_icon( '', $settings['post_view_icon'], 'df-fill' ) );
				}
			}
			if ( $settings['posts_author'] && $settings['posts_author_position'] == 'before-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_author( Kata_Plus_Helpers::get_icon( '', $settings['posts_author_icon'], 'df-fill' ), $settings['post_author_symbol'], $settings['avatar_size'] );
				}
			}
					echo '</div>'; // end .kata-post-metadata (.kata-clearfix)
		}

			// Post Title
		if ( $settings['posts_title'] ) {
			?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
				<?php the_title( '<' . Kata_Plus_Helpers::whitelist_html_tags( $settings['posts_title_tag'], 'h3' ) . ' class="kata-post-title">', '</' . Kata_Plus_Helpers::whitelist_html_tags( $settings['posts_title_tag'], 'h3' ) . '>', true ); ?>
				</a>
				<?php
		}

			// Post Thumbnail
		if ( $settings['posts_thumbnail_position'] == 'after-title' ) {
			if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() && $settings['posts_thumbnail'] ) {
				echo '<div class="kata-post-thumbnail kata-lazyload">';
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					if ( $settings['posts_thumbnail_size'] == 'custom' ) {
						$alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ? ' alt=' . get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) . '' : ' alt';
						echo '<a href="' . get_the_permalink() . '">';
						echo '<img src="' . Kata_Plus_Helpers::image_resize( get_post_thumbnail_id(), array( $thumbnail_width, $thumbnail_height ) ) . '"' . esc_attr( $alt ) . '>';
						echo '</a>';
					} else {
						Kata_Template_Tags::post_thumbnail();
					}
				}
				echo '</div>';
			}
		}

			// Post Meta (after title)
		if ( $settings['post_format_position'] == 'after-title'
			|| $settings['posts_categories_position'] == 'after-title'
			|| $settings['posts_tags_position'] == 'after-title'
			|| $settings['posts_date_position'] == 'after-title'
			|| $settings['posts_comments_position'] == 'after-title'
			|| $settings['post_time_to_read_position'] == 'after-title'
			|| $settings['post_share_count_position'] == 'after-title'
			|| $settings['post_view_position'] == 'after-title'
			|| $settings['posts_author_position'] == 'after-title' ) {
			echo '<div class="kata-post-metadata after-title">';
			if ( $settings['post_format'] && $settings['post_format_position'] == 'after-title' ) {
				if ( class_exists( 'Kata_Plus_Helpers' ) ) {
					Kata_Plus_Helpers::post_format_icon();
				}
			}
			if ( $settings['posts_categories'] && $settings['posts_categories_position'] == 'after-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_categories();
				}
			}
			if ( $settings['posts_tags'] && $settings['posts_tags_position'] == 'after-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_tags();
				}
			}
			if ( $settings['posts_date'] && $settings['posts_date_position'] == 'after-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_date( Kata_Plus_Helpers::get_icon( '', $settings['posts_date_icon'], 'df-fill' ), $settings['date_format_1'], $settings['date_format_2'], $settings['date_format_3'] );
				}
			}
			if ( $settings['posts_comments'] && $settings['posts_comments_position'] == 'after-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_comments_number( Kata_Plus_Helpers::get_icon( '', $settings['posts_comments_icon'], 'df-fill' ) );
				}
			}
			if ( $settings['post_time_to_read'] && $settings['post_time_to_read_position'] == 'after-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_time_to_read( Kata_Plus_Helpers::get_icon( '', $settings['post_time_to_read_icon'], 'df-fill' ) );
				}
			}
			if ( $settings['post_share_count'] && $settings['post_share_count_position'] == 'after-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_share_count( Kata_Plus_Helpers::get_icon( '', $settings['post_share_count_icon'], 'df-fill' ) );
				}
			}
			if ( $settings['post_view'] && $settings['post_view_position'] == 'after-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_view( Kata_Plus_Helpers::get_icon( '', $settings['post_view_icon'], 'df-fill' ) );
				}
			}
			if ( $settings['posts_author'] && $settings['posts_author_position'] == 'after-title' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_author( Kata_Plus_Helpers::get_icon( '', $settings['posts_author_icon'], 'df-fill' ), $settings['post_author_symbol'], $settings['avatar_size'] );
				}
			}
				echo '</div>'; // end .kata-post-metadata (.kata-clearfix)
		}

			// Post Excerpt(content)
		if ( $settings['posts_excerpt'] ) {
			if ( class_exists( 'Kata_Template_Tags' ) ) {
				echo '<p class="kata-post-excerpt">' . Kata_Template_Tags::excerpt( $posts_excerpt_length, $ignore_port_format ) . '</p>';
			}
		}

			// Post Meta (after excerpt)
		if ( $settings['post_format_position'] == 'after-excerpt'
				|| $settings['posts_categories_position'] == 'after-excerpt'
				|| $settings['posts_tags_position'] == 'after-excerpt'
				|| $settings['posts_date_position'] == 'after-excerpt'
				|| $settings['posts_comments_position'] == 'after-excerpt'
				|| $settings['post_time_to_read_position'] == 'after-excerpt'
				|| $settings['post_share_count_position'] == 'after-excerpt'
				|| $settings['post_view_position'] == 'after-excerpt'
				|| $settings['posts_author_position'] == 'after-excerpt' ) {
				echo '<div class="kata-post-metadata after-excerpt">';
			if ( $settings['post_format'] && $settings['post_format_position'] == 'after-excerpt' ) {
				if ( class_exists( 'Kata_Plus_Helpers' ) ) {
							Kata_Plus_Helpers::post_format_icon();
				}
			}
			if ( $settings['posts_categories'] && $settings['posts_categories_position'] == 'after-excerpt' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_categories();
				}
			}
			if ( $settings['posts_tags'] && $settings['posts_tags_position'] == 'after-excerpt' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_tags();
				}
			}
			if ( $settings['posts_date'] && $settings['posts_date_position'] == 'after-excerpt' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_date( Kata_Plus_Helpers::get_icon( '', $settings['posts_date_icon'], 'df-fill' ), $settings['date_format_1'], $settings['date_format_2'], $settings['date_format_3'] );
				}
			}
			if ( $settings['posts_comments'] && $settings['posts_comments_position'] == 'after-excerpt' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_comments_number( Kata_Plus_Helpers::get_icon( '', $settings['posts_comments_icon'], 'df-fill' ) );
				}
			}
			if ( $settings['post_time_to_read'] && $settings['post_time_to_read_position'] == 'after-excerpt' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_time_to_read( Kata_Plus_Helpers::get_icon( '', $settings['post_time_to_read_icon'], 'df-fill' ) );
				}
			}
			if ( $settings['post_share_count'] && $settings['post_share_count_position'] == 'after-excerpt' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_share_count( Kata_Plus_Helpers::get_icon( '', $settings['post_share_count_icon'], 'df-fill' ) );
				}
			}
			if ( $settings['post_view'] && $settings['post_view_position'] == 'after-excerpt' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_view( Kata_Plus_Helpers::get_icon( '', $settings['post_view_icon'], 'df-fill' ) );
				}
			}
			if ( $settings['posts_author'] && $settings['posts_author_position'] == 'after-excerpt' ) {
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					Kata_Template_Tags::post_author( Kata_Plus_Helpers::get_icon( '', $settings['posts_author_icon'], 'df-fill' ), $settings['post_author_symbol'], $settings['avatar_size'] );
				}
			}
					echo '</div>'; // end .kata-post-metadata (.kata-clearfix)
		}

			// Post Read More
		if ( $settings['posts_read_more'] ) {
			echo '<a class="kata-post-readmore elementor-inline-editing" ' . $this->get_render_attribute_string( 'posts_read_more_text' ) . ' href="' . esc_url( get_permalink() ) . '">' . esc_html( $posts_read_more_text ) . '</a>';
		}

		echo '</div>'; // end .kata-blog-post

		// Post Thumbnail
		if ( $settings['posts_thumbnail_position'] == 'before-title' && $settings['posts_thumbnail_layout_position'] == 'right' ) {
			if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() && $settings['posts_thumbnail'] ) {
				echo '<div class="kata-post-thumbnail kata-lazyload' . esc_attr( $left_thumbnail_class ) . '">';
				if ( class_exists( 'Kata_Template_Tags' ) ) {
					if ( $settings['posts_thumbnail_size'] == 'custom' ) {
						if ( ! empty( $thumbnail_width ) && ! empty( $thumbnail_height ) ) {
							$alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ? ' alt=' . get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) . '' : ' alt';
							echo '<a href="' . get_the_permalink() . '">';
							echo '<img src="' . Kata_Plus_Helpers::image_resize( get_post_thumbnail_id(), array( $thumbnail_width, $thumbnail_height ) ) . '"' . esc_attr( $alt ) . '>';
							echo '</a>';
						}
					} else {
						if ( class_exists( 'Kata_Template_Tags' ) ) {
							Kata_Template_Tags::post_thumbnail();
						}
					}
				}
				echo '</div>';
			}
		}

		if ( $settings['posts_thumbnail_position'] == 'before-title' ) {
			if ( $settings['posts_thumbnail_layout_position'] == 'left' || $settings['posts_thumbnail_layout_position'] == 'right' ) {
				echo '</div>';
			}
		}

		echo '</div>';

	endwhile;

	// Posts Pagination
	if ( $settings['posts_pagination'] && ! $settings['enable_carousel'] ) {
		echo '<div class="kata-post-pagination">';
			echo paginate_links(
				array(
					'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
					'total'        => $the_query->max_num_pages,
					'current'      => max( 1, $paged ),
					'format'       => '?paged=%#%',
					'show_all'     => false,
					'type'         => 'plain',
					'end_size'     => 2,
					'mid_size'     => 1,
					'prev_next'    => false,
					'prev_text'    => sprintf( '<i></i> %1$s', __( 'Newer Posts', 'kata-plus' ) ),
					'next_text'    => sprintf( '%1$s <i></i>', __( 'Older Posts', 'kata-plus' ) ),
					'add_args'     => false,
					'add_fragment' => '',
				)
			);
		echo '</div>';
	}
	wp_reset_postdata();
endif;

echo '</div> <!-- kata-blog-posts -->';

?>

<?php if ( $settings['inc_owl_pag_num'] == 'dots-and-num' ) { ?>
	<div class="kata-owl-progress-bar"><div class="kata-progress-bar-inner dbg-color"></div></div>
	<?php
}
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
// end copy

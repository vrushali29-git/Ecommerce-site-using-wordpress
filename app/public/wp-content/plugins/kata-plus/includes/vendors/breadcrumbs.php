<?php
/**
 * Breadcrumbs.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'kata_plus_breadcrumbs' ) ) {
	function kata_plus_breadcrumbs( $start = '', $seperator = '' ) {
		$showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
		$delimiter   = isset( $seperator ) && ! empty( $seperator ) ? $seperator : '<i class="kata-icon">&raquo;</i>'; // delimiter between crumbs
		$home        = isset( $start ) && ! empty( $start ) ? $start : __( 'Home', 'kata-plus' ); // text for the 'Home' link
		$showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
		$before      = '<span class="current">'; // tag before the current crumb
		$after       = '</span>'; // tag after the current crumb

		global $post;
		$homeLink = get_bloginfo( 'url' );

		if ( is_home() || is_front_page() ) {
			if ( is_home() ) {
				echo '<div id="kata-crumbs"><a href="' . $homeLink . '">' . $home . '</a>' . $delimiter;
				echo wp_kses_post( $before ) . __( 'Blog', 'kata-plus' ) . wp_kses_post( $after );
				echo '</div>';
			}
		} else {
			echo '<div id="kata-crumbs"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';

			if ( is_category() ) {
				$thisCat = get_category( esc_html( get_query_var( 'cat' ) ), false );
				if ( $thisCat->parent != 0 ) {
					echo get_category_parents( $thisCat->parent, true, ' ' . $delimiter . ' ' );
				}
				echo wp_kses_post( $before ) . __( 'Archive by category "', 'kata-plus' ) . single_cat_title( '', false ) . '"' . wp_kses_post( $after );
			} elseif ( is_search() ) {
				echo wp_kses_post( $before ) . __( 'Search results for "', 'kata-plus' ) . get_search_query() . '"' . wp_kses_post( $after );
			} elseif ( is_day() ) {
				echo '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a> ' . $delimiter . ' ';
				echo '<a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '">' . get_the_time( 'F' ) . '</a> ' . $delimiter . ' ';
				echo wp_kses_post( $before ) . get_the_time( 'd' ) . wp_kses_post( $after );

			} elseif ( is_month() ) {
				echo '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a> ' . $delimiter . ' ';
				echo wp_kses_post( $before ) . get_the_time( 'F' ) . wp_kses_post( $after );
			} elseif ( is_year() ) {
				echo wp_kses_post( $before ) . get_the_time( 'Y' ) . wp_kses_post( $after );
			} elseif ( is_single() && ! is_attachment() ) {
				if ( get_post_type() != 'post' ) {
					$post_type = get_post_type_object( get_post_type() );
					$slug      = $post_type->rewrite;
					echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
					if ( $showCurrent == 1 ) {
						echo ' ' . $delimiter . ' ' . wp_kses_post( $before ) . get_the_title() . wp_kses_post( $after );
					}
				} else {
					$cat  = get_the_category();
					$cat  = $cat[0];
					$cats = get_category_parents( $cat, true, ' ' . $delimiter . ' ' );
					if ( $showCurrent == 0 ) {
						$cats = preg_replace( "#^(.+)\s$delimiter\s$#", '$1', $cats );
					}
					echo wp_kses_post( $cats );
					if ( $showCurrent == 1 ) {
						echo wp_kses_post( $before ) . get_the_title() . wp_kses_post( $after );
					}
				}
			} elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' && ! is_404() ) {
				$post_type = get_post_type_object( get_post_type() );
				echo wp_kses_post( $before ) . $post_type->labels->singular_name . wp_kses_post( $after );
			} elseif ( is_attachment() ) {
				$parent = get_post( $post->post_parent );
				$cat    = get_the_category( $parent->ID );
				$cat    = isset( $cat[0] ) ? $cat[0] : '';
				if ( $cat ) {
					echo get_category_parents( $cat, true, ' ' . $delimiter . ' ' );
					echo '<a href="' . get_permalink( $parent ) . '">' . $parent->post_title . '</a>';
					if ( $showCurrent == 1 ) {
						echo ' ' . $delimiter . ' ' . wp_kses_post( $before ) . get_the_title() . wp_kses_post( $after );
					}
				} elseif ( $showCurrent == 1 ) {
						echo ' ' . wp_kses_post( $before ) . get_the_title() . wp_kses_post( $after );
				}
			} elseif ( is_page() && ! $post->post_parent ) {
				if ( $showCurrent == 1 ) {
					echo wp_kses_post( $before ) . get_the_title() . wp_kses_post( $after );
				}
			} elseif ( is_page() && $post->post_parent ) {
				$parent_id   = $post->post_parent;
				$breadcrumbs = array();
				while ( $parent_id ) {
					$page          = get_page( $parent_id );
					$breadcrumbs[] = '<a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a>';
					$parent_id     = $page->post_parent;
				}
				$breadcrumbs = array_reverse( $breadcrumbs );
				for ( $i = 0; $i < count( $breadcrumbs ); $i++ ) {
					echo wp_kses_post( $breadcrumbs[ $i ] );
					if ( $i != count( $breadcrumbs ) - 1 ) {
						echo ' ' . $delimiter . ' ';
					}
				}
				if ( $showCurrent == 1 ) {
					echo ' ' . $delimiter . ' ' . wp_kses_post( $before ) . get_the_title() . wp_kses_post( $after );
				}
			} elseif ( is_tag() ) {
				echo wp_kses_post( $before ) . __( 'Posts tagged "', 'kata-plus' ) . single_tag_title( '', false ) . '"' . wp_kses_post( $after );
			} elseif ( is_author() ) {
				global $author;
				$userdata = get_userdata( $author );
				echo wp_kses_post( $before ) . __( 'Articles posted by ', 'kata-plus' ) . $userdata->display_name . wp_kses_post( $after );
			} elseif ( is_404() ) {
				echo wp_kses_post( $before ) . __( 'Error 404', 'kata-plus' ) . wp_kses_post( $after );
			}

			if ( get_query_var( 'paged' ) ) {
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
					echo ' (';
				}
				echo __( 'Page' ) . ' ' . esc_html( get_query_var( 'paged' ) );
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
					echo ')';
				}
			}

			echo '</div>';
		}
	} // end kata_plus_breadcrumbs()
}

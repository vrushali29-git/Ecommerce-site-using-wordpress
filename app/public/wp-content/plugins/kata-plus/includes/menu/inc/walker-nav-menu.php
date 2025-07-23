<?php
/**
 * Create HTML list of nav menu input items.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Plugin;

if ( ! class_exists( 'Kata_Plus_Walker_Nav_Menu' ) ) {
	class Kata_Plus_Walker_Nav_Menu extends Walker_Nav_Menu {

		/**
		 * Start the element output. [open li tag]
		 *
		 * @param   string $output Passed by reference. Used to append additional content.
		 * @param   object $item   Menu item data object.
		 * @param   int    $depth  Depth of menu item. May be used for padding.
		 * @param   array  $args   Additional strings.
		 * @return  void
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			// classes
			$classes      = $item->classes ? (array) $item->classes : array();
			$class_names  = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
			$class_names .= is_array( $item->classes ) && in_array( 'current_page_item', $item->classes ) ? ' df-color' : '';
			$class_names .= ' df-color-h';
			$class_names .= $item->object == 'kata_mega_menu' ? ' menu-item-has-children' : '';
			$class_names  = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
			// if menu item was mega menu
			if ( Kata_Plus_Helpers::string_is_contain( $item->url, 'kata_mega_menu' ) ) {
				$item->url = '#';
			}

			// attributes
			$attributes  = '';
			$attributes .= $item->attr_title ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
			$attributes .= $item->target ? ' target="' . esc_attr( $item->target ) . '"' : '';
			$attributes .= $item->xfn ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
			$attributes .= $item->url ? ' href="' . esc_url( $item->url ) . '"' : '';

			// title
			$title = apply_filters( 'the_title', $item->title, $item->ID );
			// description
			$description = ( $item->description ) ? '<small class="menu-description">' . esc_html( $item->description ) . '</small>' : '';
			// icon
			$icon = $item->icon ? Kata_Plus_Helpers::get_icon( '', $item->icon ) : '';
			// has children
			$protocol     = Kata_Plus_Helpers::ssl_url() ? 'https://' : 'http://';
			$has_children = is_array( $item->classes ) && in_array( 'menu-item-has-children', $item->classes ) || $item->object == 'kata_mega_menu' ? '<i class="kata-icon parent-menu-icon"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><title>arrow-down</title><path d="M0.256 8.606c0-0.269 0.106-0.544 0.313-0.75 0.412-0.412 1.087-0.412 1.5 0l14.119 14.119 13.913-13.912c0.413-0.412 1.087-0.412 1.5 0s0.413 1.088 0 1.5l-14.663 14.669c-0.413 0.413-1.088 0.413-1.5 0l-14.869-14.869c-0.213-0.213-0.313-0.481-0.313-0.756z"></path></svg></i>' : '';

			// Start Output
			// title, description
			$before      = isset( $args->before ) ? $args->before : '';
			$after       = isset( $args->after ) ? $args->after : '';
			$link_before = isset( $args->link_before ) ? $args->link_before : '';
			$link_after  = isset( $args->link_after ) ? $args->link_after : '';
			$item_output = $before . '<a ' . $attributes . '>' . $link_before . $icon . $title . $has_children . '</a> ' . $link_after . $description . $after;

			// mega menu
			if ( $item->object == 'kata_mega_menu' ) :
				$post_object     = get_post( 'OBJECT', $item->object_id );
				$mega_menu_cw    = Kata_Helpers::get_meta_box( 'mega_menu_custom_width', $item->object_id, true );
				$f_w_mega_menu   = Kata_Helpers::get_meta_box( 'mega_menu_full_width', $item->object_id, true );
				$mega_menu_class = ( isset( $f_w_mega_menu ) && $f_w_mega_menu == '1' ) ? ' kata-mega-full-width' : ' kata-mega-inherit-width';
				$container       = $mega_menu_class === ' kata-mega-inherit-width' ? 'width: ' . get_theme_mod( 'kata_grid_size_desktop', '1600' ) . 'px;' : '';
				$container       = $mega_menu_cw && $f_w_mega_menu != '1' ? 'width: ' . $mega_menu_cw . 'px;' : $container;
				$item_output    .= '<ul class="sub-menu mega-menu-content ' . esc_attr( $mega_menu_class ) . '" style="' . esc_attr( $container ) . '"><li>' . Plugin::instance()->frontend->get_builder_content_for_display( $item->object_id ) . '</li></ul>';
			endif;

			// render menu item
			// since $output is called by reference we don't need to return anything.
			static $i = 0;
			++$i;
			$output .= '<li id="menu-item-' . esc_attr( $item->ID . '-' . $i ) . '" ' . $class_names . '>';
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	} // class
}

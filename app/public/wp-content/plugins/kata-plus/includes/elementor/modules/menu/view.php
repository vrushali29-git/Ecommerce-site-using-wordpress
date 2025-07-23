<?php

/**
 * Menu module view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings     = $this->get_settings();
$element_id   = $this->get_id();
$triangle     = ( isset( $settings['triangle'] ) && $settings['triangle'] == 'yes' ) ? 'triangle ' : '';
$separator    = ( isset( $settings['separator'] ) && $settings['separator'] == 'yes' ) ? 'separator ' : '';
$responsive   = isset( $settings['respnsive_menu'] ) && $settings['respnsive_menu'] == 'yes' ? ' kata-have-responsive ' : '';
$menu_type    = ( isset( $settings['menu_type'] ) && $settings['menu_type'] == 'horizontal' ) ? 'kata-menu-navigation kata-nav-menu' . $responsive . ' ' : 'kata-menu-vertical kata-nav-menu ';
$hover_effect = isset( $settings['hover_effect'] ) ? $settings['hover_effect'] : '';

if ( $settings['nav_id'] && wp_get_nav_menu_object( $settings['nav_id'] ) != false ) {
	$settings['nav_id'] = wp_get_nav_menu_object( $settings['nav_id'] )->term_id;
}

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}
?>
<div class="kata-menu-wrap">
	<?php
	if ( ! empty( $settings['nav_id'] ) && is_nav_menu( $settings['nav_id'] ) ) {
		if ( wp_get_nav_menu_items( $settings['nav_id'] ) ) {
			echo wp_nav_menu(
				array(
					'menu'        => $settings['nav_id'],
					'container'   => false,
					'menu_id'     => 'kata-menu-navigation-desktop-' . uniqid(),
					'menu_class'  => $menu_type . $separator . $triangle . $hover_effect . ' desktop',
					'depth'       => '5',
					'fallback_cb' => 'wp_page_menu',
					'items_wrap'  => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'echo'        => false,
					'walker'      => new Kata_Plus_Walker_Nav_Menu(),
				)
			);
			if ( isset( $settings['respnsive_menu'] ) && $settings['respnsive_menu'] == 'yes' ) {
				echo Kata_Plus_Helpers::get_icon( '', 'themify/align-justify', 'cm-ham-open-icon', '' );
				echo wp_nav_menu(
					array(
						'menu'        => $settings['nav_id'],
						'container'   => false,
						'menu_id'     => 'kata-responsive-menu-' . uniqid(),
						'menu_class'  => 'kata-menu-vertical kata-nav-menu kata-responsive-menu' . $separator . ' ' . $triangle,
						'depth'       => '5',
						'fallback_cb' => 'wp_page_menu',
						'items_wrap'  => '<div class="kata-responsive-menu-wrap">' . Kata_Plus_Helpers::get_icon( '', 'themify/close', 'cm-ham-close-icon', '' ) . '<ul id="%1$s" class="%2$s">%3$s</ul></div>',
						'echo'        => false,
						'walker'      => new Kata_Plus_Walker_Nav_Menu(),
					)
				);
			}
		} else {
			echo '<p>';
			echo __( 'This navigation menu does not have menu items', 'kata-plus' );
			echo '</p>';
		}
	} else {
		echo '<p>';
		echo __( 'Please select a nav menu', 'kata-plus' );
		echo '</p>';
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

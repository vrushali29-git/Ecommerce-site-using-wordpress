<?php
/**
 * Post Featured Image module view.
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

$thumbnail_url = get_the_post_thumbnail_url( Kata_Plus_Helpers::get_latest_post_id() );
if ( get_post_type() == 'kata_plus_builder' ) {
	$width       = isset( $settings['thumbnail_dimension']['width'] ) ? 'width=' . $settings['thumbnail_dimension']['width'] . '' : '';
	$height      = isset( $settings['thumbnail_dimension']['height'] ) ? 'height=' . $settings['thumbnail_dimension']['height'] . '' : '';
	$latest_post = get_posts( 'post_type=post&numberposts=1' );
	echo '<div class="kata-lazyload">';
	Kata_Plus_Helpers::image_resize_output( get_post_thumbnail_id( $latest_post[0]->ID ), array( $settings['thumbnail_dimension']['width'], $settings['thumbnail_dimension']['height'] ) );
	echo '</div>';
	?>
	<?php
} else {
	echo '<div class="kata-lazyload">';
	Kata_Plus_Helpers::image_resize_output( get_post_thumbnail_id(), array( $settings['thumbnail_dimension']['width'], $settings['thumbnail_dimension']['height'] ) );
	echo '</div>';
}

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}

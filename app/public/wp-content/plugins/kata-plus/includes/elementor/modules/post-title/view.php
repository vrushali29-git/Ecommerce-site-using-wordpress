<?php
/**
 * Post Title module view.
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
$tag      = $settings['tag'];
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}

echo '<' . Kata_Plus_Helpers::whitelist_html_tags( $tag, 'h4' ) . ' class="kata-single-post-title">';
if ( get_post_type() == 'kata_plus_builder' ) {
	$latest_post = get_posts( 'post_type=post&numberposts=1' );
	echo $latest_post[0]->post_title;
} else {
	echo wp_kses_post( get_the_title( Kata_Plus_Helpers::get_latest_post_id() ) );
}
echo '</' . Kata_Plus_Helpers::whitelist_html_tags( $tag, 'h4' ) . '>';

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}

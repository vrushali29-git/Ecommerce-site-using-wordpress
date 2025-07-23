<?php
/**
 * Post Comments module view.
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
$latest_post_id = Kata_Plus_Helpers::get_latest_post_id();

if ( comments_open( $latest_post_id ) || get_comments_number( $latest_post_id ) ) {
	comments_template();
	if ( get_post_type() == 'kata_plus_builder' ) {
		Kata_Plus_Helpers::comments_template();
	}
} else {
	echo '<p class="no-cm">You cannot use comments here, because comments are disabled for this page/post. Please enable page/post comment in settings.</p>';
}

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}

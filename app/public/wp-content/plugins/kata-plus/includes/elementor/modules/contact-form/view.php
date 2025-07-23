<?php

/**
 * Contact Form module view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus Pro
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Utils;

$settings      = $this->get_settings();
$contact_form7 = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}

// Render
echo '<div class="kata-plus-contact-form">';
if ( $contact_form7 ) {
	echo apply_shortcodes( '[contact-form-7 id="' . esc_attr( $settings['contact_form'] ) . '" title="' . esc_attr( get_the_title( $settings['contact_form'] ) ) . '"]' );
} else {
	esc_html_e( 'Please select your desired form', 'kata-plus' );
}
echo '</div>';

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}

<?php
/**
 * Social Share view.
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

	$socials = $settings['socials'];

	$socials_icon = array(
		'facebook'  => $settings['facebook_icon'],
		'twitter'   => $settings['twitter_icon'],
		'linkedin'  => $settings['linkedin_icon'],
		'reddit'    => $settings['reddit_icon'],
		'pinterest' => $settings['pinterest_icon'],
		'email'     => $settings['email_icon'],
	);

	$socials_image = array(
		'facebook'  => $settings['facebook_image'],
		'twitter'   => $settings['twitter_image'],
		'linkedin'  => $settings['linkedin_image'],
		'reddit'    => $settings['reddit_image'],
		'pinterest' => $settings['pinterest_image'],
		'email'     => $settings['email_image'],
	);


	$image_size = $settings['custom_dimension'];

	$icon_image = $settings['icon_image'];
	$style      = 'kt-social-sticky' == $settings['mode'] ? 'style=top:100px;' : '';

	echo '<div class="kata-social-share ' . esc_attr( $settings['mode'] ) . '" ' . esc_attr( $style ) . ' data-id="' . get_the_ID() . '">';
	if ( 'yes' == $settings['shared_count'] ) {
		if ( class_exists( 'Kata_Template_Tags' ) ) {
			Kata_Template_Tags::post_share_count();
		}
	}
	if ( $settings['title'] ) {
		echo '<p class="kata-plus-social-share-title">' . wp_kses_post( $settings['title'] ) . '</p>';
	}
	Kata_Plus_Frontend::kata_plus_social_share( $socials, $socials_icon, $socials_image, $icon_image, $image_size );
	echo '</div>';

	if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
		Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
	}
	if ( isset( $settings['custom_css'] ) ) {
		Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
	}
